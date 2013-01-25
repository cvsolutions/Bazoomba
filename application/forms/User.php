<?php

/**
 * Application_Form_User
 *
 * @uses     Zend_Form
 *
 * @category User
 * @package  Bazoomba.it
 * @author   Concetto Vecchio
 * @license
 * @link
 */
class Application_Form_User extends Twitter_Form
{

    /**
     * init
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function init() {

    }

    /**
     * login
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function login() {
        $this->setAttrib("class", "form-signin");

        $user = new Zend_Form_Element_Text( 'user' );
        $user->setLabel( 'Indirizzo E-mail' );
        $user->setRequired( true );
        $user->addValidator( 'NotEmpty' );
        $user->addValidator( 'EmailAddress' );
        $user->addFilters( array(
                'StringTrim',
                'StripTags'
            ) );

        $this->addElement($user);

        $pwd = new Zend_Form_Element_Password( 'pwd' );
        $pwd->setLabel( 'Password' );
        $pwd->setRequired( true );
        $pwd->addValidator( 'NotEmpty' );
        $pwd->addFilters( array(
                'StringTrim',
                'StripTags'
            ) );

        $this->addElement($pwd);

        $submit = new Zend_Form_Element_Submit( 'login' );
        $submit->setLabel( 'Esegui Login' );
        $submit->setAttrib('class', 'btn btn-warning');

        $this->addElement($submit);

        return $this->addElements( array() );
    }

    /**
     * lostPassword
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function lostPassword() {
        $user = new Zend_Form_Element_Text( 'user' );
        $user->setLabel( 'Indirizzo E-mail' );
        $user->setRequired( true );
        $user->addValidator( 'NotEmpty' );
        $user->addValidator( 'EmailAddress' );
        $user->addValidator( new Zend_Validate_Db_RecordExists( array(
                    'table' => 'ads_user',
                    'field' => 'email'
                ) ) );
        $user->addFilters( array(
                'StringTrim',
                'StripTags'
            ) );

        $submit = new Zend_Form_Element_Submit( 'submit' );
        $submit->setLabel( 'Recupera Password' );

        return $this->addElements( array( $user, $submit ) );
    }

    /**
     * resetPassword
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function resetPassword() {
        $pwd = new Zend_Form_Element_Password( 'pwd' );
        $pwd->addValidator( 'NotEmpty' );
        $pwd->addValidator( 'StringLength', false, array( 6, 20 ) );
        $pwd->setRequired( true );
        $pwd->setLabel( 'New password' );

        $confirm = new Zend_Form_Element_Password( 'confirm' );
        $confirm->addValidator( 'NotEmpty' );
        $confirm->addValidator( 'Identical' );
        $confirm->setRequired( true );
        $confirm->setLabel( 'Confirm password' );

        $validator = $confirm->getValidator( 'Identical' );
        $validator->setToken( 'pwd' );
        $validator->setObscureValue( true );

        $submit = new Zend_Form_Element_Submit( 'submit' );
        $submit->setLabel( 'Recupera Password' );

        return $this->addElements( array( $pwd, $confirm, $submit ) );
    }

    /**
     * editAdmin
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function editAdmin() {
        $select = new Application_Model_OptionSelect();
        $this->setAttrib( 'class', 'custom' );

        $type = new Zend_Form_Element_Select( 'type' );
        $type->setLabel( 'Tipo di account' );
        $type->addMultiOptions( $select->appendTypeUser() );

        $name = new Zend_Form_Element_Text( 'name' );
        $name->setLabel( 'Nome & Cognome' );
        $name->setRequired( true );
        $name->addValidator( 'NotEmpty' );
        $name->addFilters( array(
                'StringTrim',
                'StripTags'
            ) );

        $status = new Zend_Form_Element_Select( 'status' );
        $status->setLabel( 'Stato dell\'account' );
        $status->addMultiOptions( $select->appendStatus() );

        $submit = new Zend_Form_Element_Submit( 'submit' );
        $submit->setLabel( 'Salva' );
        $submit->setAttrib( 'class', 'button' );

        return $this->addElements( array( $type, $name, $status, $submit ) );
    }

    /**
     * newUser
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function newUser() {
        $this->setName( 'register' );

        $select = new Application_Model_OptionSelect();
        $this->setAttrib( 'class', 'form-horizontal' );
        $this->setAttrib( 'id', 'newUser' );

        $type = new Zend_Form_Element_Select( 'type' );
        $type->setLabel( 'Tipo di account' );
        $type->addMultiOptions( $select->appendTypeUser() );

        $name = new Zend_Form_Element_Text( 'name' );
        $name->setLabel( 'Nome & Cognome' );
        $name->setRequired( true );
        $name->addFilter( 'StripTags' );
        $name->addFilter( 'StringTrim' );
        $name->addValidator( 'NotEmpty' );

        $email = new Zend_Form_Element_Text( 'email' );
        $email->setLabel( 'Email' );
        $email->setRequired( true );
        $email->addFilter( 'StripTags' );
        $email->addFilter( 'StringTrim' );
        $email->addValidator( 'NotEmpty' );
        $email->addValidator( 'EmailAddress' );
        $email->addValidator( new Zend_Validate_Db_NoRecordExists( array(
                    'table' => 'ads_user',
                    'field' => 'email'
                ) ) );

        $phone = new Zend_Form_Element_Text( 'telephone' );
        $phone->setLabel( 'Telefono' )
        ->setRequired( true )
        ->addFilter( 'StripTags' )
        ->addFilter( 'StringTrim' )
        ->addValidator( 'NotEmpty' );

        $radio = new Zend_Form_Element_Radio( 'phone_show', array( 'multiOptions' => array( 0=> 'Non Visualizzare', 1=> 'Visualizza' ) ) );
        $radio->setLabel( 'Mostra Numero Telefonico' );
        $radio->setValue( array( '1' ) );

        $iva = new Zend_Form_Element_Text( 'vat', array( 'autocomplete' => 'off' ) );
        $iva->setAttrib( 'autocomplete', 'off' );
        $iva->setAttrib( 'class', 'brands' );
        $iva->setLabel( 'Partita Iva' )
        ->setRequired( false )
        ->addFilter( 'StripTags' )
        ->addFilter( 'StringTrim' )
        ->addValidator( 'NotEmpty' );

        $name_company = new Zend_Form_Element_Text( 'name_company', array( 'autocomplete' => 'off' ) );
        $name_company->setAttrib( 'class', 'brands' );
        $name_company->setLabel( 'Ragione Sociale' )
        ->setRequired( false )
        ->addFilter( 'StripTags' )
        ->addFilter( 'StringTrim' )
        ->addValidator( 'NotEmpty' );

        $pwd = new Zend_Form_Element_Password( 'pwd' );
        $pwd->addValidator( 'NotEmpty' );
        $pwd->addValidator( 'StringLength', false, array( 6, 20 ) );
        $pwd->setRequired( true );
        $pwd->setLabel( 'Nuova password' );

        $confirm = new Zend_Form_Element_Password( 'confirm' );
        $confirm->addValidator( 'NotEmpty' );
        $confirm->addValidator( 'Identical' );
        $confirm->setRequired( true );
        $confirm->setLabel( 'Conferma password' );

        $validator = $confirm->getValidator( 'Identical' );
        $validator->setToken( 'pwd' );
        $validator->setObscureValue( true );

        $submit = new Zend_Form_Element_Submit( 'Registrati' );
        $submit->setAttrib( 'class', 'btn btn-primary' );
        $submit->setAttrib( 'id', 'submit' );

        return $this->addElements( array( $type, $name, $email, $phone, $radio, $iva, $name_company, $pwd, $confirm, $submit ) );
    }

    /**
     * newUser
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function editUser() {
        $this->setName( 'Modifica Dati' );

        $select = new Application_Model_OptionSelect();
        $this->setAttrib( 'class', 'custom' );

        $type = new Zend_Form_Element_Select( 'type' );
        $type->setLabel( 'Tipo di account' );
        $type->addMultiOptions( $select->appendTypeUser() );

        $name = new Zend_Form_Element_Text( 'name' );
        $name->setLabel( 'Nome & Cognome' )
        ->setRequired( true )
        ->addFilter( 'StripTags' )
        ->addFilter( 'StringTrim' )
        ->addValidator( 'NotEmpty' );

        $email = new Zend_Form_Element_Text( 'email' );
        $email->setLabel( 'Email' )
        ->setRequired( true )
        ->addFilter( 'StripTags' )
        ->addFilter( 'StringTrim' )
        ->addValidator( 'NotEmpty' )
        ->addValidator( 'EmailAddress' );

        $phone = new Zend_Form_Element_Text( 'telephone' );
        $phone->setLabel( 'Telefono' )
        ->setRequired( true )
        ->addFilter( 'StripTags' )
        ->addFilter( 'StringTrim' )
        ->addValidator( 'NotEmpty' );

        $radio = new Zend_Form_Element_Radio( 'phone_show', array( 'multiOptions' => array( 0=> 'Non Visualizzare', 1=> 'Visualizza' ) ) );
        $radio->setLabel( 'Mostra Numero Telefonico' );
        $radio->setValue( array( '1' ) );

        $iva = new Zend_Form_Element_Text( 'vat' );
        $iva->setAttrib( 'class', 'brand' );
        $iva->setLabel( 'Partita Iva' )
        ->setRequired( false )
        ->addFilter( 'StripTags' )
        ->addFilter( 'StringTrim' )
        ->addValidator( 'NotEmpty' );

        $name_company = new Zend_Form_Element_Text( 'name_company' );
        $name_company->setAttrib( 'class', 'brand' );
        $name_company->setLabel( 'Ragione Sociale' )
        ->setRequired( false )
        ->addFilter( 'StripTags' )
        ->addFilter( 'StringTrim' )
        ->addValidator( 'NotEmpty' );

        $submit = new Zend_Form_Element_Submit( 'Modifica' );
        $submit->setAttrib( 'id', 'submitbutton' );

        return $this->addElements( array( $type, $name, $email, $phone, $radio, $iva, $name_company, $submit ) );
    }

    /**
     * editPassword
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function editPassword() {
        $pwd = new Zend_Form_Element_Password( 'pwd' );
        $pwd->addValidator( 'NotEmpty' );
        $pwd->addValidator( 'StringLength', false, array( 6, 20 ) );
        $pwd->setRequired( true );
        $pwd->setLabel( 'Nuova password' );

        $confirm = new Zend_Form_Element_Password( 'confirm' );
        $confirm->addValidator( 'NotEmpty' );
        $confirm->addValidator( 'Identical' );
        $confirm->setRequired( true );
        $confirm->setLabel( 'Conferma password' );

        $validator = $confirm->getValidator( 'Identical' );
        $validator->setToken( 'pwd' );
        $validator->setObscureValue( true );

        $submit = new Zend_Form_Element_Submit( 'submit' );
        $submit->setLabel( 'Modifica Password' );

        return $this->addElements( array( $pwd, $confirm, $submit ) );
    }

    /**
     * addAvatar
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function addAvatar() {
        $this->setAttrib( 'id', 'addAvatar' );

        $image = new Zend_Form_Element_File( 'image' );
        $image->setLabel( 'Avatar' );
        $image->setDestination( sprintf( '%s/uploaded/avatar', $_SERVER['DOCUMENT_ROOT'] ) );
        $image->setRequired( true );
        $image->addValidator( 'Extension', false, 'jpg,png,gif,jpeg' );

        $submit = new Zend_Form_Element_Submit( 'submit' );
        $submit->setLabel( 'Insersci Avatar' );
        $submit->setAttrib( 'class', 'btn btn-primary' );

        return $this->addElements( array( $image, $submit ) );
    }

    public function test()
	{
		// Make this form horizontal
		$this->setAttrib("horizontal", true);

		$this->addElement("text", "username", array(
			"label" => "Username",
			"description" => "Foobar"
		));

		$this->addElement("password", "password", array(
			"label" => "Password",
			"description" => "Please enter a nice password.",
			"required" => true,
		));

		$this->addElement("password", "oldpassword", array(
			"label" => "Old Password",
			"value" => "******",
			"description" => "This is your old password. Keep going.",
			"attribs" => array(
				"disabled" => true
			)
		));

		$this->addElement("select", "Security Question", array(
			"label" => "Please select a security question",
			"multiOptions" => array(
				"car" => "What was your first car?",
				"city" => "What is your favorite city?"
			)));

		$this->addElement("checkbox", "remember_me", array(
			"label" => "Remember me for two weeks",
		));

		$this->addElement("radio", "terms", array(
			"label" => "I agree to the terms",
		));

		$this->addElement("radio", "terms", array(
			"label" => "Terms",
			"multiOptions" => array(
				"1" => "I agree to the terms",
				"0" => "I don't agree to the terms"
			)
		));

		$this->addElement("multicheckbox", "multichecks", array(
			"description" => "This is a nice thing.",
			"label" => "Foobar",
			"multiOptions" => array(
				"1" => "I agree to the terms",
				"0" => "I don't agree to the terms"
			)
		));

		$this->addElement("multicheckbox", "multichecks2", array(
			"label" => "Inline checkboxes",
			"inline" => true,
			"multiOptions" => array(
				"1" => "One",
				"0" => "Two"
			)
		));

		$this->addElement("file", "file", array(
			"label" => "Please upload a file",
			"required" => true
			));

		$this->addElement("hidden", "id", array(
			"value" => "Test",
			"label" => "Test"
		));

		$elm = $this->createElement("text", "foo", array(
			"label" => "Element created via createElement"));

		$this->addElement($elm);

		$elm2 = new Zend_Form_Element_Text("foo2", array(
			"label" => "Via new instance"));

		$this->addElement($elm2);

		$this->addElement("submit", "register", array("label" => "Register"));
		$this->addElement("reset", "reset", array("label" => "Reset"));
		$this->addElement("button", "custom", array(
			"class" => "success",
			"label" => "Custom classes, too!"
		));
	}

}
