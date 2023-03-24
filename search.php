<?php
session_start(["name" => 'aByte']);

require 'library.php';

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
<html class="no-js" lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title id="title"></title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- <link rel="manifest" href="site.webmanifest"> -->
	<link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
	<!-- Place favicon.ico in the root directory -->

	<!-- CSS here -->
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/magnific-popup.css">
	<link rel="stylesheet" href="assets/css/font-awesome.min.css">
	<link rel="stylesheet" href="assets/css/flaticon.css">
	<link rel="stylesheet" href="assets/css/animate.css">
	<link rel="stylesheet" href="assets/css/style.css">
	<link rel="stylesheet" href="assets/css/sweetalert2.min.css">
	<!-- <link rel="stylesheet" href="css/responsive.css"> -->
</head>

<body>
	<!--[if lte IE 9]>
			<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
		<![endif]-->

	<!-- header-start -->
	<header>
		<div class="header-area ">
			<div id="sticky-header" class="main-header-area">
				<div class="container-fluid p-0">
					<div class="row align-items-center no-gutters">
						<div class="col-xl-12 col-lg-12 d-none d-lg-block">
							<div class="log_chat_area d-flex align-items-center">
								<a id="auth" href="#login" class="login popup-with-form">
									<i class="flaticon-user"></i>
									<span>log in</span>
								</a>
							</div>
						</div>
						<div class="col-12">
							<div class="mobile_menu d-block d-lg-none"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</header>
	<!-- header-end -->

	<!-- bradcam_area_start -->
	<div class="bradcam_area2 breadcam_bg_2 ">
		<div class="container">
			<div class="row align-items-center justify-content-center">
				<div class="col-xl-9">
					<div class="breadcam_text text-center">
						<h3>Search your Domain</h3>
						<div class="find_dowmain">
							<form action="" class="find_dowmain_form">
								<input type="text" name="domain" value="<?=$dm?>" placeholder="Find your domain">
								<button type="submit">search</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- bradcam_area_end -->

	<!-- search_area_start -->
	<div class="search_area">
		<div class="container">
			<div class="row">
				<div class="col-xl-12">
					<div class="search_title">
						<h3>Search Result</h3>
					</div>
				</div>
			</div>
			<div class="row mb-20">
				<div class="col-xl-6">
					<div class="search_result_name">
						<h4>Domain name</h4>
					</div>
				</div>
				<div class="col-xl-6">
					<div class="search_result_prise text-right">
						<h4>Pricing</h4>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xl-12">
					<?php if (!empty($dm)) { ?>
						<div class="single_search d-flex justify-content-between align-items-center">
							<div class="name_title">
								<h4><?=$dm?>.abyte.site</h4>
							</div>
							<div class="prising_content">
								<a class="premium" href="#">Free</a>
								<a href="#">1st Year Free</a> 
								<?php if ($available): ?>
									<a class="boxed_btn_green" href="dash.php?create=<?=$dm?>">Get Now</a>
								<?php else: ?>
									<a class="boxed_btn_green" style="border: 1px solid#dc3545;color:#fff !important;background-color:#dc3545;" href="search.php">Already Registered</a>
								<?php endif ?>
							</div>
						</div>
					<?php } else { ?><p>Try Searching Now!</p><?php } ?>
				</div>
			</div>
		</div>
	</div>
	<!-- search_area_end -->

	<!-- footer -->
	<footer class="footer">
		<div class="copy-right_text">
			<div class="container">
				<div class="footer_border"></div>
				<div class="row">
					<div class="col-xl-12">
						<p class="copy_right text-center">
							<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
							Copyright &copy; <script>document.write(new Date().getFullYear());</script> All rights reserved | Designed by <a href="https://colorlib.com" target="_blank">Colorlib</a>
							<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
						</p>
					</div>
				</div>
			</div>
		</div>
	</footer>
	<!-- footer -->
	<!-- link that opens popup -->

	<!-- form itself end-->
	<form id="login" method="post" action="controller.php" class="white-popup-block mfp-hide">
		<div class="popup_box ">
			<div class="popup_inner">
				<div class="logo text-center">
					<h1>aByte</h1>
				</div>
				<h3>Sign in</h3>
				<form action="#">
					<div class="row">
						<div class="col-xl-12 col-md-12">
							<input type="email" name="mail" placeholder="Enter email">
						</div>
						<div class="col-xl-12 col-md-12">
							<input type="password" name="pass" placeholder="Password">
						</div>
						<div class="col-xl-12">
							<button type="submit" id="loginButton" class="boxed_btn_green">Sign in</button>
						</div>
					</div>
				</form>
				<p class="doen_have_acc">Don’t have an account? <a class="dont-hav-acc" href="#register">Sign Up</a>
				</p>
			</div>
		</div>
	</form>
	<!-- form itself end -->

	<!-- form itself end-->
	<form id="register" method="post" action="controller.php?create" class="white-popup-block mfp-hide">
		<div class="popup_box ">
			<div class="popup_inner">
				<div class="logo text-center">
					<h1>aByte</h1>
				</div>
				<h3>Resistration</h3>
				<form action="#">
					<div class="row">
						<div class="col-xl-12 col-md-12">
							<input type="text" name="name" placeholder="Enter name">
						</div>
						<div class="col-xl-12 col-md-12">
							<input type="email" name="mail" placeholder="Enter email">
						</div>
						<div class="col-xl-12 col-md-12">
							<input type="password" name="pass" placeholder="Password">
						</div>
						<div class="col-xl-12 col-md-12">
							<input type="Password" placeholder="Confirm password">
						</div>
						<div class="col-xl-12">
							<button type="submit" id="registerButton" class="boxed_btn_green">Sign Up</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</form>
	<!-- form itself end -->

	<!-- JS here -->
<script src="assets/js/vendor/modernizr-3.5.0.min.js"></script>
	<script src="assets/js/vendor/jquery-1.12.4.min.js"></script>
	<script src="assets/js/popper.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/isotope.pkgd.min.js"></script>
	<script src="assets/js/ajax-form.js"></script>
	<script src="assets/js/jquery.counterup.min.js"></script>
	<script src="assets/js/scrollIt.js"></script>
	<script src="assets/js/jquery.scrollUp.min.js"></script>
	<script src="assets/js/wow.min.js"></script>
	<script src="assets/js/jquery.magnific-popup.min.js"></script>
	<script src="assets/js/sweetalert2.all.min.js"></script>

	<script src="assets/js/main.js"></script>
</body>

</html>