<?php
session_start();
?>
<html>
    <head>
        <meta charset="utf-8">
        <title>Hour Tracker</title>

        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="../css/infopage.css">

        <!-- JavaScript -->
        <script src="../js/volunteer.js"></script>
        <script>
            function editInfo() {
                document.getElementById("nameinfo").style.display = "none";
                document.getElementById("emailinfo").style.display = "none";
                document.getElementById("phoneinfo").style.display = "none";
                document.getElementById("passwordinfo").style.display = "none";
                document.getElementById("editinfo").style.display = "block";
                document.getElementById("editbutton").style.display = "none";
            }
            function newEntry() {
                document.getElementById("newentryform").style.display = "block";
                document.getElementById("newentrybutton").style.display = "none";
            }
        </script>

        <!-- jQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

        <!-- Font -->
        <link href="https://fonts.googleapis.com/css?family=EB+Garamond&display=swap" rel="stylesheet">

        <!-- Favicon -->
        <link rel="apple-touch-icon" sizes="180x180" href="../favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../favicon/favicon-16x16.png">
        <link rel="manifest" href="../favicon/site.webmanifest">
        <link rel="mask-icon" href="../favicon/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">
    </head>
    <?php
        //email and password are both saved as variables, email is trimmed of whitespace and set to all lowercase to avoid inconsistencies in data
        $email = strtolower(trim($_POST['email']));
        $userpassword = $_POST['userpassword'];
        $email = $_SESSION['email'];
        $userpassword = $_SESSION['userpassword'];

        //setting variables to connect to database
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

        if(preg_match("#^[a-zA-Z0-9\d._-]+$#", $userpassword)) { //ensures password only contains alphanumeric characters, hyphens, underscores, and periods
            $sql = "SELECT * FROM User WHERE (Email = '$email' and UserPassword = '$userpassword')";
            $res = mysqli_query($mysqli, $sql);
            if ($res->num_rows == 0) { //looks for matching account (same email and password), reloads page if incorrect and logs in if correct
                echo "<script type='text/javascript'>
                    alert('Incorrect username/password.');
                    window.location.href = '../volunteer.html';
                    signIn();
                    </script>";
            } else {
                $row = $res->fetch_row(); //assigns variables in each row to an array to be turned into individual variables (below)
                $userid = $row[0];
                $firstname = $row[1];
                $lastname = $row[2];
                $email = $row[4];
                $phone = $row[5];
                $_SESSION['userid'] = $userid;
                $_SESSION['email'] = $email;
                $_SESSION['userpassword'] = $userpassword;
            }
        } else {
            echo "<script type='text/javascript'>
                alert('Please only use alphanumeric characters, hyphens, underscores, and periods in the password. '.$userpassword.'');
                window.location.href = '../volunteer.html';
                </script>";
        }
        $mysqli->close();
    ?>
    <body>
        <a href = "../volunteer.html" style = "font-style: none; color: black; text-decoration: none;">
            <div id="header">
            HOUR TRACKER
            </div>
        </a>
        <div id = "contentholder">
            <div id="userinfo">
                <div style = "text-align: center;"><h3 style = "font-weight: bold;">User Info</h3></div>
                <?php
                    echo "<div id='nameinfo'>&nbspName: " . $firstname . " " . $lastname . "</div><br>";
                    echo "<div id='emailinfo'>&nbspEmail: " . $email . "</div><br>";
                    echo "<div id='phoneinfo'>&nbspPhone: " . $phone . "</div><br>";
                    echo "<div id='passwordinfo'>&nbspPassword: " . $userpassword . "</div><br>";
                ?>
                <form id="editinfo" action = "editinfo.php" style = "display: none; margin-top: 0; height: auto;" method = "post">
                    <p>&nbspFirst Name:<input type="text" id = "firstnamefield" name = "firstname" size="30" value=<?php echo $firstname ?> required/></p>
                    <br>
                    <p>&nbspLast Name:<input type="text" id = "lastnamefield" name="lastname" size="30" value=<?php echo $lastname ?> required/></p>
                    <br>
                    <p>&nbspEmail:<input type="email" id = "emailfield" name="email" size="30" value=<?php echo $email ?> required/></p>
                    <br>
                    <p>&nbspPhone:<input type="tel" id = "phonefield" name="phone" pattern="[0-9]{10}" maxlength = 10 value = <?php echo $phone ?> required/></p>
                    <br>
                    <p>&nbspPassword:<input type="password" id = "passwordfield" name="userpassword" size="30" required/></p>
                    <br>
                    <p>&nbspConfirm Password:<input type="password" id = "confirmedpasswordfield" name="confirmedpassword" size="30" required/></p>
                    <br>
                    <p>&nbsp<input type="submit" id = "saveedits" name="saveedits" value="Save Edits" /></p>
                </form>
                &nbsp<button type = 'button' class = 'selection' id = "editbutton" onclick = "editInfo()">Edit</button>
            </div>
            <div id="table">
                <div style = "text-align: center; clear: both;"><h3 style = "font-weight: bold;">Hours</h3></div>
                <?php
                // creates connection
                $mysqli = new mysqli($servername, $username, $password, $dbname);
                // checks connection
                if ($mysqli->connect_error) {
                    die("Connection failed: " . $mysqli->connect_error);
                }
                $sql = "SELECT * FROM Activity WHERE (UserID = '$userid')";
                $res = mysqli_query($mysqli, $sql);
                if ($res->num_rows > 0) {
                    echo "<div id = 'tablecontainer' style = 'clear:both;'>
                        <table>
                            <col width = 25%>
                            <col width = 100%>
                            <col width = 50%>
                            <tr>
                                <th style = 'border-right-width: 2px; border-top-left-radius: 12px; border-bottom-left-radius: 12px'>Date</th>
                                <th style = 'border-left-width: 2px; border-right-width: 2px;'>Activity</th>
                                <th style = 'border-left-width: 2px; border-top-right-radius: 12px; border-bottom-right-radius: 12px'>Length</th>
                            </tr>";
                    $totalhours = 0;
                    while($row = $res->fetch_row()) {
                        $activityid = $row[0];
                        echo '<tr id='.$activityid.'><td>' .
                        $row[2] . '</td><td>' .
                        $row[1] . '</td><td>' .
                        $row[3] . '</td></tr>';
                        $totalhours += $row[3];
                    }
                    echo "<tr><td style = 'border-bottom: none;'>TOTAL</td><td style = 'border-bottom: none;'></td><td style = 'border-bottom: none;'>" . $totalhours . "</td></tr></table></div>";
                }
                ?>
                <div><button type = 'button' class = 'selection' id = "newentrybutton" onclick = 'newEntry()'>New Entry</button></div>
                <form id = "newentryform" action = "newentry.php" method = "post" style = "display: none;">
                    <input class = "entryfield" type = "text" id = "activity" name = "activity" placeholder = "Activity" required/>
                    <input class = "entryfield" type = "number" id = "length" name = "length" min = 0 max = 1000 placeholder = "Length (Hours)" required/>
                    <input class = "entryfield" type = "date" name = "date" id = "datefield" required/>
                    <br>
                    <input class = "entryfield" type = "submit" id = "submitentry" name = "submitentry" value = "Submit Entry">
                </form>
            </div>
        </div>
    </body>
</html>
