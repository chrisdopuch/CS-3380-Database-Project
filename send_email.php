<?php
/*
This is a php script that runs periodically on CRON on babbage that sends out scheduled emails
*/

include 'connect.php';

//query the database for emails whose send by date is in the past
$result = pg_prepare($conn, "emails", "SELECT * FROM database.emails WHERE send_by < current_timestamp");
$result = pg_execute($conn, "emails", array());

//get result data and execute operations
while($row = pg_fetch_assoc($result)){
	//get variables
	$id = $row['id'];
	$recipient = $row['recipient'];
	$subject = $row['subject'];
	$text = $row['text'];
	
	//send email
	mail($recipient,$subject,$text);
	
	//remove email from DB
	$result = pg_prepare($conn, "delete", "DELETE FROM database.emails WHERE id = $1");
	$result = pg_execute($conn, "delete", array($id));
}
?>