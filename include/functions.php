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

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'class'  . DIRECTORY_SEPARATOR . 'xcp' . DIRECTORY_SEPARATOR . 'xcp.class.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'class'  . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'apicache.php';

function yonkCacheKey($unixname = '', $width = 128, $format = 'png', $mode = 'icon', $variable = '', $extra = '', $version = '0.0.0.0')
{
    
    if (!$sessions = APICache::read('cache-sessions'))
        $sessions = array();
        
    if (!isset($sessions[$unixname . '--' . $version]['seed']) && !isset($sessions[$unixname]['caches']))
        $sessions[$unixname . '--' . $version]['seed'] = mt_rand(0, 255);
        
    if (!isset($sessions[$unixname . '--' . $version]['length']) && !isset($sessions[$unixname]['caches']))
        $sessions[$unixname . '--' . $version]['length'] = mt_rand(5, 7);

    if (!isset($sessions[$unixname . '--' . $version]['caches']))
        $sessions[$unixname . '--' . $version]['caches'] = array();

    APICache::write('cache-sessions', $sessions, API_CACHE_SESSIONS);
    
    switch ($mode)
    {
        default:
        case "icon":
            $result = $unixname . '--' . $version . '-' . $width . 'x' . $width . '-' . $format . '-' . $mode . (!empty($variable)?"-$variable":"");
            break;
    }
    
    if (!empty($extra))
    {
        $xcp = new xcp($extra, $sessions[$unixname . '--' . $version]['seed'], $sessions[$unixname . '--' . $version]['length']);
        $result .= '-' . $xcp->calc($extra);
    }
    
    return $result;
}


function yonkFilename($unixname = '', $width = 128, $format = 'png', $mode = 'icon', $variable = '', $extra = '', $version = '0.0.0.0')
{
    
    if (!$sessions = APICache::read('cache-sessions'))
        $sessions = array();
    
    if (!isset($sessions[$version . '--' . $unixname]['seed']) && !isset($sessions[$unixname]['caches']))
        $sessions[$version . '--' . $unixname]['seed'] = mt_rand(0, 255);
            
    if (!isset($sessions[$version . '--' . $unixname]['length']) && !isset($sessions[$unixname]['caches']))
        $sessions[$version . '--' . $unixname]['length'] = mt_rand(5, 7);
            
    if (!isset($sessions[$version . '--' . $unixname]['caches']))
        $sessions[$version . '--' . $unixname]['caches'] = array();
                    
    APICache::write('cache-sessions', $sessions, API_CACHE_SESSIONS);
                    
    switch ($mode)
    {
        default:
            $result = ($version != '0.0.0.0' ? $version: "") . '--'  . $unixname . '-' . $width . 'x' . $width . '-' . $mode . (!empty($variable)?"-$variable":"");
            break;
            
        case "icon":
            $result = ($version != '0.0.0.0' ? $version: "") . '--' . $unixname . '-' . $width . 'x' . $width ;
            break;
    }
    
    if (!empty($extra))
    {
        $xcp = new xcp($extra, $sessions[$version . '--' . $unixname]['seed'], $sessions[$version . '--' . $unixname]['length']);
        $result .= '-' . $xcp->calc($extra);
    }
    
    $result .= '.' . $format; 
    return $result;
}


function yonkImageURL($unixname = '', $width = 128, $format = 'png', $mode = 'icon', $variable = '', $extra = '')
{
    switch ($mode)
    {
        default:
           $result = API_URL . '/v1/' . $unixname . '/' . $width . '/' . $mode . (!empty($variable)?"-$variable":"");
            break;
    }
    
    $result .= '.' . $format; 
    
    if (!empty($extra))
    {
        $result .= '?extra=' . urlencode($extra); 
    }
    
    return $result;
}


function yonkImageVersionURL($unixname = '', $width = 128, $format = 'png', $version = '1.0.0.1', $mode = 'icon', $variable = '', $extra = '')
{
    switch ($mode)
    {
        default:
            $result = API_URL . '/v1/' . $unixname . '--' . $version . '/' . $width . '/' . $mode . (!empty($variable)?"-$variable":"");
            break;
    }
    
    $result .= '.' . $format;
    
    if (!empty($extra))
    {
        $result .= '?extra=' . urlencode($extra);
    }
    
    return $result;
}

function yonkDisplayBytes($bytes = 0)
{
    $result = array();
    $scale = array();
    $scale['tb'] = 1024 * 1024 * 1024 * 1024 * 1024;
    $scale['gb'] = 1024 * 1024 * 1024 * 1024;
    $scale['mb'] = 1024 * 1024 * 1024;
    $scale['kb'] = 1024 * 1024;
    
    foreach($scale as $measure => $weight){
        if ($bytes / $weight > 1) {
            $parts = explode('.', $bytes / $weight);
            $result[$measure] = $parts[0] . $measure;
            $bytes = ((float)"0." . $parts[1]) * $weight;
        }
    }
    
    if (count($result))
        return implode(' ', $result);
    return $bytes . ' bytes';
}

function yonkUnixnameAllVersions($unixname = '')
{
    $results = array();
    $sql = "SELECT *, md5(concat(`id`, '" . API_URL . "', 'originals')) as `key`, concat(`major`, '.', `minor`, '.', `revision`, '.', `subrevision`) as `version` FROM `" . $GLOBALS['APIDB']->prefix('originals') . '` WHERE `unixname` LIKE "' . $unixname . '" ORDER BY `major` DESC, `minor` DESC, `revision` DESC, `subrevision` DESC';
    $result = $GLOBALS['APIDB']->queryF($sql);
    while($row = $GLOBALS['APIDB']->fetchArray($result))
    {
        $id = $row['id'];
        unset($row['id']);
        $versioning = str_replace('.', '-', $row['version']);
        $results['version-'.$versioning] = $row;
        unset($results['version-'.$versioning]['image-id']);
        $results['version-'.$versioning]['email'] = checkEmail($results['version-'.$versioning]['email'], true);
        unset($results['version-'.$versioning]['emailings']);
        foreach(array_merge(array($results['version-'.$versioning]['uid']), unserialize($results['version-'.$versioning]['uids'], true)) as $uid)
            $results['version-'.$versioning]['user-keys'][] = md5($uid . API_URL . 'users');
        unset($results['version-'.$versioning]['uid']);
        unset($results['version-'.$versioning]['uids']);
        if ($results['version-'.$versioning]['collection-id']<>0) {
            $sql = "SELECT *, md5(concat(`id`, '" . API_URL . "', 'collections')) as `key`  FROM `" . $GLOBALS['APIDB']->prefix('collections') . '` WHERE `id` = "' . $row['collection-id'] . '"';
            $results['version-'.$versioning]['collection'] = $GLOBALS['APIDB']->fetchArray($GLOBALS['APIDB']->queryF($sql));
            $results['version-'.$versioning]['collection']['email'] = checkEmail($results['version-'.$versioning]['collection']['email'], true);
            unset($results['version-'.$versioning]['collection']['emailings']);
            unset($results['version-'.$versioning]['collection']['id']);
            if ($results['version-'.$versioning]['collection']['pid']<>0)
                $results['version-'.$versioning]['collection']['parent-key'] = md5($results['version-'.$versioning]['collection']['pid'] . API_URL . 'collections');
            unset($results['version-'.$versioning]['collection']['pid']);
            foreach(array_merge(array($results['version-'.$versioning]['collection']['uid']), unserialize($results['version-'.$versioning]['collection']['uids'])) as $uid)
                $results['version-'.$versioning]['collection']['user-keys'][] = md5($uid . API_URL . 'users');
            $results['version-'.$versioning]['collection']['user-keys'] = array_unique($results['version-'.$versioning]['collection']['user-keys']);
            unset($results['version-'.$versioning]['collection']['uid']);
            unset($results['version-'.$versioning]['collection']['uids']);
            foreach(unserialize($results['version-'.$versioning]['collection']['oids']) as $oid)
                $results['version-'.$versioning]['collection']['original-keys'][] = md5($oid . API_URL . 'originals');
            unset($results['version-'.$versioning]['collection']['oids']);
            $results['version-'.$versioning]['collection']['original-keys'] = array_unique($results['version-'.$versioning]['collection']['original-keys']);
        }
        unset($results['version-'.$versioning]['collection-id']);
        if ($row['format-id']<>0) {
            $results['version-'.$versioning]['format'] = yonkFormatsDetails($row['format-id']);
        }
        unset($results['version-'.$versioning]['format-id']);
        $sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('users_originals') . '` WHERE `original-id` = "' . $id . '"';
        $userresult = $GLOBALS['APIDB']->queryF($sql);
        while($useroriginal = $GLOBALS['APIDB']->fetchArray($userresult))
        {
            if ($useroriginal['uid'] <> 0 )
            {
                $sql = "SELECT *, md5(concat(`uid`, '" . API_URL . "', 'users')) as `key` FROM `" . $GLOBALS['APIDB']->prefix('users') . '` WHERE `uid` = "' . $useroriginal['uid'] . '"';
                $user = $GLOBALS['APIDB']->fetchArray($GLOBALS['APIDB']->queryF($sql));
                unset($user['uid']);
                $results['version-'.$versioning]['users'][$user['uname']][sef($user['name'])] = $user;
                $results['version-'.$versioning]['users'][$user['uname']][sef($user['name'])]['email'] = checkEmail($results['version-'.$versioning]['users'][$user['uname']][sef($user['name'])]['email'], true);
                unset($results['version-'.$versioning]['users'][$user['uname']][sef($user['name'])]['pass']);
                unset($results['version-'.$versioning]['users'][$user['uname']][sef($user['name'])]['actkey']);
            }
        }
    }
    return $results;
}

function yonkUserDetails($userkey = '')
{
    $sql = "SELECT *, md5(concat(`uid`, '" . API_URL . "', 'users')) as `key` FROM `" . $GLOBALS['APIDB']->prefix('users') . '` WHERE md5(concat(`uid`, "' . API_URL . '", "users")) LIKE "' . $userkey . '"';
    $user = $GLOBALS['APIDB']->fetchArray($GLOBALS['APIDB']->queryF($sql));
    $sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('users_originals') . '` WHERE `uid` = "' . $user['uid'] . '"';
    $userresult = $GLOBALS['APIDB']->queryF($sql);
    while($useroriginal = $GLOBALS['APIDB']->fetchArray($userresult))
    {
        if ($useroriginal['original-id'] <> 0 )
        {
            $sql = "SELECT *, md5(concat(`id`, '" . API_URL . "', 'originals')) as `key`, concat(`major`, '.', `minor`, '.', `revision`, '.', `subrevision`) as `version` FROM `" . $GLOBALS['APIDB']->prefix('originals') . '` WHERE `id` = "' . $useroriginal['original-id'] . '"';
            $original = $GLOBALS['APIDB']->fetchArray($GLOBALS['APIDB']->queryF($sql));
            $versioning = str_replace('.', '-', $original['version']);
            $user['originals'][$original['unixname']]['version-'.$versioning] = $original;
            $user['originals'][$original['unixname']]['version-'.$versioning]['email'] = checkEmail($user['originals'][$original['unixname']]['version-'.$versioning]['email'], true);
            foreach(array_merge(array($user['originals'][$original['unixname']]['version-'.$versioning]['uid']), unserialize($user['originals'][$original['unixname']]['version-'.$versioning]['uids'], true)) as $uid)
                $user['originals'][$original['unixname']]['version-'.$versioning]['users'][] = md5($uid . API_URL . 'users');
            unset($user['originals'][$original['unixname']]['version-'.$versioning]['uid']);
            unset($user['originals'][$original['unixname']]['version-'.$versioning]['uids']);
            unset($user['originals'][$original['unixname']]['version-'.$versioning]['id']);
            unset($user['originals'][$original['unixname']]['version-'.$versioning]['image-id']);
            $user['originals'][$original['unixname']]['version-'.str_replace('.', '-', $$original['version'])]['email'] = checkEmail($user['originals'][$original['unixname']]['version-'.$versioning]['email'], true);
            unset($user['originals'][$original['unixname']]['version-'.$versioning]['emailings']);
            if ($user['originals'][$original['unixname']]['version-'.$versioning]['collection-id']<>0) {
                $sql = "SELECT *, md5(concat(`id`, '" . API_URL . "', 'collections')) as `key`  FROM `" . $GLOBALS['APIDB']->prefix('collections') . '` WHERE `id` = "' . $user['originals'][$original['unixname']]['version-'.$versioning]['collection-id'] . '"';
                $user['originals'][$original['unixname']]['version-'.$versioning]['collection'] = $GLOBALS['APIDB']->fetchArray($GLOBALS['APIDB']->queryF($sql));
                $user['originals'][$original['unixname']]['version-'.$versioning]['collection']['email'] = checkEmail($user['originals'][$original['unixname']]['version-'.$versioning]['collection']['email'], true);
                unset($user['originals'][$original['unixname']]['version-'.$versioning]['collection']['emailings']);
                unset($user['originals'][$original['unixname']]['version-'.$versioning]['collection']['id']);
                if ($user['originals'][$original['unixname']]['version-'.$versioning]['collection']['pid']<>0)
                    $user['originals'][$original['unixname']]['version-'.$versioning]['collection']['parent-key'] = md5($user['originals'][$original['unixname']]['version-'.$versioning]['collection']['pid'] . API_URL . 'collections');
                unset($user['originals'][$original['unixname']]['version-'.$versioning]['collection']['pid']);
                foreach(array_merge(array($user['originals'][$original['unixname']]['version-'.$versioning]['collection']['uid']), unserialize($user['originals'][$original['unixname']]['version-'.$versioning]['collection']['uids'])) as $uid)
                    $user['originals'][$original['unixname']]['version-'.$versioning]['collection']['user-keys'][] = md5($uid . API_URL . 'users');
                $user['originals'][$original['unixname']]['version-'.$versioning]['collection']['user-keys'] = array_unique($user['originals'][$original['unixname']]['version-'.$versioning]['collection']['user-keys']);
                unset($user['originals'][$original['unixname']]['version-'.$versioning]['collection']['uid']);
                unset($user['originals'][$original['unixname']]['version-'.$versioning]['collection']['uids']);
                foreach(unserialize($user['originals'][$original['unixname']]['version-'.$versioning]['collection']['oids']) as $oid)
                    $user['originals'][$original['unixname']]['version-'.$versioning]['collection']['original-keys'][] = md5($oid . API_URL . 'originals');
                unset($user['originals'][$original['unixname']]['version-'.$versioning]['collection']['oids']);
                $user['originals'][$original['unixname']]['version-'.$versioning]['collection']['original-keys'] = array_unique($user['originals'][$original['unixname']]['version-'.$versioning]['collection']['original-keys']);
            }
            unset($user['originals'][$original['unixname']]['version-'.$versioning]['collection-id']);
            if ($user['originals'][$original['unixname']]['version-'.$versioning]['format-id']<>0) {
                $user['originals'][$original['unixname']]['version-'.$versioning]['format'] = yonkFormatsDetails($user['originals'][$original['unixname']]['version-'.$versioning]['format-id']);
                unset($user['originals'][$original['unixname']]['version-'.$versioning]['format']['id']);
            }
            unset($user['originals'][$original['unixname']]['version-'.$versioning]['format-id']);
            if (isset($user['originals'][$original['unixname']]['version-']))
                unset($user['originals'][$original['unixname']]['version-']);
        }
    }
    $user['email'] = checkEmail($user['email'], true);
    unset($user['uid']);
    unset($user['pass']);
    unset($user['actkey']);
    return $user;
}


/**
 * validateMD5()
 * Validates an MD5 Checksum
 *
 * @param string $email
 * @return boolean
 */

if (!function_exists("validateMD5")) {
    function validateMD5($md5) {
        if(preg_match("/^[a-f0-9]{32}$/i", $md5)) {
            return true;
        } else {
            return false;
        }
    }
}

function yonkFormatsDetails($formatid = 0) {
    $results = array();
    if ($formatid == 0)
        $sql = "SELECT *, md5(concat(`id`, '" . API_URL . "', 'formats')) as `key` FROM `" . $GLOBALS['APIDB']->prefix('formats') . '` WHERE 1 = 1 ORDER BY `extension` ASC';
    elseif ($formatid != 0 && is_numeric($formatid) && !is_string($formatid))
        $sql = "SELECT *, md5(concat(`id`, '" . API_URL . "', 'formats')) as `key` FROM `" . $GLOBALS['APIDB']->prefix('formats') . '` WHERE `id` = ' . $formatid . ' ORDER BY `extension` ASC';
    elseif (validateMD5($formatid))
        $sql = "SELECT *, md5(concat(`id`, '" . API_URL . "', 'formats')) as `key` FROM `" . $GLOBALS['APIDB']->prefix('formats') . '` WHERE md5(concat(`id`, "' . API_URL . '", "formats")) LIKE "' . $formatid . '" ORDER BY `extension` ASC';
    $result = $GLOBALS['APIDB']->queryF($sql);
    while($row = $GLOBALS['APIDB']->fetchArray($result))
    {
        $sql = "SELECT *, md5(concat(`id`, '" . API_URL . "', 'originals')) as `key`, concat(`major`, '.', `minor`, '.', `revision`, '.', `subrevision`) as `version` FROM `" . $GLOBALS['APIDB']->prefix('originals') . '` WHERE `format-id` = "' . $row['id'] . '"';
        $resultb = $GLOBALS['APIDB']->queryF($sql);
        while($original = $GLOBALS['APIDB']->fetchArray($resultb))
            $row['original-keys'][nef($original['unixname'].'--'.$row['version'])] = $original['key'];
        unset($row['id']);
        $results[nef($row['extension'])][nef($row['title'])] = $row;
    }
    return $results;
}


function yonkCollectivesDetails($collectionid = 0) {
    $results = array();
    if ($collectionid == 0)
        $sql = "SELECT *, md5(concat(`id`, '" . API_URL . "', 'collections')) as `key` FROM `" . $GLOBALS['APIDB']->prefix('collections') . '` WHERE 1 = 1 ORDER BY `created` ASC';
    elseif ($collectionid != 0 && is_numeric($collectionid) && !is_string($collectionid))
        $sql = "SELECT *, md5(concat(`id`, '" . API_URL . "', 'collections')) as `key` FROM `" . $GLOBALS['APIDB']->prefix('collections') . '` WHERE `id` = ' . $collectionid . ' ORDER BY `extension` ASC';
    elseif (validateMD5($collectionid))
        $sql = "SELECT *, md5(concat(`id`, '" . API_URL . "', 'collections')) as `key` FROM `" . $GLOBALS['APIDB']->prefix('collections') . '` WHERE md5(concat(`id`, "' . API_URL . '", "collections")) LIKE "' . $collectionid . '" ORDER BY `extension` ASC';
    
    $result = $GLOBALS['APIDB']->queryF($sql);
    while($row = $GLOBALS['APIDB']->fetchArray($result))
    {
        unset($row['id']);
        if ($row['pid']<>0)
            $row['parent-key'] = md5($row['pid'] . API_URL . 'collections');
        unset($row['pid']);
        foreach(unserialize($row['oids']) as $indx => $oid) {
            $row['original-keys'][] = md5($oid . API_URL . 'originals');
        }
        unset($row['oids']);
        foreach(array_merge(array($row['uid']), unserialize($row['uids'])) as $indx => $uid)
            $row['user-keys'][] = md5($uid . API_URL . 'users');
        $row['user-keys'] = array_unique($row['user-keys']);
        unset($row['uids']);
        unset($row['uid']);
        $row['email'] = checkEmail($row['email'], true);
        unset($row['emailings']);
        $results[$row['unixname']][nef($row['title'])] = $row;
    }
    return $results;
}

function yonkListingDetails($ip, $mode, $session, $output)
{
    switch ($mode)
    {
        case "unixnames":
            if ($session = 'listing')
            {
                $results = array();
                $sql = "SELECT DISTINCT `unixname` FROM `" . $GLOBALS['APIDB']->prefix('originals') . "` WHERE 1 = 1 GROUP BY `unixname`";
                $result = $GLOBALS['APIDB']->queryF($sql);
                while($row = $GLOBALS['APIDB']->fetchArray($result))
                    $results[$row['unixname']] = yonkUnixnameAllVersions($row['unixname']);
                return $results;
            }
            break;

        case "users":
            if ($session = 'listing')
            {
                $results = array();
                $sql = "SELECT DISTINCT md5(concat(`uid`, '" . API_URL . "', 'users')) as `key`, `uname`, `name` FROM `" . $GLOBALS['APIDB']->prefix('users') . "` WHERE 1 = 1 GROUP BY `uid`";
                $result = $GLOBALS['APIDB']->queryF($sql);
                while($row = $GLOBALS['APIDB']->fetchArray($result))
                    $results[$row['uname']][sef($row['name'])] = yonkUserDetails($row['key']);
                return $results;
            }
            break;
            
        case "formats":
            if ($session = 'listing')
            {
                return yonkFormatsDetails();
            }
            break;
            
        case "collectives":
            if ($session = 'listing')
            {
                return yonkCollectivesDetails();
            }
            break;
            
        case "unixname":
            
            return yonkUnixnameAllVersions($session);
            break;
            
        case "user":
            
            return yonkUserDetails($session);
            break;
            
        case "collection":
            
            return yonkCollectivesDetails($session);
            break;
            
        case "format":
            
            return yonkFormatsDetails($session);
            break;
            
    }
}

function yonkUnixnameLatestVersion($unixname = '')
{
    $sql = "SELECT `major`, `minor`, `revision`, `subrevision` FROM `" . $GLOBALS['APIDB']->prefix('originals') . '` WHERE `unixname` LIKE "' . $unixname . '" ORDER BY `major` DESC, `minor` DESC, `revision` DESC, `subrevision` DESC LIMIT 1';
    list($major, $minor, $revision, $subrevision) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
    return "$major.$minor.$revision.$subrevision";
}

function yonkUnixnameEarliestVersion($unixname = '')
{
    $sql = "SELECT `major`, `minor`, `revision`, `subrevision` FROM `" . $GLOBALS['APIDB']->prefix('originals') . '` WHERE `unixname` LIKE "' . $unixname . '" ORDER BY `major` ASC, `minor` ASC, `revision` ASC, `subrevision` ASC LIMIT 1';
    list($major, $minor, $revision, $subrevision) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
    return "$major.$minor.$revision.$subrevision";
}

function yonkUnixnameRandomVersion($unixname = '')
{
    $sql = "SELECT `major`, `minor`, `revision`, `subrevision` FROM `" . $GLOBALS['APIDB']->prefix('originals') . '` WHERE `unixname` LIKE "' . $unixname . '" ORDER BY RAND() LIMIT 1';
    list($major, $minor, $revision, $subrevision) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
    return "$major.$minor.$revision.$subrevision";
}

function yonkUserURL($uid = 0, $mode = 'profile')
{

    
    if (!empty($md5))
    switch ($mode)
    {
        default:
            $sql = "SELECT md5(concat(`uid`,`email`,`api_regdate`)) as `md5` FROM `" . $GLOBALS['APIDB']->prefix('users') . '` WHERE `uid` = "' . $uid . '"';
            list($md5) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
            $result = API_URL . '/v1/users/' . $md5 . '/' . $mode . '.html';
            break;
        case 'edit':
            $sql = "SELECT md5(concat(`uid`,`uname`,`email`,`api_regdate`,`actkey`,`pass`)) as `md5` FROM `" . $GLOBALS['APIDB']->prefix('users') . '` WHERE `uid` = "' . $uid . '"';
            list($md5) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
            $result = API_URL . '/v1/users/' . $md5 . '/' . $mode . '.html';
            break;
            
    }
        
    return $result;
}

function yonkRandomUserhash($uids = array(), $not = false)
{
    $in = "";
    if (!empty($uids) && count($uids))
    {
        $in = " WHERE `uid` " . ($not==true?' NOT ':'') . 'IN (' . implode(', ', $uids) .") ";
    }
    
    $sql = "SELECT md5(concat(`uid`,'" . API_URL . "','users')) as `md5` FROM `" . $GLOBALS['APIDB']->prefix('users') . '` $in ORDER BY RAND() LIMIT 1';
    list($md5) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
    if ($md5!=md5() && $md5!=md5(NULL))
        $hashes[] = $md5;
    return $hashes[mt_rand(0, count($hashes) - 1)];
}


function yonkRandomCollectionhash($ids = array(), $not = false)
{
    $in = "";
    if (!empty($ids) && count($ids))
    {
        $in = " WHERE `id` " . ($not==true?' NOT ':'') . 'IN (' . implode(', ', $ids) .") ";
    }
    
    $sql = "SELECT md5(concat(`id`,'" . API_URL . "','collections')) as `md5` FROM `" . $GLOBALS['APIDB']->prefix('collections') . '` $in ORDER BY RAND() LIMIT 1';
    list($md5) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
    if ($md5!=md5() && $md5!=md5(NULL))
        $hashes[] = $md5;
    return $hashes[mt_rand(0, count($hashes) - 1)];
}


function yonkRandomFormathash($uids = array(), $not = false)
{
    $in = "";
    if (!empty($ids) && count($ids))
    {
        $in = " WHERE `id` " . ($not==true?' NOT ':'') . 'IN (' . implode(', ', $ids) .") ";
    }
    
    $sql = "SELECT md5(concat(`id`,'" . API_URL . "','formats')) as `md5` FROM `" . $GLOBALS['APIDB']->prefix('formats') . '` $in ORDER BY RAND() LIMIT 1';
    list($md5) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
    if ($md5!=md5() && $md5!=md5(NULL))
        $hashes[] = $md5;
        return $hashes[mt_rand(0, count($hashes) - 1)];
}

function yonkRandomUnixname()
{
    $sql = "SELECT `unixname` FROM `" . $GLOBALS['APIDB']->prefix('originals') . '` ORDER BY RAND() LIMIT 1';
    list($unixname) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
    return $unixname;
}


function yonkRandomColour()
{
    $colour  = '';
    while(strlen($colour)<6)
    {
        $colour .= mt_rand(0,1)==1?chr(mt_rand(ord('a'), ord('f'))):chr(mt_rand(ord('0'), ord('9')));
    }
    return $colour;
}


/**
 * xml_encode()
 * Encodes XML with DOMDocument Objectivity
 *
 * @param mixed $mixed					Mixed Data
 * @param object $domElement			DOM Element
 * @param object $DOMDocument			DOM Document Object
 * @return array
 */

if (!function_exists("yonkHTMLForm")) {

    function yonkHTMLForm($mode = '', $clause = '', $callback = '', $output = '', $version = 'v2')
    {
        if (empty($clause))
            $clause = substr(sha1($_SERVER['HTTP_USER_AGENT']), mt_rand(0,32), 9);
        
        $form = array();
        switch ($mode)
        {
            case "upload":
                $form[] = "<form name=\"" . $clause . "\" method=\"POST\" enctype=\"multipart/form-data\" action=\"" . API_URL . '/v2/post/' .$clause . "/form.api\">";
                $form[] = "\t<table class='image-upload' id='image-upload' style='vertical-align: top !important; min-width: 98%;'>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td style='width: 41.69% !important;'>";
                $form[] = "\t\t\t\t<label for='unixname'>Unique Unix-name for Image Referer:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td style='padding: 9px'>";
                $form[] = "\t\t\t\t<input type='textbox' name='unixname' id='unixname' maxlen='255' size='22' value='' /><br/>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>&nbsp;</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t<label for='versioning'>Icon Versioning:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td colspan='2'>";
                $form[] = "\t\t\t\t<label for='major'>Major:&nbsp</label><input type='textbox' name='major' id='major' maxlen='4' size='4' value='1' />&nbsp;";
                $form[] = "\t\t\t\t<label for='minor'>Minor:&nbsp</label><input type='textbox' name='minor' id='minor' maxlen='4' size='4' value='0' /><br />";
                $form[] = "\t\t\t\t<label for='revision'>Revision:&nbsp</label><input type='textbox' name='revision' id='revision' maxlen='4' size='4' value='0' />&nbsp;";
                $form[] = "\t\t\t\t<label for='subrevision'>Sub-revision:&nbsp</label><input type='textbox' name='subrevision' id='subrevision' maxlen='4' size='4' value='0' />";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t<label for='organisation'>Icon Organisation Name:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td style='padding: 9px'>";
                $form[] = "\t\t\t\t<input type='textbox' name='organisation' id='organisation' maxlen='255' size='22' value='' /><br/>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>&nbsp;</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t<label for='name'>Uploaders' Name:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td style='padding: 9px'>";
                $form[] = "\t\t\t\t<input type='textbox' name='name' id='name' maxlen='255' size='22' value='' /><br/>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>&nbsp;</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t<label for='username'>Uploaders' Username (here on api):&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td style='padding: 9px'>";
                $form[] = "\t\t\t\t<input type='textbox' name='username' id='username' maxlen='20' size='22' value='' /><br/>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>&nbsp;</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t<label for='email'>Uploaders' Email (here on api):&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td style='padding: 9px'>";
                $form[] = "\t\t\t\t<input type='textbox' name='email' id='email' maxlen='20' size='22' value='' /><br/>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>&nbsp;</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t<label for='password'>Uploaders' Password (here on api):&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td style='padding: 9px'>";
                $form[] = "\t\t\t\t<input type='password' name='password' id='password' size='22' value='' /><br/>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>&nbsp;</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t<label for='confirm'>Uploaders' Confirmation of Password (here on api):&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td style='padding: 9px'>";
                $form[] = "\t\t\t\t<input type='password' name='confirm' id='confirm' size='22' value='' /><br/>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>&nbsp;</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t<label for='emailings'>Email Address' of anyone else able to administer via email this icon:<br/><br/><span style='font-size: 41%; font-weight: 900;'>(Seperated by common [,] or semi-colon [;] in any of the following formats: someone@example.com, \"Simon Xaies\" &lt;simonxaies@example.com&gt;, Penny Xaies &lt;pennyxaies@example.com&gt;)</span></label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td colspan='2' style='padding: 9px'>";
                $form[] = "\t\t\t\t<textarea name='emailings' id='emailings' cols='21' rows='11'></textarea>&nbsp;&nbsp;";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td>&nbsp;</td>";
                $form[] = "\t\t\t<td colspan='2'>";
                $form[] = "\t\t\t\t<label for='logo'>Image Icon:</label>";
                $form[] = "\t\t\t\t<input type='file' name='image' id='image'><br/>";
                $form[] = "\t\t\t\t<div style='margin-left:42px; font-size: 71.99%; margin-top: 7px; padding: 11px;'>";
                $form[] = "\t\t\t\t\t ~~ <strong>Maximum Upload Size Is: <em style='color:rgb(255,100,123); font-weight: bold; font-size: 132.6502%;'>" . ini_get('upload_max_filesize') . "!!!</em></strong><br/>";
                $form[] = "\t\t\t\t\t ~~ <strong>Image Dimensional Constraints: <em style='color:rgb(255,100,123); font-weight: bold; font-size: 132.6502%;'>Same width x height!!!</em></strong><br/>";
                $form[] = "\t\t\t\t\t ~~ <strong>Minimal Image Dimensional Allowed: <em style='color:rgb(255,100,123); font-weight: bold; font-size: 132.6502%;'>".API_MINIMUM_WIDTH."x".API_MINIMUM_WIDTH."!!!</em></strong><br/>";
                $form[] = "\t\t\t\t</div>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t<label for='formats'>Image File Formats Supported:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td style='padding: 9px'>";
                $sql = "SELECT `extension`, `title` FROM `" . $GLOBALS['APIDB']->prefix('formats') . "` ORDER BY `extension` ASC";
                $results = $GLOBALS['APIDB']->queryF($sql);
                while($row = $GLOBALS['APIDB']->fetchArray($results))
                    $form[] = "\t\t\t\t<span style='font-size: 45%; padding: 3px; margin-left: 6px; margin-right: 6px; margin-bottom: 5px; float: left;' alt='*." . $row['extension'] . " ~ " . $row['title'] . "' title='*." . $row['extension'] . " ~ " . $row['title'] . "'>*." . $row['extension'] . "</span>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>&nbsp;</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t\t<td colspan='3' style='padding-left:64px;'>";
                $form[] = "\t\t\t\t<input type='hidden' name='return' value='" . API_URL ."'>";
                $form[] = "\t\t\t\t<input type='hidden' name='session' value='" . (empty($clause)?'':$clause) ."'>";
                $form[] = "\t\t\t\t<input type='submit' value='Do Image Upload' name='submit' style='padding:11px; font-size:122%;'>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td colspan='3' style='padding-top: 8px; padding-bottom: 14px; padding-right:35px; text-align: right;'>";
                $form[] = "\t\t\t\t<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold;'>* </font><font  style='color: rgb(10,10,10); font-size: 99%; font-weight: bold'><em style='font-size: 76%'>~ Required Field for Form Submission</em></font>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t</table>";
                $form[] = "</form>";
                break;
        }
        return implode("\n", $form);
    }
}

function checkEmail($email, $antispam = false)
{
    if (!$email || !preg_match('/^[^@]{1,64}@[^@]{1,255}$/', $email)) {
        return false;
    }
    $email_array      = explode('@', $email);
    $local_array      = explode('.', $email_array[0]);
    $local_arrayCount = count($local_array);
    for ($i = 0; $i < $local_arrayCount; ++$i) {
        if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/\=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/\=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
            return false;
        }
    }
    if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) {
        $domain_array = explode('.', $email_array[1]);
        if (count($domain_array) < 2) {
            return false; // Not enough parts to domain
        }
        for ($i = 0; $i < count($domain_array); ++$i) {
            if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
                return false;
            }
        }
    }
    if ($antispam) {
        $email = str_replace('@', ' at ', $email);
        $email = str_replace('.', ' dot ', $email);
    }
    
    return $email;
}


if (!function_exists('nef'))
{
    
    function nef($subject = '', $stripe ='-')
    {
        $replacements = array("one" => "1", "two" => "2", "three" => "3", "four" => "4", "five" => "5", "six" => "6", "seven" => "7", "eight" => "8", "nine" => "9", "zero" => "0");
        foreach($replacements as $replace => $search)
            $subject = str_replace($search, $replace, $subject);
        return sef($subject, $stripe);
    }
}

if (!function_exists('sef'))
{

    function sef($value = '', $stripe ='-')
    {
        return yonkOnlyAlphanumeric($value, $stripe);
    }
}


if (!function_exists('yonkOnlyAlphanumeric'))
{

    function yonkOnlyAlphanumeric($value = '', $stripe ='-')
    {
        $replacement_chars = array();
        $accepted = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","m","o","p","q",
            "r","s","t","u","v","w","x","y","z","0","9","8","7","6","5","4","3","2","1");
        for($i=0;$i<256;$i++){
            if (!in_array(strtolower(chr($i)),$accepted))
                $replacement_chars[] = chr($i);
        }
        $result = trim(str_replace($replacement_chars, $stripe, strtolower($value)));
        while(strpos($result, $stripe.$stripe, 0))
            $result = (str_replace($stripe.$stripe, $stripe, $result));
        while(substr($result, 0, strlen($stripe)) == $stripe)
            $result = substr($result, strlen($stripe), strlen($result) - strlen($stripe));
        while(substr($result, strlen($result) - strlen($stripe), strlen($stripe)) == $stripe)
            $result = substr($result, 0, strlen($result) - strlen($stripe));
        return($result);
    }
}

function yonkImageInfoArray($file = '')
{
    $output = $results = array();
    exec(API_MAGICK_IDENTIFY . " -verbose '$file'", $output, $return);
    foreach($output as $key => $value)
    {
        if (substr($value,0,8)=="        ")
            $level = 4;
        elseif (substr($value,0,6)=="      ")
            $level = 3;
        elseif (substr($value,0,4)=="    ")
            $level = 2;
        elseif (substr($value,0,2)=="  ")
            $level = 1;
        else 
            $level = 0;
        $value=str_replace(": ", ":", trim($value));
        $parts = explode(":", $value);
        if (count($parts)>1)
        {
            $data = $parts[count($parts)-1];
            unset($parts[count($parts)-1]);
            if (is_file($data))
                $data = basename($data);
        } else 
            $data = NULL;
        $variable = $parts[0];
        unset($parts[0]);
        $variable .= (count($parts)>0?implode('-',$parts):"");
        str_replace(" ", "-", strtolower($variable));
        switch ("$level")
        {
            case "0":
            default:
                $zero = $variable;
                break;
            case "1":
                $one = $variable;
                break;
            case "2":
                $two = $variable;
                break;
            case "3":
                $three = $variable;
                break;
            case "4":
                $four = $variable;
                break;
        }
        switch ($variable)
        {
            default:
                if (!is_null($data))
                    switch ("$level")
                    {
                        case "0":
                        default:
                            $results[str_replace(" ", "-", strtolower("$zero"))] = $data;
                            break;
                        case "1":
                            $results[str_replace(" ", "-", strtolower("$zero-$one"))] = $data;
                            break;
                        case "2":
                            $results[str_replace(" ", "-", strtolower("$zero-$one-$two"))] = $data;
                            break;
                        case "3":
                            $results[str_replace(" ", "-", strtolower("$zero-$one-$two-$three"))] = $data;
                            break;
                        case "4":
                            $results[str_replace(" ", "-", strtolower("$zero-$one-$two-$three-$four"))] = $data;
                            break;
                    }
                break;
            case "image-geometry":
                $parts = explode("+",$data);
                $parts = explode("x",$parts[0]);
                $results['width'] = $parts[0];
                $results['height'] = $parts[1];
                switch ("$level")
                {
                    case "0":
                    default:
                        $results["$zero"] = $data;
                        break;
                    case "1":
                        $results["$zero-$one"] = $data;
                        break;
                    case "2":
                        $results["$zero-$one-$two"] = $data;
                        break;
                    case "3":
                        $results["$zero-$one-$two-$three"] = $data;
                        break;
                    case "4":
                        $results["$zero-$one-$two-$three-$four"] = $data;
                        break;
                }
                break;
        }
    }
    $fields = array();
    $sql = "SHOW FIELDS FROM `".$GLOBALS["APIDB"]->prefix('originals') . "`";
    $rst = $GLOBALS["APIDB"]->queryF($sql);
    while($row = $GLOBALS["APIDB"]->fetchArray($rst))
        $fields[$row['Field']] = $row['Field'];
    foreach($results as $key => $value)
        if (!in_array($key, $fields))
            unset($results[$key]);
    return $results;
}

if (!function_exists("getURIData")) {
    
    /* function yonkURIData()
     *
     * 	Get a supporting domain system for the API
     * @author 		Simon Roberts (Chronolabs) simon@labs.coop
     *
     * @return 		float()
     */
    function getURIData($uri = '', $timeout = 25, $connectout = 25, $post = array(), $headers = array())
    {
        if (!function_exists("curl_init"))
        {
            die("Install PHP Curl Extension ie: $ sudo apt-get install php-curl -y");
        }
        $GLOBALS['php-curl'][md5($uri)] = array();
        if (!$btt = curl_init($uri)) {
            return false;
        }
        if (count($post)==0 || empty($post))
            curl_setopt($btt, CURLOPT_POST, false);
            else {
                $uploadfile = false;
                foreach($post as $field => $value)
                    if (substr($value , 0, 1) == '@' && !file_exists(substr($value , 1, strlen($value) - 1)))
                        unset($post[$field]);
                        else
                            $uploadfile = true;
                            curl_setopt($btt, CURLOPT_POST, true);
                            curl_setopt($btt, CURLOPT_POSTFIELDS, http_build_query($post));
                            
                            if (!empty($headers))
                                foreach($headers as $key => $value)
                                    if ($uploadfile==true && substr($value, 0, strlen('Content-Type:')) == 'Content-Type:')
                                        unset($headers[$key]);
                                        if ($uploadfile==true)
                                            $headers[]  = 'Content-Type: multipart/form-data';
            }
            if (count($headers)==0 || empty($headers)) {
                curl_setopt($btt, CURLOPT_HEADER, false);
                curl_setopt($btt, CURLOPT_HTTPHEADER, array());
            } else {
                curl_setopt($btt, CURLOPT_HEADER, false);
                curl_setopt($btt, CURLOPT_HTTPHEADER, $headers);
            }
            curl_setopt($btt, CURLOPT_CONNECTTIMEOUT, $connectout);
            curl_setopt($btt, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($btt, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($btt, CURLOPT_VERBOSE, false);
            curl_setopt($btt, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($btt, CURLOPT_SSL_VERIFYPEER, false);
            $data = curl_exec($btt);
            $GLOBALS['php-curl'][md5($uri)]['http']['uri'] = $uri;
            $GLOBALS['php-curl'][md5($uri)]['http']['posts'] = $post;
            $GLOBALS['php-curl'][md5($uri)]['http']['headers'] = $headers;
            $GLOBALS['php-curl'][md5($uri)]['http']['code'] = curl_getinfo($btt, CURLINFO_HTTP_CODE);
            $GLOBALS['php-curl'][md5($uri)]['header']['size'] = curl_getinfo($btt, CURLINFO_HEADER_SIZE);
            $GLOBALS['php-curl'][md5($uri)]['header']['value'] = curl_getinfo($btt, CURLINFO_HEADER_OUT);
            $GLOBALS['php-curl'][md5($uri)]['size']['download'] = curl_getinfo($btt, CURLINFO_SIZE_DOWNLOAD);
            $GLOBALS['php-curl'][md5($uri)]['size']['upload'] = curl_getinfo($btt, CURLINFO_SIZE_UPLOAD);
            $GLOBALS['php-curl'][md5($uri)]['content']['length']['download'] = curl_getinfo($btt, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
            $GLOBALS['php-curl'][md5($uri)]['content']['length']['upload'] = curl_getinfo($btt, CURLINFO_CONTENT_LENGTH_UPLOAD);
            $GLOBALS['php-curl'][md5($uri)]['content']['type'] = curl_getinfo($btt, CURLINFO_CONTENT_TYPE);
            curl_close($btt);
            return $data;
    }
}

function yonkImageFormats()
{
    exec(API_MAGICK_IDENTIFY . ' -list format', $results);
    unset($results[0]);
    unset($results[1]);
    unset($results[count($results)-1]);
    unset($results[count($results)-1]);
    unset($results[count($results)-1]);
    unset($results[count($results)-1]);
    unset($results[count($results)-1]);
    $formats = array();
    foreach($results as $id => $value)
    {
        while(strpos(" $value", "\t") || strpos(" $value", "  ") || strpos(" $value", "*|") || strpos(" $value", "* "))
            $value = str_replace(array("\t", "  ","*|","* "),"|",$value);
        while(strpos(" $value", "||"))
            $value = str_replace("||","|",$value);
        while(strpos(" $value", "|"))
            $value = str_replace("|"," ",$value);
        $parts = explode(" ", $value);
        $parts = array_unique($parts);
        $extension = $title = '';
        $skip = 0;
        foreach($parts as $id => $value) {
            if (!empty($value) && empty($extension)) {
                $extension = strtolower($value);
            } elseif (!empty($value) && !empty($extension) && $skip < 2)
            {
                $skip++;
                if (substr($value, 0, 1) == 'r' || substr($value, 0, 1) == 'w')
                    $skip = 3;
            } elseif(!empty($value) && $skip >= 2)
            {
                $title .= " $value";
            }
        }
        if (!empty($extension) && !empty($title))
            $formats[strtolower($extension)] = trim($title);
    }
    unset($formats['json']);
    unset($formats['thumbnail']);
    unset($formats['htm']);
    unset($formats['html']);
    unset($formats['http']);
    unset($formats['https']);
    unset($formats['ftp']);
    unset($formats['ftps']);
    unset($formats['specified']);
    unset($formats['canvas']);
    unset($formats['caption']);
    unset($formats['(dicom)",']);
    unset($formats['see']);
    unset($formats['and']);
    unset($formats['they']);
    unset($formats['resized']);
    unset($formats['gradient']);
    unset($formats['histogram']);
    unset($formats['info']);
    unset($formats['label']);
    unset($formats['null']);
    unset($formats['preview']);
    unset($formats['w']);
    unset($formats['+']);
    return $formats;
}
/**
 * validateMD5()
 * Validates an MD5 Checksum
 *
 * @param string $email
 * @return boolean
 */
function validateMD5($md5) {
    if(preg_match("/^[a-f0-9]{32}$/i", $md5)) {
        return true;
    } else {
        return false;
    }
}

/**
 * validateEmail()
 * Validates an Email Address
 *
 * @param string $email
 * @return boolean
 */
function validateEmail($email) {
    if(preg_match("^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.([0-9]{1,3})|([a-zA-Z]{2,3})|(aero|coop|info|mobi|asia|museum|name))$", $email)) {
        return true;
    } else {
        return false;
    }
}

/**
 * validateDomain()
 * Validates a Domain Name
 *
 * @param string $domain
 * @return boolean
 */
function validateDomain($domain) {
    if(!preg_match("/^([-a-z0-9]{2,100})\.([a-z\.]{2,8})$/i", $domain)) {
        return false;
    }
    return $domain;
}

/**
 * validateIPv4()
 * Validates and IPv6 Address
 *
 * @param string $ip
 * @return boolean
 */
function validateIPv4($ip) {
    if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE) === FALSE) // returns IP is valid
    {
        return false;
    } else {
        return true;
    }
}

/**
 * validateIPv6()
 * Validates and IPv6 Address
 *
 * @param string $ip
 * @return boolean
 */
function validateIPv6($ip) {
    if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === FALSE) // returns IP is valid
    {
        return false;
    } else {
        return true;
    }
}

if (!function_exists("whitelistYonkIP")) {

	/* function whitelistYonkIP()
	 *
	* 	provides an associative array of whitelisted IP Addresses
	* @author 		Simon Roberts (Chronolabs) simon@labs.coop
	*
	* @return 		array
	*/
	function whitelistYonkIP() {
		return array_merge(whitelistyonkNetBIOSIP(), file(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'whitelist.txt'));
	}
}

if (!function_exists("whitelistYonkNetBIOSIP")) {

	/* function whitelistyonkNetBIOSIP()
	 *
	* 	provides an associative array of whitelisted IP Addresses base on TLD and NetBIOS Addresses
	* @author 		Simon Roberts (Chronolabs) simon@labs.coop
	*
	* @return 		array
	*/
	function whitelistYonkNetBIOSIP() {
		$ret = array();
		foreach(file(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'whitelist-domains.txt') as $domain) {
			$ip = gethostbyname($domain);
			$ret[$ip] = $ip;
		}
		return $ret;
	}
}

if (!function_exists("whitelistYonkIP")) {

	/* function whitelistyonkIP()
	 *
	* 	yonk the True IPv4/IPv6 address of the client using the API
	* @author 		Simon Roberts (Chronolabs) simon@labs.coop
	*
	* @param		$asString	boolean		Whether to return an address or network long integer
	*
	* @return 		mixed
	*/
	function whitelistyonkIP($asString = true){
		// yonks the proxy ip sent by the user
		$proxy_ip = '';
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$proxy_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else
		if (!empty($_SERVER['HTTP_X_FORWARDED'])) {
			$proxy_ip = $_SERVER['HTTP_X_FORWARDED'];
		} else
		if (! empty($_SERVER['HTTP_FORWARDED_FOR'])) {
			$proxy_ip = $_SERVER['HTTP_FORWARDED_FOR'];
		} else
		if (!empty($_SERVER['HTTP_FORWARDED'])) {
			$proxy_ip = $_SERVER['HTTP_FORWARDED'];
		} else
		if (!empty($_SERVER['HTTP_VIA'])) {
			$proxy_ip = $_SERVER['HTTP_VIA'];
		} else
		if (!empty($_SERVER['HTTP_X_COMING_FROM'])) {
			$proxy_ip = $_SERVER['HTTP_X_COMING_FROM'];
		} else
		if (!empty($_SERVER['HTTP_COMING_FROM'])) {
			$proxy_ip = $_SERVER['HTTP_COMING_FROM'];
		}
		if (!empty($proxy_ip) && $is_ip = preg_match('/^([0-9]{1,3}.){3,3}[0-9]{1,3}/', $proxy_ip, $regs) && count($regs) > 0)  {
			$the_IP = $regs[0];
		} else {
			$the_IP = $_SERVER['REMOTE_ADDR'];
		}
			
		$the_IP = ($asString) ? $the_IP : ip2long($the_IP);
		return $the_IP;
	}
}



if (!class_exists("XmlDomConstruct")) {

	class XmlDomConstruct extends DOMDocument {

		public function fromMixed($mixed, DOMElement $domElement = null) {

			$domElement = is_null($domElement) ? $this : $domElement;

			if (is_array($mixed)) {
				foreach( $mixed as $index => $mixedElement ) {

					if ( is_int($index) ) {
						if ( $index == 0 ) {
							$node = $domElement;
						} else {
							$node = $this->createElement($domElement->tagName);
							$domElement->parentNode->appendChild($node);
						}
					}

					else {
						$node = $this->createElement($index);
						$domElement->appendChild($node);
					}

					$this->fromMixed($mixedElement, $node);

				}
			} else {
				$domElement->appendChild($this->createTextNode($mixed));
			}

		}
			
	}
}

?>