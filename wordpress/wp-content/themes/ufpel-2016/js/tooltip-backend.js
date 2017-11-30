jQuery(document).ready(function($) {
	$(".tooltip").tooltip({
			show: null,
			open: function( event, ui ) {
					ui.tooltip.animate({ top: ui.tooltip.position().top + 10 }, "fast" );
			},
			content: function() {
					return $(this).prop('title');
			}
	});
});
