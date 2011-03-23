<?php
/* includes */
$glob_debug ? null : require_once("codeBase/dbInterface.php");

class answer {
		/* globals */

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

		}
		/**
		 *  build idea object with saved date 
		 * */
		private function __construct1($uniqueId) {

		}
		
		public function toClient() {
		}
		
		private function buildDbInterface() {
			self::$dbInt = new dbInterface();
		}
}

?>
