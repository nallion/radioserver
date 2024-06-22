#!/bin/bash
JAZMIN=$(curl -s https://nokiapps40.neocities.org/vr.xml | head -n -1)
CONVERTED=$(cat /var/www/html/iradio/radios.txt | awk -F '%' '{print " " "\<c t=""\"" $1"""\""" ""u=""\"" "http://"$2 $3"\"" "/>"}')
echo "$JAZMIN" > /var/www/html/iradio/vr.xml
echo "<g t=\"tg-gw.com transcoded\">" >> /var/www/html/iradio/vr.xml
echo "$CONVERTED" >> /var/www/html/iradio/vr.xml
echo " </g>" >> /var/www/html/iradio/vr.xml
echo "</vr>" >> /var/www/html/iradio/vr.xml
