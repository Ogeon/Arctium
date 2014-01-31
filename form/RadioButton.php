<?php
include_once("Arctium/Template.php");
include_once("Arctium/form/Field.php");

class RadioButton extends Field {

	public function __construct($label, $name, $value, $templatePath = "") {
		parent::__construct($label, $name, $templatePath);
		$this->setHookedContent("type", "radio");
		$this->setValue($value);
		$this->setHookedContent("id", $name.$value);
	}

	public function fetchValues() {
		if(isset($_POST[$this->name])) {
			unset($this->attributes["checked"]);
			if($_POST[$this->name] == $this->attributes["value"])
				$this->attributes["checked"] = "true";
		}
	}

	public function validate() {
		return true;
	}
}
?>