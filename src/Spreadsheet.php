<?php

namespace Linio\Component\SpreadsheetParser;

use Doctrine\Common\Inflector\Inflector;
use Linio\Component\SpreadsheetParser\Exception\FileNotFoundException;
use Linio\Component\SpreadsheetParser\Exception\InvalidFileTypeException;
use Linio\Component\SpreadsheetParser\Parser\ParserInterface;

class Spreadsheet
{
    const TYPE_XLSX = 'xlsx';
    const TYPE_CSV = 'csv';

    /**
     * @var ParserInterface
     */
    protected $parser;

    /**
     * @param $filePath
     * @param string $fileType
     * @param array $options
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     *
     * @throws FileNotFoundException
     * @throws InvalidFileTypeException
     */
    public function __construct($filePath, $fileType = null, array $options = [])
    {
        if (!file_exists($filePath)) {
            throw new FileNotFoundException('File not found: ' . $filePath);
        }

        if (!$fileType) {
            $fileType = $this->getFileExtension($filePath);
        }

        if (!in_array($fileType, [static::TYPE_CSV, static::TYPE_XLSX])) {
            throw new InvalidFileTypeException('Invalid file type: ' . $fileType);
        }

        $this->parser = $this->getParser($filePath, $fileType, $options);
    }

    /**
     * @return bool
     */
    public function open()
    {
        return $this->parser->open();
    }

    /**
     * @return array|false
     */
    public function getColumnNames()
    {
        return $this->parser->getColumnNames();
    }

    /**
     * @param int $numRows
     *
     * @return array
     */
    public function getData($numRows = 0)
    {
        return $this->parser->getData($numRows);
    }

    /**
     * @return bool
     */
    public function close()
    {
        return $this->parser->close();
    }

    /**
     * @param string $filePath
     * @param string $fileType
     * @param array $options
     *
     * @return ParserInterface
     */
    protected function getParser($filePath, $fileType, array $options = [])
    {
        $parserClass = sprintf('%s\\Parser\\%sParser', __NAMESPACE__, Inflector::classify($fileType));

        return new $parserClass($filePath, $options);
    }

    /**
     * @param string $filePath
     *
     * @return string
     */
    protected function getFileExtension($filePath)
    {
        return substr(strrchr($filePath, '.'), 1);
    }
}
