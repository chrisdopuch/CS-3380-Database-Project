<?php

//This user info page is for the experiments to be able to update/change their contact information, including their username,
//password, and email address. It checks if there was a new username entered and if so it is udapted and stored in the database.
//The password and email both have confirmations to be sure the user entered the correct information. The code checks that the new 
//password and email match the confirmed password and email, respectively. If the new field for the password or email match the 
//confirming fields then it is updated in the database. Since the user is an experimenter they areredirected back to the experimeters home page.

//Add username and email to the page for user to see already

session_start();

if (isset($_POST['submit']))
{
	//Connect to Database
	include 'connect.php';
	
	//Get input values
	if(isset($_POST['username'])
	{
		$username = htmlspecialchars($_POST['username']);
	} 
	$newpassword = htmlspecialchars($_POST['newpassword']);
	$password_confirm = htmlspecialchars($_POST['password_confirm']);
	$email = htmlspecialchars($_POST['email']);
	$newemail = htmlspecialchars($_POST['newemail']);
	$newemail_confirm = htmlspecialchars($POST_['newemail_confirm']);
	
	//Get current Variables
	$current_username = $_SESSION['username'];
	$current_pwhash = "SELECT pwhash FROM database.users WHERE username = '$current_username'";
	$current_salt = "SELECT salt FROM database.users WHERE username = '$current_username'";
	$current_email = "SELECT email FROM database.users WHERE username = '$current_username'";

	//If username is set, update username. If not, error message.
	if(isset($_POST['username'])
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
				
		$result = pg_prepare($conn, "update_password", "UPDATE database.users SET pwhash = '$pwhash', salt = '$salt' WHERE username = '$current_username' AND pwhash = '$current_pwhash' AND salt = '$current_salt'");
		$result = pg_execute($conn, "update_password", array($pwhash, $salt, $current_username, $current_pwhash, $current_salt));
		
		if($result != NULL)
		{
			echo "You have successfully changed your password";
		}
		else
		{
			echo "Error: Password was not successfully changed.";
			echo "pg_prepare failed: ".pg_last_error($conn);; 
		}
		
		return;
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
		
			$result = pg_prepare($conn, "email", "UPDATE database.users SET email = '$newemail' WHERE username = '$current_username' AND email = $current_email'");
			$result = pg_execute($conn, "email", array($newemail, $current_username, $current_email));
			echo "You have successfully changed your email";
			echo "Click <a href 'homepageadmin.php'>here</a>\n";
	
		if($result != NULL)
		{
			echo "You have successfully changed your email";
		}
		else
		{
			echo "Error: Email was not successfully changed.";
			echo "pg_prepare failed: ".pg_last_error($conn);
		}
			
		return;
	
	}
	
		header("Location: https://babbage.cs.missouri.edu/~cs3380sp13grp11/eHome.php");

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
	<div id = 'form' class = 'clearfix'>
	<form method= 'POST' action='eUserInfo.php'>
	<br></br>
Please enter contact information to change:
	</br>
Username:
<?php echo $current_username; ?>
	</br>
Current Email:
<?php echo $current_email; ?>
	</br>
Change Username:
	<input type ='text' name='username' ></input>
	</br>
Password:
	<input type ='text' name='newpassword' ></input>
	</br>
ConfirmPassword:
	<input type ='text' name='password_confirm' ></input>
	</br>
Change Email:
	<input type ='text' name='newemail' ></input>
	</br> 
Confirm Email:
	<input type ='text' name='newemail_confirm' ></input>
	</br> 
	</br>
	<input type='submit' name='submit' value='Submit' > </input>
	
	</form>
	</div>
</body>
</html>


