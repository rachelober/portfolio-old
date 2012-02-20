<?php
// ******************************************************************************************************
// Cyberpet Database User Interface and Admin
// Copyright 2002-2003 by Rachel Ober
// http://mintkiss.net/
// ******************************************************************************************************
// functions.php
// All repeated functions computed and executed from this file.
// ******************************************************************************************************
// Generate random password for retrieval and send it to user.
// ******************************************************************************************************
function randomInt($type){
	switch ($type){
		case "letter":
			$num = rand(0,25);
			if ($num > 25)
				$num = $num - 1;
		break;
		case "number":
			$num = rand(0,9);
			if ($num > 9)
				$num = $num - 1;
		break;
	}
	return $num;
} 

function createPass() {
	$LCase = "abcdefghijklmnopqrstuvwxyz";
	$UCase = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$Integer = "0123456789";

	define("LENGTH", "1");

	$a = randomInt("letter");
	$b = randomInt("letter");
	$c = randomInt("letter");
	$d = randomInt("letter");
	$e = randomInt("number");
	$f = randomInt("number");

	$L1 = substr($LCase, $a, LENGTH);
	$L2 = substr($LCase, $b, LENGTH);
	$U1 = substr($UCase, $c, LENGTH);
	$U2 = substr($UCase, $d, LENGTH);
	$I1 = substr($Integer, $e, LENGTH);
	$I2 = substr($Integer, $f, LENGTH);

	$pass = $L1.$U2.$I1.$L2.$I2.$U1;

	return($pass);
}

function enterPass($pass,$name) {
	$key = 'Ra';						// Encrypt key
	$userpass = crypt($pass,$key);		// Encrypt the password

	$sql = "UPDATE owners SET password='$userpass' WHERE name='$name'";
	 if (!@mysql_query($sql))
		echo('<p>Error editing password: '.mysql_error().'</p>');
}

function sendMail($pass,$name) {
	$query = "SELECT email FROM owners WHERE name='$name'"; 
	$result = mysql_query($query);
	if (!$result) { 
		echo('<p>Error performing query: '.mysql_error().'</p>');
		exit(); 
	}
	$row = mysql_fetch_array($result);
	$email = $row["email"];

	$message = 'Hello '.$name.', below is your password to log in at '.SITE_NAME.'. Please make sure to save this email in case you forget your password. Remember that you can also change this password once you enter the control panel. \n\n Your password is: '.$pass.'\n\n You can log in at '.BASEURL.'. \n\n Regards,<br> '.ADMIN_NAME.'\n Thanks for using my program!'; 
	$subject = "New Password for ".SITENAME.""; 

	$headers = "From: ".ADMIN_NAME." <".ADMIN_EMAIL.">\r\n";  
	$headers .= "Reply-To: ".ADMIN_NAME." <".ADMIN_EMAIL.">\r\n"; 
	$headers .= "Return-Path: ".ADMIN_NAME." <".ADMIN_EMAIL.">\r\n";

	mail($email, $subject, $message, $headers); 
}

// ******************************************************************************************************
// Get Page Load Time
// ******************************************************************************************************
function getExecutionTime() {
   $mtime = microtime(); 
   $mtime = explode(" ",$mtime); 
   $mtime = $mtime[1] + $mtime[0]; 
   $starttime = $mtime;    

   $mtime = microtime(); 
   $mtime = explode(" ",$mtime); 
   $mtime = $mtime[1] + $mtime[0];
   $endtime = $mtime; 
   $totaltime = ($endtime - $starttime);

   echo ($totaltime); 
}

// ******************************************************************************************************
// ++ ALL following Files deal with Display of Parent Lists
// ******************************************************************************************************
// Display Table Top
// ******************************************************************************************************
function tableTop() {
	echo('<table border="0" cellspacing="5" cellpadding="5" width="75%" align="center">');
}

// ******************************************************************************************************
// Display Table Bottom
// ******************************************************************************************************
function tableBottom() {
	echo('</table>');
}

// ******************************************************************************************************
// Get Cyberpet's Species Prefix and Error Check
// ******************************************************************************************************
function idPrefix() {
	global $table;
	
	$query = "SELECT prefix FROM stats WHERE cyberpets='$table'"; 
	$result = mysql_query($query);
	if (!$result) { 
		giveError();
		exit(); 
	}
	$row = mysql_fetch_array($result);
	$pre = $row["prefix"];

	return ($pre);
}

// ******************************************************************************************************
// Generate Previous and Next Links
// ******************************************************************************************************
function generateLink($start){
	global $PHP_SELF, $table;

	if (isset($start)){
		$query = "SELECT * FROM $table LIMIT $start, 20";
		$query = "SELECT count(*) as count FROM $table"; 
		$result = mysql_query($query);
		if (!$result) { 
			echo('<p>Error performing query: '.mysql_error().'</p>');
 			exit(); 
		}
		$row = mysql_fetch_array($result);
		$numrows = $row["count"];
		
		if($start > 0)
			echo ('<a href="'.$PHP_SELF.'?table='.$table.'&start='.($start - 20).'">Previous</a> - ');
		else
			echo ('Previous - ');

		if($numrows > ($start + 20))
			echo ('<a href="'.$PHP_SELF.'?table='.$table.'&start='.($start + 20).'">Next</a>');
		else
			echo ('Next');
	}
	else
		echo('');
}

// ******************************************************************************************************
// Print Error when Invalid Cyberpet Chosen
// ******************************************************************************************************
function giveError() {
	global $PHP_SELF;

	echo('Invalid Cyberpet Chosen. Please do not manipulate the generated URLs. <a href="'.$PHP_SELF.'">Go back</a> and chose a cyberpet.');

	include (BASE_URL.'footer.php');
	exit();
}

// ******************************************************************************************************
// Is the Cyberpet on a page and linked?
// ******************************************************************************************************
function getStatus($status) {
	switch ($status){
		case '1':
			$print = '(?)';   			// Not on Page
			break;
		case '2':
			$print = '(No Link)';		// Not Linked
			break;
		case '3':
			$print = '(404)';			// 404 Error - Will be readopted out
			break;
		case '4':
			$print = '(Not Sent)';		// Cyberpet not sent yet / Not drawn / Not colored
			break;
	}
	return ($print);
}

// ******************************************************************************************************
// What's the Cyberpetpet's Gender?
// ******************************************************************************************************
function getGenderName($x) {
	switch ($x){
		case 'f':
			$print = 'Female'; 		// Female Gender
			break;
		case 'm':
			$print = 'Male';		// Male Gender
			break;
	}
	return ($print);
}

// ******************************************************************************************************
// Cyberpet's Full ID Number
// ******************************************************************************************************
function getFullID($id, $gender) {
	global $table;

	$pre = idPrefix();
	$fullID = $pre.$id.$gender;
	return ($fullID);
}

// ******************************************************************************************************
// Get Owner's Name
// ******************************************************************************************************
function getOwnerName($owner) {
	if (!isset($owner))
		$owner = '2'; 

	$getOwnerName = @mysql_query("SELECT * FROM owners WHERE id='$owner'");
	$row = mysql_fetch_array($getOwnerName);
	$ownerName = $row[name];

	return ($ownerName);
}

// ******************************************************************************************************
// Get Sire's Info
// ******************************************************************************************************
function getParentInfo($parent) {
	global $table;

	if ($parent == '0')
		$info = 'Wild';
	else {
		$getInfo = @mysql_query("SELECT * FROM $table WHERE id='$parent'");
		$row = mysql_fetch_array($getInfo);

		if ($table == 'chaetrill')
			$info = $row[prefix]."'".$row[name]." (".$row[id].")";
		else 
			$info = $row[name]." (".$row[id].")";
	}

	return ($info);
}

// ******************************************************************************************************
// Get Full Name
// ******************************************************************************************************
function getFullName($name, $prefix) {
	global $table;
	
	if ($table == 'chaetrill')
		$fullName = $prefix."'".$name;
	else
		$fullName = $name;

	return ($fullName);
}

// ******************************************************************************************************
// ++ ALL following Files deal with user and administrator control panel(s).
// ******************************************************************************************************
// Permissions Error
// ******************************************************************************************************
function permissionError(){
	global $loggedname;

	if (!isset($loggedname)){
		echo('<p align="center"><font class="notice">You do not have permission to access this part of the site, please log in first!</font></p>');

	include(BASE_URL.'footer.php');

	exit();
	}
}

// ******************************************************************************************************
// Admin Check
// ******************************************************************************************************
function adminCheck(){
	global $loggedrank;

	if ($loggedrank != '4'){
		echo('<p align="center"><font class="notice">You do not have permission to access this part of the site, you must be an administrator!</font></p>');
	
	include(BASE_URL.'footer.php');

	exit();
	}
}

// ******************************************************************************************************
// Process Drop Down Menu
// ******************************************************************************************************
function getDropDown($setID){
	global $table, $loggedrank, $loggedid;
	
	if ($loggedrank == '4')
		$add = "";

	else
		$add = "WHERE owner='$loggedid'";

	echo('<select name="setidagain" onChange="reload(this.options[this.selectedIndex].value);">');

	// Start to Generate the IDs of all Cyberpets
	$chooseID = '<option value="">  </option>';
	$result = @mysql_query("SELECT id, gendershort FROM $table $add ORDER BY id ASC");
	while ($row = mysql_fetch_array($result)) { 
		$id = $row["id"];
		$gendershort = $row["gendershort"];
		$fullID = getFullID($id, $gendershort, $table);
		// If idset is equal to id, then option is selected, else just print.
		if ($setID == $id)
			$chooseID .= '<option value="'.$id.'&table='.$table.'" selected>'.$fullID.'</option>';
		else
			$chooseID .= '<option value="'.$id.'&table='.$table.'">'.$fullID.'</option>';
	}

	echo ($chooseID);

	echo ('</select>');
}

// ******************************************************************************************************
// Pick Sire
// ******************************************************************************************************
function pickSire($sire){
	global $table;

	echo('<select name="sire">');
	$result = @mysql_query("SELECT * FROM $table WHERE gendershort='m' ORDER BY name ASC");
	if (!$result) { 
		echo('<p>Error performing query: '.mysql_error().'</p>'); 
		exit();
	}

	$pickSire = '<option value="0">Wild</option>';

	while ($row = mysql_fetch_array($result)) { 
		$id = $row["id"];							// Sire's ID
		$prefix = $row["prefix"];					// Sire's Prefix
		$name = $row["name"];						// Sire's Name
		$gendershort = $row["gendershort"];			// Sire's Gender
		$fullName = getFullName($name, $prefix, $table);
		$fullID = getFullID($id, $gendershort, $table);
		$sireInfo = $fullName." (".$fullID.")";
		if ($id == $sire)
			$pickSire .= '<option value="'.$id.'" '.$sireid.' selected>'.$sireInfo.'</option>';
		else
			$pickSire .= '<option value="'.$id.'" '.$sireid.'>'.$sireInfo.'</option>';
	}

	echo($pickSire);

	echo ('</select>');
}

// ******************************************************************************************************
// Pick Dam
// ******************************************************************************************************
function pickDam($dam){
	global $table;

	echo('<select name="dam">');

	$result = @mysql_query("SELECT * FROM $table WHERE gendershort='f' ORDER BY name ASC");
	if (!$result) { 
		echo('<p align="center"><font class="notice">Error performing query: '.mysql_error().'</font></p>'); 
   		exit();
	}

	$pickDam = '<option value="0">Wild</option>';

	while ($row = mysql_fetch_array($result)) { 
		$id = $row["id"];							// Dam's ID
		$prefix = $row["prefix"];					// Dam's Prefix
		$name = $row["name"];						// Dam's Name
		$gendershort = $row["gendershort"];			// Dam's Gender
		$fullName = getFullName($name, $prefix, $table);
		$fullID = getFullID($id, $gendershort, $table);
		$damInfo = $fullName." (".$fullID.")";
		if ($id == $dam)
			$pickDam .= '<option value="'.$id.'" '.$damid.' selected>'.$damInfo.'</option>';
		else
			$pickDam .= '<option value="'.$id.'" '.$damid.'>'.$damInfo.'</option>';
	}

	echo($pickDam);

	echo ('</select>');
}

// ******************************************************************************************************
// Get Owners
// ******************************************************************************************************
function getOwnerList($owner){
	echo('<select name="owner">');

	$result = @mysql_query("SELECT id, name FROM owners ORDER BY name ASC"); 
	if (!$result) { 
		echo('<p align="center"><font class="notice">Error performing query: '.mysql_error().'</font></p>'); 
    	exit();
	}

	while ($row = mysql_fetch_array($result)) { 
		$id = $row["id"];				// Owner's ID
		$name = $row["name"];			// Owner's Name
		if ($id == $owner)
			$pickOwner .= '<option value="'.$id.'" '.$ownerid.' selected>'.$name.'</option>';
		else
			$pickOwner .= '<option value="'.$id.'" '.$ownerid.'>'.$name.'</option>';
	}

	echo ($pickOwner);

	echo ('</select>');
}

// ******************************************************************************************************
// Display Login/Logout Menu
// ******************************************************************************************************
function displayBottom(){
	global $PHP_SELF;

	echo ('<p align="center"><a href="'.$PHP_SELF.'">Back to Section</a> | <a href="'.BASE_URL.'login.php">Back to Control Panel</a><br><a href="'.BASE_URL.'cookie.php?submit=Logout">Log Out</a></p>');
}
?>