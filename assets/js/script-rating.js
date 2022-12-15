$("label").click(function(){
	$(this).parent().find("label").css({ "background-color": "#e7e7e7"});
	$(this).css({ "background-color": "rgb(246, 157, 41)"});
	$(this).nextAll().css({ "background-color": "rgb(246, 157, 41)"});
	});
	$(".star label").click(function(){
		$(this).parent().find("label").css({ "color": "#e7e7e7"});
		$(this).css({ "color": "rgb(246, 157, 41)"});
		$(this).nextAll().css({ "color": "rgb(246, 157, 41)"});
	  $(this).css({"background-color": "transparent"});
	  $(this).nextAll().css({"background-color": "transparent"});
	});