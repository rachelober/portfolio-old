<?php
// ******************************************************************************************************
// Gallery Script
// Copyright 2003 Rachel Ober
// http://e-ober.com/rachel-ober/
// ******************************************************************************************************
// dbcnx.php
// Connect to the database
// ******************************************************************************************************
function popup($name, $filename, $thumbnail, $id) {
	$target++;
	
	$filename = BASE_URL.IMAGES.$filename;
	$thumbnail = BASE_URL.IMAGES.THUMBS.$thumbnail;

	$size = GetImageSize($filename);
	$width = $size[0];
	$height = $size[1];
	$windowwidth = $width + 50;
	$windowheight = $height + 50;
	if($thumbnail != "" && $thumbnail != "none") {
		$size = GetImageSize($thumbnail);
		$twidth = $size[0];
		$theight = $size[1];
	}
	
	print "
	<A HREF=\"#\" onMouseOver=\"window.status='$name';return true\" onMouseOut=\"window.status='';return true\" onClick=\"window.open('";
print "popup.php?z=$filename&width=$width&height=$height&name=$name&id=$id','$target','width=$windowwidth,height=$windowheight,directories=no,location=no,menubar=no,scrollbars=yes,status=no,toolbar=no,resizable=no,left=0,top=0,screenx=50,screeny=50');return false\">";	
	print"<img src=\"$thumbnail\" width=\"$twidth\" height=\"$theight\" border=\"0\"></a>";
}
?> 