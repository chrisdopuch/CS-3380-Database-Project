<?php

//This user info page is for the participants to be able to update/change their contact information, including their username,
//address, phone number, age, education, password, and email address. It checks if there was a new username entered and if so it is udapted and stored in the database.
//The password and email both have confirmations to be sure the user entered the correct information. The code checks that the new 
//password and email match the confirmed password and email, respectively. If the new field for the password or email match the 
//confirming fields then it is updated in the database. Since the user is an participant they are redirected back to the participant home page.

session_start();

if (isset($_POST['submit']))
{
	//Connect to Database
	include 'connect.php';
	
	//Get input values
	$username = htmlspecialchars($_POST['username']);
	$newpassword = htmlspecialchars($_POST['newpassword']);
	$password_confirm = htmlspecialchars($_POST['password_confirm']);
	$email = htmlspecialchars($_POST['email']);
	$newemail = htmlspecialchars($_POST['newemail']);
	$newemail_confirm = htmlspecialchars($_POST['newemail_confirm']);
	$address = htmlspecialchars($_POST['address']);
	$phonenumber = htmlspecialchars($_POST['phonenumber']);
	$age =htmlspecialchars($_POST['age']);
	$grade = htmlspecialchars($_POST['grade']);
	
	//Get current Variables
	$current_username = $_SESSION['username'];
	
	if($username == "")
	{
		continue;
	}
	
	if ($username != "");
	{

			$result = pg_prepare($conn, "update_username","UPDATE database.users SET username = $1 WHERE username = $2");
			$result = pg_execute($conn, "update_username", array($username, $current_username)); 
			
			if($result != NULL)
			{
				echo "You have successfully changed your username";
				echo "Click <a href 'pUserInfophp'>here to return to user info page</a>\n";
			}
			else
			{
				echo "Error: User was not successfully changed";
				echo "pg_prepare failed: ".pg_last_error($conn);
			}
		
	}
	
	if($newpassword || $password_confirm == " ")
	{	
		continue;
	}
	
	if($newpassword && $password_confirm != " ")
	{
		//check if passwords match
		if($newpassword != $password_confirm)
		{
			echo "Error: Passwords do not match, please try again\n<br >\n";
			echo "Click <a href 'pUserInfo.php'>here to return to user info page</a>\n";
		}
		
		//If password match, update changed password in database
		if($newpassword == $password_confirm)
		{
			//seed random number generator
			mt_srand();
		
			//create random hashed salt value, and create password hash with salt
			$salt = sha1(mt_rand());
			$pwhash = sha1($salt . $password);
			
			//Update pwhash in database
			$result = pg_prepare($conn, "get_pwhash","SELECT pwhash FROM database.users WHERE username = $1");
			$result = pg_execute($conn, "get_pwhash", array($current_username));
			$row = pg_fetch_assoc($result);
			$pwhash = $row['pwhash'];
			
			//Update salt in database
			$result = pg_prepare($conn, "get_salt","SELECT salt FROM database.users WHERE username = $1");
			$result = pg_execute($conn, "get_salt", array($current_username));
			$row = pg_fetch_assoc($result);
			$salt = $row['salt'];
			
			if($result != NULL)
			{
				echo "You have successfully changed your password";
			}
			else
			{
				echo "Error: Password was not successfully changed.";
				echo "pg_prepare failed: ".pg_last_error($conn);; 
			}
			
		}
	}
	
	if($newemail || $newemail_confirm == " ")
	{	
		continue;
	}
	
	//check if e-mail match
	if($newemail != $newemail_confirm)
	{
		echo "Error: Emails do not match, please try again\n<br >\n";
		echo "Click <a href 'pUserInfophp'>here to return to user info page</a>\n";
	}
		
	//If email match, update changed email in database
	if($newemail == $newemail_confirm)
	{		
			$result = pg_prepare($conn, "email","SELECT email FROM database.users WHERE username = $1");
			$result = pg_execute($conn, "email", array( $current_username));
			$row = pg_fetch_assoc($result);
			$newemail = $row['email'];
			
		if($result != NULL)
		{
			echo "You have successfully changed your email";
			echo "Click <a href 'homepagepart.php'>here</a>\n";
		}
		else
		{
			echo "Error: Email was not successfully changed.";
			echo "pg_prepare failed: ".pg_last_error($conn);
		}
			
	
	}
	
	if ($address != " ")
	{
		$result = pg_prepare($conn, "address","SELECT address FROM database.participants WHERE username = $1");
		$result = pg_execute($conn, "email", array( $current_username));
		$row = pg_fetch_assoc($result);
		$address = $row['address'];
		
		if($result != NULL)
		{
			echo "You have successfully changed your addressl";
			echo "Click <a href 'homepagepart.php'>here</a>\n";
		}
		else
		{
			echo "Error: Address was not successfully changed.";
			echo "pg_prepare failed: ".pg_last_error($conn);
		}
	}
	
	if ($phonenumber != " ")
	{
		$result = pg_prepare($conn, "phone_number","SELECT phone_number FROM database.participants WHERE username = $1");
		$result = pg_execute($conn, "phone_number", array( $current_username));
		$row = pg_fetch_assoc($result);
		$phonenumber = $row['phone_number'];
		
		if($result != NULL)
		{
			echo "You have successfully changed your phone number";
			echo "Click <a href 'homepagepart.php'>here</a>\n";
		}
		else
		{
			echo "Error: Phone number was not successfully changed.";
			echo "pg_prepare failed: ".pg_last_error($conn);
		}
	}

	if ($age != " ")
	{
		$result = pg_prepare($conn, "age","SELECT age FROM database.participants WHERE username = $1");
		$result = pg_execute($conn, "age", array( $current_username));
		$row = pg_fetch_assoc($result);
		$age = $row['age'];
		
		if($result != NULL)
		{
			echo "You have successfully changed your age";
			echo "Click <a href 'homepagepart.php'>here</a>\n";
		}
		else
		{
			echo "Error: Age was not successfully changed.";
			echo "pg_prepare failed: ".pg_last_error($conn);
		}
	}
	
	if ($grade != " ")
	{
		$result = pg_prepare($conn, "grade","SELECT grade FROM database.participants WHERE username = $1");
		$result = pg_execute($conn, "grade", array( $current_username));
		$row = pg_fetch_assoc($result);
		$grade = $row['grade'];
		
		if($result != NULL)
		{
			echo "You have successfully changed your grade";
			echo "Click <a href 'homepageadmin.php'>here</a>\n";
		}
		else
		{
			echo "Error: Grade was not successfully changed.";
			echo "pg_prepare failed: ".pg_last_error($conn);
		}
	}
	session_write_close();
	
}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="style.css" />
<title> Participant User Info </title>
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


	</br>
	<label  for = 'username'> Change Username: </label>
	<input type ='text' name='username' ></input>
	</br>
	<label for = 'newpassword'> Change Password: </label>
	<input type ='text' name='newpassword' ></input>
	</br>
	<label for = 'password_confirm'> Confirm Password: </label>
	<input type ='text' name='password_confirm' ></input>
	</br>
	<label for = 'newemail'> Change Email: </label>
	<input type ='text' name='newemail' ></input>
	</br> 
	<label for = 'newemail_confirm'> Confirm Email: </label>
	<input type ='text' name='newemail_confirm' ></input>
	</br> 
	<label for = 'newemail_confirm'> Change Address: </label>
	<input type ='text' name='address' ></input>
	</br> 
	<label for = 'newemail_confirm'> Change Phone Number: </label>
	<input type ='text' name='phone' ></input>
	</br>
	<label for = 'newemail_confirm'> Change Age: </label>
	<input type ='text' name='age' ></input>
	</br> 	
	<label for = 'grade'> Confirm Grade: </label>
	<input type ='text' name='grade' ></input>
	</br> 
	<input type='submit' name='submit' value='Submit' > </input>
	
	</form>
	</div>
</body>
</html>


