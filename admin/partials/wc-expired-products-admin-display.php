<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       nicogaldo.com.ar
 * @since      1.0.0
 *
 * @package    Wc_Expired_Products
 * @subpackage Wc_Expired_Products/admin/partials
 */
?>

<?php 
if (!current_user_can('manage_options'))  {
    wp_die( __('You do not have sufficient pilchards to access this page.') );
} ?>


<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">

    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
    
    <form method="post" name="wcep_options" action="options.php">

	    <?php
	        //Grab all options
	        $options = get_option($this->plugin_name);

	        // Cleanup
	        $expiretime		= $options['expiretime'];
	        $notifybefore	= $options['notifybefore'];
	        $notifynow		= $options['notifynow'];
	        $notifyafter	= $options['notifyafter'];
	        $deleteproduct	= $options['deleteproduct'];
	        $alsoimages		= $options['alsoimages'];
	    ?>

	    <?php
	        settings_fields($this->plugin_name);
	        do_settings_sections($this->plugin_name);
	    ?>

		<h2><?php esc_attr_e( 'Expire products', $this->plugin_name ); ?></h2>

		<fieldset>
			<label for="expiretime"><?php esc_attr_e( 'The products expire on:', $this->plugin_name ); ?></label>
			<input name="<?php echo $this->plugin_name; ?>[expiretime]" id="<?php echo $this->plugin_name; ?>-expiretime" type="number" min="1" max="999" value="<?php if(!empty($expiretime)) echo $expiretime; ?>" class="small-text" />
			<span class="description"><?php esc_attr_e( 'Only days in numbers: for a month, enter "30"', $this->plugin_name ); ?></span><br>
		</fieldset>

		<h2><?php esc_attr_e( 'Notifications', $this->plugin_name ); ?></h2>
		<small><?php esc_attr_e( 'All notifications are for the author of the product', $this->plugin_name ); ?></small>

		<fieldset>
			<legend class="screen-reader-text"><span><?php esc_attr_e('Notify previously.', $this->plugin_name ) ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>-notifybefore">
				<input name="<?php echo $this->plugin_name; ?>[notifybefore]" type="checkbox" id="<?php echo $this->plugin_name; ?>-notifybefore" value="1" <?php checked($notifybefore, 1); ?> />
				<span><?php esc_attr_e( 'Notify 5 days before the product expires.', $this->plugin_name ); ?></span>
			</label>
		</fieldset>

		<fieldset>
			<legend class="screen-reader-text"><span><?php esc_attr_e('Notify now', $this->plugin_name ) ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>-notifynow">
				<input name="<?php echo $this->plugin_name; ?>[notifynow]" type="checkbox" id="<?php echo $this->plugin_name; ?>-notifynow" value="1" <?php checked($notifynow, 1); ?> />
				<span><?php esc_attr_e( 'Notify when the product has expired.', $this->plugin_name ); ?></span>
			</label>
		</fieldset>

		<fieldset>
			<legend class="screen-reader-text"><span><?php esc_attr_e('Notify after', $this->plugin_name ) ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>-notifyafter">
				<input name="<?php echo $this->plugin_name; ?>[notifyafter]" type="checkbox" id="<?php echo $this->plugin_name; ?>-notifyafter" value="1" <?php checked($notifyafter, 1); ?> />
				<span><?php esc_attr_e( 'Remember (7 days later) that the product has expired.', $this->plugin_name ); ?></span>
			</label>
		</fieldset>

		<h2><?php esc_attr_e( 'Cleaning', $this->plugin_name ); ?></h2>
		<small><?php esc_attr_e( 'These options will keep your database cleaner', $this->plugin_name ); ?></small>

		<fieldset>
			<legend class="screen-reader-text"><span><?php esc_attr_e('Delete product', $this->plugin_name ) ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>-deleteproduct">
				<input name="<?php echo $this->plugin_name; ?>[deleteproduct]" type="checkbox" id="<?php echo $this->plugin_name; ?>-deleteproduct" value="1" <?php checked($deleteproduct, 1); ?> />
				<span><?php esc_attr_e( 'Delete the product if it has not been updated 14 days after it has expired.', $this->plugin_name ); ?></span>
			</label>
			<fieldset id="alsoimages">
                <legend class="screen-reader-text"><span><?php esc_attr_e('Delete product images', $this->plugin_name ) ?></span></legend>
				<label for="<?php echo $this->plugin_name; ?>-alsoimages">
					<input name="<?php echo $this->plugin_name; ?>[alsoimages]" type="checkbox" id="<?php echo $this->plugin_name; ?>-alsoimages" value="1" <?php checked($alsoimages, 1); ?> />
					<span><?php esc_attr_e( 'Also delete images?', $this->plugin_name ); ?></span>
				</label>
            </fieldset>
		</fieldset>
		
		<?php submit_button( __('Save changes', $this->plugin_name ), 'primary','submit', TRUE); ?>
	</form>

	<hr>

	<h2><?php esc_attr_e( 'It is my first installation of the plugin.', $this->plugin_name ); ?></h2>

	<div id="col-left">
    
	    <form method="post" name="wcep_resetallproducts" action="admin.php?page=<?php echo $this->plugin_name ?>">

	    	<?php settings_fields($this->plugin_name); ?>

	    	<p><?php _e('If you just install this plugin there\'s something you should know. Here we are playing with <code>post_modified</code> value of the database. The plugin see the difference between the date of moficiacion the product and the today\'s date, if that difference is equal to the value you entered above, the corresponding function is executed.', $this->plugin_name) ?></p>

			<p><?php esc_attr_e('What happens if I have products created. They change all to draft status? Yes.', $this->plugin_name ) ?></p>
			<p><?php esc_attr_e('To avoid this we should change the date of modification of all products to today\'s date. This task can make you, simply by clicking the button below :).', $this->plugin_name ) ?></p>

			<?php wp_nonce_field('iwanttoresetallproducts'); ?>
			<input type="hidden" value="true" name="resetallproducts" />
			<?php submit_button( __('Configure all products', $this->plugin_name ), 'primary', 'submit_resetallproducts', TRUE); ?>

	    </form>

    </div>

	<script type="text/javascript">
		jQuery(function () {
			//jQuery('#alsoimages').hide();

			if (jQuery('#<?php echo $this->plugin_name; ?>-deleteproduct').prop('checked')) {
				jQuery('#alsoimages').show();
			} else {
				jQuery('#alsoimages').hide();
			}	

			//show it when the checkbox is clicked
			jQuery('#<?php echo $this->plugin_name; ?>-deleteproduct').on('click', function () {
				if (jQuery(this).prop('checked')) {
					jQuery('#alsoimages').fadeIn();
				} else {
					jQuery('#alsoimages').hide();
					jQuery('#<?php echo $this->plugin_name; ?>-alsoimages').prop('checked',false);
				}
		  	});
		});
	</script>

</div>