<?php

namespace Linio\Component\SpreadsheetParser\Parser;

class XlsxParserTest extends \PHPUnit_Framework_TestCase
{
    public function testIsGettingFalseWhenGettingColumnNames()
    {
        $parser = new XlsxParser(__DIR__ . '/../Fixtures/fixture1.xlsx', [CsvParser::OPTION_HAS_COLUMN_NAMES => false]);

        $actual = $parser->getColumnNames();

        $this->assertFalse($actual);
    }

    public function testIsGettingColumnNamesWithDefaultOptions()
    {
        $parser = new XlsxParser(__DIR__ . '/../Fixtures/fixture1.xlsx');

        $actual = $parser->getColumnNames();

        $this->assertInternalType('array', $actual);
        $this->assertEquals(['column_1', 'column 2', 'column 3'], $actual);
    }

    public function testIsGettingColumnNamesWithDefaultOptionsTwice()
    {
        $parser = new XlsxParser(__DIR__ . '/../Fixtures/fixture1.xlsx');

        $actual = $parser->getColumnNames();
        $actual = $parser->getColumnNames();

        $this->assertInternalType('array', $actual);
        $this->assertEquals(['column_1', 'column 2', 'column 3'], $actual);
    }

    public function testIsGettingDataWithDefaultOptions()
    {
        $parser = new XlsxParser(__DIR__ . '/../Fixtures/fixture1.xlsx');

        $actual = $parser->getData();

        $this->assertInternalType('array', $actual);
        $this->assertEquals([[1, 2, '3.4'], ['5.6', '7.89', '0.1']], $actual);
    }

    public function testIsGettingDataWithDefaultOptionsTwice()
    {
        $parser = new XlsxParser(__DIR__ . '/../Fixtures/fixture1.xlsx');

        $actual = $parser->getData();
        $actual = $parser->getData();

        $this->assertInternalType('array', $actual);
        $this->assertEquals([[1, 2, '3.4'], ['5.6', '7.89', '0.1']], $actual);
    }

    public function testIsGettingColumnNamesAndData()
    {
        $parser = new XlsxParser(__DIR__ . '/../Fixtures/fixture1.xlsx');

        $columnNames = $parser->getColumnNames();
        $data = $parser->getData();

        $this->assertInternalType('array', $columnNames);
        $this->assertEquals(['column_1', 'column 2', 'column 3'], $columnNames);
        $this->assertInternalType('array', $data);
        $this->assertEquals([[1, 2, '3.4'], ['5.6', '7.89', '0.1']], $data);
    }

    public function testIsGettingDataWithDefinedNumberOfRows()
    {
        $parser = new XlsxParser(__DIR__ . '/../Fixtures/fixture1.xlsx');

        $actual = $parser->getData(1);

        $this->assertInternalType('array', $actual);
        $this->assertEquals([[1, 2, '3.4']], $actual);
    }

    public function testIsGettingColumnNamesAndDataFromSecondSheetByIndex()
    {
        $parser = new XlsxParser(__DIR__ . '/../Fixtures/fixture2.xlsx', [XlsxParser::OPTION_SHEET_INDEX => 2]);

        $columnNames = $parser->getColumnNames();
        $data = $parser->getData();

        $this->assertInternalType('array', $columnNames);
        $this->assertEquals(['Fixture2_A', 'Fixture2_B', 'Fixture2_C'], $columnNames);
        $this->assertInternalType('array', $data);
        $this->assertEquals([[1, 2, 3]], $data);
    }

    public function testIsGettingColumnNamesAndDataFromSecondSheetByName()
    {
        $parser = new XlsxParser(__DIR__ . '/../Fixtures/fixture2.xlsx', [XlsxParser::OPTION_SHEET_NAME => 'fixture2']);

        $columnNames = $parser->getColumnNames();
        $data = $parser->getData();

        $this->assertInternalType('array', $columnNames);
        $this->assertEquals(['Fixture2_A', 'Fixture2_B', 'Fixture2_C'], $columnNames);
        $this->assertInternalType('array', $data);
        $this->assertEquals([[1, 2, 3]], $data);
    }

    /**
     * @expectedException \Linio\Component\SpreadsheetParser\Exception\SpreadsheetParsingException
     */
    public function testIsGettingColumnNamesAndDataFromSecondSheetByInvalidName()
    {
        $parser = new XlsxParser(__DIR__ . '/../Fixtures/fixture2.xlsx', [XlsxParser::OPTION_SHEET_NAME => 'nop']);
        $parser->open();
    }
}
