<!--
	This page exists to authenticate a participant
	Authentication emails link to this page with the id of the participant as a get variable
	You can manually authenticate by going to this page and typing in as a get variable the pid of the participant
	-->
<script>
	function redirect(){
		window.location = "http://babbage.cs.missouri.edu/~cs3380sp13grp11/index.php"
	}
</script>
<?php
	//if the id was sent in a get variable
	if(isset($_GET['id'])){
		//grab the variable
		$pid = htmlspecialchars($_GET['id']);
	
		//connect to DB
		require_once 'connect.php';
		
		//check to make sure the participant hasn't already authorized
		$query_name = "check_unauthenticated";
		$query = "SELECT * FROM database.participants as p INNER JOIN database.users as u ON u.username = p.username WHERE pid = $1";
		$result = pg_prepare($conn, $query_name, $query);
		$result = pg_execute($conn, $query_name, array($pid));
		$row = pg_fetch_assoc($result);
		if($row['authenticated'] == TRUE){
			header("Location: http://babbage.cs.missouri.edu/~cs3380sp13grp11/index.php");
			exit;
		}
		
		//set the participant to be authenticated
		$query_name = "insert_authenticated";
		$query = "UPDATE database.participants SET authenticated = TRUE WHERE pid = $1";
		$result = pg_prepare($conn, $query_name, $query);
		$result = pg_execute($conn, $query_name, array($pid));
		if(!$result){
			$message = "Error: could not authenticate participant!";
			echo "<script> alert('$message'); </script>\n";
			exit;
		}
		
		$message = "You have been successfully confirmed!";
		echo "<script> alert('$message'); redirect(); </script>\n";
		
	}
	else{
		header("Location: http://babbage.cs.missouri.edu/~cs3380sp13grp11/index.php");
	}
?>
