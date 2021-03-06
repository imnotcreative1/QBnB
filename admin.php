<!DOCTYPE HTML>
<html>
    <head>
        <title>Admin page</title>
        <!-- jQuery first, then Bootstrap JS. -->
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

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
 
 if(isset($_POST['updateBtn']) && isset($_SESSION['email'])){
  // include database connection
    include_once 'config.php'; 
    
    $query = "UPDATE member SET password=?,phone_num=?, year = ? WHERE email=?";
 
    $stmt = $con->prepare($query);  
    $stmt->bind_param('ssss', $_POST['password'], $_POST['phone_num'], $_POST['gradYear'], $_SESSION['email']);
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
    //echo "Deleting";
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

        $listMembersQuery = "SELECT name, email from member";

        $stmt2 = $con->prepare($listMembersQuery);

        $stmt2->execute();

        // results 
        $result2 = $stmt2->get_result();

        //Make a query when the page loads to add the list of holdings for a user

        $listPropertiesQuery = "SELECT address, email from property";

        $stmt3 = $con->prepare($listPropertiesQuery);

        $stmt3->execute();

        $result3 = $stmt3->get_result();
        
} else {
    //User is not logged in. Redirect the browser to the login index.php page and kill this page.
    header("Location: index.php");
    die();
}

?>
<!-- dynamic content will be here -->
 <h2 class = "greeting"> Admin page 
    <?php echo $myrow['email']; ?>
</h2>
<div class="col-md-6" id = "profileMidCol">
       <h3 class = "MidHeader"> All properties </h3>
        <?php
            $addressArray = array();
            $emailArray = array();
            while ($row_users = $result3->fetch_assoc()) {
                array_push($emailArray, ($row_users['email']));
                array_push($addressArray, ($row_users['address']));
            }  
        ?>
        <table class="table table-striped">
            <tr> 
                <th> Address </th>
                <th> Email </th>
                <th> Options </th>
            </tr>
            <?php   
                for ($i = 0; $i < sizeof($addressArray) ; $i++){
                    echo "<tr>";
                    echo "<td> <a href=\"/QBnB/adminViewProperty.php?propertyAddress=" . urlencode($addressArray[$i]) . "\">" . $addressArray[$i]."</a></td>";
                    echo "<td> " . $emailArray[$i] . "</td>";
                    echo "<td><a href=\"/QBnB/deleteProperty.php?propertyAddress=" . urlencode($addressArray[$i]) . "\">Delete</a></td>";
                    echo "</tr>";
                }
            ?>
        </table>

</div>
<div class="col-md-6" id = "Members_LeftCol"> 
    <h3 class = "LeftHeader"> All Members </h3>
        <?php
            $nameArray = array();
            $emailArray = array();
            while ($row_users = $result2->fetch_assoc()) {
                array_push($nameArray, ($row_users['name']));
                array_push($emailArray, ($row_users['email']));
            }  
        ?>
        <table class="table table-striped">
            <tr> 
                <th> Name </th>
                <th> Email </th>
                <th> Options </th>
            </tr>
            <?php   
                for ($i = 0; $i < sizeof($nameArray) ; $i++){
                    echo "<tr>";
                    echo "<td> <a href=\"/QBnB/adminViewMember.php?member=" . urlencode($emailArray[$i]) . "\">" . $nameArray[$i] ."</a></td>";
                    echo "<td> " . $emailArray[$i] . "</td>";
                    echo "<td> <a href=\"/QBnB/deleteMember.php?member=" . urlencode($emailArray[$i]) . "\">Delete</a></td>";
                    echo "</tr>";
                }
            ?>
        </table>
</div> 
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js"></script>
</html>