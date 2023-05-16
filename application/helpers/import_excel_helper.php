<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (!function_exists('readExcel')) {
    function readExcel($files, $filesPath, $maxAllowSize = 8388608)
    {
        $name = $files["name"];
        $tmp_name = $files["tmp_name"];
        $error = $files["error"];
        $size = $files["size"];
        $type = $files["type"];

        $allowedFileType = [
            'application/vnd.ms-excel',
            'text/xls',
            'text/xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ];

        // 1st : check files type, only excel file are accepted
        if (in_array($type, $allowedFileType)) {

            // 2nd : check file size
            if ($size < $maxAllowSize) {
                if (file_exists($filesPath)) {

                    /**  Identify the type of $inputFileName  **/
                    $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($filesPath);
                    /**  Create a new Reader of the type that has been identified  **/
                    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                    /**  Load $inputFileName to a Spreadsheet Object  **/
                    $spreadsheet = $reader->load($filesPath);
                    /**  Convert Spreadsheet Object to an Array for ease of use  **/
                    $spreadSheetAry = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

                    return ['code' => 201, 'data' => $spreadSheetAry, 'count' => count($spreadSheetAry)];
                } else {
                    return ['code' => 400, 'message' => 'The files upload was not found.'];
                }
            } else {
                return ['code' => 400, 'message' => 'The size is not supported : ' . $size . ' bytes'];
            }
        } else {
            return ['code' => 400, 'message' => 'The file type is not supported : ' . $type];
        }
    }
}
