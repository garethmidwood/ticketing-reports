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
            'timeDate' => isset($timeSession) ? $timeSession->getSessionDate()->format('Y-m-d') : null,
            'timeMessage' => isset($timeSession) ? $timeSession->getSummary() : null,
            'timeUserFirstName' => (isset($timeSession) && $timeSession->getUser() !== null) ? $timeSession->getUser()->getFirstName() : null,
            'timeUserLastName' => (isset($timeSession) && $timeSession->getUser() !== null) ? $timeSession->getUser()->getLastName() : null
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
