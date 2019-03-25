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
     * @inheritdoc
     */
    protected function gatherData(TimeSession\Period $period) : array
    {
        $data = [];

        foreach($this->projects as $project) {
            $this->populateProjectData($project, $period, $data);
        }

        return $data;
    }

    /**
     * Populates data for a project
     * @param Project\Project &$project 
     * @param TimeSession\Period $period
     * @return type
     */
    private function populateProjectData(
        Project\Project &$project, 
        TimeSession\Period $period,
        array &$data
    ) {
        $tickets = $project->getTickets();

        foreach($tickets as $ticket) {
            $ticketDateTime = $ticket->getUpdatedAt();

            // if it's not been updated then use the creation date
            if (!isset($ticketDateTime)) {
                $ticketDateTime = $ticket->getCreatedAt();
            }

            if (!$period->inPeriod($ticketDateTime)) {
                continue;
            }

            $this->addDataRow($data, $project, $ticket);
        }
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
}
