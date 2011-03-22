 $(document).ready(function() {
	initializeMenu();
 	$("#column1").hide();
 	$("#column2").hide();
	$("#nav_idea:not(.disabled)").live('click', function() {
		$("#nav_idea").addClass('disabled');
		$("#column1").delay(500).fadeIn("slow");
	});
	$("#nav_develop:not(.disabled)").live('click', function() {
		$("#nav_develop").addClass('disabled');
		$("#column2").delay(500).fadeIn("slow");
	});
	$('#plus:not(.disabled)').live('click', function () { addIdea(); });
 });


 function addIdea() {
	$('#plus').addClass('disabled');
	var formTarget = "lala.php"
	var formHead = $('<form method="post" action="'+formTarget+'">');
	
	//var formTail = $('</form>');
	formHead.append($('<input type="text" name="name" value="your name"/>'));
	formHead.append($("<br/>"));
	formHead.append($('<textarea rows="8" cols="18" name="description">your description</textarea>'));
	formHead.append($("<br/>"));
	formHead.append($('<input type="text" name="contact" value="your email"/>'));
	formHead.append($("<br/>"));
	formHead.append($('<input type="submit" class="submit" value="post idea"/>'));

	$('#column1').append($("<hr/>"));
	$('#column1').append(formHead);

 }
 
function initializeMenu() {
		$("#ideaCloud").siblings().hide();
		$("#ideaCloud").fadeIn();
		$("#nav_ideaCloud").addClass('menuSelected');
		$("#mainmenu > ul > li").click(function () { switchMenus($(this).attr('id')); });
		
}

function switchMenus(content2show) {
	var content = content2show.substring(4)
	$("#ideaCloud").hide();
	$("#ideaCloud").siblings().fadeOut("fast");
	$("#mainmenu > ul > li").removeClass('menuSelected');
	$("#"+content).fadeIn("fast");
	$("#"+content2show).addClass('menuSelected');
}
