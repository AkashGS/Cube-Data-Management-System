<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>CSV Success</title>
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

<body>

	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				
					<span class="login100-form-title">
						<font color="black" >CSV FILE UPLOAD SUCCESSFUL</font>
					</span>

					<span class="login100-form-title">
						<font color="black" >Fact Table</font>
					</span>



					<?php

						$user_dim_tables = $_SESSION['user_dim_tables'];
						//echo $user_dim_tables;

						//Added By AB Sahoo for Populating FactProductSales Table by CSV FILE
					
						
						$v1="root";
						$v2="root";
						$v3="datamodelling";

						//To store measures selected by User

						$measures = array();
						$measures_count = 0;
						$measures_comma="";

						$link = mysqli_connect('localhost',$v1 , $v2, $v3);
						if (!$link) {
							echo "database connection failed";
						}
						else
						{
							$dbname="datamodelling";
							$dbname_ice = "datamodelling_ice";

							$val = $_POST['sub'];

							if(isset($_POST['sub']))
							{
								$file = $_FILES['file']['name'];;
								//echo $file."<br>";
							
							//$file = "upload.csv";
							//echo $file;
							$handle = fopen($file, "r");
							$c = 0;
							$sql1 = mysqli_query($link,"delete from FactProductSales");
							$db_selected = mysqli_select_db($link,$dbname_ice);
							$sql1 = mysqli_query($link,"delete from FactProductSales");
							while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
							{
								$dkey = $filesop[0];
								$sid = $filesop[1];
								$cid = $filesop[2];
								$pid = $filesop[3];
								$spid = $filesop[4];
								$quan = $filesop[5];
								$stc = $filesop[6];
								$pac = $filesop[7];
								$dev = $filesop[8];
								
								if($c >= 1)
								{
								$db_selected = mysqli_select_db($link,$dbname);

								$sql = "INSERT INTO FactProductSales (DateKey, StoreID, CustomerID, ProductKey, SalesPersonID, Quantity, SalesTotalCost, ProductActualCost, Deviation) VALUES ('$dkey','$sid','$cid','$pid','$spid','$quan','$stc','$pac','$dev')";
								//echo $sql;
								//echo "<br>";
								$result = mysqli_query($link, $sql);
								$db_selected = mysqli_select_db($link,$dbname_ice);
								$result = mysqli_query($link, $sql);
								}
								$c++;
							}


							if($result)
							{
									//echo "You database has imported successfully";
							}
							else
							{
									echo "Sorry! There is some problem.";
							}

							//Added by AB Sahoo for displaying only selected measures


							if(!empty($_POST['measures']))
							{

								echo "Measures Selected: ";
								foreach($_POST['measures'] as $selected)
								{
									echo $selected." ";
									$measures[$measures_count] = $selected;
									$measures_comma .= $selected . ",";
									$measures_count++;
								}
								echo "<br>";
							}

							$measures_len = strlen($measures_comma);
							$measures_comma = substr($measures_comma, 0, $measures_len-1);
							//echo $measures_comma;
							//echo "<br>";
							//echo $measures_count;
							}
							mysqli_close($link);
						}

						//Added By AB Sahoo for Displaying Fact Table With Only Dimensions selected by USER

						$tables = $_SESSION['user_dim_tables'];

						echo "DIMENSIONS SELECTED: " . $tables;
						echo "<br>";

						$dbname="datamodelling";
						$link = mysqli_connect('localhost',"root" ,"root");
						$db_selected = mysqli_select_db($link,$dbname);
						
						$previouscomma=-1;
						$currtablename="";
						$currprimarkkey="";
						$primarkkeys="";
						$from="";
						$j=0;
						$k=0;
						$array=array("temp");

						$array[$k]="TransactionId";
						$k++;
						
						
						for ($i=0; $i < strlen($tables) ;$i++)
						{
							
							if($tables{$i} == ' ')
							{
							
							$currtablename=substr($tables,$previouscomma+1,$i-$previouscomma-1);
							
									//getting primarkkey of $currtablename
									
									$sql="show columns from  " . $currtablename;
									$result = mysqli_query($link, $sql);
									
									while($row = mysqli_fetch_assoc($result)) {		
									
											$primarkkeys = $primarkkeys . $row['Field'] . ',';
											$array[$k]=$row['Field'];
											$k++;
											break;
									}
									$from=$from . $currtablename . ',' ;
							
							$previouscomma=$i;
							}
						}
						
						
						
						
						
						$currtablename=substr($tables,$previouscomma+1);
						//getting primarkkey of $currtablename
									$sql="describe " . $currtablename;
									$result = mysqli_query($link, $sql);
									
									while($row = mysqli_fetch_assoc($result)) {		
									
											$primarkkeys = $primarkkeys . $row['Field'] . ',';
											$array[$k]=$row['Field'];
											$k++;
											break;
									}
									$from=$from . $currtablename . ',' ;
									
									
						//displaying required Fact Table

						$primarkkeys=substr($primarkkeys,0,strlen($primarkkeys)-1);
					//	echo "PKS : " . $primarkkeys . "<br>";	
						$from=substr($from,0,strlen($from)-1);
						//$from=$from . ';';
						
						//$sql="SELECT TransactionId," . $primarkkeys . ",Quantity,SalesTotalCost,ProductActualCost,Deviation " . "FROM

						$sql="SELECT TransactionId," . $primarkkeys . "," . $measures_comma . " FROM FactProductSales";
						
						//echo $sql;
						$result = mysqli_query($link, $sql) or die("Query Failed");

					/*	foreach ($array as $sahoo)
						{
							echo $sahoo . " ";
						}								*/													

						foreach ($measures as $temp) 
						{
							$array[$k]=$temp;
							$k++;
						}
						
						
						//Displaying the Fact Table in HTML

						$i=0;

						/*echo "from=";
						echo $from;
						echo "<br>";
						echo "key=";
						echo $primarkkeys;*/
						
						echo "<table><tr>";
						while($i<$k)
						{
						echo "<th>" . $array[$i] . "</th>";
						$i++;
						}
						echo "</tr>";
						
						
						while($row = mysqli_fetch_assoc($result)) {		
									echo "<tr>";
									
										$i=0;
										while($i<$k)
										{
										echo "<td>";
										echo $row[$array[$i]];
										echo "</td>";	
										$i++;
										}
										
									echo "</tr>";
									}
						echo "</table>";
						
						mysqli_close($link);

						// Code for Fact Table Preparation ends
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

