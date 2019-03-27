<?php

namespace GarethMidwood\TicketReporting\Report\Project;

use GarethMidwood\TicketReporting\Report\Report;
use GarethMidwood\TicketReporting\System\Project;
use GarethMidwood\TicketReporting\System\Ticket;
use GarethMidwood\TicketReporting\System\TimeSession;

class TicketStatus extends Report
{
    /**
     * @inheritdoc
     */
    protected function getReportName() : string
    {
        return 'Ticket Status Report';
    }

    /**
     * Adds a row of data to the csv
     * @param array &$data 
     * @param Project\Project $project 
     * @param Ticket\Ticket $ticket
     * @return void
     */
    private function addDataRow(
        array &$data,
        Project\Project $project, 
        Ticket\Ticket $ticket
    ) {
        $timeSessions = $ticket->getTimeSessions();
        $trackedInPeriod = 0;

        foreach($timeSessions as $timeSession) {
            $trackedInPeriod += $timeSession->getMinutes();
        }

        $overEstimate = $trackedInPeriod > $ticket->getEstimate();

        $status = $ticket->getStatus();

        $data[] = [
            'projectId' => $project->getId(),
            'projectName' => $project->getName(),
            'ticketId' => $ticket->getId(),
            'ticketSummary' => $ticket->getSummary(),
            'ticketStatus' => $ticket->getStatus()->getName(),
            'ticketIsClosed' => (int)$ticket->getStatus()->isClosed(),
            'trackedInPeriod' => $trackedInPeriod,
            'ticketEstimate' => $ticket->getEstimate(),
            'overEstimate' => (int)$overEstimate,
            'needsAttention' => ($overEstimate && !$ticket->getStatus()->isClosed() ? 1 : 0)
        ];
    }

    /**
     * @inheritdoc
     */
    protected function generateHtmlBody() : string
    {
        $output = '';

        foreach($this->data->getProjects() as $project) {
            $output .= '<h2>' . $project->getName() . '</h2>';
        }

        return $output;
    }
}
