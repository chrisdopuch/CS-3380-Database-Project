<!-- 
	Author: Adam Faszl
	Date: sometime in april
	Assignment: project tool
-->

<!DOCTYPE html>
<html>
<head>
	<title>MU PSYCH SCIENCES DATA EDIT UTILITY</title>
	<link rel="stylesheet" type="text/css" href="style.css">

</head>
<body>

<?php
	include 'connect.php';
	include 'header.php';
	top("experimenter"); ?>

	<div id='main' class='clearfix'>
		<h2>DATAEDIT EXEC</h2><br />
<?php
	$action = $_GET['action']; // add, edit, remove, add_save, edit_save
	$type = $_GET['type']; //experimenters, experiments, participants, sessions, users, locations
	$val = $_GET['val']; //either the eid, expid, pid, sid, username, or lid respective to type (above)

	//Do different things for different actions
	switch ($action){
		//Add action
		case "add":
			//do different things for different tables
			switch ($type){
				case "experimenters":
					//Display add form
					echo "\t<form method='POST' action='dataedit_exec.php?action=add_save&type=experimenters'\n";
					echo "\t\t<input type='hidden' name='action' value='add_save' />\n";
					echo "\t\t<p>Enter data for the experimenter to be added: </p><br />\n";
					echo "\t\t<table border='1'>\n";
					echo "\t\t\t<tr><td>Name</td><td><input type='text' name='name' /></td></tr>\n";
					echo "\t\t\t<tr><td>Username</td><td><input type='text' name='username' /></td></tr>\n";
					echo "\t\t</table>\n";
					echo "\t\t<input type='submit' value='Save' />\n";
					echo "\t\t<input type='button' value='Cancel' onclick=\"top.location.href='dataedit.php';\" />\n";
					echo "\t</form>\n";
					break;
				case "experiments":
					//Display add form
					echo "\t<form method='POST' action='dataedit_exec.php?action=add_save&type=experiments'\n";
					echo "\t\t<input type='hidden' name='action' value='add_save' />\n";
					echo "\t\t<p>Enter data for the experiment to be added: </p><br />\n";
					echo "\t\t<table border='1'>\n";
					echo "\t\t\t<tr><td>Name</td><td><input type='text' name='name' /></td></tr>\n";
					echo "\t\t\t<tr><td>Payment</td><td><input type='text' name='payment' /></td></tr>\n";
					echo "\t\t\t<tr><td>Requirements</td><td><input type='text' name='requirements' /></td></tr>\n";
					echo "\t\t</table>\n";
					echo "\t\t<input type='submit' value='Save' />\n";
					echo "\t\t<input type='button' value='Cancel' onclick=\"top.location.href='dataedit.php';\" />\n";
					echo "\t</form>\n";
					break;
				case "participants":
					//Display add form
					echo "\t<form method='POST' action='dataedit_exec.php?action=add_save&type=participants'\n";
					echo "\t\t<input type='hidden' name='action' value='add_save' />\n";
					echo "\t\t<p>Enter data for the participant to be added: </p><br />\n";
					echo "\t\t<table border='1'>\n";
					echo "\t\t\t<tr><td>Address</td><td><input type='text' name='address' /></td></tr>\n";
					echo "\t\t\t<tr><td>Phone number</td><td><input type='text' name='phone_number' /></td></tr>\n";
					echo "\t\t\t<tr><td>Ethnicity</td><td><input type='text' name='ethnicity' /></td></tr>\n";
					echo "\t\t\t<tr><td>Gender</td><td><input type='text' name='gender' /></td></tr>\n";
					echo "\t\t\t<tr><td>Age</td><td><input type='text' name='age' /></td></tr>\n";
					echo "\t\t\t<tr><td>Education</td><td><input type='text' name='education' /></td></tr>\n";
					echo "\t\t\t<tr><td>Username</td><td><input type='text' name='username' /></td></tr>\n";					
					echo "\t\t</table>\n";
					echo "\t\t<input type='submit' value='Save' />\n";
					echo "\t\t<input type='button' value='Cancel' onclick=\"top.location.href='dataedit.php';\" />\n";
					echo "\t</form>\n";
					break;
				case "sessions":
					//Display add form
					echo "\t<form method='POST' action='dataedit_exec.php?action=add_save&type=sessions'\n";
					echo "\t\t<input type='hidden' name='action' value='add_save' />\n";
					echo "\t\t<p>Enter data for the session to be added: </p><br />\n";
					echo "\t\t<table border='1'>\n";
					echo "\t\t\t<tr><td>session_date</td><td><input type='text' name='session_date' /></td></tr>\n";
					echo "\t\t\t<tr><td>start_time</td><td><input type='text' name='start_time' /></td></tr>\n";
					echo "\t\t\t<tr><td>end_time</td><td><input type='text' name='end_time' /></td></tr>\n";
					echo "\t\t\t<tr><td>lid</td><td><input type='text' name='lid' /></td></tr>\n";
					echo "\t\t\t<tr><td>eid</td><td><input type='text' name='eid' /></td></tr>\n";
					echo "\t\t\t<tr><td>expid</td><td><input type='text' name='expid' /></td></tr>\n";
					echo "\t\t\t<tr><td>pid</td><td><input type='text' name='pid' /></td></tr>\n";					
					echo "\t\t</table>\n";
					echo "\t\t<input type='submit' value='Save' />\n";
					echo "\t\t<input type='button' value='Cancel' onclick=\"top.location.href='dataedit.php';\" />\n";
					echo "\t</form>\n";
					break;
				case "users":
					echo "\tuse the registration form";
					echo "\tReturn to <a href='dataedit.php'>dataedit</a>.";
					break;
				case "locations":
					//Display add form
					echo "\t<form method='POST' action='dataedit_exec.php?action=add_save&type=locations'\n";
					echo "\t\t<input type='hidden' name='action' value='add_save' />\n";
					echo "\t\t<p>Enter data for the location to be added: </p><br />\n";
					echo "\t\t<table border='1'>\n";
					echo "\t\t\t<tr><td>room</td><td><input type='text' name='room' /></td></tr>\n";
					echo "\t\t\t<tr><td>building</td><td><input type='text' name='building' /></td></tr>\n";
					echo "\t\t</table>\n";
					echo "\t\t<input type='submit' value='Save' />\n";
					echo "\t\t<input type='button' value='Cancel' onclick=\"top.location.href='dataedit.php';\" />\n";
					echo "\t</form>\n";
					break;

				default:
					echo "\tYou shouldn't be here. <br />\n";
					echo "\tReturn to <a href='dataedit.php'>dataedit</a>.";
					exit(1);	
			}

			break;
		//Edit action
		case "edit":
			//do different things for different tables
			switch ($type){
				//experimenter table
				case "experimenters":
					//define the query to select the country of interest
					$query = "SELECT * FROM database.experimenters WHERE eid = $1";
					//prepare the query
					$stmt = pg_prepare($conn, "select_e", $query);
					//execute query with the desired country code
					$result = pg_execute($conn, "select_e", array($val));
					//print the edit form
					pgResultsToEditableTableForm($result, $type, $val);
					break;

				//experiment table
				case "experiments":
					//define the query to select the city of interest
					$query = "SELECT * FROM database.experiments WHERE expid = $1";
					//prepare the query
					$stmt = pg_prepare($conn, "select_exp", $query);
					//execute query with the desired city id code
					$result = pg_execute($conn, "select_exp", array($val));
					//print the edit form
					pgResultsToEditableTableForm($result, $type, $val);
					break;

				//participant table
				case "participants":
					//define the query to select the language of interest
					$query = "SELECT * FROM database.participants WHERE pid = $1";
					//prepare the query
					$stmt = pg_prepare($conn, "select_p", $query);
					//execute query with the desired country code and language name
					$result = pg_execute($conn, "select_p", array($val));
					//print the edit form
					pgResultsToEditableTableForm($result, $type, $val);
					break;

				//session table
				case "sessions":
					$query = "SELECT * FROM database.sessions WHERE sid = $1";
					
					//prepare the query
					$stmt = pg_prepare($conn, "select_e", $query);
					//execute query with the desired country code
					$result = pg_execute($conn, "select_e", array($val));
					//print the edit form
					pgResultsToEditableTableForm($result, $type, $val);
					break;

				//user table
				case "users":
					$query = "SELECT * FROM database.users WHERE username = $1";
					
					//prepare the query
					$stmt = pg_prepare($conn, "select_u", $query);
					//execute query with the desired country code
					$result = pg_execute($conn, "select_u", array($val));
					//print the edit form
					pgResultsToEditableTableForm($result, $type, $val);
					break;

				//location table
				case "locations":
					$query = "SELECT * FROM database.locations WHERE lid = $1";

					//prepare the query
					$stmt = pg_prepare($conn, "select_l", $query);
					//execute query with the desired country code
					$result = pg_execute($conn, "select_l", array($val));
					//print the edit form
					pgResultsToEditableTableForm($result, $type, $val);

					break;

				default:
					echo "\tYou shouldn't be here. <br />\n";
					echo "\tReturn to <a href='dataedit.php'>search page</a>.";
					exit(1);
			}
			break;

		//Remove action
		case "remove":
			//do different things for different tables
			switch ($type){
				//experimenters table
				case "experimenters":
					//define the delete query
					$query = "DELETE FROM database.experimenters WHERE eid = $1";
					//prepare the query
					$stmt = pg_prepare($conn, "delete_e", $query);
					//execute query 
					$result = pg_execute($conn, "delete_e", array($val));
					break;

				//experiments table
				case "experiments":
					$query = "DELETE FROM database.experiments WHERE expid = $1";
					//prepare the query
					$stmt = pg_prepare($conn, "delete_exp", $query);
					//execute query 
					$result = pg_execute($conn, "delete_exp", array($val));
					break;

				//participants table
				case "participants":
					$query = "DELETE FROM database.participants WHERE pid = $1";
					//prepare the query
					$stmt = pg_prepare($conn, "delete_p", $query);
					//execute query 
					$result = pg_execute($conn, "delete_p", $vals);
					break;

				//sessions table
				case "sessions":
					$query = "DELETE FROM database.sesions WHERE sid = $1";
					//prepare the query
					$stmt = pg_prepare($conn, "delete_s", $query);

					//execute query with the desired country code and language name
					$result = pg_execute($conn, "delete_s", $vals);

					break;

				//users table
				case "users":
					$query = "DELETE FROM database.users WHERE username = $1";
					//prepare the query
					$stmt = pg_prepare($conn, "delete_u", $query);
					//$val is of the form COUNTRY_CODE:LANGUAGE-- break this into an Array(COUNTRY_CODE, LANGUAGE)
					$vals = explode(":", $val);
					//execute query 
					$result = pg_execute($conn, "delete_u", $vals);
					break;

				//locations table
				case "locations":
					$query = "DELETE FROM database.locations WHERE lid = $1";
					//prepare the query
					$stmt = pg_prepare($conn, "delete_u", $query);
					//execute query 
					$result = pg_execute($conn, "delete_u", $vals);
					break;

				default:
					echo "\tYou shouldn't be here. <br />\n";
					echo "\tReturn to <a href='dataedit.php'>search page</a>.";
					exit(1);
			}
			//check to see if the query was successful
			if ($result){
				echo "\tDelete was successful. <br />\n";
				echo "\tReturn to <a href='dataedit.php'>search page</a>.";
			}
			else{
				echo "\tDelete FAILED: ".pg_last_error($conn)."<br />\n";
				echo "\tReturn to <a href='dataedit.php'>search page</a>.";
			}
			break;

		//Add_save action (handles submitting of the insert form)					
		case "add_save":
			//do different things for different tables
			switch ($type){
				//experimenters table
				case "experimenters":
					//get postvars
					$name = $_POST['name'];
					$country_code = $_POST['username'];
			
					//define the insert query
					$query = "INSERT INTO database.experimenters (name, username) VALUES ($1, $2)";
					//prepare the query
					$stmt = pg_prepare($conn, "insert_e", $query);
					//execute the query
					$result = pg_execute($conn, "insert_e", array($name, $username));
					
					break;

				//experiments table
				case "experiments":
					$name = $_POST['name'];
					$payment = $_POST['username'];
					$retuirements = $_POST['requirements'];

					$query = "INSERT INTO database.experiments (name, payment, requirements) VALUES ($1, $2, $3)";
					//prepare the query
					$stmt = pg_prepare($conn, "insert_exp", $query);
					//execute query 
					$result = pg_execute($conn, "insert_exp", array($name, $payment, $requirement));
					break;

				//participants table
				case "participants":
					$address = $_POST['address'];
					$phone_number = $_POST['phone_number'];
					$ethnicity = $_POST['ethnicity'];
					$gender = $_POST['gender'];
					$age = $_POST['age'];
					$education = $_POST['education'];
					$username = $_POST['username'];

					$query = "INSERT INTO database.participants (address, phone_number, ethnicity, gender, age, education, username) VALUES ($1, $2, $3, $4, $5, $6, $7)";
					//prepare the query
					$stmt = pg_prepare($conn, "insert_p", $query);
					//execute query 
					$result = pg_execute($conn, "insert_p", array($address, $phone_number, $ethnicity, $gender, $age, $education, $username));
					break;

				//sessions table
				case "sessions":
					$session_date = $_POST['session_date'];
					$start_time = $_POST['start_time'];
					$end_time = $_POST['end_time'];
					$lid = $_POST['lid'];
					$eid = $_POST['eid'];
					$expid = $_POST['expid'];
					$pid = $_POST['pid'];

					$query = "INSERT INTO database.sessions (session_date, start_time, end_time, lid, eid, expid, pid) VALUES ($1, $2, $3, $4, $5, $6, $7)";
					//prepare the query
					$stmt = pg_prepare($conn, "insert_p", $query);
					//execute query 
					$result = pg_execute($conn, "insert_p", array($session_date, $start_time, $end_time, $lid, $eid, $expid, $pid));	
					break;

				//users table
				case "users":
					echo "\tuse the registration form";
					echo "\tReturn to <a href='dataedit.php'>dataedit</a>.";
					break;

				//locations table
				case "locations":
					$room = $_POST['room'];
					$building = $_POST['building'];

					$query = "INSERT INTO database.locations (room, building) VALUES ($1, $2)";
					//prepare the query
					$stmt = pg_prepare($conn, "insert_l", $query);
					//execute query 
					$result = pg_execute($conn, "insert_l", array($room, $building));
					break;

				default:
					echo "\tYou shouldn't be here in this place. <br />\n";
					echo "\tReturn to <a href='dataedit.php'>search page</a>.";
					exit(1);
			}
			
			//check to see if the query was successful
			if ($result){
				echo "\tInsert was successful. <br />\n";
				echo "\tReturn to <a href='dataedit.php'>search page</a>.";
			}
			else{
				echo "\tInsert FAILED: ".pg_last_error($conn)."<br />\n";
				echo "\tReturn to <a href='dataedit.php'>search page</a>.";
			}
			break;
		//Edit_save action (handles submitting of the edit form)
		case "edit_save":
			$type = $_GET['type'];
			$val = $_GET['val'];
			//Do different things for different tables
			switch ($type){
				//experimenters
				case "experimenters":
					$first_name	= $_POST['first_name'];
					$middle_name = $_POST['middle_name'];
					$last_name = $_POST['last_name'];
					$username = $_POST['username'];

					$query = "UPDATE database.experimenters SET (first_name, middle_name, last_name, username) = ($1, $2, $3, $4) WHERE eid = $5";
					//prepare the query
					$stmt = pg_prepare($conn, "update_e", $query);
					echo pg_last_error();
					//execute the query with user's values
					$result = pg_execute($conn, "update_e", array($first_name, $middle_name, $last_name, $username, $val));
				break;

				//experiments
				case "experiments":
					//get postvars
					$payment = $_POST['payment'];
					$name = $_POST['name'];
					$requirements = $_POST['requirements'];

					//define the query to update the city table
					$query = "UPDATE database.experiments SET (payment, name, requirements) = ($1, $2, $3) WHERE expid = $4";
					//prepare the query
					$stmt = pg_prepare($conn, "update_exp", $query);
					//execute the query with user's values
					$result = pg_execute($conn, "update_exp", array($payment, $name, $requirements, $val));
					break;

				//participants
				case "participants":
					$first_name	= $_POST['first_name'];
					$middle_name = $_POST['middle_name'];
					$last_name = $_POST['last_name'];
					$address = $_POST['address'];
					$phone_number = $_POST['phone_number'];
					$ethnicity = $_POST['ethnicity'];
					$gender = $_POST['gender'];
					$age = $_POST['age'];
					$education = $_POST['education'];
					$contact_again = $_POST['contact_again'];
					$username = $_POST['username'];

					$query = "UPDATE database.participants SET (first_name, middle_name, last_name, address, phone_number, ethnicity, gender, age, education, contact_again, username) = ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11) WHERE pid = $12";
					//prepare the query
					$stmt = pg_prepare($conn, "update_p", $query);
					//execute the query with user's values
					$result = pg_execute($conn, "update_p", array($first_name, $middle_name, $last_name, $address, $phone_number, $ethnicity, $gender, $age, $education, $contact_again, $username, $val));
					break;

				//sessions table
				case "sessions":
					//get postvars
					$session_date = $_POST['session_date'];
					$start_time = $_POST['start_time'];
					$end_time = $_POST['end_time'];
					$lid = $_POST['lid'];
					$eid = $_POST['eid'];
					$expid = $_POST['expid'];
					$pid = $_POST['pid'];
					
					//define the query to update the country table
					$query = "UPDATE database.sessions SET (session_date, start_time, end_time, lid, eid, expid, pid) = ($1, $2, $3, $4, $5, $6, $7) WHERE sid = $8";
					//prepare the query
					$stmt = pg_prepare($conn, "update_s", $query);
					//execute the query with user's values
					$result = pg_execute($conn, "update_s", array($session_date, $start_time, $end_time, $lid, $eid, $expid, $pid, $val));
					break;

				//city table
				case "users":
					//get postvars
					$pwhash = $_POST['pwhash'];
					$salt = $_POST['salt'];
					$user_type = $_POST['user_type'];
					$email = $_POST['email'];

					//define the query to update the city table
					$query = "UPDATE database.users SET (pwhash, salt, user_type, email) = ($1, $2, $3, $4) WHERE username = $5";
					//prepare the query
					$stmt = pg_prepare($conn, "update_u", $query);
					//execute the query with user's values
					$result = pg_execute($conn, "update_u", array($pwhash, $salt, $user_type, $email, $val));
					break;

				//language table
				case "locations":
					//get postvars
					$room = $_POST['room'];
					$building = $_POST['building'];

					//define the query to update the language table
					$query = "UPDATE database.locations SET (room, building) = ($1, $2) WHERE lid = $3";
					//prepare the query
					$stmt = pg_prepare($conn, "update_l", $query);
					//execute query with the desired country code and language name
					$result = pg_execute($conn, "update_l", array($room, $building, $val));
					break;

				default:
					echo "\tYou shouldn't be here. <br />\n";
					echo "\tReturn to <a href='dataedit.php'>search page</a>.";
					exit(1);
			}
			//Check to see if the query was successful
			if ($result){
				echo "\tUpdate was successful. <br />\n";
				echo "\tReturn to <a href='dataedit.php'>search page</a>.";
			}
			else{
				echo "\tUpdate FAILED: ".pg_last_error($conn)."<br />\n";
				echo "\tReturn to <a href='dataedit.php'>search page</a>.";
			}
			break;
		//Any other action specified
		default:
			echo "<a href='dataedit.php'>You shouldn't be here (bad type). Click this link to go back.</a>\n";
	}

	//Prints a table from a pg query result. $type refers to the table from which the result was returned. $val is passed in only for use in building URLs for GET
	function pgResultsToEditableTableForm($result, $type, $val){		
		//make sure $result is not null
		if (!$result){
			die("Unable to execute query: " . pg_last_error($conn));
		}

		//Print form
		echo "\t<form method='POST' action='dataedit_exec.php?action=edit_save&type=".$type."&val=".$val."'>\n";
		//Print table
		echo "\t<table border='1'>\n";
		
		$row = pg_fetch_assoc($result);
		for ($i = 0; $i < pg_num_fields($result); $i++){
			echo "\t\t<tr>\n";
			$fieldname = pg_field_name($result, $i);
			echo "\t\t\t<td><strong>".$fieldname."</strong></td>\n";
			echo "\t\t\t<td><input type='text' name='".$fieldname."' value='".$row[$fieldname]."' /></td>";
			
			echo "\t\t</tr>\n";
		}
		echo "\t</table>\n";
		echo "\t<input type='submit' value='Save' />\n";
		echo "\t<input type='button' value='Cancel' onclick=\"top.location.href='dataedit.php';\" />\n";
		echo "\t</form>";
	
	}
	echo "</div>";
	include 'footer.php';
?>

</body>
</html>