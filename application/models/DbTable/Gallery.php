<?php

/**
* Application_Model_DbTable_Gallery
*
* @uses     Zend_Db_Table_Abstract
*
* @category Gallery
* @package  Bazoomba.it
* @author   Concetto Vecchio
* @license  
* @link     
*/
class Application_Model_DbTable_Gallery extends Zend_Db_Table_Abstract
{

    /**
     * $_name
     *
     * @var string
     *
     * @access protected
     */
    protected $_name = 'ads_gallery';

    /**
     * $_primary
     *
     * @var string
     *
     * @access protected
     */
    protected $_primary = 'id';

    /**
     * getImageInfo
     * 
     * @param mixed $id ID image.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getImageInfo($id) {
        $row = $this->fetchRow(sprintf('id = %d', $id));
        if (!$row) {
            $params = Plugin_Common::getParams();
            throw new Exception($params->label_no_id, 1);
        }
        return $row->toArray();
    }

    /**
     * updateStatusGallery
     * 
     * @param mixed $id     ID Image.
     * @param mixed $status Stato.
     * @param mixed $shop   ID ADS.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updateStatusGallery($id, $status, $shop) {
        $arrayName = array(
            'status' => $status,
            'modified' => new Zend_Db_Expr('NOW()'),
            'ip_address' => $_SERVER['REMOTE_ADDR']
        );
        return $this->update($arrayName, sprintf('id = %d AND shop = %d', $id, $shop));
    }

    /**
     * deleteImageGallery
     * 
     * @param mixed $id ID Image.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function deleteImageGallery($id) {
        $row = $this->getImageInfo($id);
        if ($row['image'] > 0) unlink(sprintf('%s/uploaded/ads/%s', $_SERVER['DOCUMENT_ROOT'], $row['image']));
        return $this->delete('id = ' . $id);
    }

    /**
     * deleteGalleryPage
     * 
     * @param mixed $id   ID Image.
     * @param mixed $user ID User.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function deleteGalleryPage($id, $user) {
        $row = $this->getImageInfo($id);
        if ($row['image'] > 0) unlink(sprintf('%s/uploaded/gallery/%s', $_SERVER['DOCUMENT_ROOT'], $row['image']));
        return $this->delete(sprintf('id =  %d AND shop = %d AND page = 1', $id, $user));
    }

    /**
     * addMedia
     * 
     * @param mixed $id    ID Image.
     * @param mixed $image Files.
     * @param mixed $page  ID Page.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function addMedia($id, $image, $page) {
        $arrayMedia = array(
            'shop' => $id,
            'image' => $image,
            'status' => 1,
            'page' => $page,
            'registered' => new Zend_Db_Expr('NOW()'),
            'ip_address' => $_SERVER['REMOTE_ADDR']
        );
        return $this->insert($arrayMedia);
    }

    /**
     * galleryPage
     * 
     * @param mixed $id ID ADS.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function galleryPage($id) {
        $row = $this->fetchAll(sprintf('shop = %d AND page = 1', $id));
        if (!$row) {
            $params = Plugin_Common::getParams();
            throw new Exception($params->label_no_id, 1);
        }
        return $row->toArray();
    }


}
