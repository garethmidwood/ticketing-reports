<?php 

namespace GarethMidwood\TicketReporting\System\Project;

class Collection
{
    /**
     * @var array
     */
    private $collection = [];

    /**
     * Adds a project to the collection
     * @param Project $project 
     * @return void
     */
    public function addProject(Project $project)
    {
        $this->collection[] = $project;
    }
}
