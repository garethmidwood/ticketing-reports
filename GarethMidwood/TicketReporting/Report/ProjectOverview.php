<?php

namespace GarethMidwood\TicketReporting\Report;

use GarethMidwood\TicketReporting\System\Project\Project;
use GarethMidwood\TicketReporting\System\TimeSession\Period;

class ProjectOverview extends Report
{
    protected function gatherData(Period $period) : array
    {
        echo 'getting projects' . PHP_EOL;

        $projects = $this->system->projects();

        echo 'gathering project data' . PHP_EOL;

        $data = [];

        foreach($projects as $project) {
            echo 'gathering data for ' . $project->getName() . PHP_EOL;
            $this->populateProjectData($project, $data);
        }

        echo 'done gathering data' . PHP_EOL;

        return $data;
    }

    /**
     * Populates data for a project, returns the 
     * @param Project &$project 
     * @return type
     */
    private function populateProjectData(Project &$project, array &$data)
    {
        $tickets = $project->getTickets();

        foreach($tickets as $ticket) {
            $timeSessions = $ticket->getTimeSessions();

            foreach($timeSessions as $timeSession) {
                $data[] = [
                    'projectId' => $project->getId(),
                    'projectName' => $project->getName(),
                    'ticketId' => $ticket->getId(),
                    'ticketSummary' => $ticket->getSummary(),
                    'timeMinutes' => $timeSession->getMinutes(),
                    'timeMessage' => $timeSession->getSummary(),
                    'timeUserFirstName' => ($timeSession->getUser() !== null) ? $timeSession->getUser()->getFirstName() : 'unknown'
                ];
            }
        }
    }
}
