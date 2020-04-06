<?php
session_start();
$activitynumber = $_POST['activitynumber'];
$mysqli = new mysqli("localhost", "fblauser", "fbla2020", "volunteer_hours");
if($mysqli->connect_error) {
  echo('Could not connect');
}

$sql = "DELETE FROM ACTIVITY WHERE ActivityID = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $activitynumber);
$stmt->execute();
$stmt->close();
?>
