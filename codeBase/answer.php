<?php
/**
 * ideacloud.us - Class for any answering action
 * 
 * Version: 0.1
 * Last Update: 03/26/2011 -> added documentation
 * 
 * 
 * Copyright (c) by the ideacloud.us project
 * https://github.com/la3mmchen/ideacloud
 *  */
/* includes */
$glob_debug ? null : require_once("codeBase/dbInterface.php");

class answer {
		/* globals */
		private $answer_id;
		private $answer_name;
		private $answer_mail;
		private $answer_text;
		private $answer_timestamp;
		private $idea_id;
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
		private function __construct1($array) {
				$this->answer_name = $array["answer_name"];
				$this->answer_mail = $array["answer_mail"];
				$this->answer_text = $array["answer_text"];
				$this->idea_id = $array["idea_id"];
				$this->answer_id = self::$dbInt->insertDb("tbl_answer", array("answer_name"=>$this->answer_name, "answer_mail"=>$this->answer_mail, "answer_text"=>$this->answer_text));
				$this->linkAnswer2Idea();
		}
		
		public function toClient() {
		}
		
		private function linkAnswer2Idea(){
			self::$dbInt->insertDb("tbl_answer2idea",array("answer2id_answerId"=>$this->answer_id, "answer2id_ideaId"=>$this->idea_id));		
		}
		
		private function buildDbInterface() {
			self::$dbInt = new dbInterface();
		}
}

?>
