<!DOCTYPE HTML>
<html>
    <head>
        <title>Qbnb - Property View</title>
        <!-- jQuery first, then Bootstrap JS. -->
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css">

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
    //grabs property information
    $address = urldecode($_GET['propertyAddress']);

    $allowedToEdit = ($_SESSION['admin']); //Add functionality to compare the email with the property to be edited

    if($allowedToEdit){
    include_once 'config.php';
    //query property details
    $query = "SELECT email, address, price, district_name, rooms, type FROM property WHERE address = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $address);
    //$stmt->bind_param("s", $address);//Uncomment this after testing
    $stmt->execute();
    $result = $stmt->get_result();
    $myrow = $result->fetch_assoc();


    //query booking details
    $query = "SELECT email, booking_status, period from booking natural join availability natural join (select address from property where address = ?) as T" ;
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $address);
    //$stmt->bind_param("s", $address);//Uncomment this after testing
    $stmt->execute();
    $result = $stmt->get_result();
    $bookings = $result;

     //query availability details
    $query = "SELECT period from  availability natural join property where address = ?" ;
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $address);
    //$stmt->bind_param("s", $address);//Uncomment this after testing
    $stmt->execute();
    $result = $stmt->get_result();
    $availabilities = $result;

    //query ratings details
    $query = "SELECT * from  comments natural join member where address = ?" ;
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $address);
    //$stmt->bind_param("s", $address);//Uncomment this after testing
    $stmt->execute();
    $result = $stmt->get_result();
    $comments = $result;

    }
    else {
    //User is not logged in. Redirect the browser to the login index.php page and kill this page.
    header("Location: index.php");
    die();
    }
?>

<!-- dynamic content will be here -->
 <h2 >  
    <?php 
    if ($myrow['email'] === $_SESSION['email'])
        echo "Property @ " . $address  . " owned by "  . "YOU!";
    else
        echo "Property @ " . $address  . " owned by "  . $myrow['email']; 
    //. "!";
    ?>
</h2>

    <form name='newProperty' id='newProperty' method='post'>
        <table border='0'>
            <tr>
                <td>Address</td>
                <td><input type='text' name='address' id='address' value="<?php echo $myrow['address']; ?>" disabled/></td>
            </tr>
            <tr>
                <td>Price</td>
                 <td><input type='value' name='price' id='price' value="<?php echo $myrow['price']; ?>" disabled/></td>
            </tr>
            <tr>
                <td>District</td>
                <td><input type='text' name='district_name' id='district_name' value="<?php echo $myrow['district_name']; ?>" disabled/></td>
            </tr>
             <tr>
                <td>Rooms</td>
                <td><input type='value' name='rooms' id='rooms' value="<?php echo $myrow['rooms']; ?>"/ disabled></td>
            </tr>
             <tr>
                <td>Room(s) Type</td>
                <td><input type='text' name='type' id='type' value="<?php echo $myrow['type']; ?>"disabled /></td>
            </tr>
 
        </table>
    </form>
    

   <div class="col-md-4" id = "BookingsCol">
       <h3 class = "MidHeader"> Bookings</h3>
        <?php
            $bookingEmailArray = array();
            $statusArray = array();
            $periodArray = array();
            while ($row_users = $bookings->fetch_assoc()) {
                array_push($bookingEmailArray, ($row_users['email']));
                array_push($statusArray, ($row_users['booking_status']));
                array_push($periodArray, ($row_users['period']));
            }  
        ?>
        <table class="table table-striped">
            <tr> 
                <th> Email </th>
                <th> Status </th>
                <th> Period </th>
            </tr>
            <?php   
                for ($i = 0; $i < sizeof($bookingEmailArray) ; $i++){
                    echo "<tr>" . "<td>" . $bookingEmailArray[$i] . "</td>";
                    echo "<td>" . $statusArray[$i]. "</td>";
                    echo "<td>" . $periodArray[$i]. "</td>" . "</tr>";
                }
            ?>
        </table>

</div>
 <div class="col-md-4" id = "Availability Column">
       <h3 class = "AvailHeader"> Availabilities</h3>
        <?php
            $availabilityArray = array();
            while ($row_users = $availabilities->fetch_assoc()) {
                array_push($availabilityArray, ($row_users['period']));
            }  
        ?>
        <table class="table table-striped">
            <tr> 
                <th> Period </th>
            </tr>
            <?php   
                for ($i = 0; $i < sizeof($availabilityArray) ; $i++){
                    echo "<tr>" . "<td>" . $availabilityArray[$i] . "</td>". "</tr>";
                }
            ?>
        </table>
</div>
 <div class="col-md-4" id = "Comments Column">
       <h3 class = "commentHeader"> Comments</h3>
        <?php
            $commentArray = array();
            $userArray = array();
            $ratingArray = array();
            $replyArray = array();


            while ($row_users = $comments->fetch_assoc()) {
                array_push($commentArray, ($row_users['comment']));
                array_push($userArray, ($row_users['Name']));
                array_push($ratingArray, ($row_users['property_rating']));
                array_push($replyArray, ($row_users['reply']));
            }  
        ?>
        <table class="table table-striped">
            <tr> 
                <th> Name </th>
                <th> Rating </th>
                <th> Comment </th>
                <th> Reply </th>
            </tr>
            <?php   
                for ($i = 0; $i < sizeof($commentArray) ; $i++){
                    
                    echo "<tr>" . "<td>" . $userArray[$i] . "</td>";
                    echo "<td>" . $ratingArray[$i] . "</td>";
                    echo "<td>" . $commentArray[$i] . "</td>";
                    echo "<td>" . $replyArray[$i] . "</td>" . "</tr>";
                }
            ?>
        </table>
</div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js"></script>
</html>