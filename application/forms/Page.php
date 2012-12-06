<?php

class Application_Form_Page extends Zend_Form
{

    public function init()
    {

    }

    public function newPage()
    {
        $image = new Zend_Form_Element_File('image');
    	$image->setLabel('Logo');
    	$image->setDestination(sprintf('%s/uploaded/logo', $_SERVER['DOCUMENT_ROOT']));
    	$image->setRequired(true);
    	$image->addValidator('Extension', false, 'jpg,png,gif,jpeg');

        $description = new Zend_Form_Element_Textarea('description');
    	$description->setLabel('Dici chi sei');
    	$description->setRequired(true);
    	$description->addValidator('NotEmpty');
    	$description->addFilters(array(
    		'StringTrim',
    		'StripTags'
    		));

        $site = new Zend_Form_Element_Text('site');
        $site->setValue('http://');
    	$site->setLabel('Sito web');
    	$site->setRequired(true);
    	$site->addValidator('NotEmpty');
    	$site->setAttrib('placeholder', 'Il tuo sito web');
    	$site->addFilters(array(
    		'StringTrim',
    		'StripTags'
    		));

        $phone = new Zend_Form_Element_Text('telephone');
    	$phone->setLabel('Telefono Azienda');
    	$phone->setRequired(true);
    	$phone->addValidator('NotEmpty');
    	$phone->setAttrib('placeholder', 'Il tuo numero di telefono');
    	$phone->addFilters(array(
    		'StringTrim',
    		'StripTags'
    		));

        $submit = new Zend_Form_Element_Submit('submit');
    	$submit->setLabel('Crea Pagina');
    	$submit->setAttrib('class', 'btn btn-primary');

        return $this->addElements(array($image, $description, $site, $phone, $submit));
    }


}


