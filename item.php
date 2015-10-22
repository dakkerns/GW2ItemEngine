<?php 
ob_start();
include_once $_SERVER['DOCUMENT_ROOT']."/includes/itemData.php"; 
ob_end_flush();
?>
<!DOCTYPE html>
<html>
<head>
<title> GW2 Item Engine </title>
<link rel="stylesheet" type ="text/css" href="/css/item.css">
</head>
<body>
<div id = "info_wrapper">
	<div id = "basicinfo_container">				
		<div class = "image_cell">
			<img class = "item_img" src = <?php echo $item->imageUrl ?>>
		</div>			
		<div id = "tooltip_info">
			<?php echo $item->tooltip->details; ?>
		</div>			
		<div id = "sales_info">
			<table>
				<tr><td><b>Highest Offer:</b></td><td><?php echo $item->max; ?></td></tr>
				<tr><td><b>Lowest Seller:</b></td><td><?php echo $item->min; ?></td></tr>
				<tr><td><b>Supply:</b></td><td><?php echo $item->marketData['supply']; ?></td></tr>
				<tr><td><b>Demand:</b></td><td><?php echo $item->marketData['demand']; ?></td></tr>
			</table>  				
		</div>
	</div>
	
	<hr width = "87%">
	<div id = "highcharts_container">

	</div>	
</div>
<script src="/scripts/jquery-2.1.4.js"></script>
<script src="/scripts/item.js"></script>
<script src="/scripts/highcharts.js"></script>
</body>
</html>
