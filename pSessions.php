<?php
//include header
include 'header.php';
include 'connect.php';
top("participant");
?>
<!DOCTYPE html>
<html>
<head>
<title>My Sessions</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<script>
function clickAction(form, sid){
  document.forms[form].elements['button'].value = ;
  document.getElementById(form).submit();
}
</script>
<?php
	ERROR_REPORTING(E_ALL);
	ini_set("display_errors", 1);

	//get experiment info
	$username = $_SESSION['username'];
	
	//to test the page, uncomment below line
	//$username = 'test_user1';
	
	$query1 = "SELECT expid, eid, session_date, start_time, end_time,(SELECT name FROM database.experiments as i WHERE i.expid = o.expid) as experiment_name, (SELECT building FROM database.locations as i WHERE i.lid = o.lid) as building, (SELECT room FROM database.locations as i WHERE i.lid = o.lid) as room, (SELECT first_name FROM database.experimenters as i WHERE i.eid = o.eid) as experimenter_name FROM database.sessions as o WHERE pid = (SELECT pid FROM database.participants WHERE username = $1)";
	
	$result1 = pg_prepare($conn, "get_sessions", $query1);
	$result1 = pg_execute($conn, "get_sessions", array($username));
	$i =0;
	//populates the table with the sessions.
	if(pg_num_rows($result1) != 0){
		echo "You aren't currently signed up for any sessions<br/>\n";
	}
	else{
		echo "<h1>Your Sessions</h1><br />\n";
		echo "<form id='unenroll_form' action='pSessions.php?action=unenroll' method='POST'>\n";
		echo "<table border='1'>\n";
	}
	while ($row = pg_fetch_assoc($result1))
	{
				$exp_id = $row['expid'];
                        
				$e_id = $row['eid'];
				
				$start_date = $row['session_date'];
				
				$start_time = $row['start_time'];
				
				$end_time = $row['end_time'];
						
				$experimenter_name = $row['experimenter_name'];
				
				$experiment_name = $row['experiment_name'];
				
				$building = $row['building'];
				
				$room = $row['room'];
				
				$sid = $room['sid'];
				
				echo "<tr id='row$i'>";
				
				echo "<td> $experiment_name </td>";
				
				echo "<td> $experimenter_name </td>";
				
				echo "<td> $e_id </td>";
				
				echo "<td> $exp_id </td>";
				
				echo "<td> $building </td>";
				
				echo "<td> $room </td>";

                echo "<td> $start_date </td>";
				
                echo "<td> $start_time </td>";

                echo "<td> $end_time </td>";
						
				echo "<td> <button name='sid' onclick='clickAction(\"unenroll_form\")' value=$sid> Cancel
				</button></td>";
				echo "<input type='hidden' name='sid' value='$sid' />";
				
				echo "</tr>";
				$i++;
	}
	echo "</form>";
	if(isset($_GET['action'])){
		$sid = $_POST['sid'];
		$result = pg_prepare($conn, "unenroll", "UPDATE database.sessions SET pid = NULL WHERE sid = $1");
		$result = pg_execute($conn, "unenroll", array($sid));
		if($result){
			echo "<script> alert('You have been successfully removed from the session');</script>";
		}
	}
	
?>
</body>
</html>
		