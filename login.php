<?php
session_start(["name" => 'aByte']);

require 'library.php';

$dmg = new domainager();

if (isset($_GET['esc'])) {
	unset($_SESSION['dmg_creds']);
	header('Location: .');
	exit;
}

if (isset($_SESSION['dmg_creds'])) {
	$creds = json_decode(gzinflate(base64_decode($_SESSION['dmg_creds'])), true);
	if ($dmg->auth($creds[0], $creds[1])) {
		header('Location: dash.php');
		exit;
	}
}

// Account Creator
if (isset($_POST['mail']) && isset($_POST['pass']) && isset($_GET['register'])) {
	
	if ($dmg->mold($_POST['name'], $_POST['mail'], $_POST['pass'])) {
		$_SESSION['dmg_creds'] = base64_encode(gzdeflate(json_encode(array($_POST['mail'], $_POST['pass']))));
		header('Location: dash.php');
		exit;
	}
}


// Account Authentication
if (isset($_POST['mail']) && isset($_POST['pass'])) {
	
	if ($dmg->auth($_POST['mail'], $_POST['pass'])) {
		$_SESSION['dmg_creds'] = base64_encode(gzdeflate(json_encode(array($_POST['mail'], $_POST['pass']))));
		header('Location: dash.php');
		exit;
	}
}

?>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
		<meta name="generator" content="Jekyll v3.8.6">
		<title>aByte</title>

		<!-- Bootstrap core CSS -->
		<link href="assets/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
		<style>
			.bd-placeholder-img {
				font-size: 1.125rem;
				text-anchor: middle;
				-webkit-user-select: none;
				-moz-user-select: none;
				-ms-user-select: none;
				user-select: none;
			}

			@media (min-width: 768px) {
				.bd-placeholder-img-lg {
					font-size: 3.5rem;
				}
			}

			html, body {
				height: 100%;
			}
			
			body {
				display: -ms-flexbox;
				display: flex;
				-ms-flex-align: center;
				align-items: center;
				padding-top: 40px;
				padding-bottom: 40px;
				background-color: #f5f5f5;
			}
			
			.form-signin {
				width: 100%;
				max-width: 330px;
				padding: 15px;
				margin: auto;
			}

			.form-signin .checkbox {
				font-weight: 400;
			}

			.form-signin .form-control {
				position: relative;
				box-sizing: border-box;
				height: auto;
				padding: 10px;
				font-size: 16px;
			}

			.form-signin .form-control:focus {
				z-index: 2;
			}

			.form-signin input[type="email"] {
				margin-bottom: -1px;
				border-bottom-right-radius: 0;
				border-bottom-left-radius: 0;
			}

			.form-signin input[type="password"] {
				margin-bottom: 10px;
				border-top-left-radius: 0;
				border-top-right-radius: 0;
			}
		</style>
	</head>
	<body class="text-center">
		<form class="form-signin" method="post">
			<?php if (isset($_GET['register'])): ?>
				<input type="text" style="margin-bottom:-1px;border-bottom-left-radius:0;border-bottom-right-radius:0" name="name" class="form-control" placeholder="Full Name">
			<?php endif ?>
			<input type="email" <?php if (isset($_GET['register'])) {echo 'style="border-top-left-radius: 0;border-top-right-radius: 0;"';} ?> name="mail" class="form-control" placeholder="Email address" required autofocus>
			<input type="password" name="pass" class="form-control" placeholder="Password" required>
			<button class="btn btn-lg btn-primary btn-block" type="submit">
				<?php if (isset($_GET['register'])): ?>
					Register
				<?php else: ?>
					Login
				<?php endif ?>
			</button>
			<?php if (isset($_GET['register'])): ?>
				<a href="?" class="small">Already Have Account?</a>
			<?php else: ?>
				<a href="?register" class="small">Don't Have Account yet?</a>
			<?php endif ?>
			<p class="mt-3 mb-3 text-muted">
				Copyright &copy; <script type="text/javascript">document.write(new Date().getFullYear());</script> aByte<br><a href="https://exploiter.id/">ExploiterID</a> and <a href="https://armiko.moe/">Armiko.moe</a>
			</p>
		</form>
	</body>
</html>