<?php
/**
 * Chronolabs IP Lookup's REST API File
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Chronolabs Cooperative http://labs.coop
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         lookups
 * @since           1.1.2
 * @author          Simon Roberts <meshy@labs.coop>
 * @version         $Id: index.php 1000 2013-06-07 01:20:22Z mynamesnot $
 * @subpackage		api
 * @description		Internet Protocol Address Information API Service REST
 */

/**
 * Cron Scheduling Suggestion
 *
 * * * * / 1 * * /usr/bin/php -q /path/to/lookupsapi/cron/cron.formats.php
 *
 */

/**
 * URI Path Finding of API URL Source Locality
 * @var unknown_type
 */
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'apiconfig.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'functions.php';
$GLOBALS['APIDB']->queryF($sql = "START TRANSACTION");

foreach(yonkImageFormats() as $extension => $title)
{
    $sql = "SELECT count(*) FROM `" . $GLOBALS['APIDB']->prefix('formats') . "` WHERE `extension` LIKE '$extension'";
    list($count) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
    if ($count == 0)
    {
        $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('formats') . "` (`extension`, `title`, `created`) VALUES('$extension','".$GLOBALS['APIDB']->escape($title)."',UNIX_TIMESTAMP())";
        @$GLOBALS['APIDB']->queryF($sql);
    }
}

$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('formats') . "` WHERE `mime-type` LIKE '' ORDER BY RAND() LIMIT 169";
$result = $GLOBALS['APIDB']->queryF($sql);
while($row = $GLOBALS['APIDB']->fetchArray($result))
{
    $data = getURIData($uri = API_URL . '/v1/chronolabs-it/24/icon.'.$row['extension'], 130, 130);
    file_put_contents($tmpfile = API_PATH . DS . (string)(mt_rand(1, 9) . mt_rand('a', 'z') . mt_rand(1, 9) . mt_rand('a', 'z') . mt_rand(1, 9) . mt_rand('a', 'z') . mt_rand(1, 9) . mt_rand('a', 'z') . mt_rand(1, 9) . mt_rand('a', 'z')) . '.' . $row['extension'], $data);
    if (file_exists($tmpfile))
        if ($GLOBALS['APIDB']->queryF($sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('formats') . "` SET `mime-type` = '" . mime_content_type($tmpfile) . "' WHERE `id` = '" . $row['id'] ."'"))
            echo "Success: $sql\n";
        else 
            echo "Failed: $sql\n";
}


$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('formats') . "` WHERE `extension` NOT LIKE '' ORDER BY RAND()";
$result = $GLOBALS['APIDB']->queryF($sql);
while($row = $GLOBALS['APIDB']->fetchArray($result))
{
    if ($GLOBALS['APIDB']->queryF($sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('originals') . "` SET `format-id` = '" . $row['id'] . "' WHERE `image` LIKE '%." . $row['extension'] ."'"))
        echo "Success: $sql\n";
    else
        echo "Failed: $sql\n";
    
    list($uploads, $uploads_bytes, $downloads, $downloads_bytes, $caching, $caching_bytes, $uploaded, $downloaded, $cached) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql = "SELECT sum(`uploads`), sum(`uploads_bytes`), sum(`downloads`), sum(`downloads_bytes`), sum(`caching`), sum(`caching_bytes`), max(`uploaded`), max(`downloaded`), max(`cached`) FROM `" . $GLOBALS['APIDB']->prefix('originals') . "` WHERE `format-id` = '" . $row['id'] . "' AND `image` LIKE '%." . $row['extension'] ."'"));
        
    if (empty($uploads))
        $uploads = "0";
    if (empty($uploads_bytes))
        $uploads_bytes = "0";
    if (empty($downloads))
        $downloads = "0";
    if (empty($downloads_bytes))
        $downloads_bytes = "0";
    if (empty($caching))
        $caching = "0";
    if (empty($caching_bytes))
        $caching_bytes = "0";
    if (empty($uploaded))
        $uploaded = "0";
    if (empty($downloaded))
        $downloaded = "0";
    if (empty($cached))
        $cached = "0";
    
    if ($GLOBALS['APIDB']->queryF($sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('formats') . "` SET `uploads` = '" . $uploads . "', `uploads_bytes` = '" . $uploads_bytes . "', `downloads` = '" . $downloads . "', `downloads_bytes` = '" . $downloads_bytes . "', `caching` = '" . $caching . "', `caching_bytes` = '" . $caching_bytes . "', `uploaded` = '" . $uploaded . "', `downloaded` = '" . $downloaded . "', `cached` = '" . $cached . "' WHERE `id` = '" . $row['id'] ."'"))
        echo "Success: $sql\n";
    else
        echo "Failed: $sql\n";
            
}

$GLOBALS['APIDB']->queryF($sql = "COMMIT");

?>