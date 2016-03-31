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

        $query = "SELECT  * 
            FROM  Comments
            WHERE address = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $address);
        //$stmt->bind_param("s", $address);//Uncomment this after testing
        $stmt->execute();
        $commentResults = $stmt->get_result();
    

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
if(isset($_POST['replyBtn'])){
    include_once 'config.php';

    $query = "UPDATE comments set reply=? where address = ? and email = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('sss', $_POST['input'], $address, $_POST['commentOwner']);
    $stmt->execute();
}
?>


 <?php
 //grabs property information
 $address = urldecode($_GET['propertyAddress']);
 
 
 $allowedToEdit = isset($_SESSION['email']); //Add functionality to compare the email with the property to be edited
 //Loading the property to be edited
if($allowedToEdit){
    include_once 'config.php';
    $query = "SELECT booking.id, email, booking_status, period from booking natural join availability natural join (select address from property where address = ?) as T" ;
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $address);
    //$stmt->bind_param("s", $address);//Uncomment this after testing
    if ($stmt->execute()){
        //echo "yaaaaaaas";
    }
    $statusi = $stmt->get_result();
    $num = $statusi->num_rows;
    if ($num > 0){
        //echo "Property Loaded";

    }
    else {
        //echo "Property Failed to Load";
        //header("Location: /QBnB/profile.php"); //Re-Direct if the user isn't valied ********************************************************************
    }
} 
?>
<?php
//Not Needed
      /*  if ($allowedToView){
            include_once 'config.php';

            $query = "SELECT period from property  
                inner join availability on property.address = availability.address
                where property.address = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param('s', $address);
            if ($stmt->execute()){
                $result = $stmt->get_result();
                $periodResults = $result->fetch_assoc();
                //echo "got it";
            }
        }*/
    ?>
 <h2 >  
    Your Booking Statuses for <?php echo " " . $address. "!"?>
</h2>
    <form name='bSes' id='bSes' method='post'>
        <table class="table table-striped">
            <tr> <th> Week Starting From</th><th> Email </th> <th> Status </th> <th> Change Status </th> </tr>
            <?php
                $emailArray = array();
                $statusArray = array(); 
                $periodArray = array();
                include_once 'datePeriodConversion.php';

                while ($sresult = $statusi->fetch_assoc()){
                    array_push($emailArray, $sresult['email']);
                    array_push($statusArray, $sresult['booking_status']);
                    array_push($periodArray, $sresult['period']);
                }
                for ($i = 0; $i < count($emailArray); $i++){  
                    echo "<tr>
                        <td> 
                            " . printDate(periodToDate($periodArray[$i]))  . "
                        </td>
                        <td> 
                            " . $emailArray[$i]  . "
                        </td>
                        <td>
                            " . $statusArray[$i] . "
                        </td>
                        <td>
                            " . "<select name='bstatus" . $i . "' id='bstatus'" . $i . ">" .
                                "<option value='CANCELLED'>CANCELLED </options>" .
                                "<option value='CONFIRMED'> CONFIRMED</options>" .
                                "<option value='REJECTED'> REJECTED</options>" .
                                "<option value='REQUESTED'> REQUESTED</options>" . 
                                "</select> 
                        </td>
                        <td>
                            .   <input type='submit' name='supdate[]' value='Update' />
                        </td>
                    </tr>";
                }

            ?>
          
           
        </table>
    </form>
    <?php 
//When the form is submitted, load the changed status into the database
    if (isset($_SESSION['email'])){
        //echo "here";
        include_once 'config.php';
        if (isset($_POST['supdate'])){
            for ($i = 0; $i < count($_POST['supdate']); $i++){
                echo "</br>" . $emailArray[$i] . "</br>";
                $queryChange = "UPDATE booking set booking_status=? where id=?";
                $stmt = $con->prepare($queryChange);
                echo "id is " . $idArray[$i] . " and status is " . $_POST['bstatus' . $i];
                echo "</br>" . $queryChange;
                //echo $_POST['bstatus'. $i ];
                $stmt->bind_param("si", $_POST['bstatus' . $i] , $idArray[$i]);
                if ($stmt->execute())
                    header("Location: /QBnB/myBookingStatuses.php?propertyAddress=" . urlencode($address));
                    //echo "weoo";
                //else
                    //echo "not good";
            }
        }
    }

    ?>

    <?php
    if ($commentResults != ""){
            $rating = array();
            $comment = array();
            $replies = array();
            $commenter = array();
            while ($row_results = $commentResults->fetch_assoc()) {
                array_push($rating, ($row_results['property_rating']));
                array_push($comment, ($row_results['comment']));
                array_push($replies, ($row_results['reply']));
                array_push($commenter, ($row_results['email']));
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
                if ($comment[$i] != "" && $replies[$i] == "") {
                    echo "<td> <b> Reply to Comment </b>";
                    echo "<form name='addComment' id='addComment' action='myBookingStatuses.php?propertyAddress=" . urlencode($address) . "' method='post'>
                          <textarea class=\"form-control\" rows=\"3\" name = \"input\"></textarea>                          
                          <textarea name = \"commentOwner\" style = \"display:none;\">" .$commenter[$i]. "</textarea>
                          <input class=\"btn btn-primary\" type='submit' id='replyBtn' name='replyBtn' value='Reply to Comment'/> 
                          </form> </td>";
                } else {       
                    echo "<td>" . $replies[$i] . "</td>";
                }   
                echo "</tr>";
            }
            echo "</table>";
    } else {
        echo "<h2> You have no comments! </h2>";
    }
?>
   
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js"></script>
</html>