<?php

include ('config.php');
session_start();
if (isset($_GET['member']) && isset($_SESSION['email'])) {

$memberEmail = $_GET['member'];

$query1 = "DELETE FROM booking WHERE email = ?";
$stmt = $con->prepare($query1);
$stmt->bind_param("s", $memberEmail);
$stmt->execute();

$query2 = "DELETE from booking where booking.id in (SELECT id from availability natural join (select address from property where email = ?) as T)";
$stmt = $con->prepare($query2);
$stmt->bind_param("s", $memberEmail);
$stmt->execute();

$query3 = "DELETE from availability where address in (SELECT address from property where email = ?)";
$stmt = $con->prepare($query3);
$stmt->bind_param("s", $memberEmail);
$stmt->execute();

$query4 = "DELETE FROM property WHERE email = ?";
$stmt = $con->prepare($query4);
$stmt->bind_param("s", $memberEmail);
$stmt->execute();

$query5 = "DELETE FROM member WHERE email = ?";
$stmt = $con->prepare($query5);
$stmt->bind_param("s", $memberEmail);
$stmt->execute();

header('Location: ' . $_SERVER['HTTP_REFERER']);

} else {
	header('Location: index.php');
}

?>