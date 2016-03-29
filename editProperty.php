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
 $address = $_GET['propertyAddress'];
 echo $address;
 $_SESSION['property'] = "testing"; //Delete this after testing ************************************************************************
 $_SESSION['email'] = "12mjs17@queensu.ca"; //Delete this after testing ****************************************************************
 $allowedToEdit = isset($_SESSION['email']); //Add functionality to compare the email with the property to be edited
 //Loading the property to be edited
if($allowedToEdit){
    include_once 'config.php';
    $query = "SELECT address, price, district_name, rooms, type FROM property WHERE address = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $_SESSION['property']);
    //$stmt->bind_param("s", $address);//Uncomment this after testing
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;
    if ($num > 0){
        echo "Property Loaded";
        $myrow = $result->fetch_assoc();

    }
    else {
        echo "Property Failed to Load";
        //header("Location: /QBnB/profile.php"); //Re-Direct if the user isn't valied ********************************************************************
    }

} 
 ?>
 
 <?php
 //process any edits made and update them in the database
 $currentPageURL = "";
 if(isset($_POST['editPropertyBtn']) && isset($_SESSION['email'])){
  // include database connection
    include_once 'config.php'; 
    
    $query = "Insert into property Values (?,?, ?, ?, ?, ?)";
 
    $stmt = $con->prepare($query);  
    $stmt->bind_param('ssisis',  $_POST['address'], $_SESSION['email'], $_POST['price'], $_POST['district_name'], $_POST["rooms"], $_POST["type"]);
    // Execute the query
        if($stmt->execute()){
            echo "Property was updated. <br/>";
            $currentPageURL = $_SERVER['REQUEST_URI'];
            //header("Location: /QBnB/editProperty.php" . ); 
        }else{
            echo 'Unable to update record. Please try again. <br/>';
        }
 }
 ?>
 <?php 
    //Load all availibilites for the property
    $availResult;
    if($allowedToEdit){
        include_once 'config.php';
        $query = "SELECT * from availability where address = ?";
        $stmt = $con->prepare($query); 
        $stmt->bind_param('s', $address);
        if($stmt->execute()){
            $availResult = $stmt->get_result();
            if ($availResult->num_rows > 0){
                echo "Loaded Availibilities...";
            }
            else {
                echo "Failed to load availibilites";
            }
        }
        else {
            echo "Failed to load availibilites";
        }
    }
 ?>
 <?php 
 //add availabiltiy to a property
    if(isset($_POST['addAvailBtn'])){
        include_once 'config.php';
        $query = "INSERT into availability (period, address) values (101, ?)"; //after testing add period calculation *****************

        $stmt = $con->prepare($query);
        $stmt->bind_param('s', $address);

        if($stmt->execute()){
            echo "availability was added";
        }
        else {
            echo "Unable to add availability";
        }
    }
 ?>
 <?php
 //deleting an availability from a property
    if(isset($_POST['deleteAvailBtn'])){
            $name = $_POST['checkbox'];

        if(isset($_POST['checkbox'])) {

            echo "You deleted the following availability(s): <br>";

        foreach ($name as $checkbox){
        echo $checkbox."<br />";

        }

        } // end brace for if(isset

        else {

        echo "You did not choose an availability.";

        }
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

 <h2 > Edit Your Property: <?php 
    //echo $_SESSION['property']?>, 
    <a href="index.php?logout=1">Log Out</a><br/>
</h2>
    <?php echo $currentPageURL; ?>
    <form name='newProperty' id='newProperty' method='post'>
        <table border='0'>
            <tr>
                <td>Address</td>
                <td><input type='text' name='address' id='address' value="<?php echo $myrow['address']; ?>" /></td>
            </tr>
            <tr>
                <td>Price</td>
                 <td><input type='value' name='price' id='price' value="<?php echo $myrow['price']; ?>"/></td>
            </tr>
            <tr>
                <td>District</td>
                <td><input type='text' name='district_name' id='district_name' value="<?php echo $myrow['district_name']; ?>"/></td>
            </tr>
             <tr>
                <td>Rooms</td>
                <td><input type='value' name='rooms' id='rooms' value="<?php echo $myrow['rooms']; ?>"/></td>
            </tr>
             <tr>
                <td>Room(s) Type</td>
                <td><input type='text' name='type' id='type' value="<?php echo $myrow['type']; ?>"/></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type='submit' name='editPropertyBtn' id='editPropertyBtn' value='Update' /> 
                </td>
            </tr>
        </table>
    </form>
    <p> Add Availibilities </p>
    <form name='newAvail' id ='newAvail' method='post'>
        <div> Day \t Month \t Year </div>
        <row>
            <select name='Month' id='Month'>
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
            <select name='Day' id='Day'>
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
            <input type='submit' name='addAvailBtn' id='addAvailBtn' value='Update' /> 
    </form>
    <p> Availiblities </p>
    <?php
        //display id, period, address
        $periodArray = array();
        while ($row_avail = $availResult->fetch_assoc()) {
            array_push($periodArray, ($row_avail['period']));
            //echo "<p> " . $row_avail['period'] . "</p>";
        }
    ?>
    <form name='checkboxes' id ='checkboxes' method='post'>
        <table>
            <tr> <td> Delete </td> <td> Period Available </td> </tr> 
            <?php
                //$count = 0;
                foreach ($periodArray as $p){

                    echo "<tr> <td> <input type='checkbox' name='checkbox[]' value='<?php echo $p;?>' > </td> <td> ". $p . "</td></tr>";
                }
            ?>
            <td> <input type='submit' name='deleteAvailBtn' id='deleteAvailBtn' value='delete' /> </td>
        </table>
    </form>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js"></script>
</html>