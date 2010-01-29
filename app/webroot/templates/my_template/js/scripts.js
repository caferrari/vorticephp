$(document).ready(function() {
	$('td').autoHover();
	$("a.confirm").confirmDialog(
		function (item, json)
		{ 
			if (json.status==1){
				item.closest(".item").fadeOut("slow");
			}else{
				$(this).msgDialog(json);
			}
		}
	);
	window.setTimeout("$('#message').slideUp(\"slow\")", 5000);
});

