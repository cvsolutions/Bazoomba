<?php

/**
 * Application_Model_DbTable_Category
 *
 * @uses     Zend_Db_Table_Abstract
 *
 * @category Category
 * @package  Bazoomba.it
 * @author   Concetto Vecchio
 * @license
 * @link
 */
class Application_Model_DbTable_Category extends Zend_Db_Table_Abstract
{
    /**
     * $_name
     *
     * @var string
     *
     * @access protected
     */
    protected $_name = 'ads_category';


    /**
     * $_primary
     *
     * @var string
     *
     * @access protected
     */
    protected $_primary = 'id';

    /**
     * appendParentName
     * Recupero le informazioni sulla sottocategoria
     *
     * @param mixed   $parent ID sotto categoria.
     *
     * @access private
     *
     * @return mixed Value.
     */
    private function appendParentName( $parent ) {
        $row = $this->fetchRow( sprintf( 'id = %d', $parent ) );
        return $row['name'];
    }

    /**
     * getCategoryInfo
     * Tutte le informazioni sulla categoria
     *
     * @param mixed   $id ID della categoria.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getCategoryInfo( $id ) {
        $row = $this->fetchRow( sprintf( 'id = %d', $id ) );
        if ( !$row ) {
            $params = Plugin_Common::getParams();
            throw new Exception( $params->label_no_id, 1 );
        }
        return $row->toArray();
    }

    /**
     * full_List
     * La lista di tutte le categoria attive
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function full_List() {
        $category = $this->fetchAll();
        $arrayName = array();
        foreach ( $category as $row ) {
            $arrayName[] = array(
                'id' => $row->id,
                'name' => $row->name,
                'parent' => $row->parent == 0 ? 1 : 0,
                'sub_name' => $this->appendParentName( $row->parent )
            );
        }
        return $arrayName;
    }

    /**
     * Parent_With_Category
     * Solo le sottocategoria della catagoria selezionata
     *
     * @param mixed   $parent ID sotto categoria.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function Parent_With_Category( $parent ) {
        return $this->fetchAll( sprintf( 'parent = %d', $parent ) );
    }

    /**
     * Other_Category
     * Tutte le categorie escluso quella selezionata
     *
     * @param mixed   $id ID categoria.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function Other_Category( $id ) {
        return $this->fetchAll( sprintf( 'id != %d AND parent = 0', $id ) );
    }

    /**
     * inserNewCategory
     * Inserimento nuova categoria nel DB
     *
     * @param mixed   $id     ID della categoria.
     * @param mixed   $name   Nome.
     * @param mixed   $image  File Image.
     * @param mixed   $parent ID sottocategoria.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function inserNewCategory( $id, $name, $image, $parent ) {
        $arrayName = array(
            'id' => $id,
            'name' => $name,
            'image' => $image,
            'parent' => $parent
        );
        return $this->insert( $arrayName );
    }

    /**
     * updateCategory
     * Modifico la categoria selezionata
     *
     * @param mixed   $id     ID della Categoria.
     * @param mixed   $name   Nome.
     * @param mixed   $image  File Image.
     * @param mixed   $parent ID sottocategoria.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function updateCategory( $id, $name, $image, $parent ) {
        $arrayName = array(
            'name' => $name,
            'image' => $image,
            'parent' => $parent
        );
        return $this->update( $arrayName, sprintf( 'id = %d', $id ) );
    }

    /**
     * deleteCategory
     * Elimino la categoria / files dal server
     *
     * @param mixed   $id ID della Categoria.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function deleteCategory( $id ) {
        $row = $this->getCategoryInfo( $id );
        if ( $row['image'] > 0 ) unlink( sprintf( '%s/uploaded/category/%s', $_SERVER['DOCUMENT_ROOT'], $row['image'] ) );
        return $this->delete( 'id = ' . $id );
    }


}
