## BASH !! SHELL BEACH !!
##
## crontab script suggestion
##
## * * */1 * * sh /path/to/lookupsapi/cron/cron.updategeoip.php
##
## author: simon@snails.email
##
prg="wget"
download_path="/tmp/GeoIP"
geolite_path="/usr/share/GeoIP"

mkdir $download_path
#set -e
[ -d $download_path ] || mkdir $download_path
if [ ! -e $geolite_path ]; then
        echo "Unable to find GeoIP directory: $geolite_path"
        mkdir $geolite_path
fi

chmod -fv 0777 $download_path
chmod -fv 0777 $geolite_path
rm -f $download_path/*
cd $download_path

$prg http://geolite.maxmind.com/download/geoip/database/GeoLiteCountry/GeoIP.dat.gz
if [ ! -e $download_path/GeoIP.dat.gz ]; then
        echo "Unable to find GeoIP.dat.gz!"
        exit 1
fi
gunzip -c $download_path/GeoIP.dat.gz > $geolite_path/GeoIP.dat
rm -f $download_path/GeoIP.dat.gz

$prg http://cdn.mirrors.liferay.com/geolite.maxmind.com/download/geoip/database/GeoIPASNum.dat.gz
if [ ! -e $download_path/GeoIPASNum.dat.gz ]; then
        echo "Unable to find GeoIPASNum.dat.gz!"
        exit 1
fi
gunzip -c $download_path/GeoIPASNum.dat.gz > $geolite_path/GeoIPASNum.dat
rm -f $download_path/GeoIPASNum.dat.gz

$prg http://cdn.mirrors.liferay.com/geolite.maxmind.com/download/geoip/database/GeoIPASNum2.zip
if [ ! -e $download_path/GeoIPASNum2.zip ]; then
        echo "Unable to find GeoIPASNum2.zip!"
        exit 1
fi
unzip -c $download_path/GeoIPASNum2.zip > $geolite_path/GeoIPASNum2.dat
rm -f $download_path/GeoIPASNum2.zip

$prg http://geolite.maxmind.com/download/geoip/database/GeoLiteCity.dat.gz
if [ ! -e $download_path/GeoLiteCity.dat.gz ]; then
        echo "Unable to find GeoLiteCity.dat.gz!"
        exit 1
fi
gunzip -c $download_path/GeoLiteCity.dat.gz > $geolite_path/GeoLiteCity.dat
rm -f $download_path/GeoLiteCity.dat.gz

$prg https://github.com/gsmlg/GeoIP.dat/raw/master/GeoIPDomain.dat.gz
if [ ! -e $download_path/GeoIPDomain.dat.gz ]; then
        echo "Unable to find GeoIPDomain.dat.gz!"
        exit 1
fi
gunzip -c $download_path/GeoIPDomain.dat.gz > $geolite_path/GeoIPDomain.dat
rm -f $download_path/GeoIPDomain.dat.gz

$prg https://github.com/gsmlg/GeoIP.dat/raw/master/GeoIPCity.dat.gz
if [ ! -e $download_path/GeoIPCity.dat.gz ]; then
        echo "Unable to find GeoIPCity.dat.gz!"
        exit 1
fi
gunzip -c $download_path/GeoIPCity.dat.gz > $geolite_path/GeoIPCity.dat
rm -f $download_path/GeoIPCity.dat.gz

$prg https://github.com/gsmlg/GeoIP.dat/raw/master/GeoIPOrg.dat.gz
if [ ! -e $download_path/GeoIPOrg.dat.gz ]; then
        echo "Unable to find GeoIPOrg.dat.gz!"
        exit 1
fi
gunzip -c $download_path/GeoIPOrg.dat.gz > $geolite_path/GeoIPOrg.dat
rm -f $download_path/GeoIPOrg.dat.gz

$prg http://cdn.mirrors.liferay.com/geolite.maxmind.com/download/geoip/database/GeoLiteCityv6.dat.gz
if [ ! -e $download_path/GeoLiteCityv6.dat.gz ]; then
        echo "Unable to find GeoLiteCityv6.dat.gz!"
        exit 1
fi
gunzip -c $download_path/GeoLiteCityv6.dat.gz > $geolite_path/GeoLiteCityv6.dat
rm -f $download_path/GeoLiteCityv6.dat.gz

$prg http://geolite.maxmind.com/download/geoip/database/GeoIPv6.dat.gz
if [ ! -e $download_path/GeoIPv6.dat.gz ]; then
        echo "Unable to find GeoIPv6.dat.gz!"
        exit 1
fi
gunzip -c $download_path/GeoIPv6.dat.gz > $geolite_path/GeoIPv6.dat
rm -f $download_path/GeoIPv6.dat.gz

$prg http://cdn.mirrors.liferay.com/geolite.maxmind.com/download/geoip/database/GeoIPASNumv6.dat.gz
if [ ! -e $download_path/GeoIPASNumv6.dat.gz ]; then
        echo "Unable to find GeoIPASNumv6.dat.gz!"
        exit 1
fi
gunzip -c $download_path/GeoIPASNumv6.dat.gz > $geolite_path/GeoIPASNumv6.dat
rm -f $download_path/GeoIPASNumv6.dat.gz


$prg http://cdn.mirrors.liferay.com/geolite.maxmind.com/download/geoip/database/GeoIPASNum2v6.zip
if [ ! -e $download_path/GeoIPASNum2v6.zip ]; then
        echo "Unable to find GeoIPASNum2v6.zip!"
        exit 1
fi
unzip -c $download_path/GeoIPASNum2v6.zip > $geolite_path/GeoIPASNum2v6.dat
rm -f $download_path/GeoIPASNum2v6.zip

$prg http://cdn.mirrors.liferay.com/geolite.maxmind.com/download/geoip/database/GeoLiteCity-latest.zip
if [ ! -e $download_path/GeoLiteCity-latest.zip ]; then
        echo "Unable to find GeoLiteCity-latest.zip!"
        exit 1
fi
unzip -c $download_path/GeoLiteCity-latest.zip > $geolite_path/GeoLiteCity.dat
rm -f $download_path/GeoLiteCity-latest.zip


$prg http://cdn.mirrors.liferay.com/geolite.maxmind.com/download/geoip/database/GeoLiteCityv6.dat.gz
if [ ! -e $download_path/GeoLiteCityv6.dat.gz ]; then
        echo "Unable to find GeoLiteCityv6.dat.gz!"
        exit 1
fi
gunzip -c $download_path/GeoLiteCityv6.dat.gz > $geolite_path/GeoLiteCityv6.dat
rm -f $download_path/GeoLiteCityv6.dat.gz


chown -fv www-data:root $geolite_path
chmod -fv 0777 $geolite_path
echo "\n\nFinished Updating GEOIP Databases!\n\n"
