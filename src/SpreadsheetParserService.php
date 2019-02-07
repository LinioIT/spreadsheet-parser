<?php

declare(strict_types=1);

namespace Linio\Component\SpreadsheetParser;

class SpreadsheetParserService
{
    /**
     * @param string $filePath
     * @param string $fileType
     *
     * @return Spreadsheet
     */
    public function parseSpreadsheet($filePath, $fileType = null, array $options = [])
    {
        return new Spreadsheet($filePath, $fileType, $options);
    }
}
