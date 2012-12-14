<?php

/**
 * Application_Form_Setting
 *
 * @uses     Zend_Form
 *
 * @category Setting
 * @package  Bazoomba.it
 * @author   Concetto Vecchio
 * @license
 * @link
 */
class Application_Form_Setting extends Zend_Form
{

	/**
	 * init
	 *
	 * @access public
	 *
	 * @return mixed Value.
	 */
	public function init() {
		$title = new Zend_Form_Element_Text( 'title' );
		$title->setLabel( 'Tag title' );
		$title->setRequired( true );
		$title->addValidator( 'NotEmpty' );
		// $title->addValidator('date', 'mm-dd-YYYY');
		$title->addFilters( array(
				'StringTrim',
				'StripTags'
			) );

		$description = new Zend_Form_Element_Textarea( 'description' );
		$description->setLabel( 'Meta tag description' );
		$description->setRequired( true );
		$description->addValidator( 'NotEmpty' );
		$description->addFilters( array(
				'StringTrim',
				'StripTags'
			) );

		$keywords = new Zend_Form_Element_Textarea( 'keywords' );
		$keywords->setLabel( 'Meta tag keywords' );
		$keywords->setRequired( true );
		$keywords->addValidator( 'NotEmpty' );
		$keywords->addFilters( array(
				'StringTrim',
				'StripTags'
			) );

		$off_line = new Zend_Form_Element_Checkbox( "off_line" );
		$off_line->setLabel( 'Sito in manutenzione' );

		$submit = new Zend_Form_Element_Submit( 'send' );
		$submit->setLabel( 'Salva' );
		$submit->setAttrib( 'class', 'button' );

		$this->addElements( array( $title, $description, $keywords, $off_line, $submit ) );
	}


}
