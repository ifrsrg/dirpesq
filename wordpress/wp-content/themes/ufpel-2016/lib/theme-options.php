<?php
/**
 * WTester Tabbed Settings Page
 */

add_action( 'admin_enqueue_scripts', 'wp_enqueue_color_picker' );
function wp_enqueue_color_picker( ) {
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker-script', get_template_directory_uri().'/js/colorpickerOptions.js', array( 'wp-color-picker' ), false, true );
}


// Adiciona opção no menu Aparência

add_action( 'admin_menu', 'wpinst_settings_page_init' );

function wpinst_settings_page_init() {
	//$theme_data = get_theme_data( TEMPLATEPATH . '/style.css' );
	$theme_data = wp_get_theme();

	$settings_page = add_theme_page( 'Opções do Tema', 'Opções do Tema', 'edit_theme_options', 'theme-settings', 'wpinst_settings_page' );
	add_action( "load-{$settings_page}", 'wpinst_load_settings_page' );
}


// Adiciona opção na barra de menu no topo do site

add_action('admin_bar_menu', 'add_items_admin_bar_menu', 999);

function add_items_admin_bar_menu($admin_bar)
{

	$args = array(
		'id'    => 'opcoes-tema',
		'parent'=> 'appearance',
		'title' => 'Opções do Tema UFPel 2016',
		'href'  => admin_url().'themes.php?page=theme-settings',
		'meta'	=> array(
						'title' => "Configure o layout da página inicial e outras opções de personalização",
				),
	);

	$admin_bar->add_menu( $args );
}


function wpinst_load_settings_page() {
	if ( isset( $_POST["wpinst-settings-submit"] ) && $_POST["wpinst-settings-submit"] == 'Y' ) {
		check_admin_referer( "wpinst-settings-page" );
		wpinst_save_theme_settings();
		$url_parameters = isset($_GET['tab'])? 'updated=true&tab='.$_GET['tab'] : 'updated=true';
		wp_redirect(admin_url('themes.php?page=theme-settings&'.$url_parameters));
		exit;
	}
}

function wpinst_save_theme_settings() {
	global $pagenow;

	$inst_options = get_option('ufpel_options');

	if ( $pagenow == 'themes.php' && $_GET['page'] == 'theme-settings' ){
		if ( isset ( $_GET['tab'] ) )
			$tab = $_GET['tab'];
		else
			$tab = 'layout';

		switch ( $tab ){
			case 'redessoc' :
				$inst_options['wpinst_facebook']  = $_POST['wpinst_facebook'];
				$inst_options['wpinst_twitter']	  = $_POST['wpinst_twitter'];
				$inst_options['wpinst_gplus']	  = $_POST['wpinst_gplus'];
				$inst_options['wpinst_ogimg']	  = $_POST['wpinst_ogimg'];
				$inst_options['wpinst_ogdesc']	  = $_POST['wpinst_ogdesc'];
			break;
			case 'personalizar' :
//				if ( $_POST['limpa_config'] == 'S' )
//					$inst_options = array();	// apaga todas as opções -- desativado por segurança
//				else {
					$inst_options['wpinst_barra']  = $_POST['wpinst_barra'];
					$inst_options['color_scheme']  = $_POST['color_scheme'];
					$inst_options['wpinst_style']  = $_POST['wpinst_style'];
					$inst_options['und_vinc']  = trim($_POST['und_vinc']);
//				}
			break;
			case 'layout' :
				$inst_options['wpinst_modulos']	  = $_POST['wpinst_modulos'];
				$inst_options['wpinst_widgets']	  = $_POST['wpinst_widgets'];

/*
				$inst_options['imagemdest'] = "";	// limpa configuração da imagem destacada e carrossel nas páginas internas,
				$inst_options['carrossel'] = "";	// para o caso dos módulos terem sido excluídos do layout.
				foreach(explode(",", $inst_options['wpinst_modulos']) as $modulo) {
					$info = explode("-", $modulo);
					$opcoes = explode(":",$info[2]);
					if ($info[0] == "Imagemdest") {
						if ($opcoes[5] != "" && $opcoes[5] != "n")	// se foi configurado para exibir nas páginas internas
							$inst_options['imagemdest'] = $modulo;	// salva string do módulo na opção específica
					}
					elseif ($info[0] == "Carossel") {
						if ($opcoes[0] != "" && $opcoes[0] != "n")
							$inst_options['carrossel'] = $modulo;
					}
				}
*/
			break;
			case 'banners' :
				$inst_options['banners'] = $_POST['banners'];
			break;
			case 'localiza' :
				$inst_options['wpinst_localiza_titulo'] = $_POST['wpinst_localiza_titulo'];
				$inst_options['wpinst_localiza_campus'] = $_POST['wpinst_localiza_campus'];
				$inst_options['wpinst_localiza_linkgmaps'] = $_POST['wpinst_localiza_linkgmaps'];
				$inst_options['wpinst_localiza_endereco'] = $_POST['wpinst_localiza_endereco'];
				$inst_options['wpinst_localiza_telefone'] = $_POST['wpinst_localiza_telefone'];
				$inst_options['wpinst_localiza_email'] = $_POST['wpinst_localiza_email'];
			break;
		}
	}

	/*if( !current_user_can( 'unfiltered_html' ) ){
		if ( $settings['wpinst_ga']  )
			$settings['wpinst_ga'] = stripslashes( esc_textarea( wp_filter_post_kses( $settings['wpinst_ga'] ) ) );
		if ( $settings['wpinst_style'] )
			$settings['wpinst_style'] = stripslashes( esc_textarea( wp_filter_post_kses( $settings['wpinst_style'] ) ) );
	}*/

	$updated = update_option( 'ufpel_options', $inst_options );
}

function wpinst_admin_tabs( $current = 'layout' ) {
	$tabs = array( 'layout' => 'Layout', 'localiza' => 'Localização', 'redessoc' => 'Redes Sociais', 'banners' => 'Banners', 'personalizar' => 'Personalizar' );
	$links = array();
	echo '<div id="icon-themes" class="icon32"><br></div>';
	echo '<h2 class="nav-tab-wrapper">';
	foreach( $tabs as $tab => $name ){
		$class = ( $tab == $current ) ? ' nav-tab-active' : '';
		echo "<a class='nav-tab$class' href='?page=theme-settings&tab=$tab'>$name</a>";

	}
	echo '</h2>';
}

function wpinst_settings_page() {
	global $pagenow, $inst_options, $defaults;

	//$theme_data = get_theme_data( TEMPLATEPATH . '/style.css' );
	$theme_data = wp_get_theme();

	?>

	<div class="wrap">
		<h2>Opções do tema <?php echo $theme_data->get('Name'); ?></h2>

		<?php
			if ( isset ( $_GET['updated'] ) && 'true' == esc_attr( $_GET['updated'] ) ) echo '<div class="updated" ><p>Opções do tema atualizadas.</p></div>';

			if ( isset ( $_GET['tab'] ) ) wpinst_admin_tabs($_GET['tab']); else wpinst_admin_tabs('layout');
		?>

		<div id="poststuff">
			<form method="post" id="wpinst-options-form" action="<?php admin_url( 'themes.php?page=theme-settings' ); ?>">
				<input type="hidden" name="wpinst-settings-submit" value="Y" />
				<?php
				wp_nonce_field( "wpinst-settings-page" );

				if ( $pagenow == 'themes.php' && $_GET['page'] == 'theme-settings' ){

					if ( isset ( $_GET['tab'] ) ) $tab = $_GET['tab'];
					else $tab = 'layout';

					echo '<table class="form-table">';
					switch ( $tab ){
						case 'redessoc' :
							?>
							<tr>
								<th style="width:255px"><label for="wpinst_facebook">Link para a página do Facebook:</label></th>
								<td>
									<input type="text" name="wpinst_facebook" value="<?php echo $inst_options['wpinst_facebook']; ?>" style="width:400px">
								</td>
							</tr>
							<tr>
								<th style="width:255px"><label for="wpinst_twitter">Link para a página do Twitter:</label></th>
								<td>
									<input type="text" name="wpinst_twitter" value="<?php echo $inst_options['wpinst_twitter']; ?>" style="width:400px">
								</td>
							</tr>
							<tr>
								<th style="width:255px"><label for="wpinst_gplus">Link para a página do Google+:</label></th>
								<td>
									<input type="text" name="wpinst_gplus" value="<?php echo $inst_options['wpinst_gplus']; ?>" style="width:400px">
								</td>
							</tr>
							<tr>
								<th style="width:255px"><label for="wpinst_ogimg">Imagem padrão para compartilhamento no Facebook:</label></th>
								<td>
									<input type="text" name="wpinst_ogimg" value="<?php echo $inst_options['wpinst_ogimg']; ?>" style="width:400px"><br />
									<span class="description">
										Utilizada caso o post ou página compartilhados não contenham nenhuma imagem.<br />
										Faça upload da imagem na biblioteca de mídia e informe o URL (iniciando por http://) no campo acima.<br />
										O tamanho mínimo exigido pelo Facebook é 200x200 pixels.</span><br /><br />
									 <span class="description">Pré-visualização:</span><br /><br />
									 <img src="<?php echo $inst_options['wpinst_ogimg']; ?>" height="200" />
								</td>
							</tr>
							<tr>
								<th style="width:255px"><label for="wpinst_ogdesc">Descrição para compartilhamento no Facebook:</label></th>
								<td>
									<textarea id="wpinst_ogdesc" name="wpinst_ogdesc" style="width:600px;height:250px"><?php echo $inst_options["wpinst_ogdesc"]; ?></textarea><br />
									<span class="description">Utilizada para compartilhamento da página inicial do site e de outras páginas gerais, como arquivos e resultados de buscas.</span>
								</td>
							</tr>
							<?php
						break;
						case 'personalizar' :
							?>
							<tr>
								<th><label for="und_vinc">Unidade de origem:</label></th>
								<td>
									<input type="text" name="und_vinc" value="<?php echo $inst_options['und_vinc']; ?>" style="width:400px"><br />
									<span class="description">Se especificado, o nome da unidade será exibido no cabeçalho, acima do título do site.</span>
								</td>
							</tr>
							<tr>
								<th><label for="wpinst_barra">Exibir barra do Governo:</label></th>
								<td>
									<input type="checkbox" name="wpinst_barra" id="wpinst_barra" value="sim" <?php if ($inst_options['wpinst_barra'] == "sim") echo "checked"; ?>> Marque para exibir a barra de Identidade Visual do Governo Federal no topo do site<br>
								</td>
							</tr>
							<tr>
								<th><label for="color_scheme">Esquema de cores:</label></th>
								<td>
									<input name="color_scheme" id="color_scheme" type="text" value="<?php echo $inst_options['color_scheme']; ?>" class="wp-color-picker-field" data-default-color="<?php echo $inst_options['color_scheme']; ?>" />
									<div id="boxpreview" style="color:#fff; margin-top:20px">
										<div class="corFundo" style="width:180px; padding:4px; color:#fff; float:left; background-color:<?php echo $inst_options['color_scheme']; ?>;"><strong>Exemplo</strong></div>
										<div class="corTexto" style="width:180px; padding:4px; background-color:#666; float:left; color:<?php echo $inst_options['color_scheme']; ?>;"><strong>Exemplo</strong></div>
									</div>
									<div style="clear:both"></div>
									<span class="description">Você pode testar as cores no site em tempo real, através do menu <a href="customize.php">Aparência - Personalizar</a></span>
								</td>
							</tr>
							<tr>
								<th><label for="wpinst_style">CSS Personalizado:</label></th>
								<td>
									<textarea id="wpinst_style" name="wpinst_style" style="width:600px;height:250px"><?php echo $inst_options["wpinst_style"]; ?></textarea><br/>
									<span class="description">Você pode inserir seus próprios códigos CSS acima para modificar quaisquer outros aspectos da aparência do tema que não estejam incluídos nas opções.</span>
								</td>
							</tr>
							<?php
						break;
						case 'layout' :
							$dir = dirname(__FILE__).'/../modulos';
							$files1 = scandir($dir);

							foreach ($files1 as $value) {
								if ( is_dir("$dir/$value") && $value != '.' && $value != '..' )
									$modulos[]="$dir/$value";
							}
							?>

							<p class="submit" style="clear: both;">
								<input type="submit" id="bt_submit1" name="Submit"  class="button-primary" value="Salvar Opções" />
								<span class="themeoptions-modified">(Existem modificações não salvas)</span>
							</p>

							<ul id="modulos">
															<?php
															foreach($modulos as $item){
																$dirmodulo = substr($item, strrpos($item,"/"));
																$modulourl = get_bloginfo('template_url').'/modulos'.$dirmodulo;
																include "$item/blocolayout.php";
															}
															?>
														</ul>

							<div id="arrowmod"></div>

							<ul id="modulosList" class="mlempty">
							</ul>

							<?php
														foreach($modulos as $item){
															include "$item/blococonfig.php";
														}

														foreach($modulos as $item){
															$dirmodulo = substr($item, strrpos($item,"/"));
															$modulourl = get_bloginfo('template_url').'/modulos'.$dirmodulo;
															include "$item/blocohelp.php";
														}
							?>

														<div id="fundo_modal2"></div>

							<input id="wpinst_modulos" name="wpinst_modulos" type="hidden" value="<?php echo $inst_options["wpinst_modulos"]; ?>" />
							<input id="wpinst_widgets" name="wpinst_widgets" type="hidden" value="<?php echo $inst_options["wpinst_widgets"]; ?>" />

							<?php
						break;
						case 'banners' :
													?>
													<div id="bannerseditor" style="padding:20px 5px 0px 20px; height: 575px;">
														<span class="dashicons dashicons-info"></span> Para exibir os banners em seu site, adicione o módulo <strong>Carrossel</strong> ao layout do site, na <a href="themes.php?page=theme-settings&tab=layout">aba Layout</a>.<br />
														<input type="submit" id="bt_submit1" name="Submit"  class="button-primary" value="Salvar Opções" />
														<div style="display: inline;">
														<div id="modulo-carossel<?php //echo $id; ?>" class="wrapper">
															<div class="carousel_banners">
																<div id="banners_content">
																	<ul id="modulosList2">
																	<?php
																		if ( isset( $inst_options['banners'] ) ) {
																			$banners_inst_options = str_replace("undefined", "p", $inst_options['banners']);
																			$banners_inst_options = explode(",", $banners_inst_options);
																			//$banners_institucionais = carrega_banners();

																			foreach($banners_inst_options as $key => $value) {
																				if (substr($value, 0, 1) == "p") {
																					echo '<li data-id="'.substr($value, 1).'" data-source="p">';
																					echo get_the_post_thumbnail(substr($value, 1), 'full', array('class' => 'banners_proprios eflex itiltnone iheight70 iopacity60', 'height' => '66px', 'width' => '96px'));
																					echo "</li>";
																				}
/*
																				else if ( substr( $value, 0, 1 ) == "i" && ! empty( $banners_institucionais[substr($value, 1)] ) ) {
																					echo '<li data-id="'.substr($value, 1).'" data-source="i">';
																					$banners_institucional = $banners_institucionais[substr($value, 1)];
																					?>
																					<img src="<?php echo $banners_institucional['image']; ?>" title="<?php echo $banners_institucional['title']; ?>" width="96" height="66" />
																					<?php
																					echo "</li>";
																			  	}
*/
																			}
																		}
																	?>
																	</ul>
																</div>
																<div id="carousel_banners_left"><a href="#" class="prev-navigation"><img class="reflex itiltnone iheight70 iopacity60" src="<?php bloginfo('template_url');?>/imagens/icones/prev1.png"></a></div>
																<div id="carousel_banners_right"><a href="#" class="next-navigation"><img class="reflex itiltnone iheight70 iopacity60" src="<?php bloginfo('template_url');?>/imagens/icones/next1.png"></a></div>
															</div>
														</div>
												   </div>
												   <div style="width: 50%; margin: 10px 0; text-align: center; font-size: 13.3; color: #520e0e;"><img src="<?php bloginfo('template_url');?>/imagens/seta.png" style="width: 10px; height: 10px" /> Arraste os banners desejados para a área acima, para adicioná-los ao site. Para remover um banner, arraste-o para fora desta área. <img src="<?php bloginfo('template_url');?>/imagens/seta.png" style="width: 10px; height: 10px" /></b></div>
												   <div style="width: 100%; height: 800px;">
														<div id="banners_institucionais" style="display:none; /*inline*/ overflow-y: scroll; box-sizing: border-box; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box; -o-box-sizing: border-box; text-align: center; text-align: center; width: 50%; height: 450px; border: 10px solid #d5d5d5; float: left; padding: 5px 0px 5px 5px">
															<b>Banners Institucionais</b>
															<br />
															<?php
/*
															$banners = carrega_banners();
															echo '<ul id="modulos2">';
																foreach ($banners as $bannerID => $bannerinfo) {
																?>
																<li data-id="<?php echo $bannerID; ?>" data-source="i">
																	<a href="<?php echo $bannerinfo['link']; ?>" target="_blank"><img src="<?php echo $bannerinfo['image']; ?>" title="<?php echo $bannerinfo['title']; ?>" width="96" height="66"></a>
																</li>
																<?php
																}
															echo '</ul>';
*/
															?>
														</div>
														<div id="banners_proprios" style="/*border-left: 0px;*/ overflow-y: scroll; box-sizing: border-box; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box; -o-box-sizing: border-box; text-align: center; display: inline; text-align: center; width: 50%; height: 450px; border: 10px solid #d5d5d5; list-style-type: none; float: left; padding: 5px 0px 5px 5px">
														 <b>Banners (<a href="post-new.php?post_type=banners" target="_blank">Adicionar novo</a>)</b>
															<?php
															query_posts('showposts=30&post_type=banners');
															if (have_posts())
																echo '<ul id="modulos2">';
																	while (have_posts()) {
																		the_post();
																		?>
																		<li data-id="<?php echo get_the_ID(); ?>" data-source="p">
																			<a href="<?php echo get_post_meta( get_the_ID(), 'link', true); ?>" target="_blank">
																				<?php echo the_post_thumbnail('full', array('class' => 'banners_proprios reflex itiltnone iheight70 iopacity60', 'height' => '66px', 'width' => '96px')); ?>
																			</a>
																		</li>
																		<?php
																	}
																echo '</ul>';
															wp_reset_query(); ?>
														</div>
													</div>
													<input id="banners" name="banners" type="hidden" value="<?php if ( isset( $inst_options["banners"] ) ) echo $inst_options["banners"]; ?>" />
												</div>
												<?php
						break;
						case 'localiza' :
							?>
							<tr>
								<th style="width:50px"><label for="wpinst_localiza_titulo">Título:</label></th>
								<td>
									<input type="text" name="wpinst_localiza_titulo" value="<?php echo $inst_options['wpinst_localiza_titulo']; ?>" style="width:400px">
								</td>
							</tr>
							<tr>
								<th style="width:50px; vertical-align:middle"><label for="wpinst_localiza_campus">Setor:</label></th>
								<td>
									<input type="text" name="wpinst_localiza_campus" value="<?php echo $inst_options['wpinst_localiza_campus']; ?>" style="width:400px">
								</td>
							</tr>
							<tr>
								<th style="width:50px"><label for="wpinst_localiza_telefone">Telefone:</label></th>
								<td>
									<input type="text" name="wpinst_localiza_telefone" value="<?php echo $inst_options['wpinst_localiza_telefone']; ?>" style="width:400px">
								</td>
								<th style="width:50px"><label for="wpinst_localiza_email">Email:</label></th>
								<td>
									<input type="text" name="wpinst_localiza_email" value="<?php echo $inst_options['wpinst_localiza_email']; ?>" style="width:400px">
								</td>
							</tr>
							<tr>
								<th style="width:50px"><label for="wpinst_localiza_endereco">Endereço:</label></th>
								<td>
									<textarea id="wpinst_style" name="wpinst_localiza_endereco" style="width:400px;height:250px"><?php echo $inst_options["wpinst_localiza_endereco"]; ?></textarea><br/>
								</td>
								<th style="width:100px"><label for="wpinst_localiza_linkgmaps">Link para Mapa:</label></th>
								<td style="vertical-align:top;">
									<input type="text" name="wpinst_localiza_linkgmaps" value="<?php echo $inst_options['wpinst_localiza_linkgmaps']; ?>" style="width:400px">
								</td>
							</tr>
							<?php
						break;
					}
					echo '</table>';
				}
				?>
				<p class="submit" style="clear: both;">
					<input type="submit" id="bt_submit2" name="Submit"  class="button-primary" value="Salvar Opções" />
					<span class="themeoptions-modified">(Existem modificações não salvas)</span>
				</p>
				<?php /* if ( $tab == 'personalizar' ): // APAGAR OPÇÕES -- Desativado por segurança

				<p>&nbsp;</p>
				<p class="submit">
					<input type="hidden" id="limpa_config" name="limpa_config" value="">
					<input type="button" id="bt_limpa" class="button-primary" value="APAGAR TODAS AS CONFIGURAÇÕES" onclick="if (confirm('TODAS AS OPÇÕES DO TEMA SERÃO APAGADAS!\n\nEsta operação é irreversível. Deseja continuar?')) { document.getElementById('limpa_config').value='S'; document.getElementById('wpinst-options-form').submit(); } else return false;"><br>
					<span class="description">ATENÇÃO! Esta opção apagará todas as personalizações do tema. Utilize apenas caso algum erro nas configurações esteja impedindo o funcionamento do tema.</span>
				</p>

				 endif; */ ?>
			</form>
		</div>
	</div>
<?php
}