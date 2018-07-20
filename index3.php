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
					
					<?php
					
						$tables=$_GET["values"];
						$_SESSION['user_dim_tables']=$tables;
						//echo $tables;
						//echo "<br>";


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
											break;
									}
									$from=$from . $currtablename . ',' ;

						$keyArray = explode(",",$primarkkeys);

						//Added By ABSahoo

						$create_table = $tables;
						$table_names = []; //array to store dimension tables as entered by the USER
						$table_names_comma = [];
						$table_count = 0; 
						$temp = "";
						

						for ($i=0; $i < strlen($create_table) ;$i++)
						{	
							if($create_table{$i} == ' ')
							{
								$create_table{$i} = ",";
								$table_names{$table_count} = $temp;
								$temp = $temp . ",";
								$table_names_comma{$table_count} = $temp;
								$table_count++;
								$temp = "";
							}
							else
							{
								$temp = $temp . $create_table{$i};
							}
						}
						$table_names{$table_count} = $temp;
						$temp = $temp . ",";
						$table_names_comma{$table_count} = $temp;
						$table_count++;
						$temp = "";
						//echo $table_count;

						
						//Finding all possible combinations of dimension tables
			
						function pc_array_power_set($array) 
						{
							$results = array(array( ));
							foreach ($array as $element)
        							foreach ($results as $combination)
									array_push($results, array_merge(array($element), $combination));
									
							return $results;
						}
						//Added By Vinayak

						$keyComb = pc_array_power_set($keyArray);
			
						$power_set = pc_array_power_set($table_names);

						$combo_count = array();
						$i = 0;
						foreach ($power_set as $combo)
						{
							$combo_count{$i} = count($combo);
							//echo implode($combo). " ";
							//echo $combo_count[$i];
							//echo "<br>";
							$i++;
						} 

						// Vinayak Finish Here
						
						$create_combo_tables = [];
						$i = 0;
						foreach ($power_set as $combination) 
						{
							$str = implode($combination);
							$create_combo_tables{$i} = $str;
							$i++;
    						//echo $str;
							//echo "<br>";
						}
						
						$select_from_combo_tables = [];
						$i = 0;
						$power_set = pc_array_power_set($table_names_comma);

						foreach ($power_set as $combination) 
						{
							$str = implode($combination);
							$comblen = strlen($str);
							$str = substr($str, 0, $comblen-1);
							$select_from_combo_tables{$i} = $str;
							$i++;
							//echo $str;
							//echo "<br>";
						}
									

						//Get the mode of generating lattice of cuboids(Normal or Iceberg)

						$mode = $_GET["mode"]; 
						//echo $mode;

						if($mode == "Normal")
						{

							echo "<span class=\"login100-form-title\"> <font color=\"black\" >Normal Lattice of CUBOIDS Generated</font> </span>";

							$dbname="datamodelling";
							$link = mysqli_connect('localhost',"root" ,"root");
							$db_selected = mysqli_select_db($link,$dbname);

							$i = 0;

							$time_pre = microtime(true);

							foreach ($create_combo_tables as $create_tables)
							{
								//DONT DROP SINGLE DIMENSION TABLES

								if($combo_count[$i] == 1 )
								{

									$sql="CREATE TABLE " . $create_tables . " SELECT * FROM " . $select_from_combo_tables{$i};	
									$result1 = mysqli_query($link, $sql);
								}

								else if($combo_count[$i] > 1)
								{

									//DROP TABLES WITH MULTIPLE DIMENSIONS IF ALREADY EXISTS, RECREATE THEM

									$sql2 = "DROP TABLE IF EXISTS " . $create_tables;
									$result1 = mysqli_query($link, $sql2);							
									$sql=" CREATE TABLE " . $create_tables . " SELECT * FROM " . $select_from_combo_tables{$i};	
									$result1 = mysqli_query($link, $sql);
								}
								$i++;
							
							}
						
							echo "<br>";
							$time_post = microtime(true);
							$time_diff = $time_post - $time_pre;
							echo "Time TAKEN WITHOUT ICEBERG  ";
							echo $time_diff;
							echo "<br>";


						}
						else if ($mode == "Iceberg")
						{

							echo "<span class=\"login100-form-title\"> <font color=\"black\" >Iceberg Optimized Lattice of CUBOIDS Generated</font> </span>";


							$dbname_ice = "datamodelling_ice";
							$link = mysqli_connect('localhost',"root" ,"root");
							$db_selected = mysqli_select_db($link,$dbname_ice);

							//SQL Query for ICEBERG 

							$i = 0;

							$time_pre = microtime(true);

							foreach ($create_combo_tables as $create_tables)
							{
								//DONT DROP SINGLE DIMENSION TABLES

								if($combo_count[$i] == 1 )
								{

									$sql="CREATE TABLE " . $create_tables . "_ice " . " SELECT * FROM " . $select_from_combo_tables{$i};
									$str="";
									$str1="";
									for($j=0; $j<$combo_count{$i}; $j++)
									{
										$str1 = " WHERE ";
										$str = $str . " " . $keyComb[$i][$j] . " IN " . "(SELECT " . $keyComb[$i][$j] . " FROM factproductsales GROUP BY " . $keyComb[$i][$j] . " HAVING SUM(SalesTotalCost) > 500)";
										if($j<$combo_count{$i}-1)
										{
											$str .= " AND ";
										}
									}
									$sql .= $str1;
									$sql.=$str;
									echo $sql;
									echo "<br>SQL QUERY <br>";
									$result1 = mysqli_query($link, $sql);
								}
								else if($combo_count[$i] > 1)
								{
									//DROP TABLES WITH MULTIPLE DIMENSIONS IF ALREADY EXISTS, RECREATE THEM

									$sql2 = "DROP TABLE IF EXISTS " . $create_tables . "_ice";
									$result1 = mysqli_query($link, $sql2);		

									//Added by AB Sahoo to create Iceberg Lattice of Cubiods RECURSIVELY(using lower dimension Iceberg tables)		

									$sql="CREATE TABLE " . $create_tables . "_ice " . " SELECT * FROM " . $select_from_combo_tables{$i}."_ice";

									$str="";
									$str1="";
									for($j=0; $j<$combo_count{$i}; $j++)
									{
										$str1 = " WHERE ";
										$str = $str . " " . $keyComb[$i][$j] . " IN " . "(SELECT " . $keyComb[$i][$j] . " FROM factproductsales GROUP BY " . $keyComb[$i][$j] . " HAVING SUM(SalesTotalCost) > 500)";
										if($j<$combo_count{$i}-1)
										{
											$str .= " AND ";
										}
									}
									$sql .= $str1;
									$sql.=$str;
									//echo $sql;
									//echo "<br>SQL QUERY <br>";
									$result1 = mysqli_query($link, $sql);
								}
								$i++;
							}


							$time_post = microtime(true);
							$time_diff1 = $time_post - $time_pre;
							echo "Time TAKEN WITH ICEBERG:  ";
							echo $time_diff1;
							echo "<br>";
							echo "<br>";

						}

						
						
						

						
						

					/*	

						$diff = $time_diff - $time_diff1;
						echo "Time SAVED BY ICEBERG OPTIMIZATION: " . $diff;
						echo "<br>";						*/



						
						/*echo "from=";
						echo $from;
						echo "<br>";
						echo "key=";
						echo $primarkkeys;*/
						
					/*	echo "<table><tr>";
						while($i<=$k)
						{
						echo "<th>" . $array[$i] . "</th>";
						$i++;
						}
						echo "</tr>";
						
						
						while($row = mysqli_fetch_assoc($result)) {		
									echo "<tr>";
									
										$i=0;
										while($i<=$k)
										{
										echo "<td>";
										echo $row[$array[$i]];
										echo "</td>";	
										$i++;
										}
										
									echo "</tr>";
									}
						echo "</table>";				*/		
						
						mysqli_close($link);
					
					?>

			<!--			Added by AB Sahoo for Submitting CSV File IN POST FORM			-->

					<form method="POST" action="index4.php" enctype="multipart/form-data">

					
					
					<div align="center" class="container-login100-form-btn">
						<label class="custom-file-upload">
							<input type="file" name="file">
						</label>	<br><br>


						<span class="login100-form-title">
						<font color="black" >Choose Measures</font>
						</span>

						<label class="container">Quantity  <input type="checkbox" name="measures[]" value="Quantity"> <span class="checkmark"></span></label>
						<label class="container">SalesTotalCost  <input type="checkbox" name="measures[]" value="SalesTotalCost"> <span class="checkmark"></span></label>
						<label class="container">ProductActualCost  <input type="checkbox" name="measures[]" value="ProductActualCost"> <span class="checkmark"></span></label>
						<label class="container">Deviation  <input type="checkbox" name="measures[]" value="Deviation"> <span class="checkmark"></span></label>


						<button class="login100-form-btn" type ="submit" name="sub" value="Import">
							Upload AND SUBMIT
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
