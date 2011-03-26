/**
 * ideacloud.us - Script for loading the idea cloud
 * 
 * Version: 0.1
 * Last Update: 03/22/2011 -> added documentation
 * 
 * 
 * Copyright (c) by the ideacloud.us project
 * https://github.com/la3mmchen/ideacloud
 *  */
$(document).ready(function() {	
	loadCloud();
});

function loadCloud() {
	$(".ideaCloud").append('<img style="margin-left: 5px;" src="images/load.gif" id="loading"/>');
	$.ajax({
		type: "POST",
		url: "route.php",
		data: "load=cloud",
		dataTyp: "json",
		success: function(rc) {
			var isIdeaHTML = $(location).attr('href').search(/idea.html/i);
			$(".ideaCloud > img").remove();
			var obj = jQuery.parseJSON(rc);
			$.each(obj, function(i, val){
				var p = $("<p>");
				p.click(function() { 
					if(isIdeaHTML != -1) {
						$(this).siblings().removeClass("selected"); $(this).addClass("selected"); loadTag(i); 
					}
					else {
						window.location.href = "idea.html"; //$(this).siblings().removeClass("selected"); $(this).addClass("selected"); loadTag(i); 
					}
						});
				
				p.append(i);
				p.attr("class", val);
				p.appendTo(".ideaCloud");
			});
			return true;}
	});
	

}

function loadTag(tag) {
	$(".ideaCloud_tag").css("visibility" , "visible");
	$(".ideaCloud_idea").css("visibility" , "hidden");
	$(".ideaCloud_tag > h2 > ins").remove();
	$(".ideaCloud_tag > ul").remove();
	$(".ideaCloud_idea > form").remove(); /* clean any forms - really don't know why this line is needed */
	$(".ideaCloud_tag > h2").append(" <ins>"+tag+"</ins>");
	
	//var rcJson = '{	"idea1" : "lala1", "idea2" : "lala2" }';

	$.ajax({
		type: "POST",
		url: "route.php",
		data: "load=tag&tag="+tag,
		dataTyp: "json",
		success: function(rcJson) {
			var obj = jQuery.parseJSON(rcJson);
			var uL = $('<ul>');
			$.each(obj, function(i, val) {
						var li = $("<li>");
						li.click(function() { $(this).siblings().removeClass("selected"); $(this).addClass("selected"); loadIdea(val); });
						li.append(val.idea_name);
						li.appendTo(uL);
			});
			uL.appendTo(".ideaCloud_tag");
			return true;}
	});


}

function loadIdea(obj_idea) {
	$(".ideaCloud_idea").css("visibility" , "visible");
	$(".ideaCloud_idea > p").remove();
	$(".ideaCloud_idea > form").remove(); /* clean any forms - really don't know why this line is needed */
	/**
	 * TODO create nicer output */
	$.each(obj_idea, function(i, val) {
		$(".ideaCloud_idea").append("<p>"+i + " => " + val + "</p>");
	});	
	/**
	 * TODO add a nice icon
	 * **/
		$(".ideaCloud_idea").append("<p id='postAnswer'> answer idea owner </p>");
		$("#postAnswer:not(.disabled)").live('click', function() { 
				$(this).addClass('disabled');
				insertAnswerForm(obj_idea.idea_id);
		});
		
}

/* append a form after the idea details */
/**
 * TODO why does this function is loaded repeatedly **/
function insertAnswerForm(id) {
	$(".ideaCloud_idea > form").remove(); /* clean any forms - really don't know why this line is needed */
	var form = $('<form method="post" action="route.php">');
	var fieldset = $('<fieldset class="s_column">');
	fieldset.append('<legend>answer to an idea</legend>');
	fieldset.append(' <div><input type="hidden" id="idea_id" name="idea_id" value="'+id+'"  /></div>');
	fieldset.append(' <div><label for="answer_name">your name</label><input type="text" id="answer_name" required="required" class="box_shadow" name="answer_name"  /></div>');
	fieldset.append('<div><label for="answer_mail">your mail</label><input type="email" id="answer_mail" required="required" class="box_shadow" min="10" name="answer_mail"/></div>');
	fieldset.append('<div><label for="answer_text">your text for idea owner</label><textarea class="box_shadow textarea" name="answer_text" id="answer_text" cols="30" rows="10"></textarea></div>');
	fieldset.append('<input type="submit" value="answer &rarr;" />');
	form.append(fieldset);
	$(".ideaCloud_idea").append(form);
}

