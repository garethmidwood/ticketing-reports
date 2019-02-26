<?php

namespace GarethMidwood\TicketReporting\Report;

use GarethMidwood\CodebaseHQ\Project\Project;
use GarethMidwood\TicketReporting\Time\Period;

class ProjectOverview extends Report
{
    protected function gatherData(Period $period) : array
    {
        echo 'getting projects' . PHP_EOL;

        $projects = $this->system->projects();

        var_dump($projects);

        echo 'gathering project data' . PHP_EOL;

        $data = [];

        foreach($projects as $project) {
            echo 'gathering data for ' . $project->getName() . PHP_EOL;
            $this->gatherProjectData($project, $data);
        }

        return $data;
    }

    /**
     * Populates data for a project, returns the 
     * @param Project &$project 
     * @return type
     */
    private function gatherProjectData(Project &$project, array &$data)
    {
        $data[] = ['name' => $project->getName()];
    }
}
