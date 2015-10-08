<?php

//load database 
require_once "db.php";

//get daily stats per defined URL 'date' parameter
$tablename=$_GET["date"];

$con=mysqli_connect($dbserver, $dbuser, $dbpassword, $dbname);
// Check connection
if (mysqli_connect_errno())
	{
  		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
  
$query = "SELECT * FROM `".$tablename."`";

$result = mysqli_query($con, $query);

$i = 0;

while($row = @mysqli_fetch_array($result))
{
	//id	name	token	address	totalpts
  	
  	$data[$i]['id'] = $row['id'];
	$data[$i]['name'] = $row['name'];
	$data[$i]['token'] = $row['token'];
	$data[$i]['address'] = $row['address'];
	$data[$i]['newcredit'] = $row['totalpts'];
	
	$i++;
  	
}


mysqli_close($con);

//show daily stats as JSON
echo json_encode($data);


?>
