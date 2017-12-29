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

function yonkCacheKey($unixname = '', $width = 128, $format = 'png', $mode = 'icon', $variable = '', $extra = '')
{
    
    if (!$sessions = APICache::read('cache-sessions'))
        $sessions = array();
        
    if (!isset($sessions[$unixname]['seed']) && !isset($sessions[$unixname]['caches']))
        $sessions[$unixname]['seed'] = mt_rand(0, 255);
        
    if (!isset($sessions[$unixname]['length']) && !isset($sessions[$unixname]['caches']))
        $sessions[$unixname]['length'] = mt_rand(5, 7);
        
    if (!isset($sessions[$unixname]['caches']))
        $sessions[$unixname]['caches'] = array();

    APICache::write('cache-sessions', $sessions, API_CACHE_SESSIONS);
    
    switch ($mode)
    {
        default:
        case "icon":
            $result = $unixname . '-' . $width . 'x' . $width . '-' . $format . '-' . $mode . (!empty($variable)?"-$variable":"");
            break;
    }
    
    if (!empty($extra))
    {
        $xcp = new xcp($extra, $sessions[$unixname]['seed'], $sessions[$unixname]['length']);
        $result .= '-' . $xcp->calc($extra);
    }
    
    return $result;
}


function yonkFilename($unixname = '', $width = 128, $format = 'png', $mode = 'icon', $variable = '', $extra = '')
{
    
    if (!$sessions = APICache::read('cache-sessions'))
        $sessions = array();
    
    if (!isset($sessions[$unixname]['seed']) && !isset($sessions[$unixname]['caches']))
        $sessions[$unixname]['seed'] = mt_rand(0, 255);
            
    if (!isset($sessions[$unixname]['length']) && !isset($sessions[$unixname]['caches']))
        $sessions[$unixname]['length'] = mt_rand(5, 7);
            
    if (!isset($sessions[$unixname]['caches']))
        $sessions[$unixname]['caches'] = array();
                    
    APICache::write('cache-sessions', $sessions, API_CACHE_SESSIONS);
                    
    switch ($mode)
    {
        default:
            $result = $unixname . '-' . $width . 'x' . $width . '-' . $mode . (!empty($variable)?"-$variable":"");
            break;
            
        case "icon":
            $result = $unixname . '-' . $width . 'x' . $width ;
            break;
    }
    
    if (!empty($extra))
    {
        $xcp = new xcp($extra, $sessions[$unixname]['seed'], $sessions[$unixname]['length']);
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
    
    $sql = "SELECT md5(concat(`uid`,`email`,`api_regdate`)) as `md5` FROM `" . $GLOBALS['APIDB']->prefix('users') . '` $in ORDER BY RAND() LIMIT 1';
    list($md5) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
    if ($md5!=md5() && $md5!=md5(NULL))
        $hashes[] = $md5;
    
    $sql = "SELECT md5(`email`) as `md5` FROM `" . $GLOBALS['APIDB']->prefix('users') . '` $in ORDER BY RAND() LIMIT 1';
    list($md5) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
    if ($md5!=md5() && $md5!=md5(NULL))
        $hashes[] = $md5;
        
    $sql = "SELECT md5(`uid`, `api_regdate`) as `md5` FROM `" . $GLOBALS['APIDB']->prefix('users') . '` $in ORDER BY RAND() LIMIT 1';
    list($md5) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
    if ($md5!=md5() && $md5!=md5(NULL))
        $hashes[] = $md5;
        
    $sql = "SELECT md5(`username`,`email`) as `md5` FROM `" . $GLOBALS['APIDB']->prefix('users') . '` $in ORDER BY RAND() LIMIT 1';
    list($md5) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
    if ($md5!=md5() && $md5!=md5(NULL))
        $hashes[] = $md5;
        
    $sql = "SELECT md5(`username`,`name`) as `md5` FROM `" . $GLOBALS['APIDB']->prefix('users') . '` $in ORDER BY RAND() LIMIT 1';
    list($md5) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
    if ($md5!=md5() && $md5!=md5(NULL))
        $hashes[] = $md5;
        
    $sql = "SELECT md5(`username`) as `md5` FROM `" . $GLOBALS['APIDB']->prefix('users') . '` WHERE `uid` = "' . $uid . '"$in ORDER BY RAND() LIMIT 1';
    list($md5) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
    if ($md5!=md5() && $md5!=md5(NULL))
        $hashes[] = $md5;
        
    return $hashes[mt_rand(0, count($hashes))];
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
    /**
     * yonk the HTML Forms for the API
     *
     * @param unknown_type $mode
     * @param unknown_type $clause
     * @param unknown_type $output
     * @param unknown_type $version
     *
     * @return string
     */
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


if (!function_exists('sef'))
{
    /**
     * Safe encoded paths elements
     *
     * @param unknown $datab
     * @param string $char
     *
     * @return string
     */
    function sef($value = '', $stripe ='-')
    {
        return(strtolower(yonkOnlyAlpha($result, $stripe)));
    }
}


if (!function_exists('yonkOnlyAlpha'))
{
    /**
     * Safe encoded paths elements
     *
     * @param unknown $datab
     * @param string $char
     *
     * @return string
     */
    function yonkOnlyAlpha($value = '', $stripe ='-')
    {
        $value = str_replace('&', 'and', $value);
        $value = str_replace(array("'", '"', "`"), 'tick', $value);
        $replacement_chars = array();
        $accepted = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","m","o","p","q",
            "r","s","t","u","v","w","x","y","z","0","9","8","7","6","5","4","3","2","1");
        for($i=0;$i<256;$i++){
            if (!in_array(strtolower(chr($i)),$accepted))
                $replacement_chars[] = chr($i);
        }
        $result = trim(str_replace($replacement_chars, $stripe, ($value)));
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
            case "geometry":
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
	/**
	 * class XmlDomConstruct
	 *
	 * 	Extends the DOMDocument to implement personal (utility) methods.
	 *
	 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
	 */
	class XmlDomConstruct extends DOMDocument {

		/**
		 * Constructs elements and texts from an array or string.
		 * The array can contain an element's name in the index part
		 * and an element's text in the value part.
		 *
		 * It can also creates an xml with the same element tagName on the same
		 * level.
		 *
		 * ex:
		 * <nodes>
		 *   <node>text</node>
		 *   <node>
		 *     <field>hello</field>
		 *     <field>world</field>
		 *   </node>
		 * </nodes>
		 *
		 * Array should then look like:
		 *
		 * Array (
		 *   "nodes" => Array (
		 *     "node" => Array (
		 *       0 => "text"
		 *       1 => Array (
		 *         "field" => Array (
		 *           0 => "hello"
		 *           1 => "world"
		 *         )
		 *       )
		 *     )
		 *   )
		 * )
		 *
		 * @param mixed $mixed An array or string.
		 *
		 * @param DOMElement[optional] $domElement Then element
		 * from where the array will be construct to.
		 *
		 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
		 *
		 */
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