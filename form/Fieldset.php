<?
include_once("Arctium/HTMLElement.php");
include_once("Arctium/Template.php");
include_once("Arctium/form/Field.php");

class Fieldset {
	protected $fields;
	protected $template;

	public function __construct($legend, $templatePath = "") {
		$this->fields = array();

		$this->template = new Template(
				$templatePath == ""?
					"Arctium/form/templates/fieldset.html":
					$templatePath
				);

		$this->template->hookContent("legend", $legend);
	}

	public function addField(Field $field) {
		$this->fields[] = $field;
	}

	public function __toString() {
		$fieldHtml = "";

		foreach ($this->fields as $field) {
			$fieldHtml .= $field;
		}
		$this->template->hookContent("fields", $fieldHtml);

		return (string)$this->template;
	}

	public function fetchValues() {
		foreach ($this->fields as $field) {
			$field->fetchValues();
		}
	}

	public function getData() {
		$data = array();

		foreach ($this->fields as $field) {
			$data[$field->getName()] = $field->getValue();
		}

		return $data;
	}

	public function validate() {
		$errors = array();
		foreach ($this->fields as $field) {
			if(!$field->validate())
				$errors[$field->getName()] = false;
		}

		return count($errors) > 0? $errors: true;
	}
}
?>