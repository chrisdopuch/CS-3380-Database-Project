<!-- Experiments home page allows admin to navigate through various feature. Links in header and 
in the body are the same. Each link has a description of the pages functionalities. -->
<!DOCTYPE html>
<html>
<head>
<title> Home </title>
	<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<?php
	include 'header.php';
	top("experimenter");
?>
	<!--Experiments desctiption -->
	<a href = "eExperiments.php ">Experiments</a>
		</br>
			&nbsp;&nbsp;&nbsp;&nbsp;
				-View sessions of experiments
		</br>
			&nbsp;&nbsp;&nbsp;&nbsp;
				-Edit or remove experiments
		</br>
		</br>
	
	<!--Sessions desctiption -->	
	<a href = "eSessions.php ">Sessions</a>
		</br>
			&nbsp;&nbsp;&nbsp;&nbsp;
				-Edit or remove sessions
		</br>
			&nbsp;&nbsp;&nbsp;&nbsp;
				-Add new sessions
		</br>
		</br>
		
	<!--Locations desctiption -->				
	<a href = "eLocations.php ">Locations</a>
		</br>
			&nbsp;&nbsp;&nbsp;&nbsp;
				-Room and building of experiments
		</br>
		</br>
		
	<!--Reports desctiption -->
	<a href = "eReports.php ">Reports</a>
		</br>
			&nbsp;&nbsp;&nbsp;&nbsp;
				-Experiments
		</br>
			&nbsp;&nbsp;&nbsp;&nbsp;
				-Sessions
		</br>
			&nbsp;&nbsp;&nbsp;&nbsp;
				-Participants
		</br>
			&nbsp;&nbsp;&nbsp;&nbsp;
				-Contact List
		</br>
			&nbsp;&nbsp;&nbsp;&nbsp;
				-All experimenters
		</br>
			&nbsp;&nbsp;&nbsp;&nbsp;
				-All users
		</br>
		</br>
	
	<!--My account desctiption -->
	<a href = "eUserInfo.php ">My Account</a>
		</br>
			&nbsp;&nbsp;&nbsp;&nbsp;
				-Change your password
		</br>
			&nbsp;&nbsp;&nbsp;&nbsp;
				-Modify contact information
		</br>
			&nbsp;&nbsp;&nbsp;&nbsp;
				-Change your email address
		</br>
		</br>
</body>
</html>
		