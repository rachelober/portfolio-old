<?php
// ******************************************************************************************************
// Gallery Script
// Copyright 2003 Rachel Ober
// http://e-ober.com/rachel-ober/
// ******************************************************************************************************
// dbcnx.php
// Connect to the database
// ******************************************************************************************************
// Identifiers
	global $cnx_server, $cnx_username, $cnx_password, $cnx_database;
	$cnx_server = 'localhost';
	$cnx_username = 'blah';
	$cnx_password = 'blah';
	$cnx_database = 'blah';
// Connect to the database server 
	$dbcnx = @mysql_connect($cnx_server, $cnx_username, $cnx_password);
	if (!$dbcnx) { 
    	echo('<p>Unable to connect to the database server at this time.</p>'); 
    	exit(); 
	}

// Select directory database
	if (!@mysql_select_db($cnx_database) ) { 
    	echo('<p>Unable to locate the database <b>'.$cnx_database.'</b> at this time.</p>'); 
    	exit(); 
	 } 
// Globals for all pages
	define("BASE_URL","http://e-ober.com/rachel-ober/");	// URL to your website, remember trailing slash!
	define("SITE_NAME","Rachel Ober's Portfolio");			// Name of the site
	define("ADMIN_NAME","Rachel Ober");						// Administrator's name
	define("ADMIN_EMAIL","rhul@mintkiss.net");				// Administrator's email
	define("IMAGES","images/");								// Image Directory
	define("THUMBS","thumbnails/");							// Thumbnail Directory (inside of the image directory)
?>
