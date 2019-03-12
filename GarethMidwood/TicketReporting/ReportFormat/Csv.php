<?php 

namespace GarethMidwood\TicketReporting\ReportFormat;

class Csv implements ReportFormat
{
    /**
     * Generates a csv report
     * @param string $outFile file path to write to
     * @param array $data 
     * @return type
     */
    public function generate(string $outFile, array $data)
    {
        $fp = fopen($outFile, 'w');

        if ($fp === false) {
            throw new \Exception('Cannot write file ' . $outFile);
        }

        // write the headers
        fputcsv($fp, array_keys($data[0]));

        foreach($data as $row) {
            fputcsv($fp, $row);
        }
    }
}
