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
	public function init()
	{
		/* Form Elements & Other Definitions Here ... */
	}

    /**
     * editAdmin
     * 
     * @access public
     *
     * @return mixed Value.
     */
	public function editAdmin()
	{
		$select = new Application_Model_OptionSelect();
		$this->setAttrib('class', 'custom');

		$category = new Zend_Form_Element_Select('category');
		$category->setLabel('Categoria');
		$category->setRequired(true);
		$category->addMultiOptions($select->appendParentCategory());

		$sub_category = new Zend_Form_Element_Select('sub_category');
		$sub_category->setLabel('Sotto categoria');
		$sub_category->setRequired(true);
		$sub_category->addMultiOptions($select->appendSubCategory());

		$region = new Zend_Form_Element_Select('region');
		$region->setLabel('Regione');
		$region->setRequired(true);
		$region->addMultiOptions($select->appendRegion());

		$province = new Zend_Form_Element_Select('province');
		$province->setLabel('Provincia');
		$province->setRequired(true);
		$province->addMultiOptions($select->appendProvinces());

		$city = new Zend_Form_Element_Select('city');
		$city->setLabel('Città');
		$city->setRequired(true);
		$city->addMultiOptions($select->appendCity());

		$type = new Zend_Form_Element_Select('type');
		$type->setLabel('Tipo di annuncio');
		$type->setRequired(true);
		$type->addMultiOptions($select->appendTypeAds());

		$title = new Zend_Form_Element_Text('title');
		$title->setLabel('Titolo');
		$title->setRequired(true);
		$title->addValidator('NotEmpty');
		$title->addFilters(array(
			'StringTrim', 
			'StripTags'
			));

		$description = new Zend_Form_Element_Textarea('description');
		$description->setLabel('Contenuto');
		$description->setRequired(true);
		$description->addValidator('NotEmpty');
		$description->addFilters(array(
			'StringTrim', 
			'StripTags'
			));

		$price = new Zend_Form_Element_Text('price');
		$price->setLabel('Prezzo');
		$price->setRequired(true);
		$price->addValidator('NotEmpty');
		$price->setAttribs(array('maxlength' => '12'));
		$price->addFilters(array(
			'StringTrim', 
			'StripTags'
			));

		$status = new Zend_Form_Element_Select('status');
		$status->setLabel('Stato');
		$status->addMultiOptions($select->appendStatus());

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Salva');
		$submit->setAttrib('class', 'button');

		return $this->addElements(array($category, $sub_category, $region, $province, $city, $type, $title, $description, $price, $status, $submit));
	}


}

