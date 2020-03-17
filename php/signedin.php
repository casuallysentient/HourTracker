<?php
session_start();
?>
<html>
    <!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <title>Hour Tracker</title>

        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="../css/infopage.css">

        <!-- JavaScript -->
        <script src="../js/volunteer.js"></script>
        <script>
            var deleting = false;
            function editInfo() {
                document.getElementById("hideinfo").style.display = "none";
                document.getElementById("editinfo").style.display = "block";
                document.getElementById("editbutton").style.display = "none";
                document.getElementById("spaces").style.display = "none";
                document.getElementById("deleteaccount").style.display = "block";
                document.getElementById("logout").className = "inputStyle";
                document.getElementById("deleteaccount").className = "inputStyle";
                document.getElementById("logout").style.marginLeft = "0px";
                document.getElementById("executiveactions").style.marginLeft = "7px";
            }
            function cancelEdits() {
                window.location.reload();
            }
            function newEntry() {
                document.getElementById("newentryform").style.display = "block";
                document.getElementById("newentrybutton").style.display = "none";
                document.getElementById("deleteentrybutton").style.display = "none";
                document.getElementById("canceldeletebutton").style.display = "none";
            }
            function logOut() {
                window.location.href = 'volunteer.php';
            }
            function deleteAccount() {
                var deleteConfirmation = confirm("Are you sure you want to delete your account? Your email will be able to be reactivated, but the action can not otherwise be undone.");
                if(deleteConfirmation == true) {
                    window.location.href = 'deleteaccount.php'
                }
            }
            function deleteEntry(entryid) {
                if(deleting == true) {
                    var deleteConfirmation = confirm("Are you sure you want to delete your account? Your email will be able to be reactivated, but the action can not otherwise be undone.");
                    var activityNumber = entryid.substr(8);
                    console.log(activityNumber);
                    if(deleteConfirmation == true) {
                        $.ajax({
                            method: "POST",
                            url: "deleteentry.php",
                            data: { activitynumber: activityNumber }
                        });
                        window.location.reload();
                    }
                }
            }
            function timeToDelete() {
                deleting = true;
                document.getElementById('deleteentrybutton').style.display = "none";
                document.getElementById('canceldeletebutton').style.display = "inline-block";
                document.getElementById("newentrybutton").style.display = "none";
                var activitydatalist = document.getElementsByClassName('activitydata');
                for(i = 0; i < activitydatalist.length; i++) {
                    activitydatalist[i].style.cursor = 'pointer';
                }
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
        if(($_SESSION['edited'] == false || $_SESSION['edited'] == null) && $_SESSION['email'] != "" && $_SESSION['signup'] == false) {
            $_SESSION['email'] = "";
            $_SESSION['password'] = "";
        }
        $_SESSION['signup'] = false;
        if($_SESSION['email'] != null && $_SESSION['email'] != "") {
            $email = $_SESSION['email'];
            $userpassword = $_SESSION['userpassword'];
        }
        if($email == "" || $email == null) {
            echo "<script type='text/javascript'>
            alert('You are not signed in. Please return to the home page and sign in.');
            window.location.href = 'volunteer.php';
            signIn();
            </script>";
        }

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
                    window.location.href = 'volunteer.php';
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
                window.location.href = 'volunteer.php';
                </script>";
        }
        $mysqli->close();
    ?>
    <body>
        <a href = "volunteer.php" style = "font-style: none; color: black; text-decoration: none;">
            <div id="header">
            HOUR TRACKER
            </div>
        </a>
        <div id = "contentholder">
            <div id="userinfo">
                <div style = "text-align: center;"><h3 style = "font-weight: bold;">User Info</h3></div>
                <?php
                   echo "<div id='hideinfo'>";
                       echo "<div id='nameinfo'>&nbspName: " . $firstname . " " . $lastname . "</div><br>";
                       echo "<div id='emailinfo'>&nbspEmail: " . $email . "</div><br>";
                       echo "<div id='phoneinfo'>&nbspPhone: " . $phone . "</div><br>";
                       echo "<div id='passwordinfo'>&nbspPassword: " . $userpassword . "</div><br>";
                   echo "</div>";
                 ?>
                <form id="editinfo" action = "editinfo.php" style = "display: none; margin-top: 0; margin-bottom: 0; height: auto;" method = "post">
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
                    <div id = "editingbuttons">
                        &nbsp<button type = 'button' class = "inputstyle" id = "canceledits" onclick = "cancelEdits()">Cancel</button>
                        <input type="submit" id = "saveedits" name="saveedits" value="Save Edits"/>
                    </div>
                </form>
                <div id = "executiveactions" style = "display: flex; flex-direction: row;"><button type = 'button' class = 'selection' id = "editbutton" onclick = "editInfo()">Edit</button><p id = "spaces">&nbsp&nbsp</p><button type = 'button' class = 'selection' id = "logout" onclick = "logOut()">Log Out</button><button type = 'button' class = 'selection' id = "deleteaccount" style = "display: none;margin-left:5px;background-color:darksalmon;" onclick = "deleteAccount()">Delete Account</button></div>
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
                    echo "<table>
                        <col width = 25%>
                        <col width = 100%>
                        <col width = 50%>
                        <tr>
                            <th style = 'border-right-width: 2px; border-top-left-radius: 12px; border-bottom-left-radius: 12px'>Date</th>
                            <th style = 'border-left-width: 0px; border-right-width: 0px;'>Activity</th>
                            <th style = 'border-left-width: 2px; border-top-right-radius: 12px; border-bottom-right-radius: 12px'>Length</th>
                        </tr>";
                    $totalhours = 0;
                    while($row = $res->fetch_row()) {
                        $activityid = $row[0];
                        echo "<tr class = 'activityrow' id='activity".$activityid."' onclick='deleteEntry(\"activity".$activityid."\")'><td class = 'activitydata'>" .
                        $row[2] . '</td><td class = \'activitydata\'>' .
                        $row[1] . '</td><td class = \'activitydata\'>' .
                        $row[3] . '</td></tr>';
                        $totalhours += $row[3];
                    }
                    echo "<tr><td style = 'border-bottom: none;' id='totalrow'>TOTAL</td><td style = 'border-bottom: none;'></td><td style = 'border-bottom: none;'>" . $totalhours . "</td></tr></table><br>";
                }
                $mysqli->close();
                ?>
                <form id = "newentryform" action = "newentry.php" method = "post" style = "display: none;">
                    <input class = "entryfield" type = "number" step = 0.01 id = "length" name = "length" min = 0 max = 1000 placeholder = "Length (Hours)" required/>
                    <input class = "entryfield" type = "text" id = "activity" name = "activity" placeholder = "Activity" maxlength = 30 required/>
                    <input class = "entryfield" type = "date" name = "date" id = "datefield" required/>
                    <br>
                    <button type = 'button' class = "inputstyle" id = "cancelentry" onclick = "window.location.reload()">Cancel</button>
                    <input class = "entryfield" type = "submit" id = "submitentry" name = "submitentry" value = "Submit Entry">
                </form>
                <div style = "display:inline-flex;flex-direction:row;text-align:center;height:15%;width:40%;justify-content:center;">
                    <button type = 'button' class = 'selection' id = "newentrybutton" onclick = 'newEntry()'>New Entry</button>
                    <?php
                    $mysqli = new mysqli($servername, $username, $password, $dbname);
                    // checks connection
                    if ($mysqli->connect_error) {
                        die("Connection failed: " . $mysqli->connect_error);
                    }
                    $sql = "SELECT * FROM Activity WHERE (UserID = '$userid')";
                    $res = mysqli_query($mysqli, $sql);
                    if ($res->num_rows > 0) {
                        echo("<button type = 'button' class = 'selection' id = 'deleteentrybutton' onclick = 'timeToDelete()'>Delete Entry</button>");
                    }
                    $mysqli->close();
                    ?>
                    <button type = 'button' class = 'selection' id = "canceldeletebutton" onclick = 'window.location.reload()' style = 'display:none;'>Cancel</button>
                </div>
            </div>
        </div>
    </body>
</html>
