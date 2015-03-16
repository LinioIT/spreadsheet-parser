<?php

namespace Linio\Component\SpreadsheetParser;

trait SpreadsheetParserAware
{
    /**
     * @var SpreadsheetParserService
     */
    protected $spreadsheetParserService;

    public function getSpreadsheetParserService()
    {
        return $this->spreadsheetParserService;
    }

    /**
     * @param SpreadsheetParserService $spreadsheetParserService
     */
    public function setSpreadsheetParserService(SpreadsheetParserService $spreadsheetParserService)
    {
        $this->spreadsheetParserService = $spreadsheetParserService;
    }
}
