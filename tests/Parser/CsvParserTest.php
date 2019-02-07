<?php

declare(strict_types=1);

namespace Linio\Component\SpreadsheetParser\Parser;

class CsvParserTest extends \PHPUnit\Framework\TestCase
{
    public function testIsGettingFalseWhenGettingColumnNames(): void
    {
        $parser = new CsvParser(__DIR__ . '/../Fixtures/fixture1.csv', [CsvParser::OPTION_HAS_COLUMN_NAMES => false]);

        $actual = $parser->getColumnNames();

        $this->assertFalse($actual);
    }

    public function testIsGettingColumnNamesWithDefaultOptions(): void
    {
        $parser = new CsvParser(__DIR__ . '/../Fixtures/fixture1.csv');

        $actual = $parser->getColumnNames();

        $this->assertIsArray($actual);
        $this->assertEquals(['column_1', 'column 2', 'column 3'], $actual);
    }

    public function testIsGettingColumnNamesWithDefaultOptionsTwice(): void
    {
        $parser = new CsvParser(__DIR__ . '/../Fixtures/fixture1.csv');

        $actual = $parser->getColumnNames();
        $actual = $parser->getColumnNames();

        $this->assertIsArray($actual);
        $this->assertEquals(['column_1', 'column 2', 'column 3'], $actual);
    }

    public function testIsGettingDataWithDefaultOptions(): void
    {
        $parser = new CsvParser(__DIR__ . '/../Fixtures/fixture1.csv');

        $actual = $parser->getData();

        $this->assertIsArray($actual);
        $this->assertEquals([[1, 2, 3], [4, 5, 6], [7, 8, 9]], $actual);
    }

    public function testIsGettingDataWithDefaultOptionsTwice(): void
    {
        $parser = new CsvParser(__DIR__ . '/../Fixtures/fixture1.csv');

        $actual = $parser->getData();
        $actual = $parser->getData();

        $this->assertIsArray($actual);
        $this->assertEquals([[1, 2, 3], [4, 5, 6], [7, 8, 9]], $actual);
    }

    public function testIsGettingColumnNamesWithEnclosuresAndEscapes(): void
    {
        $parser = new CsvParser(__DIR__ . '/../Fixtures/fixture2.csv');

        $actual = $parser->getColumnNames();

        $this->assertIsArray($actual);
        $this->assertEquals(['column_1', 'column"2', 'column 3'], $actual);
    }

    public function testIsGettingDataWithEnclosuresAndEscapes(): void
    {
        $parser = new CsvParser(__DIR__ . '/../Fixtures/fixture2.csv');

        $actual = $parser->getData();

        $this->assertIsArray($actual);
        $this->assertEquals([[1, 2, 3], ['4"', 5, 6]], $actual);
    }

    public function testIsGettingColumnNamesWithDifferentDelimiter(): void
    {
        $parser = new CsvParser(__DIR__ . '/../Fixtures/fixture3.csv', [CsvParser::OPTION_DELIMITER => ';']);

        $actual = $parser->getColumnNames();

        $this->assertIsArray($actual);
        $this->assertEquals(['column_1', 'column 2', 'column 3'], $actual);
    }

    public function testIsGettingDataWithDifferentDelimiter(): void
    {
        $parser = new CsvParser(__DIR__ . '/../Fixtures/fixture3.csv', [CsvParser::OPTION_DELIMITER => ';']);

        $actual = $parser->getData();

        $this->assertIsArray($actual);
        $this->assertEquals([[1, 2, '3,4']], $actual);
    }

    public function testIsGettingColumnNamesWithDifferentEnclosure(): void
    {
        $parser = new CsvParser(__DIR__ . '/../Fixtures/fixture4.csv', [CsvParser::OPTION_ENCLOSURE => '\'']);

        $actual = $parser->getColumnNames();

        $this->assertIsArray($actual);
        $this->assertEquals(['column_1', 'column 2', 'column 3'], $actual);
    }

    public function testIsGettingDataWithDifferentEnclosure(): void
    {
        $parser = new CsvParser(__DIR__ . '/../Fixtures/fixture4.csv', [CsvParser::OPTION_ENCLOSURE => '\'']);

        $actual = $parser->getData();

        $this->assertIsArray($actual);
        $this->assertEquals([[1, 2, '3"4']], $actual);
    }

    public function testIsGettingColumnNamesAndData(): void
    {
        $parser = new CsvParser(__DIR__ . '/../Fixtures/fixture1.csv');

        $columnNames = $parser->getColumnNames();
        $data = $parser->getData();

        $this->assertIsArray($columnNames);
        $this->assertEquals(['column_1', 'column 2', 'column 3'], $columnNames);
        $this->assertIsArray($data);
        $this->assertEquals([[1, 2, 3], [4, 5, 6], [7, 8, 9]], $data);
    }

    public function testIsGettingDataWithDefinedNumberOfRows(): void
    {
        $parser = new CsvParser(__DIR__ . '/../Fixtures/fixture1.csv');

        $actual = $parser->getData(2);

        $this->assertIsArray($actual);
        $this->assertEquals([[1, 2, 3], [4, 5, 6]], $actual);
    }
}
