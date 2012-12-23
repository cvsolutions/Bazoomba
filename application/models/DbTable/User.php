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
     * Recupero tutte le informazioni sull' account user
     * da usare solo per il superadmin
     *
     * @param mixed   $id ID User.
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
     * Recupero tutte le informazioni user
     * tramite l'indirizzo email e l'account deve essere attivo
     *
     * @param mixed   $email indirizzo E-mail.
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
     * Recupero le informazioni sull'account
     * faccio una verifica sul token serialkey
     *
     * @param mixed   $id        ID User.
     * @param mixed   $serialkey Token serialkey.
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
     * Recupero le informazioni sull'account
     * Verifico il Token serialkey
     *
     * @param mixed   $serialkey Token serialkey.
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
     * Modifico la password per l'account selezionato
     *
     * @param mixed   $id        ID User.
     * @param mixed   $serialkey Token di sicurezza.
     * @param mixed   $pwd       New Password.
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
     * Aggiornamento account con privilieggi
     * di superadmin
     *
     * @param mixed   $id     ID User.
     * @param mixed   $type   Tipologia di account.
     * @param mixed   $name   Nome & Cognome.
     * @param mixed   $status Stato account.
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
     * Aggiornamento image Avatar
     *
     * @param mixed   $id     ID User.
     * @param mixed   $avatar File Image.
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
     * Inserimento nuovo user
     *
     * @param mixed   $id           ID User.
     * @param mixed   $type         Tipologia account.
     * @param mixed   $name         Nome & Cognome.
     * @param mixed   $email        Indirizzo Email.
     * @param mixed   $phone        Telefono.
     * @param mixed   $phone_show   Mostra il telefono sul sito.
     * @param mixed   $pwd          Password.
     * @param mixed   $serialkey    Token di sicurezza.
     * @param mixed   $iva          Partita IVA.
     * @param mixed   $name_company Ragione sociale.
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
     * Verifico tramite link sulla email
     * e attivo l'account
     *
     * @param mixed   $serialkey Token Serialkey.
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
     * Aggiornamento profilo account
     *
     * @param mixed   $id           ID User.
     * @param mixed   $type         Tipologia account.
     * @param mixed   $name         Nome & Cognome.
     * @param mixed   $email        Indirizzo Email.
     * @param mixed   $phone        Telefono.
     * @param mixed   $phone_show   Mostra il telefono sul sito.
     * @param mixed   $iva          Partita IVA.
     * @param mixed   $name_company Ragione sociale.
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
     * Aggiornamento New password
     *
     * @param mixed   $id  ID User.
     * @param mixed   $pwd New Password.
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
     * Aggiorno la data di accesso al sistema
     *
     * @param mixed   $date Last Date.
     * @param mixed   $id   ID User.
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
     * Verifico che l'indirizzo email
     * non esiste sul database / ajax
     *
     * @param mixed   $email UserMail.
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

    /**
     * Delete_Cron_Suspended
     * Elimino tutti gli account NON attivi
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function Delete_Account_Suspended() {
        return $this->delete( 'status = 0' );
    }

}
