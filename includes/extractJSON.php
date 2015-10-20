<?php

include('/connect.php');

ini_set('max_execution_time', 0); 

$json = file_get_contents('https://api.guildwars2.com/v2/items');
$data = json_decode($json, true);
 
$sql = "INSERT INTO item_info (id,name,type,level,rarity,description,subtype,url) VALUES ";
$sql2 = "INSERT INTO item_main_stats (id,damage_type,min_power,max_power,defense,weight_class,suffix_item_id,suffix_item_id2) VALUES ";
$sql3 = "INSERT INTO item_attributes (id,attribute,attributeVal) VALUES ";
$sql4 = "INSERT INTO item_infusions (id,infusion_type) VALUES ";

$valuesArr = array();
$valuesArr2 = array();
$valuesArr3 = array();
$valuesArr4 = array();

for($i = 0; $i < count($data); $i++){
	$json = file_get_contents('https://api.guildwars2.com/v2/items/' . $data[$i]);
	$items = json_decode($json, true);
	
	$id = 0;
	$name = "";
	$type = "";
	$level = 0;
	$rarity = "";
	$description = "";
	$subtype = "";
	$url = "";
	$damage_type = "";
	$min_power = "";
	$max_power = "";
	$defense = "";
	$weight_class = "";
	$suffix_item_id = "";
	$suffix_item_id2 = "";	
	
	$id = $items['id']; 
	
	if(isset($items['details'])){
		$details = $items['details'];
		
		if(isset($details['type'])){
			$subtype = mysql_real_escape_string($details['type']); 
		}else{
			$subtype = "";
		}	
		if(isset($details['damage_type'])){
			$damage_type = mysql_real_escape_string($details['damage_type']); 
		}else{
			$damage_type = "";
		}	
		if(isset($details['min_power'])){
			$min_power = mysql_real_escape_string($details['min_power']); 
		}else{
			$min_power = "";
		}	
		if(isset($details['max_power'])){
			$max_power = mysql_real_escape_string($details['max_power']); 
		}else{
			$max_power = "";
		}	
		if(isset($details['defense'])){
			$defense = mysql_real_escape_string($details['defense']); 
		}else{
			$defense = "";
		}	
		if(isset($details['weight_class'])){
			$weight_class = mysql_real_escape_string($details['weight_class']); 
		}else{
			$weight_class = "";
		}	
		if(isset($details['suffix_item_id'])){
			$suffix_item_id = mysql_real_escape_string($details['suffix_item_id']); 
		}else{
			$suffix_item_id = "";
		}	
		if(isset($details['suffix_item_id2'])){
			$suffix_item_id2 = mysql_real_escape_string($details['suffix_item_id2']); 
		}else{
			$suffix_item_id2 = "";
		}
		
		$valuesArr2[] = "('$id','$damage_type','$min_power','$max_power','$defense','$weight_class','$suffix_item_id','$suffix_item_id2')";
		
		if(isset($details['bonuses'])){
			$bonuses = $details['bonuses'];
			for($j = 0; $j < count($bonuses); $j++){
				$attribute = 'bonus';
				$attributeVal = mysql_real_escape_string($bonuses[$j]);	
				$valuesArr3[] = "('$id','$attribute','$attributeVal')";
			}
		}
		
		if(isset($details['infix_upgrade'])){
			$attribute = "";
			$attributeVal = "";	
			if(isset($details['infix_upgrade']['buff']['description'])){
				$buff = $details['infix_upgrade']['buff'];
				$attribute = 'buff';
				$attributeVal = mysql_real_escape_string($buff['description']);
				$valuesArr3[] = "('$id','$attribute','$attributeVal')";
			}	
			
			$attributes = $details['infix_upgrade']['attributes'];
			for($j = 0; $j < count($attributes); $j++){
				$attribute = mysql_real_escape_string($attributes[$j]['attribute']);
				$attributeVal = $attributes[$j]['modifier'];	
				$valuesArr3[] = "('$id','$attribute','$attributeVal')";
			}
		}	
	
		if(isset($details['infusion_slots'])){
			$infusions = $details['infusion_slots'];
			for($j = 0; $j < count($infusions); $j++){
				$infusion_type = "";
				$infusion_type = mysql_real_escape_string($infusions[$j]['flags'][0]);
				$valuesArr4[] = "('$id','$infusion_type')";
			}
		}		
	}
	
	$name = mysql_real_escape_string($items['name']); 
	
	if(isset($items['type'])){
		$type = mysql_real_escape_string($items['type']); 
	}else{
		$type = "";
	}	
	if(isset($items['level'])){
		$level = $items['level']; 
	}else{
		$level = "";
	}	
	if(isset($items['rarity'])){
		$rarity = mysql_real_escape_string($items['rarity']); 
	}else{
		$rarity = "";
	}	
	if(isset($items['description'])){
		$description = mysql_real_escape_string($items['description']); 
	}else{
		$description = "";
	}
	if(isset($items['icon'])){
		$url = mysql_real_escape_string($items['icon']); 
	}else{
		$url = "";
	}		
	
	$valuesArr[] = "('$id','$name','$type','$level','$rarity','$description','$subtype','$url')";
}

$sql .= implode(',', $valuesArr);
$sql2 .= implode(',', $valuesArr2);
$sql3 .= implode(',', $valuesArr3);
$sql4 .= implode(',', $valuesArr4);

mysqli_query($con,$sql) or die(mysqli_error($con));
if(!empty($valuesArr2)){
	mysqli_query($con,$sql2) or die(mysqli_error($con));
}
if(!empty($valuesArr3)){
	mysqli_query($con,$sql3) or die(mysqli_error($con));
}
if(!empty($valuesArr4)){
	mysqli_query($con,$sql4) or die(mysqli_error($con));
}
mysqli_close($con);	

?>