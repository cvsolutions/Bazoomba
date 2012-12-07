<?php

/**
 * Application_Form_Shop
 *
 * @uses     Zend_Form
 *
 * @category Form
 * @package  Bazoomba.it
 * @author   Concetto Vecchio
 * @license
 * @link
 */
class Application_Form_Shop extends Zend_Form
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
     * editAdmin
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function editAdmin() {
        $select = new Application_Model_OptionSelect();
        $this->setAttrib( 'class', 'custom' );

        $category = new Zend_Form_Element_Select( 'category' );
        $category->setLabel( 'Categoria' );
        $category->setRequired( true );
        $category->addMultiOptions( $select->appendParentCategory() );

        $sub_category = new Zend_Form_Element_Select( 'sub_category' );
        $sub_category->setLabel( 'Sotto categoria' );
        $sub_category->setRequired( true );
        $sub_category->addMultiOptions( $select->appendSubCategory() );

        $region = new Zend_Form_Element_Select( 'region' );
        $region->setLabel( 'Regione' );
        $region->setRequired( true );
        $region->addMultiOptions( $select->appendRegion() );

        $province = new Zend_Form_Element_Select( 'province' );
        $province->setLabel( 'Provincia' );
        $province->setRequired( true );
        $province->addMultiOptions( $select->appendProvinces() );

        $city = new Zend_Form_Element_Select( 'city' );
        $city->setLabel( 'Città' );
        $city->setRequired( true );
        $city->addMultiOptions( $select->appendCity() );

        $type = new Zend_Form_Element_Select( 'type' );
        $type->setLabel( 'Tipo di annuncio' );
        $type->setRequired( true );
        $type->addMultiOptions( $select->appendTypeAds() );

        $title = new Zend_Form_Element_Text( 'title' );
        $title->setLabel( 'Titolo' );
        $title->setRequired( true );
        $title->addValidator( 'NotEmpty' );
        $title->addFilters( array(
                'StringTrim',
                'StripTags'
            ) );

        $description = new Zend_Form_Element_Textarea( 'description' );
        $description->setLabel( 'Contenuto' );
        $description->setRequired( true );
        $description->addValidator( 'NotEmpty' );
        $description->addFilters( array(
                'StringTrim',
                'StripTags'
            ) );

        $price = new Zend_Form_Element_Text( 'price' );
        $price->setLabel( 'Prezzo' );
        $price->setRequired( true );
        $price->addValidator( 'NotEmpty' );
        $price->setAttribs( array( 'maxlength' => '12' ) );
        $price->addFilters( array(
                'StringTrim',
                'StripTags'
            ) );

        $status = new Zend_Form_Element_Select( 'status' );
        $status->setLabel( 'Stato' );
        $status->addMultiOptions( $select->appendStatus() );

        $submit = new Zend_Form_Element_Submit( 'submit' );
        $submit->setLabel( 'Salva' );
        $submit->setAttrib( 'class', 'button' );

        return $this->addElements( array( $category, $sub_category, $region, $province, $city, $type, $title, $description, $price, $status, $submit ) );
    }

    public function newShop() {
        $select = new Application_Model_OptionSelect();
        $this->setAttrib( 'id', 'newShop' );

        $category = new Zend_Form_Element_Select( 'category' );
        $category->setLabel( 'Categoria' );
        $category->setRequired( true );
        $category->addMultiOptions( $select->appendParentCategory() );

        $sub_category = new Zend_Form_Element_Select( 'sub_category' );
        $sub_category->setLabel( 'Sotto categoria' );
        $sub_category->setRequired( true );
        $sub_category->setRegisterInArrayValidator( false );

        $region = new Zend_Form_Element_Select( 'region' );
        $region->setLabel( 'Regione' );
        $region->setRequired( true );
        $region->addMultiOptions( $select->appendRegion() );

        $province = new Zend_Form_Element_Select( 'province' );
        $province->setLabel( 'Provincia' );
        $province->setRequired( true );
        $province->setRegisterInArrayValidator( false );

        $city = new Zend_Form_Element_Select( 'city' );
        $city->setLabel( 'Città' );
        $city->setRequired( true );
        $city->setRegisterInArrayValidator( false );

        $type = new Zend_Form_Element_Select( 'type' );
        $type->setLabel( 'Tipo di annuncio' );
        $type->setRequired( true );
        $type->addMultiOptions( $select->appendTypeAds() );
        $type->setValue( 1 );

        $title = new Zend_Form_Element_Text( 'title' );
        $title->setLabel( 'Titolo' );
        $title->setRequired( true );
        $title->addValidator( 'NotEmpty' );
        $title->setAttrib( 'placeholder', 'Titolo' );
        $title->addFilters( array(
                'StringTrim',
                'StripTags'
            ) );

        $description = new Zend_Form_Element_Textarea( 'description' );
        $description->setLabel( 'Contenuto' );
        $description->setRequired( true );
        $description->addValidator( 'NotEmpty' );
        $description->addFilters( array(
                'StringTrim',
                'StripTags'
            ) );

        $tags = new Zend_Form_Element_Text( 'tags' );
        $tags->setLabel( 'Tags' );
        $tags->addValidator( 'NotEmpty' );
        $tags->setAttrib( 'placeholder', 'Scegli i Tags' );
        $tags->addFilters( array(
                'StringTrim',
                'StripTags'
            ) );

        $price = new Zend_Form_Element_Text( 'price' );
        $price->setLabel( 'Prezzo' );
        $price->setRequired( true );
        $price->addValidator( 'NotEmpty' );
        $price->setAttribs( array( 'maxlength' => '12' ) );
        $price->addFilters( array(
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

        $terms = new Zend_Form_Element_Checkbox( 'terms' );
        $terms->setLabel( 'Accetta Condizioni' );
        $terms->setValue( 1 );
        $terms->addValidator( 'NotEmpty' );
        $terms->addFilters( array(
                'StringTrim',
                'StripTags'
            ) );

        $submit = new Zend_Form_Element_Submit( 'submit' );
        $submit->setLabel( 'Salva' );
        $submit->setAttrib( 'class', 'btn btn-primary' );

        return $this->addElements( array( $category, $sub_category, $region, $province, $city, $type, $title, $description, $tags, $price, $address, $div, $lat, $lon, $terms, $submit ) );
    }

    public function addMedia() {
        $this->setAttrib( 'id', 'addMedia' );

        $image = new Zend_Form_Element_File( 'image' );
        $image->setLabel( 'File' );
        $image->setDestination( sprintf( '%s/uploaded/ads', $_SERVER['DOCUMENT_ROOT'] ) );
        $image->setRequired( true );
        $image->addValidator( 'Extension', false, 'jpg,png,gif,jpeg' );

        $submit = new Zend_Form_Element_Submit( 'submit' );
        $submit->setLabel( 'Insersci Immagine' );
        $submit->setAttrib( 'class', 'btn btn-primary' );

        return $this->addElements( array( $image, $submit ) );
    }

    public function Reply_Advertiser() {

        $name = new Zend_Form_Element_Text( 'name' );
        $name->setLabel( 'Il tuo nome' );
        $name->setRequired( true );
        $name->addValidator( 'NotEmpty' );
        $name->addFilters( array(
                'StringTrim',
                'StripTags'
            ) );

        $mail = new Zend_Form_Element_Text( 'mail' );
        $mail->setLabel( 'La tua email' );
        $mail->setRequired( true );
        $mail->addValidator( 'NotEmpty' );
        $mail->addValidator( 'EmailAddress' );
        $mail->addFilters( array(
                'StringTrim',
                'StripTags'
            ) );

        $description = new Zend_Form_Element_Textarea( 'description' );
        $description->setLabel( 'Testo' );
        $description->setRequired( true );
        $description->addValidator( 'NotEmpty' );
        $description->addFilters( array(
                'StringTrim',
                'StripTags'
            ) );

        $submit = new Zend_Form_Element_Submit( 'submit' );
        $submit->setLabel( 'Rispondi' );

        return $this->addElements( array( $name, $mail, $description, $submit ) );
    }
}
