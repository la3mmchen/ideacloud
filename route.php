<?php
$glob_debug = true;
/* includes */
require_once("codeBase/logger.php");
$glob_debug ? require_once("codeBase/idea.php") : null;
$glob_debug ? require_once("codeBase/dbInterface.php") : null;
$glob_debug ? require_once("codeBase/tag.php") : null;
/* we need a logger object */
$logger = new logger();
if (isset($_POST["idea_name"])  and isset($_POST["idea_email"]) and isset($_POST["idea_description"])) {
		/**
		 *  TODO smarter solution wanted
		 * */
		$name = $_POST["idea_name"];
		$mail = $_POST["idea_email"];
		$description = $_POST["idea_description"];
		$ts = "";
		$glob_debug ? null : require_once("codeBase/idea.php");
		$newIdea = new idea();
		$newIdea->addIdea($name, $mail, $description, $ts);
		echo "-> ". $newIdea->get_ideaUniqueId();
}
else if (isset($_POST["id"])) {
		$glob_debug ? null : require_once("codeBase/idea.php");
		$logger->write("request for id ". $_POST["id"]. " received");
		/* initialize idea object with values from db */
		$idea = new idea($_POST["id"]);
		$logger->write(json_encode($idea->toClient()));	
		echo json_encode($idea->toClient());
}
else if (isset($_POST["tagid"]) and isset($_POST["tag"])) {
		$logger->write("request for adding tag ". $_POST["tag"] ."to ". $_POST["tagid"]);
		$glob_debug ? null : require_once("codeBase/tag.php");
		$tag = new tag($_POST["tagid"], $_POST["tag"]);
		$tag->writeTag2db();
		echo TRUE;
}
else if (isset($_POST["load"])) {
		if ($_POST["load"] == "cloud") {
			$logger->write("request for loading cloud");
			$glob_debug ? null : require_once("codeBase/tag.php");
			$tag = new tag();
			$tagCloud = $tag->getTagCloud();
			echo json_encode($tagCloud);
		}
		else if ($_POST["load"] == "tag") {
			$logger->write("request for loading tag".$_POST["tag"]);
			$tag = new tag($_POST["tag"]);
			echo json_encode($tag->loadIdeasToTag());
		}
}
/* we need to destruct the logger object */
$logger->__descruct();

/* based on action we need to forward the user */
?>
