<?php
// used to connect to the database
$host = "localhost";
$db_name = "QBnB";
$username = "root"; // use your own username and password if different from mine
$password = "";

try {
    $con = new mysqli($host,$username,$password, $db_name);
}
 
// show error
catch(Exception $exception){
    echo "Connection error: " . $exception->getMessage();
}

?>