<?php

/**
 * FilterController
 *
 * @uses     Zend_Controller_Action
 *
 * @category Filter
 * @package  Bazoomba.it
 * @author   Concetto Vecchio
 * @license
 * @link
 *
 */

class FilterController extends Zend_Controller_Action
{

    /**
     * $params
     *
     * @var mixed
     *
     * @access public
     *
     */
    public $params = null;

    /**
     * init
     *
     * @access public
     *
     * @return mixed Value.
     *
     */
    public function init()
    {

        /* Initialize action controller here */
        $this->params = Plugin_Common::getParams();

        /* Check if your website is down */
        Plugin_Common::Chech_Off_Line();

        $auth = Zend_Auth::getInstance();
        $identity = $auth->getStorage()->read();
        $this->view->identity = $identity;
    }

    /**
     * @param array $shop
     *
     * @return array
     *
     */
    private function slider_range($shop = array ())
    {
        if (is_array($shop)) {
            // print_r($shop);
            foreach ($shop as $row) {
                $price = sprintf('%d', $row['price']);
                $item[] = $price;
            }
            return array_unique($item);
        }
    }

    /**
     * indexAction
     *
     * @access public
     *
     * @return mixed Value.
     *
     */
    public function indexAction()
    {

        /* creo il form per la ricerca veloce */
        $form = new Application_Form_Filter();
        $this->view->searchForm = $form->search();

        $shop = new Application_Model_DbTable_Shop();
        $this->view->list = $shop->SearchAdsPage();

        /* la lista delle regioni */
        $region = new Application_Model_DbTable_Region();
        $this->view->region = $region->fetchAll();

        /* le categorie attive */
        $category = new Application_Model_DbTable_Category();
        $this->view->category = $category->Parent_With_Category(0);
        
        $this->view->type_ads = $this->params->type_ads->toArray();
    }

    /**
     * regionAction
     * ricerca per regione
     *
     * @access public
     *
     * @return mixed Value.
     *
     */
    public function regionAction()
    {

        /* recupero i parametri */
        $id = $this->_getParam('item', 0);
        $ads = $this->_getParam('ads', 0);
        $user = $this->_getParam('user', 0);

        $region = new Application_Model_DbTable_Region();

        /* tutte le informazioni della regione selezionata */
        $this->view->region = $region->getRegionInfo($id);

        /* le altre regioni escluso quella selezionata*/
        $this->view->other = $region->Other_Region($id);

        /* tutte le province della regione*/
        $provinces = new Application_Model_DbTable_Provinces();
        $this->view->provinces = $provinces->Parent_Provinces($id);

        /**
         * creo l'array con la lista degli annunci
         * mostro a video+array()
         */
        $shop = new Application_Model_DbTable_Shop();
        $fullShop = $shop->fullShopFilter(array('type' => 'region', 'id' => $id, 'ads' => $ads, 'user' => $user));
        $this->view->list = $fullShop;
        $this->view->range = $this->slider_range($fullShop);
        $this->view->type_ads = $this->params->type_ads->toArray();
        $this->view->type_user = $this->params->type_user->toArray();
        $this->view->notfound = $this->params->label_not_found;
    }

    /**
     * categoryAction
     * ricerca per categorie
     *
     * @access public
     *
     * @return mixed Value.
     *
     */
    public function categoryAction()
    {

        /* recupero i parametri */
        $id = $this->_getParam('item', 0);
        $ads = $this->_getParam('ads', 0);
        $user = $this->_getParam('user', 0);

        $category = new Application_Model_DbTable_Category();

        /* tutte le info della categoria selezionata */
        $this->view->category = $category->getCategoryInfo($id);

        /* le sottocategorie della categoria madre*/
        $this->view->parent = $category->Parent_With_Category($id);

        /* le altre categorie escluso la categoria selezionata*/
        $this->view->other = $category->Other_Category($id);

        /**
         * creo l'array con la lista degli annunci
         * mostro a video+array()
         */
        $shop = new Application_Model_DbTable_Shop();
        $fullShop = $shop->fullShopFilter(
            array('type' => 'category', 'id' => $id, 'ads' => $ads, 'user' => $user)
        );
        
        $this->view->list = $fullShop;
        $this->view->range = $this->slider_range($fullShop);
        $this->view->type_ads = $this->params->type_ads->toArray();
        $this->view->type_user = $this->params->type_user->toArray();
        $this->view->notfound = $this->params->label_not_found;
    }

    /**
     * provinceAction
     * ricerca per provincia
     * risultato dal filtro della regione
     *
     * @access public
     *
     * @return mixed Value.
     *
     */
    public function provinceAction()
    {

        /* recupero i parametri */
        $id = $this->_getParam('item', 0);
        $parent = $this->_getParam('parent', 0);

        $region = new Application_Model_DbTable_Region();

        /* informazioni sulla regione */
        $this->view->region = $region->getRegionInfo($parent);

        /* le alre regioni escluso quella selezionata*/
        $this->view->other = $region->Other_Region($parent);

        $provinces = new Application_Model_DbTable_Provinces();

        /* informazioni sulla provincia*/
        $this->view->province = $provinces->getProvinceInfo($id);

        /* le altre province della regione*/
        $this->view->provinces = $provinces->Parent_Provinces($parent);

        /**
         * creo l'array con la lista degli annunci
         * mostro a video+array()
         */
        $shop = new Application_Model_DbTable_Shop();
        $this->view->list = $shop->fullShopFilter(array('type' => 'province', 'id' => $id));
        $this->view->type_ads = $this->params->type_ads->toArray();
        $this->view->type_user = $this->params->type_user->toArray();
        $this->view->notfound = $this->params->label_not_found;
    }

    /**
     * subcategoryAction
     * ricerca per le sottocategorie
     * risultato dal filtro della categoria madre
     *
     * @access public
     *
     * @return mixed Value.
     *
     */
    public function subcategoryAction()
    {

        /* recupero i parametri */
        $id = $this->_getParam('item', 0);
        $parent = $this->_getParam('parent', 0);

        $category = new Application_Model_DbTable_Category();

        /* informazioni sulla categoria madre */
        $this->view->category = $category->getCategoryInfo($id);
        $this->view->sub = $category->getCategoryInfo($parent);

        /* altre sottocategorie della categoria madre */
        $this->view->parent = $category->Parent_With_Category($id);

        /* le altre categorie escluso quella selezionata */
        $this->view->other = $category->Other_Category($id);

        /**
         * creo l'array con la lista degli annunci
         * mostro a video+array()
         */
        $shop = new Application_Model_DbTable_Shop();
        $this->view->list = $shop->fullShopFilter(array('type' => 'sub_category', 'id' => $parent));
        $this->view->type_ads = $this->params->type_ads->toArray();
        $this->view->type_user = $this->params->type_user->toArray();
        $this->view->notfound = $this->params->label_not_found;
    }

    /**
     * searchAction
     * ricerca singola / concatenata
     * regione + categoria + input
     *
     * @access public
     *
     * @return mixed Value.
     *
     */
    public function searchAction()
    {

        /* recupero i parametri */
        $q = $this->_getParam('q', 0);
        $category = $this->_getParam('category', 0);
        $region = $this->_getParam('region', 0);
        $type = $this->_getParam('type', 0);
        $ads = $this->_getParam('ads', 0);

        /**
         * scambio la modalità di ricerca
         * autocomplete-> tengo in considerazione i parametri dell' autocomplete
         * ed eseguo un redirect direttamente nella scheda del prodotto
         *
         * global-> applico i filtri di ricerca che possono essere
         * concatenati tra di loro / category / region / input
         */
        switch ($type) {

            case 'autocomplete':
                $this->_redirect(sprintf('/shop/ads/show/%d', $ads));
                break;

            case 'global':
                if ($this->getRequest()->getParams()) {
                    $shop = new Application_Model_DbTable_Shop();
                    $result = $shop->fullShopFilter(
                        array(
                             'type'     => $type,
                             'category' => $category,
                             'region'   => $region,
                             'q'        => $q
                        )
                    );

                    /* Zend_Paginator */
                    $page = $this->_getParam('page', 1);
                    $paginator = Zend_Paginator::factory($result);
                    $paginator->setItemCountPerPage(6);
                    $paginator->setCurrentPageNumber($page);

                    /**
                     * lista dei risultati
                     * totale degli annunci
                     * array()
                     */
                    $this->view->list = $paginator;
                    $this->view->total_list = $paginator->getTotalItemCount();
                    $this->view->type_ads = $this->params->type_ads->toArray();
                    $this->view->type_user = $this->params->type_user->toArray();
                    $this->view->notfound = $this->params->label_not_found;
                }
                break;
        }
    }

    public function mapsAction()
    {
        // action body
    }


}

