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
        if ($this->getRequest()->getPost())
        {
            $form = new Application_Form_Shop();
            $form_data = $this->getRequest()->getPost();
            if($form->isValid($form_data))
            {
                $id = Plugin_Common::getId('ads_shop');
                $category = $this->_request->getPost('category');
                $sub_category = $this->_request->getPost('sub_category');
                $region = $this->_request->getPost('region');
                $province = $this->_request->getPost('province');
                $city = $this->_request->getPost('city');
                $type = $this->_request->getPost('type');
                $title = $this->_request->getPost('title');
                $description = $this->_request->getPost('description');
                $tags = $this->_request->getPost('tags');
                $price = $this->_request->getPost('price');
                $latitude = $this->_request->getPost('latitude');
                $longitude = $this->_request->getPost('longitude');

                $shop = new Application_Model_DbTable_Shop();
                $shop->newShop($id, $category, $sub_category, $region, $province, $city, $type, $title, $description, $tags, $price, $latitude, $longitude);

                echo json_encode(array('id' => $id));
            }
        }
    }

    public function provinceAction()
    {
        $select = new Application_Model_OptionSelect();
        $province = '';
        foreach ($select->appendProvinces($_REQUEST['id_reg']) as $key => $value) {
            $province .= sprintf('<option value="%d">%s</option>',$key, $value);
        }
        echo $province;
    }

    public function cityAction()
    {
        $select = new Application_Model_OptionSelect();
        $city = '';
        foreach ($select->appendCity(0, $_REQUEST['id_pro']) as $key => $value) {
            $city .= sprintf('<option value="%d">%s</option>',$key, $value);
        }
        echo $city;
    }

    public function subcategoryAction()
    {
        $select = new Application_Model_OptionSelect();
        $subcategory = '';
        foreach ($select->appendSubCategory($_REQUEST['id_cat']) as $key => $value) {
            $subcategory .= sprintf('<option value="%d">%s</option>',$key, $value);
        }
        echo $subcategory;
    }


}





