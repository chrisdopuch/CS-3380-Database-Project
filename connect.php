<?php

//Credentials
$host = "dbhost-pgsql.cs.missouri.edu";
$user = "cs3380sp13grp11";
$pass = "b81nEx78";
$name = "cs3380sp13grp11";

//Connect
$conn = pg_connect("host=".$host." user =".$user." password=".$pass." dbname=".$name) or die("Could not connect to ".$host.".");

session_start();

?>
