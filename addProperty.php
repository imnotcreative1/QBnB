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

if(!isset($_SESSION['email'])){
    //User is not logged in. Redirect the browser to the login index.php page and kill this page.
    header("Location: index.php");
    die();
}
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
            //echo "we made it";
           include_once 'datePeriodConversion.php';
            $month = $_POST['Month'];
            $year = $_POST['Year'];
            $day = $_POST['Day'];
            $address = $_POST['address'];
            $dateFormat= $year . "-" . $month . "-" . $day;
            echo $dateFormat; 
            $query = "INSERT into Availability (period, address) Values (?, ?)";
            $stmt2 = $con->prepare($query);
            //echo $query;
            echo dateToPeriod($dateFormat);
            //echo $address;
            //$aNum = 100;
            $stmt2->bind_param('is', dateToPeriod($dateFormat) ,$address);
            if ($stmt2->execute()){
                header("Location: /QBnB/editProperty.php?propertyAddress=" . urlencode($address));
                //echo "Availability was added. <br/>";
                //echo dateToPeriod($dateFormat);
            }
            else {
                echo "Availability was not added. <br/>";
            }
        }else{
            echo 'Unable to add property. Please try again. <br/>';
        }
 }


 
 ?>
<!-- dynamic content will be here -->

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
                <td>Property Type</td>
                <td><input type='text' name='type' id='type'/></td>
            </tr>
        </table>
        <div> Add Availability </div>
        <table>
        <!--<tr> <td> Day </td>  <td> Month </td> <td> Year </td> </tr>-->
        <tr>
        <td>
            <select name='Month' id='Month'>
              <option value="01">JAN</option>
              <option value="02">FEB</option>
              <option value="03">MAR</option>
              <option value="04">APR</option>
              <option value="05">MAY</option>
              <option value="06">JUNE</option>
              <option value="07">JULY</option>
              <option value="08">AUG</option>
              <option value="09">SEPT</option>
              <option value="10">OCT</option>
              <option value="11">NOV</option>
              <option value="12">DEC</option>
            </select>
        </td>
        <td>
            <select name='Day' id='Day'>
                <option value="01">1</option>
                <option value="02">2</option>
                <option value="03">3</option>
                <option value="04">4</option>
                <option value="05">5</option>
                <option value="06">6</option>
                <option value="07">7</option>
                <option value="08">8</option>
                <option value="09">9</option>
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
        </td>
        <td>
            <input type='value' name='Year' id='Year' value="2016"/>
            <input type='submit' name='createPropertyBtn' id='createPropertyBtn' value='Create' />
        </td>
        </tr>
        </table>
    </form> 
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js"></script>
</html>