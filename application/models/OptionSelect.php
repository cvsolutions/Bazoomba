<?php

/**
 * Application_Model_OptionSelect
 *
 * @uses
 *
 * @category HTML Select
 * @package  Bazoomba.it
 * @author   Concetto Vecchio
 * @license
 * @link
 */
class Application_Model_OptionSelect
{

    /**
     * appendStatus
     * Array con gli stati
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function appendStatus()
    {
        $params = Plugin_Common::getParams();
        return $params->status->toArray();
    }

    /**
     * appendTypeUser
     * Array con la tipologia di account
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function appendTypeUser()
    {
        $params = Plugin_Common::getParams();
        return $params->type_user->toArray();
    }

    /**
     * appendTypeAds
     * Array con la tipologia di annunci
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function appendTypeAds()
    {
        $params = Plugin_Common::getParams();
        return $params->type_ads->toArray();
    }

    /**
     * appendVideo
     * Array con i video più conosciuti
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function appendVideo()
    {
        $params = Plugin_Common::getParams();
        return $params->video->toArray();
    }

    /**
     * appendParentCategory
     * Array con tutte le categorie
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function appendParentCategory()
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select();
        $select->from('ads_category', array('id', 'name'));
        $select->where('parent = 0');
        $select->order('name ASC');
        $result = $db->fetchAll($select);
        $arrayName[0] = '-';
        foreach ($result as $row) {
            $arrayName[$row['id']] = $row['name'];
        }
        return $arrayName;
    }

    /**
     * appendSubCategory
     * Array con tutte le sotto categorie
     * per la categoria selezionata
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function appendSubCategory($category = null)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select();
        $select->from('ads_category', array('id', 'name'));
        $select->order('name ASC');
        if (isset($category)) {
            $select->where(sprintf('parent = %d', $category));
        } else {
            $select->where('parent != 0');
        }
        $result = $db->fetchAll($select);
        $arrayName[0] = '-';
        foreach ($result as $row) {
            $arrayName[$row['id']] = $row['name'];
        }
        return $arrayName;
    }

    /**
     * appendRegion
     * Array con tutte le regioni
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function appendRegion()
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select();
        $select->from('ads_region', array('id', 'name'));
        $result = $db->fetchAll($select);
        $arrayName[0] = '-';
        foreach ($result as $row) {
            $arrayName[$row['id']] = $row['name'];
        }
        return $arrayName;
    }

    /**
     * appendProvinces
     * Array con tutte le province della regione
     *
     * @param mixed   $region ID Regione.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function appendProvinces($region = null)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select();
        $select->from('ads_provinces', array('id', 'name'));
        if ($region) {
            $select->where('region = ?', $region);
        }
        $result = $db->fetchAll($select);
        $arrayName[0] = '-';
        foreach ($result as $row) {
            $arrayName[$row['id']] = $row['name'];
        }
        return $arrayName;
    }

    /**
     * appendCity
     * Array con tutte le città di ogni provincia
     *
     * @param mixed   $region    ID Regione.
     * @param mixed   $provinces ID Provincia.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function appendCity($region = null, $provinces = null)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select();
        $select->from('ads_city', array('id', 'name'));
        if ($region) {
            $select->where('region = ?', $region);
        }
        if ($provinces) {
            $select->where('provinces = ?', $provinces);
        }
        $result = $db->fetchAll($select);
        $arrayName[0] = '-';
        foreach ($result as $row) {
            $arrayName[$row['id']] = $row['name'];
        }
        return $arrayName;
    }

    /**
     * Array con tutte le regioni
     * che hanno gli annunci attivi
     *
     * @return mixed
     */
    public function RegionByAds()
    {
        $db = Zend_Db_Table::getDefaultAdapter();

        $query = $db->select();
        $query->from('ads_region', array('id', 'name'));
        $query->join('ads_shop', 'ads_shop.region = ads_region.id', array('id_ads' => 'id'));
        $query->where('ads_shop.status = 1');
        $query->where('ads_shop.expiration >= CURDATE()');
        $query->group('ads_region.id');
        $query->order('ads_region.name ASC');
        // echo $query->assemble();

        $result = $db->fetchAll($query);
        foreach ($result as $row) {
            $arrayName[$row['id']] = $row['name'];
        }
        // print_r($arrayName);
        return $arrayName;
    }

}
