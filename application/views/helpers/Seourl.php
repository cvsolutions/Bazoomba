<?php

/**
 * Seo url friendly
 *
 * @author Concetto Vecchio
 */
class Zend_View_Helper_Seourl extends Zend_View_Helper_Abstract
{
    /**
     * @param        $string Title to friendly URL conversion
     * @param string $divider Return good words separated by dashes
     *
     * @return string
     */
    public function seourl($string, $divider = '_')
    {
        $text = preg_replace('/\W+/', $divider, $string);
        return strtolower(trim($text, $divider));
    }

}