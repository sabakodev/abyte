<?php
/**
 * Ukai - Professional Traffic Warden
 *
 * @author Fray117
 * @package DeRoute
 */

/** Uka Identifier */
header('X-Powered-By: Ukai');

/** Database Configuration */
require_once('../config.php');
$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

/** Discover Database */
if ($db->connect_error) die("Connection failed: " . $db->connect_error);

/** Random String Generator */
function randstr(int $len = 3, string $charset) {
	if (!isset($charset)) {
		$charset = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	}

	$length = strlen($charset);
	$string = '';

	for ($i = 0; $i < $len; $i++) {
		$string .= $charset[rand(0, $length - 1)];
	}

	return $string;
}

if (isset($_GET['url'])) {
	if (empty($_GET['url'])) {
		header('Location: .', true, 301);
	} else {
		$q = "SELECT * FROM `redirect` WHERE `redirect` = '" . addslashes($_GET['url']) . "'";
		$data = $db->query($q);

		if ($data->num_rows > 0) {
			while ($row = $data->fetch_assoc()) $result = $row;
			header('Location: ' . $result['url'], true, 301);

			$hit = $data['view'] + 1;
			$db->query("UPDATE `redirect` SET `view` = '$hit' WHERE `redirect`.`redirect` = '" . $data['id'] . "'");
		} else {
			echo '404 Not Found';
		}
	}

	exit;
} elseif (isset($_GET['list'])) {
	header('Content-Type: application/json');

	$data = $db->query("SELECT * FROM `redirect`");

	if ($data->num_rows > 0) {
		while ($row = $data->fetch_assoc()) $result[] = $row;
	}
	
	print json_encode($result);

	exit;
} elseif(isset($_GET['create'])) {
	if (empty($_GET['create'])) {
		header('Location: ' . $_SERVER['PHP_SELF'], true, 307);
	} else {
		$url = addslashes($_GET['create']);
		$key = randstr(5);
		$qcheck = "SELECT id FROM `redirect` WHERE `redirect`.`id` = '" . $key . "'";
		$data = $db->query($qcheck);

		if ($data->num_rows < 1) {
			$key = randstr(5);
		}

		$q = "INSERT INTO `redirect` (`id`, `url`, `view`) VALUES ('$key', '$url', '0') ";
		$db->query($q);
	}

	exit;
}

?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="title" content="Ukai">
	<meta name="description" content="Professional Traffic Warden">
	<title>Ukai</title>
	<style type="text/css">
		html {
			background: #111;
			color: #EEE;
			font-family: monospace;
			text-align: center;
			font-size: 2em;
		}

		a {
			color: #FFF;
			text-decoration: none;
		}

		h1, h3 {
			font-weight: 100;
		}

		table {
			width: 100%;
			text-align: center;
		}
	</style>
</head>
<body>
	<h1>Ukai</h1>
	<table>
		<thead>
			<tr>
				<td><h3>Redirect</h3></td>
				<td><h3>Target</h3></td>
				<td><h3>Visit</h3></td>
			</tr>
		</thead>
		<tbody id="data"></tbody>
	</table>
	<script type="text/javascript">
		var xhr = new XMLHttpRequest();

		xhr.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				var data = JSON.parse(this.responseText),
					table = document.getElementById('data');

				for (var i = 0; i < data.length; i++) {
					var row = document.createElement('tr'),
						d = document.createElement('td'),
						u = document.createElement('td'),
						v = document.createElement('td');

					d.innerHTML = data[i].redirect;
					u.innerHTML = data[i].url;
					v.innerHTML = data[i].view;

					row.appendChild(d);
					row.appendChild(u);
					row.appendChild(v);

					table.appendChild(row);
				}
			}
		}

		xhr.open('GET', '?list', true);
		xhr.send();
	</script>
</body>
</html>