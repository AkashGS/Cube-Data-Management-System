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
table {
    border-collapse: collapse;
    width: 100%;
}

th, td {
    text-align: left;
    padding: 8px;
}

tr:nth-child(even) {background-color: #f2f2f2;}
</style>


<script>

</script>




<body>

	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">

			<!--			Added by AB Sahoo for Submitting CSV File IN POST FORM			-->


					<form method="POST" action="index1.2.php" enctype="multipart/form-data">

					<?php

					$tablenames = $_GET['tablenames'];
					$_SESSION['tablenames'] = $tablenames;
					//echo $tablenames;
					//echo "<br>";
					$number=$_GET['number']; //Number of Dimensions entered by the USER
					$_SESSION['number'] = $number;
					//echo $number;
					//echo "<br>";
					$tables = array();
					$tables = explode(" ",$tablenames);

					for($i=0; $i<$number; $i++)
					{
					echo '<div class="container" align="center">
						<font color="black" >Upload CSV File</font>' . " For " . $tables[$i] . '</div>';
					
					echo '<br><br>';
					}

					echo '<div align="center" class="container-login100-form-btn">
						<label class="custom-file-upload">
							<input type="file" name="file[]" multiple="multiple" />
						</label>';

						echo '<br><br>';



					?>
						<button class="login100-form-btn" type ="submit" name="sub" value="Import">
							SUBMIT
							</button>


					</div>
					
				</form>
				
				
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
