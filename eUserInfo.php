
<!--
//This user info page is for the experiments to be able to update/change their contact information, including their username,
//password, and email address. It checks if there was a new username entered and if so it is udapted and stored in the database.
//The password and email both have confirmations to be sure the user entered the correct information. The code checks that the new 
//password and email match the confirmed password and email, respectively. If the new field for the password or email match the 
//confirming fields then it is updated in the database. Since the user is an experimenter they areredirected back to the experimeters home page.
-->
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
<?php include 'header.php';
top("experimenter");?>

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
	<input type ='text' name='password_confirm' ></input>
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
</body>
</html>
<?php
if (isset($_POST['submit'])){
	//connect to DB
	
	include 'connect.php';
	
	//get and sanitize the input
	$user = htmlspecialchars($_POST['username']);
	$password = htmlspecialchars($_POST['password']);
	$newpassword = htmlspecialchars($_POST['newpassword']);
	$password_confirm = htmlspecialchars($_POST['password_confirm']);
	$email = htmlspecialchars($_POST['email']);
	$newemail = htmlspecialchars($_POST['newemail']);
	$newemail_confirm = htmlspecialchars($POST_['newemail_confirm']);

	if($username != " ")
	{
		$result = pg_prepare($conn, "update_username","UPDATE database.users SET $username = $1 WHERE database.users = $1");
		$result = pg_execute($conn, "update_username", array($users)); 
		echo "You have successfully changed your username";
	}
	
	//check if passwords match
	if($newpassword != $password_confirm)
	{
		echo "Error: Passwords do not match, please try again\n<br >\n";
		return;
	}
	
	//If password match, update changed password in database
	if($newpassword == $password_confirm)
	{
		//seed random number generator
		mt_srand();
	
		//create random hashed salt value, and create password hash with salt
		$salt = sha1(mt_rand());
		$pwhash = sha1($salt . $password);
		
		$result = pg_prepare($conn, "update_password", "UPDATE database.users SET password = '$newpassword' where username = '$username'");
		$result = pg_execute($conn, "update_password", array($username, $pwhash, $salt));
		echo "You have successfully changed your password";
		echo "Click <a href 'homepageadmin.php'>here</a>\n";
	}
	
	//check if e-mail match
	if($newemail != $newemail_confirm)
	{
		echo "Error: Emails do not match, please try again\n<br >\n";
		return;
	}
	
	//If email match, update changed email in database
	if($newemail == $newemail_confirm)
	{
		$result = pg_prepare($conn, "email", "UPDATE users SET email = '$newemail' where username = '$username'");
		$result = pg_execute($conn, "email", array($username, $email));
		echo "You have successfully changed your email";
		echo "Click <a href 'homepageadmin.php'>here</a>\n";
	}
	
	$_SESSION['user_type'] = $user_type;
	
	//redirect to home page
		header("Location: https://babbage.cs.missouri.edu/~cs3380sp13grp11/homepageadmin.php");

	session_write_close();
	
}
?>


