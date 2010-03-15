// JavaScript Document
$(document).ready(function(){
	
	$("#box-recursos li").css({opacity: '0'}).hide();	
	//$('#box-recursos li:nth-child(3n+1), #box-recursos li:first').addClass('marcador');
	
	$('#box-recursos li').each( function(i) {
		i = i+1;
		$(this).attr({id: 'm'+i+''});
		//$(this).addClass('m'+i+'');
	});
	$("#box-recursos li:eq(0), #box-recursos li:eq(1), #box-recursos li:eq(2)").addClass("ativo").css({opacity: '1'}).show();
	$('#r-menu span:first').addClass('clicado');
	
	function atotal(obj) {
		vel = 300
		if ($(obj.attr("rel")).hasClass("ativo")) return;
		
		$("#box-recursos li.ativo:eq(0)").stop().animate({ opacity: 0 }, vel, function(){
			$(this).next().animate({ opacity: 0 }, vel, function(){
				$(this).next().animate({ opacity: 0 }, vel, function() {
					$("#box-recursos li").hide().removeClass("ativo");		
					
					/*Mostrando os itens de acordo com o Link clicado*/					
					$("#box-recursos").find(obj.attr("rel")).addClass("ativo").next().addClass("ativo").next().addClass("ativo");
										
					$("#box-recursos li.ativo:eq(0), #box-recursos li.ativo:eq(1), #box-recursos li.ativo:eq(2)").show();
					$("#box-recursos li.ativo:eq(0)").stop().animate({ opacity: 1 }, vel, function(){
						$(this).next().animate({ opacity: 1 }, vel, function(){
							$(this).next().animate({ opacity: 1 }, vel);
						});
					});
					
				});
			});
		});	
	}

	$('#r-menu').find('span').each( function(i) {
		i = 3*i+1;
		$(this).attr({rel: '#m'+i+''});
	});
	
	$('#r-menu span').click(function() {
		$(this).addClass("clicado").siblings().removeClass("clicado");
		atotal($(this));		
	});
	$.Juitter.start({
		searchType:"fromUser",
		searchObject:"vorticephp", 
		live:"live-15",
		placeHolder:"twitter", 
		loadMSG: "Carregando Mensagens...",
		imgName: "loader.gif", 
		total: 4, 
		readMore: "Read it on Twitter", 
		nameUser:"text", 
		openExternalLinks:"newWindow", 
                filter:"sex->*BAD word*,porn->*BAD word*,fuck->*BAD word*,shit->*BAD word*" 
	});
	/*
	// Apresentação automática
	var auto = function(){ 
		clearInterval();
		var next = $("#r-menu span.clicado").next();
		if (next.length == 0) next = $("#r-menu span:first");
		next.trigger("click");
		setTimeout(auto, 5000);
	};
	auto();
	*/
	/*$('#r-menu span').click(function() {
		
		var primeiro = $("#r-menu span").index(this) * 3;
				
		$("#box-recursos li").hide();
		
		for (var x=primeiro; x < (primeiro+3); x++){
			$("#box-recursos li")
			$("#box-recursos li:eq(" + x + ")").show();
		}
	});*/
	
	// ----- Guardar abaixo
	/*$('#r-menu span').click(function() {
		mostra4($(this));
	});
	
	function mostra4(obj) {
		$(obj.attr("rel")).hide();
	}*/
	
	////////////////////////////
	
});