<?php
/**
 * Image upload field
 *
 * @package EverestForms\Fields
 * @since   1.2.5
 */

defined( 'ABSPATH' ) || exit;

/**
 * EVF_Field_Image_Upload Class.
 */
class EVF_Field_Image_Upload extends EVF_Form_Fields {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->name   = __( 'Image Upload', 'everest-forms' );
		$this->type   = 'image-upload';
		$this->icon   = 'evf-icon evf-icon-image-upload';
		$this->order  = 31;
		$this->group  = 'advanced';
		$this->is_pro = true;

		parent::__construct();
	}
}
