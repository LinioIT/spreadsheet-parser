<?php

namespace Linio\Component\SpreadsheetParser;

class SpreadsheetParserService
{
    /**
     * @param string $filePath
     * @param string $fileType
     * @param array $options
     *
     * @return Spreadsheet
     */
    public function parseSpreadsheet($filePath, $fileType = null, array $options = [])
    {
        return new Spreadsheet($filePath, $fileType, $options);
    }
}
