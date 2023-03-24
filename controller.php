<?php
/**
 * Mind Controller
 *
 * @author Fray117
 */

// Initialize API
session_start(["name" => 'aByte']);
header('Content-Type: application/json');

// Load Library
require 'library.php';
$dmg = new domainager();

// Account Creator
if (isset($_POST['mail']) && isset($_POST['pass']) && isset($_GET['create'])) {
	
	if ($dmg->mold($_POST['name'], $_POST['mail'], $_POST['pass'])) {
		$_SESSION['dmg_creds'] = base64_encode(gzdeflate(json_encode(array($_POST['mail'], $_POST['pass']))));
		echo json_encode(['error' => false, 'data' => ['name' => $_POST['name'], 'mail' => $_POST['mail'], 'pass' => md5($_POST['pass'])]]);
	} else {
		echo json_encode(['error' => true, 'data' => ['name' => $_POST['name'], 'mail' => $_POST['mail'], 'pass' => md5($_POST['pass'])]]);
	}
}


// Account Authentication
if (isset($_POST['mail']) && isset($_POST['pass'])) {
	
	if ($dmg->auth($_POST['mail'], $_POST['pass'])) {
		$_SESSION['dmg_creds'] = base64_encode(gzdeflate(json_encode(array($_POST['mail'], $_POST['pass']))));
		echo json_encode(['error' => false, 'data' => ['mail' => $_POST['mail'], 'pass' => md5($_POST['pass'])]]);
	} else {
		echo json_encode(['error' => true, 'data' => ['mail' => $_POST['mail'], 'pass' => md5($_POST['pass'])]]);
	}
}

if (isset($_GET['validate'])) {
	if (isset($_SESSION['dmg_creds'])) {
		$raw = json_decode(gzinflate(base64_decode($_SESSION['dmg_creds'])), true);
		if ($dmg->auth($raw[0], $raw[1])) {
			echo json_encode(['success' => true]);
		} else {
			echo json_encode(['success' => false]);
		}
	} else {
		echo json_encode(['success' => false]);
	}
}