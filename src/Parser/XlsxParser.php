<?php

namespace Linio\Component\SpreadsheetParser\Parser;

use Linio\Component\SpreadsheetParser\Exception\SpreadsheetParsingException;

class XlsxParser implements ParserInterface
{
    const OPTION_HAS_COLUMN_NAMES = 'has_column_names';
    const OPTION_SHEET_INDEX = 'sheet_index';
    const OPTION_SHEET_NAME = 'sheet_name';

    /**
     * @var string
     */
    protected $filePath;

    /**
     * @var string
     */
    protected $tmpDir;

    /**
     * @var bool
     */
    protected $hasColumnNames;

    /**
     * @var int
     */
    protected $sheetIndex;

    /**
     * @var string
     */
    protected $sheetName;

    /**
     * @var array
     */
    protected $columnNames;

    /**
     * @var \SimpleXMLElement
     */
    protected $appXml;

    /**
     * @var \SimpleXMLElement
     */
    protected $sharedStringsXml;

    /**
     * @var \SimpleXMLElement
     */
    protected $sheetXml;

    /**
     * @var \SimpleXMLElement
     */
    protected $workbookXml;

    /**
     * @var \SimpleXMLElement
     */
    protected $relationshipsXml;

    /**
     * @param string $filePath
     */
    public function __construct($filePath, array $options = [])
    {
        $this->filePath = $filePath;

        // default options
        $this->hasColumnNames = true;
        $this->sheetIndex = 1;
        $this->sheetName = null;

        $this->loadParserOptions($options);
    }

    /**
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     *
     * @throws SpreadsheetParsingException
     *
     * @return bool
     */
    public function open()
    {
        if ($this->sheetXml) {
            return true;
        }

        $this->tmpDir = tempnam(sys_get_temp_dir(), 'xls');
        unlink($this->tmpDir);

        $zip = new \ZipArchive();
        $zip->open($this->filePath);
        $zip->extractTo($this->tmpDir);
        $zip->close();

        if ($this->sheetName) {
            $this->setSheetIndexFromSheetName();
            if (!$this->sheetIndex) {
                throw new SpreadsheetParsingException('Sheet not found: ' . $this->sheetName);
            }
        }

        $this->loadXlsxXmlFiles();

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

        $rows = $this->sheetXml->sheetData->row;

        $row = $rows[0];
        $this->columnNames = $this->getRowContent($row, $this->sharedStringsXml);

        return $this->columnNames;
    }

    /**
     * @param int $numRows
     *
     * @return array
     */
    public function getData($numRows = 0)
    {
        $this->open();

        $skipLine = false;
        if ($this->hasColumnNames) {
            $skipLine = true;
        }

        $data = $this->readDataFromFile($numRows, $skipLine);

        return $data;
    }

    /**
     * @return bool
     */
    public function close()
    {
        if ($this->tmpDir && file_exists($this->tmpDir)) {
            $this->delTree($this->tmpDir);
            $this->tmpDir = null;
        }

        return true;
    }

    public function __destruct()
    {
        $this->close();
    }

    /**
     * @param \SimpleXMLElement $row
     * @param \SimpleXMLElement $sharedStrings
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     *
     * @return array
     */
    protected function getRowContent(\SimpleXMLElement $row, \SimpleXMLElement $sharedStrings)
    {
        $rowContent = [];
        foreach ($row->c as $cell) {
            $v = (string) $cell->v;

            if (isset($cell['t']) && $cell['t'] == 's') {
                $s = [];
                $si = $sharedStrings->si[(int) $v];
                $si->registerXPathNamespace('n', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
                foreach ($si->xpath('.//n:t') as $t) {
                    $s[] = (string) $t;
                }
                $v = implode($s);
            }
            $rowContent[] = $v;
        }

        return $rowContent;
    }

    /**
     * @param $dir
     *
     * @return bool
     */
    protected function delTree($dir)
    {
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->delTree("$dir/$file") : unlink("$dir/$file");
        }

        return rmdir($dir);
    }

    /**
     * @param array $options
     */
    protected function loadParserOptions(array $options)
    {
        if (isset($options[static::OPTION_HAS_COLUMN_NAMES])) {
            $this->hasColumnNames = $options[static::OPTION_HAS_COLUMN_NAMES];
        }

        if (isset($options[static::OPTION_SHEET_INDEX])) {
            $this->sheetIndex = $options[static::OPTION_SHEET_INDEX];
        }

        if (isset($options[static::OPTION_SHEET_NAME])) {
            $this->sheetName = $options[static::OPTION_SHEET_NAME];
        }
    }

    /**
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function setSheetIndexFromSheetName()
    {
        $this->sheetIndex = null;
        try {
            $this->appXml = simplexml_load_file($this->tmpDir . '/docProps/app.xml');
        } catch (\Exception $e) {
            throw new SpreadsheetParsingException('Error parsing XLSX internal files');
        }

        $sheetIndex = 1;
        $this->appXml->registerXPathNamespace('ex', 'http://schemas.openxmlformats.org/officeDocument/2006/extended-properties');
        foreach ($this->appXml->xpath('//ex:TitlesOfParts/vt:vector/vt:lpstr') as $sheetName) {
            if ($this->sheetName == (string) $sheetName) {
                $this->sheetIndex = $sheetIndex;
                break;
            }
            $sheetIndex++;
        }
    }

    protected function loadXlsxXmlFiles()
    {
        try {
            $this->sharedStringsXml = simplexml_load_file($this->tmpDir . '/xl/sharedStrings.xml');
            $this->workbookXml = simplexml_load_file($this->tmpDir . '/xl/workbook.xml');
            $this->relationshipsXml = simplexml_load_file($this->tmpDir . '/xl/_rels/workbook.xml.rels');
            $this->sheetXml = simplexml_load_file($this->tmpDir . '/xl/worksheets/sheet' . $this->sheetIndex . '.xml');
        } catch (\Exception $e) {
            throw new SpreadsheetParsingException('Error parsing XLSX internal files');
        }
    }

    /**
     * @param $numRows
     * @param $skipLine
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     *
     * @return array
     */
    protected function readDataFromFile($numRows, $skipLine)
    {
        $data = [];
        $rows = $this->sheetXml->sheetData->row;
        foreach ($rows as $row) {
            if ($skipLine) {
                $skipLine = false;
                continue;
            }

            $data[] = $this->columnNames = $this->getRowContent($row, $this->sharedStringsXml);

            $numRows--;
            if ($numRows == 0) {
                break;
            }
        }

        return $data;
    }
}
