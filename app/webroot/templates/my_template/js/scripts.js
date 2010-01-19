$(document).ready(function() {
	$('td').autoHover();
	$("a.confirm").confirmDialog(
		function (item, json)
		{ 
			if (json.status==1){
				item.parents(".item:first").fadeOut("slow");
			}else{
				$(this).msgDialog(json);
			}
		}
	);
	window.setTimeout("$('#mensagem').fadeOut(\"slow\")", 5000);
});

