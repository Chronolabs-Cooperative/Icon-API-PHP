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

define('CURL_TIMEOUT', 120);
define('CURL_CONNECTION', 240);

/**
 * Cron Scheduling Suggestion
 * 
 * * / 1 * * * * /usr/bin/php -q /path/to/lookupsapi/cron/cron.queries.php
 * * / 1 * * * * /usr/bin/php -q /path/to/lookupsapi/cron/cron.queries.php
 * * / 1 * * * * /usr/bin/php -q /path/to/lookupsapi/cron/cron.queries.php
 *
 */

 // The following Line Adjusts to the scheduling break period
 sleep(mt_rand(0, 90));

 /**
 * URI Path Finding of API URL Source Locality
 * @var unknown_type
 */
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'apiconfig.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'functions.php';

$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('queries') . "` WHERE `todo` > 0 ORDER BY RAND LIMIT 50";
$result = $GLOBALS['APIDB']->queryF($sql);
while($row = $GLOBALS['APIDB']->fetchArray($result))
{
    $GLOBALS['APIDB']->queryF("START TRANSACTION");
    $methods = json_encode($row['methods'], true);
    shuffle($methods);
    shuffle($methods);
    $ipinfo = findDetails($row['value'], $method = $method[0]);
    unset($method[0]);
    $sql = "UDPATE  `" . $GLOBALS['APIDB']->prefix('queries') . "` SET `todo` = '" .count($methods) . "', `methods` = '" . json_encode($methods) . "' WHERE `id` = '" . $row['id'];
    if (!$GLOBALS['APIDB']->queryF($sql))
        die('SQL Failed: $sql');
    $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('results') . "` (`question-id`, `query-id`, `method`, `value`, `created`) VALUES('" . $row['question-id'] . "','" . $row['id'] . "','" . $method . "','" . json_encode($ipinfo) . "', UNIX_TIMESTAMP())";
    if (!$GLOBALS['APIDB']->queryF($sql))
        die('SQL Failed: $sql');
    $GLOBALS['APIDB']->queryF("COMMIT");
    $sql = "SELECT count(*) FROM `" . $GLOBALS['APIDB']->prefix('queries') . "` WHERE `todo` > 0 AND `question-id` = ".$row['question-id']."";
    list($count) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
    if ($count==0)
    {
        // Callback Due for Scheduling
        $GLOBALS['APIDB']->queryF("START TRANSACTION");
        $data = array();
        $sql = "SELECT md5(concat(`question-id`, `query-id`)) as `key`, `method`, `value` FROM `" . $GLOBALS['APIDB']->prefix('results') . "` WHERE `question-id` = '" . $row['question-id'] . "' ORDER BY `query-id`, `method`, `created`";
        $results = $GLOBALS['APIDB']->queryF($sql);
        while($ret = $GLOBALS['APIDB']->fetchArray($result))
        {
            $data['results'][$ret['method']][$ret['key']] = json_decode($ret['value'], true);
        }
        $sql = "SELECT md5(concat(`question-id`, `id`)) as `key`, `value` FROM `" . $GLOBALS['APIDB']->prefix('queries') . "` WHERE `question-id` = '" . $row['question-id'] . "' ORDER BY `id`, `created`";
        $results = $GLOBALS['APIDB']->queryF($sql);
        while($ret = $GLOBALS['APIDB']->fetchArray($result))
        {
            $data['keys'][$ret['key']] = $ret['value'];
        }
        $sql = "SELECT `session`, `callback` FROM `" . $GLOBALS['APIDB']->prefix('questions') . "` WHERE `id` = '" . $row['question-id'] . "'";
        list($session, $callback) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
        $queries = array();
        $queries['success'][]  = "UPDATE `" . $GLOBALS['APIDB']->prefix('questions') . "` SET `called` = UNIX_TIMESTAMP() WHERE `id` = ". $row['question-id'];
        $data['session'] = $session;
        $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('callbacks') . "` (`uri`, `timeout`, `connection`, `data`, `queries`) VALUES('" . $callback . "','" . CURL_TIMEOUT . "','" . CURL_CONNECTION . "','" . json_encode($data) . "','" . json_encode($queries) . "')";
        if (!$GLOBALS['APIDB']->queryF($sql))
            die('SQL Failed: $sql');            
        $GLOBALS['APIDB']->queryF("COMMIT");
    }
    
}
?>
