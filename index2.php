<?php
session_start();
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<title>Dimension Tables</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>

<style>

.custom-file-upload {
    border: 1px solid #ccc;
    display: inline-block;
    padding: 6px 12px;
    cursor: pointer;
}

/* Customize the label (the container) */
.container {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer;
  font-size: 22px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* Hide the browser's default checkbox */
.container input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
}

/* Create a custom checkbox */
.checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 25px;
  width: 25px;
  background-color: #eee;
}

/* On mouse-over, add a grey background color */
.container:hover input ~ .checkmark {
  background-color: #ccc;
}

/* When the checkbox is checked, add a blue background */
.container input:checked ~ .checkmark {
  background-color: #2196F3;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the checkmark when checked */
.container input:checked ~ .checkmark:after {
  display: block;
}

/* Style the checkmark/indicator */
.container .checkmark:after {
  left: 9px;
  top: 5px;
  width: 5px;
  height: 10px;
  border: solid white;
  border-width: 0 3px 3px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}

</style>


<script>

function validateform()
{

var values="";
var count_dim=0;
var count_mode = 0;
var mode="";

var myform = document.getElementById('reg');
var inputTags = myform.getElementsByTagName('input');
var checkboxCount = 0;
for (var i=0, length = inputTags.length; i<length; i++) {
	if(i < 5)
	{
     if (inputTags[i].type == 'checkbox' && document.getElementById(inputTags[i].id).checked==true) {
         count_dim++;
		 values=values.concat(inputTags[i].id).concat(" ");
     }
 	}
 	else
 	{
 		if (inputTags[i].type == 'checkbox' && document.getElementById(inputTags[i].id).checked==true) {
         count_mode++;
		 mode=mode.concat(inputTags[i].id).concat(" ");
 		}
 	}
}

values=values.substr(0,values.length-1);
mode=mode.substr(0,mode.length-1);


if(count_dim==0)
{
alert("Please select atleast one dimension table");	
}
else if(count_mode == 0)
{
	alert("Please select one Mode of Creation for Lattice of Cuboids");	
}
else if(count_mode == 2)
{
	alert("Please select ONLY one Mode of Creation for Lattice of Cuboids");
}
else
{  
	console.log("--->"+values);
	window.location.href = "index3.php?values="+values+"&mode="+mode;
}	
}	

</script>




<body>

	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				
					<span class="login100-form-title">
						<font color="black" >Choose Dimensional Tables</font>
					</span>

				<form id="reg" name="reg"  method="post">	
						
					<?php

						
						$tablenames = $_SESSION['tablenames'];
						//echo $tablenames;
						$previouscomma=-1;



						for ($i=0; $i < strlen($tablenames) ;$i++)
						{
							
							
							if($tablenames{$i} == ',' && (substr($tablenames,$previouscomma+1,$i-$previouscomma-1) != "factproductsales"))
							{
							echo "<label class=\"container\">" . substr($tablenames,$previouscomma+1,$i-$previouscomma-1) .  "<input type=\"checkbox\" id=\"" . substr($tablenames,$previouscomma+1,$i-$previouscomma-1) . "\" ><span class=\"checkmark\"></span></label>";
							$previouscomma=$i;
							echo "<br>";
							}
						}
						if((substr($tablenames,$previouscomma+1) != "factproductsales"))
						echo "<label class=\"container\">" . substr($tablenames,$previouscomma+1) .  "<input type=\"checkbox\" id=\"" . substr($tablenames,$previouscomma+1) . "\" ><span class=\"checkmark\"></span></label>";

						echo "<br>";

						echo "<span class=\"login100-form-title\"> <font color=\"black\" >" . "Choose Mode of Lattice Creation" . "</font> </span>";

						echo "<label class=\"container\">" . "Normal Lattice of Cuboids" .  "<input type=\"checkbox\" id=\"" . "Normal" . "\" ><span class=\"checkmark\"></span></label>";

						echo "<br>";

						echo "<label class=\"container\">" . "Iceberg Optimized Lattice of Cuboids" .  "<input type=\"checkbox\" id=\"" . "Iceberg" . "\" ><span class=\"checkmark\"></span></label>";
					?>	
					</form>

				<div align="center" class="container-login100-form-btn">
						<button class="login100-form-btn" onclick="validateform()">
							Submit
						</button>
				</div>
								

				
			</div>
		</div>
	</div>
	
	

	
<!--===============================================================================================-->	
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/tilt/tilt.jquery.min.js"></script>
	
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>