<!--
This page is used for displaying reports on data held in the database in various ways
Experimenters can view experiments, sessions, sessions by experiment, participants, experimenters, and users
-->
<!DOCTYPE html>
<head>
<title>Enroll</title>
<!--connect to the database-->
<?php include 'connect.php';?>
<!--include the style sheet for the website-->
<link rel="stylesheet" type="text/css" href="style.css">
<link href="css/start/jquery-ui-1.10.2.custom.css" rel="stylesheet" type="text/css" />


<style>

</style>
<!--include the jQuery library-->
<script src="jslibs/jquery-1.9.1.min.js"></script>
<script src="jslibs/jquery-1.9.1.js"></script>
<script src="jslibs/jquery-ui-1.10.2.custom.js"></script>
<script src="jslibs/jquery-ui-1.10.2.custom.min.js"></script>

<!-- include data table library
for reference: http://www.datatables.net/index -->
<script src="jslibs/jquery.dataTables.min.js"></script>


<script>
//do this function when the page is fully loaded
$(document).ready(function() {
	//if the report select changes, do this function
	$('#DisplayBySelect').change(function(eventData) {
		$('#extraOptions input, #extraOptions select').detach();
		$('#extraOptions').html('');
		//dynamically generate additional report options
		if($('#reportSelect').val() == 'all'){
			$('#extraOptions').append( //add to extra options the following html code
			);
		}
		
		else if($('#DisplayBySelect').val() == 'date'){
			$('#extraOptions').append( //add to extra options the following html code
			$("#datepickerID").datepicker()	                       
			);
		}       
		else if($('#DisplayBySelect').val() == 'time'){
			$('#extraOptions').append( //add to extra options the following html code
				'<input type="radio" name="options" value="mor">Mornings (8am to 12pm)<br />',
				'<input type="radio" name="options" value="aft">Afternoon (12pm to 5pm)<br />',
				'<input type="radio" name="options" value="eve">Evening (5pm to 12am)<br />'
			);
		}
		else if($('#DisplayBySelect').val() == "experiment"){	
			$('#extraOptions').append( //add to extra options the following html code
				'<select name="experiment" id="experimentSelect">');
			$('#experimentSelect').append(
				//drop down menu for experiment
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
	//apply datatables format 
	$('#reportsTable').dataTable();
});

</script>
</head>
<body>

<!-- Calandar form --!>

<form id="form1" value =  name="form1" method="post" action="">
<label for="datepickerID"></label>
<input type="text" name="datepickerID" id="datepickerID" />
</form>


<!--include the header-->
<?php include 'header.php';
//the argument for top() must be either "participant" or "experimenter"
top("participant"); 
//check which option was selected, and set selected variable
if(isset($_POST['submit'])){
	switch($_POST['EnrollType']){
		case 'all':
			$selected ='0';
			break;
		case 'date':
			$selected = '1';
			break;
		case 'time':
			$selected = '2';
			break;
		case 'experiment':
			$selected = '3';
			break;
		
	}
}
?>
<div id='main' class='clearfix'>
	<h2>Enroll</h2><br />
	<form action='pEnroll.php' method='POST' name='submit' id='reportForm'>
		<h3 id="formHeader">Search Experiments:</h3>
		<select name='EnrollType' id="DisplayBySelect">
			<option selected="selected">Select Option</Option>
			<option value="all" <?php if($selected == '0') echo "selected"; ?>>All Experiments</option>
			<option value="date" <?php if($selected == '1') echo "selected"; ?>>By Date of Experiment</option>
			<option value="time" <?php if($selected == '2') echo "selected"; ?>>By Start Time</option>
			<option value="experiment"<?php if($selected == '3') echo "selected"; ?>>By Experiment Name</option>
		</select><br />
		<div id="extraOptions">
		</div>
		<input type='submit' value='Submit' name='submit' ><br />
	</form>
</div>
<!--process form submisison -->
<?php
if(isset($_POST['submit'])){
	switch($_POST['EnrollType']){
		case 'all':
			$query = "SELECT experiments.expid, experiments.payment,experiments.name, sessions.session_date, sessions.start_time, sessions.end_time FROM database.experiments INNER JOIN database.sessions ON (experiments.expid = sessions.expid) ORDER BY sessions.session_date asc";
				$result = pg_prepare($conn, "all_experiments", $query);
				$result = pg_execute($conn, "all_experiments", array());
			break;

		case 'date':
			 
		        	$mydate=$_POST['datepickerID'];
				// change format to match database format of "date" (mm-dd-yyyy)       
				$show_date = DateTime::createFromFormat('m/d/Y', $mydate)->format('Y-m-d');	
				$query = "SELECT  experiments.expid,experiments.payment,experiments.name, sessions.session_date, sessions.start_time, sessions.end_time FROM database.experiments INNER JOIN database.sessions ON(experiments.expid = sessions.expid) WHERE(sessions.session_date = '$show_date') ORDER BY sessions.session_date asc";
				$result = pg_prepare($conn, "chosen_Date", $query);
				$result = pg_execute($conn, "chosen_Date", array());
						
			break;

		case 'time':
			$option = $_POST['options'];
			if($option = "mor"){
				$query = "SELECT  experiments.expid,experiments.payment,experiments.name, sessions.session_date, sessions.start_time, sessions.end_time FROM database.experiments INNER JOIN database.sessions ON(experiments.expid = sessions.expid) WHERE(sessions.start_time < '12:00:00') ORDER BY sessions.session_date asc";
				$result = pg_prepare($conn, "morning_sessions", $query);
				$result = pg_execute($conn, "morning_sessions", array());
			} else if($option = "aft"){
				$query = "SELECT  experiments.expid,experiments.payment,experiments.name, sessions.session_date, sessions.start_time, sessions.end_time FROM database.experiments INNER JOIN database.sessions ON(experiments.expid = sessions.expid) WHERE(sessions.start_time < '17:00:00') ORDER BY sessions.session_date asc";
				$result = pg_prepare($conn, "afternoon_sessions", $query);
				$result = pg_execute($conn, "afternoon_sessions", array($username));
			} else if($option = "eve"){
                                $query = "SELECT  experiments.expid,experiments.payment,experiments.name, sessions.session_date, sessions.start_time, sessions.end_time FROM database.experiments INNER JOIN database.sessions ON(experiments.expid = sessions.expid) WHERE(sessions.start_time < '24:00:00') ORDER BY sessions.session_date asc";
                                $result = pg_prepare($conn, "afternoon_sessions", $query);
                                $result = pg_execute($conn, "afternoon_sessions", array($username));
                        }
			break;

		case 'experiment':

				$experiment = $_POST['experiment'];
				echo $experiment;
				$query ="SELECT  experiments.expid,experiments.payment,experiments.name, sessions.session_date, sessions.start_time, sessions.end_time FROM database.experiments INNER JOIN database.sessions ON(experiments.expid = sessions.expid) WHERE(experiments.name = '$experiment') ORDER BY sessions.session_date asc";
				$result = pg_prepare($conn, "by_experiments", $query);
				$result = pg_execute($conn, "by_experiments", array($experiment));
			
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
	$rows = 0;

        if (!$row)
                return FALSE;
		echo "<table border='1'>"; 
                echo "<thead>\n";
               
        echo "<tr>";

        echo "<th>Action</th>";
        foreach($row as $key => $value)
        {
                echo "<th>$key</th>";
        }

        echo "</tr>";

        // Now print the data from the first row - otherwise
        // that data is lost
        echo "<tr>";
        enroll_button($row);


        foreach($row as $res)
        {
                echo "<td>$res</td>";
        }

        echo "</tr>";

        while($row = pg_fetch_assoc($result))
        {
                //print_r($row);
                echo "<tr>";
                $rows++;
              	enroll_button($row);

                foreach($row as $res)
                {
                     echo "<td>$res</td>";
                }

                echo "</tr>";
        }
                $rows++;
        echo "There were $rows experiments returned" . "<br></br>";


}

FUNCTION enroll_button($row)
{
		$expid = $row['expid'];
		$session_date = $row['session_date'];
		$start_time = $row['start_time'];
                $end_time = $row['end_time'];
                $payment = $row['payment'];

		//enroll button
                echo "<td><form action='enrollment_confirm.php' method='POST'>
                <input type='hidden' name='expid' value= $expid />
                <input type='hidden' name='session_date' value= $session_date />
                <input type='hidden' name='start_time' value=$start_time />
		<input type='hidden' name='end_time' value=$end_time />
		<input type='hidden' name='payment' value=$payment />
                <input type='submit' name='submit' value='Enroll' />
                </form></td>";

}

?>
<!--include the footer-->
<?php if(!isset($_POST['submit']))include 'footer.php'; ?>
</body>
</html>

