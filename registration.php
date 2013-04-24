<!--
This page is where users are directed when they click the register button on index.php. The page is used to add new users to the users table, and also adds to either the participant or experimenter table, and associates the username with the p or e entry. Refer to this page for information on how the salt and password hash is generated and stored.
-->
<!DOCTYPE html>
<html>
<head>
<title>Registration page</title>
<!--include the jQuery library-->
<script src="jslibs/jquery-1.9.1.min.js"></script>
<!--include masked input library-->
<script src="jslibs/jquery.maskedinput.min.js"></script>
<script>
//do this when the document loads
$(document).ready(function() {
	$('#userType').change(function(eventData) {
		//clear previous options
		$('#additionalInfo input, #additionalInfo select').detach();
		$('#additionalInfo').html('');
		//determine which type of user the user has selected
		if($('#userType').val() == 'experimenter'){
			//add extra options
			$('#additionalInfo').append(
				'First Name<input type="text" name="first"><br />',
				'Middle Name<input type="text" name="middle"><br />',
				'Last Name<input type="text" name="last"><br />'
			);
		} else if($('#userType').val() == 'participant'){
			//add extra options
			$('#additionalInfo').append('First Name<input type="text" name="first"><br />');
			$('#additionalInfo').append('Middle Name<input type="text" name="middle"><br />');
			$('#additionalInfo').append('Last Name<input type="text" name="last"><br />');
			$('#additionalInfo').append('Street Address<input type="text" name="address"><br />');
			$('#additionalInfo').append('Phone Number<input type="text" name="phone" id="phone"><br />');
			$('#additionalInfo').append('Ethnicity<select name="ethnicity" id="ethnicity">');
			$('#ethnicity').append(
				'<option value="Asian">Asian</option>',
				'<option value="Black/African American">Black/African American</option>',
				'<option value="White/Caucasian">White/Caucasian</option>',
				'<option value="American Indian/Alaska Native">American Indian/Alaska Native</option>',
				'<option value="Native Hawaiian/Pacific Islander">Native Hawaiian/Pacific Islander</option>',
				'<option value="Hispanic/Latino">Hispanic/Latino</option>',
				'<option value="Unknown">Unknown</option>'
			);
			$('#additionalInfo').append('<br />');
			$('#additionalInfo').append('Gender<select name="gender" id="gender">');
			$('#gender').append('<option value="female">Female</option>',
				'<option value="male">Male</option>',
				'<option value="other">Other</option>'
			);
			$('#additionalInfo').append('<br />');
			$('#additionalInfo').append('Age<input type="text" name="age" id="age"><br />');
			$('#additionalInfo').append('Highest Grade Completed (numeric)<input type="text" name="grade" id="grade"><br />');
			$('#additionalInfo').append('May we contact you for further expriments?');
			$('#additionalInfo').append('<select name="contact" id="contact">');
			$('#contact').append('<option value="true">Yes</option>',
				'<option value="false">No</option>'
			);	
			$('#additionalInfo').append('<br />');
			//set input masks
			$("#phone").mask("(999)-999-9999");
			$("#age").mask("9?99");
			$("#grade").mask("9?9");
		}
	});
});
</script>
<?php
//set error reporting
ERROR_REPORTING(E_ALL);
ini_set("display_errors", 1);

session_start();

//check if already logged in, and redirect
if(isset($_SESSION['username'])){ 
	if($user_type == "experimenter"){
		header("Location: https://babbage.cs.missouri.edu/~cs3380sp13grp11/eHome.php");
	} else {
		header("Location: https://babbage.cs.missouri.edu/~cs3380sp13grp11/pHome.php");
	}
}

if (isset($_POST['submit'])){
	//connect to DB
	include 'connect.php';
	
	//get and sanitize the input
	$user = htmlspecialchars($_POST['username']);
	$password = htmlspecialchars($_POST['password']);
	$password_confirm = htmlspecialchars($_POST['confirm-password']);
	$email = htmlspecialchars($_POST['email']);
	$user_type = htmlspecialchars($_POST['user_type']);
	
	//make sure a type was selected
	if ($user_type == 'none'){
		echo "Error: You must select a user type\n<br>\n";
		echo "Click <a href='registration.php'>here</a> to go back to registration.\n";
		return;
	}
	
	//check if username is already in DB
	$result = pg_prepare($conn, "check_user", "SELECT * FROM database.users WHERE $1 = username");
	$result = pg_execute($conn, "check_user", array($user));
	//if the query returned any rows, the username already exists in the DB
	if(pg_num_rows($result) != 0){
		echo "Error: Username already in use, please choose another\n<br>\n";
		echo "Click <a href='registration.php'>here</a> to go back to registration.\n";
		return;
	}

	//check if password was actually entered
	if($password == ""){
		echo "Error: No password entered\n<br>\n";
		echo "Click <a href='registration.php'>here</a> to go back to registration.\n";
		return;
	}
	
	//check if passwords match
	if($password != $password_confirm){
		echo "Error: Passwords do not match, please try again\n<br >\n";
		echo "Click <a href='registration.php'>here</a> to go back to registration.\n";
		return;
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
	
	//redirect to home page
	if($user_type == "experimenter"){
		header("Location: https://babbage.cs.missouri.edu/~cs3380sp13grp11/eHome.php");
	} else {
		header("Location: https://babbage.cs.missouri.edu/~cs3380sp13grp11/pHome.php");
	}
}
?>
</head>
<body>
<form method='POST' action='registration.php'>
Username <input type='text' name='username' /><br />
Password <input type='password' name='password' /><br />
Confirm password <input type='password' name='confirm-password' /><br />
Email address <input type='text' name='email' /><br />
User type:
<select name='user_type' id='userType'>
  <option value="none">Select Type</option>
  <option value="participant">Participant</option>
  <option value="experimenter">Experimenter</option>
</select>
<div id='additionalInfo'>
</div>
<input type='submit' value='Submit' name='submit' />
</form>
Return to <a href='index.php'>login</a> page.<br />
</body>
</html>

