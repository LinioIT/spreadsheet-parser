<?php

declare(strict_types=1);

namespace Linio\Component\SpreadsheetParser;

trait SpreadsheetParserAware
{
    /**
     * @var SpreadsheetParserService
     */
    protected $spreadsheetParserService;

    public function getSpreadsheetParserService(): SpreadsheetParserService
    {
        return $this->spreadsheetParserService;
    }

    public function setSpreadsheetParserService(SpreadsheetParserService $spreadsheetParserService): void
    {
        $this->spreadsheetParserService = $spreadsheetParserService;
    }
}
