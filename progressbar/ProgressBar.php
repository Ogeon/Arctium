<?php
include_once "Arctium/Template.php";

class ProgressBar {
	private $min;
	private $max;
	private $progress;
	private $textFormat;
	private $template;

	public function __construct($progress, $max = 100, $textFormat = "[[percentage]]%", $template = null) {
		$this->min = 0;
		$this->max = $max;
		$this->progress = $progress;
		$this->textFormat = $textFormat;
		if($template == null)
			$this->template = new Template("Arctium/progressbar/templates/progressbar.html");
		else
			$this->template = new Template($template);
	}

	public function setMin($min) {
		$this->min = $min;
	}

	public function setMax($max) {
		$this->max = $max;
	}

	public function setProgress($progress) {
		$this->progress = $progress;
	}

	public function setTextFormat($textFormat) {
		$this->textFormat = $textFormat;
	}

	public function __toString() {
		$this->template->hookContent("text", $this->textFormat);
		$this->template->hookContent("min", $this->min);
		$this->template->hookContent("max", $this->max);
		$this->template->hookContent("progress", $this->progress);
		$this->template->hookContent("percentage", $this->min == $this->max? 100: 100*($this->progress-$this->min)/($this->max-$this->min));
		return (string)$this->template;
	}
}

?>