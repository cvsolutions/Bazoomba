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

		$submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('value', 'Login');
        $submit->setLabel('Cerca');

		return $this->addElements(array($q, $submit));
	}


}

