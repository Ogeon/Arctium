<?php
include_once "Arctium/Template.php";

abstract class Archive {
	public abstract function get($path = "");
	public abstract function fillTemplate(Template $template, $path = "");
}