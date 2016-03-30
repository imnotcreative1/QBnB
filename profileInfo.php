<!DOCTYPE HTML>
<html>
    <head>
        <title>Welcome to QBnB</title>
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
 include_once 'config.php'; 

        // SELECT query
        $query = "SELECT email, password, phone_num, year, name FROM member WHERE email=?";

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
    ?>

 <?php
    if(isset($_POST['updateBtn']) && isset($_SESSION['email'])){
    // include database connection
    include_once 'config.php'; 

    $query = "UPDATE member SET password=?,phone_num=?, year = ?, name=? WHERE email=?";

    $stmt = $con->prepare($query);  
    $stmt->bind_param('sssss', $_POST['password'], $_POST['phone_num'], $_POST['gradYear'], $_POST['name'], $_SESSION['email']);
    // Execute the query
        if($stmt->execute()){
            //echo "Record was updated. <br/>";
        }else{
            //echo 'Unable to update record. Please try again. <br/>';
        }
    }
    ?>

    <?php
    if (isset($_DELETE['deletePropertyBtn']) && isset($_SESSION['email'])){
    include_once 'config.php';

    $query1 = "";
    $query2 = "";
    $query3 = "";

    $stmt = $con->prepare($query);

    }
    ?>
<div class="center-block container">
    <h3 class = "PinfoHead"> Your Profile Information </h3>
    <div class="center-block container">
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
                <td></td>
                <td>
                    <input type='submit' name='updateBtn' id='updateBtn' value='Update' /> 
                </td>
            </tr>
        </table>
    </form>
</div>
</div>
<div class="center-block container">
    <?php
    echo "<a href=\"/QBnB/cancelMembership.php?member=" . urlencode( $myrow['email']) ."\">Cancel Membership </a>";
    ?>
</div>