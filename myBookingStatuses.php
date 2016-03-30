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
 <h2 >  
    Your Booking Statuses for <?php echo " " . $address. "!"?>
</h2>
    <form name='bSes' id='bSes' method='post'>
        <table class="table table-striped">
            <tr> <th> ID </th><th> Email </th> <th> Status </th> <th> Change Status </th> </tr>
            <?php
                $emailArray = array();
                $statusArray = array(); 
                $idArray = array();
                while ($sresult = $statusi->fetch_assoc()){
                    array_push($emailArray, $sresult['email']);
                    array_push($statusArray, $sresult['booking_status']);
                    array_push($idArray, $sresult['id']);
                }
                for ($i = 0; $i < count($emailArray); $i++){  
                    echo "<tr>
                        <td> 
                            " . $idArray[$i]  . "
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
   
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js"></script>
</html>