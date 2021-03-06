<?php
include_once "Arctium/Template.php";
include_once "Arctium/Archive.php";

class XMLArchive extends Archive {
	private $XML;

	public function __construct($xmlFile) {
		$file = fopen($xmlFile, "r");
		$this->XML = new SimpleXmlElement(fread($file, filesize($xmlFile)));
	}

	public function get($path = "") {
		$position = $this->navigate($path);
		return $position != null? $this->innerXML($position->asXML()): null;
	}

	public function fillTemplate(Template $template, $path = "") {
		$position = $this->navigate($path);
		foreach ($position->children() as $key => $value) {
			$template->hookContent($key, $this->innerXml($value->asXML()));
		}
	}

	private function innerXml($xml_text) {
		$xml_text = trim($xml_text);
		$s1 = strpos($xml_text,">");
		$s2 = trim(substr($xml_text,0,$s1));
		if (strlen($s2) < 1 || $s2[strlen($s2)-1]=="/")
			return "";
		$s3 = strrpos($xml_text,"<");
		return trim(substr($xml_text,$s1+1,$s3-$s1-1));
	}

	private function navigate($path) {
		$nodes = explode("/", $path);

		$position = $this->XML;
		foreach ($nodes as $node) {
			if(trim($node) != "")
				$position = $position->{trim($node)};
			if($position == null)
				return null;
		}

		return $position;
	}
}