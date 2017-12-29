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

	$parts = explode(".", microtime(true));
	mt_srand(mt_rand(-microtime(true), microtime(true))/$parts[1]);
	mt_srand(mt_rand(-microtime(true), microtime(true))/$parts[1]);
	mt_srand(mt_rand(-microtime(true), microtime(true))/$parts[1]);
	mt_srand(mt_rand(-microtime(true), microtime(true))/$parts[1]);
	$salter = ((float)(mt_rand(0,1)==1?'':'-').$parts[1].'.'.$parts[0]) / sqrt((float)$parts[1].'.'.intval(cosh($parts[0])))*tanh($parts[1]) * mt_rand(1, intval($parts[0] / $parts[1]));
	header('Blowfish-salt: '. $salter);
	
	/**
	 * URI Path Finding of API URL Source Locality
	 * @var unknown_type
	 */
	require_once __DIR__ . DIRECTORY_SEPARATOR . 'apiconfig.php';
	require_once __DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'functions.php';
	
	/**
	 * URI Path Finding of API URL Source Locality
	 * @var unknown_type
	 */
	$odds = $inner = array();
	foreach($_GET as $key => $values) {
	    if (!isset($inner[$key])) {
	        $inner[$key] = $values;
	    } elseif (!in_array(!is_array($values) ? $values : md5(json_encode($values, true)), array_keys($odds[$key]))) {
	        if (is_array($values)) {
	            $odds[$key][md5(json_encode($inner[$key] = $values, true))] = $values;
	        } else {
	            $odds[$key][$inner[$key] = $values] = "$values--$key";
	        }
	    }
	}
	
	foreach($_POST as $key => $values) {
	    if (!isset($inner[$key])) {
	        $inner[$key] = $values;
	    } elseif (!in_array(!is_array($values) ? $values : md5(json_encode($values, true)), array_keys($odds[$key]))) {
	        if (is_array($values)) {
	            $odds[$key][md5(json_encode($inner[$key] = $values, true))] = $values;
	        } else {
	            $odds[$key][$inner[$key] = $values] = "$values--$key";
	        }
	    }
	}
	
	foreach(parse_url('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'], '?')?'&':'?').$_SERVER['QUERY_STRING'], PHP_URL_QUERY) as $key => $values) {
	    if (!isset($inner[$key])) {
	        $inner[$key] = $values;
	    } elseif (!in_array(!is_array($values) ? $values : md5(json_encode($values, true)), array_keys($odds[$key]))) {
	        if (is_array($values)) {
	            $odds[$key][md5(json_encode($inner[$key] = $values, true))] = $values;
	        } else {
	            $odds[$key][$inner[$key] = $values] = "$values--$key";
	        }
	    }
	}
	$help=false;
	if ((!isset($inner['unixname']) || empty($inner['unixname'])) && (!isset($inner['width']) || empty($inner['width'])) && (!isset($inner['format']) || empty($inner['format']))) {
		$help=true;
	}
	
	if ($help==true) {
		header("Location: " . API_URL);
		exit;
	}
	
	if (function_exists("http_response_code"))
		http_response_code(200);
	
	if (!$sessions = APICache::read('cache-sessions'))
	    $sessions = array();
	    
    if (!isset($sessions[$inner['unixname']]['seed']) && !isset($sessions[$inner['unixname']]['caches']))
        $sessions[$inner['unixname']]['seed'] = mt_rand(0, 255);
        
    if (!isset($sessions[$inner['unixname']]['length']) && !isset($sessions[$inner['unixname']]['caches']))
        $sessions[$inner['unixname']]['length'] = mt_rand(5, 7);
        
    if (!isset($sessions[$inner['unixname']]['caches']))
        $sessions[$inner['unixname']]['caches'] = array();

    if (!$cache == APICache::read(yonkCacheKey($inner['unixname'], $inner['width'], $inner['format'], 'icon', '', $inner['extra'])) || empty($cache['data']) || $cache == true)
	{
	    
	    $sql = "SELECT `id`, `format-id`, `image-id`, `width`, `height`, `image` FROM `" . $GLOBALS['APIDB']->prefix('originals') . '` WHERE `unixname` LIKE "' . $inner['unixname'] . '"';
	    list($originalid, $formatid, $imageid, $width, $height, $filename) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
	    $sql = "SELECT `extension` FROM `" . $GLOBALS['APIDB']->prefix('formats') . '` WHERE `id` = "' . $formatid . '"';
	    list($extension) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
	    mkdir($workpath = API_VAR_PATH . DS . sha1(microtime(true)), 0777, true);
	    if (!is_array($original = APICache::read($key = yonkCacheKey($unixname, $width, $extension, 'original'))) || empty($original['data']) || $original == true)
	    {
	        
	        $sql = "SELECT `image` FROM `" . $GLOBALS['APIDB']->prefix('images') . '` WHERE `id` = "' . $imageid . '"';
	        list($imgdata) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
	        file_put_contents($workpath . DS . $filename, $imgdata);
	        $original = yonkImageInfoArray($workpath . DS . $filename);
	        $original['data']  = $imgdata;
	        
	        $sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('images') . '` SET `queried` = UNIX_TIMESTAMP() WHERE `id` = "' . $imageid . '"';
	        $GLOBALS['APIDB']->queryF($sql);
	        $sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('originals') . '` SET `caching` = `caching` + 1, `caching_bytes` = `caching_bytes` + '.strlen($imgdata) . ', `cached` = UNIX_TIMESTAMP() WHERE `id` = "' . $originalid . '"';
	        $GLOBALS['APIDB']->queryF($sql);
	        
	    } else {
	        
	        file_put_contents($workpath . DS . $filename, $original['data']);
	    }
	    
	    APICache::write($key = yonkCacheKey($unixname, $width, $extension, 'original'), $original, $seconds = API_CACHE_ORIGINAL);
	    $sessions[$unixname]['caches'][$key] = time() + $seconds;
	    chdir($workpath);
	    shell_exec(API_MAGICK_CONVERT . " -resize ".$inner['width'].'x'.$inner['width']. " " .$inner['extra'] . (!empty($inner['extra'])?" ":"")  . basename($filename) . " " . ($file = yonkFilename($inner['unixname'], $inner['width'], $inner['format'], 'icon', '', $inner['extra'])));

	    $sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('originals') . '` SET `converts` = `converts` + 1, `converts_bytes` = `converts_bytes` + '.filesize($workpath . DS . $file) . ', `converted` = UNIX_TIMESTAMP() WHERE `id` = "' . $originalid . '"';
	    $GLOBALS['APIDB']->queryF($sql);
	    $sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('originals') . '` SET `caching` = `caching` + 1, `caching_bytes` = `caching_bytes` + '.filesize($workpath . DS . $file) . ', `cached` = UNIX_TIMESTAMP() WHERE `id` = "' . $originalid . '"';
	    $GLOBALS['APIDB']->queryF($sql);
	    
	    $cache = yonkImageInfoArray($workpath . DS . $file);
	    $cache['data']  = file_get_contents($workpath . DS . $file);
	    shell_exec("rm -Rfv '$workpath'");
	    
	}

    APICache::write(yonkCacheKey($inner['unixname'], $inner['width'], $inner['format'], 'icon', '', $inner['extra']), $cache, $seconds = API_CACHE_ORIGINAL);
    $sessions[$inner['unixname']]['caches'][$key] = time() + $seconds;
    APICache::write('cache-sessions', $sessions, API_CACHE_SESSIONS);
    
	header('Content-Type: ' . $cache['image-mime-type']);
	header('Content-Disposition: attachment; filename="' . yonkFilename($inner['unixname'], $inner['width'], $inner['format'], 'icon', '', $inner['extra']) . '"');
	header('Content-Transfer-Encoding: binary');
	header('Accept-Ranges: bytes');
	header('Cache-Control: private');
	header('Pragma: private');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	
	$sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('originals') . '` SET `downloads` = `downloads` + 1, `downloads_bytes` = `downloads_bytes` + '.strlen($cache['data']) . ', `downloaded` = UNIX_TIMESTAMP() WHERE `id` = "' . $originalid . '"';
	$GLOBALS['APIDB']->queryF($sql);
	
	die($cache['data']);
	            
?>
