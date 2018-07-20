
<?php
ini_set('max_execution_time', 300); 
session_start();
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<title>Fact Table</title>
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

			<span class="login100-form-title">
						<font color="black" >Upload CSV File for Fact Table</font>
					</span>

					<form method="POST" action="index6.1.php" enctype="multipart/form-data">

					
					
					<div align="center" class="container-login100-form-btn">
						<label class="custom-file-upload">
							<input type="file" name="file">
						</label>	<br><br>


						<button class="login100-form-btn" type ="submit" name="sub" value="Import">
							Upload AND SUBMIT
							</button>


					</div>
					
				</form>


			<?php


			
			$tablenames = $_SESSION['tablenames'];
			$number = $_SESSION['number'];

			$tables = array();
			$tables = explode(" ",$tablenames);

			$v1="root";
			$v2="";
			$v3="datamodelling";

			$link = mysqli_connect('localhost',$v1 , $v2, $v3);

			if (!$link) 
			{
				echo "database connection failed";
			}
			else
			{
				$dbname="datamodelling";
				$dbname_ice = "datamodelling_ice";

				$dim_schema = array("dimcustomer"=>array("CustomerID", "CustomerAltID", "CustomerName", "Gender", "Aadhar_number"), "dimstores"=>array("StoreID", "StoreAltID", "StoreName", "StoreLocation", "City"), "dimproduct"=>array("ProductKey", "ProductAltKey", "ProductName", "ProductActualCost", "ProductSalesCost"), "dimdate"=>array("DateKey", "DayOfMonth", "DayName", "MonthName", "Year"), "dimsalesperson"=>array("SalesPersonID", "SalesPersonAltID", "storenumber", "City", "Country"));

				foreach ($_FILES['file']['name'] as $filename) 
            	{
                	echo $filename;
                	echo "<br>";

                	if(isset($_POST['sub']))
							{
								$file = $filename;
								//echo $file."<br>";
							
							//$file = "upload.csv";
							//echo $file;
							$handle = fopen($file, "r");

							$dim_file_table = substr($filename, 0, strlen($filename)-4);

							//echo $dim_file_table;
							//echo "<br>";

							$db_selected = mysqli_select_db($link,$dbname);

							$sql1 = "DROP TABLE IF EXISTS ". $dim_file_table;
							$result = mysqli_query($link, $sql1);

							//echo $sql1;
							//echo "<br>";

							$sql2 = "CREATE TABLE ". $dim_file_table . " (" . $dim_schema[$dim_file_table][0] . " int, " . $dim_schema[$dim_file_table][1] . " varchar(255), " . $dim_schema[$dim_file_table][2] . " varchar(255), " . $dim_schema[$dim_file_table][3] . " varchar(255), " . $dim_schema[$dim_file_table][4] . " varchar(255)" . " )";
							$result = mysqli_query($link, $sql2) or die("Query Failed at 136");

							//echo $sql2;
							//echo "<br>";


							
							$db_selected = mysqli_select_db($link,$dbname_ice);

							$sql1 = "DROP TABLE IF EXISTS ". $dim_file_table;
							$result = mysqli_query($link, $sql1);

							$sql2 = "CREATE TABLE ". $dim_file_table . " (" . $dim_schema[$dim_file_table][0] . " int, " . $dim_schema[$dim_file_table][1] . " varchar(255), " . $dim_schema[$dim_file_table][2] . " varchar(255), " . $dim_schema[$dim_file_table][3] . " varchar(255), " . $dim_schema[$dim_file_table][4] . " varchar(255)" . " )";

							//echo $sql2;
							//echo "<br>";

							$result = mysqli_query($link, $sql2) or die("Query Failed For Iceberg Dim Tables");


							$c = 0;
							while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
							{

								//Each Dimension CSV File should contain 5 columns

								$key = $filesop[0];
								$first = $filesop[1];
								$second = $filesop[2];
								$third = $filesop[3];
								$fourth = $filesop[4];
								
								if($c >= 1)
								{
								$db_selected = mysqli_select_db($link,$dbname);



								$sql3 = "INSERT INTO " . $dim_file_table . "(" . $dim_schema[$dim_file_table][0] . "," . $dim_schema[$dim_file_table][1] . "," . $dim_schema[$dim_file_table][2] . "," . $dim_schema[$dim_file_table][3] . "," . $dim_schema[$dim_file_table][4] . ")" . " VALUES ('$key','$first','$second','$third','$fourth')";


								//echo $sql3;
								//echo "<br>";
								$result = mysqli_query($link, $sql3) or die("Query Failed ");
;
								$db_selected = mysqli_select_db($link,$dbname_ice);

								$sql3 = "INSERT INTO " . $dim_file_table . "(" . $dim_schema[$dim_file_table][0] . "," . $dim_schema[$dim_file_table][1] . "," . $dim_schema[$dim_file_table][2] . "," . $dim_schema[$dim_file_table][3] . "," . $dim_schema[$dim_file_table][4] . ")" . " VALUES ('$key','$first','$second','$third','$fourth')";


								//echo $sql3;
								//echo "<br>";

								$result = mysqli_query($link, $sql3) or die("Query Failed For Iceberg Dim Tables");
;
								}
								$c++;
							}


							
						}
            	}
			}

		?>
				
				
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
