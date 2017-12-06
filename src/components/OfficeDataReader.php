<?php

namespace rowe\dataupdown\components;


class OfficeDataReader extends DataReader
{

    private $_excel;

    function loadData()
    {
        $this->_excel = \PHPExcel_IOFactory::load($this->source);
        $titles = $this->getTitles();
        $maxColsCounter = count($titles);
        $rowIndex = 2;

        $emptyRowFlg = false;
        $rows = [];
        while (!$emptyRowFlg) {

            $emptyRowFlg = true;
            $colIndex = 0;
            $row = [];
            while ($colIndex < $maxColsCounter) {
                $cellValue = $this->getCellVal($colIndex, $rowIndex);
                if (!empty($cellValue)) {
                    $emptyRowFlg = false;
                }
                $row[$titles[$colIndex]] = $cellValue;
                $colIndex++;
            }
            if (!$emptyRowFlg) {
                $rows[] = $row;
            }
            $rowIndex++;
        }
        return $rows;

    }

    /**
     * 获取标题
     * @return array
     */
    private function getTitles()
    {
        $titles = [];
        $colIndex = 0;
        $colVal = $this->getCellVal($colIndex, 1);
        while ($colVal) {
            $titles[] = $colVal;
            $colVal = $this->getCellVal(++$colIndex, 1);

        }
        return $titles;
    }

    /**
     * 获取单元格值
     * @param $colIndex
     * @param $rowIndex
     * @return string
     */
    private function getCellVal($colIndex, $rowIndex)
    {
        return trim($this->_excel->getActiveSheet()->getCellByColumnAndRow($colIndex, $rowIndex)->getValue());
    }
}