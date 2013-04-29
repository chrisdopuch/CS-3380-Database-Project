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
		$action = $_GET['action']; // edit, remove, edit_commit, add, add_commit, NOTHING
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

			//this action displays a form to create a new experiment
			case "add":
				//Display add form
				echo "\t<form method='POST' action='eExperiments.php?action=add_commit'>\n";
				echo "\t\t<input type='hidden' name='action' value='add_commit' />\n";
				echo "\t\t<p>Enter data for the experiment to be added: </p><br />\n";
				echo "\t\t\t<table border='1'>\n";
				echo "\t\t\t\t<tr><td>Name</td><td><input type='text' name='name' /></td></tr>\n";
				echo "\t\t\t\t<tr><td>Payment</td><td><input type='text' name='payment' /></td></tr>\n";
				echo "\t\t\t\t<tr><td colspan='3'><b>Requirements</b></td></tr>\n";
				echo "\t\t\t\t<tr><td>Attribute</td><td>Operator</td><td>Selection</td></tr>\n";
				echo "\t\t\t\t<tr>\n";
				echo "\t\t\t\t\t<td>Ethnicity</td>\n";
				echo "\t\t\t\t\t<td>\n";
				echo "\t\t\t\t\t\t<select name='ethnicity_op'>\n";
				echo "\t\t\t\t\t\t\t<option value='is'> is </option>\n";
				echo "\t\t\t\t\t\t\t<option value='is not'> is not </option>\n";
				echo "\t\t\t\t\t\t</select>\n";
				echo "\t\t\t\t\t</td>\n";
				echo "\t\t\t\t\t<td>\n";
				echo "\t\t\t\t\t\t<select name='ethnicity_sel'>";
				echo "\t\t\t\t\t\t\t<option value='x' selected='selected'> don't care </option>";
				echo "\t\t\t\t\t\t\t<option value='s1'> s1 </option>";
				echo "\t\t\t\t\t\t\t<option value='s2'> s2 </option>";
				echo "\t\t\t\t\t\t\t<option value='s3'> s3 </option>";
				echo "\t\t\t\t\t\t</select>";
				echo "\t\t\t\t\t</td>";
				echo "\t\t\t\t</tr>";

				echo "\t\t\t\t<tr>\n";
				echo "\t\t\t\t\t<td>Gender</td>\n";
				echo "\t\t\t\t\t<td>\n";
				echo "\t\t\t\t\t\tis\n";
				echo "\t\t\t\t\t</td>\n";
				echo "\t\t\t\t\t<td>\n";
				echo "\t\t\t\t\t\t<select name='gender_sel'>\n";
				echo "\t\t\t\t\t\t\t<option value='x' selected='selected'> don't care </option>\n";
				echo "\t\t\t\t\t\t\t<option value='m'> male </option> \n";
				echo "\t\t\t\t\t\t\t<option value='f'> female </option> \t";
				echo "\t\t\t\t\t\t</select>\n";
				echo "\t\t\t\t\t</td>\n";
				echo "\t\t\t\t</tr>\n";

				echo "\t\t\t\t<tr>\n";
				echo "\t\t\t\t\t<td>Age</td>\n";
				echo "\t\t\t\t\t<td>\n";
				echo "\t\t\t\t\t\t<select name='age_op'>\n";
				echo "\t\t\t\t\t\t\t<option value='x' selected='selected'> don't care </option>\n";
				echo "\t\t\t\t\t\t\t<option value='=='> equal to </option> \n";
				echo "\t\t\t\t\t\t\t<option value='>='> greater than or equal to </option> \n";
				echo "\t\t\t\t\t\t\t<option value='<='> less than or equal to </option>\n";
				echo "\t\t\t\t\t\t</select>\n";
				echo "\t\t\t\t\t</td>\n";
				echo "\t\t\t\t\t<td>\n";
				echo "\t\t\t\t\t\t<input type='text' name='age_sel' />\n";
				echo "\t\t\t\t\t</td>\n";
				echo "\t\t\t\t</tr>\n";

				echo "\t\t\t\t<tr>\n";
				echo "\t\t\t\t\t<td>Education</td>\n";
				echo "\t\t\t\t\t<td>\n";
				echo "\t\t\t\t\t\t<select name='education_op'>\n";
				echo "\t\t\t\t\t\t\t<option value='x' selected='selected'> don't care </option>\n";
				echo "\t\t\t\t\t\t\t<option value='=='> equal to </option> \n";
				echo "\t\t\t\t\t\t\t<option value='>='> greater than or equal to </option> \n";
				echo "\t\t\t\t\t\t\t<option value='<='> less than or equal to </option>\n";
				echo "\t\t\t\t\t\t</select>\n";
				echo "\t\t\t\t\t</td>\n";
				echo "\t\t\t\t\t<td>\n";
				echo "\t\t\t\t\t\t<input type='text' name='education_sel' />\n";
				echo "\t\t\t\t\t</td>\n";
				echo "\t\t\t\t</tr>\n";
				echo "\t\t\t</table>\n";
				echo "\t\t<input type='submit' value='Save' />\n";
				echo "\t\t<input type='button' value='Cancel' onclick='top.location.href='eExperiments.php'' />\n";
				echo "\t</form>\n";

				break;

			//this action adds a new experiment to the database based on the data entered by the user
			case "add_commit":
				//get post vars
				$name = $_POST['name'];
				$payment = $_POST['payment'];
				$requirements = array(
									"ethnicity" => array(
										"op" => $_POST['ethnicity_op'],
										"sel" => $_POST['ethnicity_sel']),
									"gender" => $_POST['gender_sel'],
									"age" => array(
										"op" => $_POST['gender_op'],
										"sel" => $_POST['gender_sel']),
									"education" => array(
										"op" => $_POST['education_op'],
										"sel" => $_POST['education_sel']));
				//example usage: $requirements["age"]["sel"] == $_POST['age_sel'] (i.e. 21 or whatever age was initially entered)

				//encode as JSON string for storage
				$requirements = json_encode($requirements);

				$query = "INSERT INTO database.experiments (name, payment, requirements) VALUES ($1, $2, $3)";
				//prepare the query
				$stmt = pg_prepare($conn, "add_exp", $query);
				//execute query 
				$result = pg_execute($conn, "add_exp", array($name, $payment, $requirements));

				//Check to see if the query was successful
				if ($result){
					echo "\Insert was successful. <br />\n";
					echo "\tReturn to <a href='eExperiments.php'>experiments page</a>.";
				}
				else{
					echo "\tINSERT FAILED: ".pg_last_error($conn)."<br />\n";
					echo "\tReturn to <a href='eExperiments.php'>experiments page</a>.";
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
				//print an add link
				echo "Insert a new Experiment by clicking this <a href='eExperiments.php?action=add'>link</a>.<br />\n";


				//Print the results of the query in a nice table
				pgResultToTableWithButtons($result, "experiments");

				//make the query to generate the experiment select dropdown
				$query = "SELECT expid, name FROM database.experiments";
				//prepare
				$stmt = pg_prepare($conn, "get_exp_for_dropdown", $query);
				//execute
				$result = pg_execute($conn, "get_exp_for_dropdown", array());

				//print out a drop down so the user can select an experiment to view sessions
				echo "\tSelect an experiment below to view its sessions:\n";
				pgResultToDropDownWithDefault($result, "experiments", $expid);

				//Print out how many rows were returned
				echo "\t<p>There were <em>".pg_num_rows($result)."</em> rows returned.</p>\n";
				break;


		}

	//Inputs
	//result: the result of a pg query
	//type: the table the result is from (experimenters, experiments, participants, users, sessions, locations) (not used at present)
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

	//Prints a table from a pg query result. $type refers to the table from which the result was returned. $val is passed in only for use in building URLs for GET
function pgResultsToEditableTableForm($result, $type, $val){
	
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
	echo "\t<form method='POST' action='/~cs3380sp13grp11/eExperiments.php'>\n";
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
		echo "\t\t\t\t<input type='submit' value='View Sessions' formaction='eSessions.php?action=view_exp&val=".$row['expid']."' />\n";
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
