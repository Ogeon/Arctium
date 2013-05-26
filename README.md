Arctium
=======

A simple PHP model-view framework, based on templates, which allows
you to freely write your website without any restrictions. The main
purpouse of this framework is to separate code, design and content
to avoid cluttering.

#Quick example
_Note: The Arctium files assumes that they are placed in a directory called
Arctium in the same directory as the executed PHP file. This will be
made configurable in a later version._

```
Example file structure:

/[...]/Arctium/[arctium files]
/[...]/templates/main.html
/[...]/graphics/
/[...]/pages/[...]/gallery/
/[...]/index.php
/[...]/texts.xml
/[...]/style.css
/[...]/.htaccess
```

```HTML
templates/main.html

<!DOCTYPE html>

<html>
	<head>
		<title>[[title]]</title>
		<!-- CSS links and stuff here -->
	</head>
	<body>
		<p>[[useful text]]</p>
		{{Gallery;pictures,pages/[[page]]/gallery}}
	</body>
</html>
```

```XML
texts.xml

<?xml version="1.0" encoding="UTF-8"?>
<texts>
	<en>
		<about>
			This example shows how Arctium can be used.
		</about>
	</en>
	<sv>
		<about>
			Det här exemplet visar hur Arctium kan användas.
		</about>
	</sv>
</texts>
```

```
.htaccess

RewriteEngine On

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

RewriteRule ^show/(.*)$ /index.php?args=show/$1 [L]
RewriteRule ^lang/(.*)$ /index.php?args=lang/$1 [L]
```

```PHP
index.php

<?

include_once "Arctium/Template.php";
include_once "Arctium/XMLArchive.php";
include_once "Arctium/URL.php";

$texts = new XMLArchive("texts.xml");
$url = new URL($_GET["args"]);

$lagn = $url->getParameter("lang");
if($lang === null) {
	$lagn = "en";
}

$page = new Template("templates/main.html");
$page->hookContent("title", "Quick example");
$page->hookContent("page", $url->getParameter("show"));
$page->hookContent("useful text", $texts->get("$lang/about"));

echo $page;

?>
```

#How it works
Arctium is based on templates with place holders called "hooks".
Any content that can be converted to strings can be hooked into a
template and that is the key to the flexibility of this framework.

There are two types of hooks:
* Text hooks - Will be replaced with plain text.
* Object hooks - Will be replaced with an instance of a PHP object.

##Text hooks
Syntax: `[[hook-name]]`

These are the most basic and most useful hooks. They are replaced by
calling `Template->hookContent($hook, $content)` with the name of
the hook and the desired content as parameters.

##Object hooks
Syntax: `{{ClassName;param1[,param2,...]}}`

These are a little stranger than the text hooks. These are used to add
semi dynamic content to the document. Object hooks will be replaced
automatically and this happens after the text hooks are replaces. This
makes it possible to add text hooks into the object hooks to create
static programmable elements with dynamic content.

Object hooks will, for now only be replaced with content from the
Arctium directory, but there are plans to extend this to any directory.

##Order of replacement
The fact that each hook can be replaced with any type of content that
can be converted to a text string makes it possible to hook in an other
Template object (Warning! I will not be responsible for the effects of
cyclic Template hooking). Then you may wonder in which order the hooks
are replaced.

The order is as following:
 1. Convert content to text strings and replace text hooks.
 2. Replace object hooks with PHP objects.

When the subtemplates are converted to strings their replacement
procedure will be triggered and their content will be replaced first.
