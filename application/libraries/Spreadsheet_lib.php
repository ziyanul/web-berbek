<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// require_once APPPATH.'vendor/phpoffice/phpspreadsheet/src/Bootstrap.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Spreadsheet_lib { 
    public function __construct() {
        // Initialize the PhpSpreadsheet library
    }

    public function createSpreadsheet() {
        $spreadsheet = new Spreadsheet();
        // Create and modify your spreadsheet here
        return $spreadsheet;
    }

    public function saveSpreadsheet($spreadsheet, $filename) {
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($filename);
    }
}
