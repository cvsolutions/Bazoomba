<?php

/**
* Application_Form_Filter
*
* @uses     Zend_Form
*
* @category Filter
* @package  Bazoomba.it
* @author   Concetto Vecchio
* @license  
* @link     
*/
class Application_Form_Filter extends Zend_Form
{

    /**
     * init
     * 
     * @access public
     *
     * @return mixed Value.
     */
	public function init() {
		/* Form Elements & Other Definitions Here ... */
	}

    /**
     * search
     * 
     * @access public
     *
     * @return mixed Value.
     */
	public function search() {
		$select = new Application_Model_OptionSelect();

		$this->setAction( '/filter/search' );
		$this->setMethod( 'get' );

		$category = new Zend_Form_Element_Select( 'category' );
		$category->setLabel( 'Categoria' );
		$category->setRequired( true );
		$category->addMultiOptions( $select->appendParentCategory() );

		$region = new Zend_Form_Element_Select( 'region' );
		$region->setLabel( 'Regione' );
		$region->setRequired( true );
		$region->addMultiOptions( $select->appendRegion() );

		$q = new Zend_Form_Element_Text( 'q' );
		$q->setLabel( 'Cosa cerchi?' );
		$q->setAttrib ( 'placeholder', 'es. Iphone 5, Mercedes, Divano' );
		$q->setRequired( true );
		$q->addValidator( 'NotEmpty' );
		$q->addFilters( array(
				'StringTrim',
				'StripTags'
			) );

		$type = new Zend_Form_Element_Hidden( 'type' );
		$type->setRequired( true );
		$type->setValue( 'global' );
		$type->removeDecorator( 'HtmlTag' );
		$type->removeDecorator( 'Label' );

		$ads = new Zend_Form_Element_Hidden( 'ads' );
		$ads->setRequired( true );
		$ads->setValue( '' );
		$ads->removeDecorator( 'HtmlTag' );
		$ads->removeDecorator( 'Label' );


		$submit = new Zend_Form_Element_Submit( 'submit' );
		$submit->setLabel( 'Cerca' );

		return $this->addElements( array( $category, $region, $q, $type, $ads, $submit ) );
	}


}
