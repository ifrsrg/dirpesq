jQuery(document).ready(function($) {

// Cookies Acessibilidade

	function setCookie(c_name,value,exdays,path){
		var exdate=new Date();
		exdate.setDate(exdate.getDate() + exdays);
		var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString()+"; path="+path);
//        console.log(c_value);
		document.cookie=c_name + "=" + c_value;
	}

	function getCookie(c_name){
		var c_value = document.cookie;
		var c_start = c_value.indexOf(" " + c_name + "=");
		if (c_start == -1)
		  {
		  c_start = c_value.indexOf(c_name + "=");
		  }
		if (c_start == -1)
		  {
		  c_value = null;
		  }
		else
		  {
		  c_start = c_value.indexOf("=", c_start) + 1;
		  var c_end = c_value.indexOf(";", c_start);
		  if (c_end == -1)
		  {
		c_end = c_value.length;
		}
		c_value = unescape(c_value.substring(c_start,c_end));
		}
		return c_value;
	}

	function checkCookie(c_name){
		var c_name=getCookie(c_name);
		if (c_name!=null && c_name!=""){
			return true;
		}else{
			return false;
		}
	}

	if (checkCookie("contraste")){
		var contraste = getCookie("contraste");
		if (contraste == "true") {
			contraste = true;
			$("body").addClass("contraste");
		}
		else
			contraste = false;
	}
	else {
		setCookie("contraste",false,7,"/")
		var contraste = false;
	}


// Contraste liga/desliga

	$("#contraste").click(function(){
		contraste = !contraste;
		setCookie("contraste",contraste,7,"/");
		if (contraste == true)
			$("body").addClass("contraste");
		else
			$("body").removeClass("contraste");
	});


// Zoom

    var currZoom = 1;
    var step = 0.1;

    jQuery(".fonte_aum").click(function(){
        if (currZoom < 1.50) {
        	currZoom += step;
	        if (!(window.mozInnerScreenX == null)) {
	            jQuery('body').css('MozTransform','scale(' + currZoom + ','+ currZoom + ')');
	            jQuery('body').css('transform-origin','center 0');
	        }
	        else {
	            jQuery('body').css('zoom', ' ' + (currZoom*100) + '%');
	        }
	    }
    });

    jQuery(".fonte_dim").click(function(){
        if (currZoom > 0.80) {
        	currZoom -= step;
	        if (!(window.mozInnerScreenX == null)) {
	            jQuery('body').css('MozTransform','scale(' + currZoom + ','+ currZoom +')');
	            jQuery('body').css('transform-origin','center 0');
	        }
	        else {
	            jQuery('body').css('zoom', ' ' + (currZoom*100) + '%');
	        }
	    }
    });

    jQuery(".fonte_nor").click(function(){
        currZoom = 1;
        if (!(window.mozInnerScreenX == null)) {
            jQuery('body').css('MozTransform','scale(' + currZoom + ','+ currZoom + ')');
            jQuery('body').css('transform-origin','center 0');
        }
        else {
            jQuery('body').css('zoom', ' ' + (currZoom*100) + '%');
        }
    });

// Detecção de browser antigo

	if(!$.support.opacity) {	/* IE 6-8 */
		$('#avisobrowser').show().animate({bottom: '0'}, 'slow');
		$('#avisobrowser div').click(function(){
			$('#avisobrowser').fadeOut();
		});
	}


// Ajusta padding da imagem de capa no cabeçalho e rodapé

	if ($("#index").children("div").first().hasClass("moduloimgdest"))	// é o primeiro módulo da home?
		$("#index").children("div").first().css("padding-top","0");
	if (!$("#index").children("div").last().hasClass("moduloimgdest"))	// não é o último módulo da home?
		$("#index").children("div").last().css("padding-bottom","18px");


// Menus responsivos

	$('.ufpel-toggle').click( function(){
		$('#menu-menu-ufpel').toggle();

		if ( $('#menu-menu-ufpel').is(':visible') )
			$('.ufpel-toggle span').removeClass('dashicons-menu').addClass('dashicons-no');
		else
			$('.ufpel-toggle span').removeClass('dashicons-no').addClass('dashicons-menu');
	});

	$(".principal-toggle").click( function(){
		$("#menu_principal .menu").toggle();

		if ( $('#menu_principal .menu').is(':visible') )
			$('.principal-toggle span').removeClass('dashicons-menu').addClass('dashicons-no');
		else
			$('.principal-toggle span').removeClass('dashicons-no').addClass('dashicons-menu');
	});

	$(".sidebar-toggle").click( function(){
		if ( $('#sidebar').width() > 40 ) {
			$('#sidebar').animate({width: "40px"}, 'fast', function(){
				$('#sidebar > ul').hide();
			});
			$('.sidebar-toggle span').removeClass('dashicons-arrow-right-alt2').addClass('dashicons-arrow-left-alt2');
		}
		else {
			$('#sidebar > ul').show();
			$('#sidebar').animate({width: "252px"}, 'fast');
			$('.sidebar-toggle span').removeClass('dashicons-arrow-left-alt2').addClass('dashicons-arrow-right-alt2');
		}
	});


// Fixa menu do topo

	var altura = 0;
	if ( $('#barra-brasil').length ) {
		altura += $('#barra-brasil').outerHeight();
	}
	if ( $('#menu_ufpel').length ) {
		altura += $('#menu_ufpel').outerHeight();
	}
	if ( $('#header_content').length ) {
		altura += $('#header_content').outerHeight();
	}

	$(document).on('scroll', function () {
		if ( altura <= $(window).scrollTop() ) {
			$('#menu_principal').addClass('fixar');
		}
		else {
			$('#menu_principal').removeClass('fixar');
		}
	});

});
