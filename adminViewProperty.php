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
    $query = "SELECT *  from  comments where address = ?" ;
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $address);
    //$stmt->bind_param("s", $address);//Uncomment this after testing
    $stmt->execute();
    $result = $stmt->get_result();
    $comments = $result;

    } 
?>

<?php
    //Loads the features for the property
    $searchResults = "";
    if($allowedToEdit){
    include_once 'config.php';
    $query = "SELECT property.address, price, district_name, rooms, type 
        FROM property
        INNER JOIN Features
        ON property.address = Features.address
        WHERE property.address = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $address);
    //$stmt->bind_param("s", $address);//Uncomment this after testing
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;

    if ($num > 0){
        echo "Property Loaded";
        $searchResults = $result;

    }
    else {
        echo "Property Failed to Load";
        //header("Location: /QBnB/profile.php"); //Re-Direct if the user isn't valied ********************************************************************
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

<?php
    //load all the comments about the property on the page
    if($allowedToEdit){
        include_once 'config.php';
        $query = "SELECT  * 
            FROM  Comments
            WHERE address = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $address);
        //$stmt->bind_param("s", $address);//Uncomment this after testing
        $stmt->execute();
        $commentResults = $stmt->get_result();
        echo "Comments Loaded";
    } 
    else {
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
            <tr> <td> Features </td> </tr>
             <?php
                if ($searchResults != ""){
                    $address = array();
                    $price = array();
                    $district = array();
                    $rooms = array();
                    $type = array();
                    while ($row_results = $searchResults->fetch_assoc()) {
                        array_push($address, ($row_results['address']));
                        array_push($price, ($row_results['price']));
                        array_push($district, ($row_results['district_name']));
                        array_push($rooms, ($row_results['rooms']));
                        array_push($type, ($row_results['type']));
                    }
                    /*<a href="http://example.com">
                        <div style="height:100%;width:100%">
                          hello world
                        </div>
                      </a>*/
                    for ($i = 0; $i < count($address); $i++){  
                        echo "<tr>
                            <td> 
                                <a href=/QBnB/viewProperty.php?propertyAddress=" . urlencode($address[$i]) . "> 
                                " . $address[$i] . "
                            </td>
                            <td>
                                " . $price[$i] . "
                            </td>
                            <td>
                                " . $district[$i] . "
                            </td>
                            <td>
                                " . $rooms[$i] . "
                            </td> 
                            <td>
                                " . $type[$i] . "
                            </td>     
                        </tr>";
                    }
                }
           ?>
           
        </table>
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
            <tr> <td> Period Available </td> </tr> 
            <?php
                //$count = 0;
                foreach ($periodArray as $p){

                    echo "<tr> <td> <input type='checkbox' name='checkbox[]' value='<?php echo $p;?>' > </td> <td> ". $p . "</td></tr>";
                }
            ?>
            <td> <input type='submit' name='bookBtn' id='bookBtn' value='Book!' /> </td>
        </table>
    </form>
    <tr> <td> Comments </td> </tr>
     <?php
        if ($commentResults != ""){
            $rating = array();
            $comment = array();
            while ($row_results = $commentResults->fetch_assoc()) {
                array_push($rating, ($row_results['property_rating']));
                array_push($comment, ($row_results['comment']));
            }
            /*<a href="http://example.com">
                <div style="height:100%;width:100%">
                  hello world
                </div>
              </a>*/
            for ($i = 0; $i < count($rating); $i++){  
                echo "<tr>
                    <td> 
                        " . $rating[$i] . "
                    </td>
                    <td>
                        " . $comment[$i] . "
                    </td>   
                </tr>";
            }
        }
   ?>

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
            $emailArray = array();
            $ratingArray = array();
            

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
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js"></script>
</html>