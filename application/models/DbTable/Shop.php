<?php

class Application_Model_DbTable_Shop extends Zend_Db_Table_Abstract
{

    protected $_name = 'ads_shop';

    protected $_primary = 'id';

    public function getAdminShopInfo( $id ) {
        $row = $this->fetchRow( sprintf( 'id = %d', $id ) );
        if ( !$row ) {
            $params = Plugin_Common::getParams();
            throw new Exception( $params->label_no_id, 1 );
        }
        return $row->toArray();
    }

    public function getSiteShopInfo( $id ) {
        $row = $this->fetchRow( sprintf( 'id = %d AND status = 1', $id ) );
        if ( !$row ) {
            $params = Plugin_Common::getParams();
            throw new Exception( $params->label_no_id, 1 );
        }
        return $row->toArray();
    }

    public function fullShop( $params = array() ) {
        $query = $this->getDefaultAdapter()->select();
        $query->from( 'ads_shop', array(
                'id',
                'code',
                'type',
                'title',
                'price',
                'status',
                'registered',
                'modified'
            ) );
        $query->join( 'ads_category', 'ads_shop.category = ads_category.id', array( 'category' => 'name' ) );
        $query->join( 'ads_region', 'ads_shop.region = ads_region.id', array( 'region' => 'name' ) );
        $query->join( 'ads_user', 'ads_shop.user = ads_user.id', array( 'user' => 'name' ) );

        if ( !empty( $params['search'] ) && $params['search'] != 'item' ) {
            $query->where( sprintf( 'ads_shop.%s = %d', $params['search'], $params['q'] ) );
        }

        if ( !empty( $params['search'] ) && $params['search'] == 'item' ) {
            $query->where( sprintf( "MATCH(ads_shop.title, ads_shop.description, ads_shop.tags) AGAINST('+%s*' IN BOOLEAN MODE)", str_replace( ' ', ' +', $params['q'] ) ) );

        }

        $query->order( 'registered DESC' );
        // echo $query->assemble();
        return $this->getDefaultAdapter()->fetchAll( $query );
    }

    public function LastInsertAdminShop() {
        $query = $this->getDefaultAdapter()->select();
        $query->from( 'ads_shop', array(
                'id',
                'title',
                'registered'
            ) );
        $query->join( 'ads_user', 'ads_shop.user = ads_user.id', array( 'user' => 'name' ) );
        $query->order( 'ads_shop.registered DESC' );
        $query->limit( '0, 10' );
        // echo $query->assemble();
        return $this->getDefaultAdapter()->fetchAll( $query );
    }

    public function LastEditAdminShop() {
        $query = $this->getDefaultAdapter()->select();
        $query->from( 'ads_shop', array(
                'id',
                'title',
                'status',
                'modified'
            ) );
        $query->join( 'ads_user', 'ads_shop.user = ads_user.id', array( 'user' => 'name' ) );
        $query->where( 'ads_shop.modified >= CURDATE()' );
        $query->order( 'ads_shop.registered DESC' );
        $query->limit( '0, 10' );
        return $this->getDefaultAdapter()->fetchAll( $query );
    }

    public function LastHomeShop() {
        $query = $this->getDefaultAdapter()->select();
        $query->from( 'ads_shop', array(
                'id',
                'type',
                'title',
                'description',
                'category',
                'region',
                'price',
                'registered'
            ) );
        $query->join( 'ads_category', 'ads_shop.category = ads_category.id', array( 'name_category' => 'name' ) );
        $query->join( 'ads_region', 'ads_shop.region = ads_region.id', array( 'name_region' => 'name' ) );
        $query->join( 'ads_user', 'ads_shop.user = ads_user.id', array( 'user' => 'name', 'type_user' => 'type' ) );
        $query->joinLeft( 'ads_gallery', 'ads_shop.id = ads_gallery.shop', array( 'photo' => 'image' ) );
        // $query->where(sprintf("TRUNCATE ( 6363 * sqrt( POW( RADIANS('%s') - RADIANS(ads_shop.latitude) , 2 ) + POW( RADIANS('%s') - RADIANS(ads_shop.longitude) , 2 ) ) , 3 ) < 10", $latitude, $longitude));
        $query->where( 'ads_shop.status = 1' );
        $query->where( 'ads_user.status = 1' );
        $query->where( 'ads_gallery.status = 1' );
        $query->group( 'ads_shop.id' );
        $query->order( 'ads_shop.registered DESC' );
        $query->limit( '0, 10' );
        // echo $query->assemble();
        return $this->getDefaultAdapter()->fetchAll( $query );
    }

    public function RandomGeoIPShop( $ads, $latitude, $longitude ) {
        $query = $this->getDefaultAdapter()->select();
        $query->from( 'ads_shop', array(
                'id',
                'type',
                'title',
                'description',
                'category',
                'region',
                'price',
                'registered'
            ) );
        $query->join( 'ads_category', 'ads_shop.category = ads_category.id', array( 'category' => 'name' ) );
        $query->join( 'ads_provinces', 'ads_shop.province = ads_provinces.id', array( 'province' => 'name' ) );
        $query->join( 'ads_region', 'ads_shop.region = ads_region.id', array( 'region' => 'name' ) );
        $query->join( 'ads_user', 'ads_shop.user = ads_user.id', array( 'user' => 'name', 'type_user' => 'type' ) );
        $query->joinLeft( 'ads_gallery', 'ads_shop.id = ads_gallery.shop', array( 'photo' => 'image' ) );
        $query->where( sprintf( "TRUNCATE ( 6363 * sqrt( POW( RADIANS('%s') - RADIANS(ads_shop.latitude) , 2 ) + POW( RADIANS('%s') - RADIANS(ads_shop.longitude) , 2 ) ) , 3 ) < 10", $latitude, $longitude ) );
        $query->where( sprintf( 'ads_shop.id != %d', $ads ) );
        $query->where( 'ads_shop.status = 1' );
        $query->where( 'ads_user.status = 1' );
        $query->where( 'ads_gallery.status = 1' );
        $query->order( 'registered DESC' );
        $query->group( 'ads_shop.id' );
        $query->limit( '0, 10' );
        // echo $query->assemble();
        return $this->getDefaultAdapter()->fetchAll( $query );
    }

    public function fullShopFilter( $params = array() ) {
        $query = $this->getDefaultAdapter()->select();
        $query->from( 'ads_shop', array(
                'id',
                'type',
                'title',
                'description',
                'price',
                'registered'
            ) );
        $query->join( 'ads_category', 'ads_shop.category = ads_category.id', array( 'category' => 'name' ) );
        $query->join( 'ads_provinces', 'ads_shop.province = ads_provinces.id', array( 'province' => 'name' ) );
        $query->join( 'ads_region', 'ads_shop.region = ads_region.id', array( 'region' => 'name' ) );
        $query->join( 'ads_user', 'ads_shop.user = ads_user.id', array( 'user' => 'name', 'type_user' => 'type' ) );
        $query->joinLeft( 'ads_gallery', 'ads_shop.id = ads_gallery.shop', array( 'photo' => 'image' ) );

        switch ( $params['type'] ) {

        case 'global':
            if ( $params['category'] ) $query->where( sprintf( 'ads_shop.category = %d', $params['category'] ) );
            if ( $params['region'] ) $query->where( sprintf( 'ads_shop.region = %d', $params['region'] ) );
            if ( $params['q'] ) $query->where( sprintf( "MATCH(ads_shop.title, ads_shop.description, ads_shop.tags) AGAINST('+%s*' IN BOOLEAN MODE)", str_replace( ' ', ' +', $params['q'] ) ) );
            break;

        case 'category':
            if ( $params['ads'] ) $query->where( sprintf( 'ads_shop.type = %d', $params['ads'] ) );
            if ( $params['user'] ) $query->where( sprintf( 'ads_user.type = %d', $params['user'] ) );
            $query->where( sprintf( 'ads_shop.category = %d', $params['id'] ) );
            break;

        case 'sub_category':
            $query->where( sprintf( 'ads_shop.sub_category = %d', $params['id'] ) );
            break;

        case 'region':
            if ( $params['ads'] ) $query->where( sprintf( 'ads_shop.type = %d', $params['ads'] ) );
            if ( $params['user'] ) $query->where( sprintf( 'ads_user.type = %d', $params['user'] ) );
            $query->where( sprintf( 'ads_shop.region = %d', $params['id'] ) );
            break;

        case 'province':
            $query->where( sprintf( 'ads_shop.province = %d', $params['id'] ) );
            break;
        }

        $query->where( 'ads_shop.status = 1' );
        $query->where( 'ads_user.status = 1' );
        $query->where( 'ads_gallery.status = 1' );
        $query->group( 'ads_shop.id' );
        $query->order( 'ads_shop.registered DESC' );
        // echo $query->assemble();
        return $this->getDefaultAdapter()->fetchAll( $query );
    }


    public function updateShopAdmin( $id, $category, $sub_category, $region, $province, $city, $type, $title, $price, $description, $status ) {
        $arrayName = array(
            'category' => $category,
            'sub_category' => $sub_category,
            'region' => $region,
            'province' => $province,
            'city' => $city,
            'type' => $type,
            'title' => $title,
            'price' => $price,
            'description' => $description,
            'status' => $status,
            'modified' => time(),
            'ip_address' => $_SERVER['REMOTE_ADDR']
        );
        return $this->update( $arrayName, sprintf( 'id = %d', $id ) );
    }


    public function updateStep( $id, $status ) {
        $arrayName = array(
            'step' => $status,
            'modified' => time(),
            'ip_address' => $_SERVER['REMOTE_ADDR']
        );
        return $this->update( $arrayName, sprintf( 'id = %d', $id ) );
    }


    public function newShop( $id, $category, $sub_category, $region, $province, $city, $type, $title, $description, $tags, $price, $latitude, $longitude ) {
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getStorage()->read();

        $arrayNewShop = array(
            'id' => $id,
            'user' => $identity->id,
            'code' => strtoupper( Plugin_Common::getRandom( 6 ) ),
            'category' => $category,
            'sub_category' => $sub_category,
            'region' => $region,
            'province' => $province,
            'city'=> $city,
            'type'=> $type,
            'title' => $title,
            'description' => $description,
            'tags' => $tags,
            'price' => $price,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'registered' => time(),
            'computer' => $_SERVER['HTTP_USER_AGENT'],
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'status' => 0,
            'terms' => 1,
            'step' => 1
        );
        return $this->insert( $arrayNewShop );
    }


    public function controlAds( $id_ads ) {
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getStorage()->read();

        $query = $this->getDefaultAdapter()->select();
        $query->from( 'ads_shop', array(
                'id',
                'user'
            ) );
        $query->where( 'user = ?', $identity->id );
        $query->where( 'id = ?', $id_ads );
        $query->where( 'status = 0' );
        return $this->getDefaultAdapter()->fetchAll( $query );
    }


    public function myshop( $id ) {
        $query = $this->getDefaultAdapter()->select();
        $query->from( 'ads_shop', array(
                'id',
                'type',
                'title',
                'description',
                'price',
                'registered',
                'status',
                'step'
            ) );
        $query->joinLeft( 'ads_gallery', 'ads_shop.id = ads_gallery.shop', array( 'photo' => 'image' ) );
        $query->where( sprintf( 'ads_shop.user = %d', $id ) );
        $query->group( 'ads_shop.id' );
        return $this->getDefaultAdapter()->fetchAll( $query );
    }


}
