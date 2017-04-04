<?php
session_start();
$link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
require( "functions.php" );
$droot = $_SERVER['DOCUMENT_ROOT']."/";
$file = $_GET['file'];
$dir = $_GET['dir'];

if (isset($_GET['dir'])) {
	$_SESSION['dir'] = $dir;
	$_SESSION['file'] = $file;
}
$pattern = '/maindir/i';
$pw = "1234";
if (isset($_POST['submit']) || isset($_SESSION['token']))	{
	if (isset($_POST['password']) && $_POST['password'] == $pw) {
		$token = $_POST['password'];
		$_SESSION['token'] = $token;
	}
	else if (isset($_SESSION['token']) && $_SESSION['token'] == $pw) {
		$token = $_SESSION['token'];
	}
	else {
		$token = null;
	}
	if ($token == $pw) {
		$file = $_SESSION['file'];
		$dir = $_SESSION['dir'];
		
		if ($dir != $droot) {
			$dir = $droot.$dir;
		}
?>
<!doctype HTML>
<html>
	<head>
		<!-- site Character set -->
		<meta charset="utf-8">
		<!-- site title -->
		<title> Highlight - <?php echo $file; ?> </title>
		<link rel="icon"  type="image/png"  href="img/icon.png">
		<!-- style link -->
		<style>
		html, body {
			margin:0; padding:0;
			overflow-x:hidden;
		}
		.goback {
			width:100%;
			height:50px;
			background-color:#E6E6E6;
			display:flex;
			align-items:center;
			position:fixed;
			
		}
		.goback a {
			padding:5px 10px;
			border-radius:10px;
			background-color:#0B9D00;
			margin-left:10px;
			color:#E6E6E6;
			text-decoration:none;
			font-family:sans-serif;
		}
		.file {
			padding-top:50px;
			width:100vw;
			overflow:hidden;
			word-wrap:break-all;
			padding-left:20px;
			padding-right:20px;
		}
		</style>
	</head>
	
	<body>
		<div class="goback">
		<a href="<?php echo $link; ?>">Start</a>
		<?php
		if ($_POST['submit']) {
		?>
		<a href="" onclick="window.history.go(-3);">Tilbage</a>
		<?php
		}
		else {
		?>
		<a href="" onclick="window.history.back();">Tilbage</a>
		<?php
		}
		?>
		</div>
		<div class="file" id="file">
		<?php
		if (preg_match("/maindir/i", $dir)) {
			echo "You're not allowed to check the main directory, please stop sniffing around";
		}
		else if ($file == "index.php" && $dir == $droot) {
			echo "this file is locked, don't sniff around";
		}
		else {
		highlight_file($dir."/".$file);
		}
		
		?>
		</div>
	</body>
</html>
<?php	
	}
	else {
?>
<!doctype HTML>
<html>
	<head>
		<!-- site Character set -->
		<meta charset="utf-8">
		<!-- site title -->
		<title> Highlight - Login </title>
		<link rel="icon"  type="image/png"  href="img/icon.png">
		<!-- style link -->
		<link rel="stylesheet" type="text/css" href="css/hllogin.css">
	</head>
	
	<body>
		<h2>
			Koden er 1234
		</h2>
		<h3>
			Koden var ikke rigtigt!
		</h3>
		<form action="highlight.php" method="POST">
			<input type="password" min="4" max="4" name="password" placeholder="pin her">
			<input type="submit" name="submit" value=">">
		</form>
	</body>
</html>
<?php	
	}
	}
	else {

?>
<!doctype HTML>
<html>
	<head>
		<!-- site Character set -->
		<meta charset="utf-8">
		<!-- site title -->
		<title> Highlight - Login </title>
		<link rel="icon"  type="image/png"  href="img/icon.png">
		<!-- style link -->
		<link rel="stylesheet" type="text/css" href="css/hllogin.css">
	</head>
	
	<body>
		<h2>
			Koden er 1234
		</h2>
		<form action="highlight.php" method="POST">
			<input type="password" min="4" max="4" name="password" placeholder="pin her">
			<input type="submit" name="submit" value=">">
		</form>
	</body>
</html>
<?php
}
?>