<?php

namespace GarethMidwood\TicketReporting\System;

use GarethMidwood\CodebaseHQ\CodebaseHQAccount;
use GarethMidwood\CodebaseHQ\Project as CodebaseProject;
use GarethMidwood\CodebaseHQ\TimeSession\Period as CodebasePeriod;
use GarethMidwood\TicketReporting\System\Project;
use GarethMidwood\TicketReporting\System\User;
use GarethMidwood\TicketReporting\Time\Period;

class CodebaseHQ implements System
{
    /**
     * @var CodebaseHQAccount
     */
    private $codebaseHQ;

    private $users;
    private $projects;

    /**
     * Constructor
     * @param CodebaseHQAccount $codebaseHQ 
     * @return void
     */
    public function __construct(CodebaseHQAccount $codebaseHQ)
    {
        $this->codebaseHQ = $codebaseHQ;

        $this->users = new User\Collection;
        $this->projects = new Project\Collection;
    }

    /**
     * Returns the user collection for this system
     * @return User\Collection
     */
    public function users() : User\Collection
    {
        return $this->users;
    }

    /**
     * Returns the project collection for this system
     * @return Project\Collection
     */
    public function projects() : Project\Collection
    {
        return $this->projects;
    }

    /**
     * Retrieves all users for the system
     * @return array
     */
    public function populateUserData()
    {
        echo 'gathering user data' . PHP_EOL;

        $users = $this->codebaseHQ->users();

        echo 'retrieved ' . $users->getCount() . ' total users' . PHP_EOL;

        foreach($users as $user) {
            // TODO: Convert this system user into a local user for adding to collection
            $normalisedUser = new User\User();
            $this->user->addUser($normalisedUser);
        }

        return $users;
    }

    /**
     * Retrieves all users for the system
     * @param Period $period
     * @param string|null $projectName
     * @return array
     */
    public function populateProjectData(Period $period, string $projectName = null)
    {
        $codebasePeriod = $this->getSystemDatePeriod($period);

        echo 'time period to gather data for is ' . $codebasePeriod->getPeriod() . PHP_EOL;

        echo 'getting list of projects' . PHP_EOL;

        if (isset($projectName)) {
            echo 'looking for specific project - ' . $projectName . PHP_EOL;
            $projectCollection = [$this->codebaseHQ->project($projectName)];
        } else {
            $projectCollection = $this->codebaseHQ->projects();
        }

        echo 'gathering project data' . PHP_EOL;

        foreach($projectCollection as $project) {
            echo 'populating tickets for ' . $project->getName() . PHP_EOL;
            $this->populateTickets($project);
            echo 'populating times for ' . $project->getName() . PHP_EOL;
            $this->populateTimes($project, $codebasePeriod);

            // TODO: Convert this system project into a local project for adding to collection
            $normalisedProject = new Project\Project();
            $this->projects->addProject($normalisedProject);
        }

        // TODO: Convert projects (and their tickets/times) into a plain array, or an
        // object that the ticket reporting system owns and is consistent across systems

        return $projectCollection;
    }

    /**
     * Populates tickets for a project
     * @param CodebaseProject\Project &$project 
     * @return void
     */
    private function populateTickets(CodebaseProject\Project &$project)
    {
        $pageNo = 1;
        $moreResultsToRetrieve = true;

        echo 'Loading tickets for "' . $project->getName() . '" ';

        while ($moreResultsToRetrieve) {
            echo '.';
            $moreResultsToRetrieve = $this->codebaseHQ->tickets($project, $pageNo);
            $pageNo++;
        }

        echo ' done' . PHP_EOL;
    }

    /**
     * Populates times for tickes on a project
     * @param CodebaseProject\Project &$project 
     * @param CodebasePeriod\Period $period 
     * @return void
     */
    private function populateTimes(CodebaseProject\Project &$project, CodebasePeriod\Period $period)
    {
        $this->codebaseHQ->times($project, $period);
    }

    /**
     * Returns the CodebaseHQ period object for the given date range
     * @param Period $period 
     * @return type
     */
    public function getSystemDatePeriod(Period $period)
    {
        if ($period->isDay()) {
            return new CodebasePeriod\Day;
        } elseif ($period->isWeek()) {
            return new CodebasePeriod\Week;
        } elseif ($period->isMonth()) {
            return new CodebasePeriod\Month;
        }

        return new CodebasePeriod\Day;
    }
}
