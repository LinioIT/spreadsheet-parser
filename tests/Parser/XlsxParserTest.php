<?php

declare(strict_types=1);

namespace Linio\Component\SpreadsheetParser\Parser;

class XlsxParserTest extends \PHPUnit\Framework\TestCase
{
    public function testIsGettingFalseWhenGettingColumnNames(): void
    {
        $parser = new XlsxParser(__DIR__ . '/../Fixtures/fixture1.xlsx', [CsvParser::OPTION_HAS_COLUMN_NAMES => false]);

        $actual = $parser->getColumnNames();

        $this->assertFalse($actual);
    }

    public function testIsGettingColumnNamesWithDefaultOptions(): void
    {
        $parser = new XlsxParser(__DIR__ . '/../Fixtures/fixture1.xlsx');

        $actual = $parser->getColumnNames();

        $this->assertIsArray($actual);
        $this->assertEquals(['column_1', 'column 2', 'column 3'], $actual);
    }

    public function testIsGettingColumnNamesWithDefaultOptionsTwice(): void
    {
        $parser = new XlsxParser(__DIR__ . '/../Fixtures/fixture1.xlsx');

        $actual = $parser->getColumnNames();
        $actual = $parser->getColumnNames();

        $this->assertIsArray($actual);
        $this->assertEquals(['column_1', 'column 2', 'column 3'], $actual);
    }

    public function testIsGettingDataWithDefaultOptions(): void
    {
        $parser = new XlsxParser(__DIR__ . '/../Fixtures/fixture1.xlsx');

        $actual = $parser->getData();

        $this->assertIsArray($actual);
        $this->assertEquals([[1, 2, '3.4'], ['5.6', '7.89', '0.1']], $actual);
    }

    public function testIsGettingDataWithDefaultOptionsTwice(): void
    {
        $parser = new XlsxParser(__DIR__ . '/../Fixtures/fixture1.xlsx');

        $actual = $parser->getData();
        $actual = $parser->getData();

        $this->assertIsArray($actual);
        $this->assertEquals([[1, 2, '3.4'], ['5.6', '7.89', '0.1']], $actual);
    }

    public function testIsGettingColumnNamesAndData(): void
    {
        $parser = new XlsxParser(__DIR__ . '/../Fixtures/fixture1.xlsx');

        $columnNames = $parser->getColumnNames();
        $data = $parser->getData();

        $this->assertIsArray($columnNames);
        $this->assertEquals(['column_1', 'column 2', 'column 3'], $columnNames);
        $this->assertIsArray($data);
        $this->assertEquals([[1, 2, '3.4'], ['5.6', '7.89', '0.1']], $data);
    }

    public function testIsGettingDataWithDefinedNumberOfRows(): void
    {
        $parser = new XlsxParser(__DIR__ . '/../Fixtures/fixture1.xlsx');

        $actual = $parser->getData(1);

        $this->assertIsArray($actual);
        $this->assertEquals([[1, 2, '3.4']], $actual);
    }

    public function testIsGettingColumnNamesAndDataFromSecondSheetByIndex(): void
    {
        $parser = new XlsxParser(__DIR__ . '/../Fixtures/fixture2.xlsx', [XlsxParser::OPTION_SHEET_INDEX => 2]);

        $columnNames = $parser->getColumnNames();
        $data = $parser->getData();

        $this->assertIsArray($columnNames);
        $this->assertEquals(['Fixture2_A', 'Fixture2_B', 'Fixture2_C'], $columnNames);
        $this->assertIsArray($data);
        $this->assertEquals([[1, 2, 3]], $data);
    }

    public function testIsGettingColumnNamesAndDataFromSecondSheetByName(): void
    {
        $parser = new XlsxParser(__DIR__ . '/../Fixtures/fixture2.xlsx', [XlsxParser::OPTION_SHEET_NAME => 'fixture2']);

        $columnNames = $parser->getColumnNames();
        $data = $parser->getData();

        $this->assertIsArray($columnNames);
        $this->assertEquals(['Fixture2_A', 'Fixture2_B', 'Fixture2_C'], $columnNames);
        $this->assertIsArray($data);
        $this->assertEquals([[1, 2, 3]], $data);
    }

    public function testIsGettingColumnNamesAndDataFromSecondSheetByInvalidName(): void
    {
        $this->expectException(\Linio\Component\SpreadsheetParser\Exception\SpreadsheetParsingException::class);

        $parser = new XlsxParser(__DIR__ . '/../Fixtures/fixture2.xlsx', [XlsxParser::OPTION_SHEET_NAME => 'nop']);
        $parser->open();
    }
}
