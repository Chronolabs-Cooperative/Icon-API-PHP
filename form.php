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
	if ((!isset($inner['mode']) || empty($inner['mode'])) && (!isset($inner['ip']) || empty($inner['ip']))) {
		$help=true;
	} elseif (isset($inner['output']) || !empty($inner['output'])) {
		$mode = trim($inner['mode']);
		$session = trim($inner['session']);
		$output = trim($inner['output']);
	} else {
		$help=true;
	}
	
	if ($help==true) {
		header("Location: " . API_URL);
		exit;
	}
	
	if (function_exists("http_response_code"))
		http_response_code(200);
	
    switch($mode)
    {
        case 'html':
            echo $form = yonkHTMLForm('upload', $session);
            break;
        case 'post':
            if (empty($inner['unixname']))
            {
                http_response_code(501);
                die("<h1>error missing variable</h1><p>The Variable _POST['unixname'] should contain a string that is unique as a referee to this icon!</p>");
            }
            $unixname = strtolower($inner['unixname']);
            $unixname = str_replace(array('"', "'", '~', '`', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '_'. '-', '+', '=', '{', '{', '}', ']', '|', '\\', ';', ':', '<', ',', '.', '>', '?', '/', ' '), '-', $unixname);
            while(strpos($unixname, '--'))
                $unixname = str_replace('--', '-', $unixname);

            $sql = "SELECT count(*) FROM `" . $GLOBALS['APIDB']->prefix('originals') . "` WHERE `unixname` LIKE '" . $unixname . "'";
            list($count) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
            if ($count>0)
            {
                http_response_code(501);
                die("<h1>none unique variable</h1><p>The Variable _POST['unixname'] should contain a string that is unique as a referee to this icon!</p>");
            }
            if (empty($inner['organisation']))
            {
                http_response_code(501);
                die("<h1>error missing variable</h1><p>The Variable _POST['organisation'] need to be populated with the icon organisation on this api!</p>");
            }
            if (empty($inner['name']))
            {
                http_response_code(501);
                die("<h1>error missing variable</h1><p>The Variable _POST['name'] need to be populated with the icon uploader individual name on this api!</p>");
            }
            if (empty($inner['email']))
            {
                http_response_code(501);
                die("<h1>error missing variable</h1><p>The Variable _POST['email'] need to be populated with the icon uploader individual valid email address on this api!</p>");
            }
            if (empty($inner['username']))
            {
                http_response_code(501);
                die("<h1>error missing variable</h1><p>The Variable _POST['username'] need to be populated with the icon uploader individual valid username on this api!</p>");
            }
            if (empty($inner['password']))
            {
                http_response_code(501);
                die("<h1>error missing variable</h1><p>The Variable _POST['password'] need to be populated with the icon uploader individual password on this api or a new password for a new user!</p>");
            }
            if (empty($inner['confirm']))
            {
                http_response_code(501);
                die("<h1>error missing variable</h1><p>The Variable _POST['confirm'] need to be populated with the icon uploader individual password on this api or a new password for a new user!</p>");
            }
            if ($inner['password']!=$inner['confirm'])
            {
                http_response_code(501);
                die("<h1>mismatched variable</h1><p>The Variable _POST['confirm'] need to be identical to _POST['password'] and they don't match with the password!</p>");
            }
            if (empty($inner['return']))
            {
                http_response_code(501);
                die("<h1>error missing variable</h1><p>The Variable _POST['return'] should contain a URL that this function returns the post from!</p>");
            }
            if (empty($inner['session']) && strlen($inner['session'])>128)
            {
                http_response_code(501);
                die("<h1>error missing variable</h1><p>The Variable _POST['session'] This should contain a session identified key or hash that is no more than 128 characters!</p>");
            }
            $uids = array();
            $sql = "SELECT count(*) FROM `" . $GLOBALS['APIDB']->prefix('users') . "` WHERE `email` LIKE '" . $inner['email'] . "'";
            list($count) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
            if ($count>0) {
                $sql = "SELECT `uid`, `uname`, `pass`, `email` FROM `" . $GLOBALS['APIDB']->prefix('users') . "` WHERE `email` LIKE '" . $inner['email'] . "'";
                list($uid, $username, $pass, $email) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
                if (!empty($username) && $username!=$inner['username'])
                {
                    http_response_code(501);
                    die("<h1>mismatched variable</h1><p>The Variable _POST['username'] need to be match the username associated with the email address!</p>");
                }
                if (validateMD5($inner['password']) && !empty($pass) && $inner['password']!=$pass)
                {
                    http_response_code(501);
                    die("<h1>mismatched variable</h1><p>The Variable _POST['password'] need to be match the username password or md5 of the password associated with the email address!</p>");
                } elseif (!validateMD5($inner['password']) && !empty($pass) && md5($inner['password'])!=$pass)
                {
                    http_response_code(501);
                    die("<h1>mismatched variable</h1><p>The Variable _POST['password'] need to be match the username password or md5 of the password associated with the email address!</p>");
                }
                $data['uid'] = $uid;
                if (empty($username)||empty($pass))
                    $newuser = true;
                else 
                    $newuser = false;
            } else {
                $xcp = new xcp(microtime(false), mt_rand(0,255), mt_rand(5,8));
                $actkey = $xcp->calc(API_DB_HOST . API_DB_PASS . API_DB_NAME . API_DB_PREFIX . microtime(true));
                $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('users') . "` (`name`, `uname`, `pass`, `email`, `actkey`, `api_regdate`, `api_mailok`) VALUES('".$GLOBALS['APIDB']->escape($inner['name'])."','".$GLOBALS['APIDB']->escape($inner['username'])."','" . (validateMD5($inner['password'])?$inner['password']:md5($inner['password'])) . "','".$inner['email']."','".$actkey."',UNIX_TIMESTAMP(), 1)";
                if (!$GLOBALS['APIDB']->queryF($sql))
                    die("SQL Failed: $sql;");
                $data['uid'] = $uid = $GLOBALS['APIDB']->getInsertId();
                $newuser = true;
            }
            $uids[] = $uid;
            mkdir($uploadpath = API_VAR_PATH . DS . sha1(microtime(true)), 0777, true);
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $file = $uploadpath . DS . $_FILES['image']['name'])) {
                http_response_code(501);
                die("<h1>file upload issues</h1><p>The Variable _FILE['image'] errored uploading to the server!</p>");
            }
            if (is_file($file))
            {
                $data = array_merge($data, yonkImageInfoArray($file));
                if ($data['width']>API_MINIMUM_WIDTH||$data['height']>API_MINIMUM_WIDTH)
                {
                    http_response_code(501);
                    shell_exec("rm -Rfv \"" . dirname($file) . "\"");
                    die("<h1>image file dimensional undersized</h1><p>The file '".basename($file)."' is below the minimal size set to recieve on the server the file must be square and at least the dimensions ".API_MINIMUM_WIDTH."x".API_MINIMUM_WIDTH."!</p>");
                }
                if ($data['width']!=$data['height'])
                {
                    http_response_code(501);
                    shell_exec("rm -Rfv \"" . dirname($file) . "\"");
                    die("<h1>image file dimensional not squared</h1><p>The file '".basename($file)."' is not square it has to have the same number of pixels in width + height and at least be the size of ".API_MINIMUM_WIDTH."x".API_MINIMUM_WIDTH."!</p>");
                }
                $parts = explode(".", $data['image']);
                $sql = "SELECT `id`, `extension`, `mime-type` FROM `" . $GLOBALS['APIDB']->prefix('formats') . "` WHERE `extension` LIKE '" . $parts[count($parts)-1] . "'";
                list($formatid, $extorigin, $mimetype) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
                if (empty($mimetype))
                {
                    $sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('formats') . "` SET `mime-type` = '" . $GLOBALS["APIDB"]->escape($data['image-mime-type']) . "' WHERE `id` = '" . $formatid . "'";
                    if (!$GLOBALS['APIDB']->queryF($sql))
                        die("SQL Failed: $sql;");
                }
                $data['unixname'] = $unixname;
                $data['name'] = $inner['name'];
                $data['email'] = $inner['email'];
                $data['format-id'] = $formatid;
                $data['uid'] = $uid;
                $data['bytes'] = filesize($file);
                $data['uploads'] = 1;
                $data['uploads_bytes'] = filesize($file);
                $data['created'] = time();
                $data['uploaded'] = time();
                $data['organisation'] = $inner['organisation'];
                $data['major'] = $inner['major'];
                $data['minor'] = $inner['minor'];
                $data['revision'] = $inner['revision'];
                $data['subrevision'] = $inner['subrevision'];
                if (!empty($inner['emailings']))
                {
                    $emails = mailparse_rfc822_parse_addresses($inner['emailings']);
                    foreach($emails as $id => $values)
                    {
                        $data['emailings'][md5($values['address'])]['email'] = $values['address'];
                        $data['emailings'][md5($values['address'])]['name'] = (!empty($values['display'])?$values['display']:$values['address']);
                        $sql = "SELECT count(*) FROM `" . $GLOBALS['APIDB']->prefix('users') . "` WHERE `email` LIKE '" . $values['address'] . "'";
                        list($count) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
                        if ($count>0) {
                            $sql = "SELECT `uid`, `uname`, `pass` FROM `" . $GLOBALS['APIDB']->prefix('users') . "` WHERE `email` LIKE '" . $values['address'] . "'";
                            list($uid, $uname, $pass) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
                            $data['emailings'][md5($values['address'])]['uid'] = $uid;
                            $data['uids'][md5($values['address'])]['uid'] = $uid;
                            $data['emailings'][md5($values['address'])]['new'] = (empty($uname)||empty($pass)?true:false);
                            $data['uids'][md5($values['address'])]['new'] = (empty($uname)||empty($pass)?true:false);
                        } else {
                            $xcp = new xcp(microtime(false), mt_rand(0,255), mt_rand(5,8));
                            $actkey = $xcp->calc(API_DB_HOST . API_DB_PASS . API_DB_NAME . API_DB_PREFIX . microtime(true));
                            $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('users') . "` (`name`, `email`, `actkey`, `api_regdate`, `api_mailok`) VALUES('".$GLOBALS["APIDB"]->escape(($data['emailings'][md5($values['address'])]['name']!=$data['emailings'][md5($values['address'])]['email']?$data['emailings'][md5($values['address'])]['name']:""))."','".$data['emailings'][md5($values['address'])]['email']."','".$actkey."',UNIX_TIMESTAMP(), 1)";
                            if (!$GLOBALS['APIDB']->queryF($sql))
                                die("SQL Failed: $sql;");
                            $uid = $GLOBALS['APIDB']->getInsertId();
                            $data['emailings'][md5($values['address'])]['uid'] = $uid;
                            $data['uids'][md5($values['address'])]['uid'] = $uid;
                            $data['emailings'][md5($values['address'])]['new'] = true;
                            $data['uids'][md5($values['address'])]['new'] = true;                           
                        }
                        $uids[] = $uid;
                    }
                }
            }
            // Insert Record
            $values = array();
            foreach($data as $field => $value)
                $values[$field] = (!is_array($value)?"'" . $GLOBALS["APIDB"]->escape($value)."'":"COMPRESS('" . $GLOBALS["APIDB"]->escape(json_encode($value))."')");
            $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('originals') . "` (`" . implode('`, `', array_keys($values)) . "`) VALUES(" . implode(', ', $values) . ")";
            if (!$GLOBALS['APIDB']->queryF($sql))
                die("SQL Failed: $sql;");
            $originalid = $GLOBALS['APIDB']->getInsertId();
            $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('images') . "` (`format-id`, `original-id`, `image`, `created`) VALUES('" . $formatid  . "', '" . $originalid  . "', '".$GLOBALS["APIDB"]->escape(file_get_contents($file)) . "', UNIX_TIMESTAMP())";
            if (!$GLOBALS['APIDB']->queryF($sql))
                die("SQL Failed: $sql;");
            $imageid = $GLOBALS['APIDB']->getInsertId();
            $sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('originals') . "` SET `image-id` = '" . $imageid . "' WHERE `id` = '$originalid'";
            if (!$GLOBALS['APIDB']->queryF($sql))
                die("SQL Failed: $sql;");
            foreach($uids as $uid)
            {
                $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('users_originals') . "` (`uid`, `original-id`) VALUES('" . $uid  . "', '" . $originalid  . "')";
                if (!$GLOBALS['APIDB']->queryF($sql))
                    die("SQL Failed: $sql;");
            }
            
            $caches = $attach = $attachments = array();
            chdir(dirname($file));
            shell_exec(API_MAGICK_CONVERT . " -resize 32x32 " . basename($file) . " " . ($attach[] = yonkFilename($unixname, '32', 'ico')));
            shell_exec(API_MAGICK_CONVERT . " -resize 48x48 " . basename($file) . " " . ($attach[] = yonkFilename($unixname, '48', 'ico')));
            shell_exec(API_MAGICK_CONVERT . " -resize 48x48 " . basename($file) . " " . ($attach[] = yonkFilename($unixname, '48', 'png')));
            shell_exec(API_MAGICK_CONVERT . " -resize 56x56 " . basename($file) . " " . ($attach[] = yonkFilename($unixname, '56', 'png')));
            shell_exec(API_MAGICK_CONVERT . " -resize 72x72 " . basename($file) . " " . ($attach[] = yonkFilename($unixname, '72', 'png')));
            shell_exec(API_MAGICK_CONVERT . " -resize 114x114 " . basename($file) . " " . ($attach[] = yonkFilename($unixname, '114', 'png')));
            shell_exec(API_MAGICK_CONVERT . " -resize 128x128 " . basename($file) . " " . ($attach[] = yonkFilename($unixname, '128', 'png')));
            $caches[basename($file)] = yonkImageInfoArray(dirname($file) . DS . basename($file));
            $caches[basename($file)]['data']  = file_get_contents(dirname($file) . DS . basename($file));
            
            $sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('originals') . '` SET `caching` = `caching` + 1, `caching_bytes` = `caching_bytes` + '.strlen($caches[basename($file)]['data']) . ', `cached` = UNIX_TIMESTAMP() WHERE `id` = "' . $originalid . '"';
            $GLOBALS['APIDB']->queryF($sql);
            
            foreach($attach as $filename) {
                $attachments[$filename] = file_get_contents(dirname($file) . DS . basename($filename));
                $caches[$filename] = yonkImageInfoArray(dirname($file) . DS . basename($filename));
                $caches[$filename]['data']  = file_get_contents(dirname($file) . DS . basename($filename));

                $sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('originals') . '` SET `converts` = `converts` + 1, `converts_bytes` = `converts_bytes` + '.filesize(dirname($file) . DS . basename($filename)) . ', `converted` = UNIX_TIMESTAMP() WHERE `id` = "' . $originalid . '"';
                $GLOBALS['APIDB']->queryF($sql);
                $sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('originals') . '` SET `caching` = `caching` + 1, `caching_bytes` = `caching_bytes` + '.filesize(dirname($file) . DS . basename($filename)) . ', `cached` = UNIX_TIMESTAMP() WHERE `id` = "' . $originalid . '"';
                $GLOBALS['APIDB']->queryF($sql);
            }
            
            if (!$sessions = APICache::read('cache-sessions'))
                $sessions = array();
            
            if (!isset($sessions[$unixname]['seed']) && !isset($sessions[$unixname]['caches']))
                $sessions[$unixname]['seed'] = mt_rand(0, 255);
        
            if (!isset($sessions[$unixname]['length']) && !isset($sessions[$unixname]['caches']))
                $sessions[$unixname]['length'] = mt_rand(5, 7);
            
            if (!isset($sessions[$unixname]['caches']))
                $sessions[$unixname]['caches'] = array();
                
            APICache::write('cache-sessions', $sessions, API_CACHE_SESSIONS);
            
            APICache::write($key = yonkCacheKey($unixname, $data['width'], $extorigin, 'original'), $caches[basename($file)], $seconds = API_CACHE_ORIGINAL);
            $sessions[$unixname]['caches'][$key] = time() + $seconds;
            APICache::write($key = yonkCacheKey($unixname, '32', 'ico', 'icon'), $caches[yonkFilename($unixname, '32', 'ico', 'icon')], $seconds = API_CACHE_ORIGINAL);
            $sessions[$unixname]['caches'][$key] = time() + $seconds;
            APICache::write($key = yonkCacheKey($unixname, '48', 'ico', 'icon'), $caches[yonkFilename($unixname, '48', 'ico', 'icon')], $seconds = API_CACHE_IMAGE);
            $sessions[$unixname]['caches'][$key] = time() + $seconds;
            APICache::write($key = yonkCacheKey($unixname, '48', 'png', 'icon'), $caches[yonkFilename($unixname, '48', 'png', 'icon')], $seconds = API_CACHE_IMAGE);
            $sessions[$unixname]['caches'][$key] = time() + $seconds;
            APICache::write($key = yonkCacheKey($unixname, '56', 'png', 'icon'), $caches[yonkFilename($unixname, '56', 'png', 'icon')], $seconds = API_CACHE_IMAGE);
            $sessions[$unixname]['caches'][$key] = time() + $seconds;
            APICache::write($key = yonkCacheKey($unixname, '72', 'png', 'icon'), $caches[yonkFilename($unixname, '72', 'png', 'icon')], $seconds = API_CACHE_IMAGE);
            $sessions[$unixname]['caches'][$key] = time() + $seconds;
            APICache::write($key = yonkCacheKey($unixname, '114', 'png', 'icon'), $caches[yonkFilename($unixname, '114', 'png', 'icon')], $seconds = API_CACHE_IMAGE);
            $sessions[$unixname]['caches'][$key] = time() + $seconds;
            APICache::write($key = yonkCacheKey($unixname, '128', 'png', 'icon'), $caches[yonkFilename($unixname, '128', 'png', 'icon')], $seconds = API_CACHE_IMAGE);
            $sessions[$unixname]['caches'][$key] = time() + $seconds;
            APICache::write('cache-sessions', $sessions, API_CACHE_SESSIONS);

            if ($newuser == true)
                $body = file_get_contents(__DIR__ . DS . 'include' . DS . 'mail_templates' . DS . 'new_icon_new_user.html');
            else 
                $body = file_get_contents(__DIR__ . DS . 'include' . DS . 'mail_templates' . DS . 'new_icon_existing_user.html');
    
            $body = str_replace('%licenseorg', API_LICENSE_COMPANY, $body);
            $body = str_replace('%apiurl', API_URL, $body);
            $body = str_replace('%fileformat', $data['image-format'], $body);
            $body = str_replace('%iconemail', $unixname.'@'.parse_url(API_URL, PHP_URL_HOST), $body);
            $body = str_replace('%url_user_edit', yonkUserURL($data['uid'], 'edit'), $body);
            $body = str_replace('%url_32x32_ico', yonkImageURL($unixname, '32', 'ico', 'icon'), $body);
            $body = str_replace('%file_32x32_ico', yonkFilename($unixname, '32', 'ico', 'icon'), $body);
            $body = str_replace('%url_48x48_ico', yonkImageURL($unixname, '48', 'ico', 'icon'), $body);
            $body = str_replace('%file_48x48_ico', yonkFilename($unixname, '48', 'ico', 'icon'), $body);
            $body = str_replace('%url_48x48_png', yonkImageURL($unixname, '48', 'png', 'icon'), $body);
            $body = str_replace('%file_48x48_png', yonkFilename($unixname, '56', 'png', 'icon'), $body);
            $body = str_replace('%url_56x56_png', yonkImageURL($unixname, '56', 'png', 'icon'), $body);
            $body = str_replace('%file_56x56_png', yonkFilename($unixname, '72', 'png', 'icon'), $body);
            $body = str_replace('%url_72x72_png', yonkImageURL($unixname, '72', 'png', 'icon'), $body);
            $body = str_replace('%file_114x114_png', yonkFilename($unixname, '114', 'png', 'icon'), $body);
            $body = str_replace('%url_114x114_png', yonkImageURL($unixname, '114', 'png', 'icon'), $body);
            $body = str_replace('%file_128x128_png', yonkFilename($unixname, '128', 'png', 'icon'), $body);
            $body = str_replace('%url_128x128_png', yonkImageURL($unixname, '128', 'png', 'icon'), $body);
                        
            $tos = array($data['email'] => $data['name']);
            $ccs = array();
            $bccs = array();
            
            $subject = sprintf(API_SUBJECT_NEWICON, $data['organisation']);
            
            $sql = "INSERT INTO `" . $GLOBALS["APIDB"]->prefix('emails') . "` (`format-id`, `original-id`, `image-id`, `unixname`, `tos`, `ccs`, `bccs`, `subject`, `body`, `attachments`, `created`) VALUES('" . $formatid . "','" . $originalid . "','" . $imageid . "','" . $data['unixname'] . "','" . $GLOBALS["APIDB"]->escape(json_encode($tos)) . "', '" . $GLOBALS["APIDB"]->escape(json_encode($ccs)) . "', '" . $GLOBALS["APIDB"]->escape(json_encode($bccs)) . "', '" . $GLOBALS["APIDB"]->escape($subject) . "','" . $GLOBALS["APIDB"]->escape($body) . "',COMPRESS('" . $GLOBALS["APIDB"]->escape(json_encode($attachments)) . "'),UNIX_TIMESTAMP())";
            if (!$GLOBALS['APIDB']->queryF($sql))
                die("SQL Failed: $sql;");
                    
            foreach($data['emailings'] as $md5 => $values)
            {
                if ($values['new'] == true)
                    $body = file_get_contents(__DIR__ . DS . 'include' . DS . 'mail_templates' . DS . 'new_icon_new_user.html');
                else
                    $body = file_get_contents(__DIR__ . DS . 'include' . DS . 'mail_templates' . DS . 'new_icon_existing_user.html');
                
                $body = str_replace('%licenseorg', API_LICENSE_COMPANY, $body);
                $body = str_replace('%apiurl', API_URL, $body);
                $body = str_replace('%fileformat', $data['image-format'], $body);
                $body = str_replace('%iconemail', $unixname.'@'.parse_url(API_URL, PHP_URL_HOST), $body);
                $body = str_replace('%url_user_edit', yonkUserURL($data['uid'], 'edit'), $body);
                $body = str_replace('%url_32x32_ico', yonkImageURL($unixname, '32', 'ico', 'icon'), $body);
                $body = str_replace('%file_32x32_ico', yonkFilename($unixname, '32', 'ico', 'icon'), $body);
                $body = str_replace('%url_48x48_ico', yonkImageURL($unixname, '48', 'ico', 'icon'), $body);
                $body = str_replace('%file_48x48_ico', yonkFilename($unixname, '48', 'ico', 'icon'), $body);
                $body = str_replace('%url_48x48_png', yonkImageURL($unixname, '48', 'png', 'icon'), $body);
                $body = str_replace('%file_48x48_png', yonkFilename($unixname, '56', 'png', 'icon'), $body);
                $body = str_replace('%url_56x56_png', yonkImageURL($unixname, '56', 'png', 'icon'), $body);
                $body = str_replace('%file_56x56_png', yonkFilename($unixname, '72', 'png', 'icon'), $body);
                $body = str_replace('%url_72x72_png', yonkImageURL($unixname, '72', 'png', 'icon'), $body);
                $body = str_replace('%file_114x114_png', yonkFilename($unixname, '114', 'png', 'icon'), $body);
                $body = str_replace('%url_114x114_png', yonkImageURL($unixname, '114', 'png', 'icon'), $body);
                $body = str_replace('%file_128x128_png', yonkFilename($unixname, '128', 'png', 'icon'), $body);
                $body = str_replace('%url_128x128_png', yonkImageURL($unixname, '128', 'png', 'icon'), $body);
                
                $tos = array($values['email'] => $values['name']);
                $ccs = array();
                $bccs = array();
                
                $subject = sprintf(API_SUBJECT_NEWICON, $data['organisation']);
                
                $sql = "INSERT INTO `" . $GLOBALS["APIDB"]->prefix('emails') . "` (`format-id`, `original-id`, `image-id`, `unixname`, `tos`, `ccs`, `bccs`, `subject`, `body`, `attachments`, `created`) VALUES('" . $formatid . "','" . $originalid . "','" . $imageid . "','" . $data['unixname'] . "','" . $GLOBALS["APIDB"]->escape(json_encode($tos)) . "', '" . $GLOBALS["APIDB"]->escape(json_encode($ccs)) . "', '" . $GLOBALS["APIDB"]->escape(json_encode($bccs)) . "'),'" . $GLOBALS["APIDB"]->escape($subject) . "','" . $GLOBALS["APIDB"]->escape($body) . "',COMPRESS('" . $GLOBALS["APIDB"]->escape(json_encode($attachments)) . "'),UNIX_TIMESTAMP())";
                if (!$GLOBALS['APIDB']->queryF($sql))
                    die("SQL Failed: $sql;");
                        
            }
    }
    if (is_dir($uploadpath))
        shell_exec("rm -Rfv '$uploadpath'");
    header("Location: " . (isset($inner['return'])?$inner['return']:API_URL));            
?>
