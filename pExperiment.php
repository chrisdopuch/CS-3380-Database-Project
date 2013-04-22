<?php
$connString = "host=dbhost-pgsql.cs.missouri.edu user=cs4320sp13grp14 dbname=cs4320sp13grp14 password=vTc9pwMw"; 

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
	$expid = $_SESSION[''];
	
	$query1 = "SELECT name, expid FROM experiments WHERE expid = $1";
	
	$result1 = pg_query($dbconn, $query1);
	$i =0;
	//populates the table with the tours.
	while ($row = pg_fetch_assoc($result1))
	{
	
		$name = $row['name'];
		$expid = $row['expid'];
		
		
		
				echo "<tr id='row$i'>";

				echo "<td> $name </td>";
						
				echo "<td> <button onclick='deleteres($expid, $i)'> Delete </button></td>";
				

			echo "</tr>";
$i++;
	}
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
		