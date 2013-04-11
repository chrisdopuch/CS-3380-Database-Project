<?php

session_start();

if (isset($_POST['submit'])){
	//connect to DB
	include 'connect.php';
	
	//get and sanitize the input
	$user = htmlspecialchars($_POST['username']);
	$password = htmlspecialchars($_POST['password']);
	$newpassword = htmlspecialchars($_POST['newpassword']);
	$password_confirm = htmlspecialchars($_POST['confirm-password']);
	$email = htmlspecialchars($_POST['email']);
	$newemail = htmlspecialchars($_POST['newemail']);
	$newemail_confirm = htmlspecialchars($POST_['newemail_confirm']);

	//check if password was actually entered
	if($newpassword == ""){
		echo "Error: No password entered\n<br>\n";
		return;
	}
	
	//check if passwords match
	if($newpassword != $password_confirm){
		echo "Error: Passwords do not match, please try again\n<br >\n";
		return;
	}
	
	if($newpassword == $password_confirm)
	{
		$result = pg_prepare($conn, "UPDATE users SET password = '$newpassword' where username = '$username'");
		echo "You have successfully changed your password";
		echo "Click <a href 'homepageadmin.php'>here</a>\n";
	}
	
	//check if email was actually entered
	if($newemail == ""){
		echo "Error: No email entered\n<br>\n";
		return;
	}
	
	//check if passwords match
	if($newemail != $newemail_confirm){
		echo "Error: Emails do not match, please try again\n<br >\n";
		return;
	}
	
	//If email match, update email in database
	if($newemail == $newemail_confirm)
	{
		$result = pg_prepare($conn, "UPDATE users SET email = '$newemail' where username = '$username'");
		echo "You have successfully changed your email";
	}
	
	$_SESSION['user_type'] = $user_type;
	
	//redirect to home page
	if($user_type == "experimenter"){
		header("Location: https://babbage.cs.missouri.edu/~cs3380sp13grp11/homepageadmin.php");
	} else {
		header("Location: https://babbage.cs.missouri.edu/~cs3380sp13grp11/homepagepart.php");
	}
	//seed random number generator
	mt_srand();
	
	//create random hashed salt value, and create password hash with salt
	$salt = sha1(mt_rand());
	$pwhash = sha1($salt . $password);
	
	//add user to the users table
	$result = pg_prepare($conn, "info", "INSERT INTO database.users VALUES ($1, $2, $3, $4, $5)");
	$result = pg_execute($conn, "info", array($user, $pwhash, $salt, $user_type, $email));
		
	//log the username and type in session
	$_SESSION['username'] = $user;
	$_SESSION['user_type'] = $user_type;
	
	session_write_close();
	
}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="style.css" />
<title> User Info </title>
<style>

#image
{
	vertical-align: center;
}

</style>
</head>
<body>
	<div id = image>
	<img src = "https://babbage.cs.missouri.edu/~cs3380sp13grp11/images/main.jpg " class = "center" />

	<div id = body>
<?php include 'header.php'; ?>
	<form method= 'POST' action= 'update.php'>
	<br></br>
Please enter contact information to change:
	</br>
Username:
	<input type ='text' name='username' ></input>
	</br>
Password:
	<input type ='text' name='newpassword' ></input>
	</br>
ConfirmPassword:
	<input type ='text' name='passwordconfirm' ></input>
	</br>
Email:
	<input type ='text' name='newemail' ></input>
	</br> 
Confirm Email:
	<input type ='text' name='newemail_confirm' ></input>
	</br> 
	</br>
	<input type='submit' name='submit' value='Submit' > </input>
</form>
<?php include 'header.php'; ?>
</body>
</head>
</html>


