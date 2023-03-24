<?php
session_start(["name" => 'aByte']);

require '../library.php';

$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (isset($_GET['domain'])) {
	$dm = addslashes(strtolower($_GET['domain']));

	if ($db->query( "SELECT * FROM `domains` WHERE `name` = '$dm'")->num_rows == 0) {
		$available = true;
	} else {
		$available = false;
	}
} else {
	$dm = '';
}
?>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<link rel="apple-touch-icon" sizes="76x76" href="matkit/assets/img/apple-icon.png">
	<link rel="icon" type="image/png" href="matkit/assets/img/favicon.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Grab aByte</title>
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
	<link href="assets/css/material-kit.css?v=2.0.6" rel="stylesheet" />
</head>

<body class="profile-page sidebar-collapse">
	<nav class="navbar navbar-transparent navbar-color-on-scroll fixed-top navbar-expand-lg" color-on-scroll="100" id="sectionsNav">
		<div class="container">
			<div class="navbar-translate">
				<a class="navbar-brand" href="https://www.abyte.site/">aByte</a>
			</div>
		</div>
	</nav>
	<div class="page-header header-filter" data-parallax="true" style="background-image: url('matkit/assets/img/city-profile.jpg');"></div>
	<div class="main main-raised">
		<div class="profile-content">
			<div class="container">
				<div class="row">
					<div class="col-md-6 ml-auto mr-auto">
						<div class="profile">
							<h2 class="title">Search Domain</h2>
						</div>
					</div>
					<div class="col-lg-12 col-sm-13">
						<form>
							<div class="form-group">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text">
											<i class="material-icons">search</i>
										</span>
									</div>
									<input type="text" name="domain" class="form-control" value="<?=$dm?>" placeholder="Grab aByte">
								</div>
							</div>
						</form>
					</div>
				</div>
				<div class="section">
					<div class="row">
						<div class="col-md-12 ml-auto mr-auto">
							<?php if (!empty($dm)): ?>
							<nav class="navbar navbar-expand-lg bg-primary">
								<div class="container">
									<div class="navbar-translate">
										<a class="navbar-brand"><?=$dm?>.abyte.site</a>
									</div>
									<div class="collapse navbar-collapse">
										<ul class="navbar-nav ml-auto">
											<li class="nav-item">
												<?php if($available): ?>
												<a href="dash.php?create=<?=$dm?>" class="nav-link"><button class="btn btn-success">Purchase Now</button></a>
												<?php else: ?>
												<a href="" class="nav-link"><button class="btn btn-danger">Can't Purchase</button></a>
												<?php endif ?>
											</li>
										</ul>
									</div>
								</div>
							</nav>
							<?php else: ?>
							<h4 class="text-center">Start searching domain now!</h4>
							<?php endif ?>
						</div>
					</div>
				</div>
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
	<!--   Core JS Files   -->
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://unpkg.com/popper.js@1.12.6/dist/umd/popper.js" integrity="sha384-fA23ZRQ3G/J53mElWqVJEGJzU0sTs+SvzG8fXVWP+kJQ1lwFAOkcUOysnlKJC33U" crossorigin="anonymous"></script>
	<script src="https://unpkg.com/bootstrap-material-design@4.1.1/dist/js/bootstrap-material-design.js" integrity="sha384-CauSuKpEqAFajSpkdjv3z9t8E7RlpJ1UP0lKM/+NdtSarroVKu069AlsRPKkFBz9" crossorigin="anonymous"></script>
	<script src="https://unpkg.com/nouislider@14.1.1/distribute/nouislider.min.js" type="text/javascript"></script>
	<script src="assets/js/material-kit.min.js?v=2.0.6" type="text/javascript"></script>
	<script src="assets/js/main.js" type="text/javascript"></script>
</body>

</html>
