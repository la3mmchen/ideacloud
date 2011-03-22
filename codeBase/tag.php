<?php 
class tag {
	private $tag_name;
	private $tag_id;
	private $tag_ideas; /* for loading corresponding ideas */
	private static $dbInt;
		
	public function __construct() {
		$this->buildDbInterface();
		$in = func_get_args();
		$i = func_num_args();
		if (method_exists($this,$f='__construct'.$i)) {
			call_user_func_array(array($this,$f),$in);
		} 
	}
	public function __construct0() {
		
	}
	public function __construct1($name) {
		$this->tag_name = $name;
	}
	public function __construct2($id, $name) {
		$this->tag_name = $name;
		$this->tag_id = $id;
	}
	
	public function writeTag2db() {
		self::$dbInt->tag_add(array("tag_id"=>$this->tag_id, "tag_name"=>$this->tag_name));
	}
	
	private function buildDbInterface() {
		self::$dbInt = new dbInterface();
	}
	
	public function loadIdeasToTag() {
		$this->tag_ideas = self::$dbInt->tag_loadIdeas2Tag($this->tag_name);
		return $this->tag_ideas;
	}
	
	public function getTagCloud() {
		$result = self::$dbInt->tag_tagCloud();
		/* get max value */
		 $max = $result[0]["e"];
		 if ($max == 0) { $max++; }
		 foreach ($result as $row) {
				$percent = ($row["e"] * 100) / $max;
				$resultArray[$row["tag2id_tagName"]] = $this->mapPercentToValue($percent);
		}
		$res = $this->shuffle_assoc($resultArray);
		return $res;
	}
		
	/**
	 * TODO needs a smarter solution
	 * 
	 * **/
	private function mapPercentToValue($num) {
		if ($num >= 0 and $num < 20)  {
			return "one";
		}
		else if ($num >= 20 and $num < 40) {
			return "two";
		}
		else if ($num >= 40 and $num < 60) {
			return "three";
		}
		else if ($num >= 60 and $num < 80) {
			return "four";
		}
		else if ($num >= 80 and $num <= 100) {
			return "five";
		}
	}
	
	private function shuffle_assoc($list) {
		  if (!is_array($list)) return $list;
		  $keys = array_keys($list);
		  shuffle($keys);
		  $random = array();
		  foreach ($keys as $key)
			$random[$key] = $list[$key];
		  return $random;
	} 

}
