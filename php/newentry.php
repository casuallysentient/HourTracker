<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<?php
session_start();
$activity = trim($_POST['activity']);
$length = $_POST['length'];
$date = $_POST['date'];
$userid = $_SESSION['userid'];
$email = $_SESSION['email'];
$userpassword = $_SESSION['userpassword'];

$servername = "localhost";
$username = "fblauser";
$password = "fbla2020";
$dbname = "volunteer_hours";

// creates connection
$mysqli = new mysqli($servername, $username, $password, $dbname);
// checks connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$stmt = $mysqli->prepare("INSERT INTO Activity(ActivityType, ActivityDate, Length, UserID) VALUES (?,?,?,?)");
$stmt->bind_param("ssii", $activity, $date, $length, $userid);
$stmt->execute();
header('Location:signedin.php');
?>
