<?php 

namespace GarethMidwood\TicketReporting\ReportFormat;

class Csv implements ReportFormat
{
    public function generate(string $outFile, array $data)
    {
        file_put_contents($outFile, json_encode($data));
    }
}
