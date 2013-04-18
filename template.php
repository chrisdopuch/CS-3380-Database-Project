<!--this is a template for making pages on the website-->
<!DOCTYPE html>

<head>
<title>Title goes here...</title>
<!--connect to the database-->
<?php include 'connect.php';?>
<!--include the style sheet for the website-->
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<!--include the header-->
<?php include 'header.php';
//the argument for top() must be either "participant" or "experimenter"
top("") ?>
Content goes here...
<!--include the footer-->
<?php include 'footer.php'; ?>
</body>
</html>
