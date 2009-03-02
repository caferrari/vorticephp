$(document).ready(function() {
	$('td').autoHover();
	$("ul.checkbox").hideChecks();
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
	
	$("#conteudo a:not(.confirm)").live("click", function(){
		$("#conteudo").load($(this).attr("href"));
		return false;
	});
	
	window.setTimeout("$('#mensagem').fadeOut(\"slow\")", 10000);
});

