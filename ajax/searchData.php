<?php

if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']))
{
   include('../404error.php');
   die();
}

header("Content-type: text/javascript");

include('../php/connect.php');

$items = array();
	
$sql = "SELECT i.id, i.name,i.url, c.min_sell, c.max_offer FROM item_info i LEFT JOIN current_market c ON i.id = c.id";
$result = mysqli_query($con, $sql) or die(mysqli_error($con));
		
while($row = $result->fetch_assoc()){
	if($row['max_offer'] == null){
		$row['max_offer'] = "";
	}
	if($row['min_sell'] == null){
		$row['min_sell'] = "";
	}
	$items[] = $row;
}
	
print json_encode($items);

mysqli_close($con);
	
?>