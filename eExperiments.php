<!--this is a template for making pages on the website-->

<!DOCTYPE html>
<!--connect to the database and start the session-->
<?php include 'connect.php';?>
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
	<h2>Experiments</h2><br />

<?php
		$action = $_GET['action']; // edit, remove, edit_commit, NOTHING
		$val = $_GET['val']; //expid

		switch ($action){
			//this action shows an editable form to the user
			case "edit":
				//define the query to select the country of interest
				$query = "SELECT * FROM database.experiments WHERE expid = $1";
				//prepare the query
				$stmt = pg_prepare($conn, "select_exp", $query);
				//execute query with the desired country code
				$result = pg_execute($conn, "select_exp", array($val));
				//print the edit form
				pgResultsToEditableTableForm($result, $type, $val);
				break;

			//this action removes the selected experiment from the database
			case "remove":
				$query = "DELETE FROM database.experiments WHERE expid = $1";
				//prepare the query
				$stmt = pg_prepare($conn, "delete_exp", $query);
				//execute query 
				$result = pg_execute($conn, "delete_exp", array($val));

				if ($result){
					echo "\tUpdate was successful. <br />\n";
					echo "\tReturn to <a href='eExperiments.php'>search page</a>.";
				}
				else{
					echo "\tUpdate FAILED: ".pg_last_error($conn)."<br />\n";
					echo "\tReturn to <a href='eExperiments.php'>search page</a>.";
				}
				break;

			//this action saves the values entered into the edit form to the database
			case "edit_commit":

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

				//Check to see if the query was successful
				if ($result){
					echo "\tUpdate was successful. <br />\n";
					echo "\tReturn to <a href='eExperiments.php'>search page</a>.";
				}
				else{
					echo "\tUpdate FAILED: ".pg_last_error($conn)."<br />\n";
					echo "\tReturn to <a href='eExperiments.php'>search page</a>.";
				}
				break;

			//No action selected; show all experiments
			default:
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
				break;


		}

		

	//Prints a table from a pg query result. $type refers to the table from which the result was returned. $val is passed in only for use in building URLs for GET
	function pgResultsToEditableTableForm($result, $type, $val){
		include 'connect.php';
		
		//make sure $result is not null
		if (!$result){
			die("Unable to execute query: " . pg_last_error($conn));
		}

		//Print form
		echo "\t<form method='POST' action='eExperiments.php?action=edit_commit&val=".$val."'>\n";
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
		echo "\t<input type='button' value='Cancel' onclick=\"top.location.href='eExperiments.php';\" />\n";
		echo "\t</form>";
	
	}

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
			echo "\t\t\t\t<input type='submit' value='Edit' formaction='eExperiments.php?action=edit&".$buttonAction."' />\n";
			echo "\t\t\t\t<input type='submit' value='Remove' formaction='eExperiments.php?action=remove&".$buttonAction."' />\n";
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
