$(document).ready(function() {	
	$("#ideaCentre_in").submit(function() {
		var userId  = $("#idea_id").attr("value");
		loadIdea(userId);
		return false;
	});
	$("#ideaCentre_tag").submit(function() {
		var userTag  = $("#tag_name").attr("value");
		var userTagId  = $("#tag_idea").attr("value");
		addTag(userTag,userTagId);
		return false;
	});
});

function loadIdea(id) {
	$('<img style="margin-left: 5px;" src="images/load.gif" id="loading"/>').insertAfter("#submit_ideaCentre");
	$.ajax({
		type: "POST",
		url: "route.php",
		data: "id=" + id,
		dataTyp: "json",
		success: function(rc) {
			$("#loading").remove();
			var rcObj = jQuery.parseJSON(rc);
			$("#idea_name").attr("value",rcObj.idea_name);
			$("#idea_email").attr("value",rcObj.idea_mail);
			$("#idea_description").attr("value",rcObj.idea_description);
			$("#tag_name").removeAttr("disabled");
			$("#tag_idea").attr("value", rcObj.idea_id);
			return true;},
		error: function(rc, rcText) {
			$('<p> '+rcText+' </p>').insertAfter("#submit_ideaCentre");
		}
	});
  return false;
}

function addTag(tag, id) {
	$("#tag_name").attr("value", "");	
	$.ajax({
	type: "POST",
	url: "route.php",
	data: "tagid=" + id +"&tag="+tag,
	dataTyp: "json",
	success: function(rc) {
		$("#userFeedback").html('<ins class="positive"> tag added </ins>').hide().delay(500).fadeIn("slow").delay(5000).fadeOut("slow");
		return true;}
	});
}
