<?php
include('header.php');

	$sql = @mysql_query("SELECT * FROM `categories` WHERE id='$cat'");
	if (!$sql) { 
		echo('<p>Error performing query: '.mysql_error().'</p>'); 
   		exit(); 
	}
	$select = mysql_fetch_array($sql);
	$category = $select["name"];
	$desc = $select["description"];
	
	$result2 = @mysql_query("SELECT count(*) as count FROM `artwork` WHERE category='$cat'"); 
	$row2 = mysql_fetch_array($result2);
	$count = $row2["count"];

	echo('<h1>'.$category.'</h1>
			<p>'.$desc.'</p>
			<p>There are <b>'.$count.'</b> files in this gallery.</p>
			<p><i>*All Thumbnails are linked to a popup window.</i></p>
			<div id="GALLERY">');

	$result = @mysql_query("SELECT * FROM `artwork` WHERE category='$cat' ORDER BY date ASC");
	if (!$result) { 
		echo('<p>Error performing query: '.mysql_error().'</p>'); 
   		exit(); 
	}

	while ($row = mysql_fetch_array($result)){
		$id = $row["id"];
		$name = $row["name"];
		$filename = $row["filename"];
		$thumbnail = $row["thumbnail"];
		$medium = $row["medium"];
		$assignment = $row["assignment"];
		$description = $row["description"];
		$created = $row["created"];
		$date = $row["date"];
		
		echo('<p><a href="/rachel/popup.php?id='.$id.'"><b>'.$name.'</b> - '.$created.'</a></p>');
	}
		
	echo('</p></div>
	');
include('footer.php');
?>
