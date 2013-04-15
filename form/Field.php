<?
include_once("Arctium/Template.php");

class Field {
	protected $template;
	protected $attributes;
	protected $name;
	protected $validationFunc;

	public function __construct($label, $name, $templatePath = "") {
		$this->template = new Template(
				$templatePath == ""?
					"Arctium/form/templates/field.html":
					$templatePath
				);
		$this->template->hookContent("label", $label);
		$this->name = $name;
		$this->template->hookContent("name", $name);
		$this->template->hookContent("id", $name);
		$this->template->hookContent("type", "text");
		$this->template->hookContent("status", "");

		$this->attributes = array();

		$this->validationFunc = function($value) {
			return true;
		};
	}

	public function setAttribute($name, $value) {
		$this->attributes[$name] = $value;
		if($name == "name")
			$this->name = $value;
	}

	public function setHookedContent($hook, $content) {
		$this->template->hookContent($hook, $content);
		if($hook == "name")
			$this->name = $content;
	}

	public function __toString() {
		$attr = "";
		foreach ($this->attributes as $name => $value) {
			$attr .= "$name=\"$value\" ";
		}

		$this->template->hookContent("attributes", $attr);
		$this->template->hookContent("name", $this->name);
		//$this->template->hookContent("id", $this->name);
		return (string)$this->template;
	}

	public function fetchValues() {
		if(isset($_POST[$this->name])) {
			$value = $_POST[$this->name];
			$this->attributes["value"] = htmlentities($value, ENT_COMPAT, 'UTF-8');
		}
	}

	public function valueSent() {
		return isset($_POST[$this->name]);
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function setValue($value) {
		$this->setAttribute("value", $value);
	}

	public function getValue() {
		if(isset($this->attributes["value"])) {
			return $this->attributes["value"];
		} else {
			return "";
		}
	}

	public function validate() {
		if(!isset($this->attributes["value"]))
			$this->attributes["value"] = "";
		
		$func = $this->validationFunc;
		if($func($this->attributes["value"])) {
			$this->template->hookContent("status", "ok");
			return true;
		} else {
			$this->template->hookContent("status", "error");
			return false;
		}
	}

	public function setValidationFunction($function) {
		$this->validationFunc = $function;
	}
}
?>