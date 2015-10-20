<?php

class toolTip {
	
	private $data;
	private $attrs;	
	private $infusions;
	private $colors;
	private $connection;
	public $details;
	
	public function __construct($itemData,$itemAttrs,$itemInfusions,$con){
		$this->data = $itemData;
		$this->attrs = $itemAttrs;
		$this->infusions = $itemInfusions;
		$this->connection = $con;
		$this->colors = array("Junk"=>"#baaaaa","Basic"=>"white","Fine"=>"#4f9dfe","Masterwork"=>"#81c583","Rare"=>"#fda500","Exotic"=>"#cc6600","Ascended"=>"#ff3349","Legendary"=>"#DD2223");
		
		switch($this->data['type']){
			case "CraftingMaterial":
			case "MiniPet":
			case "Trophy":
				$this->details = $this->trophyTT();
				break;
			case "Container":
			case "Consumable":
				$this->details = $this->consumableTT();
				break;	
			case "UpgradeComponent":
				$this->details = $this->upgradeCompTT();
				break;
			case "Trinket":
			case "Weapon":
			case "Back":
			case "Armor":
				$this->details = $this->equipmentTT();
				break;				
		}
	}
	
	private function consumableTT(){
		$tt = "<dl class = tt_dl><dt class = tt_title style = 'color:" . $this->colors[$this->data['rarity']] . ";'>" . $this->data['name'] . "</dt>" .
		"<dd class = tt_description style = 'padding-bottom: 50px;'>" . $this->data['description'] . "</dd>" . 
		"<dd class = tt_subtype style = 'color:#71c2e7'>" . $this->data['subtype'] . "</dd>" .
		"<dd class = tt_type style = 'color:#71c2e7'>" . $this->data['type'] . "</dd>" .
		"</dl>";
		return $tt;
	}
	
	private function trophyTT(){
		$tt = "<dl class = tt_dl><dt class = tt_title style = 'color:" . $this->colors[$this->data['rarity']] . ";'>" . $this->data['name'] . "</dt>" .
		"<dd class = tt_description style = 'padding-bottom: 50px;'>" . $this->data['description'] . "</dd>" . 
		"<dd class = tt_type style = 'color:#71c2e7'>" . $this->data['type'] . "</dd>" .
		"</dl>";
		return $tt;
	}
	
	private function upgradeCompTT(){
		$tt = "<dl class = tt_dl><dt class = tt_title style = 'color:" . $this->colors[$this->data['rarity']] . ";'>" . $this->data['name'] . "</dt>";	
		$tt .= $this->buffTTInfo($this->attrs);			
		$tt .= "<dd class = tt_description style = 'padding-top: 15px;'>" . $this->data['description'] . "</dd>" . 
		"<dd class = tt_subtype style = 'color:#71c2e7'>" . $this->data['subtype'] . "</dd>" .
		"<dd class = tt_type style = 'color:#71c2e7'>" . $this->data['type'] . "</dd>" .
		"</dl>";
		return $tt;
	}
	
	private function equipmentTT(){
		$itemAttrs = "";
		$buffFlag = -1;
		$tt = "<dl class = tt_dl><dt class = tt_title style = 'color:" . $this->colors[$this->data['rarity']] . ";'>" . $this->data['name'] . "</dt>";
		for($i=0; $i < count($this->attrs); $i++){
			if(strcmp($this->attrs[$i]['attribute'],'buff') == 0){
				$buffFlag = $i;
				continue;
			}
			$itemAttrs .= "<dd class = tt_attrs>+" . $this->attrs[$i]['attributeVal'] . " " . $this->attrs[$i]['attribute'] . "</dd>";
		}
		if($this->data['defense'] != 0){
			$tt .= "<dd class = tt_identifier>Defense: <span class = tt_attrs>" . $this->data['defense'] . "</span></dd>";
		}
		if($this->data['min_power'] != ""){
			$tt .= "<dd class = tt_identifier>Weapon Strength: <span class = tt_attrs>" . $this->data['min_power'] . " - " . $this->data['max_power'] . "</span></dd>";
		}
		$tt .= $itemAttrs;		
		if($buffFlag != -1){
			$tt .= $this->buffTTInfo($this->attrs);
		}
		if($this->data['suffix_item_id'] != ""){
			$suffix = $this->data['suffix_item_id'];
			$tt .= $this->suffixTTInfo($suffix);
		}
		for($i=0;$i < count($this->infusions); $i++){
			$tt .= "<dd class = tt_infusions>Unused " . $this->infusions[$i]['infusion_type'] . " Infusion Slot</dd>";
		}
		$tt .= "<dd class = tt_identifier style = 'padding-top: 10px; color:" . $this->colors[$this->data['rarity']] . ";'>" . $this->data['rarity'] . "</dd>" .
		"<dd class = tt_identifier>" . $this->data['weight_class'] . "</dd>" .
		"<dd class = tt_identifier>" . $this->data['subtype'] . "</dd>";
		if($this->data['description'] != ""){
			$tt .= "<dd class = tt_description>" . $this->data['description'] . "</dd>";
		}		
		$tt .= "<dd class = tt_identifier>Required Level: " . $this->data['level'] . "</dd>" .
		"</dl>";
		return $tt;		
	}
	
	private function suffixTTInfo($suffix){		
		$tt = "";
		$sql = "SELECT * FROM item_info AS info WHERE info.id = '$suffix'";
		$result = mysqli_query($this->connection, $sql) or die(mysqli_error($item->connection));	
		$row = mysqli_fetch_assoc($result);
		$tt .= "<dd style = 'padding-top: 5px; color:" . $this->colors[$row['rarity']] . ";'><img width = 16px src =/images/items/" . $suffix . ".jpg> " . $row['name'] . "</dd>"; 
		
		$attrs = array();
		$sql = "SELECT * FROM item_attributes WHERE item_attributes.id = '$suffix'";
		$result = mysqli_query($this->connection, $sql) or die(mysqli_error($item->connection));	
		while($row = mysqli_fetch_assoc($result)){
			$attrs[] = $row;
		}		
		
		return $tt . $this->buffTTInfo($attrs);
	}
	
	private function buffTTInfo($buff){
		$tt = "";
		if(strpos($this->data['name'],'Rune') !== false){
			for($j = 0; $j < count($buff); $j++){
				$tt .= "<dd class = tt_attrs>(" . ($j+1) . ") " . $buff[$j]['attributeVal'] . "</dd>";
			}
			return $tt;
		}else if(strcmp($buff[0]['attributeVal'][0],"+") !== 0){
			return "<dd class = tt_attrs>" . $buff[0]['attributeVal'] . "</dd>";
		}
		
		$ex = explode("+",trim($buff[0]['attributeVal'],"+"));
		
		foreach($ex as $str){
			$tt .= "<dd class = tt_attrs>+" . $str . "</dd>";
		}		
		return $tt;
	}
}

?>	