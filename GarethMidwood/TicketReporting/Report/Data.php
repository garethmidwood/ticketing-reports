<?php

namespace GarethMidwood\TicketReporting\Report;

use GarethMidwood\TicketReporting\System\TimeSession\Period;
use GarethMidwood\TicketReporting\System\User;
use GarethMidwood\TicketReporting\System\Project;

class Data
{
    /**
     * @var User\Collection
     */
    protected $users;
    /**
     * @var Project\Collection
     */
    protected $projects;
    /**
     * @var Period
     */
    protected $period;

    /**
     * Constructor
     * @param User\Collection $users
     * @param Project\Collection $projects 
     * @param Period $period
     * @return void
     */
    public function __construct(
        User\Collection $users,
        Project\Collection $projects,
        Period $period
    ) {
        $this->users = $users;
        $this->projects = $projects;
        $this->period = $period;
    }

    /**
     * Returns the data period
     * @return Period
     */
    public function getPeriod() : Period
    {
        return $this->period;
    }

    /**
     * Returns the project collection
     * @return Project\Collection
     */
    public function getProjects() : Project\Collection
    {
        return $this->projects;
    }

    /**
     * Returns the user collection
     * @return User\Collection
     */
    public function getUsers() : User\Collection
    {
        return $this->users;
    }

    /**
     * Returns data about the user timesessions in this period
     * @return 
     */
    public function getUserTimeSessionsFull()
    {
        $userTimeSessionsFull = [];

        foreach($this->projects as $project) {
            $tickets = $project->getTickets();

            foreach($tickets as $ticket) {
                $timeSessions = $ticket->getTimeSessions();

                foreach($timeSessions as $timeSession) {
                    $timeSessionUser = $timeSession->getUser();

                    $user = $this->users->searchById($timeSessionUser->getId());

                    if (isset($user)) {
                        $user->addTimeSession($timeSession);
                    }
                }
            }
        }
    }
}
