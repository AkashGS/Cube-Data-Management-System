<?php
$v1="root";
$v2="root";
$v3="datamodelling";

$link = mysqli_connect('localhost',$v1 , $v2, $v3);
if (!$link) {
    echo "database connection failed";
}
else
{

	
	if(isset($_POST['sub']))
	{
		$file = $_FILES['file']['name'];;
		echo $file."<br>";
	
	//$file = "upload.csv";
	//echo $file;
 	$handle = fopen($file, "r");
 	$c = 0;
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
 		$sql = mysqli_query($link,"INSERT INTO FactProductSales (DateKey, StoreID, CustomerID, ProductID, SalesPersonID, Quantity, SalesTotalCost, 			       ProductActualCost, Deviation) VALUES ('$dkey','$sid','$cid','$pid','$spid','$quan','$stc','$pac','$dev')");
		}
		$c++;
 	}


	if($sql)
	{
 			//echo "You database has imported successfully";
	}
	else
	{
 			echo "Sorry! There is some problem.";
	}
	}
	mysqli_close($link);
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Excel Upload</title>
</head>
<body>
<form method="POST" enctype="multipart/form-data">
	<input type="file" name="file">
	<input type="submit" name="sub" value="Import">
</form>
</body>
</html>


