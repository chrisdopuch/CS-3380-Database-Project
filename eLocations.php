<!DOCTYPE html>
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

//set error reporting
ERROR_REPORTING(E_ALL);
ini_set("display_errors", 1);

//check if already logged in, and redirect
if(isset($_SESSION['username'])){
        $user_type = $_SESSION['user_type'];
        if($user_type == "experimenter")
        {

        // connect to database, Counld NOT use the "include connect.php" due to the use of the variable $conn when I created this program
        //$conn = pg_connect("host=dbhost-pgsql.cs.missouri.edu user=cs3380sp13grp11 password=KnV7FH9kfq-ro  dbname=cs3380sp13grp11");
		include 'connect.php';
		
		
        if (!$conn)
                {
                die("Failed to connect to database: " . pg_last_error($conn) );  // error checking 
                }

        $name = $_POST['query'];
        $type = 3;      // can be taken out but just used as a continuity check and is used throughout the program
        $query = "SELECT * FROM database.locations ORDER BY building"; // querry will return all locations and order them by building name

        if($button == update)   // if update button is depressed, prepare query
                {
                $query =   $result = pg_prepare($conn, 'prepare', $query); // prepare the update query
                if (!$result)
                        {
                        echo "Error with pg_prepare: " . pg_last_error(); // error checking
                        exit;
                        }

                 $result = pg_execute($conn, "prepare", array( $lid)); // execute the update
                 if(!$result)
                        {
                        echo "Error with pg_execute: " . pg_last_error(); // error checking
                        exit;
                        }
                }

        else if($button == delete)      // if delete button is depressed, delete the selected row from DB
		                {
                $query = "DELETE FROM database.locations WHERE lid = $1";
                }


        $result = pg_prepare($conn, "search", $query); // prepare the search query
        if (!$result)
                {
                echo "Error with pg_prepare: " . pg_last_error(); // error checking
                exit;
                }

        $updatedname = $name . "%";
        $result = pg_execute($conn, "search", array( $updatedname )); // execute search query

        $counter = 0; // create a counter to count # of locations

        echo '<table border="1">';
        while($row = pg_fetch_assoc($result))   // while loop to send all data to table function
                {
                to_table($row, $type); // send to table row and check type
                $counter = $counter+1; // increment counter
                }
        echo "There are " . $counter . " locations. <br /><br />"; // display how man locations are in Database
        echo '</table>';

}
///////////////////////// PRINT TABLE FUNCTION///////////////////////////
function to_table($row, $type)
        {
        static $counter = 0;
        if($counter == 0)
                {
                echo '<tr>';
                echo'<th>Actions</th>';
                echo '</th>';
                foreach($row as $key => $value)
                        {
                        echo "<th>$key</th>";
                        }
                echo '</tr>';
                $counter = $counter + 1;
                }
                echo '<tr>';
                $lid = $row['lid'];

        // delete button        
        echo "<td><form action = delete.php onsubmit='return confirmdelete();' method='POST'></a>
        <input type='submit' name='submit' value= 'Delete' />";
        echo "<input type='hidden' name='lid' value= $lid />";
        echo "<input type='hidden' name='type' value=$type /></form>";
        echo " <div> <input type='hidden' name='confirm' id='confirm' value='false'></div>";
        echo "</form>";

        // update button
        echo "<form action = update.php method='POST'></a>
        <input type='submit' name='submit' value= 'Update' />";
        echo "<input type='hidden' name='lid' value= $lid />";
        echo "<input type='hidden' name='type' value=$type /></form>";
        echo '</td>';

        foreach($row as $res)
                {
                echo "<td>$res</td>";
                }

        echo '</tr>';
        }

?>
</div>
</div>

<!--include the footer-->
<?php include 'footer.php'; ?>

</body>
</html>