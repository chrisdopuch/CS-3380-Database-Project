<!--this is a template for making pages on the website-->
<!DOCTYPE html>

<head>
<title>Enrolled</title>
<!--connect to the database-->
<?php
 session_start();
 include 'connect.php';?>
<!--include the style sheet for the website-->
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<!--include the header-->
<?php include 'header.php';
//the argument for top() must be either "participant" or "experimenter"
top("participant") ?>
<div id='main' class='clearfix'>
<?php 
                $expid = $_POST['expid'];
                $session_date = $_POST['session_date'];
                $start_time = $_POST['start_time'];
                $end_time = $_POST['end_time'];
                $payment = $_POST['payment'];
		$sid = $_POST['sid'];

		// Get the username of who is logged in
		$user_name= $_SESSION['username']; 

		// Query db to return the users pid
		$query = 'SELECT pid FROM database.participants WHERE username = $1';
	
		$stmt0 = pg_prepare($conn, "pid_match", $query);
		if (!$stmt0)
	                {
	                echo "Error with pg_prepare: " . pg_last_error();
	                exit;
	                }
		$result0 = pg_execute($conn, "pid_match",array($user_name));
		if (!$result0)
	                {
	                echo "Error with pg_execute: " . pg_last_error();
	                exit;
	                }
		
	// search the row for the pid
	$row = pg_fetch_assoc($result0);	
        $pid = $row['pid'];
	

///////////////////////////////////////////// CHECK REQUIREMENTS /////////////////////////////////////
$check = validate_user_against_requirements($expid, $username);
	 if (!$check)
                {
                echo "Sorry, You do not meet the requirements for this experiment";
		?>Click <a href='pEnroll.php'>here</a> to return to the Enrollment page.<br /><?php
                exit;
                }
	
///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////// EMail //////////////////////////////////////////////////
	include 'email.php';
	enroll_email($sid);
	
/////////////////////////////////////////////////////////////////////////////////////////////////////
	 

	// query the db to update the experiment session pid to the current users pid
	$result2 = pg_prepare($conn, "pid_insert","UPDATE database.sessions SET pid = $1 WHERE sid = $2");
	if (!$result2)
		{
	        echo "Error with pg_prepare: " . pg_last_error();
       		exit;
		}

	$result3 = pg_execute($conn, "pid_insert", array($pid, $sid));
	if (!$result3)
		{
	        echo "Error with pg_execute: " . pg_last_error();
	        exit;
		}

	if($result2 && $result3)
	echo "You are successfully signed up for the following experiment.";
	echo "<br />";

	$query4 = 'SELECT * FROM database.sessions WHERE sessions.pid = $1';
	$result= pg_prepare($conn, "session_display", $query4);
	 if (!$result)
                {
                echo "Error with pg_prepare: " . pg_last_error();
                exit;
                }
	$result = pg_execute($conn, "session_display", array($pid));
	 if (!$result)
                {
                echo "Error with pg_execute: " . pg_last_error();
                exit;
                }

make_table($result);


function make_table($result)
	{
	//Print form
        echo "\t<form method='POST' action='/~cs3380sp13grp11/pEnroll_confirm.php'>\n";
        //Print headers
        echo "\t<table border='1'>\n";
        echo "\t\t<tr>\n";
        //print the rest of the headers
        for ($i = 0; $i < pg_num_fields($result); $i++){
                $fieldname = pg_field_name($result, $i);
                echo "\t\t\t<th>$fieldname</th>\n";
        }
        echo "\t\t</tr>\n";

        //Print the rows
        while($row = pg_fetch_assoc($result)){
                //Prepare buttons
       
       		foreach($row as $entry)
			{
                        echo "\t\t\t<td>$entry</td>\n";
                	}
                echo "\t\t</tr>\n";
        }

        echo "\t</table>\n";
        echo "\t</form>";
	}


/*
 * IN: the exact expid for an experiment and username for a user
 * OUT: boolean true or false
 * FUNCTION: compares a user's demographic information with an experiment's requirements and returns true if they
 *                              are eligable and false if they are not
 * PROCESS INTUITION: experiments store their requirements as a JSON object. This function queries for this object and
                                for all of the user's information required to do the checks. It then deserializes the experiment's JSON
                                string and starts doing comparisons until one fails or they all succeed.
 *
 */

function validate_user_against_requirements($expid, $username)
{
	
        //build queries
        $query1 = "SELECT requirements FROM database.experiments WHERE expid = $1";
        $query2 = "SELECT ethnicity, gender, age, education FROM database.users WHERE username = $1";
        //prepare the query
        $stmt1 = pg_prepare($conn, "exp_query", $query1);
        $stmt2 = pg_prepare($conn, "user_query", $query2);
        //execute the query 
        $result1 = pg_execute($conn, "exp_query", array($expid));
        $result2 = pg_execute($conn, "user_query", array($username));

        if (!$stmt1){
                die("Unable to prepare query1: " . pg_last_error($conn));
	}
	//Die if either query fails
        if (!$result1){
                die("Unable to execute query1: " . pg_last_error($conn));
        }
        if (!$result2){
                die("Unable to execute query2: " . pg_last_error($conn));
        }

        //returns an assoc array formed from the JSON field returned by the query
        $requirements = json_decode(pg_fetch_result($result1, 0, "requirements"));
        //check that the decode went alright
        if ($requirements == NULL){
                echo "something goofy goin' on with your requirements on that study: ".json_last_error()."\n";
                echo "if the study's requirements are set to \"none\" it will cause this error; \"none\" is not valid JSON.";
                return FALSE;
        }
        else if ($requirements )
        $credentials = pg_fetch_assoc($result2);
		        //start checking requirements
        //ethnicity
        //If ethnicity is not set to "don't care" in the requirements field
        if ($requirements["ethnicity"]["sel"] != "x"){
                //switch on the operator; available options: "is" and "is not"
                switch ($requirements["ethnicity"]["op"]){
                        case "is":
                                //if it "is" NOT the selected option, return false
                                if ($requirements["ethnicity"]["sel"] != $credentials["ethnicity"]){
                                        return FALSE;
                                }
                                break;
                        case "is not":
                                //if it "IS" the selected option, return false
                                if ($requirements["ethnicity"]["sel"] == $credentials["ethnicity"]){
                                        return FALSE;
                                }
                                break;
                }
        }
        //gender
        //If gender is not set to "don't care"
        if ($requirements["gender"]["sel"] != "x"){
                //no switch because the only operator is "is"
                //if it IS NOT the required gender, return false
                if ($requirements["gender"]["sel"] != $credentials["gender"]){
                        return FALSE;
                }

        }

        //age
        //If the operator is not set to "don't care"
        if ($requirements["age"]["op"] != "x"){
                //switch on the remaining operators
                switch ($requirements["age"]["op"]){
                        case "==":
                                //if the age requirement is not exactly equal to the credential, return false
                                if ($requirements["age"]["sel"] != $credentials["age"]){
                                        return FALSE;
                                }

                                break;
                        case ">=":
                                //if is is not the case that the credential is greater than or equal to the requirement, return false
                                if (!($credentials["age"] >= $requirements["age"]["sel"])){
                                        return FALSE;
                                }

                                break;
                        case "<=":
                                //if is is not the case that the credential is less than or equal to the requirement, return false
                                if (!($credentials["age"] <= $requirements["age"]["sel"])){
                                        return FALSE;
                                }
                                break;
                }
        }

        //education
        //If the operator is not set to "don't care"
        if ($requirements["education"]["op"] != "x"){
                //switch on the remaining operators
                switch ($requirements["education"]["op"]){
                        case "==":
                                //if the education requirement is not exactly equal to the credential, return false
                                if ($requirements["education"]["sel"] != $credentials["education"]){
                                        return FALSE;
                                }
                                break;
                        case ">=":
                                //if is is not the case that the credential is greater than or equal to the requirement, return false
                                if (!($credentials["education"] >= $requirements["education"]["sel"])){
                                        return FALSE;
                                }
                                break;
                        case "<=":
                                //if is is not the case that the credential is less than or equal to the requirement, return false
                                if (!($credentials["education"] <= $requirements["education"]["sel"])){
                                        return FALSE;
                                }
                                break;
                }
        }

        //otherwise everything checks out
        return TRUE;
}
	
?>

Click <a href='pEnroll.php'>here</a> to return to the Enrollment page.<br />
<!--include the footer-->
</div>
<?php include 'footer.php'; ?>
</body>
</html>

