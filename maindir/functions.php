<?php

function pushToBlacklist($dir)
	{
	$blacklist = array(
		".",
		"..",
		"robots.txt",
		".db",
		"config.php",
		"Config.php",
		"db.php"
	);
	if ($dir == getcwd())
		{
		array_push($blacklist, "index.php", "maindir");
		}

	return $blacklist;
	}

function arrSearch($array, $search)
	{
	$result = array();
	foreach($array as $key => $value)
		{
		foreach($search as $k => $v)
			{
			if (!isset($value[$k]) || $value[$k] != $v)
				{
				continue2;
				}
			}

		$result[] = $key;
		}

	return $result;
	}

/*  Ignore above */

function is_blank($value)
	{
	return empty($value) && !is_numeric($value);
	}

function listDirectory($directory, $blacklist)
	{
	$files = scandir($directory);
	$division = array();
	$sortarr = array();
	$x = 0;
	foreach($files as $value)
		{
		if (in_array($value, $blacklist))
			{
			}
		  else
			{
			$a = substr($value, -2, 2);
			if (preg_match('/[A-Za-z]/', $a))
				{
				$file = substr($value, -1, 1);
				}
			  else
				{
				$file = substr($value, -2, 2);
				}

			$sortarr[$file . $x] = $value;
			$x++;
			}
		}

	sort($sortarr, SORT_NATURAL);
	$sortarr = array_values($sortarr);
	$workdir = $_SERVER['DOCUMENT_ROOT'] . "/work";
	if (isset($_GET['dir']) && $_GET['dir'] == $workdir)
		{
		$files;
		$renamer = array();
		foreach($sortarr as $value)
			{
			$a = substr($value, -2, 2);
			if (preg_match('/[A-Za-z]/', $a))
				{
				$file = substr($value, -1, 1);
				}
			  else
				{
				$file = substr($value, -2, 2);
				}

			if ($file == "_")
				{
				$renamer[] = "Webprøver";
				}
			  else
				{
				$renamer[] = "Uge " . $file;
				}

			$number = substr($value, -4, 0);
			}
		}

	foreach($sortarr as $file)
		{
		$extension = new SplFileInfo($file);
		$ext = $extension->getExtension();
		if (in_array($file, $blacklist))
			{
			}
		  else
			{
			if ($ext == "")
				{
				$ext = "folder";
				}

			$division[$ext][] = $file;
			}
		}

	$lr = array(
		$division,
		$renamer
	);
	return $lr;
	}

?>