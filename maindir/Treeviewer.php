<?php

/*
 @class - FileTreeView
 @desc	- lists directory based on $_GET method.
*/

class fileTreeView {
	/* Variables */
	
	// Holds the current directory.
	private $current_dir;
	// Holds all current files
	private $current_files;
	// Holds array of all the directories from the past.
	private $breadCrumbs;
	// holds array of all black listed files.
	private $black_list;
	// Holds config array.
	private $config;
	
	/* Public functions */
	
	// Constructor is called on new object.
	public function __construct(array $black_list = array(), array $config = array("debugging" => 0, "getName" => "dir")) {
		$this->black_list = $black_list;
		$this->config = $config;
		$this->current_files = array();
		
		$this->getCurrentDir();
		$this->generateBreadCrumbs();
		
		$this->addBlacklisted(".");
		$this->addBlacklisted("..");
		$this->addBlacklisted("maindir");
		
		if($this->current_dir == getcwd() && $this->config["debugging"] == 0) {
			$this->addBlacklisted("index.php");		
		}
	}
	
	// Makes it possible to add Black listed files.
	public function AddBlacklisted($fileName) {
		array_push($this->black_list, $fileName);
		

	}
	
	// list directory.
	public function listDir() {
		$files = scandir($this->current_dir);
		foreach($files as $file) {
			if (!in_array($file, $this->black_list)) {
				$this->current_files[$this->getExt($file)][] = $file; 
			}
		}
		return $this->current_files;
	}
	
	// Generate Link.
	public function generateLink($file, $ext) {
		if ($ext == "folder") {
				$link = "index.php?".$this->config["getName"]."=".$this->current_dir."/".$file;
		}
		else {
			if (substr($_SERVER["DOCUMENT_ROOT"], -1, 1) == "/") {
				$dir = str_replace(substr($_SERVER["DOCUMENT_ROOT"], 0, -1), "", str_replace("\\", "/", $this->current_dir));
			}
			else {
				$dir = str_replace($_SERVER["DOCUMENT_ROOT"], "", str_replace("\\", "/", $this->current_dir));
			}
			$link = $dir."/".$file;
		}
		return $link;
	}
	
	public function isLocked() {
		if ($this->config["debugging"] == 0) {
			foreach ($this->black_list as $blacked) {
				if (in_array($blacked, $this->breadCrumbs)) {
					if ($blacked == "." || $blacked == "..") {}
					else {
						return true;
					}
				}
			}
		}
		return false;
	}
	public function isEmpty() {
		if (empty($this->current_files)) {
			return true;
		}
		return false;
	}
	public function isProject() {
		if (in_array("index.php", $this->current_files["php"])) {
			return true;
		}
		else {
			
		}
		return false;
	}
	
	public function getProjectUrl() {
		return $this->generateLink("index.php", "php");
	}
	public function highlightFile($file, $ext) {
		if ($ext == "php" || $ext == "html") {
			return "true";
		}
		else return "false";
	}
	public function removeExt($file) {
		return str_replace("_", " ", pathinfo($file, PATHINFO_FILENAME)); 
	}
	public function getLastEditDate($file) {
		return date("d-m-Y H:i", filemtime($this->current_dir . '/' . $file));
		
	}
	
	
	/* Private Functions */
	private function getExt($file) {
			$extension = new SplFileInfo($file);
			$ext = $extension->getExtension();
			if ($ext == "") {
				$ext = "folder";
			}
			return $ext;
	}
	
	
	private function getCurrentDir() {
	if (isset($_GET[$this->config["getName"]])) {
			$dir = $_GET[$this->config["getName"]];
		} else {
			$dir = getcwd();
		}
		$this->current_dir = $dir;
	}
	private function GenerateBreadcrumbs() {
		$dir = str_replace("\\", "/", $this->current_dir);
		$this->breadCrumbs = explode("/", $dir);
	}
	
	
	
	
	
	
}

