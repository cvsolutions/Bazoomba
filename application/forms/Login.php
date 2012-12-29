<?php

/**
 * Application_Form_Login
 *
 * @uses     Zend_Form
 *
 * @category Login
 * @package  Bazoomba.it
 * @author   Concetto Vecchio
 * @license
 * @link
 */
class Application_Form_Login extends Zend_Form
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
	}

	/**
	 * login
	 *
	 * @access public
	 *
	 * @return mixed Value.
	 */
	public function login() {

		$usermail = new Zend_Form_Element_Text( 'usermail' );
		$usermail->setLabel( 'Indirizzo E-mail' );
		$usermail->setRequired( true );
		$usermail->addValidator( 'NotEmpty' );
		$usermail->addValidator( 'EmailAddress' );
		$usermail->addFilters( array(
				'StringTrim',
				'StripTags'
			) );

		$pwd = new Zend_Form_Element_Password( 'pwd' );
		$pwd->setLabel( 'Password' );
		$pwd->setRequired( true );
		$pwd->addValidator( 'NotEmpty' );
		$pwd->addFilters( array(
				'StringTrim',
				'StripTags'
			) );

		$hash = new Zend_Form_Element_Hash( 'hash', 'no_csrf_foo', array( 'salt' => 'unique' ) );
		$hash->setDecorators( array(
				array( 'ViewHelper', array( 'helper' => 'formHidden' ) )
			) );

		$submit = new Zend_Form_Element_Submit( 'login' );
		$submit->setLabel( 'Login' );
		$submit->setAttrib( 'class', 'button' );

		return $this->addElements( array( $usermail, $pwd, $hash, $submit ) );
	}

	/**
	 * add
	 *
	 * @access public
	 *
	 * @return mixed Value.
	 */
	public function add() {
		$id = new Zend_Form_Element_Hidden( 'id' );
		$id->addFilter( 'Int' );
		$id->setDecorators( array( 'ViewHelper' ) );
		// $id->setValue('ratings');

		$name = new Zend_Form_Element_Text( 'name' );
		$name->setLabel( 'Nome & Cognome' );
		$name->setRequired( true );
		$name->addValidator( 'NotEmpty' );
		$name->addFilters( array(
				'StringTrim',
				'StripTags'
			) );

		$usermail = new Zend_Form_Element_Text( 'usermail' );
		$usermail->setLabel( 'Indirizzo E-mail' );
		$usermail->setRequired( true );
		$usermail->addValidator( 'NotEmpty' );
		$usermail->addValidator( 'EmailAddress' );
		$usermail->addFilters( array(
				'StringTrim',
				'StripTags'
			) );

		$pwd = new Zend_Form_Element_Password( 'pwd' );
		$pwd->setLabel( 'Password' );
		$pwd->setRequired( true );
		$pwd->addValidator( 'NotEmpty' );
		$pwd->addFilters( array(
				'StringTrim',
				'StripTags'
			) );

		$status = new Zend_Form_Element_Select( 'status' );
		$status->setLabel( 'Stato dell\'account' );
		$select = new Application_Model_OptionSelect();
		$status->addMultiOptions( $select->appendStatus() );

		$submit = new Zend_Form_Element_Submit( 'submit' );
		$submit->setLabel( 'Salva' );
		$submit->setAttrib( 'class', 'button' );

		return $this->addElements( array( $id, $name, $usermail, $pwd, $status, $submit ) );
	}


}
