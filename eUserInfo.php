<?php

//This user info page is for the experiments to be able to update/change their contact information, including their username,
//password, and email address. It checks if there was a new username entered and if so it is udapted and stored in the database.
//The password and email both have confirmations to be sure the user entered the correct information. The code checks that the new 
//password and email match the confirmed password and email, respectively. If the new field for the password or email match the 
//confirming fields then it is updated in the database. Since the user is an experimenter they areredirected back to the experimeters home page.

session_start();

if (isset($_POST['submit'])){
	//Connect to Database
	include 'connect.php';
	
	//Get input values
	$username = htmlspecialchars($_POST['username']);
	$newpassword = htmlspecialchars($_POST['newpassword']);
	$password_confirm = htmlspecialchars($_POST['password_confirm']);
	$email = htmlspecialchars($_POST['email']);
	$newemail = htmlspecialchars($_POST['newemail']);
	$newemail_confirm = htmlspecialchars($POST_['newemail_confirm']);
	
	//Get current Variables
	$current_username = $_SESSION['username'];
	$current_pwhash = $_SESSION['pwhash'];
	$current_salt = $_SESSION['salt'];
	$current_email = $_SESSION['email'];

	//If no username entered, skip
	if($username == " ")
	{
		return;
	}
	
	//Check if username is not empty
	if($username != " ")
	{
		$result = pg_prepare($conn, "update_username","UPDATE database.users SET username = $1 WHERE username = $2");
		$result = pg_execute($conn, "update_username", array($username, $current_username)); 
		
		if($result != NULL)
		{
			echo "You have successfully changed your username";
			echo "Click <a href 'eUserInfophp'>here to return to user info page</a>\n";
		}
		else
		{
			echo "Error: User was not successfully changed";
			echo "pg_prepare failed: ".pg_last_error($conn);
		}
		
		return;
	}
		
		
	//If no new password entered, skip
	if($newpassword AND $password_confirm == " ")
	{
		return;
	}
	
	//check if passwords match
	if($newpassword != $password_confirm)
	{
		echo "Error: Passwords do not match, please try again\n<br >\n";
		echo "Click <a href 'eUserInfo.php'>here to return to user info page</a>\n";
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
				
		$result = pg_prepare($conn, "update_password", "UPDATE database.users SET pwhash = $1 AND salt = $2 WHERE username = $3");
		$result = pg_execute($conn, "update_password", array($username, $pwhash, $current_pwhas, $salt, $current_salt));
		
		if($result != NULL)
		{
			echo "You have successfully changed your password";
		}
		else
		{
			echo "Error: Password was not successfully changed.";
			echo "pg_last_error($conn)"; 
		}
	}
	
	
	//Check if new email entered, if not skip
	if($newemail  AND $newemail_confirm == " ")
	{
		return;
	}
	
	//check if e-mail match
	if($newemail != $newemail_confirm)
	{
		echo "Error: Emails do not match, please try again\n<br >\n";
		echo "Click <a href 'eUserInfophp'>here to return to user info page</a>\n";
		return;
	}
		
	//If email match, update changed email in database
	if($newemail == $newemail_confirm)
	{
		$result = pg_prepare($conn, "email", "UPDATE database.users SET email = $1 WHERE username = $2");
		$result = pg_execute($conn, "email", array($username, $email, $current_email));
		echo "You have successfully changed your email";
		echo "Click <a href 'homepageadmin.php'>here</a>\n";
	}
	
	

	session_write_close();
	
}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="style.css" />
<title> User Info </title>
</head>
<body>

		<?php
			include 'header.php';
			top("experimenter");
		?>


	<div id = body>
	


	<form method= 'POST' action='eUserInfo.php'>
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
<?php include 'footer.php'; ?>
</body>
</head>
</html>


