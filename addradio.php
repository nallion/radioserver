<?php
$radioname=$_GET["n"];
$radiourl=$_GET["u"];
$br = exec("ffmpeg -i http://$radiourl -f null 2>&1 | grep bitrate | awk -F ' ' '{print $6}'");
if (empty($br)) {
  echo "Error adding radio. Cannot determine bitrate! URL INVALID!";
  die();
}
else {
echo "Radio $radioname SUCCESS added to server! Bitrate: $br kbps";
$converted_url = str_replace("/", "%/", $radiourl);
$writetofile = "$radioname ($br kbps)%$converted_url%$br\n";
file_put_contents('radios.txt', $writetofile, FILE_APPEND);
}
