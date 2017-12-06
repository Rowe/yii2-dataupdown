<?php

namespace rowe\dataupdown\components;


use PHPExcel;
use PHPExcel_IOFactory;

class OfficeExporter extends Exporter
{

    public function handle()
    {
        $data = $this->dataLayout->render();
        if ($data) {
            // Create new PHPExcel object
            $objPHPExcel = new PHPExcel();

// Set document properties
            $objPHPExcel->getProperties()->setCreator("UA-CMS")
                ->setLastModifiedBy("UA-CMS")
                ->setTitle("UA_CMS_CUSTOMER_DATA")
                ->setSubject("UA_CMS_CUSTOMER_DATA")
                ->setDescription("Test document for Office 2007 XLSX, generated by U-Achievement Customer Management System.")
                ->setKeywords("UA_CMS_CUSTOMER_DATA")
                ->setCategory("UA_CMS_CUSTOMER_DATA");

// Add some data
            foreach ($data as $rowIndex => $row) {
                foreach ($row as $colIndex => $col) {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($colIndex, $rowIndex + 1,$col);
                }
            }

// Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('UACMSCUSTOMERDATA');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="UA_CMS_CUSTOMER_DATA.xlsx"');
            header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
        }
        exit;
    }


}