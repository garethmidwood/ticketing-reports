<?php

namespace GarethMidwood\TicketReporting\ReportFormat;

interface ReportFormat
{
    public function generate(string $outFile, array $data);
}
