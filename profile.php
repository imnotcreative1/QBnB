<!DOCTYPE HTML>
<html>
    <head>
        <title>Welcome to QBnB</title>
        <!-- jQuery first, then Bootstrap JS. -->
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

        <!-- Bootstrap Vertical Nav -->
        <link rel="stylesheet" href="stylesheets/bootstrap-vertical-menu.css">
    </head>

<body>
    <?php
    //Create a user session or resume an existing one
    session_start();
       include 'adminTab.php';
    ?>
    <?php

    if(isset($_SESSION['email'])){
    // include database connection
    include_once 'config.php'; 

        // SELECT query
        $query = "SELECT email, password, phone_num, year FROM member WHERE email=?";

        // prepare query for execution
        $stmt = $con->prepare($query);
        
        // bind the parameters. This is the best way to prevent SQL injection hacks.
        $stmt->bind_Param("s", $_SESSION['email']);

        // Execute the query
        $stmt->execute();

        // results 
        $result = $stmt->get_result();
        
        // Row data
        $myrow = $result->fetch_assoc();

        //Make another query when the pages loads to add the list of bookings for a user

        $listBookingsQuery = "SELECT availability.period, booking.booking_status, property.address, property.price from booking
        INNER JOIN availability on booking.id = availability.id INNER JOIN property on availability.address = property.address
        WHERE booking.email = ?";

        $stmt2 = $con->prepare($listBookingsQuery);
        $stmt2->bind_Param("s", $_SESSION['email']);
        $result2 = "";
        if ($stmt2->execute()){
            //echo "success in selecting booking information";
            $result2 = $stmt2->get_result();
        }

        //Make a query when the page loads to add the list of holdings for a user

        $listHoldingQuery = "SELECT T.address, T.price, property_rating from comments right join (select address, price from property where email = ?)as T on T.address = comments.address group by T.address";


        $stmt3 = $con->prepare($listHoldingQuery);

        $stmt3->bind_Param("s", $_SESSION['email']);

        $stmt3->execute();

        $result3 = $stmt3->get_result();
        
    } else {

        //User is not logged in. Redirect the browser to the login index.php page and kill this page.
        header("Location: index.php");
        die();

    }

    ?>

    <!-- dynamic content will be here -->
    <div class="col-md-10">
    <h2 class = "greeting"> Welcome  
    <?php echo $myrow['email']; ?>, 
    <a href="index.php?logout=1">Log Out</a><br/>
    </h2>
    </div>
    <div class="col-md-2">
    <form method='GET' action="/QBnB/profileInfo.php"><input style="width: 8em;height:3em;" type='submit' name='profileInfoBtn' id='profileInfoBtn' value='Edit Profile' /></form>
    </div>
    <div class="container-fluid">
        <div class="col-md-6" class = "profileMidCol">
        
            <h3> List of Your Properties </h3>
            <?php
                $priceArray = array();
                $addressArray = array();
                $ratingArray = array();
                while ($row_users = $result3->fetch_assoc()) {
                    array_push($priceArray, ($row_users['price']));
                    array_push($addressArray, ($row_users['address']));
                    array_push($ratingArray, ($row_users['property_rating']));
                }

            ?>
            <table class="table table-striped">
                <tr> 
                    <th> Address </th>
                    <th> Price </th>
                    <th> Average Rating </th>
                    <th> Options </th>
                    <th></th>
                </tr>
                <?php   
                    for ($i = 0; $i < sizeof($priceArray) ; $i++){
                        echo "<tr>";
                        echo "<td> <a href=\"/QBnB/viewProperty.php?propertyAddress=" . urlencode($addressArray[$i]) . "\">" . $addressArray[$i] . "</td>";
                        echo "<td> " . $priceArray[$i] . "</td>";

                        if (is_null($ratingArray[$i])) echo "<td> No ratings </td>";
                        else echo "<td> " . $ratingArray[$i] . "</td>";
                        echo "<td> <a href=\"/QBnB/editProperty.php?propertyAddress=" . urlencode($addressArray[$i]) . "\">Edit </a> </td>";
                        echo "<td> <a href=\"/QBnB/deleteProperty.php?propertyAddress=" . urlencode($addressArray[$i]) . "\">Delete</a></td>";
                        echo "</tr>";
                    }
                ?>
            </table>

        </div>
        <div class="col-md-6"> 
            <h3 class = "myBookingHeader"> List of Your Bookings </h3>
        <?php
            //display id, period, address
            $priceArray = array();
            $periodArray = array();
            $addressArray = array();
            $status = array();
            while ($row_users = $result2->fetch_assoc()) {
                array_push($priceArray, $row_users['price']);
                array_push($status, ($row_users['booking_status']));
                array_push($periodArray, ($row_users['period']));
                array_push($addressArray, ($row_users['address']));
            }  
        ?>
            <table class="table table-striped">
            <th > Week Staring From </th>
            <th> Address </th>
            <th> Price </th>
            <th> Status </th>
            <th> Options </th> 
                <?php 
                    include_once 'datePeriodConversion.php';
                    for($i = 0; $i < count($periodArray); $i++){
                        echo "<tr>" . "<td>" . printDate(periodToDate($periodArray[$i])) . "</td>"
                                . "<td>" . $addressArray[$i] . "</td>"
                                . "<td>" . $priceArray[$i] . "</td>"
                                . "<td>" . $status[$i] . "</td>";
                                echo "<td> <a href=\"/QBnB/viewProperty.php?propertyAddress=" . urlencode($addressArray[$i]) . "\">Comment/Rate</a>";
                                echo "<td> <a href=\"/QBnB/viewProperty.php?propertyAddress=" . urlencode($addressArray[$i]) . "\">Delete</a>";
                                echo "</tr>";
                    }
                ?>
            </table>
        </div> 
    </div>
</body>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js"></script>

</html>