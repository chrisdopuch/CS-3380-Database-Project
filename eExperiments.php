<!--this is a template for making pages on the website-->

<!DOCTYPE html>
<!--connect to the database and start the session-->
<?php include 'connect.php';
session_start();?>
<head>
<title>Experiments</title>
<!--include the style sheet for the website-->
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<!--include the header-->
<?php include 'header.php';
top("experimenter");
?>
<div id='main' class='clearfix'>
	<h2>Reports</h2><br />

<?php
	$query = "SELECT * FROM database.experiments ORDER BY expid";

		
		//prepare the query
		$stmt = pg_prepare($conn, "query", $query);
		//execute the query 
		$result = pg_execute($conn, "query", array());
		
		//Die if the query fails
		if (!$result){
			die("Unable to execute query: " . pg_last_error($conn));
		}

		//Print the results of the query in a nice table
		pgResultToTableWithButtons($result, "experiments");

		//Print out how many rows were returned
		echo "\t<p>There were <em>".pg_num_rows($result)."</em> rows returned.</p>\n";

//Function to retrieve experiments data and display it with buttons for user interaction
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
			echo "\t\t\t\t<input type='submit' value='Edit' formaction='eExperiment.php?action=edit&".$buttonAction."' />\n";
			echo "\t\t\t\t<input type='submit' value='Remove' formaction='eExperiment.php?action=remove&".$buttonAction."' />\n";
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
</div>
<!--include the footer-->
<?php include 'footer.php'; ?>
</body>
</html>
