<?php

/**
 * Application_Model_DbTable_Request
 *
 * @uses     Zend_Db_Table_Abstract
 *
 * @category Request
 * @package  Bazoomba.it
 * @author   Concetto Vecchio
 * @license
 * @link
 */
class Application_Model_DbTable_Request extends Zend_Db_Table_Abstract
{

    /**
     * $_name
     *
     * @var string
     *
     * @access protected
     */
    protected $_name = 'ads_request';

    /**
     * $_primary
     *
     * @var string
     *
     * @access protected
     */
    protected $_primary = 'id';

    /**
     * New_Request
     * Inserimento nuova richiesta
     *
     * @param mixed $name     Nome & Cognome.
     * @param mixed $email    Indirizzo Email.
     * @param mixed $category Categoria.
     * @param mixed $region   Regione.
     * @param mixed $tags     Parole chiavi.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function New_Request($name, $email, $category, $region, $tags)
    {
        $arrayNewUser = array(
            'id'         => rand(11111, 99999),
            'token'      => md5(uniqid(rand(), true)),
            'name'       => $name,
            'email'      => $email,
            'category'   => $category,
            'region'     => $region,
            'tags'       => $tags,
            'registered' => new Zend_Db_Expr('NOW()'),
            'status'     => 1,
            'ip_address' => $_SERVER['REMOTE_ADDR']
        );
        return $this->insert($arrayNewUser);
    }

    /**
     * Delete_Request_Suspended
     * Elimino tutte le richieste NON più attive
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function Delete_Request_Suspended()
    {
        return $this->delete('status = 0');
    }

    /**
     * Tutte le richieste fatte dagli utenti
     *
     * @return array
     */
    public function List_Request()
    {
        $query = $this->getDefaultAdapter()->select();
        $query->from(
            'ads_request', array(
                                'name',
                                'email',
                                'category',
                                'region',
                                'token',
                                'tags'
                           )
        );
        $query->where('status = 1');
        // echo $query->assemble();
        return $this->getDefaultAdapter()->fetchAll($query);
    }

    /**
     * Verifica sul Token di sicurezza
     * per accedere alla lista delle richieste
     *
     * @param $email Indirizzo email
     * @param $token Token di sicurezza
     *
     * @return array
     * @throws Exception
     */
    public function Check_Token_Request($email, $token)
    {
        $row = $this->fetchRow(sprintf("email = '%s' AND token = '%s'", $email, $token));
        if (!$row) {
            $params = Plugin_Common::getParams();
            throw new Exception($params->label_no_id, 1);
        }
        return $row->toArray();
    }

    /**
     * Recupero la lista delle richieste
     * fatte dal cliente con una determinata e-mail
     *
     * @param $email Indirizzo email
     *
     * @return array
     */
    public function List_Token_Request($email)
    {
        $query = $this->getDefaultAdapter()->select();
        $query->from(
            'ads_request', array(
                                'id',
                                'registered',
                                'status',
                                'tags'
                           )
        );
        $query->join('ads_category', 'ads_request.category = ads_category.id', array('name_category' => 'name'));
        $query->join('ads_region', 'ads_request.region = ads_region.id', array('name_region' => 'name'));
        $query->where(sprintf("email = '%s'", $email));
        // echo $query->assemble();
        return $this->getDefaultAdapter()->fetchAll($query);
    }

    /**
     * Dettaglio Completo della richiesta
     *
     * @param $id ID della richiesta
     *
     * @return array
     * @throws Exception
     */
    public function Info_Request($id)
    {
        $row = $this->fetchRow(sprintf('id = %d', $id));
        if (!$row) {
            $params = Plugin_Common::getParams();
            throw new Exception($params->label_no_id, 1);
        }
        return $row->toArray();
    }

    /**
     * Aggiorno lo stato della richiesta
     *
     * @param $id     ID della richiesta
     * @param $status Stato
     *
     * @return int
     */
    public function Update_Status_Request($id, $status)
    {
        $arrayName = array(
            'status' => $status
        );
        return $this->update($arrayName, sprintf('id = %d', $id));
    }

    /**
     * Elimino la richiesta
     *
     * @param $id ID della richiesta
     *
     * @return int
     */
    public function Delete_Request($id)
    {
        return $this->delete(sprintf('id = %d', $id));
    }

}
