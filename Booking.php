<!DOCTYPE HTML>
<html>
    <head>
        <title>Welcome to QBnB</title>
        <!-- jQuery first, then Bootstrap JS. -->
        <!-- Bootstrap CSS -->
        <!--link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css"-->

        <!-- Bootstrap Vertical Nav -->
        <!--link rel="stylesheet" href="stylesheets/bootstrap-vertical-menu.css"-->
    </head>
<body>
 <?php
  //Create a user session or resume an existing one
 session_start();
 ?>
 
 <?php
 
 if(isset($_POST['updateBtn']) && isset($_SESSION['email'])){
  // include database connection
    include_once 'config.php'; 
    
    $query = "UPDATE member SET password=?,phone_num=?, year = ? WHERE email=?";
 
    $stmt = $con->prepare($query);  
    $stmt->bind_param('ssss', $_POST['password'], $_POST['phone_num'], $_POST['gradYear'], $_SESSION['email']);
    // Execute the query
        if($stmt->execute()){
            echo "Record was updated. <br/>";
        }else{
            echo 'Unable to update record. Please try again. <br/>';
        }
 }
 
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

        $listBookingsQuery = "SELECT * from booking
        JOIN availability on booking.id = availability.id
        WHERE booking.email = ?";

        $stmt2 = $con->prepare($listBookingsQuery);
        $stmt2->bind_Param("s", $_SESSION['email']);

        $stmt2->execute();

        // results 
        $result2 = $stmt2->get_result();

        //Make a query when the page loads to add the list of holdings for a user

        $listHoldingQuery = "SELECT * FROM property
        JOIN features on property.address = features.address
        WHERE email = ?";

        $stmt3 = $con->prepare($query);

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
<ul class = "header">
  <li class = "navp"><a href="/QBnB/index.php">Home</a></li>
  <li class = "navp"><a href="/QBnB/profile.php">Profile</a></li>
  <li class = "navp"><a href="/QBnB/search.php">Find a Place</a></li>
  <li class = "navp"><a href="/QBnB/about.php">About</a></li>
</ul>

 <h2 class = "greeting"> Welcome  
    <?php echo $myrow['email']; ?>, 
    <a href="index.php?logout=1">Log Out</a><br/>
</h2>
 <div class="col-md-4">
    <h3 class = "PinfoHead"> Your Profile Information </h3>
    <form name='editProfile' id='editProfile' action='profile.php' method='post'>
        <table border='0'>
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
                <td></td>
                <td>
                    <input type='submit' name='updateBtn' id='updateBtn' value='Update' /> 
                </td>
            </tr>
        </table>
    </form>
</div>
<div class="col-md-4" id = "profileMidCol">
    <h3 class = "myBookingHeader"> List of Your Bookings </h3>
    <?php
        //display id, period, address
        $idArray = array();
        $periodArray = array();
        $addressArray = array();
        while ($row_users = $result2->fetch_assoc()) {
            array_push($idArray, ($row_users['id']));
            array_push($periodArray, ($row_users['period']));
            array_push($addressArray, ($row_users['address']));
        }  
    ?>
        <div class="col-md-4"> Id 
            <?php 
                foreach($idArray as $i){
                    echo "<p> </p>";
                    echo "<tr class = \"rowlength\"><td> " . $i . "</td></tr>";
                }
            ?>

        </div>
        <div class="col-md-4"> Period
            <?php 
                foreach($periodArray as $i){
                    echo "<p> </p>";
                    echo "<tr class = \"rowlength\"><td> " . $i . "</td></tr>";
                }
            ?>

        </div>
        <div class="col-md-4"> Address
            <?php 
                foreach($addressArray as $i){
                    echo "<p> </p>";
                    echo "<tr class = \"rowlength\"><td> " . $i . "</td></tr>";
                }
            ?>

        </div>
        <h3> List of Your Holdings </h3>
        <?php
            $idArray = array();
            $priceArray = array();
            $addressArray = array();
            while ($row_users = $result3->fetch_assoc()) {
                array_push($priceArray, ($row_users['price']));
                array_push($districtArray, ($row_users['district_name']));
                array_push($addressArray, ($row_users['address']));
            }  
        ?>
        <div class="col-md-4"> Address
            <?php 
                foreach($addressArray as $i){
                    echo "<p> </p>";
                    echo "<tr class = \"rowlength\"><td> " . $i . "</td></tr>";
                }
            ?>

        </div>
        <div class="col-md-4"> Price
            <?php 
                foreach($priceArray as $i){
                    echo "<p> </p>";
                    echo "<tr class = \"rowlength\"><td> " . $i . "</td></tr>";
                }
            ?>

        </div>
        <div class="col-md-4"> District
            <?php 
                foreach($districtArray as $i){
                    echo "<p> </p>";
                    echo "<tr class = \"rowlength\"><td> " . $i . "</td></tr>";
                }
            ?>

        </div>

</div>
<div class="col-md-4"> 
    <ul> 
        <li> Add Booking Button </li>
        <li> Remove Booking Button </li>
        <li> View Comments </li>
    </ul>
</div> 
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js"></script>
</html>