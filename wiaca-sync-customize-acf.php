<?php

/**
 * That class permits to synchronize two images options :
 * An ACF on wich stores an image id and an option declared with the
 * WordPress Customize API.
 *
 * At save this class duplicate the value in the right format (id or url)
 *
 */
class wiaca_SyncAcfCustomizeImageOption {

	public $acf_option_name;
	public $customize_option_name;

	/**
	 * Constructor
	 * @param string $acf_option_name       : acf option name without the acf prefix
	 * @param string $customize_option_name : customize api option name
	 */
	function __construct( $acf_option_name, $customize_option_name ) {

		// ACF Activated ?
		if( !function_exists('get_field_object') ) {
			return;
		}

		// init vars
		$this->acf_option_name = $acf_option_name;
		$this->customize_option_name = $customize_option_name;

		// Get acf field details
		$this->acf_option = get_field_object( $this->acf_option_name, 'options' );

		// Exit if field doesn't exist
		if( empty( $this->acf_option ) ) {
			return;
		}

		// Action for synchronization
		add_action('updated_option', array($this, 'updateSyncOption'),20, 3);
	}

	/**
	 * Synchronize options when options updated
	 *
	 * @param  string $option	: option name
	 * @param  mixed $old_value : old value of the option
	 * @param  mixed $value     : new value of the option
	 * @return void
	 */
	function updateSyncOption( $option, $old_value, $value ) {

		// Update of one of the watch options ?
		if( !in_array( $option, array( 'options_' . $this->acf_option_name, $this->customize_option_name ) ) ) {
			return;
		}

		// No update, same values, to prevent code loop
		if( $old_value == $value ) {
			return;
		}

		// Acf options has been updated ?
		if( $option == 'options_' . $this->acf_option_name ) {
			// Synchronize option from customize API
			$this->updateWpOption( $value );

		// Customize API function has been updated ?
		} else if( $option == $this->customize_option_name ) {
			// Synchronize ACF option
			$this->updateAcfOption( $value );
		}
	}

	/**
	 * Update ACF option when WP option updated
	 *
	 * @param type $old_value
	 * @param type $value
	 * @return void
	 */
	function updateAcfOption( $value ) {
		update_field( $this->acf_option_name, $this->getAttachmentIdByUrl( $value ), 'options' );
	}

	/**
	 * Update WP option when acf option updated
	 *
	 * @param mixed $postId
	 * @return void
	 */
	function updateWpOption( $value ) {

		// Get new acf option value
		$url = wp_get_attachment_image_src( $value, 'full' );

		// Get wp option
		$optVal = get_option(  $this->customize_option_name );

		// Update
		if( $optVal !== false ) {
			// Update option
			update_option( $this->customize_option_name, $url[0] );

		// Create option value in db
		} else {
			// Add option
			add_option( $this->customize_option_name, $url[0] );
		}
	}

	/**
	 * Get image id by url image
	 *
	 * @param  string $url 	: image url
	 * @return int			: image id
	 */
	function getAttachmentIdByUrl( $url ) {
	    global $wpdb;
	    $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $url ));
	    return $attachment[0];
	}
}
