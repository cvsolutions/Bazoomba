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
     * getImageInfo
     *
     * @param mixed $id ID Image.
     *
     * @access public
     *
     * @return mixed Value.
     */
	public function getImageInfo($id)
	{
		$row = $this->fetchRow(sprintf('id = %d', $id));
		if(!$row)
		{
			$params = Plugin_Common::getParams();
			throw new Exception($params->label_no_id, 1);
		}
		return $row->toArray();
	}

    /**
     * updateStatusGallery
     *
     * @param mixed $id     ID Image.
     * @param mixed $status Se è Attivo / Sospeso.
     * @param mixed $shop   ID annuncio.
     *
     * @access public
     *
     * @return mixed Value.
     */
	public function updateStatusGallery($id, $status, $shop)
	{
		$arrayName = array('status' => $status);
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
	public function deleteImageGallery($id)
	{
		$row = $this->getImageInfo($id);
		if($row['image'] > 0) unlink(sprintf('%s/uploaded/ads/%s', $_SERVER['DOCUMENT_ROOT'], $row['image']));
		return $this->delete('id = ' . $id);
	}

     /**
     * addMedia
     *
     * @param mixed $id ID Image.
     *
     * @access public
     *
     * @return mixed Value.
     */
	public function addMedia($id, $image)
	{
		$arrayMedia = array(
                          'shop' => $id,
                          'image' => $image,
                          'status' => 1,
                );
                return $this->insert($arrayMedia);
	}


}

