<!DOCTYPE html>
<html>
<head>
<title>My Sessions</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>

<?php
//include header
include 'header.php';
include 'connect.php';

top("participant");

ERROR_REPORTING(E_ALL);
ini_set("display_errors", 1);

if (isset($_GET['action'])){
	$action = $_GET['action']; // remove
	$val = $_GET['val']; //sid
}
else {
	$action = "default";
}

switch ($action){
	case "remove":
		//get vars
		$sid = $val;
		//query removes the participant link from the specified session
		$stmt = pg_prepare($conn, "unenroll", "UPDATE database.sessions SET pid = NULL WHERE sid = $1");
		//execute query
		$result = pg_execute($conn, "unenroll", array($sid));
		
		//if query was successful
		if ($result){
			echo "\tSuccessfully unenrolled from session!<br />\n";
			echo "\tReturn to <a href='pSessions.php'>sessions page</a>.";
		}
		else{
			echo "\tUnenroll FAILED: ".pg_last_error($conn)."<br />\n";
			echo "\tReturn to <a href='pSessions.php'>sessions page</a>.";
		}

		break;

	default:
		//get experiment info
		$username = $_SESSION['username'];

		//to test the page, uncomment below line
		$username = 'test_user1';
		
		//what... is this?
		$query = "SELECT expid, sid, eid, session_date, start_time, end_time,(".
						"SELECT name FROM database.experiments as i WHERE i.expid = o.expid) as experiment_name, (".
							"SELECT building FROM database.locations as i WHERE i.lid = o.lid) as building, (".
								"SELECT room FROM database.locations as i WHERE i.lid = o.lid) as room, (".
									"SELECT first_name FROM database.experimenters as i WHERE i.eid = o.eid) as experimenter_name FROM database.sessions as o WHERE pid = (".
										"SELECT pid FROM database.participants WHERE username = $1)";
		
		//prepare query
		$stmt = pg_prepare($conn, "get_sessions", $query);
		//execute
		$result = pg_execute($conn, "get_sessions", array($username));
		//indexing variable for table printing (why are we indexing the table?)
		$i = 0;

		//if query failed or user not signed up
		if(!$result){
			echo "You aren't currently signed up for any sessions.<br/>\n";
			exit(1);
		}

		//otherwise print the table
		pgResultToTableWithButtons($result);
		break;
}


//Function to retrieve sessions data and display it with buttons for user interaction
function pgResultToTableWithButtons($result){
	//Print form
	echo "\t<form method='POST' action='/~cs3380sp13grp11/pSessions.php'>\n";
	//Print headers
	echo "\t<table border='1'>\n";
	echo "\t\t<tr>\n";
	//print "Actions" header
	echo "\t\t\t<th>Actions</th>\n";
	//print the rest of the headers
	for ($i = 0; $i < pg_num_fields($result); $i++){
		$fieldname = pg_field_name($result, $i);
		echo "\t\t\t<th>$fieldname</th>\n";
	}
	echo "\t\t</tr>\n";

	//Print the rows
	while($row = pg_fetch_assoc($result)){
		echo "\t\t<tr>\n";
		//Print the buttons
		echo "\t\t\t<td>\n";
		echo "\t\t\t\t<input type='submit' value='Unenroll' formaction='pSessions.php?action=remove&val=".$row['sid']."' />\n";
		echo "\t\t\t</td>\n";

		//Print row contents
		foreach($row as $entry){
			echo "\t\t\t<td>$entry</td>\n";
		}

		echo "\t\t</tr>\n";
	}

	echo "\t</table>\n";
	echo "\t</form>";
}

?>
</body>
</html>
		