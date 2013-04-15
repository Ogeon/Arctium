<?

class HTMLElement {
	private $tagname;
	private $attributes;
	private $selfClosing;
	private $content;

	public function __construct($tagname, $selfClosing = true, $content = "") {
		$this->tagname = $tagname;
		$this->attributes = array();
		$this->selfClosing = $selfClosing;
		$this->content = $content;
	}

	public function setAttribute($name, $value) {
		$this->attributes[$name] = $value;
	}

	public function setSelfClosing($selfClosing) {
		$this->selfClosing = $selfClosing;
	}

	protected function getSelfClosing() {
		$html = "<$this->tagname";

		foreach ($this->attributes as $key => $value) {
			$html .= " $key=\"$value\"";
		}

		$html .= " />";

		return $html;
	}

	protected function getOpening() {
		$html = "<$this->tagname";

		foreach ($this->attributes as $key => $value) {
			$html .= " $key=\"$value\"";
		}

		$html .= ">";

		return $html;
	}

	protected function getClosing() {
		return "</$this->tagname>";
	}

	public function __toString() {
		if($this->selfClosing)
			return $this->getSelfClosing();
		else
			return $this->getOpening().$this->content.$this->getClosing();
	}
}
?>