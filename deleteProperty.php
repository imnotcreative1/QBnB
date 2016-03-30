<?php

include ('config.php');
session_start();
if (isset($_GET['propertyAddress']) && isset($_SESSION['email'])) {

$propertyAddress = $_GET['propertyAddress'];

$query0 = "DELETE FROM comments WHERE address = ?";
	$stmt = $con->prepare($query0);
	$stmt->bind_param("s", $propertyAddress);
	$stmt->execute();

$query1 = "DELETE from booking where booking.ID in (SELECT id from availability NATURAL join property where property.address = ?);";
	$stmt = $con->prepare($query1);
	$stmt->bind_param("s", $propertyAddress);
	$stmt->execute();

$query2 = "DELETE from availability where availability.address = ?";
	$stmt = $con->prepare($query2);
	$stmt->bind_param("s", $propertyAddress);
	$stmt->execute();

$query3 = "DELETE from property where address = ?";
	$stmt = $con->prepare($query3);
	$stmt->bind_param("s", $propertyAddress);
	$stmt->execute();

header('Location: ' . $_SERVER['HTTP_REFERER']);

} else {
	header('Location: index.php');
}

?>