<?php
clearstatcache();

$key = 'Passw0rd!';

if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
 	$address = trim(end(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])));
  	$proxy = $_SERVER['REMOTE_ADDR'];
 } else {
  	$address = $_SERVER['REMOTE_ADDR'];
  	$proxy = 'OFF';
 }

 if (isset($_SERVER['HTTP_REFERER'])) {
 	$referrer = $_SERVER['HTTP_REFERER'];
 } else {
	$referrer = 'NONE';
}

$headers = getallheaders();

function headout($headers) {
	foreach ($headers as $name => $value) {
		if (preg_match("~\bcurl\b~", $_SERVER['HTTP_USER_AGENT'])) {
			print "$name: $value\n";
		} else {
			print "<style media='screen' type='text/css'>html{font-family:'Courier New'}</style>";
			print "<b>" . strtoupper($name) . "</b>: $value<br>";
		}
	}
}

if (isset($_SESSION['xLeaker']) && $_SESSION['xLeaker']) {
	headout($headers);
	exit;
}

if (isset($_GET['key']) && ($_GET['key'] == $key)) {
	headout($headers);
	$_SESSION['xLeaker'] = true;
	exit;
}

echo 'Forbidden, use your KEY to access this Services';
?>
