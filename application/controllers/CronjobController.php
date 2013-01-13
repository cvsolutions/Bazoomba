<?php

class CronjobController extends Zend_Controller_Action
{
    /**
     * @var
     */
    public $params;

    public function init()
    {
        /* disable Layout */
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        /* load common params */
        $this->params = Plugin_Common::getParams();
    }

    public function indexAction()
    {
        // action body
    }

    public function expirationAction()
    {
        /**
         * @todo eliminazione degli annunci in scadenza
         *
         * 1) expiration = date()
         */
    }

    public function eliminatesAction()
    {
        /**
         * @todo eliminazione dei record inutilizzati sul database
         *
         * 1) User: status = 0
         * 2) ADS: status = 1 & step != 3
         * 3) Gallery = status = 0
         * 4) request = status = 0
         */
    }

    /**
     * ADS Jobs
     * Cerca tra tutte le offerte di annunci in Italia.
     */
    public function remembernewadsAction()
    {
        /** @var $Request */
        $Request = new Application_Model_DbTable_Request();

        /** @var $result la lista di tutte le richieste */
        $result = $Request->List_Request();

        $Shop = new Application_Model_DbTable_Shop();

        if (is_array($result)) {
            foreach ($result as $row) {

                /** @var $listADS Tutti gli annunci trovati */
                $listADS = $Shop->List_Ads_Request($row['tags'], $row['category'], $row['region']);
                print_r($listADS);

                if ($listADS) {

                    /**
                     * invio la mail ai clienti
                     * solo se il sistema ha trovato gli annunci richiesti
                     */
                    Plugin_Common::getMail(
                        array(
                             'email'    => $row['email'],
                             'reply'    => $this->params->noreplay,
                             'subject'  => 'Nuovi Annunci su Bazoomba.it',
                             'template' => 'shop_remember_new_ads.phtml',
                             'params'   => array(
                                 'user'     => $row['name'],
                                 'type_ads' => $this->params->type_ads->toArray(),
                                 'ads'      => $listADS
                             )
                        )
                    );
                }
            }
        }

        //print_r($result);
    }


}
