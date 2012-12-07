<?php

class Application_Form_Filter extends Zend_Form
{

	public function init() {
		/* Form Elements & Other Definitions Here ... */
	}

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
		$q->setLabel( 'Ricerca Annunci' );
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


		$submit = new Zend_Form_Element_Submit( 'submit' );
		$submit->setLabel( 'Cerca' );

		return $this->addElements( array( $category, $region, $q, $type, $submit ) );
	}


}
