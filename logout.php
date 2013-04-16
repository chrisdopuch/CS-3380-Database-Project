<?php
	session_start();
	unset($_SESSION['username']);
	unset($_SESSION['user_type']);
    session_unset();
    session_destroy();
    session_write_close();
	header("Location: http://babbage.cs.missouri.edu/~cs3380sp13grp11/index.php");
?>