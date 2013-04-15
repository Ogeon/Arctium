<?
include_once("Arctium/Template.php");
include_once("Arctium/form/Field.php");

class MultiField extends Field {
	protected $template;
	protected $attributes;
	protected $name;
	protected $values;
	protected $status;

	public function __construct($labels, $name, $fields, $templatePath) {
		$this->template = new Template($templatePath);
		foreach ($labels as $n => $label) {
			$this->template->hookContent("label".($n+1), $label);
		}
		$this->name = $name;
		$this->template->hookContent("name", $name);
		$this->template->hookContent("id", $name);
		$this->template->hookContent("type", "text");

		$this->attributes = array();
		$this->values = array();
		$this->status = array();
		for ($n=1; $n <= $fields; $n++) {
			$this->values["value$n"] = "";
			$this->validationFunc[$n] = function($value) {
				return true;
			};
		}
	}

	public function __toString() {
		$attr = "";
		foreach ($this->attributes as $name => $value) {
			$attr .= "$name=\"$value\" ";
		}

		foreach ($this->values as $hook => $value) {
			$this->template->hookContent($hook, $value);
		}

		foreach ($this->status as $hook => $value) {
			$this->template->hookContent($hook, $value);
		}

		$this->template->hookContent("attributes", $attr);
		$this->template->hookContent("name", $this->name);
		$this->template->hookContent("id", $this->name);
		return (string)$this->template;
	}

	public function fetchValues() {
		for($n = 1; $n <= count($this->values); $n++) {
			if(isset($_POST["$this->name-$n"])) {
				$value = $_POST["$this->name-$n"];
				$this->values["value$n"] = htmlentities($value, ENT_COMPAT, 'UTF-8');
			}
		}
	}

	public function valueSent() {
		for($n = 1; $n <= count($this->values); $n++) {
			if(isset($_POST["$this->name-$n"]))
				return true;
		}

		return false;
	}

	public function setValue($value) {
		foreach ($value as $key => $value) {
			$this->values["value$key"] = $value;
		}
	}

	public function getValue() {
		$result = array();

		for($n = 1; $n <= count($this->values); $n++) {
			$result[$n] = $this->values["value$n"];
		}

		return $result;
	}

	public function validate() {
		$correct = true;
		for($n = 1; $n <= count($this->values); $n++) {
			if($this->validationFunc[$n]($this->values["value$n"])) {
				$this->status["status-$n"] = "ok";
			} else {
				$this->status["status-$n"] = "error";
				$correct = false;
			}
		}
		return $correct;
	}

	public function setValidationFunction($function) {
		foreach ($function as $key => $function) {
			$this->validationFunc[$key] = $function;
		}
	}
}
?>