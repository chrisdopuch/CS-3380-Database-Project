<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="style.css" />
<title>MU Psychological Sciences</title>
</head>

<?php 
//set error reporting
ERROR_REPORTING(E_ALL);
ini_set("display_errors", 1);

session_start();

//check if already logged in, and redirect
if(isset($_SESSION['username'])){ 
	$user_type = trim($_SESSION['user_type']);
	if($user_type == "experimenter"){
		header("Location: https://babbage.cs.missouri.edu/~cs3380sp13grp11/eHome.php");
		exit;
	} else if($user_type == "participant") {
		header("Location: https://babbage.cs.missouri.edu/~cs3380sp13grp11/pHome.php");
		exit;
	}
}

if (isset($_POST['submit'])){
	//connect to the DB
	include 'connect.php'; 

	//get and sanitize input
	$user = htmlspecialchars($_POST['username']);
	$password = htmlspecialchars($_POST['password']);

	//check if user exists
	$result = pg_prepare($conn, "check_user", "SELECT * FROM database.users WHERE $1 = username");
	$result = pg_execute($conn, "check_user", array($user));
	//if the query returned no rows, the user doesn't exist
	if(pg_num_rows($result) == 0 OR $user == ""){
		echo "Error: Username not valid, please try again\n<br>\n";
		echo "Click <a href='index.php'>here</a> to go back to login.\n";
		return;
	}

	//check that a password was actually entered
	if($password == ""){
		echo "Error: No password entered\n<br>\n";
		echo "Click <a href='index.php'>here</a> to go back to login.\n";
		return;
	}

	//check if the username given matches with the password given
	$result = pg_prepare($conn, "authenticate", "SELECT salt, pwhash, user_type FROM database.users WHERE $1 = username");
	$result = pg_execute($conn, "authenticate", array($user));
	$row = pg_fetch_assoc($result);
	$salt = $row['salt'];
	$pwhash = $row['pwhash'];
	$user_type = $row['user_type'];
	//convert the password entered to a salted hash
	$local_hash = sha1($salt . $password);
	//check the local hash against the hashed password in the DB
	if($local_hash != $pwhash){
		echo "Error: Invalid password/username combination\n<br>\n";
		echo "Click <a href='index.php'>here</a> to go back to login.\n";
		return;
	}	
	//store username in session variable
	$_SESSION['username'] = $user;
	$_SESSION['user_type'] = $user_type;
	
	session_write_close();
	
	//redirect to home page
	if($user_type == "experimenter"){
		header("Location: https://babbage.cs.missouri.edu/~cs3380sp13grp11/eHome.php");
		exit;
		
	} else if ($user_type == "participant"){
		header("Location: https://babbage.cs.missouri.edu/~cs3380sp13grp11/pHome.php");
		exit;
	}
}
?>

<body>
<div id="container">
		<div id="mainpic">
        	<h1>&nbsp;</h1>
            <h2>&nbsp;</h2>
		</div>   
 
 
		<div id="content">
 			<p>&nbsp;</p>
            <p>
			LOGIN:
			<br/>
			<form action= "index.php" method='POST' name='submit'>
			Username:  <input type="text"name="username"><br>
			Password:  <input type="password"name="password"><br>
			<input type='submit' value= 'Submit' name='submit'>
			</form>
	
			<form action="registration.php" method='POST' name='register'>
			<input type='submit' value="Register" name='register'>
			</form>
			</p>
			<p>&nbsp;</p>

  
    		<h2><u>About </u></h2>
    		<p>&nbsp;</p>
   			<p>This is where we put the about info..</p>
    
    		<h3>&nbsp;</h3>
			<h3>&nbsp;</h3>
			<p>&nbsp;</p>
    	    <h3>&nbsp;</h3>
		</div>
</div>
<?php include 'footer.php';?>

</body>
</html>
