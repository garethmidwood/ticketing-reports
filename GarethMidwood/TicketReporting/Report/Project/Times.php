<?php

namespace GarethMidwood\TicketReporting\Report\Project;

use GarethMidwood\TicketReporting\Report\Report;
use GarethMidwood\TicketReporting\System\Project;
use GarethMidwood\TicketReporting\System\Ticket;
use GarethMidwood\TicketReporting\System\TimeSession;

class Times extends Report
{
    /**
     * @inheritdoc
     */
    protected function getReportName() : string
    {
        return 'Ticket Times Report';
    }

    /**
     * @inheritdoc
     */
    protected function gatherData(TimeSession\Period $period) : array
    {
        $projects = $this->system->projects();

        $data = [];

        foreach($projects as $project) {
            $this->populateProjectData($project, $data);
        }

        return $data;
    }

    /**
     * Populates data for a project, returns the 
     * @param Project\Project &$project 
     * @return type
     */
    private function populateProjectData(Project\Project &$project, array &$data)
    {
        $tickets = $project->getTickets();

        foreach($tickets as $ticket) {
            $timeSessions = $ticket->getTimeSessions();

            if ($timeSessions->getCount() == 0) {
                continue;
            }

            foreach ($timeSessions as $timeSession) {
                $this->addDataRow($data, $project, $ticket, $timeSession);
            }
        }
    }

    /**
     * Adds a row of data to the csv
     * @param array &$data 
     * @param Project\Project $project 
     * @param Ticket\Ticket $ticket 
     * @param TimeSession\TimeSession|null $timeSession 
     * @return void
     */
    private function addDataRow(
        array &$data,
        Project\Project $project, 
        Ticket\Ticket $ticket,
        TimeSession\TimeSession $timeSession = null
    ) {
        $data[] = [
            'projectId' => $project->getId(),
            'projectName' => $project->getName(),
            'ticketId' => $ticket->getId(),
            'ticketSummary' => $ticket->getSummary(),
            'timeMinutes' => isset($timeSession) ? $timeSession->getMinutes() : null,
            'timeMessage' => isset($timeSession) ? $timeSession->getSummary() : null,
            'timeUserFirstName' => (isset($timeSession) && $timeSession->getUser() !== null) ? $timeSession->getUser()->getFirstName() : null,
            'timeUserLastName' => (isset($timeSession) && $timeSession->getUser() !== null) ? $timeSession->getUser()->getLastName() : null
        ];
    }
}