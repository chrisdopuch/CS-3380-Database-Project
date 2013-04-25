<!DOCTYPE html>
<!--connect to the database and start the session-->
<?php include 'connect.php';
session_start();?>

<head>
<title>Locations</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--include the style sheet for the website-->
<link rel="stylesheet" type="text/css" href="style.css" />
<style type = "text/css">
#top
        {
        text-align: center;
        border: 1px solid blue;
        padding-left: 10px;
        background-color: #FFD651;
        text-color: black;
        }
#Options
        {
        text-align: left;
        padding-right: 30px;
        background-color: white;
        }
</style>



</head>
<body>
<?php include 'header.php';
top('experimenter');
?>

<div id="container">
        <div id="content">
        <p>&nbsp;</p>

<?php


$action = $_GET['action']; // edit, remove, edit_commit, NOTHING
$val = $_GET['val']; //lid

switch ($action)
	{
        //this action shows an editable form to the user
        case "add":
		$val = 'add';// setting val to add so that it will come back to add after the info has been inserted
				
		if ($bool != 1)
			{
			$bool = 1;
			echo"<form action='eLocations.php?action=add&val=".$val."' method='post'>";
			echo"<table>";
			//echo"<tr><td>Location ID:</td><td><input type='Integer' name='lid'></td></tr>";
			echo"<tr><td>Room Number:</td><td><input type='Integer' name='room'></td></tr>";
			echo"<tr><td>Building Name:</td><td><input type='text' name='building'></td></tr>";
			echo"<tr><td colspan='1'><input type='submit' value='Submit'></td></tr>";
			echo"</table>";
			echo"</form>";
			}
		else
			{
	                //$lid = $_POST['lid'];
	                $room = $_POST['room'];
	                $building = $_POST['building'];
	        	$query = 'INSERT INTO database.locations (room,building) VALUES ($1, $2)';
			$result = pg_prepare($conn, "add", $query);
			$result1 = pg_execute($conn, "add", array($room, $building));
			
        
			if ($result1)
                	        {
                	         echo "\tLocation was successfully added. <br />\n";
                	         echo "\tReturn to <a href='eLocations.php'>search page</a>.";
                	        }
                	else
                	        {
                	         echo "\tAdding the location FAILED: ".pg_last_error($conn)."<br />\n";
                	         echo "\tReturn to <a href='eLocations.php'>search page</a>.";
                	        }
	
			$bool = 0;
			}
             	break;
	case "edit":
	        //define the query to select the location of interest
                $query = "SELECT * FROM database.locations WHERE lid = $1";
                //prepare the query
                $stmt = pg_prepare($conn, "select_lid", $query);
                //execute query with the desired location code
                $result = pg_execute($conn, "select_lid", array($val));
                //print the edit form
                pgResultsToEditableTableForm($result, $type, $val);
                break;

	//this action removes the selected experiment from the database
        case "remove":
        	$query = "DELETE FROM database.locations WHERE lid = $1";
                //prepare the query
                $stmt = pg_prepare($conn, "delete_exp", $query);
                //execute query 
                $result = pg_execute($conn, "delete_exp", array($val));
                if ($result)
			{
                         echo "\tUpdate was successful. <br />\n";
                         echo "\tReturn to <a href='eLocations.php'>search page</a>.";
                        }
                else
			{
                         echo "\tUpdate FAILED: ".pg_last_error($conn)."<br />\n";
                         echo "\tReturn to <a href='eLocations.php'>search page</a>.";
                        }
                break;

	//this action saves the values entered into the edit form to the database
        case "edit_commit":
		
		//get postvars
		$lid = $_POST['lid'];
                $room = $_POST['room'];
                $building = $_POST['building'];
                //define the query to update the city table
                $query = "UPDATE database.locations SET (room, building) = ($2, $3) WHERE lid = $1";
                //prepare the query
                $stmt = pg_prepare($conn, "update_exp", $query);
                //execute the query with user's values
                $result = pg_execute($conn, "update_exp", array($lid,$room, $building));
                //Check to see if the query was successful
                if ($result)
			{
                         echo "\tUpdate was successful. <br />\n";
                         echo "\tReturn to <a href='eLocations.php'>search page</a>.";
                        }
                else
			{
                         echo "\tUpdate FAILED: ".pg_last_error($conn)."<br />\n";
                         echo "\tReturn to <a href='eLocations.php'>search page</a>.";
                        }
                break ;

	//No action selected; show all experiments
        default:
        	$query = "SELECT * FROM database.locations ORDER BY lid";
                //prepare the query
                $stmt = pg_prepare($conn, "query", $query);
                //execute the query 
                $result = pg_execute($conn, "query", array());
                //Die if the query fails
                if (!$result)
			{
                         die("Unable to execute query: " . pg_last_error($conn));
                        }

                //Print the results of the query in a nice table
                pgResultToTableWithButtons($result, "locations");
                //Print out how many locations were returned
                echo "\t<p>There were <em>".pg_num_rows($result)."</em> locations returned.</p>\n";
                break;
	
	}//end switch



//Prints a table from a pg query result. $type refers to the table from which the result was returned. $val is passed in only for use in building URLs for GET
function pgResultsToEditableTableForm($result, $type, $val)
	{
	include 'connect.php';
        //make sure $result is not null
        if (!$result)
		{
                 die("Unable to execute query: " . pg_last_error($conn));
                }

	//Print form
        echo "\t<form method='POST' action='eLocations.php?action=edit_commit&val=".$val."'>\n";
        //Print table
        echo "\t<table border='1'>\n";
        $row = pg_fetch_assoc($result);
        for ($i = 0; $i < pg_num_fields($result); $i++)
		if($i == 0){
		 echo "\t\t<tr>\n";
                 $fieldname = pg_field_name($result, $i);
                 echo "\t\t\t<td><strong>".$fieldname."</strong></td>\n";
                 echo "\t\t\t<td><input type='text' name='".$fieldname."' value='".$row[$fieldname]."' readonly /></td>";
                 echo "\t\t</tr>\n";

		}	
		else{
                 echo "\t\t<tr>\n";
                 $fieldname = pg_field_name($result, $i);
                 echo "\t\t\t<td><strong>".$fieldname."</strong></td>\n";
                 echo "\t\t\t<td><input type='text' name='".$fieldname."' value='".$row[$fieldname]."' /></td>";
                 echo "\t\t</tr>\n";
                }
	echo "\t</table>\n";
        echo "\t<input type='submit' value='Save' />\n";
        echo "\t<input type='button' value='Cancel' onclick=\"top.location.href='eLocations.php';\" />\n";
        echo "\t</form>";

        } // end function



//Function to retrieve locations data and display it with buttons for user interaction
function pgResultToTableWithButtons($result, $entryType)
	{
        //Print form
        echo "\t<form method='POST' action='/~cs3380sp13grp11/dataedit_exec.php'>\n";
        //Print headers
        echo "\t<table border='1'>\n";
        echo "\t\t<tr>\n";
        //print "Actions" header
        echo "\t\t\t<th>Actions</th>\n";
	//print the rest of the headers
        for ($i = 0; $i < pg_num_fields($result); $i++)
		{
                 $fieldname = pg_field_name($result, $i);
                 echo "\t\t\t<th>$fieldname</th>\n";
                }
        echo "\t\t</tr>\n";
        //Print the rows
        while($row = pg_fetch_assoc($result))
		{
                //Prepare buttons
                switch($entryType)
			{
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
		echo "\t\t\t\t<input type='submit' name='Edit' value='Edit' formaction='eLocations.php?action=edit&".$buttonAction."' />\n";
		echo "\t\t\t\t<input type='submit' name='Remove' value='Remove' formaction='eLocations.php?action=remove&".$buttonAction."' />\n";
	        echo "\t\t\t</td>\n";
                //Print row contents
                foreach($row as $entry)
			{
			 echo "\t\t\t<td>$entry</td>\n";
                        }
                echo "\t\t</tr>\n";
                } //end while loop

	// add button
	echo "\t\t<tr>\n";
        //Print the buttons
        echo "\t\t\t<td>\n";
        echo "\t\t\t\t<input type='submit' name='Add' value='Add Location' formaction='eLocations.php?action=add&".$buttonAction."' />\n";


	echo "\t</table>\n";
        echo "\t</form>";
        } // end function

?>
</div>
<!--include the footer-->
<!--?php include 'footer.php'; ?-->
</body>
</html>
