<?php include('header.php');

	$sql = @mysql_query("SELECT * FROM `artwork` WHERE id='$id'");
	if (!$sql) { 
		echo('<p>Error performing query: '.mysql_error().'</p>'); 
   		exit(); 
	}

	$select = mysql_fetch_array($sql);
	$name = $select["name"];
	$category = $select["category"];
	$medium = $select["medium"];
	$assignment = $select["assignment"];
	$description = $select["description"];
	$created = $select["created"];
	$date = $select["date"];
	$url = '/rachel/images/'.$category.'/'.$select["filename"];

	echo('<h1>'.$name.'</h1>');
		
	$ext = substr("$url", -3);
	
	if($ext == "gif")
			echo('<p align="center"><img src="'.$url.'"></a></p>');
	else if($ext == "swf")
		echo('<p align="center"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0">
			<param name="movie" value="'.$url.'">
			<param name="quality" value="high">
			<embed src="'.$url.'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="'.$width.'" height="'.$height.'"></embed></object></p>');
	
	$query = @mysql_query("SELECT * FROM `categories` WHERE id='$category'");
	if (!$query) { 
		echo('<p>Error performing query: '.mysql_error().'</p>'); 
   		exit(); 
	}

	$row = mysql_fetch_array($query);
	$cat = $row["name"];
	
	echo('<div id="GALLERY">
		<p><b>Category</b>: '.$cat.'<br>
		<b>Medium</b>: '.$medium.'<br>
		<b>Assignment</b>: '.$assignment.'<br>
		<b>Description</b>: '.$description.'<br>
		<b>Created</b>: '.$created.'<br>
		<b>Added to Database</b>: '.$date.'</p>
		</div>');
	
include('footer.php'); ?>
