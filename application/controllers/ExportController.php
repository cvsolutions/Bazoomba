<?php

class ExportController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function adsAction()
    {
        $id = $this->_getParam('id', 0);

        $ads = new Application_Model_DbTable_Shop();
        $row = $ads->getSiteShopInfo($id);

        $pdf = new Zend_Pdf();
        $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);

        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);

        $page->setFont($font, 24)->drawText($row['title'], 5, 600);

        // add page to document
        $pdf->pages[] = $page;

        // instruct browser to download the PDF
        header("Content-Type: application/x-pdf");
        header(sprintf('Content-Disposition: attachment; filename=ads-%s.pdf', time()));
        header("Cache-Control: no-cache, must-revalidate");

        // output the PDF
        echo $pdf->render();
    }


}



