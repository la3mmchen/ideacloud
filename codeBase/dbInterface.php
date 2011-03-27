<?php 
class dbInterface {
	private static $dbHost;
	private static $dbUser;
	private static $dbPw;
	private static $dbDatabase;
		
	public function __construct() {
			//constructor needs to load values from file
			require_once("dbConfig.php");
			self::$dbHost = $conf_dbHost;
			self::$dbUser = $conf_dbUser;
			self::$dbPw = $conf_dbPw;
			self::$dbDatabase = $conf_dbDatabase;
			// drop values from file
			unset($conf_dbDatabase);
			unset($conf_dbHost);
			unset($conf_dbPw);
			unset($conf_dbUser);
	}
	/**
	 *  prepares a new table-entry at tbl_idea with given params, additionally creates an entry at tbl_idea2id with idea_id and idea_uniqueId
	 *  @params: value to insert into tbl_idea in associative array
	 *  @returns: nothing
	 * */
	public function idea_insertQuery($insertArray) {
			$insertQuery = 'INSERT INTO `ideacloud`.`tbl_idea` (`idea_id`, `idea_name`, `idea_mail`, `idea_description`, `idea_timestamp`) VALUES (NULL, "'.$insertArray["idea_name"].'", "'.$insertArray["idea_mail"].'","'.$insertArray["idea_description"].'", CURRENT_TIMESTAMP)';
			$unId = $this->executeQuery($insertQuery);
			$insertQuery = 'INSERT INTO `ideacloud`.`tbl_idea2id` (`idea2id_ideaId`, `idea2id_uniqueId`) VALUES ("'.$unId.'", "'.$insertArray["idea_uniqueId"].'")';
			$this->executeQuery($insertQuery);
	}
	/**
	 * prepares loading from db to object with given unique id
	 * @params: uniqueId
	 * @returns: array with parameters
	 * */
	public function idea_loadQuery($uniqid) {
			$selectQuery = "Select a.idea_id, a.idea_name, a.idea_mail, a.idea_description, a.idea_timestamp FROM ideacloud.tbl_idea a, ideacloud.tbl_idea2id b WHERE b.idea2id_uniqueId like \"".$uniqid."\" AND b.idea2id_ideaId = a.idea_id";
			return $this->executeQuery($selectQuery);
	}
	
	public function tag_add($insertArray) {
			$insertQuery = 'INSERT INTO `ideacloud`.`tbl_tag2id` (`tag2id_tagName`, `tag2id_ideaId`) VALUES ("'.$insertArray["tag_name"].'", "'.$insertArray["tag_id"].'")';
			return $this->executeQuery($insertQuery);
	}
	
	public function tag_tagCloud() {
			$selectQuery = "SELECT count(*) as e, `tag2id_tagName` FROM `tbl_tag2id` group by `tag2id_tagName` order by e desc";
			return $this->LoadPayload($selectQuery);
	}
	
	public function tag_loadIdeas2Tag($tag) {
			$selectQuery= "SELECT a.`idea_id`, a.`idea_name`, a.`idea_description`, a.`idea_timestamp` FROM `tbl_idea` a where a.`idea_id` IN \n"
						. "( SELECT distinct(`tag2id_ideaId`) FROM `tbl_tag2id` WHERE `tag2id_tagName` like \"".$tag."\" ORDER BY `tbl_tag2id`.`tag2id_tagName` ASC\n"
						. ")";
			return $this->LoadPayload($selectQuery);
	}
	public function idea_loadAnswers2Idea($id) {
			$selectQuery = "SELECT `answer_name` , `answer_mail` , `answer_text` , `answer_timestamp` FROM tbl_answer a where a.answer_id IN \n"
						. "( SELECT distinct(answer2id_answerId) FROM tbl_answer2idea WHERE answer2id_ideaId = ".$id." \n"
						. "ORDER BY tbl_answer2idea.answer2id_ideaId ASC ) LIMIT 0, 30 ";
			return $this->LoadPayload($selectQuery);
	}
	
	public function insertDb($table, $array) {
		// array containing data
		$sql  = "INSERT INTO `ideacloud`.`".$table."`";	
		$sql .= " (`".implode("`, `", array_keys($array))."`)";
		$sql .= " VALUES ('".implode("', '", $array)."') ";
		
		return $this->executeQuery($sql);
	}
	/**
	 * 
	 * TODO need a smarter solution
	 * 
	 * **/
	private function executeQuery($iQ) {
			$rc = 0;
			/* connect to dbms */
			$dbHandle = mysql_connect(self::$dbHost, self::$dbUser,self::$dbPw);
			if (!$dbHandle) {
				die("no db connection" . mysql_error());
			}
			/* select db */
			mysql_select_db(self::$dbDatabase) or die("wrong databse");
			/* execute query */
			$mysqlResult = mysql_query($iQ);
			/* return id on insert */
			preg_match("/INSERT/i",$iQ) == 1 ? $rc = mysql_insert_id() : $rc = mysql_fetch_assoc($mysqlResult);
			/* close db*/
			mysql_close($dbHandle);
			return $rc;
	}
	/**
	 * 
	 * TODO need a smarter solution
	 * 
	 * **/
		private function LoadPayload($iQ) {
			$rc = 0;
			/* connect to dbms */
			$dbHandle = mysql_connect(self::$dbHost, self::$dbUser,self::$dbPw);
			if (!$dbHandle) {
				die("no db connection" . mysql_error());
			}
			/* select db */
			mysql_select_db(self::$dbDatabase) or die("wrong databse");
			/* execute query */
			$mysqlResult = mysql_query($iQ);
			/* build new array with all results */
			while(($resultArray[] = mysql_fetch_assoc($mysqlResult)) || array_pop($resultArray));
			/* close db*/
			mysql_close($dbHandle);
			return $resultArray;
	}
}

?>
