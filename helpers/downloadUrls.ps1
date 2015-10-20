$failure = (Get-Content D:\GW2SITE\helpers\ids.txt | Select-Object -last 1)
Write-Host $failure
$id = 0;
$val = 0;
while($id -ne $failure){
	$url = (Get-Content D:\GW2SITE\helpers\urls.txt)[$val]
	$id = (Get-Content D:\GW2SITE\helpers\ids.txt)[$val]
	if($url -eq ""){
		$val++
		continue
	}

	$wc = New-Object System.Net.WebClient
	$wc.DownloadFile($url, "D:\GW2SITE\images\items\" + [Math]::Floor([decimal]($id/1000)) + "/" + $id + ".jpg")
	$val++;
}

