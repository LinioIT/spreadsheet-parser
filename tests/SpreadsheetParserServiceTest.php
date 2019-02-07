<?php

declare(strict_types=1);

namespace Linio\Component\SpreadsheetParser;

use org\bovigo\vfs\vfsStream;

class SpreadsheetParserServiceTest extends \PHPUnit\Framework\TestCase
{
    public function testIsParsingSpreadsheet(): void
    {
        $mockDir = vfsStream::setup();
        $mockFile = vfsStream::newFile('mockfile.csv')
            ->at($mockDir);

        $service = new SpreadsheetParserService();
        $actual = $service->parseSpreadsheet($mockFile->url());

        $this->assertInstanceOf('Linio\Component\SpreadsheetParser\Spreadsheet', $actual);
    }
}
