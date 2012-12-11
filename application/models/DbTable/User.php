<?php

/**
 * Application_Model_DbTable_User
 *
 * @uses     Zend_Db_Table_Abstract
 *
 * @category User
 * @package  Bazoomba.it
 * @author   Concetto Vecchio
 * @license
 * @link
 */
class Application_Model_DbTable_User extends Zend_Db_Table_Abstract
{

    /**
     * $_name
     *
     * @var string
     *
     * @access protected
     */
    protected $_name = 'ads_user';

    /**
     * $_primary
     *
     * @var string
     *
     * @access protected
     */
    protected $_primary = 'id';

    /**
     * getAdminInfo
     *
     * @param mixed   $id Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getAdminInfo( $id ) {
        $row = $this->fetchRow( sprintf( "id = %d", $id ) );
        if ( !$row ) {
            $params = Plugin_Common::getParams();
            throw new Exception( $params->label_no_id, 1 );
        }
        return $row->toArray();
    }

    /**
     * getEmailInfo
     *
     * @param mixed   $email Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getEmailInfo( $email ) {
        $row = $this->fetchRow( sprintf( "email = '%s' AND status = 1", $email ) );
        if ( !$row ) {
            $params = Plugin_Common::getParams();
            throw new Exception( $params->label_no_id, 1 );
        }
        return $row->toArray();
    }


    /**
     * getSerialKeyInfo
     *
     * @param mixed   $id        Description.
     * @param mixed   $serialkey Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getSerialKeyInfo( $id, $serialkey ) {
        $row = $this->fetchRow( sprintf( "id = %d AND serialkey = '%s'", $id, $serialkey ) );
        if ( !$row ) {
            $params = Plugin_Common::getParams();
            throw new Exception( $params->label_no_id, 1 );
        }
        return $row->toArray();
    }

    /**
     * getInfoSerialKey
     *
     * @param mixed   $serialkey Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getInfoSerialKey( $serialkey ) {
        $row = $this->fetchRow( sprintf( "serialkey = '%s'", $serialkey ) );
        if ( !$row ) {
            $params = Plugin_Common::getParams();
            throw new Exception( $params->label_no_id, 1 );
        }
        return $row->toArray();
    }

    /**
     * updatePassword
     *
     * @param mixed   $id        Description.
     * @param mixed   $serialkey Description.
     * @param mixed   $pwd       Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updatePassword( $id, $serialkey, $pwd ) {
        $arrayName = array(
            'pwd' => sha1( $pwd ),
            'modified' => new Zend_Db_Expr( 'NOW()' ),
            'ip_address' => $_SERVER['REMOTE_ADDR']
        );
        return $this->update( $arrayName, sprintf( "id = %d AND serialkey = '%s'", $id, $serialkey ) );
    }

    /**
     * updateAdminUser
     *
     * @param mixed   $id     Description.
     * @param mixed   $type   Description.
     * @param mixed   $name   Description.
     * @param mixed   $status Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updateAdminUser( $id, $type, $name, $status ) {
        $arrayName = array(
            'type' => $type,
            'name' => $name,
            'status' => $status,
            'modified' => new Zend_Db_Expr( 'NOW()' ),
            'ip_address' => $_SERVER['REMOTE_ADDR']
        );
        return $this->update( $arrayName, sprintf( 'id = %d', $id ) );
    }

    /**
     * updateAvatar
     *
     * @param mixed   $id     Description.
     * @param mixed   $avatar Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updateAvatar( $id, $avatar ) {
        $arrayAvatar = array(
            'avatar' => $avatar,
            'modified' => new Zend_Db_Expr( 'NOW()' ),
            'ip_address' => $_SERVER['REMOTE_ADDR']
        );
        return $this->update( $arrayAvatar, sprintf( 'id = %d', $id ) );
    }

    /**
     * newUser
     *
     * @param mixed   $id           Description.
     * @param mixed   $type         Description.
     * @param mixed   $name         Description.
     * @param mixed   $email        Description.
     * @param mixed   $phone        Description.
     * @param mixed   $phone_show   Description.
     * @param mixed   $pwd          Description.
     * @param mixed   $serialkey    Description.
     * @param mixed   $iva          Description.
     * @param mixed   $name_company Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function newUser( $id, $type, $name, $email, $phone, $phone_show, $pwd, $serialkey, $iva, $name_company ) {
        $arrayNewUser = array(
            'id' => $id,
            'type' => $type,
            'name' => $name,
            'email' => $email,
            'telephone' => $phone,
            'phone_show'=> $phone_show,
            'pwd' => sha1( $pwd ),
            'serialkey' => $serialkey,
            'vat' => $iva,
            'name_company' => $name_company,
            'role' => 'user',
            'status' => 0,
            'newsletter' => 1,
            'registered' => new Zend_Db_Expr( 'NOW()' ),
            'modified' => new Zend_Db_Expr( 'NOW()' ),
            'last_login' => new Zend_Db_Expr( 'NOW()' ),
            'ip_address' => $_SERVER['REMOTE_ADDR']
        );
        return $this->insert( $arrayNewUser );
    }

    /**
     * confirmUser
     *
     * @param mixed   $serialkey Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function confirmUser( $serialkey ) {
        $arrayConfirm = array(
            'status' => 1,
            'modified' => new Zend_Db_Expr( 'NOW()' ),
            'ip_address' => $_SERVER['REMOTE_ADDR']
        );
        return $this->update( $arrayConfirm, sprintf( 'serialkey = %d', $serialkey ) );
    }

    /**
     * updateEditUser
     *
     * @param mixed   $id           Description.
     * @param mixed   $type         Description.
     * @param mixed   $name         Description.
     * @param mixed   $email        Description.
     * @param mixed   $phone        Description.
     * @param mixed   $phone_show   Description.
     * @param mixed   $iva          Description.
     * @param mixed   $name_company Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updateEditUser( $id, $type, $name, $email, $phone, $phone_show, $iva, $name_company ) {
        $arrayName = array(
            'type' => $type,
            'name' => $name,
            'email' => $email,
            'telephone' => $phone,
            'phone_show' => $phone_show,
            'vat' => $iva,
            'name_company' => $name_company,
            'modified' => new Zend_Db_Expr( 'NOW()' ),
            'ip_address' => $_SERVER['REMOTE_ADDR']
        );
        return $this->update( $arrayName, sprintf( 'id = %d', $id ) );
    }

    /**
     * updatePasswordUser
     *
     * @param mixed   $id  Description.
     * @param mixed   $pwd Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updatePasswordUser( $id, $pwd ) {
        $arrayName = array(
            'pwd' => sha1( $pwd ),
            'modified' => new Zend_Db_Expr( 'NOW()' ),
            'ip_address' => $_SERVER['REMOTE_ADDR']
        );
        return $this->update( $arrayName, sprintf( 'id = %d', $id ) );
    }

    /**
     * updateAccessUser
     *
     * @param mixed   $date Description.
     * @param mixed   $id   Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updateAccessUser( $date, $id ) {
        $arrayName = array(
            'last_login' => $date,
            'ip_address' => $_SERVER['REMOTE_ADDR']
        );
        return $this->update( $arrayName, sprintf( 'id = %d', $id ) );
    }

    /**
     * controlemail
     *
     * @param mixed   $email Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function controlemail( $email ) {
        $query = $this->getDefaultAdapter()->select();
        $query->from( 'ads_user', 'COUNT(email)' );
        $query->where( sprintf( "email = '%s' ", $email ) );
        return $this->getDefaultAdapter()->fetchOne( $query );
    }

}
