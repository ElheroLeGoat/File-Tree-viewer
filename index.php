<?php
	// require Class
	require("maindir/treeViewer.php");
	// Call Object
	$dir = new fileTreeView();
	// Call function to get the current files in directory
	$dirList = $dir->listDir();
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
        <a href="/"> Start </a>
    </header>
    <section class="mainSection">

<?php if ($dir->isLocked()) { ?>
	
	<div class="info">
		<h3> Directory lock </h3>
		<div class="infoimg"> </div>
		<p> This directory is either locked or empty </p>
	</div>

<?php } else { if ($dir->isProject()) { ?>
	<div class="websiteview">
		<h2><a href="<?php echo $dir->getProjectUrl(); ?>"> Check out this project </a></h2>
	</div>	

<?php } ?>

      <div class="pageWrapper">
        <div class="row">
			<div class="cell"> File Type </div>
			<div class="cell"> File name </div>
			<div class="cell"> Last edited </div>
        </div>
<?php
	foreach ($dirList as $ext => $fileArr) {
		
		foreach ($fileArr as $file) {
?>
		<div class="wrap">
			<a href=" <?php echo $dir->generateLink($file, $ext); ?>">
			<div class="row">
				<div class="cell"> <img src="maindir/img/<?php echo $ext; ?>.png" alt="<?php echo $ext; ?>"> </div>
				<div class="cell"> <?php echo $dir->removeExt($file); ?> </div>
				<div class="cell"> <?php echo $dir->getLastEditDate($file); ?> </div>
			</div>
			</a>
		</div>
<?php } } } ?>
      </div>
    </section>
    <footer class="pageFooter">
      <small>
        System & design: RKM
      </small>
    </footer>
  </body>
</html>
