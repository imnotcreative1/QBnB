<?php
// used to connect to the database
$host = "localhost";
$db_name = "cisc332_2";
$username = "cisc332";
$password = "cisc332password";

try {
    $con = new mysqli($host,$username,$password, $db_name);
}
 
// show error
catch(Exception $exception){
    echo "Connection error: " . $exception->getMessage();
}
/*
 $con = new mysqli($host,$username,$password, $db_name);
 //$con = mysqli_connect($host,$username,$password, $db_name);
 // Check connection
 if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  die();
  }
   */
?>