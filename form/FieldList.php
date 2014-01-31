<?php 
include_once "Arctium/form/Fieldset.php";
include_once "Arctium/Template.php";

class FieldList extends Fieldset {
	private $items;
	private $itemTemplate;
	private $name;

	public function __construct($legend, $name, Field $templateField, $itemTemplatePath="", $listTemplatePath="") {
		parent::__construct($legend,
				$itemTemplatePath == ""?
					"Arctium/form/templates/fieldList.html":
					$itemTemplatePath
				);
		$this->itemTemplate = new Template(
				$listTemplatePath == ""?
					"Arctium/form/templates/fieldListItem.html":
					$listTemplatePath
				);

		$this->name = $name;
		$this->templateField = $templateField;
		$this->template->hookContent("name", $name);
		$this->itemTemplate->hookContent("name", $name);
	}

	public function setAddButtonText($text) {
		$this->template->hookContent("addRowText", $text);
	}

	public function setDeleteButtonText($text) {
		$this->itemTemplate->hookContent("deleteRowText", $text);
	}

	public function hookContent($hook, $value) {
		$this->template->hookContent($hook, $value);
	}

	public function fetchValues() {
		$n = 0;
		$deleted = false;
		while(true) {
			$field = clone $this->templateField;
			$field->setName($this->name."field".$n);
			
			if(!$field->valueSent())
				break;

			if($n == 0)
				$this->fields = array();

			if(!isset($_POST[$this->name.$n."-delete"])) {
				$field->fetchValues();
				$this->addField($field);
			} else {
				$deleted = true;
			}

			$n++;
		}

		if($deleted) {
			foreach ($this->fields as $row => $field) {
				$field->setName($this->name."field".$row);
			}
		}

		if(isset($_POST[$this->name."-add"])) {
			$field = clone $this->templateField;
			$field->setName($this->name."field".count($this->fields));
			$this->addField($field);
		}
	}

	public function addRow($value = NULL) {
		$field = clone $this->templateField;
		$field->setName($this->name."field".count($this->fields));

		if($value != NULL)
			$field->setValue($value);
		$this->addField($field);
	}

	public function getData() {
		$data = array();

		foreach ($this->fields as $row => $field) {
			$data[$row] = $field->getValue();
		}

		return array($this->name => $data);
	}

	public function __toString() {
		$html = $this->getOpening()."\n";
		
		$fieldHtml = "";

		foreach ($this->fields as $row => $field) {
			$this->itemTemplate->hookContent("fields", $field);
			$this->itemTemplate->hookContent("row", $row);
			$fieldHtml .= $this->itemTemplate;
		}

		$this->template->hookContent("fields", $fieldHtml);

		$html .= $this->template."\n";

		$html .= $this->getClosing()."\n";

		return $html;
	}
}
?>