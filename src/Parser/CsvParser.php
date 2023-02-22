<?php

declare(strict_types=1);

namespace Linio\Component\SpreadsheetParser\Parser;

class CsvParser implements ParserInterface
{
    public const OPTION_HAS_COLUMN_NAMES = 'has_column_names';
    public const OPTION_LENGTH = 'length';
    public const OPTION_DELIMITER = 'delimiter';
    public const OPTION_ENCLOSURE = 'enclosure';
    public const OPTION_ESCAPE = 'escape';

    /**
     * @var string
     */
    protected $filePath;

    /**
     * @var resource
     */
    protected $handle;

    /**
     * @var bool
     */
    protected $hasColumnNames;

    /**
     * @var int
     */
    protected $length;

    /**
     * @var string
     */
    protected $delimiter;

    /**
     * @var string
     */
    protected $enclosure;

    /**
     * @var string
     */
    protected $escape;

    /**
     * @var array
     */
    protected $columnNames;

    /**
     * @param string $filePath
     */
    public function __construct($filePath, array $options = [])
    {
        $this->filePath = $filePath;

        // default options
        $this->hasColumnNames = true;
        $this->length = 0;
        $this->delimiter = ',';
        $this->enclosure = '"';
        $this->escape = '\\';

        $this->loadParserOptions($options);
    }

    /**
     * @return bool
     */
    public function open()
    {
        if ($this->handle) {
            return true;
        }

        $this->handle = fopen($this->filePath, 'r');

        return true;
    }

    /**
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     *
     * @return array|false
     */
    public function getColumnNames()
    {
        if (!$this->hasColumnNames) {
            return false;
        }

        if ($this->columnNames) {
            return $this->columnNames;
        }

        $this->open();
        $this->columnNames = fgetcsv($this->handle, $this->length, $this->delimiter, $this->enclosure, $this->escape);

        return $this->columnNames;
    }

    /**
     * @param int $numRows
     *
     * @return array
     */
    public function getData($numRows = 0)
    {
        $this->close();
        $this->open();

        $skipLine = false;
        if ($this->hasColumnNames) {
            $skipLine = true;
        }

        return $this->readDataFromFile($numRows, $skipLine);
    }

    /**
     * @return bool
     */
    public function close()
    {
        if ($this->handle) {
            fclose($this->handle);
            $this->handle = null;
        }

        return true;
    }

    public function __destruct()
    {
        $this->close();
    }

    /**
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function loadParserOptions(array $options): void
    {
        if (isset($options[static::OPTION_HAS_COLUMN_NAMES])) {
            $this->hasColumnNames = $options[static::OPTION_HAS_COLUMN_NAMES];
        }

        if (isset($options[static::OPTION_LENGTH])) {
            $this->length = $options[static::OPTION_LENGTH];
        }

        if (isset($options[static::OPTION_DELIMITER])) {
            $this->delimiter = $options[static::OPTION_DELIMITER];
        }

        if (isset($options[static::OPTION_ENCLOSURE])) {
            $this->enclosure = $options[static::OPTION_ENCLOSURE];
        }

        if (isset($options[static::OPTION_ESCAPE])) {
            $this->escape = $options[static::OPTION_ESCAPE];
        }
    }

    /**
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     *
     * @return array
     */
    protected function readDataFromFile($numRows, $skipLine)
    {
        $data = [];
        while (($row = fgetcsv($this->handle, $this->length, $this->delimiter, $this->enclosure, $this->escape)) !== false) {
            if ($skipLine) {
                $skipLine = false;
                continue;
            }

            $data[] = $row;

            $numRows--;
            if ($numRows == 0) {
                break;
            }
        }

        return $data;
    }
}
