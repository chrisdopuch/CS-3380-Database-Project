<!--this is a template for making pages on the website-->

<!DOCTYPE html>
<!--connect to the database and start the session-->
<?php include 'connect.php';?>
<head>
<title>Sessions</title>
<!--include the style sheet for the website-->
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<!--include the header-->
<?php include 'header.php';
top("experimenter");
?>
<div id='main' class='clearfix'>
	<h2>Sessions</h2><br />

<?php
		$action = $_GET['action']; // edit, remove, edit_commit, view_exp, NOTHING
		$val = $_GET['val']; //sid, expid

		switch ($action){
			//this action shows an editable form to the user
			case "edit":
				//define the query to select the country of interest
				$query = "SELECT * FROM database.sessions WHERE sid = $1";
				//prepare the query
				$stmt = pg_prepare($conn, "select_s", $query);
				//execute query with the desired country code
				$result = pg_execute($conn, "select_s", array($val));
				//print the edit form
				pgResultsToEditableTableForm($result, $type, $val);
				break;

			//this action removes the selected experiment from the database
			case "remove":
				$query = "DELETE FROM database.sessions WHERE sid = $1";
				//prepare the query
				$stmt = pg_prepare($conn, "delete_s", $query);
				//execute query 
				$result = pg_execute($conn, "delete_s", array($val));

				if ($result){
					echo "\tUpdate was successful. <br />\n";
					echo "\tReturn to <a href='eSessions.php'>Session page</a>.";
				}
				else{
					echo "\tUpdate FAILED: ".pg_last_error($conn)."<br />\n";
					echo "\tReturn to <a href='eSessions.php'>Session page</a>.";
				}
				break;

			//this action saves the values entered into the edit form to the database
			case "edit_commit":

				//get postvars
				$payment = $_POST['payment'];
				$name = $_POST['name'];
				$requirements = $_POST['requirements'];

				//define the query to update the city table
				$query = "UPDATE database.sessions SET (payment, name, requirements) = ($1, $2, $3) WHERE sid = $4";
				//prepare the query
				$stmt = pg_prepare($conn, "update_s", $query);
				//execute the query with user's values
				$result = pg_execute($conn, "update_s", array($payment, $name, $requirements, $val));

				//Check to see if the query was successful
				if ($result){
					echo "\tUpdate was successful. <br />\n";
					echo "\tReturn to <a href='eSession.php'>Session page</a>.";
				}
				else{
					echo "\tUpdate FAILED: ".pg_last_error($conn)."<br />\n";
					echo "\tReturn to <a href='eSession.php'>Session page</a>.";
				}
				break;

			case "view_exp":
				//url is of the form: eSession.php?action=view_exp AND expid is in postdata from the experiments dropdown
				//OR url is of the form: eSession.php?action=view_exp&val=expid from a link on eExperiments
				//if expid was sent as postdata, get it
				if (isset($_POST['expid'])){
					$expid = $_POST['expid'];
				}
				else{
					//otherwise set it from the getdata
					$expid = $val;
				}

				//our query needs to find all sessions with this experiment
				$query = "SELECT * FROM database.sessions WHERE expid = $1";
				//prepare query
				$stmt = pg_prepare($conn, "view_s_by_exp", $query);
				//execute query
				$result = pg_execute($conn, "view_s_by_exp", array($expid));

				//Check to see if the query was successful
				if (!$result){
					echo "\Query failed. Could not view sessions by experiment: ".pg_last_error($conn)."<br />\n";
					echo "\tReturn to <a href='eSession.php'>search page</a>.";
				}
				else {
					pgResultToTableWithButtons($result, "sessions");

					
				//make the query to generate the experiment select dropdown
				$query = "SELECT expid, name FROM database.experiments";
				//prepare
				$stmt = pg_prepare($conn, "get_exp_for_dropdown", $query);
				//execute
				$result = pg_execute($conn, "get_exp_for_dropdown", array());
				//print the dropdown
				echo "\tSelect an experiment below to view its sessions:\n";
				pgResultToDropDownWithDefault($result, "experiments", $expid);
				}
				break;


			//No action selected; show all experiments
			default:
				$query = "SELECT * FROM database.sessions ORDER BY sid";
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

				//make the query to generate the experiment select dropdown
				$query = "SELECT expid, name FROM database.experiments";
				//prepare
				$stmt = pg_prepare($conn, "get_exp_for_dropdown", $query);
				//execute
				$result = pg_execute($conn, "get_exp_for_dropdown", array());
				//print the dropdown
				echo "\tSelect an experiment below to view its sessions:\n";
				pgResultToDropDownWithDefault($result, "experiments", $expid);

				//Print out how many rows were returned
				echo "\t<p>There were <em>".pg_num_rows($result)."</em> rows returned.</p>\n";
				break;
		}

		
	//Prints a table from a pg query result. $type refers to the table from which the result was returned. $val is passed in only for use in building URLs for GET
	function pgResultsToEditableTableForm($result, $type, $val){		
		//make sure $result is not null
		if (!$result){
			die("Bad value for result in pgResultsToEditableTableForm: " . pg_last_error($conn));
		}

		//Print form
		echo "\t<form method='POST' action='eSessions.php?action=edit_commit&val=".$val."'>\n";
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
		echo "\t<input type='button' value='Cancel' onclick=\"top.location.href='eSessions.php';\" />\n";
		echo "\t</form>";
	
	}

//Inputs
	//result: the result of a pg query
	//type: the table the result is from (experimenters, experiments, participants, users, sessions, locations)
	//default: the value of the primary key field for the one you want to be selected by default
//Output
	//returns: nothing
	//prints: a drop-down menu with all entries in result, with default selected automatically
function pgResultToDropDownWithDefault($result, $type, $default){
	//make sure $result is not null
	if (!$result){
		die("Bad value for result in pgResultsToEditableTableForm: " . pg_last_error($conn));
	}
	//Print form
	echo "\t<form method='POST' action='eSessions.php?action=view_exp'>\n";
	echo "\t\t<select name='expid'>\n";

	//for each row in the query result

	for ($i = 0; $i <= pg_num_fields($result); $i++){
			//get associative array from query results
	$row = pg_fetch_assoc($result);
		//if this entry's expid is set as the default, print it so it will be preselected
		if ($row['expid'] == $default){
			echo "\t\t\t<option value='".$row['expid']."' selected='selected'> ".$row['name']." </option>\n";
		}
		//otherwise print normal
		else {
			echo "\t\t\t<option value='".$row['expid']."' > ".$row['name']." </option>\n";
		}
	}
	//end the select
	echo "\t\t</select>\n";
	//add submit button
	echo "\t\t<input type='submit' name='submit' value='View' />\n";
	//end the form
	echo "\t</form>\n";
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
			echo "\t\t\t\t<input type='submit' value='Edit' formaction='eSessions.php?action=edit&".$buttonAction."' />\n";
			echo "\t\t\t\t<input type='submit' value='Remove' formaction='eSessions.php?action=remove&".$buttonAction."' />\n";
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
