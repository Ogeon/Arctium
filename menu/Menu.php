<?php
include_once("Arctium/Template.php");

class Menu {
	private $items;
	private $id;
	private $current;

	public function __construct($id, $menuTemplate=null, $itemTemplate=null) {
		$this->id = $id;
		$this->items = array();
		$this->current = "";

		if($menuTemplate == null || $menuTemplate == "")
			$menuTemplate = "Arctium/menu/templates/menu.html";

		if($itemTemplate == null || $itemTemplate == "")
			$itemTemplate = "Arctium/menu/templates/item.html";

		$this->menuTemplate = new Template($menuTemplate);
		$this->itemTemplate = new Template($itemTemplate);
	}

	public function addItem($content, $name, $url) {
		$this->items[] = array("content"=> $content, "name" => $name, "url" => $url);
	}

	public function insertItem($index, $content, $name, $url) {
		array_splice($this->items, $index, 0, array(array("content"=> $content, "name" => $name, "url" => $url)));
	}

	public function setCurrent($current) {
		$this->current = $current;
	}

	public function __toString() {
		$items = "";

		foreach ($this->items as $item) {
			$this->itemTemplate->hookContent("url", $item["url"]);
			$this->itemTemplate->hookContent("content", $item["content"]);

			if($this->current == $item["name"])
				$this->itemTemplate->hookContent("current", " current");
			else
				$this->itemTemplate->hookContent("current", "");

			$items .= $this->itemTemplate;
		}

		$this->menuTemplate->hookContent("id", $this->id);
		$this->menuTemplate->hookContent("items", $items);

		return (string)$this->menuTemplate;
	}
}

?>