	<div class="limpa"></div>
		<footer>

<?php
	global $inst_options;

	if ( ! is_home() ) {

		global $modulos_footer;

		if ( sizeof( $modulos_footer ) )
			carregaModulos( implode( ',', $modulos_footer ) );
	}
?>

			<div class="wrapper">
				<div id="foot_content">
					<ul>
						<li>
							<h2 class="corTexto"><?php echo $inst_options['wpinst_localiza_titulo']; ?></h2>
							<div class="textwidget">

							<?php if ( current_user_can( 'edit_posts' ) && empty( $inst_options['wpinst_localiza_titulo'] ) && empty ( $inst_options['wpinst_localiza_campus'] ) && empty( $inst_options['wpinst_localiza_endereco'] ) ) : ?>

								<p>
									Personalize o conteúdo deste bloco em <b>Aparência - Opções do Tema</b>, na aba <b>Localização</b>.
								</p>
								<em>(Esta mensagem não é visível aos visitantes do site)</em>

							<?php else: ?>

								<p><b>
									<?php echo $inst_options['wpinst_localiza_campus']; ?>
								</b></p>

								<?php if ($inst_options['wpinst_localiza_linkgmaps'] != "") : ?>
									<a href="<?php echo $inst_options['wpinst_localiza_linkgmaps']; ?>" target="_blank" title="Localize no mapa">
										<?php echo wpautop( $inst_options['wpinst_localiza_endereco'] ); ?>
									</a>
								<?php else : ?>
									<?php echo wpautop( $inst_options['wpinst_localiza_endereco'] ); ?>
								<?php endif; ?>

								<p>
									<?php if ( $inst_options['wpinst_localiza_telefone'] ) echo $inst_options['wpinst_localiza_telefone'] . "<br>"; ?>
									<?php if ( $inst_options['wpinst_localiza_email'] ) echo '<a href="mailto:' . $inst_options['wpinst_localiza_email'] . '">' . $inst_options['wpinst_localiza_email'] . '</a>'; ?>
								</p>

							<?php endif; ?>
							</div>
						</li>
						<li>
							<?php if ( ( ! function_exists('dynamic_sidebar') || ! dynamic_sidebar('links2') ) && current_user_can( 'edit_posts' ) ) : ?>
								<p>
									<br>
									Personalize o conteúdo deste bloco em <b>Aparência - Widgets</b>.
								</p>
								<em>(Esta mensagem não é visível aos visitantes do site)</em>
							<?php endif; ?>
						</li>
						<li>
							<?php if ( ( ! function_exists('dynamic_sidebar') || ! dynamic_sidebar('links3') ) && current_user_can( 'edit_posts' ) ): ?>
								<p>
									<br>
									Personalize o conteúdo deste bloco em <b>Aparência - Widgets</b>.
								</p>
								<em>(Esta mensagem não é visível aos visitantes do site)</em>
							<?php endif; ?>
						</li>
					</ul>
					<div id="foot_content_end" class="limpa"></div>
				</div>
				<div id="creditos" class="corBorda">
					<div id="creditos_content">
						<div id="midiassociais">
							<ul>
								<li class="rss midiaicon" title="Feeds RSS">
									<a href="<?php bloginfo('rss2_url'); ?>">
										<span class="dashicons dashicons-rss corTexto"></span>
									</a>
								</li>
								<?php if ($inst_options['wpinst_gplus']): ?>
									<li class="gplus midiaicon" title="Google+">
										<a href="<?php echo $inst_options['wpinst_gplus']; ?>" target="_blank">
											<span class="dashicons dashicons-googleplus corTexto"></span>
										</a>
									</li>
								<?php endif; ?>

								<?php if ($inst_options['wpinst_facebook']): ?>
									<li class="facebook midiaicon" title="Facebook">
										<a href="<?php echo $inst_options['wpinst_facebook']; ?>" target="_blank">
											<span class="dashicons dashicons-facebook-alt corTexto"></span>
										</a>
									</li>
								<?php endif; ?>

								<?php if ($inst_options['wpinst_twitter']): ?>
									<li class="twitter midiaicon" title="Twitter">
										<a href="<?php echo $inst_options['wpinst_twitter']; ?>" target="_blank">
											<span class="dashicons dashicons-twitter corTexto"></span>
										</a>
									</li>
								<?php endif; ?>
							</ul>
						</div>
						<div id="copyright">&copy;<?php echo date("Y");?> <?php bloginfo('name'); ?>.</div>
						<div id="wp">Criado com <a href="http://wordpress.org" target="_blank">WordPress</a>. </div>
						<div id="equipe">Tema <a href="http://wp.ufpel.edu.br/sobre/tema-ufpel-2016" target="_blank">UFPel 2016</a> desenvolvido por <a href="http://wp.ufpel.edu.br/equipe" target="_blank">Equipe WP UFPel</a>.</div>
					</div>
				</div>
			</div>
		</footer>
		<div class="limpa"></div>
	</div>

<?php wp_footer(); ?>

</body>
</html>