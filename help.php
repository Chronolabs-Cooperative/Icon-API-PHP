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
	
	$formats = yonkImageFormats();
	$unixname = yonkRandomUnixname();
	$size = mt_rand(96, 256);
	$userhash = yonkRandomUserhash();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta property="og:title" content="<?php echo API_VERSION; ?>"/>
<meta property="og:type" content="api<?php echo API_TYPE; ?>"/>
<meta property="og:image" content="<?php echo API_URL; ?>/assets/images/logo_500x500.png"/>
<meta property="og:url" content="<?php echo (isset($_SERVER["HTTPS"])?"https://":"http://").$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]; ?>" />
<meta property="og:site_name" content="<?php echo API_VERSION; ?> - <?php echo API_LICENSE_COMPANY; ?>"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="rating" content="general" />
<meta http-equiv="<?php echo $place['iso2']; ?>thor" content="wishcraft@users.sourceforge.net" />
<meta http-equiv="copyright" content="<?php echo API_LICENSE_COMPANY; ?> &copy; <?php echo date("Y"); ?>" />
<meta http-equiv="generator" content="Chronolabs Cooperative (<?php echo $place['iso3']; ?>)" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo API_VERSION; ?> || <?php echo API_LICENSE_COMPANY; ?></title>
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
	<img style="float: right; margin: 11px; width: auto; height: auto; clear: none;" src="<?php echo API_URL; ?>/assets/images/logo_350x350.png" />
    <h1><?php echo API_VERSION; ?> -- <?php echo API_LICENSE_COMPANY; ?></h1>
    <p>This API is for providing Icons in many supported formats at any general resized scale for things like the favorite icon and so on.</p>
    <?php 
    $ua = substr(sha1($_SERVER['HTTP_USER_AGENT']), mt_rand(0,32), 9);
    ?>
    <h2>Icon Intial Upload Mount</h2>
    <p>The following form is an example of the submission of a new icon to the api: <strong><a href='<?php echo API_URL . '/v2/html/' .$ua . "/form.api"; ?>'><?php echo API_URL . '/v2/html/' .$ua . "/form.api"; ?></a></strong></p>
    <blockquote>
        <h3>Uploading Functions for most Image File Formats'</h3>
    	<?php  
    	       echo $form = yonkHTMLForm('upload', $ua); ?>
    	<pre style="overflow: scroll; height: 520px;">
    		<?php echo htmlspecialchars($form); ?>
    	</pre>
    </blockquote>
    
    <h2>Resource Listing Functions</h2>
    <p>The following form is an example of retrieving various image formats as an image!</p>
    <blockquote>
    	<?php foreach($typals as $extension => $title) { ?>
    	<h3><?php echo $title; ?> File Format Output</h3>
    	<font class="help-title-text">Retrieves an listing of unix-names in relationship to user ownership hashes in the format of PHP generated '<?php echo $title; ?>'!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL; ?>/v1/unixnames/listing.<?php echo $extension; ?>"><?php echo API_URL; ?>/v1/unixnames/listing.<?php echo $extension; ?></a></font><br /><br />
		<font class="help-title-text">Retrieves an listing of users in relationship to unix-names based in ownership hashes in the format of PHP generated '<?php echo $title; ?>'!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL; ?>/v1/users/listing.<?php echo $extension; ?>"><?php echo API_URL; ?>/v1/users/listing.<?php echo $extension; ?></a></font><br /><br />
        <font class="help-title-text">Retrieves an listing of unix-names in relationship to user ownership hashes in the format of PHP generated '<?php echo $title; ?>'!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL; ?>/v1/unixname/<?php echo $unixname; ?>.<?php echo $extension; ?>"><?php echo API_URL; ?>/v1/unixname/<?php echo $unixname; ?>.<?php echo $extension; ?></a></font><br /><br />
		<font class="help-title-text">Retrieves an listing of users in relationship to unix-names based in ownership hashes in the format of PHP generated '<?php echo $title; ?>'!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL; ?>/v1/user/<?php echo $userhash; ?>.<?php echo $extension; ?>"><?php echo API_URL; ?>/v1/user/<?php echo $userhash; ?>.<?php echo $extension; ?></a></font><br /><br />
<?php } ?>
		<h3>HTML File Format Output</h3>
		<font class="help-title-text">Retrieves an viewiable profile of unix-name's in openly in HTML!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL; ?>/v1/unixname/<?php echo $unixname; ?>.html"><?php echo API_URL; ?>/v1/unixname/<?php echo $unixname; ?>.html</a></font><br /><br />
		<font class="help-title-text">Retrieves an viewiable profile of a User(s) in openly in HTML!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL; ?>/v1/user/<?php echo $userhash; ?>.html"><?php echo API_URL; ?>/v1/user/<?php echo $userhash; ?>.html</a></font><br /><br />
    </blockquote>

    <h2>Icon/Image URL Functions</h2>
    <p>The following form is an example of retrieving various image formats as an image!</p>
    <blockquote>
    	<?php foreach($formats as $extension => $title) { ?>
    	<h3>Image Format: <?php echo $title; ?></h3>
    	<font class="help-title-text">Retrieves an *.<?php echo $extension; ?> icon/image in the format of '<?php echo $title; ?>' which is '<?php echo $size; ?>x<?php echo $size; ?>' in size with width+height!</font><br/>
        <font class="help-url-example"><a href="<?php echo yonkImageURL($unixname, $size, $extension, 'icon'); ?>" target="_blank"><?php echo yonkImageURL($unixname, $size, $extension, 'icon'); ?></a></font><br /><br />
		<?php } ?>        
    </blockquote>
        
    <h2>Extra Command on Image/Icon Output</h2>
    <p>On any of the image outputting URL you can specify ?extra= and any number of the combination of the calls below for imagemagick convert executable so ie. <a href="<?php echo API_URL; ?>/v1/chronolabs-it/256/icon.png?extra=-antialias -caption 'this is a caption'"><?php echo API_URL; ?>/v1/chronolabs-it/256/icon.png?extra=-antialias -caption 'this is a caption'</a></p>
    <blockquote>
        <h3>ImageMagick - Convert Functions'</h3>
		<pre>
  -adjoin              join images into a single multi-image file
  -affine matrix       affine transform matrix
  -alpha option        activate, deactivate, reset, or set the alpha channel
  -antialias           remove pixel-aliasing
  -attenuate value     lessen (or intensify) when adding noise to an image
  -background color    background color
  -bias value          add bias when convolving an image
  -black-point-compensation
                       use black point compensation
  -blue-primary point  chromaticity blue primary point
  -bordercolor color   border color
  -caption string      assign a caption to an image
  -channel type        apply option to select image channels
  -clip-mask filename  associate a clip mask with the image
  -colors value        preferred number of colors in the image
  -colorspace type     alternate image colorspace
  -comment string      annotate image with comment
  -compose operator    set image composite operator
  -compress type       type of pixel compression when writing the image
  -define format:option
                       define one or more image format options
  -delay value         display the next image after pausing
  -density geometry    horizontal and vertical density of the image
  -depth value         image depth
  -direction type      render text right-to-left or left-to-right
  -display server      get image or font from this X server
  -dispose method      layer disposal method
  -dither method       apply error diffusion to image
  -encoding type       text encoding type
  -endian type         endianness (MSB or LSB) of the image
  -family name         render text with this font family
  -fill color          color to use when filling a graphic primitive
  -filter type         use this filter when resizing an image
  -font name           render text with this font
  -format "string"     output formatted image characteristics
  -fuzz distance       colors within this distance are considered equal
  -gravity type        horizontal and vertical text placement
  -green-primary point chromaticity green primary point
  -intensity method    method to generate intensity value from pixel
  -intent type         type of rendering intent when managing the image color
  -interlace type      type of image interlacing scheme
  -interline-spacing value
                       set the space between two text lines
  -interpolate method  pixel color interpolation method
  -interword-spacing value
                       set the space between two words
  -kerning value       set the space between two letters
  -label string        assign a label to an image
  -limit type value    pixel cache resource limit
  -loop iterations     add Netscape loop extension to your GIF animation
  -mask filename       associate a mask with the image
  -matte               store matte channel if the image has one
  -mattecolor color    frame color
  -moments             report image moments
  -monitor             monitor progress
  -orient type         image orientation
  -page geometry       size and location of an image canvas (setting)
  -ping                efficiently determine image attributes
  -pointsize value     font point size
  -precision value     maximum number of significant digits to print
  -preview type        image preview type
  -quality value       JPEG/MIFF/PNG compression level
  -quiet               suppress all warning messages
  -red-primary point   chromaticity red primary point
  -regard-warnings     pay attention to warning messages
  -remap filename      transform image colors to match this set of colors
  -respect-parentheses settings remain in effect until parenthesis boundary
  -sampling-factor geometry
                       horizontal and vertical sampling factor
  -scene value         image scene number
  -seed value          seed a new sequence of pseudo-random numbers
  -size geometry       width and height of image
  -stretch type        render text with this font stretch
  -stroke color        graphic primitive stroke color
  -strokewidth value   graphic primitive stroke width
  -style type          render text with this font style
  -support factor      resize support: > 1.0 is blurry, < 1.0 is sharp
  -synchronize         synchronize image to storage device
  -taint               declare the image as modified
  -texture filename    name of texture to tile onto the image background
  -tile-offset geometry
                       tile offset
  -treedepth value     color tree depth
  -transparent-color color
                       transparent color
  -undercolor color    annotation bounding box color
  -units type          the units of image resolution
  -verbose             print detailed information about the image
  -view                FlashPix viewing transforms
  -virtual-pixel method
                       virtual pixel access method
  -weight type         render text with this font weight
  -white-point point   chromaticity white point

Image Operators:
  -adaptive-blur geometry
                       adaptively blur pixels; decrease effect near edges
  -adaptive-resize geometry
                       adaptively resize image using 'mesh' interpolation
  -adaptive-sharpen geometry
                       adaptively sharpen pixels; increase effect near edges
  -alpha option        on, activate, off, deactivate, set, opaque, copy
                       transparent, extract, background, or shape
  -annotate geometry text
                       annotate the image with text
  -auto-gamma          automagically adjust gamma level of image
  -auto-level          automagically adjust color levels of image
  -auto-orient         automagically orient (rotate) image
  -bench iterations    measure performance
  -black-threshold value
                       force all pixels below the threshold into black
  -blue-shift factor   simulate a scene at nighttime in the moonlight
  -blur geometry       reduce image noise and reduce detail levels
  -border geometry     surround image with a border of color
  -bordercolor color   border color
  -brightness-contrast geometry
                       improve brightness / contrast of the image
  -canny geometry      detect edges in the image
  -cdl filename        color correct with a color decision list
  -charcoal radius     simulate a charcoal drawing
  -chop geometry       remove pixels from the image interior
  -clamp               keep pixel values in range (0-QuantumRange)
  -clip                clip along the first path from the 8BIM profile
  -clip-path id        clip along a named path from the 8BIM profile
  -colorize value      colorize the image with the fill color
  -color-matrix matrix apply color correction to the image
  -connected-components connectivity
                       connected-components uniquely labeled
  -contrast            enhance or reduce the image contrast
  -contrast-stretch geometry
                       improve contrast by `stretching' the intensity range
  -convolve coefficients
                       apply a convolution kernel to the image
  -cycle amount        cycle the image colormap
  -decipher filename   convert cipher pixels to plain pixels
  -deskew threshold    straighten an image
  -despeckle           reduce the speckles within an image
  -distort method args
                       distort images according to given method ad args
  -draw string         annotate the image with a graphic primitive
  -edge radius         apply a filter to detect edges in the image
  -encipher filename   convert plain pixels to cipher pixels
  -emboss radius       emboss an image
  -enhance             apply a digital filter to enhance a noisy image
  -equalize            perform histogram equalization to an image
  -evaluate operator value
                       evaluate an arithmetic, relational, or logical expression
  -extent geometry     set the image size
  -extract geometry    extract area from image
  -features distance   analyze image features (e.g. contrast, correlation)
  -fft                 implements the discrete Fourier transform (DFT)
  -flip                flip image vertically
  -floodfill geometry color
                       floodfill the image with color
  -flop                flop image horizontally
  -frame geometry      surround image with an ornamental border
  -function name parameters
                       apply function over image values
  -gamma value         level of gamma correction
  -gaussian-blur geometry
                       reduce image noise and reduce detail levels
  -geometry geometry   preferred size or location of the image
  -grayscale method    convert image to grayscale
  -hough-lines geometry
                       identify lines in the image
  -identify            identify the format and characteristics of the image
  -ift                 implements the inverse discrete Fourier transform (DFT)
  -implode amount      implode image pixels about the center
  -interpolative-resize geometry
                       resize image using 'point sampled' interpolation
  -kuwahara geometry   edge preserving noise reduction filter
  -lat geometry        local adaptive thresholding
  -level value         adjust the level of image contrast
  -level-colors color,color
                       level image with the given colors
  -linear-stretch geometry
                       improve contrast by `stretching with saturation'
  -liquid-rescale geometry
                       rescale image with seam-carving
  -local-contrast geometry
                       enhance local contrast
  -magnify             double the size of the image with pixel art scaling
  -mean-shift geometry delineate arbitrarily shaped clusters in the image
  -median geometry     apply a median filter to the image
  -mode geometry       make each pixel the 'predominant color' of the
                       neighborhood
  -modulate value      vary the brightness, saturation, and hue
  -monochrome          transform image to black and white
  -morphology method kernel
                       apply a morphology method to the image
  -motion-blur geometry
                       simulate motion blur
  -negate              replace every pixel with its complementary color 
  -noise geometry      add or reduce noise in an image
  -normalize           transform image to span the full range of colors
  -opaque color        change this color to the fill color
  -ordered-dither NxN
                       add a noise pattern to the image with specific
                       amplitudes
  -paint radius        simulate an oil painting
  -perceptible epsilon
                       pixel value less than |epsilon| become epsilon or
                       -epsilon
  -polaroid angle      simulate a Polaroid picture
  -posterize levels    reduce the image to a limited number of color levels
  -profile filename    add, delete, or apply an image profile
  -quantize colorspace reduce colors in this colorspace
  -radial-blur angle   radial blur the image (deprecated use -rotational-blur
  -raise value         lighten/darken image edges to create a 3-D effect
  -random-threshold low,high
                       random threshold the image
  -region geometry     apply options to a portion of the image
  -render              render vector graphics
  -repage geometry     size and location of an image canvas
  -resample geometry   change the resolution of an image
  -roll geometry       roll an image vertically or horizontally
  -rotate degrees      apply Paeth rotation to the image
  -rotational-blur angle
                       rotational blur the image
  -sample geometry     scale image with pixel sampling
  -scale geometry      scale the image
  -segment values      segment an image
  -selective-blur geometry
                       selectively blur pixels within a contrast threshold
  -sepia-tone threshold
                       simulate a sepia-toned photo
  -set property value  set an image property
  -shade degrees       shade the image using a distant light source
  -shadow geometry     simulate an image shadow
  -sharpen geometry    sharpen the image
  -shave geometry      shave pixels from the image edges
  -shear geometry      slide one edge of the image along the X or Y axis
  -sigmoidal-contrast geometry
                       increase the contrast without saturating highlights or
                       shadows
  -sketch geometry     simulate a pencil sketch
  -solarize threshold  negate all pixels above the threshold level
  -sparse-color method args
                       fill in a image based on a few color points
  -splice geometry     splice the background color into the image
  -spread radius       displace image pixels by a random amount
  -statistic type geometry
                       replace each pixel with corresponding statistic from the
                       neighborhood
  -strip               strip image of all profiles and comments
  -swirl degrees       swirl image pixels about the center
  -threshold value     threshold the image
  -thumbnail geometry  create a thumbnail of the image
  -tile filename       tile image when filling a graphic primitive
  -tint value          tint the image with the fill color
  -transform           affine transform image
  -transparent color   make this color transparent within the image
  -transpose           flip image vertically and rotate 90 degrees
  -transverse          flop image horizontally and rotate 270 degrees
  -trim                trim image edges
  -type type           image type
  -unique-colors       discard all but one of any pixel color
  -unsharp geometry    sharpen the image
  -vignette geometry   soften the edges of the image in vignette style
  -wave geometry       alter an image along a sine wave
  -wavelet-denoise threshold
                       removes noise from the image using a wavelet transform
  -white-threshold value
                       force all pixels above the threshold into white

Image Sequence Operators:
  -append              append an image sequence
  -clut                apply a color lookup table to the image
  -coalesce            merge a sequence of images
  -combine             combine a sequence of images
  -compare             mathematically and visually annotate the difference between an image and its reconstruction
  -complex operator    perform complex mathematics on an image sequence
  -composite           composite image
  -copy geometry offset
                       copy pixels from one area of an image to another
  -crop geometry       cut out a rectangular region of the image
  -deconstruct         break down an image sequence into constituent parts
  -evaluate-sequence operator
                       evaluate an arithmetic, relational, or logical expression
  -flatten             flatten a sequence of images
  -fx expression       apply mathematical expression to an image channel(s)
  -hald-clut           apply a Hald color lookup table to the image
  -layers method       optimize, merge, or compare image layers
  -morph value         morph an image sequence
  -mosaic              create a mosaic from an image sequence
  -poly terms          build a polynomial from the image sequence and the corresponding
                       terms (coefficients and degree pairs).
  -print string        interpret string and print to console
  -process arguments   process the image with a custom image filter
  -separate            separate an image channel into a grayscale image
  -smush geometry      smush an image sequence together
  -write filename      write images to this file

Image Stack Operators:
  -clone indexes       clone an image
  -delete indexes      delete the image from the image sequence
  -duplicate count,indexes
                       duplicate an image one or more times
  -insert index        insert last image into the image sequence
  -reverse             reverse image sequence
  -swap indexes        swap two images in the image sequence


		</pre>
	</blockquote>

    <h2>The Author</h2>
    <p>This was developed by Simon Roberts in 2017 and is part of the Chronolabs System and api's.<br/><br/>This is open source which you can download from <a href="https://sourceforge.net/projects/chronolabsapis/">https://sourceforge.net/projects/chronolabsapis/</a> contact the scribe  <a href="mailto:wishcraft@users.sourceforge.net">wishcraft@users.sourceforge.net</a></p></body>
</div>
</html>