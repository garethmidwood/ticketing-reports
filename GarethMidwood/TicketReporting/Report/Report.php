<?php

namespace GarethMidwood\TicketReporting\Report;

use GarethMidwood\TicketReporting\ReportFormat\ReportFormat;
use GarethMidwood\TicketReporting\System\System;
use GarethMidwood\TicketReporting\System\TimeSession\Period;

abstract class Report
{
    /**
     * @var System
     */
    protected $system;
    /**
     * @var ReportFormat
     */
    protected $formatter;

    /**
     * Constructor
     * @param System $system 
     * @param ReportFormat $formatter
     * @return void
     */
    public function __construct(System &$system, ReportFormat $formatter)
    {
        $this->system = $system;
        $this->formatter = $formatter;
    }

    /**
     * Generates the report
     * @param string $outFile path to file to write output
     * @param Period $period
     * @throws \Exception
     * @return void
     */
    public function generate(string $outFile, Period $period)
    {
        if (!$this->prepForFileWrite($outFile)) {
            throw new \Exception('Could not write file ' . $outFile);
        }

        $data = $this->gatherData($period);

        $this->formatter->generate($outFile, $data);
    }

    /**
     * Creates directory for writing output file
     * @param string $outFile 
     * @return bool
     */
    private function prepForFileWrite(string $outFile)
    {
        $dir = dirname($outFile);

        if (!is_dir($dir)) {
            return mkdir($dir, 0755, true);
        }

        return is_writable($outFile);
    }

    /**
     * Gathers the data, returns it as an array
     * @return array
     */
    protected abstract function gatherData(Period $period) : array;
}
