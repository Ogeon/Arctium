<?php

class URL {
	private $parameters;

	public function __construct($urlString = "") {
		$this->parameters = array();

		if($urlString != "") {
			$parts = explode("/", $urlString);

			$params = floor(count($parts)/2);
			for($i = 0; $i < $params*2; $i += 2) {
				$this->parameters[$parts[$i]] = $parts[$i+1];
			}
		}
	}

	public function getParameter($key) {
		if(isset($this->parameters[$key]))
			return $this->parameters[$key];

		return null;
	}

	public function setParameter($key, $value) {
		$this->parameters[$key] = $value;
	}

	public function __toString() {
		$url = "";

		foreach ($this->parameters as $key => $value) {
			$url .= "$key/$value/";
		}

		return $url;
	}
}

?>