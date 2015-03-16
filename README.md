# Linio Spreadsheet Parser
[![Latest Stable Version](https://poser.pugx.org/linio/spreadsheet-parser/v/stable.svg)](https://packagist.org/packages/linio/spreadsheet-parser) [![License](https://poser.pugx.org/linio/spreadsheet-parser/license.svg)](https://packagist.org/packages/linio/spreadsheet-parser) [![Build Status](https://secure.travis-ci.org/LinioIT/spreadsheet-parser.png)](http://travis-ci.org/LinioIT/spreadsheet-parser) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/LinioIT/spreadsheet-parser/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/LinioIT/spreadsheet-parser/?branch=master)

Linio Spreadsheet Parser allows you to parse and import data files. This component supports
both text and binary file formats.

## Install

The recommended way to install Linio Spreadsheet Parser is [through composer](http://getcomposer.org).

```JSON
{
    "require": {
        "linio/spreadsheet-parser": "dev-master"
    }
}
```

## Tests

To run the test suite, you need install the dependencies via composer, then
run PHPUnit.

    $ composer install
    $ phpunit

## Usage

There are two ways of using the parser: standalone or as a service.

### Standalone

```php
<?php

use Linio\Component\SpreadsheetParser\Spreadsheet;

$spreadsheet = new Spreadsheet('/folder/file.csv');

$columnNames = $spreadsheet->getColumnNames();
$data = $spreadsheet->getData();
```

### Service

```php
<?php

$container['spreadsheet.parser'] = function() {
    return new SpreadsheetParserService();
}

$spreadsheet = $container['spreadsheet.parser']->parseSpreadsheet($filePath, $fileType, $options);
$columnNames = $spreadsheet->getColumnNames();
$data = $spreadsheet->getData();
```

## Methods

### `Constructor`

```php
<?php

    use Linio\Component\SpreadsheetParser\Spreadsheet;
    use Linio\Component\SpreadsheetParser\Exception\FileNotFoundException;
    use Linio\Component\SpreadsheetParser\Exception\InvalidFileTypeException;
    use Linio\Component\SpreadsheetParser\Parser\CsvParser;

    /**
     * @param $filePath
     * @param string $fileType
     * @param array $options
     *
     * @throws FileNotFoundException
     * @throws InvalidFileTypeException
     */
    public function __construct($filePath, $fileType = null, array $options = []);

    $spreadsheet = new Spreadsheet('/folder/file.txt', Spreadsheet::TYPE_CSV, [CsvParser::OPTION_DELIMITER => ';']);

```

The `$fileType` parameter is optional. If not present, it will use the file extension to determine its type.

### `open`

This method opens the file. This is an optional step as the methods that read from the file will open the file automatically if the file was not opened before they are called.

```php
<?php

     /**
     * @return void
     */
    public function open();

```

### `getColumnNames`

This method returns the column names from the file (first row) or `false` if the option `has_column_names` was set to false when creating the `Spreadsheet` object.

```php
<?php

    /**
     * @return array|false
     */
    public function getColumnNames();

    $columnNames = $spreadsheet->getColumnNames();

```

### `getData`

```php
<?php

    /**
     * @param int $numRows
     *
     * @return array
     */
    public function getData($numRows = 0);

    $dataWholeFile = $spreadsheet->getData();

    $dataFirst5Lines = $spreadsheet->getData(5);

```

### `close`

This method closes the open handles and deletes the temporary files created. It should always be called when you don't need to access the file anymore.

```php
<?php

     /**
     * @return void
     */
    public function close();

    $spreadsheet->close();

```

## Parsers

### `csv`

Parser for [CSV](http://en.wikipedia.org/wiki/Comma-separated_values) files.

Parser options:

- `CsvParser::OPTION_HAS_COLUMN_NAMES` (defaults to `true`)
- `CsvParser::OPTION_LENGTH` (defaults to `0`)
- `CsvParser::OPTION_DELIMITER` (defaults to `,`)
- `CsvParser::OPTION_ENCLOSURE` (defaults to `"`)
- `CsvParser::OPTION_ESCAPE` (defaults to `\`)

----------

### `xlsx`

Parser for [XLSX](http://en.wikipedia.org/wiki/Office_Open_XML) (Excel 2007+) files.
The `OPTION_SHEET_INDEX` setting specifies the desired sheet index to import within the file.
The `OPTION_SHEET_NAME` setting has precedence over the setting `OPTION_SHEET_INDEX`. If both are specified, the parser will only try to use the sheet specified by `OPTION_SHEET_NAME`.

Parser options:

- `XlsxParser::OPTION_HAS_COLUMN_NAMES` (defaults to `true`)
- `XlsxParser::OPTION_SHEET_INDEX` (defaults to `1`)
- `XlsxParser::OPTION_SHEET_NAME` (defaults to `null`)

----------
