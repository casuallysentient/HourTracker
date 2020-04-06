<?php
session_start();
$activitynumber = $_POST['activitynumber'];
$mysqli = new mysqli("localhost", "fblauser", "fbla2020", "volunteer_hours");
if($mysqli->connect_error) {
  echo('Could not connect');
}

$sql = "SELECT * FROM User WHERE (ActivityID = '$activitynumber')";

$res = mysqli_query($mysqli, $sql);
$row = $res->fetch_row(); //assigns variables in each row to an array to be turned into individual variables (below)
$activitytype = $row[1];
$date = date("Y-m-d");
$length = $row[3];
$userid = $row[4];
$stmt = $mysqli->prepare($sql);
$stmt = $mysqli->prepare("INSERT INTO Activity(ActivityType, ActivityDate, Length, UserID) VALUES (?,?,?,?)");
$stmt->bind_param("ssdi", $activitytype, $date, $length, $userid);
$stmt->execute();

$stmt->close();
?>
