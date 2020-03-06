<!-- jQuery -->
src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js";

function signUp() {
    document.getElementById("signupform").style.display = "block";
    document.getElementById("signinform").style.display = "none";
    document.getElementById("instructions").style.display = "none";
    document.getElementById("description").style.textAlign = "left";
}

function signIn() {
    document.getElementById("signinform").style.display = "block";
    document.getElementById("signupform").style.display = "none";
    document.getElementById("instructions").style.display = "none";
    document.getElementById("description").style.textAlign = "left";
}

function newEntry() {
    document.getElementById("rowcreator").style.display = "inline-block";
}
