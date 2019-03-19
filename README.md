## Chronolabs Cooperative presents

# Favorite Icons REST API v1.1.2

## icons + Favorite Icons - http://icons.snails.email

### Author: Simon Antony Roberts <simon@snails.email>

The following REST API allows for images over nearly every format to be stored as an original in the database compressed and service icons with URL for support.

# Setting Up the environment in Ubuntu/Debian

There is a couple of extensions you will require for this API to run you need to execute the following at your terminal bash shell to have the modules installed before installation.

    $ sudo apt-get install imagemagick* -y
    

# Apache Module - URL Rewrite

The following script goes in your API_ROOT_PATH/.htaccess file

    php_value memory_limit 145M
    php_value upload_max_filesize 30M
    php_value post_max_size 50M
    php_value error_reporting 1
    php_value display_errors 1
    
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    
    RewriteRule ^v([0-9]{1,2})/(.*?)--([0-9]+).([0-9]+).([0-9]+).([0-9]+)/([0-9]+)/icon.(3fr|aai|ai|art|arw|avi|avs|bgr|bgra|bgro|bie|bmp|bmp2|bmp3|brf|cal|cals|cin|cip|clip|cmyk|cmyka|cr2|crw|cur|cut|data|dcm|dicom|dcr|dcx|dds|dfont|djvu|dng|dpx|digital|dxt1|dxt5|epdf|epi|eps|eps2|eps3|epsf|epsi|ept|ept2|ept3|erf|exr|fax|file|fits|fractal|fts|g3|g4|gif|gif87|gray|group4|gv|h|hald|hdr|hrz|icb|ico|icon|iiq|inline|ipl|isobrl|isobrl6|jbg|jbig|jng|jnx|jpe|jpeg|jpg|jps|k25|kdc|m2v|m4v|mac|magick|map|mask|mat|mef|miff|mkv|mng|mono|mov|mp4|mpc|mpeg|mpg|mrw|msl|msvg|mtv|mvg|nef|nrw|orf|otb|otf|pal|palm|pam|pango|pattern|pbm|pcd|pcds|pcl|pct|pcx|pdb|pdf|pdfa|pef|pes|pfa|pfb|pfm|pgm|picon|pict|pix|pjpeg|plasma|png|png00|png24|png32|png48|png64|png8|pnm|ppm|ps|ps2|ps3|psb|psd|ptif|pwp|raf|ras|raw|rgb|rgba|rgbo|rgf|rla|rle|rmf|rw2|scr|sct|sfw|sgi|shtml|six|sixel|sr2|srf|stegano|sun|svg|svgz|text|tga|tiff|tiff64|tile|tim|ttc|ttf|txt|ubrl|ubrl6|uil|uyvy|vda|vicar|vid|viff|vips|vst|wbmp|wmf|wmv|wmz|wpg|x|x3f|xbm|xc|xcf|xpm|xps|xv|xwd|ycbcr)$ ./icon.php?version=$1&unixname=$2&major=$3&minor=$4&revision=$5&subrevision=$6&width=$7&format=$8 [L,NC,QSA]
    RewriteRule ^v([0-9]{1,2})/(.*?)--([0-9]+).([0-9]+).([0-9]+).([0-9]+)/([0-9]+)/icon.(3fr|aai|ai|art|arw|avi|avs|bgr|bgra|bgro|bie|bmp|bmp2|bmp3|brf|cal|cals|cin|cip|clip|cmyk|cmyka|cr2|crw|cur|cut|data|dcm|dicom|dcr|dcx|dds|dfont|djvu|dng|dpx|digital|dxt1|dxt5|epdf|epi|eps|eps2|eps3|epsf|epsi|ept|ept2|ept3|erf|exr|fax|file|fits|fractal|fts|g3|g4|gif|gif87|gray|group4|gv|h|hald|hdr|hrz|icb|ico|icon|iiq|inline|ipl|isobrl|isobrl6|jbg|jbig|jng|jnx|jpe|jpeg|jpg|jps|k25|kdc|m2v|m4v|mac|magick|map|mask|mat|mef|miff|mkv|mng|mono|mov|mp4|mpc|mpeg|mpg|mrw|msl|msvg|mtv|mvg|nef|nrw|orf|otb|otf|pal|palm|pam|pango|pattern|pbm|pcd|pcds|pcl|pct|pcx|pdb|pdf|pdfa|pef|pes|pfa|pfb|pfm|pgm|picon|pict|pix|pjpeg|plasma|png|png00|png24|png32|png48|png64|png8|pnm|ppm|ps|ps2|ps3|psb|psd|ptif|pwp|raf|ras|raw|rgb|rgba|rgbo|rgf|rla|rle|rmf|rw2|scr|sct|sfw|sgi|shtml|six|sixel|sr2|srf|stegano|sun|svg|svgz|text|tga|tiff|tiff64|tile|tim|ttc|ttf|txt|ubrl|ubrl6|uil|uyvy|vda|vicar|vid|viff|vips|vst|wbmp|wmf|wmv|wmz|wpg|x|x3f|xbm|xc|xcf|xpm|xps|xv|xwd|ycbcr)?extra=(.*?)$ ./icon.php?version=$1&unixname=$2&&major=$3&minor=$4&revision=$5&subrevision=$6&width=$7&format=$8&extra=$9 [L,NC,QSA]
    RewriteRule ^v([0-9]{1,2})/(.*?)/([0-9]+)/icon.(3fr|aai|ai|art|arw|avi|avs|bgr|bgra|bgro|bie|bmp|bmp2|bmp3|brf|cal|cals|cin|cip|clip|cmyk|cmyka|cr2|crw|cur|cut|data|dcm|dicom|dcr|dcx|dds|dfont|djvu|dng|dpx|digital|dxt1|dxt5|epdf|epi|eps|eps2|eps3|epsf|epsi|ept|ept2|ept3|erf|exr|fax|file|fits|fractal|fts|g3|g4|gif|gif87|gray|group4|gv|h|hald|hdr|hrz|icb|ico|icon|iiq|inline|ipl|isobrl|isobrl6|jbg|jbig|jng|jnx|jpe|jpeg|jpg|jps|k25|kdc|m2v|m4v|mac|magick|map|mask|mat|mef|miff|mkv|mng|mono|mov|mp4|mpc|mpeg|mpg|mrw|msl|msvg|mtv|mvg|nef|nrw|orf|otb|otf|pal|palm|pam|pango|pattern|pbm|pcd|pcds|pcl|pct|pcx|pdb|pdf|pdfa|pef|pes|pfa|pfb|pfm|pgm|picon|pict|pix|pjpeg|plasma|png|png00|png24|png32|png48|png64|png8|pnm|ppm|ps|ps2|ps3|psb|psd|ptif|pwp|raf|ras|raw|rgb|rgba|rgbo|rgf|rla|rle|rmf|rw2|scr|sct|sfw|sgi|shtml|six|sixel|sr2|srf|stegano|sun|svg|svgz|text|tga|tiff|tiff64|tile|tim|ttc|ttf|txt|ubrl|ubrl6|uil|uyvy|vda|vicar|vid|viff|vips|vst|wbmp|wmf|wmv|wmz|wpg|x|x3f|xbm|xc|xcf|xpm|xps|xv|xwd|ycbcr)$ ./icon.php?version=$1&unixname=$2&width=$3&format=$4 [L,NC,QSA]
    RewriteRule ^v([0-9]{1,2})/(.*?)/([0-9]+)/icon.(3fr|aai|ai|art|arw|avi|avs|bgr|bgra|bgro|bie|bmp|bmp2|bmp3|brf|cal|cals|cin|cip|clip|cmyk|cmyka|cr2|crw|cur|cut|data|dcm|dicom|dcr|dcx|dds|dfont|djvu|dng|dpx|digital|dxt1|dxt5|epdf|epi|eps|eps2|eps3|epsf|epsi|ept|ept2|ept3|erf|exr|fax|file|fits|fractal|fts|g3|g4|gif|gif87|gray|group4|gv|h|hald|hdr|hrz|icb|ico|icon|iiq|inline|ipl|isobrl|isobrl6|jbg|jbig|jng|jnx|jpe|jpeg|jpg|jps|k25|kdc|m2v|m4v|mac|magick|map|mask|mat|mef|miff|mkv|mng|mono|mov|mp4|mpc|mpeg|mpg|mrw|msl|msvg|mtv|mvg|nef|nrw|orf|otb|otf|pal|palm|pam|pango|pattern|pbm|pcd|pcds|pcl|pct|pcx|pdb|pdf|pdfa|pef|pes|pfa|pfb|pfm|pgm|picon|pict|pix|pjpeg|plasma|png|png00|png24|png32|png48|png64|png8|pnm|ppm|ps|ps2|ps3|psb|psd|ptif|pwp|raf|ras|raw|rgb|rgba|rgbo|rgf|rla|rle|rmf|rw2|scr|sct|sfw|sgi|shtml|six|sixel|sr2|srf|stegano|sun|svg|svgz|text|tga|tiff|tiff64|tile|tim|ttc|ttf|txt|ubrl|ubrl6|uil|uyvy|vda|vicar|vid|viff|vips|vst|wbmp|wmf|wmv|wmz|wpg|x|x3f|xbm|xc|xcf|xpm|xps|xv|xwd|ycbcr)?extra=(.*?)$ ./icon.php?version=$1&unixname=$2&width=$3&format=$4&extra=$5 [L,NC,QSA]
    RewriteRule ^v([0-9]{1,2})/(html|post)/(.*?)/(form).api ./form.php?version=$1&mode=$2&session=$3&output=$4 [L,NC,QSA]
    RewriteRule ^v([0-9]{1,2})/(imageedit|user)/(.*?).(html) ./index.php?version=$1&mode=$2&session=$3&output=$4 [L,NC,QSA]
    RewriteRule ^v([0-9]{1,2})/(unixnames|users|formats|collectives)/(listing).(serial|xml|json|html|raw) ./index.php?version=$1&mode=$2&session=$3&output=$4 [L,NC,QSA]
    RewriteRule ^v([0-9]{1,2})/(user|unixname|format|collection)/(.*?).(serial|xml|json|html|raw) ./index.php?version=$1&mode=$2&session=$3&output=$4 [L,NC,QSA]


To Turn on the module rewrite with apache run the following:

    $ sudo a2enmod rewrite
    $ sudo service apache2 restart

# Cron Jobs - Scheduled Tasks

There is a couple of cron jobs that need to run on the system in order for the system to run completely within versioning specifications to get to the cron scheduler in ubuntu/debian run the following

    $ sudo crontab -e
    
once in the cron scheduler put these lines in making sure the paths resolution is correct as well as any load balancing you have to do

    

