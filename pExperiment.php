<?php
//include header
include 'header.php';
include 'connect.php';

top("participant");

	//get experiment info
	$username = $_SESSION['username'];
	
	$query1 = "SELECT expid, eid, session_date, start_time, end_time, lid FROM sessions WHERE sid = $1";
	
	$result1 = pg_query($conn, "getres", $query1);
	$i =0;
	//populates the table with the tours.
	while ($row = pg_fetch_assoc($result1))
	{
	
				$exp_id = $row['expid'];
                        
				$e_id = $row['eid'];
				
				$start_date = $row['session_date'];
				
				$start_time = $row['start_time'];
				
				$end_time = $row['end_time'];
				
	
				
				
						
				$query2 = "SELECT exp_id, name FROM experiment WHERE expid = $1";
				
				$prep2 = pg_prepare($conn, "exp$i", $query2);

                $result2 = pg_execute($conn, "exp$i", array($e_id));

                $row2 = pg_fetch_assoc ($result2);
				
				$exp_name = $row['name'];
				
				
				$query3 = "SELECT building FROM locations WHERE lid = $1";
				
				$prep3 = pg_prepare($conn, "locate$i", array($l_id));
				
				$row3 = pg_fetch_assoc ($result3);
				
				$building_name = $row['building'];
				
				
				
				
				echo "<tr id='row$i'>";
				
				echo "<td> $exp_name </td>";
				
				echo "<td> $e_id </td>";
				
				echo "<td> $building_name </td>";

                echo "<td> $start_date </td>";
				
                echo "<td> $start_time </td>";

                echo "<td> $end_time </td>";
						
				echo "<td> <button onclick='deleteexp($expid, $i)'> Delete </button></td>";
				

			echo "</tr>";
$i++;
	}
?>
</body>
<script>


function deleteexp(exp_id, row)
{

var hiderow = "row" + row;

$("#" + hiderow).hide();


$.post("deletesession.php", 

{

exp_id : exp_id

}
);


}


</script>
</html>
		