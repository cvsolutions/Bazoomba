<?php

class Application_Model_DbTable_Favorite extends Zend_Db_Table_Abstract
{

    protected $_name = 'ads_favorites';

    protected $_primary = 'id';


    public function addFavorite($user, $shop)
    {
        $arrayFavorite = array(
            'user' => $user,
            'shop' => $shop,
            'registered' => new Zend_Db_Expr('NOW()')
        );
        return $this->insert($arrayFavorite);
    }

    public function controlfavorite($user, $shop)
    {
        $query = $this->getDefaultAdapter()->select();
        $query->from( 'ads_favorites', 'COUNT(shop)' );
        $query->where( sprintf( "user = %d AND shop = %d", $user, $shop));
        return $this->getDefaultAdapter()->fetchOne( $query );
    }

    public function favoriteshop($id)
    {
        $query = $this->getDefaultAdapter()->select();
        $query->from(
            'ads_favorites', array(
                             'id',
                             'shop',
                             'user'
                        )
        );
        $query->joinLeft('ads_shop', 'ads_favorites.shop = ads_shop.id', array('title' => 'title', 'description' => 'description', 'price' => 'price', 'date' => 'registered'));
        $query->joinLeft('ads_gallery', 'ads_favorites.shop = ads_gallery.shop', array('photo' => 'image'));
        $query->order('ads_gallery.registered ASC');
        $query->group('ads_favorites.shop');
        $query->where(sprintf('ads_favorites.user = %d', $id));
        return $this->getDefaultAdapter()->fetchAll($query);
    }


        public function removefavorite( $id )
        {
        return $this->delete( 'id = ' . $id );
        }
}

