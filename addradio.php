<title>Adding radio url to Iradio</title>
<h2>Adding radio url to Iradio</h2><br>
<form action=addradio.php method="GET">
Radio Name: <input type="text" name="n">
Stream URL http://<input type="text" name="u"><br><br>
<input type="Submit" value="Add radio">
</form>

<?php
$radioname=$_GET["n"];
$radiourl=$_GET["u"];
$radiourl = str_replace("http://", "", $radiourl);
if (empty($radioname)) {
echo "Please, enter radio name and stream url";
die();
}
if (empty($radiourl)) {
echo "Radiourl empty";
die();
}
$br = exec("ffmpeg -i http://$radiourl -f null 2>&1 | grep bitrate | awk -F ' ' '{print $6}'");
if (empty($br)) {
  echo "Cannot determine bitrate! Error adding radio! Stream URL Invalid!";
  die();
}
else {
echo "Radio $radioname SUCCESS added to server! Bitrate: $br kbps";
$converted_url = str_replace("/", "%/", $radiourl);
$writetofile = "$radioname ($br kbps)%$converted_url%$br\n";
file_put_contents('radios.txt', $writetofile, FILE_APPEND);
}
?>
