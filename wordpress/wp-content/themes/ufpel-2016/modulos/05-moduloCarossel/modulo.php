<?php

function moduloCarossel($id) {

	global $inst_options;
?>
	<div id="modulo-carossel<?php echo $id; ?>" class="wrapper modulos modulo-carrossel">
		<div class="carousel_banners">
			<div id="banners_content">
				<ul>
					<?php
					if ($inst_options['banners']) {

						$banners_inst_options = str_replace("undefined", "p", $inst_options['banners']);
						$banners_inst_options = explode(",", $banners_inst_options);
//						$banners_institucionais = carrega_banners();

						foreach($banners_inst_options as $key => $value) {
							if ( $value[0] == 'p' && has_post_thumbnail(substr($value, 1)) ) {
								echo '<li>';
								echo '<a href="'.get_post_meta(substr($value, 1), 'link', true).'" target="'.get_post_meta(substr($value, 1), 'destino', true).'">';
								echo get_the_post_thumbnail( substr($value, 1), 'full' );
								echo '</a>';
								echo '</li>';
							}
/*							else if ( $value[0] == 'i' ) {
								if ( ! empty( $banners_institucionais[substr($value, 1)] ) ) {
									$banners_institucional = $banners_institucionais[substr($value, 1)];

									<li>
										<a href="<?php echo $banners_institucional['link']; ?>" target="_blank">
											<img src="<?php echo $banners_institucional['image']; ?>" title="<?php echo $banners_institucional['title']; ?>">
										</a>
									</li>
								}
							}
*/
						}

					} // end if

					?>
				</ul>
			</div>
			<div id="carousel_banners_left"><a href="" class="prev-navigation"><img src="<?php bloginfo('template_url');?>/imagens/icones/prev1.png"></a></div>
			<div id="carousel_banners_right"><a href="" class="next-navigation"><img src="<?php bloginfo('template_url');?>/imagens/icones/next1.png"></a></div>
		</div>

		<script>
			jQuery(document).ready(function($) {
				$('#banners_content')
				.jcarousel({
					'wrap': 'circular'
				})
				.jcarouselSwipe();


				if($('#banners_content ul').children().size()>8){
					$('#banners_content').jcarouselAutoscroll({
						'interval': 1000,
					}).hover(function() {
						$(this).jcarouselAutoscroll('stop');
					}, function() {
						$(this).jcarouselAutoscroll('start');
					});
				}

				$("#banners_content li a").fadeTo('1', '0.65');

				$("#banners_content li a").hover(
					function() {
						$(this).fadeTo('0.8', '1');
					}, function() {
						$(this).fadeTo('0.8', '0.65');
					}
				);

				$('.next-navigation')
					.on('inactive.jcarouselcontrol', function() {
						$(this).addClass('inactive');
					})
					.on('active.jcarouselcontrol', function() {
						$(this).removeClass('inactive');
					})
					.jcarouselControl({
						target: '+=1'
					});

				$('.prev-navigation')
					.on('inactive.jcarouselcontrol', function() {
						$(this).addClass('inactive');
					})
					.on('active.jcarouselcontrol', function() {
						$(this).removeClass('inactive');
					})
					.jcarouselControl({
						target: '-=1'
					});
				});
		</script>


	</div>

<?php } ?>