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
	$version = yonkUnixnameLatestVersion($inner['unixname']);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta property="og:title" content="<?php echo $inner['unixname']; ?> || <?php echo $version; ?>"/>
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
<title><?php echo $inner['unixname']; ?> || <?php echo $version; ?></title>
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
    <h1><?php echo $inner['unixname']; ?> -- <?php echo $version; ?></h1>
    <p>This ICON is down for the following details:</p>
    <?php 
        $originals = yonkUnixnameAllVersions($inner['unixname']);
        foreach($originals as $key => $images) {
            foreach($images as $key => $image) {
    ?>
    <img style="margin: 11px; float: right; width: auto; height: auto; clear: none;" src="<?php echo API_URL; ?>/v1/<?php echo $image['unixname']; ?>--<?php echo $image['major']; ?>.<?php echo $image['minor']; ?>.<?php echo $image['revision']; ?>.<?php echo $image['subrevision']; ?>/350/icon.png" />
    <table style="margin-top: 17px; margin-bottom: 6px; margin-left: auto; margin-right: auto; font-weight: 600;" width="52%">
    	<tr class="head">
    		<td><h2><?php echo $image['unixname']; ?> -- <?php echo $image['major']; ?>.<?php echo $image['minor']; ?>.<?php echo $image['revision']; ?>.<?php echo $image['subrevision']; ?></h2></td>
    		<td>&nbsp;</td>
    	</tr>
    	<tr class="even">
    		<td>Organisation:</td>
    		<td><?php echo $image['organisation'];?></td>
    	</tr>
    	<tr class="odd">
    		<td>Person:</td>
    		<td><?php echo $image['name'];?></td>
    	</tr>
    	<tr class="even">
    		<td>Unixname:</td>
    		<td><?php echo $image['unixname'];?></td>
    	</tr>
    	<tr class="odd">
    		<td>Version:</td>
    		<td><?php echo $image['major']; ?>.<?php echo $image['minor']; ?>.<?php echo $image['revision']; ?>.<?php echo $image['subrevision']; ?></td>
    	</tr>
    	<tr class="even">
    		<td>Original Image:</td>
    		<td><?php echo $image['image'];?></td>
    	</tr>
    	<tr class="odd">
			<td>Original Image Format:</td>
    		<td><?php echo $image['image-format'];?></td>
    	</tr>
    	<tr class="even">
    		<td>Original Image Mime-type:</td>
    		<td><?php echo $image['image-mime-type'];?></td>
    	</tr>
    	<tr class="odd">
    		<td>Original Image Class:</td>
    		<td><?php echo $image['image-class'];?></td>
    	</tr>
    	<tr class="even">
    		<td>Original Image Geometry:</td>
    		<td><?php echo $image['image-geometry'];?></td>
    	</tr>
    	<tr class="odd">
    		<td>Original Image Units:</td>
    		<td><?php echo $image['image-units'];?></td>
    	</tr>
    	<tr class="even">
    		<td>Original Image Endianess:</td>
    		<td><?php echo $image['image-endianess'];?></td>
    	</tr>
    	<tr class="odd">
    		<td>Original Image Colourspace:</td>
    		<td><?php echo $image['image-colorspace'];?></td>
    	</tr>
    	<tr class="even">
    		<td>Original Image Depth:</td>
    		<td><?php echo $image['image-depth'];?></td>
    	</tr>
    	<tr class="odd">
    		<td>Original Image Channel Depth Red:</td>
    		<td><?php echo $image['image-channel-depth-red'];?></td>
    	</tr>
    	<tr class="even">
    		<td>Original Image Channel Depth Blue:</td>
    		<td><?php echo $image['image-channel-depth-blue'];?></td>
    	</tr>
    	<tr class="odd">
    		<td>Original Image Channel Depth Green:</td>
    		<td><?php echo $image['image-channel-depth-green'];?></td>
    	</tr>
    	<tr class="even">
    		<td>Width:</td>
    		<td><?php echo $image['width'];?></td>
    	</tr>
    	<tr class="odd">
    		<td>Height:</td>
    		<td><?php echo $image['height'];?></td>
    	</tr>
    	<tr class="even">
    		<td>Bytes:</td>
    		<td><?php echo yonkDisplayBytes($image['bytes']);?></td>
    	</tr>
    	<tr class="odd">
    		<td>Bytes Converted:</td>
    		<td><?php echo yonkDisplayBytes($image['convert_bytes']);?></td>
    	</tr>
    	<tr class="even">
    		<td>Bytes Downloaded:</td>
    		<td><?php echo yonkDisplayBytes($image['downloads_bytes']);?></td>
    	</tr>
    	<tr class="odd">
    		<td>Bytes Cached:</td>
    		<td><?php echo yonkDisplayBytes($image['caching_bytes']);?></td>
    	</tr>
    	<tr class="even">
    		<td>Created:</td>
    		<td><?php echo date('Y-m-d, D, H:i:s', $image['created']);?></td>
    	</tr>	    	    	    	
    	<tr class="odd">
    		<td>Converted last:</td>
    		<td><?php echo date('Y-m-d, D, H:i:s', $image['converted']);?></td>
    	</tr>	    	    	    	
    	<tr class="even">
    		<td>Downloaded last:</td>
    		<td><?php echo date('Y-m-d, D, H:i:s', $image['downloaded']);?></td>
    	</tr>	    	    	    	
    	<tr class="odd">
    		<td>Cached last:</td>
    		<td><?php echo date('Y-m-d, D, H:i:s', $image['cached']);?></td>
    	</tr>	    	    	    	
    	<tr class="even">
    		<td>Users That Own:</td>
    		<td><?php $count = 0; foreach($image['users'] as $ukey => $user) { $count++; ?><a target="_blank" href="<?php echo API_URL; ?>/v1/user/<?php echo $ukey; ?>.html"><?php echo (!empty($user['name']) ? $user['name'] . "&nbsp;[ ":"") . (!empty($user['name']) ? $user['uname'] . " ]":$user['uname']) ;?></a><?php echo ($count < count($image['users'])? ",&nbsp;" :""); }?></td>
    	</tr>	    	    	    		    	    	    	
    </table>
    <?php }
        } ?>
</div>
</html>