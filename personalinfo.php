<?php

	$page = "update";
	
?>

<html> 
<head>
<style>

form, fieldset {
margin: 0;
padding: 0;
vertical-align: middle;
}
form {
width: 500px;
background: #BBBFC1;
color: #000;
font: small Verdana, sans-serif;
}

label {
vertical-align: middle;
font-weight: bold;
}
input {
font: 1em Verdana, sans-serif;
vertical-align: middle;
}

legend {
padding: 0 0.2em;
background: #000000;
color: #D5D038;
font-weight: bold;
text-transform: uppercase;
margin-left: 0.5em;
}
</style> 



<!-- Updates Personal Information--> 

</head> 
<body>

<form method="#" action="action">

<fieldset>
<legend>Personal Infomation </legend>
	<label for="name">First Name:</label>
	<input type="text" size="12" maxlength="12" name="Fname">:<br />
	<label for="name">Last Name:</label>
	<input type="text" size="12" maxlength="36" name="Lname">:<br />
	
	<label for="name">Address:</label>
    <input type="text" size="20" maxlength="40" name="address"><br />
	<label for="name">City:</label>
    <input type="text" size="12" maxlength="25" name="city">: <br />
	<label for="name">State:</label>
	<input type="text" size="2" maxlength="2" name="state">: <br /> 
	<label for="name">Zip Code:</label>
    <input type="text" size="5" maxlength="5" name="zip">: <br /> 
	<label for="name">Phone:</label>
	<input type="text" size="10" maxlength="10" name="phone">: <br />
	<label for="name">E-mail:</label>
	<input type="text" size="25" maxlength="40" name="email">: <br />
	<h2> Comments </h2>
    <textarea cols="50" rows="4" name="comments"></textarea>
    <input type="submit" value="Update">
</fieldset>
</form>

</body>