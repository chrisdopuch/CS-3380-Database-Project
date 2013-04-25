<?php
$connString = "host=dbhost-pgsql.cs.missouri.edu user=cs3380sp13grp11 dbname=cs3380sp13grp11 password=vTc9pwMw"; 

$dbconn = pg_connect($connString ) or die("Problem with connection to PostgreSQL:".pg_last_error());


$loggedIn = empty($_SESSION['user']) ? false : $_SESSION['user'];
if ($loggedIn == false) {
	header ("Location: index.php");
	exit;
	}
	
?>

<!DOCTYPE html>
<html>
<head>
<title> Experiments </title>
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
{
</style>
</head>
<body>
<?php
//include header
include 'header.php';
top("participant");
?>
	<?php
	//get experiment info
	$user_id = $_SESSION['userid'];
	
	$query1 = "SELECT expid, eid, session_date, start_time, end_time FROM sessions WHERE sid = $1";
	
	$result1 = pg_query($dbconn, "getres", $query1);
	$i =0;
	//populates the table with the tours.
	while ($row = pg_fetch_assoc($result1))
	{
	
				$exp_id = $row['expid'];
                        
				$e_id = $row['eid'];
				
				$start_date = $row['session_date'];
				
				$start_time = $row['start_time'];
				
				$end_time = $row['end_time']:
				
				
						
				$query2 = "SELECT exp_id, name FROM experiment WHERE expid = $1";
				
				$prep2 = pg_prepare($dbconn, "res$i", $query2);

                $result2 = pg_execute($dbconn, "res$i", array($e_id));

                $row2 = pg_fetch_assoc ($result2);
				
				$exp_name = $row['name'];
				
				
				
				echo "<tr id='row$i'>";
				
				echo "<td> $exp_name </td>";
				
				echo "<td> $e_id </td>";

                echo "<td> $start_date </td>";
				
                echo "<td> $start_time </td>";

                echo "<td> $end_time </td>";
						
				echo "<td> <button onclick='deleteres($expid, $i)'> Delete </button></td>";
				

			echo "</tr>";
$i++;
	}
?>
</body>
<script>
//delete experiment row
function deleteexp(exp_id, row)
{

var hiderow = "row" + row;

$("#" + hiderow).hide();


$.post("deletetour.php", 

{

exp_id : exp_id

}
);


}


</script>
</html>
		