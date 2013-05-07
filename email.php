<?php 
	/*test*/
	//enroll_email(1);
	//enroll_email(2);


	/*
	This is a function to enter two email messages into the database upon succesful enrollment of a participant in a session
		1. confirmation email
		2. reminder email
	Input: session id of the session the user just signed up for
	Output: return true if success; email to emails table, or error message
	*/
	function enroll_email($sid){
		//connect to the DB if we aren't already
		require 'connect.php';
		
		//check to make sure the pid column in sessions isn't null
		$query_name = "check_enroll";
		$query = "SELECT pid FROM database.sessions WHERE sid = $1";
		$result = pg_prepare($conn, $query_name, $query);
		$result = pg_execute($conn, $query_name, array($sid));
		$row = pg_fetch_assoc($result);
		if($row['pid'] == NULL){
			$message = "Error: there is no one signed up for the session entered!";
			echo "<script> alert('$message'); </script>\n";
			return false;
		}
		
		
		//query the DB for the location, session, participant/user, and experiment associated with the session id
		$query_name = "get_info";
		$query = "SELECT p.first_name, p.last_name, u.email, s.session_date, s.start_time, s.end_time, e.name as experiment_name, l.room, l.building FROM database.sessions AS s INNER JOIN database.locations AS l ON l.lid = s.lid INNER JOIN database.experiments AS e ON e.expid = s.expid INNER JOIN database.participants AS p ON p.pid = s.pid INNER JOIN database.users AS u ON u.username = p.username WHERE s.sid = $1";
		$result = pg_prepare($conn, $query_name, $query);
		$result = pg_execute($conn, $query_name, array($sid));
		
		//fetch the reuslts
		$row = pg_fetch_assoc($result);
		$first_name = $row['first_name'];
		$last_name = $row['last_name'];
		$email = $row['email'];
		$session_date = $row['session_date'];
		$start_time = $row['start_time'];
		$end_time = $row['end_time'];
		$experiment_name = $row['experiment_name'];
		$room = $row['room'];
		$building = $row['building'];
		
		//create the confirmation email
		$c_subject = "Confirmation for $experiment_name";
		$c_body = "Dear $first_name $last_name,\n \t This is an automated email message confirming your appointment at $start_time on $session_date for the experiment $experiment_name. The session will last until $end_time. The experiment will be in $building room $room. \n\nPlease don't respond to this email, as this mailbox is unmonitored.\n";
		
		//insert the confirmation email into the emails table
		$query_name = "insert_confirm";
		$query = "INSERT INTO database.emails (send_by, recipient, subject, text) VALUES (CURRENT_TIMESTAMP, $1, $2, $3)";
		$result = pg_prepare($conn, $query_name, $query);
		$result = pg_execute($conn, $query_name, array($email, $c_subject, $c_body));
		if(!$result){
			$message = "Error: could not add confirmation email to emails table!";
			echo "<script> alert('$message'); </script>\n";
			return false;
		}
		
		//now create the reminder email
		$r_subject = "Reminder for $experiment_name";
		$r_body = "Dear $first_name $last_name,\n \t This is an automated email message reminding you of your appointment at $start_time on $session_date for the experiment $experiment_name. The session will last until $end_time. The experiment will be in $building room $room. \n\nPlease don't respond to this email, as this mailbox is unmonitored.\n";
		
		$send_by = parse_timestamp($session_date);
		
		//insert the confirmation email into the emails table
		$query_name = "insert_reminder";
		$query = "INSERT INTO database.emails (send_by, recipient, subject, text) VALUES ($1, $2, $3, $4)";
		$result = pg_prepare($conn, $query_name, $query);
		$result = pg_execute($conn, $query_name, array($send_by, $email, $c_subject, $c_body));
		if(!$result){
			$message = "Error: could not add reminder email to emails table!";
			echo "<script> alert('$message'); </script>\n";
			return false;
		}
		
		return true;
	}
	
	/*
	Function to send an authentication email to participants who register
	Input: id of participant who registered
	Output: true on success; email to emails table
	*/
	function authentication_email($pid){
		//connect to the DB if we aren't already
		require 'connect.php';
		
		//check to make sure the participant hasn't already authorized
		$query_name = "check_unauthenticated";
		$query = "SELECT * FROM database.participants as p INNER JOIN database.users as u ON u.username = p.username WHERE pid = $1";
		$result = pg_prepare($conn, $query_name, $query);
		$result = pg_execute($conn, $query_name, array($pid));
		$row = pg_fetch_assoc($result);
		if($row['authenticated'] == 't'){
			$message = "Error: this participant is already authenticated!";
			echo "<script> alert('$message'); </script>\n";
			return false;
		}
		
		//fetch the reuslts
		$email = $row['email'];
		$first_name = $row['first_name'];
		$last_name = $row['last_name'];
		
		//create the authentication email
		$a_subject = "Confirm your account";
		$a_body = "Dear $first_name $last_name,\n \t This is an automated email message to confirm your account. Please go to <a href='http://babbage.cs.missouri.edu/~cs3380sp13grp11/authenticate.php?id=$pid'>this link</a> to authenticate your account. \n\nPlease don't respond to this email, as this mailbox is unmonitored.\n";
		
		//insert the confirmation email into the emails table
		$query_name = "insert_authenticate";
		$query = "INSERT INTO database.emails (send_by, recipient, subject, text) VALUES (CURRENT_TIMESTAMP, $1, $2, $3)";
		$result = pg_prepare($conn, $query_name, $query);
		$result = pg_execute($conn, $query_name, array($email, $a_subject, $a_body));
		if(!$result){
			$message = "Error: could not add authentication email to emails table!";
			echo "<script> alert('$message'); </script>\n";
			return false;
		}
		
		return true;
	}
	
	//helper function
	function parse_timestamp($timestamp, $format = 'd-m-Y')
	{
		$formatted_timestamp = date($format, strtotime($timestamp));
		return $formatted_timestamp;
	}
?>