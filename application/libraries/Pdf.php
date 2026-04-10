<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf {
    public function __construct() {
        // Load TCPDF library
        if (!class_exists('TCPDF', FALSE)) {
            require_once(APPPATH . 'third_party/tcpdf/tcpdf.php');
        }
    }
    
    public function create() {
        return new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    }
}