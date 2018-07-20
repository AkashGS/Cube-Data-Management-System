<?php


/*
$v1="root";
$v2="root";
$tablenames="";


session_start();


$link = mysqli_connect('localhost',$v1 , $v2);
if (!$link) {
    echo "database connection failed";
}
else
{
//connection was successful

$dbname="datamodelling";
$sql="show tables from $dbname";
$result = mysqli_query($link, $sql);

$i=0;

$exclude="factproductsales";

while($row = mysqli_fetch_assoc($result)) {
	
	if($tablenames != $exclude)
	{
	if($i==0)
	$tablenames = $tablenames  . $row['Tables_in_datamodelling'];
	else
    $tablenames = $tablenames . ',' . $row['Tables_in_datamodelling'];

	$i++;
	}

}
	
}


//echo $tablenames;

$tablenames = "dimcustomer,dimdate,dimproduct,dimsalesperson,dimstores";
//echo $tablenames;

mysqli_close($link);

$_SESSION['tablenames']=$tablenames;

header('index6.php');

*/
?>





<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login V1</title>
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




<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt">
					<img src="images/img-01.jpg" alt="IMG"> 
				</div>


						Number of Dimensions:<input type="text" style="border-style: groove; width:90px; height:30px;" id="member" name="member" value=""><br />
						<button type="button" class="btn btn-success" style="width:100px; height:35px;" id="btn" onclick="addinputFields()">Enter</button>
    					<div id="container"/>
 				 <br><br>
 				

 				<div style="position: fixed; top: 50%; left: 50%;">

	 <button type="button" class="btn btn-success" style="width:100px; height:35px; margin-top: 100px" id="btn1" href="#" onclick="submit_func()">Submit</button>

	</div>
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
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
<!--===============================================================================================-->

<script>
	
	
 function addinputFields(){
    var number = document.getElementById("member").value;
    var tablenames = "";

    for (i=0;i<number;i++){

        var input = document.createElement("input");
        input.id = i;
        input.type = "text";
        input.style = "border-style: groove";
        input.placeholder = "Enter Dimension " + (i+1);
        container.appendChild(input);
        container.appendChild(document.createElement("br"));
       
    }
}

function submit_func()
{
	//alert("Inside submit");
	var number = document.getElementById("member").value;
    var tablenames = "";
	for(j=0; j<number; j++)
    {
    	 var temp_table = document.getElementById(j).value;
        tablenames = tablenames.concat(temp_table);
        tablenames = tablenames.concat(" ");
        //alert(temp_table);
    }
    //alert(tablenames);

    window.location.href = "index1.1.php?tablenames="+tablenames+"&number="+number;
}
  </script>
	<script src="js/main.js"></script>



</body>
</html>