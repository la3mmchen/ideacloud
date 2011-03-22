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
			//var rcJson = '{	"tag1" : "five", "tag2" : "ten", "tag3" : "one", "tag4" : "four" , "tag5" : "six", "tag6" : "nine", "tag7" : "nine", "tag8" : "three", "tag10" : "two", "tag11" : "five", "tag12" : "five", "tag13" : "ten", "tag14" : "one", "tag15" : "four" , "tag16" : "six", "tag17" : "nine", "tag18" : "nine", "tag19" : "three", "tag20" : "two", "tag21" : "five"}';
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

	$.each(obj_idea, function(i, val) {
		$(".ideaCloud_idea").append("<p>"+i + " => " + val + "</p>");
	});	
}
