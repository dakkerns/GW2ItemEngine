<?php

if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']))
{
   include('../404error.php');
   die();
}

header("Content-type: text/javascript");

include('../includes/connect.php');

$sql = "SELECT max_offer, min_sell, demand, supply, DATE_FORMAT(dateadded, '%b %d %Y %h:%i %p') AS date FROM highcharts WHERE id = '$_GET[id];'";
$result = mysqli_query($con, $sql) or die(mysqli_error($con));

$offers = array();
$sales = array();
$demand = array();
$supply = array();
$dates = array();
$sales['name'] = 'Lowest Price';
$offers['name'] = 'Highest Offer';
$demand['name'] = 'Number Demanded';
$supply['name'] = 'Number Available';
$dates['name'] = 'Date';

while($row = $result->fetch_assoc()){
	
	$sales['data'][] = (int)$row['min_sell'];
	$offers['data'][] = (int)$row['max_offer'];	
	$demand['data'][] = (int)$row['demand'];
	$supply['data'][] = (int)$row['supply'];
	$dates['data'][] = $row['date'];
}

$items = array();
array_push($items,$dates);
array_push($items,$offers);
array_push($items,$sales);
array_push($items,$demand);
array_push($items,$supply);

print json_encode($items); 

mysqli_close($con);

?>