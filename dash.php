<?php
/**
 * Dashboard
 *
 * @author Fray117
 */

session_start(["name" => 'aByte']);

require 'library.php';

$dmg = new domainager();

$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (isset($_SESSION['dmg_creds'])) {
	$creds = json_decode(gzinflate(base64_decode($_SESSION['dmg_creds'])), true);
	if (!$dmg->auth($creds[0], $creds[1])) {
		header('Location: login.php');
		exit;
	}
} else {
	header('Location: login.php');
	exit;
}

$usr = $dmg->dump($creds[0], $creds[1]);
$usr = $usr['id'];

if (isset($_GET['delete'])) {
	header('Content-Type: application/json');
	
	$dm = addslashes($_GET['delete']);
	$out = $dmg->delete($dm);

	if (!$out) {
		echo json_encode(['error']);
	} else {
		$raw = $db->query("DELETE FROM `domains` WHERE `domains`.`id` = '$dm' AND `domains`.`owner` = '$usr'");
		header('Location: dash.php');
	}

	exit;
}

if (isset($_GET['create'])) {
	
	$na = addslashes($_GET['create']);

	$i4 = '127.0.0.1';

	$raw = $db->query( "SELECT * FROM `domains` WHERE `name` = '$na'");
	
	if ($raw->num_rows > 0) {
		echo "This domains already registered by someone else.";
	} else {
		if ($id = $dmg->create($na, $i4, 0)) {
			if ($db->query("INSERT INTO `domains` (`id`, `name`, `cname`, `point4`, `point6`, `owner`, `status`) VALUES ('$id', '$na', '$cn', '$i4', '$i6', '$usr', '1')")) {
				header('Location: dash.php?edit=' . $id);
			} else {
				echo $db->error;
			}
		} else {
			http_response_code(502);
		}
	}
}

if (isset($_GET['update'])) {
	
	if (isset($_POST['proxy']) && ($_POST['proxy'] == 'true')) {
		$proxy = [true, 1];
	} else {
		$proxy = [false, 0];
	}
	
	$dm = addslashes(htmlspecialchars(strtolower($_GET['update'])));
	$md = (int)$_POST['mode'];
	$tx = [ addslashes(htmlspecialchars(strtolower($_POST['IPv4']))), addslashes(htmlspecialchars(strtolower($_POST['IPv6']))), addslashes(htmlspecialchars(strtolower($_POST['cname']))) ];
	$px = (bool)$_POST['proxy'];
					
	$raw = $db->query( "SELECT * FROM `domains` WHERE `id` = '$dm'");
					
	while ($row = $raw->fetch_assoc()) {
		
		$ud = $dmg->update($row['name'], $dm, $tx[$_POST['mode']], $px, $md);
		
		if ($ud) {
			$db->query("UPDATE `domains` SET `mode` = '$md', `cname` = '$tx[2]', `point4` = '$tx[0]', `point6` = '$tx[1]', `status` = '$proxy[1]' WHERE `domains`.`id` = '$dm'");
			header('Location: dash.php');
		} else {
			file_put_contents('error_log', date("F j, Y, g:i a") . ' - aByte Dashboard: ' . $ud, FILE_APPEND);
			include 'error.php';
		}
	}
	
	exit;
}

?>
<html lang="en" class="h-100">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
		<meta name="generator" content="Jekyll v3.8.6">
		<title>aByte</title>

		<link rel="canonical" href="https://abyte.site/dash/">

		<!-- Bootstrap core CSS -->
		<link href="/assets/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">


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

			main > .container {
				padding: 60px 15px 0;
			}
			
			.footer {
				background-color: #f5f5f5;
			}
			
			.footer > .container {
				padding-right: 15px;
				padding-left: 15px;
			}
			
			code {
				font-size: 80%;
			}
		</style>
	</head>
	<body class="d-flex flex-column h-100">
		<header>
			<!-- Fixed navbar -->
			<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
				<a class="navbar-brand" href="https://abyte.site/">aByte</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarCollapse">
					<ul class="navbar-nav mr-auto">
						<li class="nav-item active dropdown">
							<a class="nav-link dropdown-toggle" href="dash.php" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Manage Domain <span class="sr-only">(current)</span>
							</a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdown">
								<a class="dropdown-item" href="dash.php">Domain Lists</a>
								<a class="dropdown-item" href="search.php">+ Add Domain</a>
							</div>
						</li>
						<li class="nav-item">
							<a class="nav-link disabled" href="" tabindex="-1" aria-disabled="true">URL Trim</a>
						</li>
					</ul>
					<a href="login.php?esc"><button class="btn btn-outline-danger">Logout</button></a>
				</div>
			</nav>
		</header>

		<!-- Begin page content -->
		<main role="main" class="flex-shrink-0">
			<div class="container">
				<?php
				
				if (isset($_GET['edit'])) {
					
					if (isset($_GET['m'])) {
						$m = $_GET['m'];
					} else {
						$m = 0;
					}
					
					$dm = addslashes(strtolower($_GET['edit']));
					
					$raw = $db->query( "SELECT * FROM `domains` WHERE `id` = '$dm'");
					
					while ($row = $raw->fetch_assoc()) {
						$data = $row;
					}
				?>
					<div class="row">
							<div class="col-md-12 order-md-1">
								<h1 class="mt-5">Update Domain</h1>
								<form class="needs-validation" novalidate="" method="post" action="?update=<?=$dm?>&m=<?=$m?>">
									<div class="mb-3">
										<label for="domain">Domain</label>
										<div class="input-group">
											<div class="input-group-prepend">
											</div>
											<input type="text" class="form-control" id="domain" value="<?=$data['name']?>.abyte.site" disabled placeholder="Domain" required name="domain">
											<div class="invalid-feedback" style="width: 100%;">
												Your domain is required.
											</div>
										</div>
									</div>
									<div class="mb-3">
										<label for="mode">Online Mode</label>
										<select name="mode" class="custom-select">
											<option disabled <?php if ($data['mode'] > 2) echo "selected" ?>>Domain Mode</option>
											<option id="i4" value="0" <?php if ($data['mode'] == 0) echo "selected" ?>>IPv4</option>
											<option id="i6" value="1" <?php if ($data['mode'] == 1) echo "selected" ?>>IPv6</option>
											<option id="cn" value="2" <?php if ($data['mode'] == 2) echo "selected" ?>>CNAME</option>
										</select>
									</div>
									<div class="mb-3" id="cc">
										<label for="cname">CNAME</label>
										<input type="text" class="form-control" id="cname" value="<?= $data['cname'] ?>" placeholder="foo.example.com" required name="cname">
										<div class="invalid-feedback">
											Please enter your server address.
										</div>
									</div>
									<div class="mb-3" id="c4">
										<label for="IPv4">IPv4 Host</label>
										<input type="text" class="form-control" id="IPv4" value="<?= $data['point4'] ?>" placeholder="127.0.0.1" required name="IPv4">
										<div class="invalid-feedback">
											Please enter your server address.
										</div>
									</div>
									<div class="mb-3" id="c6">
										<label for="IPv6">IPv6 Host</label>
										<input type="text" class="form-control" id="IPv6" value="<?= $data['point6'] ?>" placeholder="::1" name="IPv6">
									</div>
									<hr class="mb-4">
									<h4 class="mb-3">Route Proxy by Cloudflare®</h4>
									<p class="lead">Protect your site from DDoS Attack and hid your Server Host Address to give it more Privacy Protection, plus serve your sites when your server down and make it faster.</p>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="custom-control-input" id="proxy" value="true" <?php if ($data['status'] == 1) echo 'checked' ?> name="proxy" />
										<label class="custom-control-label" for="proxy">Activate Proxy</label>
									</div>
									<hr class="mb-4"><button class="btn btn-primary btn-lg btn-block" type="submit">Update</button>
								</form>
							</div>
						<script>
							var i4 = document.getElementById("i4"),
								i6 = document.getElementById("i6"),
								cn = document.getElementById("cn"),
								cc = document.getElementById("cc"),
								c4 = document.getElementById("c4"),
								c6 = document.getElementById("c6");
							
							c4.style.display = 'none';
							cc.style.display = 'none';
							c6.style.display = 'none';
							
							if (i4.selected) {
								c4.style.display = '';
							} else if (i6.selected) {
								c6.style.display = '';
							} else if (cn.selected) {
								cc.style.display = '';
							}
							
							i4.onclick = function () {
								c4.style.display = '';
								cc.style.display = 'none';
								c6.style.display = 'none';
							}
							
							i6.onclick = function () {
								c4.style.display = 'none';
								cc.style.display = 'none';
								c6.style.display = '';
							}
							
							cn.onclick = function () {
								c4.style.display = 'none';
								cc.style.display = '';
								c6.style.display = 'none';
							}
						</script>
					</div>
				<?php } else { ?>
				
					<h1 class="mt-5 text-right">Manage Domain</h1>
				
					<div class="container" style="text-align:center">
						<div class="row">
							<ul class="nav nav-tabs">
								<li class="nav-item">
									<a class="nav-link active" id="i4">IPv4</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="i6">IPv6</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="cnm">CNAME</a>
								</li>
							</ul>
						</div>
					</div>
					
					<?php
					
					$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
				
					$raw = $db->query( "SELECT * FROM `domains` WHERE `owner` = '$usr'");
				
					if ($raw->num_rows == 0) {
						echo '<p class="lead">No Domains? Start <a href="search.php">Searching</a></p>' . PHP_EOL;
					} else {
				
					?>
						<table class="table">
							<thead>
								<tr id="mhr">
									<th scope="col">Name</th>
									<th id="CH" scope="col">CNAME</th>
									<th id="AH" scope="col">IPv4</th>
									<th id="HA" scope="col">IPv6</th>
									<th scope="col">Proxy</th>
									<th scope="col">Date</th>
									<th scope="col"></th>
								</tr>
							</thead>
							<tbody>
								<?php
								while ($row = $raw->fetch_assoc()) {
									$mode = $row['mode'];
									
									$mr = $row['id'] . '&m=' . $row['mode'];
								?>
									<tr>
										<th scope="row"><?= $row['name'] ?></th>
										<td style="display: none"><?= $mode ?></td>
										<td class="CD"><a href="?update=<?=$mr?>"><?= $row['cname'] ?></a></td>
										<td class="AD"><a href="?update=<?=$mr?>"><?= $row['point4'] ?></a></td>
										<td class="DA"><a href="?update=<?=$mr?>"><?= $row['point6'] ?></a></td>
										<td><a href="?update=<?=$mr?>"><?= $row['status'] ?></a></td>
										<td><?= $row['registered'] ?></td>
										<td class="col-sm-2">
											<a href="?edit=<?=$mr?>" class="text-info">Edit</a>
											<span> - </span>
											<a href="?delete=<?=$row['id']?>" class="text-danger">Delete</a>
										</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
						<script>
							var getParentsUntil = function ( elem, parent, selector ) {
								if (!Element.prototype.matches) {
									Element.prototype.matches =
										Element.prototype.matchesSelector ||
										Element.prototype.mozMatchesSelector ||
										Element.prototype.msMatchesSelector ||
										Element.prototype.oMatchesSelector ||
										Element.prototype.webkitMatchesSelector ||
										function(s) {
											var matches = (this.document || this.ownerDocument).querySelectorAll(s), i = matches.length;
											while (--i >= 0 && matches.item(i) !== this) {}
											return i > -1;
										};
								}
								
								var parents = [];
								
								for ( ; elem && elem !== document; elem = elem.parentNode ) {
									
									if ( parent ) {
										if ( elem.matches( parent ) ) break;
									}
									
									if ( selector ) {
										
										if ( elem.matches( selector ) ) {
											parents.push( elem );
										}
										
										break;
									}
									parents.push( elem );	
								}
								
								return parents;
								
							};

							
							var at = document.getElementsByClassName('active'),
								cd = document.getElementsByClassName('CD'),
								ad = document.getElementsByClassName('AD'),
								da = document.getElementsByClassName('DA'),
								cr = document.getElementsByTagName('tr'),
								mhr = document.getElementById('mhr'),
								ah = document.getElementById('AH'),
								ha = document.getElementById('HA'),
								td = document.getElementById('i4'),
								ts = document.getElementById('i6'),
								tc = document.getElementById('cnm'),
								ch = document.getElementById('CH'),
								ec = [];
							
							tc.onclick = function() {
								tc.setAttribute('class', 'nav-link active');
								td.setAttribute('class', 'nav-link');
								ts.setAttribute('class', 'nav-link');
								
								for(i = 0;i < cd.length; ++i) {
									cd[i].style.display = "";
									da[i].style.display = "none";
									ad[i].style.display = "none";
								}
								
								for(i = 0;i < cr.length; ++i) {
									if (cr[i].cells[1].innerHTML == 0 || cr[i].cells[1].innerHTML == 1) {
										cr[i].style.display = "none";
									} else {
										cr[i].style.display = "";
									}
								}
								
								ch.style.display = "";
								ah.style.display = "none";
								ha.style.display = "none";
								mhr.style.display = "";
							}
							
							td.onclick = function() {
								td.setAttribute('class', 'nav-link active');
								tc.setAttribute('class', 'nav-link');
								ts.setAttribute('class', 'nav-link');
								
								for(i = 0;i < ad.length; ++i) {
									cd[i].style.display = "none";
									ad[i].style.display = "";
									da[i].style.display = "none";
								}
								
								for(i = 0;i < cr.length; ++i) {
									if (cr[i].cells[1].innerHTML == 1 || cr[i].cells[1].innerHTML == 2) {
										cr[i].style.display = "none";
									} else {
										cr[i].style.display = "";
										ec.push(cr[i]);
									}
								}
								
								ch.style.display = "none";
								ah.style.display = "";
								ha.style.display = "none";
								mhr.style.display = "";
							}
							
							ts.onclick = function() {
								ts.setAttribute('class', 'nav-link active');
								tc.setAttribute('class', 'nav-link');
								td.setAttribute('class', 'nav-link');
								
								for(i = 0;i < ad.length; ++i) {
									cd[i].style.display = "none";
									ad[i].style.display = "none";
									da[i].style.display = "";
								}
								
								for(i = 0;i < cr.length; ++i) {
									if (cr[i].cells[1].innerHTML == 0 || cr[i].cells[1].innerHTML == 2) {
										cr[i].style.display = "none";
									} else {
										if(cr[i].cells[1].innerHTML == 1) {
											cr[i].style.display = "";
											ec.push(cr[i]);
										}
									}
								}
								
								ch.style.display = "none";
								ah.style.display = "none";
								ha.style.display = "";
								mhr.style.display = "";
							}
							
							if (td === at[at.length - 1]) {
								for(i = 0;i < ad.length; ++i) {
									cd[i].style.display = "none";
									ad[i].style.display = "";
									da[i].style.display = "none";
								}
								
								for(i = 0;i < cr.length; ++i) {
									if (cr[i].cells[1].innerHTML == 1 || cr[i].cells[1].innerHTML == 2) {
										cr[i].style.display = "none";
									} else {
										cr[i].style.display = "";
										ec.push(cr[i]);
									}
								}
								
								if (ec.length < 1) {
									console.log(getParentsUntil(AD));
								}
								
								ch.style.display = "none";
								ah.style.display = "";
								ha.style.display = "none";
								mhr.style.display = "";
							}
						</script>
				<?php } } ?>
			</div>
		</main>

		<footer class="footer mt-auto py-3">
			<div class="container">
				<span class="text-muted">
					Copyright &copy; <script type="text/javascript">document.write(new Date().getFullYear());</script> aByte | Developed by <a href="https://exploiter.id/">ExploiterID</a> and <a href="https://armiko.moe/">Armiko.moe</a>
				</span>
			</div>
		</footer>
		<script src="https://unpkg.com/jquery@3.4.1/dist/jquery.min.js" crossorigin="anonymous"></script>
		<script src="https://unpkg.com/bootstrap@4.4.1/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
	</body>
</html>