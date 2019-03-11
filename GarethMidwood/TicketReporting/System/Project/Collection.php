<?php 

namespace GarethMidwood\TicketReporting\System\Project;

use GarethMidwood\TicketReporting\Base\Collection as BaseCollection;

class Collection extends BaseCollection
{
    /**
     * Adds a project to the collection
     * @param Project $project 
     * @return void
     */
    public function addProject(Project $project)
    {
        $this->addItem($project->getId(), $project);
    }
}
