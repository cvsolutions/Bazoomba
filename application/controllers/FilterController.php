<?php

class FilterController extends Zend_Controller_Action
{

    public $params = null;

    public function init()
    {
        $this->params = Plugin_Common::getParams();
    }

    public function indexAction()
    {
        $form = new Application_Form_Filter();
        $this->view->searchForm = $form->search();
    }

    public function regionAction()
    {
        $id = $this->_getParam('id', 0);

        $region = new Application_Model_DbTable_Region();
        $this->view->region = $region->getRegionInfo($id);
        $this->view->other = $region->Other_Region($id);

        $provinces = new Application_Model_DbTable_Provinces();
        $this->view->provinces = $provinces->Parent_Provinces($id);

        $shop = new Application_Model_DbTable_Shop();
        $this->view->list = $shop->fullShopFilter(array('type' => 'region', 'id' => $id));
        $this->view->type_ads = $this->params->type_ads->toArray();
        $this->view->notfound = $this->params->label_not_found;
    }

    public function categoryAction()
    {
        $id = $this->_getParam('id', 0);

        $category = new Application_Model_DbTable_Category();
        $this->view->category = $category->getCategoryInfo($id);
        $this->view->parent = $category->Parent_With_Category($id);
        $this->view->other = $category->Other_Category($id);

        $shop = new Application_Model_DbTable_Shop();
        $this->view->list = $shop->fullShopFilter(array('type' => 'category', 'id' => $id));
        $this->view->type_ads = $this->params->type_ads->toArray();
        $this->view->notfound = $this->params->label_not_found;
    }

    public function provinceAction()
    {
        $id = $this->_getParam('id', 0);
        $parent = $this->_getParam('parent', 0);

        $region = new Application_Model_DbTable_Region();
        $this->view->region = $region->getRegionInfo($parent);
        $this->view->other = $region->Other_Region($parent);

        $provinces = new Application_Model_DbTable_Provinces();
        $this->view->province = $provinces->getProvinceInfo($id);
        $this->view->provinces = $provinces->Parent_Provinces($parent);

        $shop = new Application_Model_DbTable_Shop();
        $this->view->list = $shop->fullShopFilter(array('type' => 'province', 'id' => $id));
        $this->view->type_ads = $this->params->type_ads->toArray();
        $this->view->notfound = $this->params->label_not_found;
    }

    public function subcategoryAction()
    {
        $id = $this->_getParam('id', 0);
        $parent = $this->_getParam('parent', 0);

        $category = new Application_Model_DbTable_Category();
        $this->view->category = $category->getCategoryInfo($id);
        $this->view->sub = $category->getCategoryInfo($parent);
        $this->view->parent = $category->Parent_With_Category($id);
        $this->view->other = $category->Other_Category($id);

        $shop = new Application_Model_DbTable_Shop();
        $this->view->list = $shop->fullShopFilter(array('type' => 'sub_category', 'id' => $parent));
        $this->view->type_ads = $this->params->type_ads->toArray();
        $this->view->notfound = $this->params->label_not_found;
    }

    public function searchAction()
    {
        $q = $this->_getParam('q', 0);

        $shop = new Application_Model_DbTable_Shop();
        $this->view->list = $shop->fullShopFilter(array('type' => 'search', 'q' => $q));
        $this->view->type_ads = $this->params->type_ads->toArray();
        $this->view->notfound = $this->params->label_not_found;
        $this->view->q = $q;
    }


}





