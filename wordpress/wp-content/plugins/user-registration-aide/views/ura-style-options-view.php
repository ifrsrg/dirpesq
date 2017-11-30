<?php

/**
 * Class  URA_STYLE_OPTIONS_VIEW
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_STYLE_OPTIONS_VIEW
{

		
	
	/**	
	 * function custom_style_view
	 * Handles Display settings and options for plugin style settings and allows user to change selected style settings
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @params string $msg, string $nonce, $string nonce1
	 * @returns string $msg
	 * @access public
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
		
	function custom_style_view(){
		global $current_user;
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){
			wp_die( __( 'You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			$options = get_option('csds_userRegAide_Options');
			$border_styles = array();
			$collapsed = array();
			$span = array( 'regForm', __( 'Choose Custom URA Style Options Here:', 'user-registration-aide' ), 'user-registration-aide' );
			do_action( 'start_mini_wrap',  $span );
			?>
			<table class="style">
				<tr>
					<th colspan="4" class="style">
					<?php
					echo __( 'Choose Custom CSS Table Settings Here: ', 'user-registration-aide' );
					?>
					</th>
				</tr>
				<tr>
					<td class="style">
					<?php
					//add_filter( 'ura_styles_border_array', array( &$styles_model, 'ura_border_style_array' ) );
					//add_filter( 'ura_styles_collapse_array', array( &$styles_model, 'ura_border_collapse_array' ) );
					//$border_styles = $this->ura_border_style_array();
					//$collapsed = $this->ura_border_collapse_array();
					$border_styles = apply_filters( 'ura_styles_border_array', $border_styles );
					$collapsed = apply_filters( 'ura_styles_collapse_array', $collapsed );
					$border_color = $options['border-color'];
					$collapse = $options['border-collapse'];
					$bckgrd_color = $options['tbl_background_color'];
					$tbl_color = $options['tbl_color'];
					$border_width = $options['tbl_border-width'];
					$border_style = $options['border-style'];
					$border_spacing = $options['border-spacing'];
					$div_color = $options['div_stuffbox_bckgrd_color'];
					$padding = $options['tbl_padding'];
					?>
					<label for="ura_tbl_border_style"> <?php _e( 'Select Table Border Style Here: ', 'user-registration-aide' ); ?> </label>
					<select name="ura_tbl_border_style" id="ura_tbl_border_style" title=" <?php _e('Select Table Border Style Here', 'user-registration-aide'); ?>" size="8" multiple style="height:50px">
					<?php
					foreach( $border_styles as $style	=> $desc ){ 
						if( $style == $border_style){
							$selected = "selected=\"selected\"";
						}else{
							$selected = NULL;
						}
						echo "<option title=\"$desc\" value=\"$style\" $selected >$style</option>";
					}
					?>
					</select>
					</td>
					<td class="style">
					<label for="ura_tbl_border_collapse"> <?php _e( 'Select Table Border Collapse Style Here: ', 'user-registration-aide' ); ?> </label>
					<select name="ura_tbl_border_collapse" id="ura_tbl_border_collapse" title=" <?php _e('Select Table Border Collapse Style Here', 'user-registration-aide'); ?>" size="8" multiple style="height:50px">
					<?php
					foreach( $collapsed as $ckey	=>	$cdesc ){
						if( $ckey == $collapse ){
							$selected = "selected=\"selected\"";
						}else{
							$selected = NULL;
						}
						echo "<option title=\"$cdesc\" value=\"$ckey\" $selected >$ckey</option>";
					}
					?>
					</select>
					</td>
					<td class="style">
					<label for="ura_tbl_border_width"> <?php _e( 'Select Table Border Width Here: ', 'user-registration-aide' ); ?> </label>
					<select name="ura_tbl_border_width" id="ura_tbl_border_width" title=" <?php _e( 'Select Table Border Width Here', 'user-registration-aide' ); ?>" size="8" multiple style="height:50px">
					<?php 
					for( $wcnt = 1; $wcnt <= 10; $wcnt += 1){
						if( $wcnt == $border_width){
							$selected = "selected=\"selected\"";
						}else{
							$selected = NULL;
						}
						$wcnt = $wcnt.'px';
						echo "<option title=\"$wcnt\" value=\"$wcnt\" $selected >$wcnt</option>";
					}
					?>
					</select>
					</td>
					<td class="style">
					<label for="ura_tbl_padding"> <?php _e( 'Select Table Padding Here: ', 'user-registration-aide' ); ?> </label>
					<select name="ura_tbl_padding" id="ura_tbl_padding" title=" <?php _e( 'Select Table Padding Here', 'user-registration-aide' ); ?>" size="8" multiple style="height:50px">
					<?php 
					for( $wcnt = 1; $wcnt <= 10; $wcnt += 1){
						if( $wcnt == $padding){
							$selected = "selected=\"selected\"";
						}else{
							$selected = NULL;
						}
						$wcnt = $wcnt.'px';
						echo "<option title=\"$wcnt\" value=\"$wcnt\" $selected >$wcnt</option>";
					}
					?>
					</select>
					</td>
				</tr>
				<tr>
					<td class="style">
					
					<script type='text/javascript'>
						jQuery(document).ready(function($) {
							$('#ura_border_color_picker').wpColorPicker();
						});
					</script>
					
					<label for="ura_border_color_picker"> <?php _e( 'Select Table Border Color Here: ', 'user-registration-aide' ); ?> </label>
					<br/>
					<input type="text" id="ura_border_color_picker" name="ura_border_color_picker" value=" <?php echo $border_color; ?> " />
					</td>	
					<td class="style">
					
					<script type='text/javascript'>
						jQuery(document).ready(function($) {
							$('#ura_tbl_bckgrd_color_picker').wpColorPicker();
						});
					</script>
					
					<label for="ura_tbl_bckgrd_color_picker"> <?php _e( 'Select Table Background Color Here: ', 'user-registration-aide' ); ?> </label>
					<br/>
					<input type="text" id="ura_tbl_bckgrd_color_picker" name="ura_tbl_bckgrd_color_picker" value=" <?php echo $bckgrd_color; ?> " />
					</td>
					<td class="style">
					
					<script type='text/javascript'>
						jQuery(document).ready(function($) {
							$('#ura_tbl_color_picker').wpColorPicker();
						});
					</script>
					
					<label for="ura_tbl_color_picker"> <?php _e( 'Select Table Text Color Here: ', 'user-registration-aide' ); ?> </label>
					<br/>
					<input type="text" id="ura_tbl_color_picker" name="ura_tbl_color_picker" value=" <?php echo $tbl_color; ?> " />
					</td>
					<td class="style">
					
					<script type='text/javascript'>
						jQuery(document).ready(function($) {
							$('#ura_div_color_picker').wpColorPicker();
						});
					</script>
					
					<label for="ura_div_color_picker"> <?php _e( 'Select Outside Table Background Color Here: ', 'user-registration-aide' ); ?> </label>
					<br/>
					<input type="text" id="ura_div_color_picker" name="ura_div_color_picker" value=" <?php echo $div_color; ?> " />
					</td>
				</tr>
				<tr>
					<td class="style">
					<label for="ura_tbl_border_spacing"> <?php _e( 'Select Table Border Spacing Here: ', 'user-registration-aide' ); ?> </label>
					<select name="ura_tbl_border_spacing" id="ura_tbl_border_spacing" title=" <?php _e( 'Select Table Border Spacing Here', 'user-registration-aide' ); ?>" size="8" multiple style="height:50px">
					<?php 
					for( $spcnt = 1; $spcnt <= 10; $spcnt += 1){
						if( $spcnt == $border_spacing){
							$selected = "selected=\"selected\"";
						}else{
							$selected = NULL;
						}
						$spcnt = $spcnt.'px';
						echo "<option title=\"$spcnt\" value=\"$spcnt\" $selected >$spcnt</option>";
					}
					?>
					</select>
					</td>
					
					<td colspan="3" class="style">
						<input type="submit" class="button-primary" name="ura_update_style" value="<?php _e('Update Plugin Table Style Settings', 'user-registration-aide');?>"/>
					</td>
				</tr>
			</table>
			<?php
		}
	}
}
?>