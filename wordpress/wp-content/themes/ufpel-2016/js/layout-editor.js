jQuery(document).ready(function($) {

	function createArray(){
		var vet = [];
		if ($("#modulosList li").size() != 0){
			$("#modulosList li").each(function(index, item) {
				vet.push($(this).attr("data-type")+"-"+$(this).attr("data-id")+"-"+$(this).attr("data-options"));
			});

			$("#wpinst_modulos").val(vet);
		}
		else{
			$("#wpinst_modulos").val("");
		}
	};

		$( document ).on( "click", "#icon_help2, #icon_help", function() {
			bloco_data_type = $(this).parent().attr("data-type");
			$("#help"+bloco_data_type).css("top", $("#help"+bloco_data_type).position().top+100+"px");
			$("#help"+bloco_data_type).fadeIn();
			$("#fundo_modal2").fadeIn();
	});


	function configuraModulo( elemento ) {

		id = elemento.parent().attr("id");
				$("#bloco"+id).fadeIn();
				$("#fundo_modal2").fadeIn();
		// Popula campos da janela de configuração com os valores do data-options do módulo
		vetoptions = elemento.parent().attr("data-options").split(":");
		i=0;
		$("#bloco"+id).find(":input").each(function(){
			$(this).val(vetoptions[i]);
			i++;
		});

		// Fecha janela de configuração
		$("#bloco"+id).fadeIn().find("a#fechaconfig").click(function(){$(this).parent().parent().fadeOut(); $("#fundo_modal2").fadeOut();});

		// Transfere valores dos campos de configuração para o data-options do módulo, ao clicar no link 'Salvar'
		$("#bloco"+id).fadeIn().find("#salvaconfig").click(function(){
			var configstr = [];
			$(this).parent().parent().find(":input").each(function(){
				configstr.push( $(this).val().replace(/,/g,".").replace(/\-|:/g,"\u2013") ); // substitui caracteres usados como separadores nos campos de texto (- : ,)
			});

			idgeral = $(this).parent().parent().attr("id").substring(5);

			$("#"+idgeral).attr("data-options", configstr.join(":"));
			$(this).parent().parent().fadeOut();
			$("#fundo_modal2").fadeOut();

			configuracao_modificada = true;
			$(".themeoptions-modified").show();
		});
	} // end function configuraModulo()


	$("#wpinst-options-form input, #wpinst-options-form textarea").change(function(){
		configuracao_modificada = true;
		$(".themeoptions-modified").show();
	});

	$("#wpinst-options-form").submit(function(){
		configuracao_modificada = false;
		$(".themeoptions-modified").hide();
	});


	if ( $("#modulos").length ) {   // Executa o código abaixo apenas se estiver na aba Layout

		$( "#modulos li" ).draggable({
		  connectToSortable: "#modulosList",
		  helper: "clone",
		  containment: "#poststuff",
		  scroll: false,
		  revert: "invalid"
		});


		// Monta lista de módulos atualmente em uso no site

		if (arrayIds != "") {

			//var titulos = {'Imagemdest':'Imagem de Capa', 'Lista':'Lista de Posts', 'Carossel':'Carrossel de Banners'};
			var configLink, helpLink;

			arrayIds = arrayIds.split(",");
			arrayWidgets = $('#wpinst_widgets').val().split(",");

			$.each(arrayIds, function(index, item) {
				item = item.split("-");
				configLink = "";

				if ( $("#config"+item[0]).length ) {    // testa se existe bloco de configuração para este tipo de módulo
					configLink = '<a href="#">Configurar</a>';
					$("#config"+item[0]).clone().appendTo("#poststuff").attr("id", "bloco"+ids).addClass("configbloco");
				}

				helpLink = '<div id="icon_help2"></div><span id="help">?</span>'; // adiciona link de help para o módulo

				$("#modulosList").append('<li id="'+ids+'" class="'+item[0]+'" data-type="'+item[0]+'" data-id="'+item[1]+'" data-options="'+item[2]+'" class="ui-state-default ui-draggable" style="display: list-item;">'+$("#"+item[0]).data("title")+helpLink+configLink+'</li>');
				ids++;
			});

			$("#modulosList li a").click(function(){
				configuraModulo($(this));
			});

			if ($('#modulosList li').size() > 1) {
				$('#modulosList').removeClass( "mlempty" );
			}
		}


		// Drag & drop

		$( "#modulosList" ).sortable({
			connectWith: "#modulos",
			placeholder: "highlight",
			cursor: "move",
			forcePlaceholderSize: true,
			update: function(event, ui) {
				ui.item.attr("data-id", ui.item.index() );
				item = ui.item.next();
				while (item.index() != -1){
					ui.item.attr("data-id", parseInt(ui.item.attr("data-id"))+1 );
					item = item.next();
				}
				if ($('#modulosList li').size() > 0) {
					$('#modulosList').removeClass( "mlempty" )
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
				if ($('#modulosList li').size() == 1) {
					$('#modulosList').addClass( "mlempty" )
				}
				configuracao_modificada = true;
				$(".themeoptions-modified").show();
			},
			receive: function( event, ui ) {
				var newItem = $(this).data().uiSortable.currentItem;
				newItem.attr("id", ids);

				if ( $("#config"+newItem.data("type")).length ) {   // testa se existe bloco de configuração para este tipo de módulo
					newItem.append('<a href="#">Configurar</a>');
//					newItem.find("#icon_help2").remove();
//					newItem.find("#help").remove();
					$("#config"+newItem.data("type")).clone().appendTo("#poststuff").attr("id", "bloco"+ids).addClass("configbloco");
				}

				ids++;

				$("#modulosList li a").click(function(){
						configuraModulo($(this));
					});
				configuracao_modificada = true;
				$(".themeoptions-modified").show();
			},
		revert:250
		});

		$("#bt_submit1, #bt_submit2").click(function(){createArray();});
		$("#modulos, #modulosList").disableSelection();

		$("#fundo_modal2, #fechaconfig").click(function() {
			$(".configs").fadeOut();
			$("#fundo_modal2").fadeOut();
		});

	}  // end if ( $("#modulos").length )
});