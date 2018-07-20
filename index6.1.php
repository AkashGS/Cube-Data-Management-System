<?php
ini_set('max_execution_time', 500); 
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
						<font color="black" >CHOOSE MEASURES</font>
					</span>

					<?php

						//Added By AB Sahoo for Populating FactTable by CSV FILE
					
						
						$v1="root";
						$v2="";
						$v3="datamodelling";

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
								$file = $_FILES['file']['name'];
								echo $file."<br>";
								echo "<br>";
							
							//$file = "upload.csv";
							//echo $file;

							//$handle = fopen($file, "r");
							$handle = fopen($_FILES["file"]["tmp_name"], 'r');
							$c = 0;
							$sql1 = mysqli_query($link,"delete from FactTable");
							$db_selected = mysqli_select_db($link,$dbname_ice);
							$sql1 = mysqli_query($link,"delete from FactTable");
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

								$sql2 = "INSERT INTO FactTable (DateKey, StoreID, CustomerID, ProductKey, SalesPersonID, Quantity, SalesTotalCost, ProductActualCost, Deviation) VALUES ('$dkey','$sid','$cid','$pid','$spid','$quan','$stc','$pac','$dev')";
								//echo $sql;
								//echo "<br>";
								$result = mysqli_query($link, $sql2);
								$db_selected = mysqli_select_db($link,$dbname_ice);
								$result = mysqli_query($link, $sql2);
								}
								$c++;
							}


							
						}
						//Added to Find Measures in CSV File

						$facttable="FactTable";
						$sql="desc $facttable";
						$result = mysqli_query($link, $sql);

						$i=0;

						//$exclude="FactTable";
						$columnnames = "";

						while($row = mysqli_fetch_assoc($result)) {
	
						
				
							if($i==0)
							$columnnames = $columnnames  . $row['Field'];
							else
    						$columnnames = $columnnames . ',' . $row['Field'];

							$i++;
				

						}

						//echo $columnnames;
						echo "<br>";

						$column_array = array();
						$column_array = explode(",",$columnnames);

						$column_count = count($column_array);
						$measure_count = $column_count -6;

						$_SESSION['measure_count'] = $measure_count;

						$measures = "";
						
						for($i=6; $i<$column_count; $i++)
						{
							$measures .= $column_array[$i];
							if($i != ($column_count-1))
							$measures .= ",";
						}

						//echo $measures;

						$measureArray = explode(",",$measures);

						mysqli_close($link);
					}
						//Displaying Measures as Present in CSV File

						$previouscomma=-1;



						//Added by AB for More than One Measures
?>

		<form id="reg" name="reg"  method="post">

<table>
  <tr>
    <td valign="top"></td>
    <td>
      <table>
        <tr>
        	<?php
        	for($i=0; $i<$measure_count; $i++)
        	{

          echo '<td width="30">
            <input type="checkbox" id="' . $measureArray[$i] . '" name="' . $measureArray[$i] . '"/>
          </td>
          	<td width="200">' . $measureArray[$i] . '</td>';

        	}
        ?>
        </tr>
      </table>
      </td>

  </tr>
</table>
		


    </form>
    				<div id="select_agg"></div>

					<div align="center" class="container-login100-form-btn">
						<button class="login100-form-btn" onclick="showhidetables()">
							Enter Measures
						</button>
					</div>


					<div align="center" class="container-login100-form-btn">
						<button class="login100-form-btn" onclick="myfunction()">
							Submit
						</button>
				</div>
						
			</div>
		</div>
	</div>
	
<script type="text/javascript">

function myfunction() {
		//alert("my function");

var values="";
var selected_measures_count=0;
var agg_func="";

//alert("Inside JS");

var myform = document.getElementById('reg');
var inputTags = myform.getElementsByTagName('input');
var my_select_form = document.getElementById("select_agg");
var selectTags = my_select_form.getElementsByTagName('select');
var select_flag = 0;
//var measure_count = "<?php echo $measure_count; ?>";
//alert(measure_count);	

for (var i=0, length = inputTags.length; i<length; i++) 
{
	
     if (inputTags[i].type == 'checkbox' && document.getElementById(inputTags[i].id).checked==true) 
     {
     	//alert("Inside loop");
         selected_measures_count++;
		 values=values.concat(inputTags[i].id).concat(" ");
		 
		 for(var j=0; j<selectTags.length; j++)
		 {
		 	if(j == select_flag)
		 	{
		 		agg_func = agg_func.concat(selectTags[j].options[selectTags[j].selectedIndex].value).concat(" ");
		 		//alert(selectTags[j].options[selectTags[j].selectedIndex].value);
		 		select_flag++;
		 		break;
		 	}
		 }
     }
 }

values=values.substr(0,values.length-1);
agg_func=agg_func.substr(0,agg_func.length-1);	


if(selected_measures_count==0)
{
alert("Please select ATLEAST ONE Measure");	
}
else
{  
	console.log("--->"+agg_func+"  "+values);
	window.location.href = "index6.2.php?values="+values+"&agg_func="+agg_func+"&selected_measures_count="+selected_measures_count;
}



	}


	function showhidetables()
	{

		var measure_count = '<?php echo $measure_count; ?>';
		//alert(measure_count);

		var myform = document.getElementById('reg');
		var inputTags = myform.getElementsByTagName('input');

		var measureArray = <?php echo json_encode($measureArray); ?>;

		var agg_func_array = ["AVG", "COUNT", "SUM", "MIN", "MAX"];

		for (var i=0; i<inputTags.length; i++) 
		{
			
     		if (inputTags[i].type == "checkbox" && document.getElementById(measureArray[i]).checked==true) 
     		{
     			//alert(measureArray[i]);

				var str = "&emsp;&emsp;&emsp;&emsp;<select><option value=\"AVG\">AVG</option><option value=\"COUNT\">COUNT</option><option value=\"SUM\">SUM</option><option value=\"MIN\">MIN</option><option value=\"MAX\">MAX</option></select>";

     			document.getElementById("select_agg").innerHTML += str;
 			}
 		}
	}


	</script>

	
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

