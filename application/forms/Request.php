<?php

class Application_Form_Request extends Twitter_Form
{
	public $select;

	public function init() {

		/* Form Elements & Other Definitions Here ... */
		$this->select = new Application_Model_OptionSelect();
	}

	public function Subscribe() {

		$name = new Zend_Form_Element_Text( 'name' );
		$name->setLabel( 'Nome & Cognome' );
		$name->setRequired( true );
		$name->addValidator( 'NotEmpty' );
		$name->addFilters( array(
				'StringTrim',
				'StripTags'
			) );

		$email = new Zend_Form_Element_Text( 'email' );
		$email->setLabel( 'Indirizzo E-mail' );
		$email->setRequired( true );
		$email->addValidator( 'NotEmpty' );
		$email->addValidator( 'EmailAddress' );
		$email->addFilters( array(
				'StringTrim',
				'StripTags'
			) );

		$category = new Zend_Form_Element_Select( 'category' );
		$category->setLabel( 'Categoria' );
		$category->setRequired( true );
		$category->addMultiOptions( $this->select->appendParentCategory() );

		$region = new Zend_Form_Element_Select( 'region' );
		$region->setLabel( 'Regione' );
		$region->setRequired( true );
		$region->addMultiOptions( $this->select->appendRegion() );

		$tags = new Zend_Form_Element_Text( 'tags' );
		$tags->setLabel( 'Inserire delle parole chiave separate da una virgola' );
		$tags->setRequired( true );
		$tags->addValidator( 'NotEmpty' );
		$tags->addFilters( array(
				'StringTrim',
				'StripTags'
			) );

		$submit = new Zend_Form_Element_Submit( 'submit' );
		$submit->setLabel( 'Attiva' );
		$submit->setAttrib( 'class', 'btn btn-primary' );

		return $this->addElements( array( $name, $email, $category, $region, $tags, $submit ) );
	}


}
