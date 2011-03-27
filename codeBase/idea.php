<?php
/* includes */
$glob_debug ? null : require_once("codeBase/dbInterface.php");

class idea {
		/* globals */
		private $idea_id;
		private $idea_name;
		private $idea_mail;
		private $idea_description;
		private $idea_timestamp;
		private $idea_uniqueId;
		private static $dbInt;
		
	
		/**
		 *  php doesn't allow multiple constructors so we have to decide
		 * */
		public function __construct() {
				$this->buildDbInterface();
				$in = func_get_args();
				$i = func_num_args();
				if (method_exists($this,$f='__construct'.$i)) {
					call_user_func_array(array($this,$f),$in);
				} 
		}
		
		/**
		 *  build a new object
		 * */
		private function __construct0() {
			$this->idea_uniqueId = $this->createUniqueId();
		}
		/**
		 *  build idea object with saved date 
		 * */
		private function __construct1($uniqueId) {
			$this->idea_uniqueId = $uniqueId;
			$this->initializeValues();
		}
		
		public function toClient() {
			return array("idea_id"=>$this->idea_id, "idea_name"=>$this->idea_name, "idea_mail"=>$this->idea_mail, "idea_description"=>$this->idea_description, "idea_timestamp"=>$this->idea_timestamp, "idea_uniqueId"=>$this->idea_uniqueId);
		}
		
		public function loadAnswersToIdea($ideaId) {
			$this->idea_id = $ideaId;
			return self::$dbInt->idea_loadAnswers2Idea($this->idea_id);
		}
		
		/**
		 * 
		 * TODO
		 * */
		private function initializeValues() {
			$array = self::$dbInt->idea_loadQuery($this->idea_uniqueId);
			$this->idea_id = $array["idea_id"];
			$this->idea_name = $array["idea_name"];
			$this->idea_mail = $array["idea_mail"];
			$this->idea_description = $array["idea_description"];
			$this->idea_timestamp = $array["idea_timestamp"];
		}
		
		
		private function buildDbInterface() {
			self::$dbInt = new dbInterface();
		}
		/**
		 * @returns: $idea_uniqueId
		 * 
		 * */
		public function get_ideaUniqueId() {
			return $this->idea_uniqueId;
		}
		
		public function addIdea($name, $mail, $description, $ts) {
			$insertArray = array( 	"idea_id" => NULL,
									"idea_name" => $name,
									"idea_mail" => $mail,
									"idea_description" => $description,
									"idea_timestamp" => "CURRENT_TIMESTAMP",
									"idea_uniqueId" => $this->idea_uniqueId);
			self::$dbInt->idea_insertQuery($insertArray);
		}
		/**
		 *  creates an unique id with random contents an random length from 10 to 30
		 * @return string containing id
		 * */
		private function createUniqueId() {
			// pool
			$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
			$length =rand(10, 30);
			// start creation
			srand((double)microtime()*1000000);
			$i = 0; 
			$rndStr = "";
			while ($i < $length) { 
					$num = rand() % strlen($chars);
					$tmp = substr($chars, $num, 1);
					$rndStr = $rndStr . $tmp;
					$i++;
			}
			return $rndStr;
		}
}



?>
