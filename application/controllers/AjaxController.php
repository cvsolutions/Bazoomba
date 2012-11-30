<?php

class AjaxController extends Zend_Controller_Action
{

    public function preDispatch()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
    }

    public function init()
    {
    }

    public function indexAction()
    {
        // action body
    }

    public function newshopAction()
    {
        // action body
    }

    public function provinceAction()
    {
        $select = new Application_Model_OptionSelect();
        $Arr_province = $select->appendProvinces($_REQUEST['id_reg']);
        unset($Arr_province[0]);
        $province = '';
        foreach ($Arr_province as $key => $value) {
            $province .= sprintf('<option value="%d">%s</option>',$key, $value);
        }
        echo $province;
    }

    public function cityAction()
    {
        $select = new Application_Model_OptionSelect();
        $Arr_city = $select->appendCity(0, $_REQUEST['id_pro']);
        unset($Arr_city[0]);
        $city = '';
        foreach ($Arr_city as $key => $value) {
            $city .= sprintf('<option value="%d">%s</option>',$key, $value);
        }
        echo $city;
    }

    public function subcategoryAction()
    {
        $select = new Application_Model_OptionSelect();
        $Arr_subcategory = $select->appendSubCategory($_REQUEST['id_cat']);
        unset($Arr_subcategory[0]);
        $subcategory = '';
        foreach ($Arr_subcategory as $key => $value) {
            $subcategory .= sprintf('<option value="%d">%s</option>',$key, $value);
        }
        echo $subcategory;
    }


}









