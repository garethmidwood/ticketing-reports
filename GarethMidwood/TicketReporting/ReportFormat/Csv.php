<?php 

namespace GarethMidwood\TicketReporting\ReportFormat;

class Csv implements ReportFormat
{
    public function generate(string $outFile, array $data)
    {
        file_put_contents($outFile, implode(',', array_keys($data[0])) . PHP_EOL);

        foreach($data as $row) {
            file_put_contents($outFile, implode(',', $row) . PHP_EOL, FILE_APPEND);
        }
    }
}
