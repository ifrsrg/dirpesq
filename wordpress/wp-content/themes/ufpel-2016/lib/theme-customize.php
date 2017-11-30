<?php
/*
	PERSONALIZAÇÃO - THEME CUSTOMIZE API
	https://codex.wordpress.org/Theme_Customization_API
*/

function inst_customize_register($wp_customize){

	global $inst_options;
//	$wp_customize->remove_section('static_front_page');  // Remove aba de página inicial estática

	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage'; // muda forma de transporte para atualizar título do site em tempo real
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	$wp_customize->add_section('inst_color_scheme', array(
		'title'    => 'Esquema de Cores',
		'priority' => 120,
	));

	$wp_customize->add_setting('ufpel_options[color_scheme]', array(
		'default'        => $inst_options["color_scheme"],
		'capability'     => 'edit_theme_options',
		'type'           => 'option',
		'transport' 	 => 'postMessage',
	));

	$wp_customize->add_setting( 'ufpel_options[und_vinc]', array(
		'default'        => $inst_options["und_vinc"],
		'capability'     => 'edit_theme_options',
		'type'           => 'option',
		'transport'      => 'postMessage',
		'sanitize_callback' => 'trim_text_field',
	));

// Controle personalizado para o color-picker

	class Custom_Colorpicker_Control extends WP_Customize_Control {
		public $type = 'colorpicker';

		public function render_content() {
			?>
			<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<input type="text" <?php $this->link(); ?> value="<?php echo esc_html( $this->value() ); ?>" class="wp-color-picker-field" data-default-color="<?php echo esc_html( $this->value() ); ?>" />
			</label>
			<?php
		}
	}

	$wp_customize->add_control( new Custom_Colorpicker_Control( $wp_customize, 'inst_color_scheme', array(
		'label'		=> 'Cor de destaque',
		'section'	=> 'inst_color_scheme',
		'settings'	=> 'ufpel_options[color_scheme]',
	) ) );


	$wp_customize->add_control( 'inst_und_vinc', array(
		'section'  => 'title_tagline',
		'settings' => 'ufpel_options[und_vinc]',
		'label'    => 'Unidade de origem',
		'type'     => 'text',
		'description' => 'Exibida acima do título do site',
		'priority' => 9,
	));

}

add_action('customize_register', 'inst_customize_register');


function trim_text_field($input) {
	return trim($input);
}


/**
 * Used by hook: 'customize_preview_init'
 *
 * @see add_action('customize_preview_init',$func)
 */

function ufpel_customizer_live_preview()
{
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker-script', get_template_directory_uri().'/js/colorpickerOptions.js', array( 'wp-color-picker' ), false, true );
	wp_enqueue_script( 'ufpel-themecustomizer', get_template_directory_uri().'/js/theme-customizer.js', array( 'jquery','customize-preview' ), false, true );
}
add_action( 'customize_preview_init', 'ufpel_customizer_live_preview' );

?>
