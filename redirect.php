<?php
//check if a user is already logged in
if(!isset($_SESSION['username'])){
	//if no user is logged on, redirect to the login page
	header("Location:  http://babbage.cs.missouri.edu/~cs3380sp13grp11/index.php");
}
?>