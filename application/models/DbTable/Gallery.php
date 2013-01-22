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
     * Recupero tutt le informazioni sulla singola photo
     *
     * @param mixed   $id ID image.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getImageInfo( $id ) {
        $row = $this->fetchRow( sprintf( 'id = %d', $id ) );
        if ( !$row ) {
            $params = Plugin_Common::getParams();
            throw new Exception( $params->label_no_id, 1 );
        }
        return $row->toArray();
    }

    /**
     * updateStatusGallery
     * Aggiorno lo stato della photo selezionata
     * 1-> Attiva / 2-> Sospesa
     *
     * @param mixed   $id     ID Image.
     * @param mixed   $status Stato.
     * @param mixed   $shop   ID ADS.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updateStatusGallery( $id, $status, $shop ) {
        $arrayName = array(
            'status' => $status,
            'modified' => new Zend_Db_Expr( 'NOW()' ),
            'ip_address' => $_SERVER['REMOTE_ADDR']
        );
        return $this->update( $arrayName, sprintf( 'id = %d AND shop = %d', $id, $shop ) );
    }

    /**
     * deleteImageGallery
     * Elimino la singola photo e il file dal server
     *
     * @param mixed   $id ID Image.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function deleteImageGallery( $id ) {
        $row = $this->getImageInfo( $id );
        if ( $row['image'] > 0 ) unlink( sprintf( '%s/uploaded/ads/%s', $_SERVER['DOCUMENT_ROOT'], $row['image'] ) );
        return $this->delete( 'id = ' . $id );
    }

    /**
     * deleteGalleryPage
     * Elimino tutte le photos dal server
     *
     * @param mixed   $id   ID Image.
     * @param mixed   $user ID User.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function deleteGalleryPage( $id, $user ) {
        $row = $this->getImageInfo( $id );
        if ( $row['image'] > 0 ) unlink( sprintf( '%s/uploaded/gallery/%s', $_SERVER['DOCUMENT_ROOT'], $row['image'] ) );
        return $this->delete( sprintf( 'id =  %d AND shop = %d AND page = 1', $id, $user ) );
    }

    /**
     * addMedia
     * inserimento nuova photo
     *
     * @param mixed   $id    ID Image.
     * @param mixed   $image Files.
     * @param mixed   $page  ID Page.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function addMedia( $id, $image, $title, $page ) {
        $arrayMedia = array(
            'shop' => $id,
            'image' => $image,
            'title' => $title,
            'status' => 1,
            'page' => $page,
            'registered' => new Zend_Db_Expr( 'NOW()' ),
            'ip_address' => $_SERVER['REMOTE_ADDR']
        );
        return $this->insert( $arrayMedia );
    }

    /**
     * galleryPage
     * Recupero tutte le photo della brand page
     *
     * @param mixed   $id ID ADS.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function galleryPage( $id ) {
        $row = $this->fetchAll( sprintf( 'shop = %d AND page = 1', $id ) );
        if ( !$row ) {
            $params = Plugin_Common::getParams();
            throw new Exception( $params->label_no_id, 1 );
        }
        return $row->toArray();
    }

    /**
     * Delete_Images_Suspended
     * Elimino tramite >Cron tutt le photos sospese
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function Delete_Images_Suspended() {
        return $this->delete( 'status = 0' );
    }

    /**
     * controlDeleteImage
     * Verifica se si hanno i permessi per eliminare foto
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function controlDeleteImage($photo, $ads)
    {
        $query = $this->getDefaultAdapter()->select();
        $query->from( 'ads_gallery', 'COUNT(id)' );
        $query->where( sprintf( "id = %d AND shop = %d", $photo, $ads));
        return $this->getDefaultAdapter()->fetchOne( $query );
    }

    public function Delete_Media($id)
    {
        $row = $this->getImageInfo( $id );
        if ( $row['image'] > 0 ) unlink( sprintf( '%s/uploaded/ads/%s', $_SERVER['DOCUMENT_ROOT'], $row['image'] ) );
        return $this->delete( 'id = ' . $id );
    }

    /**
     * Delete_Media_Ads
     * Crea un array di tutte le immagini dell'annuncio
     * e poi tramite ciclo elimina fisicamente e dal db le foto
     * Metodo richimato dall'utente quando decide di eliminare l'annuncio.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function Delete_Media_Ads($ads)
    {
        $query = $this->getDefaultAdapter()->select();
        $query->from(
            'ads_gallery', array(
                             'id',
                             'image'
                        )
        );
        $query->where(sprintf('ads_gallery.shop = %d', $ads));
        $img = $this->getDefaultAdapter()->fetchAll($query);

        foreach ($img as $key) {
            if ( $key['image'] > 0 ) unlink( sprintf( '%s/uploaded/ads/%s', $_SERVER['DOCUMENT_ROOT'], $key['image'] ) );
        }
        return $this->delete( 'shop = ' . $ads );
    }



}
