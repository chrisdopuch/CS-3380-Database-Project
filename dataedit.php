<!DOCTYPE html>
<!--connect to the database and start the session-->
<?php include 'connect.php'; ?>
<head>
	<title>MU PSYCH SCIENCES DATA EDIT UTILITY</title>
<!-- include the style sheet for the website-->
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<!--include the header-->
	<?php 
	include 'header.php'; 
	top("experimenter");
	?>

	<div id='main' class='clearfix'>
		<h2>DATAEDIT MAIN</h2><br />
	
	<!-- FORM FOR SELECTING TABLE TO VIEW/EDIT -->
	<form method="POST" action="/~cs3380sp13grp11/dataedit.php">
		View records from: <input type="radio" name="table" value="experimenters" checked="checked" />Experimenters<br />
		<input type="radio" name="table" value="experiments" />Experiments<br />
		<input type="radio" name="table" value="participants" />Participants<br />
		<input type="radio" name="table" value="sessions" />Sessions<br />
		<input type="radio" name="table" value="users" />Users<br />
		<input type="radio" name="table" value="locations" />Locations <br /><br />
		<!-- That match string: <input type="text" name="query_string" /> <br /><br /> -->
		<input type="submit" name="submit" value="Submit" />
	</form>
	
	<br />
	<hr />
	Insert a new Experimenter by clicking this <a href="dataedit_exec.php?action=add&type=experimenters">link</a>.<br />
	Insert a new Experiment by clicking this <a href="dataedit_exec.php?action=add&type=experiments">link</a>.<br />
	Insert a new Participant by clicking this <a href="dataedit_exec.php?action=add&type=participants">link</a>.<br />
	Insert a new Session by clicking this <a href="dataedit_exec.php?action=add&type=sessions">link</a>.<br />
	Insert a new User by clicking this <a href="dataedit_exec.php?action=add&type=users">link</a>.<br />
	Insert a new Location by clicking this <a href="dataedit_exec.php?action=add&type=locations">link</a>.<br />


	<?php
	//check to see if form was submitted
	if (isset($_POST['submit'])){
	
		//switch statement on "table"
		switch($_POST['table']){
			//select query based on user's search selection
			case "experimenters":
				$query = "SELECT * FROM database.experimenters ORDER BY eid";
				break;
			case "experiments":
				$query = "SELECT * FROM database.experiments ORDER BY expid";
				break;
			case "participants":
				$query = "SELECT * FROM database.participants ORDER BY pid";
				break;
			case "sessions":
				$query = "SELECT * FROM database.sessions ORDER BY sid";
				break;
			case "users":
				$query = "SELECT * FROM database.users ORDER BY username";
				break;
			case "locations":
				$query = "SELECT * FROM database.locations ORDER BY lid";
				break;
			default:
				echo "Bad option selected.\n";
				exit(1);
		}
		
		//prepare the query
		$stmt = pg_prepare($conn, "query", $query);
		//execute the query (and get some crude benchmarking data)
		$ts = microtime(true);
		$result = pg_execute($conn, "query", array());
		$tq = microtime(true) - $ts;
		
		//Die if the query fails
		if (!$result){
			die("Unable to execute query: " . pg_last_error($conn));
		}

		//Print out how many rows were returned
		echo "\t<p>There were <em>".pg_num_rows($result)."</em> rows returned.</p>\n";
		
		//Print the results of the query in a nice table
		pgResultToTableWithButtons($result, $_POST['table']);

		//Print query time
		echo "\t<p>Approximate query running time: <em>".round($tq, 6)." </em>sec.</p>\n";
	}

	//Prints out a pg query result at a table with delete and edit buttons for each entry
	function pgResultToTableWithButtons($result, $entryType){
		//Print form
		echo "\t<form method='POST' action='/~cs3380sp13grp11/dataedit_exec.php'>\n";
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
			//Prepare buttons
			switch($entryType){
				case "experimenters":
					$buttonAction = "type=experimenters&val=".$row['eid'];
					break;
				case "experiments":
					$buttonAction = "type=experiments&val=".$row['expid'];
					break;
				case "participants":
					$buttonAction = "type=participants&val=".$row['pid'];
					break;
				case "sessions":
					$buttonAction = "type=sessions&val=".$row['sid'];
					break;
				case "users":
					$buttonAction = "type=users&val=".$row['username'];
					break;
				case "locations":
					$buttonAction = "type=locations&val=".$row['lid'];
					break;
				default:
					echo "Bad option for $entryType in pgResultToTableWithButtons().\n";
					exit(1);
			}

			echo "\t\t<tr>\n";
			//Print the buttons
			echo "\t\t\t<td>\n";
			echo "\t\t\t\t<input type='submit' value='Edit' formaction='dataedit_exec.php?action=edit&".$buttonAction."' />\n";
			echo "\t\t\t\t<input type='submit' value='Remove' formaction='dataedit_exec.php?action=remove&".$buttonAction."' />\n";
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


<!--include the footer-->
</div>
<?php include 'footer.php'; ?>
</body>
</html>
