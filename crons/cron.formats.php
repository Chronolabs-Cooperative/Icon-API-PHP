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

$sql = "DELETE FROM `" . $GLOBALS['APIDB']->prefix('formats') . "` WHERE `extension` NOT IN ('".implode("', '", array_keys($formats)) . "')";
@$GLOBALS['APIDB']->queryF($sql);
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
$GLOBALS['APIDB']->queryF($sql = "COMMIT");

?>