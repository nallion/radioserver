<title>Adding radio url to Iradio (with automatic transcoding).</title>
<h2>Universal transcoder. Adding radio url to Iradio.</h2><br>
Supported input formats: all ffmpeg-supported input formats of streaming audio and video.<br>
Supported schemas: http, https.<br>
Supported ports: all.<br>
Output format: MP3, mono, 40kbps, 32000hz, equalisation on high freq.
<br><br>
<form action=addradio.php method="GET">
Radio Name: <input type="text" name="n">
Stream URL <input type="text" name="u"><br><br>
<input type="Submit" value="Add radio">
</form>

<?php
$radioname=$_GET["n"];
$radiourl=$_GET["u"];
if (empty($radioname)) {
echo "Please, enter radio name and stream url.";
die();
}
if (empty($radiourl)) {
echo "Radiourl empty";
die();
}
$brmp3 = exec("ffmpeg -i $radiourl -f null 2>&1 | grep bitrate | awk -F ' ' '{print $6}'");
$braac = exec("ffmpeg -i $radiourl -f null 2>&1 | grep bitrate | awk -F ' ' '{print $4}'");
$brm3u8 = exec("ffmpeg -i $radiourl -f null 2>&1 | grep Stream | awk -F ' ' '{print $10}' | head -c 3");
//echo $brm3u8;
if (empty($brmp3 | $braac | $brm3u8)) {
//if (empty($brmp3) | empty($braac) | empty($brm3u8)) {
echo "Error adding radio, cannot determine bitrate, maybe stream is dead?";
die();
}
else {
$filename = strtok($radioname, " ");
function generateRandomString($length = 10) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}
$randstrradio = generateRandomString();
$writetofile = "while true\r\n do\r\n ffmpeg -re -i $radiourl -acodec libmp3lame -ar 32000 -ab 40k -ac 1 -af \"equalizer=f=13000:width_type=h:width=4000:g=+40\" -bufsize 10240k -content_type 'audio/mpeg' -legacy_icecast 1 icecast://source:lfflu41b@127.0.0.1:8000/$randstrradio.mp3\r\n sleep2\r\n done\r\n";
$filenameradio = "radios_scripts/$filename$randstrradio.sh";
file_put_contents("$filenameradio", $writetofile);
exec("dos2unix /var/www/html/iradio/$filenameradio");
exec("chmod 777 /var/www/html/iradio/$filenameradio");
exec("chmod +X /var/www/html/iradio/$filenameradio");
$writestation = "$radioname (40 kbps)%tg-gw.com:8000%/$randstrradio.mp3%40\n";
file_put_contents('radios.txt', $writestation, FILE_APPEND);
echo "Radio $radioname successfully added to transcoding and to application! Thank you!<br> URL http://tg-gw.com:8000/$randstrradio.mp3";
$writerclocal = "nohup bash /var/www/html/iradio/$filenameradio > /dev/null 2>&1&\n";
file_put_contents('/etc/rc.local', $writerclocal, FILE_APPEND);
exec("nohup bash /var/www/html/iradio/$filenameradio > /dev/null 2>&1&");
}
?>
