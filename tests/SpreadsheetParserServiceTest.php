<?php

namespace Linio\Component\SpreadsheetParser;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;

class SpreadsheetParserServiceTest extends \PHPUnit_Framework_TestCase
{

    public function testIsParsingSpreadsheet()
    {
        $mockDir = vfsStream::setup();
        $mockFile = vfsStream::newFile('mockfile.csv')
            ->at($mockDir);

        $service = new SpreadsheetParserService();
        $actual = $service->parseSpreadsheet($mockFile->url());

        $this->assertInstanceOf('Linio\Component\SpreadsheetParser\Spreadsheet', $actual);
    }
}
