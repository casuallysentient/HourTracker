<?php
session_start();
$userid = $_SESSION['userid'];
$mysqli = new mysqli("localhost", "fblauser", "fbla2020", "volunteer_hours");
if($mysqli->connect_error) {
  echo('Could not connect');
}

$sql = "DELETE FROM ACTIVITY WHERE UserID = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $userid);
$stmt->execute();
$stmt->close();

$sql = "DELETE FROM USER WHERE UserID = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $userid);
$stmt->execute();
$stmt->close();
header('Location:volunteer.php');
?>
