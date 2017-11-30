jQuery(function($){
	function performAutosave(){
		var panel = $(this).parents('div.ura-custom-options-panel');
		//var panel = $(this).parents('div.ura_users_admin-default-settings');
		
		var params = panel.find('input, checkbox, textarea').serialize();
		//var params = panel.find('checkbox').serialize();
		params = params + '&action=save_settings-' + panel.attr('id');
		$.post(
			'admin-ajax.php',
			params
		);
	}
	//$('#bulk-action-selector-top').attr('disabled', 'disabled');
	//$('#new_role').attr('disabled', 'disabled');
	//$('#bulk-action-selector-top').remove();
	$('#screen-options-wrap div.requires-autosave').find('input, checkbox, textarea').change(performAutosave);
	//$('#screen-options-wrap div.requires-autosave').find('checkbox').change(performAutosave);
});