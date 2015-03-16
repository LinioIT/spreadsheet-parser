<?php

namespace Linio\Component\SpreadsheetParser\Parser;

class CsvParserTest extends \PHPUnit_Framework_TestCase
{
    public function testIsGettingFalseWhenGettingColumnNames()
    {
        $parser = new CsvParser(__DIR__ . '/../Fixtures/fixture1.csv', [CsvParser::OPTION_HAS_COLUMN_NAMES => false]);

        $actual = $parser->getColumnNames();

        $this->assertFalse($actual);
    }

    public function testIsGettingColumnNamesWithDefaultOptions()
    {
        $parser = new CsvParser(__DIR__ . '/../Fixtures/fixture1.csv');

        $actual = $parser->getColumnNames();

        $this->assertInternalType('array', $actual);
        $this->assertEquals(['column_1', 'column 2', 'column 3'], $actual);
    }

    public function testIsGettingColumnNamesWithDefaultOptionsTwice()
    {
        $parser = new CsvParser(__DIR__ . '/../Fixtures/fixture1.csv');

        $actual = $parser->getColumnNames();
        $actual = $parser->getColumnNames();

        $this->assertInternalType('array', $actual);
        $this->assertEquals(['column_1', 'column 2', 'column 3'], $actual);
    }

    public function testIsGettingDataWithDefaultOptions()
    {
        $parser = new CsvParser(__DIR__ . '/../Fixtures/fixture1.csv');

        $actual = $parser->getData();

        $this->assertInternalType('array', $actual);
        $this->assertEquals([[1, 2, 3], [4, 5, 6], [7, 8, 9]], $actual);
    }

    public function testIsGettingDataWithDefaultOptionsTwice()
    {
        $parser = new CsvParser(__DIR__ . '/../Fixtures/fixture1.csv');

        $actual = $parser->getData();
        $actual = $parser->getData();

        $this->assertInternalType('array', $actual);
        $this->assertEquals([[1, 2, 3], [4, 5, 6], [7, 8, 9]], $actual);
    }

    public function testIsGettingColumnNamesWithEnclosuresAndEscapes()
    {
        $parser = new CsvParser(__DIR__ . '/../Fixtures/fixture2.csv');

        $actual = $parser->getColumnNames();

        $this->assertInternalType('array', $actual);
        $this->assertEquals(['column_1', 'column"2', 'column 3'], $actual);
    }

    public function testIsGettingDataWithEnclosuresAndEscapes()
    {
        $parser = new CsvParser(__DIR__ . '/../Fixtures/fixture2.csv');

        $actual = $parser->getData();

        $this->assertInternalType('array', $actual);
        $this->assertEquals([[1, 2, 3], ['4"', 5, 6]], $actual);
    }

    public function testIsGettingColumnNamesWithDifferentDelimiter()
    {
        $parser = new CsvParser(__DIR__ . '/../Fixtures/fixture3.csv', [CsvParser::OPTION_DELIMITER => ';']);

        $actual = $parser->getColumnNames();

        $this->assertInternalType('array', $actual);
        $this->assertEquals(['column_1', 'column 2', 'column 3'], $actual);
    }

    public function testIsGettingDataWithDifferentDelimiter()
    {
        $parser = new CsvParser(__DIR__ . '/../Fixtures/fixture3.csv', [CsvParser::OPTION_DELIMITER => ';']);

        $actual = $parser->getData();

        $this->assertInternalType('array', $actual);
        $this->assertEquals([[1, 2, '3,4']], $actual);
    }

    public function testIsGettingColumnNamesWithDifferentEnclosure()
    {
        $parser = new CsvParser(__DIR__ . '/../Fixtures/fixture4.csv', [CsvParser::OPTION_ENCLOSURE => '\'']);

        $actual = $parser->getColumnNames();

        $this->assertInternalType('array', $actual);
        $this->assertEquals(['column_1', 'column 2', 'column 3'], $actual);
    }

    public function testIsGettingDataWithDifferentEnclosure()
    {
        $parser = new CsvParser(__DIR__ . '/../Fixtures/fixture4.csv', [CsvParser::OPTION_ENCLOSURE => '\'']);

        $actual = $parser->getData();

        $this->assertInternalType('array', $actual);
        $this->assertEquals([[1, 2, '3"4']], $actual);
    }

    public function testIsGettingColumnNamesAndData()
    {
        $parser = new CsvParser(__DIR__ . '/../Fixtures/fixture1.csv');

        $columnNames = $parser->getColumnNames();
        $data = $parser->getData();

        $this->assertInternalType('array', $columnNames);
        $this->assertEquals(['column_1', 'column 2', 'column 3'], $columnNames);
        $this->assertInternalType('array', $data);
        $this->assertEquals([[1, 2, 3], [4, 5, 6], [7, 8, 9]], $data);
    }

    public function testIsGettingDataWithDefinedNumberOfRows()
    {
        $parser = new CsvParser(__DIR__ . '/../Fixtures/fixture1.csv');

        $actual = $parser->getData(2);

        $this->assertInternalType('array', $actual);
        $this->assertEquals([[1, 2, 3], [4, 5, 6]], $actual);
    }
}
