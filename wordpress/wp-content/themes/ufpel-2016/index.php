<?php get_header();?>
<?php global $inst_options; ?>

<div id="index">

	<?php
		if ( ! empty( $inst_options["wpinst_modulos"] ) ) {

			carregaModulos($inst_options['wpinst_modulos']);

		}
	?>

</div>

<?php get_footer();?>