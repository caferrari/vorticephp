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
	
	window.setTimeout("$('#mensagem').fadeOut(\"slow\")", 10000);

	/*
	$("#conteudo a").live("click", function(){
		var href = $(this).attr("href");
		$("#conteudo").load(href);
		window.location.hash = href.replace(rootvirtual, '');
		return false;
	});
	if (window.location.hash != '') $("#conteudo").load((rootvirtual + window.location.hash).replace("#", ""));
	*/
});

