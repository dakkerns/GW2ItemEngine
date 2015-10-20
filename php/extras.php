<?php

function coinImages($itemPrice){
	if(strval($itemPrice) == ""){
			return "";
	}else if($itemPrice < 100){
		return strval($itemPrice) . " <img class = coin-img src = /images/copper.png>";
	}else if($itemPrice < 10000){		
		return strval(floor($itemPrice / 100) % 100) . " <img class = coin-img src = /images/silver.png> " . strval($itemPrice % 100) . "  <img class = coin-img src = /images/copper.png>";
	}
	return strval(floor($itemPrice / 10000) % 10000) . " <img class = coin-img src = /images/gold.png> " . strval(floor($itemPrice / 100) % 100) . " <img class = coin-img src = /images/silver.png> " . strval($itemPrice % 100) . "  <img class = coin-img src = /images/copper.png>";
}	

?>