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
 ?>
 
 <?php
 $myrow = "";
 $_SESSION['email'] = "12mjs17@queensu.ca";
 if(isset($_POST['createPropertyBtn']) && isset($_SESSION['email'])){
  // include database connection
    include_once 'config.php'; 
    
    $query = "Insert into property Values (?,?, ?, ?, ?, ?)";
 
    $stmt = $con->prepare($query);  
    $stmt->bind_param('ssisis',  $_POST['address'], $_SESSION['email'], $_POST['price'], $_POST['district_name'], $_POST["rooms"], $_POST["type"]);
    // Execute the query
        if($stmt->execute()){
            echo "Property was updated. <br/>";
        }else{
            echo 'Unable to update record. Please try again. <br/>';
        }
 }
 
 ?>
<!-- dynamic content will be here -->
<ul class = "header">
  <li class = "navp"><a href="/QBnB/index.php">Home</a></li>
  <li class = "navp"><a href="/QBnB/profile.php">Profile</a></li>
  <li class = "navp"><a href="/QBnB/search.php">Find a Place</a></li>
  <li class = "navp"><a href="/QBnB/about.php">About</a></li>
</ul>

 <h2 > Add New Property, 
    <a href="index.php?logout=1">Log Out</a><br/>
</h2>
    <form name='newProperty' id='newProperty' action='addProperty.php' method='post'>
        <table border='0'>
            <tr>
                <td>Address</td>
                <td><input type='text' name='address' id='address'  /></td>
            </tr>
            <tr>
                <td>Price</td>
                 <td><input type='value' name='price' id='price'/></td>
            </tr>
            <tr>
                <td>District</td>
                <td><input type='text' name='district_name' id='district_name'/></td>
            </tr>
             <tr>
                <td>Rooms</td>
                <td><input type='value' name='rooms' id='rooms'/></td>
            </tr>
             <tr>
                <td>Room(s) Type</td>
                <td><input type='text' name='type' id='type'/></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type='submit' name='createPropertyBtn' id='createPropertyBtn' value='Update' /> 
                </td>
            </tr>
        </table>
    </form>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js"></script>
</html>