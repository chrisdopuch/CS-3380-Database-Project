<!--This user info page is for the experiments to be able to update/change their contact information, including their username,
password, and email address. It checks if there was a new username entered and if so it is udapted and stored in the database.
The password and email both have confirmations to be sure the user entered the correct information. The code checks that the new 
password and email match the confirmed password and email, respectively. If the new field for the password or email match the 
confirming fields then it is updated in the database.-->

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="style.css" />
<title> Experimenter User Info </title>
<style>

</style>
</head>
<body>
	<div id = 'parent'>
		<?php
			include 'header.php';
			top("experimenter");
		?>
	<div id = 'form' class = 'clearfix'>
	<form method= 'POST' action='eUserInfo.php'>
	<br></br>

<?php

//Connect to Database
	include 'connect.php';
	
	//Get input values
	$newpassword = htmlspecialchars($_POST['newpassword']);
	$password_confirm = htmlspecialchars($_POST['password_confirm']);
	$email = htmlspecialchars($_POST['email']);
	$newemail = htmlspecialchars($_POST['newemail']);
	$newemail_confirm = htmlspecialchars($_POST['newemail_confirm']);
	$first_name = htmlspecialchars($_POST['first_name']);
	$middle_name = htmlspecialchars($_POST['middle_name']);
	$last_name = htmlspecialchars($_POST['last_name']);
	
	//Get current username
	$current_username = trim($_SESSION['username']);
	
	//Get current contact information from database
	$query = pg_prepare($conn, "display_info", "SELECT first_name, middle_name, last_name FROM database.experimenters WHERE username = $1");
	$result = pg_execute($conn, "display_info", array($current_username));
	$row = pg_fetch_assoc($result);
	$f_name = $row['first_name'];
	$m_name = $row['middle_name'];
	$l_name  = $row['last_name'];

	$query = pg_prepare($conn, "display_email", "SELECT email FROM database.users WHERE username = $1");
	$result = pg_execute($conn, "display_email", array($current_username));
	$row = pg_fetch_assoc($result);
	$current_email = $row['email'];
		
		
if (isset($_POST['submit']))
{
	
	if(!empty($first_name))
	{

			$query = "UPDATE database.experimenters SET (first_name) = ($1) WHERE username = $2";
			
			$result = pg_prepare($conn, "update_first_name", $query);
			echo pg_last_error();
			$result = pg_execute($conn, "update_first_name", array($first_name, $current_username));
			
			if($result)
			{
				echo "You have successfully changed your first name \n</br>\n";
			}
			else
			{
				echo "Error: Your first name was not successfully changed.\n</br>\n";
				echo "First name failed to update".pg_last_error($conn); 
			}
	}
	
	if(!empty($middle_name))
	{

			$query = "UPDATE database.experimenters SET (middle_name) = ($1) WHERE username = $2";
			
			$result = pg_prepare($conn, "update_middle_name", $query);
			echo pg_last_error();
			$result = pg_execute($conn, "update_middle_name", array($middle_name, $current_username));
			
			if($result)
			{
				echo "You have successfully changed your middle name\n</br>\n";
			}
			else
			{
				echo "Error: Your middle name was not successfully changed.\n</br>\n";
				echo "Middle name failed to update".pg_last_error($conn);
			}
	}
	
	if(!empty($last_name))
	{

			$query = "UPDATE database.experimenters SET (last_name) = ($1) WHERE username = $2";
			
			$result = pg_prepare($conn, "update_last_name", $query);
			echo pg_last_error();
			$result = pg_execute($conn, "update_last_name", array($last_name, $current_username));
			
			if($result)
			{
				echo "You have successfully changed your last name\n</br>\n";
			}
			else
			{
				echo "Error: Your last name was not successfully changed.\n</br>\n";
				echo "Last name to failed to update".pg_last_error($conn); 
			}
	}
	
	if(!empty($newpassword) && !empty($password_confirm))
	{
		//check if passwords match
		if($newpassword != $password_confirm)
		{
			echo "Error: Passwords do not match, please try again\n<br >\n";
		}
		
		//If password match, update changed password in database
		if($newpassword == $password_confirm)
		{
			//seed random number generator
			mt_srand();
		
			//create random hashed salt value, and create password hash with salt
			$salt = sha1(mt_rand());
			$pwhash = sha1($salt . $newpassword);
			var_dump($current_username);
			
			//define the query to update the password and salt
				$query = "UPDATE database.users SET (pwhash, salt) = ($1, $2) WHERE username = $3";

				//prepare the query
				$result = pg_prepare($conn, "update_password_salt", $query);
				echo pg_last_error();
				//execute the query with user's values
				$result = pg_execute($conn, "update_password_salt", array($pwhash, $salt, $current_username));
			
			
			if($result)
			{
				echo "You have successfully changed your password\n</br>\n";
			}
			else
			{
				echo "Error: Password was not successfully changed.\n</br>\n";
				echo "Failed to update password".pg_last_error($conn); 
			}
			
		}
	}

		
	//If email match, update changed email in database
	if(!empty($newemail) && !empty($newemail_confirm))
	{		
	
		//check if e-mail match
		if($newemail != $newemail_confirm)
		{
			echo "Error: Emails do not match, please try again\n<br >\n";
		}
		else
		{
			//define the query to update the password and salt
			$query = "UPDATE database.users SET (email) = ($1) WHERE username = $2";
			//prepare the query
			$stmt = pg_prepare($conn, "update_email", $query);
			echo pg_last_error();
			//execute the query with user's values
			$result = pg_execute($conn, "update_email", array($newemail, $current_username));
		
				if($result)
				{
					echo "You have successfully changed your email\n</br>\n";
				}
				else
				{
					echo "Error: Email was not successfully changed.\n</br>\n";
					echo "pg_prepare failed: ".pg_last_error($conn);
				}
					
		}
	} 

	session_write_close();
	
}

?>

</br>
Please enter contact information to change:
	</br>
	<label for = 'first_name'> First Name: </label>
	<input type ='text' name='first_name'  placeholder = " <?php echo $f_name; ?>"></input>
	</br>
	<label for = 'middle_name'> Middle Name: </label>
	<input type ='text' name='middle_name' placeholder = " <?php echo $m_name; ?>"></input>
	</br>
	<label for = 'last_name'> Last Name: </label>
	<input type ='text' name='last_name' placeholder = " <?php echo $l_name; ?>"></input>
	</br>
	<label for = 'newpassword'> Change Password: </label>
	<input type ='password' name='newpassword' ></input>
	</br>
	<label for = 'password_confirm'> Confirm Password: </label>
	<input type ='password' name='password_confirm' ></input>
	</br>
	<label for = 'newemail'> Change Email: </label>
	<input type ='text' name='newemail' placeholder = " <?php echo $current_email; ?>"></input>
	</br> 
	<label for = 'newemail_confirm'> Confirm Email</label>
	<input type ='text' name='newemail_confirm'  placeholder = " <?php echo $current_email; ?>" ></input>
	</br> 
	</br>
	<input type='submit' name='submit' value='Submit' > </input>
	
	</form>
	</div>
	</div>
</body>
</html>


