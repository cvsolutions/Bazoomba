<?php

/**
* SettingController
*
* @uses     Zend_Controller_Action
*
* @category Setting
* @package  Bazoomba.it
* @author   Concetto Vecchio
* @license  
* @link     
*/
class SettingController extends Zend_Controller_Action
{

    /**
     * $params
     *
     * @var mixed
     *
     * @access public
     */
	public $params = null;

    /**
     * init
     * 
     * @access public
     *
     * @return mixed Value.
     */
	public function init()
	{
		$this->params = Plugin_Common::getParams();
	}

    /**
     * indexAction
     * 
     * @access public
     *
     * @return mixed Value.
     */
	public function indexAction()
	{
        // action body
	}

    /**
     * editAction
     * 
     * @access public
     *
     * @return mixed Value.
     */
	public function editAction()
	{
		$form = new Application_Form_Setting();
		$this->view->settingForm = $form;
		
		$setting = new Application_Model_DbTable_Setting();

		if ($this->getRequest()->getPost())
		{
			$form_data = $this->getRequest()->getPost();
			if($form->isValid($form_data))
			{
				$title = $form->getValue('title');
				$description = $form->getValue('description');
				$keywords = $form->getValue('keywords');
				
				$setting->updateSettings($title, $description, $keywords);
				$this->view->successForm = $this->params->label_success;
				$this->view->headMeta()->appendHttpEquiv('refresh','3; url=/dashboard');
			}

		} else {
			$form->populate($setting->getSettings());
		}
	}


}



