<?php

/**
 * Application_Form_Page
 *
 * @uses     Zend_Form
 *
 * @category Page
 * @package  Bazoomba.it
 * @author   Concetto Vecchio
 * @license
 * @link
 */
class Application_Form_Page extends Zend_Form
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
     * newPage
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function newPage() {
        $image = new Zend_Form_Element_File( 'image' );
        $image->setLabel( 'Logo' );
        $image->setDestination( sprintf( '%s/uploaded/logo', $_SERVER['DOCUMENT_ROOT'] ) );
        $image->setRequired( true );
        $image->addValidator( 'Extension', false, 'jpg,png,gif,jpeg' );

        $description = new Zend_Form_Element_Textarea( 'description' );
        $description->setLabel( 'Dici chi sei' );
        $description->setRequired( true );
        $description->addValidator( 'NotEmpty' );
        $description->addFilters( array(
                'StringTrim',
                'StripTags'
            ) );

        $site = new Zend_Form_Element_Text( 'site' );
        $site->setValue( 'http://' );
        $site->setLabel( 'Sito web' );
        $site->setRequired( true );
        $site->addValidator( 'NotEmpty' );
        $site->setAttrib( 'placeholder', 'Il tuo sito web' );
        $site->addFilters( array(
                'StringTrim',
                'StripTags'
            ) );

        $phone = new Zend_Form_Element_Text( 'telephone' );
        $phone->setLabel( 'Telefono Azienda' );
        $phone->setRequired( true );
        $phone->addValidator( 'NotEmpty' );
        $phone->setAttrib( 'placeholder', 'Il tuo numero di telefono' );
        $phone->addFilters( array(
                'StringTrim',
                'StripTags'
            ) );

        $address = new Zend_Form_Element_Text( 'address' );
        $address->setAttrib( 'placeholder', "Scrivi l'indirizzo dell'oggetto" );
        $address->setLabel( 'Mappa' );
        $address->setRequired( true );
        $address->addValidator( 'NotEmpty' );
        $address->addFilters( array(
                'StringTrim',
                'StripTags'
            ) );

        $div = new Zend_Form_Element_Hidden( 'div' );
        $div->setDecorators( array( array( 'HtmlTag', array( 'tag'=>'div', 'id'=>'map_canvas' ) ) ) );

        $lat = new Zend_Form_Element_Hidden( 'latitude' );
        $lat->setRequired( true );
        $lat->addValidator( 'NotEmpty' );
        $lat->addFilters( array(
                'StringTrim',
                'StripTags'
            ) );

        $lon = new Zend_Form_Element_Hidden( 'longitude' );
        $lon->setRequired( true );
        $lon->addValidator( 'NotEmpty' );
        $lon->addFilters( array(
                'StringTrim',
                'StripTags'
            ) );

        $submit = new Zend_Form_Element_Submit( 'submit' );
        $submit->setLabel( 'Crea Pagina' );
        $submit->setAttrib( 'class', 'btn btn-primary' );

        return $this->addElements( array( $image, $description, $site, $phone, $address, $div, $lat, $lon, $submit ) );
    }

    /**
     * editData
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function editData() {
        $description = new Zend_Form_Element_Textarea( 'description' );
        $description->setLabel( 'Dici chi sei' );
        $description->setRequired( true );
        $description->addValidator( 'NotEmpty' );
        $description->addFilters( array(
                'StringTrim',
                'StripTags'
            ) );

        $site = new Zend_Form_Element_Text( 'site' );
        $site->setValue( 'http://' );
        $site->setLabel( 'Sito web' );
        $site->setRequired( true );
        $site->addValidator( 'NotEmpty' );
        $site->setAttrib( 'placeholder', 'Il tuo sito web' );
        $site->addFilters( array(
                'StringTrim',
                'StripTags'
            ) );

        $phone = new Zend_Form_Element_Text( 'telephone' );
        $phone->setLabel( 'Telefono Azienda' );
        $phone->setRequired( true );
        $phone->addValidator( 'NotEmpty' );
        $phone->setAttrib( 'placeholder', 'Il tuo numero di telefono' );
        $phone->addFilters( array(
                'StringTrim',
                'StripTags'
            ) );

        $address = new Zend_Form_Element_Text( 'address' );
        $address->setAttrib( 'placeholder', "Scrivi l'indirizzo dell'oggetto" );
        $address->setLabel( 'Mappa' );
        $address->setRequired( true );
        $address->addValidator( 'NotEmpty' );
        $address->addFilters( array(
                'StringTrim',
                'StripTags'
            ) );

        $div = new Zend_Form_Element_Hidden( 'div' );
        $div->setDecorators( array( array( 'HtmlTag', array( 'tag'=>'div', 'id'=>'map_canvas' ) ) ) );

        $lat = new Zend_Form_Element_Hidden( 'latitude' );
        $lat->setRequired( true );
        $lat->addValidator( 'NotEmpty' );
        $lat->addFilters( array(
                'StringTrim',
                'StripTags'
            ) );

        $lon = new Zend_Form_Element_Hidden( 'longitude' );
        $lon->setRequired( true );
        $lon->addValidator( 'NotEmpty' );
        $lon->addFilters( array(
                'StringTrim',
                'StripTags'
            ) );

        $submit = new Zend_Form_Element_Submit( 'submit' );
        $submit->setLabel( 'Modifica Dati' );
        $submit->setAttrib( 'class', 'btn btn-primary' );

        return $this->addElements( array( $description, $site, $phone, $address, $div, $lat, $lon, $submit ) );
    }

    /**
     * editPicture
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function editPicture() {
        $image = new Zend_Form_Element_File( 'image' );
        $image->setLabel( 'Logo' );
        $image->setDestination( sprintf( '%s/uploaded/logo', $_SERVER['DOCUMENT_ROOT'] ) );
        $image->setRequired( true );
        $image->addValidator( 'Extension', false, 'jpg,png,gif,jpeg' );

        $submit = new Zend_Form_Element_Submit( 'submit' );
        $submit->setLabel( 'Modifica Logo' );
        $submit->setAttrib( 'class', 'btn btn-primary' );

        return $this->addElements( array( $image, $submit ) );
    }

    /**
     * gallery
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function gallery() {
        $image = new Zend_Form_Element_File( 'image' );
        $image->setLabel( 'Aggiungi Immagine Alla Gallery' );
        $image->setDestination( sprintf( '%s/uploaded/gallery', $_SERVER['DOCUMENT_ROOT'] ) );
        $image->setRequired( true );
        $image->addValidator( 'Extension', false, 'jpg,png,gif,jpeg' );

        $submit = new Zend_Form_Element_Submit( 'submit' );
        $submit->setLabel( 'Aggiungi Immagine' );
        $submit->setAttrib( 'class', 'btn btn-primary' );

        return $this->addElements( array( $image, $submit ) );

    }
}
