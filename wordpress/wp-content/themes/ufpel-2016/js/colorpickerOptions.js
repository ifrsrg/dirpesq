jQuery(document).ready(function($){
	
    var myOptions = {
	    // you can declare a default color here,
	    // or in the data-default-color attribute on the input
	    defaultColor: '#172b54', //false,
	    // a callback to fire whenever the color changes to a valid color
	    change: function(event, ui){
	       // event = standard jQuery event, produced by whichever control was changed.
	       // ui = standard jQuery UI object, with a color member containing a Color.js object

	    	$('.corFundo').css('background-color', ui.color.toString());
	    	$('.corTexto').css('color', ui.color.toString());

	    	preview = $("#customize-preview").find("iframe"); // para atualizar preview do site no personalizar

	    	if (preview.length) {
				$(".corFundo", preview.contents()).css('background-color', "#FFF");
				$('.corTexto', preview.contents()).css( "color", ui.color.toString() );
				$("#titulo", preview.contents()).mouseenter(function() {
				    $(this).css("color", ui.color.toString());
				}).mouseleave(function() {
				    $(this).css("color", "#666");
				});
				$('.corFundo', preview.contents()).css( "background-color", ui.color.toString() );
				$('.corBorda', preview.contents()).css( "border-color", ui.color.toString() );
				$('.corFill', preview.contents()).css( "fill", ui.color.toString() );
			}

			$("#customize-control-inst_color_scheme input.wp-color-picker-field").val(ui.color.toString()).trigger("change"); // força atualização no campo de texto, para habilitar o botão de Salvar do personalizar

	    },
		// a callback to fire when the input is emptied or an invalid color
	    clear: function() {},
	    // hide the color picker controls on load
	    hide: true,
	    // show a group of common colors beneath the square
	    // or, supply an array of colors to customize further
	    palettes: ['#172b54', '#6badd5', '#27a9e1', '#e8c21d', '#e28e26', '#f5a61c', '#7aa740', '#9cb33a', '#a7af38', '#8f181b']
	};
	 
	$('.wp-color-picker-field').wpColorPicker(myOptions);	// ativa color picker nos campos de texto com a classe (no theme-options.php e theme-customize.php)

});

