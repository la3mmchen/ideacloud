/**
 * ideacloud.us - Script for the ideacentre
 * 
 * Version: 0.1
 * Last Update: 03/22/2011 -> added documentation
 * 
 * 
 * Copyright (c) by the ideacloud.us project
 * https://github.com/la3mmchen/ideacloud
 *  */
 
/* this function shoud be loaded after finishing loading */
$(document).ready(function() {	
	/* we don't want to reload the page on clicking the submit button, so we
	 * overwrite the submit-event */
	$("#ideaCentre_in").submit(function() {
		/* we need the id an user has typed */
		var insertedId  = $("#idea_id").attr("value");
		/* we need another js function which loads from backend */
		loadIdea(insertedId);
		return false;
	});
	/* again, we don't want to reload the page */
	$("#ideaCentre_tag").submit(function() {
		var userInputTag  = $("#tag_name").attr("value");
		var userInputTagId  = $("#tag_idea").attr("value");
		/* we need another js function for adding a tag to the backend */
		addTag(userInputTag,userInputTagId);
		return false;
	});
});
/* this function trys to load the submitted information via a XHR */
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
			loadAnswer2idea(rcObj.idea_id);	
			return true;
			},
		error: function(rc, rcText) {
			$('<p> '+rcText+' </p>').insertAfter("#submit_ideaCentre");
		}
	});
  return false;
}
/* this function trys to add a tag to an idea via a XHR */
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


function loadAnswer2idea (idea2loadAnswers) {
		$.ajax({
		type: "POST",
		url: "route.php",
		data: "load=answers&idForAnswers="+idea2loadAnswers,
		dataTyp: "json",
		success: function(rcJson) {
			/**
			 * TOOD create nicer output
			 * */
			var obj = jQuery.parseJSON(rcJson);
			var ul = $('<ul>');
			$.each(obj, function(i, val) {
						$.each(val, function(x,y) {
							var li = $("<li>");
							li.append(x + " => "+ y);
							li.appendTo(ul);
						});
			});
			$(".ideaCentre_answers").append(ul);
			return true;}
		});
}
