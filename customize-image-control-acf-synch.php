<?php

/**
 * This class extends WP_Customize_Image_Control and just add some features :
 *
 * If an "acf_option" args is passed, with the key of an existing ACF options image,
 * it permit to inherit Label and descriptuion args values
 *
 *
 */
class Customize_Image_Control_Acf_Sync extends WP_Customize_Image_Control {

	public $aica_args = array();
	public $aica_id;

	public $acf_option;


	function __construct( $manager, $id, $args = array() ) {

		$this->aica_args = $args;
		$this->aica_id = $id;

		// Acf exists and acf option name given ?
		if( function_exists('get_field_object') && isset( $this->aica_args['acf_option'] ) ) {
			// Get params from Wordpress
			$this->inheritAcfConfig();
		}

		// Continue standard image customize API
		parent::__construct( $manager, $this->aica_id, $this->aica_args );

	}

	/**
	 * Get parameters from ACF option
	 *
	 * @return void
	 */
	public function inheritAcfConfig() {

		// Get acf field details
		$this->acf_option = get_field_object( $this->aica_args['acf_option'], 'options' );

		// Exit if field doesn't exist or isn't an image
		if( empty( $this->acf_option ) || $this->acf_option['type'] !== 'image' ) {
			return;
		}

		// Args from ACF
		$new_args = array(
			'label' => $this->acf_option['label'],
			'description' => $this->acf_option['instructions']
		);

		// Merge to inherit if empty
		$this->aica_args = array_merge( $new_args, $this->aica_args );
	}
}
