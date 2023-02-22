<?php

declare(strict_types=1);

namespace Linio\Component\SpreadsheetParser;

use Doctrine\Inflector\InflectorFactory;
use Linio\Component\SpreadsheetParser\Exception\FileNotFoundException;
use Linio\Component\SpreadsheetParser\Exception\InvalidFileTypeException;
use Linio\Component\SpreadsheetParser\Parser\ParserInterface;

class Spreadsheet
{
    public const TYPE_XLSX = 'xlsx';
    public const TYPE_CSV = 'csv';

    /**
     * @var ParserInterface
     */
    protected $parser;

    /**
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     *
     * @throws FileNotFoundException
     * @throws InvalidFileTypeException
     */
    public function __construct($filePath, string $fileType = null, array $options = [])
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

    public function open(): bool
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

    public function getData(int $numRows = 0): array
    {
        return $this->parser->getData($numRows);
    }

    public function close(): bool
    {
        return $this->parser->close();
    }

    protected function getParser(string $filePath, string $fileType, array $options = []): ParserInterface
    {
        $inflector = InflectorFactory::create()->build();
        $parserClass = sprintf('%s\\Parser\\%sParser', __NAMESPACE__, $inflector->classify($fileType));

        return new $parserClass($filePath, $options);
    }

    protected function getFileExtension(string $filePath): string
    {
        return substr(strrchr($filePath, '.'), 1);
    }
}
