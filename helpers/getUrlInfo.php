<?php

require '../php/connect.php';

$sql = "SELECT id, url FROM item_info";
$result = mysqli_query($con, $sql) or die(mysqli_error($con));
$myfile1 = fopen("D:\GW2SITE\helpers\ids.txt", "w") or die("Unable to open file!");
$myfile2 = fopen("D:\GW2SITE\helpers\urls.txt", "w") or die("Unable to open file!");

while($row = $result->fetch_array())
{
	fwrite($myfile1, $row['id'] . "\n");
	fwrite($myfile2, $row['url'] . "\n");
}

mysqli_close($con);

?>