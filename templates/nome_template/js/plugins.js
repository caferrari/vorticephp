/*
jQuery Plugins By Carlos A. Ferrari
*/

/* Adiciona a classe Hover quando passa o mouse */
$.fn.autoHover = function(item){
	var i = item || ".item";
	$(this).hover(
		function(){ $(this).parents(i).addClass('hover'); },
		function(){ $(this).parents(i).removeClass('hover'); }
	);
}


/* Dialogo de Confirmação */
$.fn.confirmDialog = function (okFunc){
	$(this).live('click', 
		function (index, obj){
			var a = $(this);
			var mensagem = a.attr("title");
			var titulo = a.text();			
			obj = $("<div class=\"dialog flora\"><p>" + mensagem + "</p></div>");
			obj.dialog({ 
				modal: true, title: titulo, height: 100, dialogClass: "flora", resizable: false, draggable: false, 
				overlay: { 
					opacity: 0.5, 
					background: "black" 
				}, 
				buttons: { 
					"Sim": function(){
						dlg = $(this)
						$.ajax({
							type: "POST",
							url: a.attr("href"),
							dataType: "json",
							success: function(json){
								okFunc(a, json);
								dlg.dialog("destroy");
							},
							error: function(json){
								a.msgDialog({mensagem : "Erro no servidor!"});
								dlg.dialog("destroy"); 
							}
						});
					}, 
					"Não": function() { 
						$(this).dialog("destroy"); 
					} 
				} 
			}
		);
		return false;
	});
}

/* Dialog de alerta */
$.fn.msgDialog = function(json)
{
	$("<div class=\"dialog flora\"><p>" + json.mensagem + "</p></div>").dialog({ 
		modal: true, title: "Mensagem", height: 100, dialogClass: "flora", resizable: false, draggable: false, 
		overlay: { 
			opacity: 0.5, 
			background: "black" 
		}, 
		open: function()
		{
			setTimeout("$('.dialog').dialog(\"destroy\")",5000);
		},
		buttons: { 
			"Ok": function(){
				$(this).dialog("destroy"); 
			}
		}
	});
}

/* Carrega links em um div */
$.fn.linkToAjax = function (t){
	var div_conteiner = $(this);
	var target = t;
	$(t).filter(':not(.link_processado)').click ( function (){
		$.ajax({
			dataType : "html", type : "get", url : $(this).attr("href"),
			success : function (html) {
				div_conteiner.html(html).linkToAjax(t, div_conteiner);
			}
		})		
		return false;
	});
	$(t).addClass('link_processado');
}
