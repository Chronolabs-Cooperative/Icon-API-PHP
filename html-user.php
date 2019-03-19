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
 * @copyright       Chronolabs Cooperative http://snails.email
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         lookups
 * @since           1.1.2
 * @author          Simon Roberts <meshy@snails.email>
 * @version         $Id: index.php 1000 2013-06-07 01:20:22Z mynamesnot $
 * @subpackage		api
 * @description		Internet Protocol Address Information API Service REST
 */

	$typals['raw'] = 'RAW';
	$typals['json'] = 'JSON';
	$typals['serial'] = 'Serialisation';
	$typals['xml'] = 'XML';
	
	global $output, $mode, $inner;
	$user = yonkUserDetails($inner['session']);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta property="og:title" content="<?php echo (!empty($user['name']) ? $user['name'] . " [ ":"") . (!empty($user['name']) ? $user['uname'] . " ]":$user['uname']) ;?> (<?php echo count($user['originals']); ?>)"/>
<meta property="og:type" content="api<?php echo API_TYPE; ?>"/>
<meta property="og:image" content="<?php echo API_URL; ?>/v1/<?php echo $inner['unixname']; ?>/500/icon.png"/>
<meta property="og:url" content="<?php echo (isset($_SERVER["HTTPS"])?"https://":"http://").$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]; ?>" />
<meta property="og:site_name" content="<?php echo $inner['unixname']; ?> -- <?php echo $version; ?> - <?php echo API_LICENSE_COMPANY; ?>"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="rating" content="general" />
<meta http-equiv="<?php echo $place['iso2']; ?>thor" content="wishcraft@users.sourceforge.net" />
<meta http-equiv="copyright" content="<?php echo API_LICENSE_COMPANY; ?> &copy; <?php echo date("Y"); ?>" />
<meta http-equiv="generator" content="Chronolabs Cooperative (<?php echo $place['iso3']; ?>)" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo (!empty($user['name']) ? $user['name'] . " [ ":"") . (!empty($user['name']) ? $user['uname'] . " ]":$user['uname']) ;?> (<?php echo count($user['originals']); ?>)</title>
<!-- AddThis Smart Layers BEGIN -->
<!-- Go to http://www.addthis.com/get/smart-layers to customize -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-50f9a1c208996c1d"></script>
<script type="text/javascript">
  addthis.layers({
	'theme' : 'transparent',
	'share' : {
	  'position' : 'right',
	  'numPreferredServices' : 6
	}, 
	'follow' : {
	  'services' : [
		{'service': 'facebook', 'id': 'Chronolabs'},
		{'service': 'twitter', 'id': 'JohnRingwould'},
		{'service': 'twitter', 'id': 'ChronolabsCoop'},
		{'service': 'twitter', 'id': 'Cipherhouse'},
		{'service': 'twitter', 'id': 'OpenRend'},
	  ]
	},  
	'whatsnext' : {},  
	'recommended' : {
	  'title': 'Recommended for you:'
	} 
  });
</script>
<!-- AddThis Smart Layers END -->
<link rel="stylesheet" href="<?php echo API_URL; ?>/assets/css/style.css" type="text/css" />
<!-- Custom Fonts -->
<link href="<?php echo API_URL; ?>/assets/media/Labtop/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Bold/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Bold Italic/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Italic/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Superwide Boldish/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Thin/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Unicase/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/LHF Matthews Thin/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Life BT Bold/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Life BT Bold Italic/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Prestige Elite/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Prestige Elite Bold/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Prestige Elite Normal/style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?php echo API_URL; ?>/assets/css/gradients.php" type="text/css" />
<link rel="stylesheet" href="<?php echo API_URL; ?>/assets/css/shadowing.php" type="text/css" />

</head>

<body>
<div class="main">
    <h1><?php echo (!empty($user['name']) ? $user['name'] . "&nbsp;[ ":"") . (!empty($user['name']) ? $user['uname'] . " ]":$user['uname']) ;?></h1>
    <p>This USER is down for the following details:</p>
    <table style="margin-top: 17px; margin-bottom: 6px; margin-left: auto; margin-right: auto; font-weight: 600;" width="52%">
    	<tr class="even">
    		<td>Name:</td>
    		<td><?php echo $user['name'];?></td>
    	</tr>
    	<tr class="odd">
    		<td>Username:</td>
    		<td><?php echo $user['uname'];?></td>
    	</tr>
    	<tr class="even">
    		<td>Number Hits:</td>
    		<td><?php echo $user['hits'];?></td>
    	</tr>
    	<tr class="odd">
    		<td>Registration Date:</td>
    		<td><?php echo date('Y-m-d, D, H:i:s', $user['api_regdate']);?></td>
    	</tr>
    	<tr class="even">
    		<td>Last Online:</td>
    		<td><?php echo date('Y-m-d, D, H:i:s', $user['last_online']);?></td>
	   	</tr>
    	<tr class="odd">
    		<td>Last Login:</td>
    		<td><?php echo date('Y-m-d, D, H:i:s', $user['last_login']);?></td>
    	</tr>
    	<tr class="even">
			<td>Original Images:</td>
			<td><?php foreach($user['originals'] as $okey => $original) { ?><a target="_blank" alt="<?php echo $original['unixname']; ?> version: <?php echo $original['major']; ?>.<?php echo $original['minor']; ?>.<?php echo $original['revision']; ?>.<?php echo $original['subrevision']; ?>" href="<?php echo API_URL; ?>/v1/unixname/<?php echo $original['unixname']; ?>.html"><img style="margin: 3px; width: auto; height: auto; clear: none;" src="<?php echo API_URL; ?>/v1/<?php echo $original['unixname']; ?>--<?php echo $original['major']; ?>.<?php echo $original['minor']; ?>.<?php echo $original['revision']; ?>.<?php echo $original['subrevision']; ?>/48/icon.png" /></a><?php } ?></td>
    	</tr>
    </table>
</div>
</body>
</html>