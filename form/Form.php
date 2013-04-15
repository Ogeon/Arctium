<?
include_once("Arctium/HTMLElement.php");

class Form {
	private $fieldsets;
	private $submitButton;
	private $action;
	private $method;
	private $template;

	public function __construct($action, $method ="POST", $templatePath = "") {
		$this->fieldsets = array();
		$this->action = $action;
		$this->method = $method;

		$this->template = new Template(
			$templatePath == ""?
				"Arctium/form/templates/form.html":
				$templatePath
			);

		$this->submitButton = new HTMLElement("input");
		$this->submitButton->setAttribute("name", "submit");
		$this->submitButton->setAttribute("type", "submit");
		$this->submitButton->setAttribute("value", "Submit");
	}

	public function setSubmitText($text) {
		$this->submitButton->setAttribute("value", $text);
	}

	public function addFieldset(Fieldset $fieldset) {
		$this->fieldsets[] = $fieldset;
	}

	public function __toString() {
		$html = "";

		foreach ($this->fieldsets as $set) {
			$html .= $set;
		}

		$this->template->hookContent("fieldsets", $html);
		$this->template->hookContent("method", $this->method);
		$this->template->hookContent("action", $this->action);
		$this->template->hookContent("submit", $this->submitButton);

		return (string)$this->template;
	}

	public function fetchValues() {
		foreach ($this->fieldsets as $fieldset) {
			$fieldset->fetchValues();
		}
	}

	public function getData() {
		$data = array();

		foreach ($this->fieldsets as $fieldset) {
			$data = array_merge($data, $fieldset->getData());
		}

		return $data;
	}

	public function validate() {
		$errors = array();

		foreach ($this->fieldsets as $fieldset) {
			$result = $fieldset->validate();
			if($result !== true)
				$errors = array_merge($errors, $result);
		}


		return count($errors) > 0? $errors: true;
	}

	public function isSubmitted() {
		return isset($_POST["submit"]);
	}
}

?>