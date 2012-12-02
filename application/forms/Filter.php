<?php

class Application_Form_Filter extends Zend_Form
{

	public function init()
	{
		/* Form Elements & Other Definitions Here ... */
	}

	public function search()
	{
		$this->setAction('/filter/search');
		$this->setMethod('get');

		$q = new Zend_Form_Element_Text('q');
		$q->setLabel('Ricerca Annunci');
		$q->setRequired(true);
		$q->addValidator('NotEmpty');
		$q->addFilters(array(
			'StringTrim', 
			'StripTags'
			));

		$type = new Zend_Form_Element_Hidden('type');
		$type->setRequired(true);
		$type->setValue('label');
		$type->removeDecorator('HtmlTag'); 
		$type->removeDecorator('Label'); 


		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Cerca');

		return $this->addElements(array($q, $type, $submit));
	}


}

