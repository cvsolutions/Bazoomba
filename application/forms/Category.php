<?php

/**
 * Application_Form_Category
 *
 * @uses     Zend_Form
 *
 * @category Category
 * @package  Bazoomba.it
 * @author   Concetto Vecchio
 * @license
 * @link
 */
class Application_Form_Category extends Zend_Form
{

	/**
	 * init
	 *
	 * @access public
	 *
	 * @return mixed Value.
	 */
	public function init() {
		$this->setAttrib( 'class', 'custom' );

		$id = new Zend_Form_Element_Hidden( 'id' );
		$id->addFilter( 'Int' );
		$id->setDecorators( array( 'ViewHelper' ) );
		// $id->setValue('ratings');

		$name = new Zend_Form_Element_Text( 'name' );
		$name->setLabel( 'Nome' );
		$name->setRequired( true );
		$name->addValidator( 'NotEmpty' );
		$name->addFilters( array(
				'StringTrim',
				'StripTags'
			) );

		$parent = new Zend_Form_Element_Select( 'parent' );
		$parent->setLabel( 'Genitore' );
		$select = new Application_Model_OptionSelect();
		$parent->addMultiOptions( $select->appendParentCategory() );

		$image = new Zend_Form_Element_File( 'image' );
		$image->setLabel( 'File' );
		$image->setDestination( sprintf( '%s/uploaded/category', $_SERVER['DOCUMENT_ROOT'] ) );
		// $image->setRequired(true);
		$image->addValidator( 'Extension', false, 'jpg,png,gif' );

		$submit = new Zend_Form_Element_Submit( 'submit' );
		$submit->setLabel( 'Salva' );
		$submit->setAttrib( 'class', 'button' );

		$this->addElements( array( $id, $name, $parent, $image, $submit ) );
	}


}
