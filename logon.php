<?php
require_once '/home/common/mail.php'; // Add email functionality
require_once '/home/common/dbInterface.php'; // Add database functionality
processPageRequest(); // Call the processPageRequest() function

function authenticateUser($username, $password){
	$credentials = validateUser($username, $password);
	if(is_null($credentials))
		displayLoginForm("Incorrect Username or Password");
	else {
		session_start();
		$_SESSION["credentials"] = $credentials; //contains user's data: ID, Display Name and Email Address
		header('Location: index.php');
	}
}

function createAccount($username, $password, $displayName, $emailAddress) {
	$addAccount = addUser($username, $password, $displayName, $email);
	if($addAccount > 0)
		sendValidationEmail($userId, $displayName, $emailAddress);
	else
		displayLoginForm("The provided username already exists");
}
function displayCreateAccountForm(){
	require_once ('templates/create_form.html');
}

function displayForgotPasswordForm(){
	require_once ('templates/forgot_form.html');
}


function displayLoginForm($message="") {
	echo $message;
	require_once ('templates/logon_form.html');
}
function displayResetPasswordForm($userId){
	require_once ('templates/reset_form.html');
}

function processPageRequest(){
	session_unset();
	
	if (isset($_POST['action']))
		$action = $_POST['action'];
	elseif (isset($_GET['action']))
		$action = $_GET['action'];
	else {
	    displayLoginForm();
	    return;
	}

	switch($action) {
	case "create":
		createAccount($username, $password, $displayName, $emailAddress);
		return;
	case "forgot":
		sendForgotPasswordEmail($username);
		return;
	case "login":
		authenticateUser($username, $password);
		return;
	case "reset":
		resetPassword($userId, $password);
		return;
	case "validate":
	    validateAccount($userId);
	    return;
	}
	
	if (isset($_GET['form'])) {
	    switch ($_GET['form']) {
	       case "create":
	            displayCreateAccountForm();
	            return;
	       case "forgot":
	            displayForgotPasswordForm();
	            return;
	       case "reset":
	            displayResetPasswordForm($userId);
	            return;
	   }
	}
	
	displayLoginForm();
}

function resetPassword($userId, $password){
    $resetPass = resetUserPassword($userId, $password);
    if($resetPass)
        displayLoginForm("Your password has been sucessfully changed");
    else{
        if(!$userId)
            displayLoginForm("The provided user ID does not exist");
        else
            displayLoginForm("New password is the same as the current");
    }
}
function sendForgotPasswordEmail($username){
    $userData = getUserData($username);    
    if (is_null($userData))
        displayLoginForm("The username is invalid");
    else{

        $displayName = $_SESSION["credentials"][1];
            $userId = $_SESSION["credentials"][0];
        $message = "myMovies Xpress!<br>"
        . "Hello $displayName<br>"
        . "1. Enter your email address and choose send email<br>"
        . "2. Open the email and click set a New Password<br>"
        . "3. Create a new Password<br>"
        . '<a href="http://139.62.210.181/~fs41640/project5/logon.php?form=reset&user_id=' . $userId . '">Click to reset your Password.</a>';
        $result = sendMail(737589532, "n00688432@unf.edu", $displayName, "Forgot Password", $message);
    }
    
function sendValidationEmail($userId, $displayName, $emailAddress){
    $message =  "myMovies Xpress!<br>"
    . "Hello $displayName"
    . "Thank you for registering."
    . "To validate your email address you MUST click the link below."
    . '<a href="http://139.62.210.181/~fs41640/project5/logon.php?action=validate&user_id=' . $userId . '">Validate Your Email.</a>';
    $result = sendMail(737589532, "n00688432@unf.edu", $displayName, "Email Validation", $message);
}   

function validateAccount($userId){
    $activateAccount = activateAccount($userId);
    if ($activateAccount)
		displayLoginForm("The specified User Id exists, The account has been activated");
	else
		displayLoginForm("The specificed User Id does not exist");
}
?>