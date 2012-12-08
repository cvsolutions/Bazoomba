<?php
/**
 * /tmp/phptidy-sublime-buffer.php
 *
 * @package default
 */


class FilterController extends Zend_Controller_Action
{

    public $params = null;

    /**
     *
     */
    public function init() {
        $this->params = Plugin_Common::getParams();
    }


    /**
     *
     */
    public function indexAction() {
        $form = new Application_Form_Filter();
        $this->view->searchForm = $form->search();

        $region = new Application_Model_DbTable_Region();
        $this->view->region = $region->fetchAll();

        $category = new Application_Model_DbTable_Category();
        $this->view->category = $category->Parent_With_Category( 0 );
    }


    /**
     *
     */
    public function regionAction() {
        $id = $this->_getParam( 'item', 0 );
        $ads = $this->_getParam( 'ads', 0 );
        $user = $this->_getParam( 'user', 0 );

        $region = new Application_Model_DbTable_Region();
        $this->view->region = $region->getRegionInfo( $id );
        $this->view->other = $region->Other_Region( $id );

        $provinces = new Application_Model_DbTable_Provinces();
        $this->view->provinces = $provinces->Parent_Provinces( $id );

        $shop = new Application_Model_DbTable_Shop();
        $this->view->list = $shop->fullShopFilter( array( 'type' => 'region', 'id' => $id, 'ads' => $ads, 'user' => $user ) );
        $this->view->type_ads = $this->params->type_ads->toArray();
        $this->view->type_user = $this->params->type_user->toArray();
        $this->view->notfound = $this->params->label_not_found;
    }


    /**
     * kjjjkj
     */
    public function categoryAction() {
        $id = $this->_getParam( 'item', 0 );
        $ads = $this->_getParam( 'ads', 0 );
        $user = $this->_getParam( 'user', 0 );

        $category = new Application_Model_DbTable_Category();
        $this->view->category = $category->getCategoryInfo( $id );
        $this->view->parent = $category->Parent_With_Category( $id );
        $this->view->other = $category->Other_Category( $id );

        $shop = new Application_Model_DbTable_Shop();
        $this->view->list = $shop->fullShopFilter( array( 'type' => 'category', 'id' => $id, 'ads' => $ads, 'user' => $user ) );
        $this->view->type_ads = $this->params->type_ads->toArray();
        $this->view->type_user = $this->params->type_user->toArray();
        $this->view->notfound = $this->params->label_not_found;
    }


    /**
     *
     */
    public function provinceAction() {
        $id = $this->_getParam( 'item', 0 );
        $parent = $this->_getParam( 'parent', 0 );

        $region = new Application_Model_DbTable_Region();
        $this->view->region = $region->getRegionInfo( $parent );
        $this->view->other = $region->Other_Region( $parent );

        $provinces = new Application_Model_DbTable_Provinces();
        $this->view->province = $provinces->getProvinceInfo( $id );
        $this->view->provinces = $provinces->Parent_Provinces( $parent );

        $shop = new Application_Model_DbTable_Shop();
        $this->view->list = $shop->fullShopFilter( array( 'type' => 'province', 'id' => $id ) );
        $this->view->type_ads = $this->params->type_ads->toArray();
        $this->view->type_user = $this->params->type_user->toArray();
        $this->view->notfound = $this->params->label_not_found;
    }


    /**
     *
     */
    public function subcategoryAction() {
        $id = $this->_getParam( 'item', 0 );
        $parent = $this->_getParam( 'parent', 0 );

        $category = new Application_Model_DbTable_Category();
        $this->view->category = $category->getCategoryInfo( $id );
        $this->view->sub = $category->getCategoryInfo( $parent );
        $this->view->parent = $category->Parent_With_Category( $id );
        $this->view->other = $category->Other_Category( $id );

        $shop = new Application_Model_DbTable_Shop();
        $this->view->list = $shop->fullShopFilter( array( 'type' => 'sub_category', 'id' => $parent ) );
        $this->view->type_ads = $this->params->type_ads->toArray();
        $this->view->type_user = $this->params->type_user->toArray();
        $this->view->notfound = $this->params->label_not_found;
    }


    /**
     *
     */
    public function searchAction() {

        $q = $this->_getParam( 'q', 0 );
        $category = $this->_getParam( 'category', 0 );
        $region = $this->_getParam( 'region', 0 );
        $type = $this->_getParam( 'type', 0 );
        $ads = $this->_getParam( 'ads', 0 );

        switch ($type) {

        case 'autocomplete':
            $this->_redirect(sprintf('/shop/ads/show/%d', $ads));
            break;

        case 'global':
            if ($this->getRequest()->getParams()) {
                $shop = new Application_Model_DbTable_Shop();
                $result = $shop->fullShopFilter( array(
                        'type' => $type,
                        'category' => $category,
                        'region' => $region,
                        'q' => $q
                    ) );

                $page = $this->_getParam('page', 1);
                $paginator = Zend_Paginator::factory($result);
                $paginator->setItemCountPerPage(2);
                $paginator->setCurrentPageNumber($page);

                $this->view->list = $paginator;
                $this->view->total_list = $paginator->getTotalItemCount();
                $this->view->type_ads = $this->params->type_ads->toArray();
                $this->view->type_user = $this->params->type_user->toArray();
                $this->view->notfound = $this->params->label_not_found;
            }
            break;
        }
    }


}
