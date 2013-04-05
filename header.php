<!-- 	
This is the header that will be displayed on top of all the pages in our website
It dynamically generates which links to display in the header
 -->
<?php

//get user name
$user = $_SESSION['username'];

//get user type
$user_type = $_SESSION['user_type'];

/*
//if you want to test this header out before the login process is working, uncomment this block, and comment out everything else in the php block
$user_type = "experimenter";
*/
?>
<header>
	<div id="headerLinksDiv">
		<a class="headerLinks" href="home.php">Home</a>
		<?php
		if($user_type == "experimenter"){
			echo "<a class=\"headerLinks\" href=\"experiments.php\">Experiments</a>\n";
			echo "<a class=\"headerLinks\" href=\"sessions.php\">Sessions</a>\n";
			echo "<a class=\"headerLinks\" href=\"users.php\">Users</a>\n";
		}
		else{
			echo "<a class=\"headerLinks\" href=\"signup.php\">Sign Up</a>\n";
			echo "<a class=\"headerLinks\" href=\"participant_sessions.php\">My Sessions</a>\n";
		}
		?>
		<a class="headerLinks" href="account.php">My Account</a>
		<a id="headerLogout" href="logout.php">Logout</a>
	</div>
</header>