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
<!DOCTYPE html>
<html>
<head>
<title>Registration page</title>
</head>
<body>
<form method='POST' action='registration.php'>
Username: <input type='text' name='username' /><br />
Password: <input type='password' name='password' /><br />
Confirm password: <input type='password' name='confirm-password' /><br />
Email address: <input type='text' name='email' /><br />
User type:
<select name='user_type'>
  <option value="participant">Participant</option>
  <option value="experimenter">Experimenter</option>
</select>
<input type='submit' value='Submit' name='submit' />
</form>
Return to <a href='index.php'>login</a> page.<br />
</body>
</html>

