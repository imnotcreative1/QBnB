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
    //grabs property information
    $address = urldecode($_GET['propertyAddress']);
    //echo "address is " . $address;
    //$_SESSION['property'] = "testing"; //Delete this after testing ************************************************************************
    //$_SESSION['email'] = "12mjs17@queensu.ca"; //Delete this after testing ****************************************************************
    $allowedToView = isset($_SESSION['email']); //Add functionality to compare the email with the property to be edited
    //Loading the property to be edited
    if($allowedToView){
        include_once 'config.php';

        $query = "SELECT email FROM property WHERE address = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $address);
        $stmt->execute();
        $result = $stmt->get_result();
        $property_owner = $result->fetch_assoc();
    

        $query = "SELECT email, address, price, district_name, rooms, type FROM property WHERE address = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $address);
        //$stmt->bind_param("s", $address);//Uncomment this after testing
        $stmt->execute();
        $result = $stmt->get_result();
        $property_info = $result->fetch_assoc();
        
        $query = "SELECT private_bath, shared_bath, close_to_subway, pool, full_kitchen, laundry FROM features WHERE address = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $address);
        $stmt->execute();
        $result = $stmt->get_result();
        $property_features = $result->fetch_assoc();

        $query = "SELECT email FROM availability NATURAL JOIN booking WHERE address = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $address);
        $stmt->execute();
        $result = $stmt->get_result();
        $commenters = $result->fetch_assoc();
    } 
?>

<?php
    //Loads the features for the property
    $searchResults = "";
    if($allowedToView){
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

    } 
?>

<?php 
    //Load all availibilites for the property
    $availResult;
    if($allowedToView){
        include_once 'config.php';
        $query = "SELECT * from availability where address = ?";
        $stmt = $con->prepare($query); 
        $stmt->bind_param('s', $address);
        if($stmt->execute()){
            $availResult = $stmt->get_result();
            if ($availResult->num_rows > 0){
                //echo "Loaded Availibilities...";
            }
            else {
                //echo "Failed to load availibilites";
            }
        }
        else {
            //echo "Failed to load availibilites";
        }
    }
?>

<?php
//load all the comments about the property on the page
if($allowedToView){
    include_once 'config.php';
    $query = "SELECT  * 
        FROM  Comments
        WHERE address = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $address);
    //$stmt->bind_param("s", $address);//Uncomment this after testing
    $stmt->execute();
    $commentResults = $stmt->get_result();
    //echo "Comments Loaded";
} 
else {
    //User is not logged in. Redirect the browser to the login index.php page and kill this page.
    header("Location: index.php");
    die();
}

?>

<?php
if(isset($_POST['submitFeedbackBtn'])){
    include_once 'config.php';

    $query = "INSERT INTO Comments (email, address, property_rating, comment) VALUES (? , ? , ?, ?);";
    $stmt = $con->prepare($query);
    $stmt->bind_param('ssis', $_SESSION['email'], $address, $_POST['Rating'], $_POST['input']);
    $stmt->execute();
}
?>

<?php
if(isset($_POST['replyBtn'])){
    include_once 'config.php';

    $query = "UPDATE comments set address=?,email=?, price=?, district_name=?, rooms=?, type=? where address = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('ssis', $_SESSION['email'], $address, $_POST['Rating'], $_POST['input']);
    $stmt->execute();
}
?>

<?php
//add selected bookings
    //echo "checking time";
    if (isset($_POST['bookBtn'])){
        $bookingIdArray = array();
        include_once 'config.php';
        foreach ($_POST['checkbox'] as $checkPeriod){
            //echo "dfghj";
            //echo "{" . $checkPeriod. "}" . "\n";
            array_push($bookingIdArray, $checkPeriod);
            $idArray = array();
            $queryId = "SELECT availability.id, email, property.address 
                from availability 
                inner join property on availability.address = property.address                
                where availability.period = ? AND availability.address = ?";
            $stmt = $con->prepare($queryId);
            //echo $checkPeriod;
            $stmt->bind_param("is", $checkPeriod, $address);
            if ($stmt->execute()){
                //echo "found booking info </br>";
                $bookResults=$stmt->get_result();
                $bookR=$bookResults->fetch_assoc();
                $insertBooking = "INSERT into booking Values (?, ?, 'REQUESTED');";
                $stmt2 = $con->prepare($insertBooking);
                //echo $bookR['id'] . " " . $bookR['email'];
                //echo "\n" . $insertBooking . "\n" . $bookR['id'] . "\n" . $bookR['email'];
                $stmt2->bind_param("is", $bookR['id'], $bookR['email']);
                if ($stmt2->execute()){
                    //echo "</br> booked it! </br>";

                }
                else {
                    //echo "</br>failed to book</br>";
                }
            }
            else {
                //echo "sht</br>";
            }
        }
        /*$query = "INSERT into booking (id, email, booking_status)
        Values (?, 'REQUESTED')";

        $stmt= $con->prepare($query);
        $stmt->bind_param("s", $_SESSION['email']);
        echo "</br>" . $query . "</br>";
        if($stmt->execute()){
            echo "Succesfully booked";
        }
        else {
            echo "Booking Failed";
        }*/
    }
?>
<!-- dynamic content will be here -->


        <h2 >  
            <?php 
            if ($property_owner['email'] === $_SESSION['email'])
                header("Location: myBookingStatuses.php?propertyAddress=" . urlencode($address));
            else
                echo "Property @ " . $address  . " owned by "  . $property_owner['email']; 
            //. "!";
            ?>
        </h2>
    <form name='newProperty' id='newProperty' method='post'>
        <table border='0'>
            <tr>
                <td>Address</td>
                <td><input type='text' name='address' id='address' value="<?php echo $property_info['address']; ?>" disabled/></td>
            </tr>
            <tr>
                <td>Price</td>
                 <td><input type='value' name='price' id='price' value="<?php echo $property_info['price']; ?>" disabled/></td>
            </tr>
            <tr>
                <td>District</td>
                <td><input type='text' name='district_name' id='district_name' value="<?php echo $property_info['district_name']; ?>" disabled/></td>
            </tr>
             <tr>
                <td>Rooms</td>
                <td><input type='value' name='rooms' id='rooms' value="<?php echo $property_info['rooms']; ?>"/ disabled></td>
            </tr>
             <tr>
                <td>Room(s) Type </td>
                <td><input type='text' name='type' id='type' value="<?php echo $property_info['type']; ?>"disabled /></td>
            </tr>
        </table>
        <h2> Features </h2>
        <?php
            if ($property_features['private_bath'] > 0) echo "<a href=\"#\" class=\"btn btn-success btn-lg disabled\" role=\"button\">Private Bath</a>";
            else echo "<a href=\"#\" class=\"btn btn-danger btn-lg disabled\" role=\"button\">Private Bath</a>";
            if ($property_features['shared_bath'] > 0) echo "<a href=\"#\" class=\"btn btn-success btn-lg disabled\" role=\"button\">Shared Bath</a>";
            else echo "<a href=\"#\" class=\"btn btn-danger btn-lg disabled\" role=\"button\">Shared Bath</a>";
            if ($property_features['close_to_subway'] > 0) echo "<a href=\"#\" class=\"btn btn-success btn-lg disabled\" role=\"button\">Close to Subway</a>";
            else echo "<a href=\"#\" class=\"btn btn-danger btn-lg disabled\" role=\"button\">Close to Subway</a>";
            if ($property_features['pool'] > 0) echo "<a href=\"#\" class=\"btn btn-success btn-lg disabled\" role=\"button\">Pool</a>";
            else echo "<a href=\"#\" class=\"btn btn-danger btn-lg disabled\" role=\"button\">Pool</a>";
            if ($property_features['full_kitchen'] > 0) echo "<a href=\"#\" class=\"btn btn-success btn-lg disabled\" role=\"button\">Full Kitchen</a>";
            else echo "<a href=\"#\" class=\"btn btn-danger btn-lg disabled\" role=\"button\">Full Kitchen</a>";
            if ($property_features['laundry'] > 0) echo "<a href=\"#\" class=\"btn btn-success btn-lg disabled\" role=\"button\">Laundry</a>";
            else echo "<a href=\"#\" class=\"btn btn-danger btn-lg disabled\" role=\"button\">Laundry</a>";
        ?>
             <?php
                if ($searchResults != ""){
                    $address = array();
                    $price = array();
                    $district = array();
                    $rooms = array();
                    $type = array();
                    if ($searchResults->num_rows > 0){
                        while ($row_results = $searchResults->fetch_assoc()) {
                            array_push($address, ($row_results['address']));
                            array_push($price, ($row_results['price']));
                            array_push($district, ($row_results['district_name']));
                            array_push($rooms, ($row_results['rooms']));
                            array_push($type, ($row_results['type']));
                        }
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
    <?php
        //display id, period, address
        $periodArray = array();
        $idArray = array();
        while ($row_avail = $availResult->fetch_assoc()) {
            array_push($periodArray, ($row_avail['period']));
            array_push($idArray, $row_avail['id']);
            //echo "<p> " . $row_avail['period'] . "</p>";
        }
    ?>
    <form name='checkboxes' id ='checkboxes' method='post'>
        <table>
            <tr> <td> Period Available </td> </tr> 
            <?php
                $count = 0;
                foreach ($periodArray as $p){
                    echo "<tr> <td> <input type='checkbox' name='checkbox[]' value=" . $p . " ></td> <td> ". $p . "</td></tr>";
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
            $replies = array();
            while ($row_results = $commentResults->fetch_assoc()) {
                array_push($rating, ($row_results['property_rating']));
                array_push($comment, ($row_results['comment']));
                array_push($replies, ($row_results['reply']));
            }
            echo "<table class = \"table table-bordered table-striped\">
                    <col width = \"10%\">
                    <col width = \"40%\">
                    <col width = \"40%\">
                    <th> Rating </th>
                    <th> Comment </th>";
                    if ($_SESSION['email'] == $property_owner['email']) echo "<th> Reply </th>";
            for ($i = 0; $i < count($rating); $i++){  
                echo "<tr>
                    <td>" . $rating[$i] . "</td>
                    <td>" . $comment[$i] . "</td>";
                if ($replies[$i] == "") {
                    echo "<td> <b> Reply to Comment </b>";
                    echo "<form name='addComment' id='addComment' action='viewProperty.php?propertyAddress=" . urlencode($address) . "' method='post'>
                          <textarea class=\"form-control\" rows=\"3\" name = \"input\"></textarea>                          <input class=\"btn btn-primary\" type='submit' id='replyBtn' name='replyBtn' value='Reply to Comment'/> 
                          </form> </td>";
                } else {       
                    echo "<td>" . $replies[$i] . "</td>";
                }   
                echo "</tr>";
            }
            echo "</table>";
            for ($i = 0; $i < sizeof($commenters); $i++){
                if ($commenters['email'] == $_SESSION['email']){
                    echo "<h2> Submit Feedback </h2>";
                    echo "<form name='addComment' id='addComment' action='viewProperty.php?propertyAddress=" . urlencode($address) . "' method='post'>
                          <textarea class=\"form-control\" rows=\"3\" name = \"input\"></textarea>
                          <select name='Rating' id='Rating'>
                                <option value=\"01\">1</option>
                                <option value=\"02\">2</option>
                                <option value=\"03\">3</option>
                                <option value=\"04\">4</option>
                                <option value=\"05\">5</option>
                          </select>
                          <input class=\"btn btn-primary\" type='submit' id='submitFeedbackBtn' name='submitFeedbackBtn' value='Submit Feedback'/> 
                          </form>";
                }
            }
        }
   ?>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js"></script>
</html>