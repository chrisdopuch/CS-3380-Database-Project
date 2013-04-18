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
button{
width:25px;
}
</style>
<!--include the jQuery library-->
<script src="jslibs/jquery-1.9.1.min.js"></script>
<script>
//do this function when the page is fully loaded
$(document).ready(function() {
	//if the report select changes, do this function
	$('#reportSelect').change(function(eventData) {
		$('#extraOptions input, #extraOptions select').detach();
		$('#extraOptions').html('');
		//dynamically generate additional report options
		if($('#reportSelect').val() == 'experiments'){
			$('#extraOptions').append(
				'<input type="radio" name="options" value="all">Show All Experiments<br />',
				'<input type="radio" name="options" value="my">Show My Experiments<br />'
			);
		}
		else if($('#reportSelect').val() == 'sessions'){
			$('#extraOptions').append(
				'<input type="radio" name="options" value="all">Show All Sessions<br />',
				'<input type="radio" name="options" value="my">Show My Sessions<br />'
			);
		}
		else if($('#reportSelect').val() == "participants"){
			$('#extraOptions').append(
				'<input type="radio" name="options" value="all">Show All Participants<br />',
				'<input type="radio" name="options" value="my">Show Participants by Experiment<br />',
				'<select name="experiment">',
				<?php
					//query the DB to get all the experiments
					$result = pg_prepare($connection, "experiments", "SELECT DISTINCT name FROM database.experiments ORDER BY name ASC");
					$result = pg_execute($connection, "experiments", array());
					//if there are no experiments, print none as an option
					if(!$result){
						echo "'<option value=\"none\">none</option>,'";
					}
					//while there are results returned from the query
					While($row = pg_fetch_assoc($result)){
						$name = $row['name'];
						//add the experiment to the list of options
						echo "'<option value=\"$name\">$name</option>,'";
					}
				?>
				
			);
		}
		else if($('#reportSelect').val() == 'contacts'){
			$('#extraOptions').append(
				'<input type="radio" name="options" value="all">Show All Experiments<br />',
				'<input type="radio" name="options" value="my">Show My Experiments<br />'
			);
		}
		else if($('#reportSelect').val() == 'experimenters'){
			$('#extraOptions').append(
				'<input type="radio" name="options" value="all">Show All Experiments<br />',
				'<input type="radio" name="options" value="my">Show My Experiments<br />'
			);
		}
		else if($('#reportSelect').val() == 'users'){
			$('#extraOptions').append(
				'<input type="radio" name="options" value="all">Show All Experiments<br />',
				'<input type="radio" name="options" value="my">Show My Experiments<br />'
			);
		}
		
	});
});
</script>
</head>
<body>
<!--include the header-->
<?php include 'header.php';
//the argument for top() must be either "participant" or "experimenter"
top("experimenter") ?>
<div>
	<div>
		<p>Choose a report to view:</p>
		<form action='eReports.php' method='POST' name='submit' id='reportForm'>
			<select name='reportType' id="reportSelect">
				<option value="experiments">Experiments</option>
				<option value="sessions">Sessions</option>
				<option value="participants">Participants</option>
				<option value="contacts">Contact List</option>
				<option value="experimenters">All Experimenters</option>
				<option value="users">All Users</option>
			</select><br />
			<div id="extraOptions">
			</div>
			<button type='submit' value='Submit' name='submit' ><br />
		</form>
	</div>
</div>
<!--include the footer-->
<?php include 'footer.php'; ?>
</body>
</html>
