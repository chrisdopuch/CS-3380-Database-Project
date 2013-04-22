<!--
This page is used for displaying reports on data held in the database in various ways
Experimenters can view experiments, sessions, sessions by experiment, participants, experimenters, and users
-->
<!DOCTYPE html>
<head>
<title>Reports</title>
<!--connect to the database-->
<?php include 'connect.php';?>
<!--include the style sheet for the website-->
<link rel="stylesheet" type="text/css" href="style.css">
<style>

</style>
<!--include the jQuery library-->
<script src="jslibs/jquery-1.9.1.min.js"></script>
<!-- include data table library
for reference: http://www.datatables.net/index -->
<script src="jslibs/jquery.dataTables.min.js"></script>
<script>
//do this function when the page is fully loaded
$(document).ready(function() {
	//if the report select changes, do this function
	$('#reportSelect').change(function(eventData) {
		$('#extraOptions input, #extraOptions select').detach();
		$('#extraOptions').html('');
		//dynamically generate additional report options
		if($('#reportSelect').val() == 'experiments'){
			$('#extraOptions').append( //add to extra options the following html code
				'<input type="radio" name="options" value="all">Show All Experiments<br />',
				'<input type="radio" name="options" value="my">Show My Experiments<br />'
			);
		}
		else if($('#reportSelect').val() == 'sessions'){
			$('#extraOptions').append( //add to extra options the following html code
				'<input type="radio" name="options" value="all">Show All Sessions<br />',
				'<input type="radio" name="options" value="my">Show My Sessions<br />'
			);
		}
		else if($('#reportSelect').val() == "participants"){
			$('#extraOptions').append( //add to extra options the following html code
				'<input type="radio" name="options" value="all">Show All Participants<br />',
				'<input type="radio" name="options" value="by_exp">Show Participants by Experiment<br />',
				'<select name="experiment">', //drop down menu for experiment
				<?php
					//query the DB to get all the experiments
					$result = pg_prepare($conn, "experiments", "SELECT DISTINCT name FROM database.experiments ORDER BY name ASC");
					$result = pg_execute($conn, "experiments", array());
					//if there are no experiments, print none as an option
					if(!$result){
						echo "'<option value=\"none\">none</option>,'";
					}
					//while there are results returned from the query
					$flag = true;
					While($row = pg_fetch_assoc($result)){
						if(!$flag) echo ", ";
						$flag = false;
						$name = trim($row['name']);
						//add the experiment to the list of options
						echo "'<option value=\"$name\">$name</option>'";
					}
				?>
			);
		}
	});
	//apply datatables format to the report
	$('#reportsTable').dataTable();
});

</script>
</head>
<body>
<!--include the header-->
<?php include 'header.php';
//the argument for top() must be either "participant" or "experimenter"
top("experimenter"); 
//check which option was selected, and set selected variable
if(isset($_POST['submit'])){
	switch($_POST['reportType']){
		case 'experiments':
			$selected = '1';
			break;
		case 'sessions':
			$selected = '2';
			break;
		case 'participants':
			$selected = '3';
			break;
		case 'contacts':
			$selected = '4';
			break;
		case 'experimenters':
			$selected = '5';
			break;
		case 'users':
			$selected = '6';
			break;
	}
}
?>
<div id='main' class='clearfix'>
	<h2>Reports</h2><br />
	<form action='eReports.php' method='POST' name='submit' id='reportForm'>
		<h3 id="formHeader">Choose a report to view:</h3>
		<select name='reportType' id="reportSelect">
			<option value="experiments" <?php if($selected == '1') echo "selected"; ?>>Experiments</option>
			<option value="sessions" <?php if($selected == '2') echo "selected"; ?>>Sessions</option>
			<option value="participants"<?php if($selected == '3') echo "selected"; ?>>Participants</option>
			<option value="contacts"<?php if($selected == '4') echo "selected"; ?>>Contact List</option>
			<option value="experimenters"<?php if($selected == '5') echo "selected"; ?>>All Experimenters</option>
			<option value="users"<?php if($selected == '6') echo "selected"; ?>>All Users</option>
		</select><br />
		<div id="extraOptions">
		</div>
		<input type='submit' value='Submit' name='submit' ><br />
	</form>
</div>
<!--process form submisison -->
<?php
if(isset($_POST['submit'])){
	switch($_POST['reportType']){
		case 'experiments':
			$option = $_POST['options'];
			if($option = "all"){
				$query = "SELECT expid, name, payment, requirements FROM database.experiments ORDER BY expid asc";
				$result = pg_prepare($conn, "all_experiments", $query);
				$result = pg_execute($conn, "all_experiments", array());
			} else if($option = "my"){
				$username = $_SESSION['username'];
				$query = "SELECT expid, name, payment, requirements FROM database.experiments WHERE eid = (SELECT eid FROM experimenters WHERE username = $1) ORDER BY expid asc";
				$result = pg_prepare($conn, "my_experiments", $query);
				$result = pg_execute($conn, "my_experiments", array($username));
			}
			break;
		case 'sessions':
			$option = $_POST['options'];
			if($option = "all"){
				$query = "SELECT sid, (Select name FROM database.experiment as i WHERE i.expid = o.expid) as experiment_name, start_time, end_time, (Select name FROM database.experimenters as i WHERE i.eid = o.eid) as experimenter_name, (Select name FROM database.participants as i WHERE i.pid = o.pid) as participant_name as experimenter_name (SELECT (building+' '+room) as location FROM database.locations as i WHERE i.lid = o.lid) as location  FROM database.sessions as o ORDER BY experiment_name asc";
				$result = pg_prepare($conn, "all_sessions", $query);
				$result = pg_execute($conn, "all_sessions", array());
			} else if($option = "my"){
				$username = $_SESSION['username'];
				$query = "SELECT sid, (Select name FROM database.experiment as i WHERE i.expid = o.expid) as experiment_name, start_time, end_time, (Select name FROM database.experimenters as i WHERE i.eid = o.eid) as experimenter_name, (Select name FROM database.participants as i WHERE i.pid = o.pid) as participant_name as experimenter_name  FROM database.sessions as o WHERE 0.eid = (SELECT eid FROM database.experimenters WHERE username = $1) ORDER BY experiment_name asc";
				$result = pg_prepare($conn, "my_sessions", $query);
				$result = pg_execute($conn, "my_sessions", array($username));
			}
			break;
		case 'participants':
			$option = $_POST['options'];
			if($option = "all"){
				$query = "SELECT pid, first_name, middle_name last_name, username, address, phone_number,(SELECT email FROM database.users as i WHERE i.username = o.username) as email_address, ethnicity, gender, age, education FROM database.participants as o ORDER BY expid asc";
				$result = pg_prepare($conn, "all_participants", $query);
				$result = pg_execute($conn, "all_participants", array());
			} else if($option = "by_exp"){
				$experiment = $_POST['experiment'];
				$query = "SELECT pid, first_name, middle_name, last_name, username, address, phone_number, (SELECT email FROM database.users as i WHERE i.username = o.username) as email_address, ethnicity, gender, age, education FROM database.participants as o WHERE pid = (SELECT pid FROM database.sessions WHERE expid = (SELECT expid FROM database.experiments WHERE name = $1)) ORDER BY last_name asc";
				$result = pg_prepare($conn, "by_exp_participants", $query);
				$result = pg_execute($conn, "by_exp_participants", array($experiment));
			}
			break;
		case 'contacts':
			$query = "SELECT pid, first_name, middle_name, last_name, username, address, phone_number, (SELECT email FROM database.users as i WHERE i.username = o.username) as email_address, ethnicity, gender, age, education FROM database.participants as o ORDER BY expid asc";
			$result = pg_prepare($conn, "contacts", $query);
			$result = pg_execute($conn, "contacts", array());
			break;
		case 'experimenters':
			$query = "SELECT eid, first_name, middle_name, last_name, username, (SELECT email FROM database.users as i WHERE i.username = o.username) as email_address FROM database.experimenters as o ORDER BY eid asc";
			$result = pg_prepare($conn, "experimenters", $query);
			$result = pg_execute($conn, "experimenters", array());
			break;
		case 'users':
			$query = "SELECT username, user_type, email FROM database.users ORDER BY username asc";
			$result = pg_prepare($conn, "users", $query);
			$result = pg_execute($conn, "users", array());
			break;
	}
	if($result){
		//create the table
		make_table($result);
	}
	else{
		echo "No results were returned by your query.";
	}

}
?>
<?php
	function make_table($result){
		// Print the table headers
		$row = pg_fetch_assoc($result);
		
		if (!$row)
			return FALSE;
		
		echo "<table border='1' id='reportsTable'>\n";
		
		echo "<thead>\n";
		echo "<tr>";
		foreach($row as $key => $value)
		{
			echo "<th>$key</th>\n";
		}

		echo "</tr>\n";
		
		echo "</thead>";
		
		// Now print the data from the first row - otherwise
		// that data is lost
		echo "<tbody>";
		echo "<tr>";
		foreach($row as $res)
		{
			echo "<td>$res</td>\n";
		}
		
		echo "</tr>\n";

		while($row = pg_fetch_assoc($result))
		{
			echo "<tr>\n";
			
			foreach($row as $res)
			{
				echo "<td>$res</td>\n";
			}

			echo "</tr>\n";
		}
		
		echo "</tbody>";
	}
?>
<!--include the footer-->
<?php if(!isset($_POST['submit']))include 'footer.php'; ?>
</body>
</html>
