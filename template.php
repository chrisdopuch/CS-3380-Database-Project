<!--this is a template for making pages on the website-->
<!--include redirect-->
<?php include 'redirect.php';?>
<!DOCTYPE html>
<!--connect to the database and start the session-->
<?php include 'connect.php';
session_start();?>
<head>
<title>Title goes here...</title>
<!--include the style sheet for the website-->
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<!--include the header-->
<?php include 'header.php'; ?>
Content goes here...
<!--include the footer-->
<?php include 'footer.php'; ?>
</body>
</html>
