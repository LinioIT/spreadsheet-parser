<?php

declare(strict_types=1);

namespace Linio\Component\SpreadsheetParser;

use org\bovigo\vfs\vfsStream;

class SpreadsheetTest extends \PHPUnit\Framework\TestCase
{
    public function testIsConstructingObjectWithoutSpecifyingTypeWithValidExtension(): void
    {
        $mockDir = vfsStream::setup();
        $mockFile = vfsStream::newFile('mockfile.csv')
            ->at($mockDir);

        $actual = new Spreadsheet($mockFile->url());

        $this->assertInstanceOf('Linio\Component\SpreadsheetParser\Spreadsheet', $actual);
    }

    public function testIsNotConstructingObjectWithoutSpecifyingTypeWithInvalidExtension(): void
    {
        $this->expectException(\Linio\Component\SpreadsheetParser\Exception\InvalidFileTypeException::class);

        $mockDir = vfsStream::setup();
        $mockFile = vfsStream::newFile('mockfile.txt')
            ->at($mockDir);

        $actual = new Spreadsheet($mockFile->url());
    }

    public function testIsConstructingObjectWithInvalidType(): void
    {
        $this->expectException(\Linio\Component\SpreadsheetParser\Exception\InvalidFileTypeException::class);

        $mockDir = vfsStream::setup();
        $mockFile = vfsStream::newFile('mockfile.csv')
            ->at($mockDir);

        $actual = new Spreadsheet($mockFile->url(), 'txt');

        $this->assertInstanceOf('Linio\Component\SpreadsheetParser\Spreadsheet', $actual);
    }

    public function testIsNotConstructingObjectWithInvalidType(): void
    {
        $this->expectException(\Linio\Component\SpreadsheetParser\Exception\InvalidFileTypeException::class);

        $mockDir = vfsStream::setup();
        $mockFile = vfsStream::newFile('mockfile.csv')
            ->at($mockDir);

        $actual = new Spreadsheet($mockFile->url(), 'txt');
    }

    public function testIsNotConstructingObjectWithFileNotFound(): void
    {
        $this->expectException(\Linio\Component\SpreadsheetParser\Exception\FileNotFoundException::class);

        $actual = new Spreadsheet('nop.csv');
    }
}
