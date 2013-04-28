<!--This user info page is for the participants to be able to update/change their contact information, including their username,
address, phone number, age, education, password, and email address. It checks if there was a new username entered and if so it is udapted and stored in the database.
The password and email both have confirmations to be sure the user entered the correct information. The code checks that the new 
password and email match the confirmed password and email, respectively. If the new field for the password or email match the 
confirming fields then it is updated in the database. Since the user is an participant they are redirected back to the participant home page.-->

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="style.css" />
<title> Participant User Info </title>
</head>
<body>

		<?php
			include 'header.php';
			top("participant");
		?>
		
	<div id = 'form' class = 'clearfix'>
	<form method= 'POST' action='pUserInfo.php'>
	<br></br>
	
	<?php

if (isset($_POST['submit']))
{
	
	//Connect to Database
	include 'connect.php';
	
	//Get input values
	$email = htmlspecialchars($_POST['email']);
	$newemail = htmlspecialchars($_POST['newemail']);
	$newemail_confirm = htmlspecialchars($_POST['newemail_confirm']);
	$address = htmlspecialchars($_POST['address']);
	$phone_number = htmlspecialchars($_POST['phone_number']);
	$age =htmlspecialchars($_POST['age']);
	$grade = htmlspecialchars($_POST['grade']);
	$newpassword = htmlspecialchars($_POST['newpassword']);
	$password_confirm = htmlspecialchars($_POST['password_confirm']);
	$first_name = htmlspecialchars($_POST['first_name']);
	$middle_name = htmlspecialchars($_POST['middle_name']);
	$last_name = htmlspecialchars($_POST['last_name']);
	$ethnicity = htmlspecialchars($_POST['ethnicity']);
	
	//Get current username
	$current_username = $_SESSION['username'];
	
	if(!empty($first_name))
	{

			$query = "UPDATE database.participants SET (first_name) = ($1) WHERE username = $2";
			
			$result = pg_prepare($conn, "update_first_name", $query);
			echo pg_last_error();
			$result = pg_execute($conn, "update_first_name", array($first_name, $current_username));
			
			if($result)
			{
				echo "You have successfully changed your first name.\n<br >\n";
			}
			else
			{
				echo "Error: Your first name was not successfully changed.\n<br >\n";
				echo "First name failed to update:".pg_last_error($conn); 
			}
	}
	
	if(!empty($middle_name))
	{

			$query = "UPDATE database.participants SET (middle_name) = ($1) WHERE username = $2";
			
			$result = pg_prepare($conn, "update_middle_name", $query);
			echo pg_last_error();
			$result = pg_execute($conn, "update_middle_name", array($middle_name, $current_username));
			
			if($result)
			{
				echo "You have successfully changed your middle name.\n<br >\n";
			}
			else
			{
				echo "Error: Your middle name was not successfully changed.\n<br >\n";
				echo "Middle name failed to update:".pg_last_error($conn);
			}
	}
	
	if(!empty($last_name))
	{

			$query = "UPDATE database.participants SET (last_name) = ($1) WHERE username = $2";
			
			$result = pg_prepare($conn, "update_last_name", $query);
			echo pg_last_error();
			$result = pg_execute($conn, "update_last_name", array($last_name, $current_username));
			
			if($result)
			{
				echo "You have successfully changed your last name.\n<br >\n";
			}
			else
			{
				echo "Error: Your last name was not successfully changed.\n<br >\n";
				echo "Last name to failed to update:".pg_last_error($conn); 
			}
	}
	
	if(!empty($newpassword) && !empty($password_confirm))
	{
		//check if passwords match
		if($newpassword != $password_confirm)
		{
			echo "Error: Passwords do not match, please try again.\n<br >\n";
		}
		
		//If password match, update changed password in database
		if($newpassword == $password_confirm)
		{
			//seed random number generator
			mt_srand();
		
			//create random hashed salt value, and create password hash with salt
			$salt = sha1(mt_rand());
			$pwhash = sha1($salt . $newpassword);
			
			//define the query to update the password and salt
				$query = "UPDATE database.users SET (pwhash, salt) = ($1, $2) WHERE username = $3";

				//prepare the query
				$result = pg_prepare($conn, "update_password_salt", $query);
				echo pg_last_error();
				//execute the query with user's values
				$result = pg_execute($conn, "update_password_salt", array($pwhash, $salt, $current_username));
			
			
			if($result)
			{
				echo "You have successfully changed your password.\n<br >\n";
			}
			else
			{
				echo "Error: Password was not successfully changed.\n<br >\n";
				echo "Failed to update password:".pg_last_error($conn); 
			}
			
		}
	}

		
	//If email match, update changed email in database
	if(!empty($newemail) && !empty($newemail_confirm))
	{		
	
		//check if e-mail match
		if($newemail != $newemail_confirm)
		{
			echo "Error: Emails do not match, please try again.\n<br >\n";
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
					echo "You have successfully changed your email.\n<br >\n";
				}
				else
				{
					echo "Error: Email was not successfully changed.\n<br >\n";
					echo "pg_prepare failed: ".pg_last_error($conn);
				}
					
		}
	}
	
	if(!empty($address))
	{

			$query = "UPDATE database.participants SET (address) = ($1) WHERE username = $2";
			
			$result = pg_prepare($conn, "update_address", $query);
			echo pg_last_error();
			$result = pg_execute($conn, "update_address", array($address, $current_username));
			
			if($result)
			{
				echo "You have successfully changed your address.\n<br >\n";
			}
			else
			{
				echo "Error: Your address was not successfully changed.\n<br >\n";
				echo "Address failed to update".pg_last_error($conn); 
			}
	}	

	if(!empty($phone_number))
	{

			$query = "UPDATE database.participants SET (fphone_number) = ($1) WHERE username = $2";
			
			$result = pg_prepare($conn, "update_phone_number", $query);
			echo pg_last_error();
			$result = pg_execute($conn, "update_phone_number", array($phone_number, $current_username));
			
			if($result)
			{
				echo "You have successfully changed your phone number.\n<br >\n";
			}
			else
			{
				echo "Error: Your phone number was not successfully changed.\n<br >\n";
				echo "Phone number failed to update".pg_last_error($conn); 
			}
	}
	
	if(!empty($age))
	{

			$query = "UPDATE database.participants SET (age) = ($1) WHERE username = $2";
			
			$result = pg_prepare($conn, "update_age", $query);
			echo pg_last_error();
			$result = pg_execute($conn, "update_age", array($age, $current_username));
			
			if($result)
			{
				echo "You have successfully changed your age.\n<br >\n";
			}
			else
			{
				echo "Error: Your age was not successfully changed.\n<br >\n";
				echo "Age failed to update".pg_last_error($conn); 
			}
	}
	
	if(!empty($grade))
	{

			$query = "UPDATE database.participants SET (education) = ($1) WHERE username = $2";
			
			$result = pg_prepare($conn, "update_phone_number", $query);
			echo pg_last_error();
			$result = pg_execute($conn, "update_phone_number", array($grade, $current_username));
			
			if($result)
			{
				echo "You have successfully changed your grade.\n<br >\n";
			}
			else
			{
				echo "Error: Your grade was not successfully changed.\n<br >\n";
				echo "Grade failed to update".pg_last_error($conn); 
			}
	}
	
	if(!empty($ethnicity))
	{
			$query = "UPDATE database.participants SET (ethnicity) = ($1) WHERE username = $2";
			
			$result = pg_prepare($conn, "update_ethnicity", $query);
			echo pg_last_error();
			$result = pg_execute($conn, "update_ethnicity", array($ethnicity, $current_username));
			
			if($result)
			{
				echo "You have successfully changed your ethnicity.\n<br >\n";
			}
			else
			{
				echo "Error: Your ethnicity was not successfully changed.\n<br >\n";
				echo "Ethnicity failed to update".pg_last_error($conn); 
			}
	}
	session_write_close();
	
}
?>

Please enter contact information to change:
	</br>
	<label for = 'first_name'> First Name: </label>
	<input type ='text' name='first_name' ></input>
	</br>
	<label for = 'middle_name'> Middle Name: </label>
	<input type ='text' name='middle_name' ></input>
	</br>
	<label for = 'last_name'> Last Name: </label>
	<input type ='text' name='last_name' ></input>
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
	<label for = 'address'> Change Address: </label>
	<input type ='text' name='address' ></input>
	</br> 
	<label for = 'phone_number'> Change Phone Number: </label>
	<input type ='text' name='phone' ></input>
	</br>
	<label for = 'ethnicity'>Change Ethnicity: </label>
			<select name = 'ethnicity'>
				<option value = "Asian">Asian</option>
				<option value = "Black">Black/African American</option>
				<option value = "White">White/Caucasian</option>
				<option value = "Native">American Indian/Alaska Native</option>
				<option value = "Islander">Nativa Hawaiian/Pacific Islander</option>
				<option value = "Hispanic">Hispanic/Latino</option>
				<option value = "Unknown">Unknown</option>
			</select>
	</br>
	<label for = 'age'> Change Age: </label>
	<input type ='text' name='age' ></input>
	</br> 	
	<label for = 'grade'> Change Grade: </label>
	<input type ='text' name='grade' ></input>
	</br> 
	<input type='submit' name='submit' value='Submit' > </input>
	
	</form>
	</div>
</body>
</html>


