<?php

include('/connect.php');

$sql = "TRUNCATE TABLE current_market";
mysqli_query($con,$sql) or die(mysqli_error($con));

$json = file_get_contents('http://www.gw2spidy.com/api/v0.9/json/all-items/all');
$items = json_decode($json, true);
$items = $items['results'];

$sql = "INSERT INTO current_market (id,name,max_offer,min_sell,demand,supply) VALUES ";
$valuesArr = array();

foreach($items as $row){

	$max = mysql_real_escape_string($row['max_offer_unit_price']);
	$min = mysql_real_escape_string($row['min_sale_unit_price']);
	$demand = mysql_real_escape_string($row['offer_availability']);
	$supply = mysql_real_escape_string($row['sale_availability']);

	if((int)$max == 0){
		$max = "";
	}
	if((int)$min == 0){
		$min = "";
	}
	
	if((int)$demand == 0){
		$demand = "";
	}
	if((int)$supply == 0){
		$supply = "";
	}

	$id = $row['data_id'];
	$name = mysql_real_escape_string($row['name']);
			
	$valuesArr[] = "('$id','$name','$max','$min','$demand','$supply')";

}

$sql .= implode(',', $valuesArr);
mysqli_query($con,$sql) or die(mysqli_error($con));

$sql = "DELETE FROM `highcharts` where dateadded < DATE_SUB(curdate(), INTERVAL 28 DAY)";
mysqli_query($con,$sql) or die(mysqli_error($con));

$sql = "INSERT INTO highcharts (id,max_offer,min_sell,demand,supply,dateadded) VALUES ";
$valuesArr = array();

foreach($items as $row){
	
	$max = mysql_real_escape_string($row['max_offer_unit_price']);
	$min = mysql_real_escape_string($row['min_sale_unit_price']);
	$demand = mysql_real_escape_string($row['offer_availability']);
	$supply = mysql_real_escape_string($row['sale_availability']);
	
	if((int)$max == 0 && (int)$min == 0 && (int)$demand == 0 && (int)$supply == 0){
		continue;
	}	

	if((int)$max == 0){
		$max = "";
	}
	if((int)$min == 0){
		$min = "";
	}
	
	if((int)$demand == 0){
		$demand = "";
	}
	if((int)$supply == 0){
		$supply = "";
	}

	$id = $row['data_id'];
			
	$valuesArr[] = "('$id','$max','$min','$demand','$supply',NOW())";

}

$sql .= implode(',', $valuesArr);
mysqli_query($con,$sql) or die(mysqli_error($con));

mysqli_close($con);		

?>