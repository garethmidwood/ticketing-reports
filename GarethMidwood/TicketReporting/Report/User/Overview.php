<?php

namespace GarethMidwood\TicketReporting\Report\User;

use GarethMidwood\TicketReporting\Report\Report;
use GarethMidwood\TicketReporting\System\Project;
use GarethMidwood\TicketReporting\System\Ticket;
use GarethMidwood\TicketReporting\System\TimeSession;

class Overview extends Report
{
    /**
     * @inheritdoc
     */
    protected function getReportName() : string
    {
        return 'User Overview Report';
    }

    /**
     * Adds a row of data
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
            'ticketStatus' => $ticket->getStatus()->getName(),
            'timeMinutes' => isset($timeSession) ? $timeSession->getMinutes() : null,
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

        $period = $this->data->getPeriod();

        foreach($this->data->getUsers() as $user) {
            if (!$user->getEnabled()) {
                continue;
            }

            $timeSessionsInPeriod = $user->getTimeSessions()->filterByPeriod($period);

            if ($timeSessionsInPeriod->getCount() == 0) {
                continue;
            }

            $output .= '<h2>' . $user->getFirstName() . ' ' . $user->getLastName() . '</h2>';
            $output .= '<h3>Time Sessions Logged : ' . $user->getTimeSessions()->getCount() . '</h3>';
            $output .= '<table class="sortable"><thead><th>Project</th><th>Ticket ID</th><th>Ticket Summary</th><th>Time Session Message</th><th>Time (minutes)</th><th>Estimate (minutes)</th><th>Date</th></thead><tbody>';

            $totalTime = 0;

            foreach($timeSessionsInPeriod as $timeSession) {
                $project = $this->data->getProjects()->searchById($timeSession->getProjectId());
                $ticket = $project->getTickets()->searchById($timeSession->getTicketId());

                $output .= '<tr><td>' . $project->getName() . '</td><td>' . $ticket->getId() . '</td><td>' .$ticket->getSummary() . '</td><td>' . $timeSession->getSummary() . '</td><td>' . $timeSession->getMinutes() . '</td><td>' . $ticket->getEstimate() . '</td><td>' . $timeSession->getSessionDate()->format('Y-m-d') . '</td></tr>';
                $totalTime += $timeSession->getMinutes();
            }

            $output .= '<tfoot><tr><td></td><td></td><td></td><td>Total</td><td>' . $totalTime . '</td><td></td><td></td></tr></tfoot>';

            $output .= '</tbody></table>';
        }

        return $output;
    }
}
