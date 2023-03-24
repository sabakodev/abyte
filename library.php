<?php
/**
 * Domainager - DNS Manager Library
 *
 * @version 0.6.3
 * @author Fray117
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require_once 'config.php';

class domainager {
	
	function __construct() {
	}

	public function create($name, $target, $mode) {
		$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		
		$type = ['A', 'AAAA', 'CNAME'];

		$data = [
			'type' => $type[$mode],
			'name' => $name,
			'content' => $target,
			'ttl' => 1,
			'priority' => 10,
			'proxied' => false
		];

		$curl = curl_init();
		
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://api.cloudflare.com/client/v4/zones/" . CF_ZONE . "/dns_records",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => json_encode($data),
			CURLOPT_HTTPHEADER => array(
				"Connection: keep-alive",
				"Content-Length: " . strlen(json_encode($data)),
				"Content-Type: application/json",
				"Host: api.cloudflare.com",
				"User-Agent: Domainager/0.5",
				"X-Auth-Email: " . CF_MAIL,
				"X-Auth-Key: " . CF_AUTH,
			),
		));
		
		$response = json_decode(curl_exec($curl), true);
		$err = curl_error($curl);
		
		curl_close($curl);
		
		if ($err) {
			return $err;
		} else {
			$id = $response['result']['id'];
			
			return $id;
		}
	}

	public function delete($id) {

		$curl = curl_init();
		
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://api.cloudflare.com/client/v4/zones/" . CF_ZONE . "/dns_records/" . $id,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "DELETE",
			CURLOPT_HTTPHEADER => array(
				"Host: api.cloudflare.com",
				"Content-Type: application/json",
				"User-Agent: Domainager/0.5",
				"X-Auth-Email: " . CF_MAIL,
				"X-Auth-Key: " . CF_AUTH
			)
		));
		
		$response = json_decode(curl_exec($curl), true);
		$url =  "https://api.cloudflare.com/client/v4/zones/" . CF_ZONE . "/dns_records/" . $id;
		$err = curl_error($curl);
		
		curl_close($curl);
		
		if ($response['success'] === false) {
			return false;
		} else {
			return $response;
		}
	}

	public function update($name, $id, $target, $proxy = false, $mode = 0) {
		
		$type = ['A', 'AAAA', 'CNAME'];

		$data = [
			'type' => $type[$mode],
			'name' => $name,
			'content' => $target,
			'ttl' => 1,
			'priority' => 10,
			'proxied' => $proxy
		];

		$curl = curl_init();
		
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://api.cloudflare.com/client/v4/zones/" . CF_ZONE . "/dns_records/" . $id,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "PUT",
			CURLOPT_POSTFIELDS => json_encode($data),
			CURLOPT_HTTPHEADER => array(
				"Connection: keep-alive",
				"Content-Length: " . strlen(json_encode($data)),
				"Content-Type: application/json",
				"Host: api.cloudflare.com",
				"User-Agent: Domainager/0.5",
				"X-Auth-Email: " . CF_MAIL,
				"X-Auth-Key: " . CF_AUTH,
			),
		));
		
		$response = json_decode(curl_exec($curl), true);
		$err = curl_error($curl);
		
		curl_close($curl);
		
		if ($err) {
			return "cURL Error #:" . $err;
		} else {
			return $response['success'];
		}
	}

	public function read($id) {

		$curl = curl_init();
		
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://api.cloudflare.com/client/v4/zones/" . CF_ZONE . "/dns_records/" . $id,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"Connection: keep-alive",
				"Content-Length: 0",
				"Host: api.cloudflare.com",
				"User-Agent: Domainager/0.5",
				"X-Auth-Email: " . CF_MAIL,
				"X-Auth-Key: " . CF_AUTH,
			),
		));
		
		$response = curl_exec($curl);
		$err = curl_error($curl);
		
		curl_close($curl);
		
		if ($err) {
			return "cURL Error #:" . $err;
		} else {
			return $response;
		}
	}

	public function auth($mail, $pass) {
		$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		$m = addslashes($mail);
		$p = md5($pass);

		$raw = $db->query( "SELECT * FROM `users` WHERE `mail` = '$m' AND `pass` = '$p'");

		if ($raw->num_rows >= 1) {
			return true;
		} else {
			return false;
		}
	}

	public function dump($mail, $pass) {
		$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		$m = addslashes($mail);
		$p = md5($pass);

		$raw = $db->query( "SELECT * FROM `users` WHERE `mail` = '$m' AND `pass` = '$p'");

		if ($raw->num_rows >= 1) {
			return $raw->fetch_assoc();
		} else {
			return false;
		}
	}

	public function mold($name = '', $mail, $pass) {
		$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		$u = addslashes($name);
		$m = addslashes($mail);
		$p = md5($pass);

		$raw = $db->query("INSERT INTO `users` (`name`, `mail`, `pass`) VALUES ('$u', '$m', '$p')");

		if ($raw->num_rows >= 1) {
			return true;
		} else {
			return false;
		}

	}
	
	public function outbox($from, $rm, $rn, $subject, $body) {
		
		$mail = new PHPMailer(true);

		try {
			//Server settings
			$mail->SMTPDebug = SMTP::DEBUG_SERVER;
			$mail->isSMTP();
			$mail->Host       = MAIL_HOST;
			$mail->SMTPAuth   = true;
			$mail->Username   = MAIL_USER;
			$mail->Password   = MAIL_PASS;
//			$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
			$mail->Port       = MAIL_PORT;
			
			//Recipients
			$mail->setFrom($from, 'aByte');
			$mail->addAddress($rm, $rn); 
			$mail->addReplyTo($from, 'aByte');
			
			// Attachments
//			$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//			$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
			
			// Content
			$mail->isHTML(true);
			$mail->Subject = $subject;
			$mail->Body    = $body;
//			$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
			
			$mail->send();
			return true;
		} catch (Exception $e) {
			return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}

	}

}