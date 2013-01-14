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

    public function controlfavorite($user, $shop) {
        $query = $this->getDefaultAdapter()->select();
        $query->from( 'ads_favorites', 'COUNT(shop)' );
        $query->where( sprintf( "user = %d AND shop = %d", $user, $shop));
        return $this->getDefaultAdapter()->fetchOne( $query );
    }
}

