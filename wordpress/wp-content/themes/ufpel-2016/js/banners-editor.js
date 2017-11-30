jQuery(document).ready(function($) {

	function createArray2() {
		var vet = [];
		if ($("#modulosList2 li").size() != 0){
			$("#modulosList2 li").each(function(index, item) {
				vet.push($(this).attr("data-source")+$(this).attr("data-id"));
			});
			$("#banners").val(vet);
		}
		else {
			$("#banners").val("");
		}
	};

	$( "#modulos2 li" ).draggable( {
	  connectToSortable: "#modulosList2",
	  helper: "clone",
	  containment: "#poststuff",
	  scroll: false,
	  revert: "invalid"
	});


	if ( $("#modulos2").length ) {   // Executa o código abaixo apenas se estiver na aba dos banners

		// Drag & drop
		$( "#modulosList2" ).sortable( {
			connectWith: "#modulos2",
			placeholder: "highlight",
					cursor: "move",
			forcePlaceholderSize: true,
			update: function(event, ui) {
				//ui.item.attr("data-id", ui.item.index() );
				item = ui.item.next();/*
				while (item.index() != -1){
					ui.item.attr("data-id", parseInt(ui.item.attr("data-id"))+1 );
					item = item.next();
				} */
				if ($('#modulosList2 li').size() > 0) {
					$('#modulosList2').removeClass( "mlempty" )
				}
				configuracao_modificada = true;
				$(".themeoptions-modified").show();
			},
			over: function () {
				removeIntent = false;
			},
			out: function () {
				removeIntent = true;
			},
			beforeStop: function (event, ui) {
				if(removeIntent == true){
					ui.item.remove();
				}
				if ($('#modulosList2 li').size() == 1) {
					$('#modulosList2').addClass( "mlempty" )
				}
				configuracao_modificada = true;
				$(".themeoptions-modified").show();
			},
			receive: function( event, ui ) {
				var newItem = $(this).data().uiSortable.currentItem;
				newItem.attr("id", ids);

				if ( $("#config"+newItem.data("type")).length ) {	// testa se existe bloco de configuração para este tipo de módulo
						newItem.append('<a href="#">Configurar</a>');
						$("#config"+newItem.data("type")).clone().appendTo("#poststuff").attr("id", "bloco"+ids).addClass("configbloco");
				}

				ids++;
				$("#modulosList2 li a").click(function(){
					configuraModulo2($(this));
				});
				configuracao_modificada = true;
				$(".themeoptions-modified").show();
			},
		revert:250
		});

		$("#bt_submit1, #bt_submit2").click(function(){createArray2();});
		$("#modulos2, #modulosList2").disableSelection();

		function mover_banners_esquerda() {
			if ($("#modulosList2 li:last").position().left > $("#modulosList2").position().left*-1)
				$("#modulosList2").css({ left: "-=3"}, 50);
		}
		function mover_banners_direita() {
			if ($("#modulosList2").position().left < 0) {
				$("#modulosList2").css({ left: "+=3"}, 50);
			}
		}

		$("#carousel_banners_left").hover(function() {
			var delay = 15;
			banner_interval = setInterval(mover_banners_esquerda, delay);
		}, function() {
			clearInterval(banner_interval);
			$("#modulosList2").stop();
		});

		$("#carousel_banners_right").hover(function() {
			var delay = 15;
			banner_interval = setInterval(mover_banners_direita, delay);
		}, function() {
			clearInterval(banner_interval);
			$("#modulosList2").stop();
		});

		$("#carousel_banners_left").click(function() {
			if ($("#modulosList2 li:last").position().left > $("#modulosList2").position().left*-1)
				$("#modulosList2").css({ left: "-=50"}, 550);
		});
		$("#carousel_banners_right").click(function() {
			if ($("#modulosList2").position().left < 0)
				$("#modulosList2").css({ left: "+=50"}, 550);
		});

		$(".banners_proprios").animate({width : "96px", height: "66px"}, 0);

	} // end if ( $("#modulos2").length )

});