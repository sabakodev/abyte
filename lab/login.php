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
<!--

	Created based on Material Kit v2.0.6

-->
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<link rel="apple-touch-icon" sizes="76x76" href="matkit/assets/img/apple-icon.png">
	<link rel="icon" type="image/png" href="matkit/assets/img/favicon.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>aByte Login</title>
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
	<!-- Fonts and icons -->
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
	<!-- CSS Files -->
	<link href="assets/css/material-kit.css?v=2.0.6" rel="stylesheet" />
	<link href="assets/css/abyte.css" rel="stylesheet" />
</head>

<body class="login-page sidebar-collapse">
	<nav class="navbar navbar-transparent navbar-color-on-scroll fixed-top navbar-expand-lg" color-on-scroll="100" id="sectionsNav">
		<div class="container">
			<div class="navbar-translate">
				<a class="navbar-brand" href="/">aByte</a>
			</div>
		</div>
	</nav>
	<div class="page-header header-filter" style="background-image: url('assets/img/bg7.jpg'); background-size: cover; background-position: top center;">
		<div class="container">
			<div class="row">
				<div class="col-lg-4 col-md-6 ml-auto mr-auto">
					<div class="card card-login <?php if (isset($_GET['register'])): ?>signup<?php endif ?>">
						<form class="form" method="post">
							<div class="card-body">
								<?php if (isset($_GET['register'])) : ?>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text">
												<i class="material-icons">face</i>
											</span>
										</div>
										<input type="text" name="name" class="form-control" placeholder="Full Name">
									</div>
								<?php endif ?>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text">
											<i class="material-icons">mail</i>
										</span>
									</div>
									<input type="email" name="mail" class="form-control" placeholder="Email Address">
								</div>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text">
											<i class="material-icons">lock_outline</i>
										</span>
									</div>
									<input type="password" name="pass" class="form-control" placeholder="Password">
								</div>
							</div>
							<div class="footer text-center">
								<button class="btn btn-primary btn-link btn-wd btn-lg" type="submit">Get Started</button>
								
								<?php if (isset($_GET['register'])): ?>
									<a href="?" class="small">Already Have Account?</a>
								<?php else: ?>
									<a href="?register" class="small">Don't Have Account yet?</a>
								<?php endif ?>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<footer class="footer footer-default">
			<div class="container">
				<nav class="float-left">
					<ul>
						<li>
							<a href="https://news.otabyte.net/">
								Blog
							</a>
						</li>
					</ul>
				</nav>
				<div class="copyright float-right">
					&copy;
					<script>
						document.write(new Date().getFullYear())
					</script> aByte by <a href="https://armiko.moe/">armiko.moe</a> and <a href="https://exploiter.id/">ExploiterID</a>
				</div>
			</div>
		</footer>
	</div>
</body>

</html>
