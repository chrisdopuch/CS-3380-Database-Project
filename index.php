<html>
<head>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MU Psychological Sciences</title>
<meta name="keywords" content="" />
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700|Archivo+Narrow:400,700" rel="stylesheet" type="text/css">
<link href="style.css" rel="stylesheet" type="text/css" media="all" />
</head>
</head>
<script>
	function redirect(){
		window.location = "http://babbage.cs.missouri.edu/~cs3380sp13grp11/index.php"
	}
</script>
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
	$result = pg_prepare($conn, "authenticate", "SELECT salt, pwhash, user_type, (SELECT authenticated FROM database.participants AS i WHERE i.username = o.username) as authenticated FROM database.users AS o WHERE $1 = username");
	$result = pg_execute($conn, "authenticate", array($user));
	$row = pg_fetch_assoc($result);
	$salt = $row['salt'];
	$pwhash = $row['pwhash'];
	$user_type = trim($row['user_type']);
	if($user_type == "participant"){
		$auth = $row['authenticated'];
		if($auth == 'f'){
			$message = "Error: You must authenticate your account via email before you can log in!";
			echo "<script> alert('$message'); redirect();</script>\n";
			return;
		}
	}
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
<div id="banner-wrapper"></div>
	<div id="header-wrapper">
		<div id="header" class="container">
		<div id="menu"></div>
		</div>
	</div>
	<div id="page" class="container">
 		<div id="content">
	 		<div class="post">
			<h2 class="title"><a>Welcome to the Department of Psychological Science at Mizzou!</a> </h2>
            		<p>&nbsp;</p>
            		<h3>
			
			<a>LOGIN:</a>
			</h3>

			<br/>
			<form action= "index.php" method='POST' name='submit'>
			Username:  <input type="text"name="username"><br>
			Password:  <input type="password"name="password"><br>
			<input type='submit' value= 'Submit' name='submit'>
			</form>
			</br>
			New Here? Click below to Register!				
			<form action="registration.php" method='POST' name='register'>
			<input type='submit' value="Register" name='register'>
			</form>
			</p>
			<p>&nbsp;</p>
        
	<p class="meta">&nbsp;</p>
	<div style="clear: both;"></div>
	</div>
  </div>	
  <!-- end #content -->

  <!-- Create a side column to display contact info-->
  <div id="sidebar">
	<ul>
	<li>
	<h2>Contact Information:</h2>
		<ul>
		<li>
		<div>
		  <div>
		    <div>
		       <div>
		       	   <li>
		             <div>
		              <h3> 
		              <p> Department of: </br>Psychological Sciences </br>210 McAlester Hall </br>Columbia, MO 65211-2500 </br>Phone: 573-882-6860 </br>Fax: 573-882-7710</p>
				</h3>
		              </div>
		            </li>
		         </div>
		    </div>
		  </div>
		</div>
		</li>
		</ul>
	</li>
	<li>
	<h2>&nbsp;</h2>
</li>
<li> </li>
</ul>
</div>
<!-- end #sidebar -->


</div>

<div id="footer" class="container">
<?php include 'footer.php';?>
</div>
</body>
</html>
