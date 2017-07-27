<?php

namespace Linio\Component\SpreadsheetParser;

use org\bovigo\vfs\vfsStream;

class SpreadsheetTest extends \PHPUnit_Framework_TestCase
{
    public function testIsConstructingObjectWithoutSpecifyingTypeWithValidExtension()
    {
        $mockDir = vfsStream::setup();
        $mockFile = vfsStream::newFile('mockfile.csv')
            ->at($mockDir);

        $actual = new Spreadsheet($mockFile->url());

        $this->assertInstanceOf('Linio\Component\SpreadsheetParser\Spreadsheet', $actual);
    }

    /**
     * @expectedException \Linio\Component\SpreadsheetParser\Exception\InvalidFileTypeException
     */
    public function testIsNotConstructingObjectWithoutSpecifyingTypeWithInvalidExtension()
    {
        $mockDir = vfsStream::setup();
        $mockFile = vfsStream::newFile('mockfile.txt')
            ->at($mockDir);

        $actual = new Spreadsheet($mockFile->url());
    }

    /**
     * @expectedException \Linio\Component\SpreadsheetParser\Exception\InvalidFileTypeException
     */
    public function testIsConstructingObjectWithInvalidType()
    {
        $mockDir = vfsStream::setup();
        $mockFile = vfsStream::newFile('mockfile.csv')
            ->at($mockDir);

        $actual = new Spreadsheet($mockFile->url(), 'txt');

        $this->assertInstanceOf('Linio\Component\SpreadsheetParser\Spreadsheet', $actual);
    }

    /**
     * @expectedException \Linio\Component\SpreadsheetParser\Exception\InvalidFileTypeException
     */
    public function testIsNotConstructingObjectWithInvalidType()
    {
        $mockDir = vfsStream::setup();
        $mockFile = vfsStream::newFile('mockfile.csv')
            ->at($mockDir);

        $actual = new Spreadsheet($mockFile->url(), 'txt');
    }

    /**
     * @expectedException \Linio\Component\SpreadsheetParser\Exception\FileNotFoundException
     */
    public function testIsNotConstructingObjectWithFileNotFound()
    {
        $actual = new Spreadsheet('nop.csv');
    }
}
