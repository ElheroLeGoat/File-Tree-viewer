<?php
clearstatcache();
require("maindir/functions.php");
$extensions_list = array(
							"folder",
							"php",
							"html",
							"htm",
							"css",
							"js",
							"sql",
							"jpg",
							"jpeg",
							"png",
							"gif",
							"svg",
							"zip",
							"rar",
							"psd",
							"txt",
							"log",
							"xml",
							"json",
							"xlsx"
						);
$dir = false;
if (isset($_GET['dir'])) {
	$dir = $_GET['dir'];
} else {
	$dir = getcwd();
}

$blacklist = pushToBlacklist($dir);
$files     = scandir($dir);
$last_dir  = substr($dir, strrpos($dir, '/') + 1);
$last_dir  = preg_replace("/_/", ' ', $last_dir);
if ($last_dir == "public_html") {
	$last_dir = "Start";
}
if (preg_match("/public_html/i", $_GET[ 'dir' ])) {
	$last_dir = substr( $dir, strrpos( $dir, '/' ) );
}
$last_dir = ucfirst($last_dir);
$go_back = str_replace($last_dir, "", $dir);
$linkdir = str_replace($_SERVER["DOCUMENT_ROOT"], "", $dir);
?>
<!doctype HTML>
<html>
  <head>
    <meta charset="utf-8">
    <title>
      RKM - <?php echo str_replace("/", "", $last_dir); ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="maindir/css/main.css">
    <link rel="icon"  type="image/png"  href="maindir/img/icon.png">
  </head>
  <body>
    <header class="pageHeader">
      <div class="pageWrapper">
        <a href="/">
          Start
        </a>
<?php if (isset($_GET['dir']) && $_GET['dir'] != $_SERVER["DOCUMENT_ROOT"]) { ?>
        <a href="index.php?dir=<?php echo $go_back; ?>">
          tilbage
        </a>
<?php } ?>
      </div>
    </header>
    <section class="mainSection">
			<div class="info">
				<h3>
				  Lille guide
				</h3>
				<div class="infoimg">
				</div>
				<p>
				  Tryk på filnavnet for at gå direkte til filen. tryk på den lille gule markør for at se indholdet af filen (den reelle kilde kode).
				</p>
			  </div>
<?php if (preg_match("/maindir/i", $_GET['dir'])) { ?>
			<div class="info">
				<h3>
				  Directory lock
				</h3>
				<div class="infoimg">
				</div>
				<p>
				  This directory is locked, please don't try to enter it.
				</p>
			  </div>
<?php }
	else {		  
		if (in_array("index.php", $files) && $dir != getcwd() || in_array("index.html", $files) && $dir != getcwd()) {
			$searched = arrSearch( $files, array("index.html","index.php"));
			echo '
					<div class="websiteview">
						<h2>
							<a href="' . $linkdir . '/' . $searched[ 0 ] . '"> Gå til Projektet </a>
						</h2>
					</div>
				';
		}
?>
      <div class="pageWrapper">
        <div class="row">
          <div class="cell">
            FilType
          </div>
          <div class="cell">
            Filnavn
          </div>
          <div class="cell">
            sidst redigeret
          </div>
        </div>
<?php
	$multiArr = listDirectory($dir, $blacklist);
	if ($_GET['dir'] == $_SERVER['DOCUMENT_ROOT']."/work") {
		$multiArr[ 1 ]             = array_reverse( $multiArr[ 1 ] );
		$multiArr[ 0 ][ 'folder' ] = array_reverse( $multiArr[ 0 ][ 'folder' ] );
	}
	foreach ( $extensions_list as $val ) {
		$x = 0;
		if ( array_key_exists( $val, $multiArr[ 0 ] ) ) {
			foreach ( $multiArr[ 0 ][ $val ] as $file ) {
				$withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file);
				$withoutExt = preg_replace("/_/", ' ', $withoutExt);
				if ( is_dir( $dir . '/' . $file ) ) {
					echo '
						<div class="wrap">
							<a href="index.php?dir=' . $dir . '/' . $file . '">
						<div class="row">
					';
				} else if ( $_GET[ 'dir' ] == $_SERVER['DOCUMENT_ROOT']."/work" ) {
					echo '
						<div class="wrap">
							<a href="index.php?dir=' . $dir . '/' . $file . '">
						<div class="row">
					';
				} else {
					echo '
						<div class="wrap">
							<a href="' . $linkdir . '/' . $file . '">
						<div class="row">
					';
				}
				echo '
					<div class="cell">
						<img src="maindir/img/' . $val . '.png" alt="' . $val . '">
					</div>
				';
				if ($_GET['dir'] == $_SERVER['DOCUMENT_ROOT']."/work") {
					echo '
						<div class="cell">
							' . $multiArr[ 1 ][ $x ] . '
						</div>
					';
				} else {
					echo '
						<div class="cell">
							' . $withoutExt . '
						</div>
					';
				}
				echo '
					<div class="cell">
						' . date( "d-m-Y H:i", filemtime( $dir . '/' . $file ) ) . '
					</div>
					</div>
					</a>
				';
				if ($val == "php" || $val == "html") {
					echo '<a class="highlight" href="maindir/highlight.php?dir='.$linkdir.'&file=' . $file . '" title="highlight Fil"></a>';
				}

				echo "</div>";
				$x++;


				}
		}
	}
}
?>
      </div>
    </section>
    <footer class="pageFooter">
      <small>
        System & design: RKM
      </small>
    </footer>
  </body>
</html>