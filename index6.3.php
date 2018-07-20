
<?php
ini_set('max_execution_time', 300); 
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
					
						$tables=$_SESSION['tablenames'];
						$_SESSION['user_dim_tables']=$tables;
						//echo $tables;
						//echo "<br>";

						$measures = $_SESSION['measures'];
						//echo $measures;
						//echo "<br>";

						$agg_func = $_SESSION['agg_func'];
						//echo $agg_func;
						//echo "<br>"

						$iceberg_limit = $_GET['iceberg_limit'];
						//echo $iceberg_limit;
						//echo "<br>";

						$selected_measures_array = explode(" ",$measures);
						$agg_func_array = explode(" ", $agg_func);

						$selected_measures_count = $_SESSION['selected_measures_count'];



						$dbname="datamodelling";
						$link = mysqli_connect('localhost',"root" ,"");
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
							//echo $currtablename;
							//echo "<br>";
							
									//getting primarkkey of $currtablename
									
									$sql="show columns from  " . $currtablename;

									//echo $sql;
									//echo "<br>";
									
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

							//echo $primarkkeys;
							//echo "<br>";
						}
						
						
						
						
						
				/*		$currtablename=substr($tables,$previouscomma+1);
						//getting primarkkey of $currtablename
						echo $currtablename;
						echo "<br>";
									$sql="describe " . $currtablename;
									echo $sql;
									echo "<br>";
									$result = mysqli_query($link, $sql);
									
									while($row = mysqli_fetch_assoc($result)) {		
									
											$primarkkeys = $primarkkeys . $row['Field'] . ',';
											$array[$k]=$row['Field'];
											break;
									}
									$from=$from . $currtablename . ',' ;  */

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
								//echo $temp;
								//echo "<br>";
								$temp = $temp . ",";
								$table_names_comma{$table_count} = $temp;
								//echo $temp;
								//echo "<br>";
								$table_count++;
								$temp = "";
							}
							else
							{
								$temp = $temp . $create_table{$i};
							}
						}
						
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

						$total_combos = $i;

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

						$keyComb = pc_array_power_set($keyArray);

						$count = 0;


						//Get the mode of generating lattice of cuboids(Normal or Iceberg)

						$mode = $_GET["mode"]; 
						//echo $mode;

						$multiple_measures_agg_func="";

						for($k=0; $k<$selected_measures_count; $k++)
						{
								$multiple_measures_agg_func .= $agg_func_array[$k] . "(" . $selected_measures_array[$k] . "),"; 
						}
						$multiple_measures_agg_func = substr($multiple_measures_agg_func, 0, strlen($multiple_measures_agg_func)-1);
						//echo $multiple_measures_agg_func;
						//echo "<br>";

						if($mode == "Normal")
						{

							echo "<span class=\"login100-form-title\"> <font color=\"black\" >Normal Lattice of CUBOIDS Generated</font> </span>";

							$dbname="datamodelling";
							$link = mysqli_connect('localhost',"root" ,"");
							$db_selected = mysqli_select_db($link,$dbname);

							$i = 0;

							$time_pre = microtime(true);

							sleep(7);

							for($i=0; $i<$total_combos; $i++)
							{
		
								//DONT DROP SINGLE DIMENSION TABLES

								if($combo_count[$i] == 1 )
								{
									$pk_keys_combo = "";
									
									$pk_keys_combo = $keyComb[$i][0];
									//echo $pk_keys_combo;	
									//echo "<br>";

									$sql="CREATE TABLE " . $create_combo_tables[$i] . " SELECT " . $pk_keys_combo . "," . $multiple_measures_agg_func . " FROM FactTable" . " GROUP BY " . $pk_keys_combo;	
									//echo $sql;
									//echo "<br>";
									$result1 = mysqli_query($link, $sql);
								}

								else if($combo_count[$i] > 1)
								{

									//DROP TABLES WITH MULTIPLE DIMENSIONS IF ALREADY EXISTS, RECREATE THEM

									$pk_keys_combo = "";
									for($j=0; $j<$combo_count{$i}-1; $j++)
									{
										$pk_keys_combo .= $keyComb[$i][$j];
										$pk_keys_combo .= ",";
									}
									$pk_keys_combo .= $keyComb[$i][$j];
									//echo $pk_keys_combo;
									//echo "<br>";

									$sql2 = "DROP TABLE IF EXISTS " . $create_combo_tables[$i];
									$result1 = mysqli_query($link, $sql2);							
									
									$sql="CREATE TABLE " . $create_combo_tables[$i] . " SELECT " . $pk_keys_combo . "," . $multiple_measures_agg_func . " FROM  FactTable". " GROUP BY " . $pk_keys_combo;	
									//echo $sql;
									//echo "<br>";	
									$result1 = mysqli_query($link, $sql);
								}
							}
						
							echo "<br>";
							$time_post = microtime(true);
							$time_diff = $time_post - $time_pre;
							echo "Time TAKEN IN NORMAL MODE  ";
							echo $time_diff;
							echo "<br>";


						}
						else if ($mode == "Iceberg")
						{

							//Iceberg optimization should be applied on ONLY 1 MEASURE

							$iceberg_measure = $_GET['iceberg_measure'];
							
							$index = array_search($iceberg_measure, $selected_measures_array);
							$iceberg_agg_func = $agg_func_array[$index];
							//echo "Iceberg agg func :" . $iceberg_agg_func;



							echo "<span class=\"login100-form-title\"> <font color=\"black\" >Iceberg Optimized Lattice of CUBOIDS Generated</font> </span>";


							$dbname_ice = "datamodelling_ice";
							$link = mysqli_connect('localhost',"root" ,"");
							$db_selected = mysqli_select_db($link,$dbname_ice);

							//SQL Query for ICEBERG 

							$i = 0;

							$time_pre = microtime(true);

							foreach ($create_combo_tables as $create_tables)
							{

								//Create 1D Iceberg Tables from 1D Normal Dim Tables

								if($combo_count[$i] == 1 )
								{
									$pk_keys_combo = "";
									
									$pk_keys_combo .= $keyComb[$i][0];
									//echo $pk_keys_combo;	

									$sql2 = "DROP TABLE IF EXISTS " . $create_tables . "_ice";
									//echo $sql2;
									//echo "<br>";
									$result1 = mysqli_query($link, $sql2);

									//echo $combo_count[$i];
									//echo "<br>";

									$sql="CREATE TABLE " . $create_tables . "_ice" . " SELECT " . $pk_keys_combo . ", " . $multiple_measures_agg_func . " FROM FactTable" . " GROUP BY " . $pk_keys_combo . " HAVING " . $iceberg_agg_func . "(" . $iceberg_measure . ")" . ">" . $iceberg_limit;	
									//echo $sql;
									//echo "<br>";
									$result1 = mysqli_query($link, $sql);
								}

								else if($combo_count[$i] > 1)
								{
									//DROP TABLES WITH MULTIPLE DIMENSIONS IF ALREADY EXISTS, RECREATE THEM

									$pk_keys_combo = "";
									for($j=0; $j<$combo_count{$i}-1; $j++)
									{
										$pk_keys_combo .= $keyComb[$i][$j];
										$pk_keys_combo .= ",";
									}
									$pk_keys_combo .= $keyComb[$i][$j];
									//echo $pk_keys_combo;
									$split_combo = explode(",",$select_from_combo_tables[$i]);

									$str1 = "";
									$str2 = "";

									for($z=0; $z<($combo_count[$i]-1); $z++)
									{
										$str1 .= $split_combo[$z];
									}
									$str1 .= "_ice";

									$str2 .= $split_combo[$z];
									$str2 .= "_ice";

									//echo "splitted tables: " . $str1 . " " . $str2 . "<br>";

									$sql2 = "DROP TABLE IF EXISTS " . $create_tables . "_ice";
									$result1 = mysqli_query($link, $sql2);							
									
									$sql="CREATE TABLE " . $create_combo_tables[$i] . "_ice" . " SELECT " . $pk_keys_combo . "," . $multiple_measures_agg_func . " FROM  FactTable". " WHERE (".$pk_keys_combo .") IN (SELECT ". $pk_keys_combo . " FROM ". $str1 . ", " . $str2 . ") GROUP BY " . $pk_keys_combo . " HAVING " . $iceberg_agg_func . "(" . $iceberg_measure . ")" . ">" . $iceberg_limit;
									echo $sql;
									echo "<br>";
	
									$result1 = mysqli_query($link, $sql);
								}
								$i++;
							}


							$time_post = microtime(true);
							$time_diff1 = $time_post - $time_pre;
							echo "Time TAKEN IN ICEBERG MODE:  ";
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
