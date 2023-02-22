<?php

declare(strict_types=1);

namespace Linio\Component\SpreadsheetParser;

class SpreadsheetParserService
{
    public function parseSpreadsheet(string $filePath, string $fileType = null, array $options = []): Spreadsheet
    {
        return new Spreadsheet($filePath, $fileType, $options);
    }
}
