<?
include_once("Arctium/Template.php");

class Textset {
	private $texts;

	public function __construct($textFile) {
		$file = fopen($textFile, "r");
		$textset = new SimpleXmlElement(fread($file, filesize($textFile)));

		$this->texts = array();

		foreach ($textset->group as $group) {
			$hooks = array();

			foreach ($group->hook as $hook) {
				$hooks[(string)$hook["name"]] = $this->innerXML($hook->asXML());
			}

			$this->texts[(string)$group["name"]] = $hooks;
		}
	}

	public function getHook($group, $hook) {
		if(isset($this->texts[$group][$hook]))
			return $this->texts[$group][$hook];
		return null;
	}

	public function setHook($group, $hook, $text) {
		$this->texts[$group][$hook] = $text;
	}

	public function fillTemplate(Template $template, $group) {
		foreach ($this->texts[$group] as $hook => $text) {
			$template->hookContent($hook, $text);
		}
	}

	private function innerXml($xml_text) {
		//strip the first element
		//check if the strip tag is empty also
		$xml_text = trim($xml_text);
		$s1 = strpos($xml_text,">");
		$s2 = trim(substr($xml_text,0,$s1)); //get the head with ">" and trim (note that string is indexed from 0)
		if ($s2[strlen($s2)-1]=="/") //tag is empty
			return "";
		$s3 = strrpos($xml_text,"<"); //get last closing "<"
		return trim(substr($xml_text,$s1+1,$s3-$s1-1));
	}

}




?>