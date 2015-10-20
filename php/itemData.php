<?php

require_once('connect.php');
require_once('extras.php');
require_once('tooltip.php');

if(!isset($_GET['id']) || empty($_GET['id'])){
	include('/404error.php');
	die();	
}

$id = $_GET['id'];

$sql = "SELECT COUNT(*) FROM item_info WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param('i',$id);
$stmt->execute();
$stmt->bind_result($numrows); 
$stmt->fetch();
if($numrows == 0){
	include('/404error.php');
	die();	
}	
$stmt->close();

$item = new Item($id, $con);

$items = array();

if(isset($_COOKIE["frequented"])){
	$items = json_decode($_COOKIE['frequented']);	
}
if(in_array($item->itemData['id'], $items)){
	$key = array_search($item->itemData['id'],$items);
	unset($items[$key]);
	$items = array_values($items);	
}
array_unshift($items,$item->itemData['id']);
if(count($items) == 11){
	array_pop($items);
}
setcookie("frequented", json_encode($items), time() + (86400 * 14), "/");

mysqli_close($con);

class Item {
	
	private $itemId;
	private $connection;
	private $itemAttrs;
	private $itemInfusions;
	public $itemData;
	public $marketData;		
	public $imageUrl;
	public $max;
	public $min;
	public $tooltip;
	
	public function __construct($id, $con){
		$this->itemId = $id;
		$this->connection = $con;		
		$this->marketData = $this->getMarketData();	
		$this->itemData = $this->getItemData();
		$this->itemAttrs = $this->getItemAttrs();
		$this->itemInfusions = $this->getItemInfusions();
		$this->max = coinImages($this->marketData['max_offer']);
		$this->min = coinImages($this->marketData['min_sell']);
		$this->imageUrl = '/images/items/' . floor($this->itemId/1000) . '/' . $this->itemId . '.jpg';
		$this->tooltip = new toolTip($this->itemData, $this->itemAttrs, $this->itemInfusions, $this->connection);
	}
	
	private function getMarketData(){
		$sql = "SELECT max_offer, min_sell, supply, demand FROM current_market WHERE id = '$this->itemId'";
		$result = mysqli_query($this->connection, $sql) or die(mysqli_error($item->connection));		
		return mysqli_fetch_assoc($result);
	}	
	
	private function getItemData(){
		$sql = "SELECT * FROM item_info AS info LEFT JOIN item_main_stats AS stats ON info.id=stats.id WHERE info.id = '$this->itemId'";
		$result = mysqli_query($this->connection, $sql) or die(mysqli_error($item->connection));		
		return mysqli_fetch_assoc($result);		
	}
	
	private function getItemAttrs(){
		$attrs = array();
		$sql = "SELECT * FROM item_attributes WHERE item_attributes.id = '$this->itemId'";
		$result = mysqli_query($this->connection, $sql) or die(mysqli_error($item->connection));	
		while($row = mysqli_fetch_assoc($result)){
			$attrs[] = $row;
		}	
		return $attrs;	
	}
	
	private function getItemInfusions(){
		$infusions = array();
		$sql = "SELECT * FROM item_infusions WHERE item_infusions.id = '$this->itemId'";
		$result = mysqli_query($this->connection, $sql) or die(mysqli_error($item->connection));	
		while($row = mysqli_fetch_assoc($result)){
			$infusions[] = $row;
		}	
		return $infusions;	
	}
}	

?>




