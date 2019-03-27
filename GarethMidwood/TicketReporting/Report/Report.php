<?php

namespace GarethMidwood\TicketReporting\Report;

use GarethMidwood\TicketReporting\ReportFormat\ReportFormat;
use GarethMidwood\TicketReporting\System\System;
use GarethMidwood\TicketReporting\System\TimeSession\Period;
use GarethMidwood\TicketReporting\System\User;
use GarethMidwood\TicketReporting\System\Project;

abstract class Report
{
    /**
     * @var Data
     */
    protected $data;

    /**
     * Constructor
     * @param Data $data
     * @return void
     */
    public function __construct(Data $data)
    {
        $this->data = $data;
    }

    /**
     * Generates the report
     * @param string $outFile path to file to write output
     * @param Period $period
     * @throws \Exception
     * @return void
     */
    public function generate(string $outFile)
    {
        if (!$this->prepForFileWrite($outFile)) {
            throw new \Exception('Could not write file ' . $outFile);
        }

        echo 'Generating ' . $this->getReportName() . PHP_EOL;

        $data = $this->generateHtml();

        $this->writeOutFile($outFile, $data);
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

        return (!file_exists($outFile) || is_writable($outFile));
    }

    /**
     * Writes the output file with the given data
     * @param string $outFile 
     * @param string $data 
     * @return void
     */
    private function writeOutFile(string $outFile, string $data)
    {
        if (empty($data)) {
            echo 'No data to write for ' . $this->getReportName() . PHP_EOL;
            return;
        }

        file_put_contents($outFile, $data);
    }

    /**
     * Returns the report name for display
     * @return string
     */
    protected abstract function getReportName() : string;

    /**
     * Generates the report as HTML
     * @param Period $period 
     * @return string
     */
    protected abstract function generateHtmlBody() : string;

    /**
     * Generates the report HTML, including calling the body generation method from the implementing class
     */
    protected function generateHtml() : string
    {
        $output = '<html><head><script src="sorttable.js" type="text/javascript"></script></head><body>';

        $output .= $this->generateReportFrontPage();

        $output .= $this->generateHtmlBody();

        $output .= $this->generateHtmlFooter();

        $output .= '</body></html>';

        return $output;
    }

    /**
     * Generates the front page of the report
     * @return string
     */
    private function generateReportFrontPage()
    {
        $period = $this->data->getPeriod();

        return '<h1>' . $this->getReportName() . '</h1><h2>' . $period->getStartDate()->format('d-m-Y') . ' - ' . $period->getEndDate()->format('d-m-Y') . '</h2>';
    }

    /**
     * Generates the footer of the report
     * @return string
     */
    private function generateHtmlFooter() 
    {
        $now = new \DateTime();
        return '<footer>report generated on ' . $now->format('d-m-Y') . ' at ' . $now->format('H:i:s') . '</footer>';
    }
}
