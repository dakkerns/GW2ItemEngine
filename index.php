<!DOCTYPE html>
<html>
<head>
<title> GW2 Item Engine </title>
<meta name="viewport" content="width=device-width, initial-scale=.86">
<link rel="icon" href="/images/favicon.ico">
<link rel="stylesheet" type ="text/css" href="/css/market.css">
<link rel="stylesheet" type ="text/css" href="/css/jquery.fancybox.css" media="screen">
</head>
<body>
<div id = "logo-container">
	<h1 class = "title">GW2 Item Engine</h1>
	<h2 class = "title">Real time market search</h3>
</div>
<div id="bloodhound">
	<div id = "input-container">
		<input id="typeahead" type="text" placeholder="Item Search" disabled = "disabled" autofocus>	
		<img id = "input-img" src = "/images/searchicon.png">
	</div>
	<div id = "result-div">
		<img id = "load_img" src = "/images/ajax-loader.gif">
		<table id = "result-table">		
			<tbody>
				<tr id = "table-header">
					<td style = "width: 10%;"><img class = "item-img" style = "width:38px;" src = "/images/cameraicon.png"></td>
					<td style = "width: 40%;">Name</td>
					<td style = "width: 25%;">Highest Offer</td>
					<td style = "width: 25%;">Lowest Seller</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<div id = "copyright_container">
	<h1 class = "title">Created by David Kerns</h1>
</div>
<script src="/scripts/jquery-2.1.4.js"></script>
<script src="/scripts/jquery.cookie.js"></script>
<script src="/scripts/typeahead.bundle.js"></script>
<script src="/scripts/market.js"></script>
<script src="/scripts/jquery.fancybox.pack.js"></script>
</body>
</html>