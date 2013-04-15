<?
include_once("Arctium/Template.php");

class Gallery {
	private $id;
	private $template;
	private $picture;
	private $directory;

	public function __construct($id, $imageDirectory, $template="", $pictureTemplate="") {
		$this->id = $id;
		if($template == "")
			$this->template = new Template("Arctium/gallery/templates/gallery.html");
		else
			$this->template = new Template($template);

		if($pictureTemplate == "")
			$this->picture = new Template("Arctium/gallery/templates/galleryThumb.html");
		else
			$this->picture = new Template($pictureTemplate);

		$this->directory = $imageDirectory;
	}

	public function __tostring() {
		$pictures = "";
		if ($handle = opendir($this->directory)) {
			while (false !== ($entry = readdir($handle))) {
				if ($entry != "." && $entry != ".." && !is_dir("$this->directory/$entry")) {
					$this->picture->hookContent("url", "$this->directory/$entry");
					$pictures .= $this->picture;
				}
			}
			closedir($handle);
		}

		$this->template->hookContent("id", $this->id);
		$this->template->hookContent("pictures", $pictures);
		return (string) $this->template;
	}
}

?>