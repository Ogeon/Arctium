<?

include_once("Arctium/Textset.php");

class Cluster {
	
	private $content;

	public function __construct(Textset $content) {
		$this->content = $content;
	}

	private function create_instance($class, $params) {
		$reflection_class = new ReflectionClass($class);
		return $reflection_class->newInstanceArgs($params);
	}

	private function findAndInclude($class, $base="Arctium") {
		$files = array();
		if ($handle = opendir($base)) {
			while (false !== ($entry = readdir($handle))) {
				if ($entry != "." && $entry != "..") {
					if($entry == "$class.php") {
						include_once("$base/$class.php");
						return true;
					} else {
						if(is_dir("$base/$entry"))
							if($this->findAndInclude($class, "$base/$entry"))
								return true;
					}
				}
			}
			closedir($handle);
		}

		return false;
	}

	public function __tostring() {
		$html = $this->content->getHook("main", "content");
		$count = 1;
		$pattern = '/(?<!\\\)\{\{.+\}\}/';

		while(preg_match($pattern, $html, $tags, PREG_OFFSET_CAPTURE)) {
			foreach ($tags as $tag) {
				$t = str_replace("{", "", str_replace("}", "", $tag[0]));
				$parts = explode(";",$t);

				if(count($parts) > 1)
					$args = explode(",", $parts[1]);
				else
					$args = array();
				
				if($this->findAndInclude($parts[0]))
					$component = $this->create_instance($parts[0], $args);

				$html = str_replace($tag[0], (string)$component, $html, $count);
			}
		}

		return $html;
	}
}
?>