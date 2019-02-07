<?php

declare(strict_types=1);

namespace Linio\Component\SpreadsheetParser\Parser;

interface ParserInterface
{
    /**
     * @param string $filePath
     */
    public function __construct($filePath, array $options = []);

    /**
     * @return bool
     */
    public function open();

    /**
     * @return array
     */
    public function getColumnNames();

    /**
     * @param int $numRows
     *
     * @return array
     */
    public function getData($numRows = 0);

    /**
     * @return bool
     */
    public function close();
}
