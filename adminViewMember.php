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
    $email = urldecode($_GET['member']);

    $allowedToEdit = ($_SESSION['admin']); //Add functionality to compare the email with the property to be edited

    if($allowedToEdit){
    include_once 'config.php';
    //query property details
   $query = "SELECT email, password, phone_num, year, name FROM member WHERE email=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $email);
    //$stmt->bind_param("s", $address);//Uncomment this after testing
    $stmt->execute();
    $result = $stmt->get_result();
    $myrow = $result->fetch_assoc();


    //query booking details
    $query = "SELECT address, email, booking_status, period from booking natural join availability natural join (select address from property where email = ?) as T" ;
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $email);
    //$stmt->bind_param("s", $address);//Uncomment this after testing
    $stmt->execute();
    $result = $stmt->get_result();
    $bookings = $result;

     //query availability details
    $query = "SELECT period, address from  availability natural join property where email = ?" ;
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $email);
    //$stmt->bind_param("s", $address);//Uncomment this after testing
    $stmt->execute();
    $result = $stmt->get_result();
    $availabilities = $result;

    //query ratings details
    $query = "SELECT * from  member natural join comments natural join (select address from property where email = ?) as T";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $email);
    //$stmt->bind_param("s", $address);//Uncomment this after testing
    $stmt->execute();
    $result = $stmt->get_result();
    $comments = $result;

        //query ratings details
    $query = "SELECT * from  booking natural join availability where email = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $email);
    //$stmt->bind_param("s", $address);//Uncomment this after testing
    $stmt->execute();
    $result = $stmt->get_result();
    $memberBookings = $result;

    }
    else {
    //User is not logged in. Redirect the browser to the login index.php page and kill this page.
    header("Location: index.php");
    die();
    }
?>

<!-- dynamic content will be here -->


    <h3> Member Information </h3>
    <form name='editProfile' id='editProfile' action='profileInfo.php' method='post'>
        <table border='0'>
            <tr>
                <td>Name</td>
                <td><input type='text' name='name' id='name' value="<?php echo $myrow['name']; ?>"  /></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><input type='text' name='email' id='email' disabled  value="<?php echo $myrow['email']; ?>"  /></td>
            </tr>
            <tr>
                <td>Password</td>
                 <td><input type='text' name='password' id='password'  value="<?php echo $myrow['password']; ?>" /></td>
            </tr>
            <tr>
                <td>Phone Number</td>
                <td><input type='value' name='phone_num' id='phone_num'  value="<?php echo $myrow['phone_num']; ?>" /></td>
            </tr>
             <tr>
                <td>Graduation Year</td>
                <td><input type='value' name='gradYear' id='gradYear'  value="<?php echo $myrow['year']; ?>" /></td>
            </tr>
            <tr>
            </tr>
        </table>
    </form>
    
    <h3> Properties </h3>
    <div class = "row"> 
    <div class="col-md-4" id = "BookingsCol">
       <h3 class = "MidHeader"> Bookings</h3>
        <?php
            $bookingEmailArray = array();
            $statusArray = array();
            $periodArray = array();
            $addressArray = array();
            while ($row_users = $bookings->fetch_assoc()) {
                array_push($bookingEmailArray, ($row_users['email']));
                array_push($statusArray, ($row_users['booking_status']));
                array_push($periodArray, ($row_users['period']));
                array_push($addressArray, ($row_users['address']));
            }  
        ?>
        <table class="table table-striped">
            <tr> 
                <th> Email </th>
                <th> Address </th>
                <th> Status </th>
                <th> Period </th>
                
            </tr>
            <?php   
                for ($i = 0; $i < sizeof($bookingEmailArray) ; $i++){
                    echo "<tr>" . "<td>" . $bookingEmailArray[$i] . "</td>";
                    echo "<td>" . $addressArray[$i]. "</td>";
                    echo "<td>" . $statusArray[$i]. "</td>";
                    echo "<td>" . $periodArray[$i]. "</td>" . "</tr>";
                }
            ?>
        </table>

</div>
 <div class="col-md-4" id = "Availability Column">
       <h3 class = "AvailHeader"> Availabilities</h3>
        <?php
            $addressArray = array();
            $availabilityArray = array();
            while ($row_users = $availabilities->fetch_assoc()) {
                array_push($availabilityArray, ($row_users['period']));
                array_push($addressArray, ($row_users['address']));
            }  
        ?>
        <table class="table table-striped">
            <tr>
                <th> Address </th>
                <th> Period </th>
            </tr>
            <?php   
                for ($i = 0; $i < sizeof($availabilityArray) ; $i++){
                    echo "<tr>" ."<td>" . $addressArray[$i]. "</td>";
                    echo  "<td>" . $availabilityArray[$i] . "</td>". "</tr>";
                }
            ?>
        </table>
</div>
 <div class="col-md-4" id = "Comments Column">
       <h3 class = "commentHeader"> Comments</h3>
        <?php
            $addressArray = array();
            $commentArray = array();
            $userArray = array();
            $ratingArray = array();
            $replyArray = array();


            while ($row_users = $comments->fetch_assoc()) {
                array_push($commentArray, ($row_users['comment']));
                array_push($userArray, ($row_users['Name']));
                array_push($ratingArray, ($row_users['property_rating']));
                array_push($replyArray, ($row_users['reply']));
                array_push($addressArray, ($row_users['address']));
            }  
        ?>
        <table class="table table-striped">
            <tr> 
                <th> Address </th>
                <th> Name </th>
                <th> Rating </th>
                <th> Comment </th>
                <th> Reply </th>
            </tr>
            <?php   
                for ($i = 0; $i < sizeof($commentArray) ; $i++){
                    echo "<tr>" ."<td>" . $addressArray[$i]. "</td>";
                    echo  "<td>" . $userArray[$i] . "</td>";
                    echo "<td>" . $ratingArray[$i] . "</td>";
                    echo "<td>" . $commentArray[$i] . "</td>";
                    echo "<td>" . $replyArray[$i] . "</td>" . "</tr>";
                }
            ?>
        </table>
</div>
</div>

<h3> Bookings </h3>
 <div class="col-md-4" id = "Comments Column">
       <h3 class = "commentHeader"> Properties booked</h3>
        <?php
            $statusArray = array();
            $periodArray = array();
            $addressArray = array();
            while ($row_users = $memberBookings->fetch_assoc()) {
                array_push($statusArray, ($row_users['booking_status']));
                array_push($periodArray, ($row_users['period']));
                array_push($addressArray, ($row_users['address']));
            }  
        ?>
        <table class="table table-striped">
            <tr> 

                <th> Address </th>
                <th> Status </th>
                <th> Period </th>
                
            </tr>
            <?php   
                for ($i = 0; $i < sizeof($statusArray) ; $i++){
                    echo "<tr>" ."<td>" . $addressArray[$i]. "</td>";
                    echo "<td>" . $statusArray[$i]. "</td>";
                    echo "<td>" . $periodArray[$i]. "</td>" . "</tr>";
                }
            ?>
        </table>

</div>
</div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js"></script>
</html>