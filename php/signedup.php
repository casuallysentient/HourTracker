<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<?php
$firstname = ucfirst(strtolower(trim($_POST['firstname'])));
$lastname = ucfirst(strtolower(trim($_POST['lastname'])));
$email = strtolower(trim($_POST['email']));
$phone = trim($_POST['phone']);
$userpassword = $_POST['userpassword'];
$confirmedpassword = $_POST['confirmedpassword'];

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

if($userpassword == $confirmedpassword) {
    if(preg_match("#^[a-zA-Z0-9\d._-]+$#", $userpassword)) {
        $sql = "SELECT * FROM User WHERE (Email = '$email')";
        $res = $mysqli->query($sql);
        if ($res->num_rows > 0) {
            $row = $res->fetch_assoc();
            echo "<script type='text/javascript'>
            alert('This email is already in use.');
            window.location.href = '../volunteer.html';
            </script>";
        } else {
            $stmt = $mysqli->prepare("INSERT INTO User(FirstName, LastName, Email, Phone, UserPassword) VALUES (?,?,?,?,?)");
            $stmt->bind_param("sssss", $firstname, $lastname, $email, $phone, $userpassword);
            $stmt->execute();
            echo "<script type='text/javascript'>
            alert('Account successfully created. Please log in.');
            window.location.href = '../volunteer.html';
            </script>";
        }
    } else {
        echo "<script type='text/javascript'>
        alert('Please only use alphanumeric characters, hyphens, underscores, and periods in the password.');
        window.location.href = '../volunteer.html';
        </script>";
    }
} else {
    echo "<script type='text/javascript'>
    alert('The passwords do not match.');
    window.location.href = '../volunteer.html';
    </script>";
}
$mysqli->close();
?>
