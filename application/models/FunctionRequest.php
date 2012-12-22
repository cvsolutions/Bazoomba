<?php

/**
* Application_Model_FunctionRequest
*
* @uses     
*
* @category FunctionRequest
* @package  Bazoomba.it
* @author   Concetto Vecchio
* @license  
* @link     
*/
class Application_Model_FunctionRequest {

    /**
     * Create_TagsToString
     * 
     * @param mixed $words Tags.
     *
     * @access public
     *
     * @return mixed Value.
     */
	public function Create_TagsToString( $words ) {

		/* explode to words */
		$comma = explode( ',', $words );
		foreach ( $comma as $value ) {

			/* create new array */
			$point[] = trim( $value );
		}
		return join( '|', $point );
	}

}
