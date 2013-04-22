<?php
	session_start();
	$connString = "host=dbhost-pgsql.cs.missouri.edu user=cs3380sp13grp11 dbname=cs3380sp13grp11 password=vTc9pwMw";
	$dbconn = pg_connect($connString ) or die("Problem with connection to PostgreSQL:".pg_last_error());
	
	$exp_id= $_POST['exp_id'];	
	
	$query3 = "DELETE FROM experiment WHERE expid = $1";
	
	//prepare the query
	$prepare3 = pg_prepare($dbconn, "deleteexp", $query3);
	
	//if it prepared properly, execute the query
	//deletes experiment participant is apart of
	if($prepare3)
	{
		$result3 = pg_execute($dbconn, "deleteexp", array($exp_id));	
	}
	else
	{
	echo "sanitizing failed";	
	}
	
	
	
	
?>
