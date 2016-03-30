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
 //$_SESSION['email'] = "12mjs17@queensu.ca";
 if(isset($_POST['createPropertyBtn']) && isset($_SESSION['email'])){
  // include database connection
    include_once 'config.php'; 
    
    $query = "INSERT into property Values (?,?, ?, ?, ?, ?)";
 
    $stmt = $con->prepare($query);  
    $stmt->bind_param('ssisis',  $_POST['address'], $_SESSION['email'], $_POST['price'], $_POST['district_name'], $_POST["rooms"], $_POST["type"]);
    // Execute the query
        if($stmt->execute()){
            echo "Property was added. <br/>";
            $query = "INSERT into Availability (period, address) Values (15, ?)";
            $stmt2 = $con->prepare($query);
            echo $query;
            echo $_POST['address'];
            //$aNum = 100;
            $stmt2->bind_param('s', $_POST['address']);
            if ($stmt2->execute()){
                header("Location: /QBnB/profile.php");
                //echo "Availability was added. <br/>";
            }
            else {
                echo "Availability was not added. <br/>";
            }
        }else{
            echo 'Unable to add property. Please try again. <br/>';
        }
 } else {
    //User is not logged in. Redirect the browser to the login index.php page and kill this page.
    header("Location: index.php");
    die();
}

 
 ?>
<!-- dynamic content will be here -->
<nav class = "header">
  <li class = "navp"><a href="/QBnB/index.php">Home</a></li>
  <li class = "navp"><a href="/QBnB/profile.php">Profile</a></li>
  <li class = "navp"><a href="/QBnB/addProperty.php">Become a host</a></li>
  <li class = "navp"><a href="/QBnB/search.php">Find a Place</a></li>
  <li class = "navp"><a href="/QBnB/about.php">About</a></li>
  <li class = "navp"><a href="/QBnB/index.php?logout=1">Log Out</a></li>
</nav>

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
        </table>
        <div> Add Availability </div>
        <div> Day \t Month \t Year </div>
        <row>
            <select>
              <option value="JAN">JAN</option>
              <option value="FEB">FEB</option>
              <option value="MAR">MAR</option>
              <option value="APR">APR</option>
              <option value="MAY">MAY</option>
              <option value="JUNE">JUNE</option>
              <option value="JULY">JULY</option>
              <option value="AUG">AUG</option>
              <option value="SEPT">SEPT</option>
              <option value="OCT">OCT</option>
              <option value="NOV">NOV</option>
              <option value="DEC">DEC</option>
            </select>
            <select>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
                <option value="13">13</option>
                <option value="14">14</option>
                <option value="15">15</option>
                <option value="16">16</option>
                <option value="17">17</option>
                <option value="18">18</option>
                <option value="19">19</option>
                <option value="20">20</option>
                <option value="21">21</option>
                <option value="22">22</option>
                <option value="23">23</option>
                <option value="24">24</option>
                <option value="25">25</option>
                <option value="26">26</option>
                <option value="27">27</option>
                <option value="28">28</option>
                <option value="29">29</option>
                <option value="30">30</option>
                <option value="31">31</option>
            </select>
            <input type='value' name='year' id='year'/>
            <table>
             <tr>
                <td></td>
                <td>
                    <input type='submit' name='createPropertyBtn' id='createPropertyBtn' value='Update' /> 
                </td>
            </tr>
        </table>
        </row>

    </form>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js"></script>
</html>