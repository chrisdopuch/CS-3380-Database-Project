<?php
/* 	
This file should be included on every page except index.php and registration.php
It does three things:
1. Checks if user is logged in and if not, redirect to index.php
2. Finds what type of user they are, and if they don't belong on that page, redirect them to the appropriate Home.php file
3. Display the header based on what kind of user
 */
session_start();
/*
This is the function you should call immediately after including the file
Input: the type of user intended to use the page, should be either "experimenter" or "participant" 
Output: html for the header
*/
function top($user_type){

	/* DEBUGGING ENVIRONMENT VARIABLE
		When set to TRUE, debug tools will appear on the experimenter header.
	*/
	$debug = true;
	
	//validate argument input
	if($user_type != "experimenter" && $user_type != "participant"){
		//print error message
		echo "<script>alert(\"Error: the input to header(user_type) must be a string containing either participant or experimenter\");</script>\n";
		return;
	}
	
	//check if a user is logged in or not
	if(!isset($_SESSION['username'])){
		//if no user is logged on, redirect to the login page
		header("Location:  http://babbage.cs.missouri.edu/~cs3380sp13grp11/index.php");
		exit;
	}
	
	//get session user type and name
	$session_username = trim($_SESSION['username']);
	$session_user_type = trim($_SESSION['user_type']);
	
	//make sure the user is on a page they are supposed to be on
	//if the user type requested by the calling page isn't the same as the user's type
	if($user_type != $session_user_type){
		//redirect to the proper home page

		if($user_type == "participant"){
			header("Location:  http://babbage.cs.missouri.edu/~cs3380sp13grp11/eHome.php");
			exit;
		} else if($user_type == "experimenter") {
			header("Location:  http://babbage.cs.missouri.edu/~cs3380sp13grp11/pHome.php");
			exit;
		}
	}

	//print banner image
	echo "<div id = \"banner\">\n<img src = \"images/banner.jpg\" alt=\"Mizzou Physcology\"/>\n</div>\n";
	
	//build header
	echo "<header>\n<div id=\"headerLinksDiv\">\n";
	
	//Dynamically generate content for header based on user type
	if($user_type == "experimenter"){
			echo "<a class=\"headerLinks\" href=\"eHome.php\">Home</a>\n";
			echo "<a class=\"headerLinks\" href=\"eExperiments.php\">Experiments</a>\n";
			echo "<a class=\"headerLinks\" href=\"eSessions.php\">Sessions</a>\n";
			echo "<a class=\"headerLinks\" href=\"eLocations.php\">Locations</a>\n";
			echo "<a class=\"headerLinks\" href=\"eReports.php\">Reports</a>\n";
			echo "<a class=\"headerLinks\" href=\"eUserInfo.php\">My Account</a>\n";
			if ($debug){
				echo "<a class=\"headerLinks\" href=\"dataedit.php\">DEBUG: Edit Data</a>\n";
			}
		}
		else{
			echo "<a class=\"headerLinks\" href=\"pHome.php\">Home</a>\n";
			echo "<a class=\"headerLinks\" href=\"pEnroll.php\">Sign Up</a>\n";
			echo "<a class=\"headerLinks\" href=\"pSessions.php\">My Sessions</a>\n";
			echo "<a class=\"headerLinks\" href=\"pUserInfo.php\">My Account</a>\n";
		}
	
	//finish header
	echo "<a id=\"headerLogout\" href=\"logout.php\">Logout</a>\n</div>\n</header>\n";
}
?>
