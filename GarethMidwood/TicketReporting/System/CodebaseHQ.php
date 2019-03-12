<?php

namespace GarethMidwood\TicketReporting\System;

use GarethMidwood\CodebaseHQ\CodebaseHQAccount;
use GarethMidwood\CodebaseHQ\Project as CodebaseProject;
use GarethMidwood\CodebaseHQ\Ticket\Category as CodebaseTicketCategory;
use GarethMidwood\CodebaseHQ\Ticket\Priority as CodebaseTicketPriority;
use GarethMidwood\CodebaseHQ\Ticket\Status as CodebaseTicketStatus;
use GarethMidwood\CodebaseHQ\Ticket\Type as CodebaseTicketType;
use GarethMidwood\CodebaseHQ\TimeSession as CodebaseTimeSession;
use GarethMidwood\CodebaseHQ\TimeSession\Period as CodebasePeriod;
use GarethMidwood\CodebaseHQ\User as CodebaseUser;
use GarethMidwood\TicketReporting\System\Project;
use GarethMidwood\TicketReporting\System\User;
use GarethMidwood\TicketReporting\System\Ticket\Category;
use GarethMidwood\TicketReporting\System\Ticket\Priority;
use GarethMidwood\TicketReporting\System\Ticket\Status;
use GarethMidwood\TicketReporting\System\Ticket\Type;
use GarethMidwood\TicketReporting\System\TimeSession;

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
            $normalisedUser = $this->createNormalisedUser($user);
            $this->users->addUser($normalisedUser);
        }

        return $users;
    }

    /**
     * Retrieves all users for the system
     * @param TimeSession\Period $period
     * @param string|null $projectName
     * @return array
     */
    public function populateProjectData(TimeSession\Period $period, string $projectName = null)
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
            echo 'populating categories for ' . $project->getName() . PHP_EOL;
            $this->populateCategories($project);
            echo 'populating priorities for ' . $project->getName() . PHP_EOL;
            $this->populatePriorities($project);
            echo 'populating statuses for ' . $project->getName() . PHP_EOL;
            $this->populateStatuses($project);
            echo 'populating types for ' . $project->getName() . PHP_EOL;
            $this->populateTypes($project);
            echo 'populating tickets for ' . $project->getName() . PHP_EOL;
            $this->populateTickets($project);
            echo 'populating times for ' . $project->getName() . PHP_EOL;
            $this->populateTimes($project, $codebasePeriod);

            $normalisedProject = $this->createNormalisedProject($project);
            $this->projects->addProject($normalisedProject);
        }

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
     * Populates categories for a project
     * @param CodebaseProject\Project &$project
     * @return void
     */
    private function populateCategories(CodebaseProject\Project &$project)
    {
        $this->codebaseHQ->categories($project);
    }

    /**
     * Populates priorities for a project
     * @param CodebaseProject\Project &$project
     * @return void
     */
    private function populatePriorities(CodebaseProject\Project &$project)
    {
        $this->codebaseHQ->priorities($project);
    }

    /**
     * Populates statuses for a project
     * @param CodebaseProject\Project &$project
     * @return void
     */
    private function populateStatuses(CodebaseProject\Project &$project)
    {
        $this->codebaseHQ->statuses($project);
    }

    /**
     * Populates types for a project
     * @param CodebaseProject\Project &$project
     * @return void
     */
    private function populateTypes(CodebaseProject\Project &$project)
    {
        $this->codebaseHQ->types($project);
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
     * @param TimeSession\Period $period 
     * @return type
     */
    public function getSystemDatePeriod(TimeSession\Period $period)
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


    /**
     * Converts system project into a normalised project
     * @param CodebaseProject\Project $project 
     * @return Project\Project
     */
    private function createNormalisedProject(CodebaseProject\Project $project) : Project\Project
    {
        $tickets = $this->createNormalisedTickets($project);

        $normalisedProject = new Project\Project(
            $project->getId(),
            $project->getName(),
            $project->getStatus(),
            $project->getPermalink(),
            $tickets
        );

        return $normalisedProject;
    }    
    
    /**
     * Converts system project tickets into normalised tickets
     * @param CodebaseProject\Project $project 
     * @return Ticket\Collection
     */
    private function createNormalisedTickets(CodebaseProject\Project $project) : Ticket\Collection
    {
        $collection = new Ticket\Collection();

        $tickets = $project->getTickets();

        foreach($tickets as $codebaseTicket) {
            // normalise entities
            $reporter = $codebaseTicket->getReporter() ?
                        $this->createNormalisedUser($codebaseTicket->getReporter()) :
                        null;
            $assignee = $codebaseTicket->getAssignee() ? 
                        $this->createNormalisedUser($codebaseTicket->getAssignee()) :
                        null;
            $category = $codebaseTicket->getCategory() ?
                        $this->createNormalisedCategory($codebaseTicket->getCategory()) :
                        null;
            $priority = $codebaseTicket->getPriority() ? 
                        $this->createNormalisedPriority($codebaseTicket->getPriority()) :
                        null;
            $status   = $codebaseTicket->getStatus() ? 
                        $this->createNormalisedStatus($codebaseTicket->getStatus()) : 
                        null;
            $type     = $codebaseTicket->getType() ?
                        $this->createNormalisedType($codebaseTicket->getType()) :
                        null;
            $timeSessions = $codebaseTicket->getTimeSessions() ? 
                        $this->createNormalisedTimeSessions($codebaseTicket->getTimeSessions()) :
                        null;

            $ticket = new Ticket\Ticket(
                $codebaseTicket->getId(),
                $codebaseTicket->getProjectId(),
                $codebaseTicket->getSummary(),
                $reporter, 
                $assignee,
                $category,
                $priority,
                $status,
                $type,
                $codebaseTicket->getEstimate(),
                $codebaseTicket->getUpdatedAt(),
                $codebaseTicket->getCreatedAt(),
                $codebaseTicket->getTotalTimeSpent(),
                $timeSessions
            );

            $collection->addTicket($ticket);
        }

        return $collection;
    }

    /**
     * Converts system user into normalised user
     * @param CodebaseUser\User $user 
     * @return User\User
     */
    private function createNormalisedUser(CodebaseUser\User $user) : User\User
    {
        $normalisedUser = new User\User(
            $user->getId(),
            $user->getUsername(),
            $user->getCompany(),
            $user->getEmailAddress(),
            $user->getFirstName(),
            $user->getLastName(),
            $user->getGravatarUrl(),
            $user->getEnabled()
        );

        return $normalisedUser;
    }

    /**
     * Converts system category into normalised category
     * @param CodebaseTicketCategory\Category $category 
     * @return Category\Category
     */
    private function createNormalisedCategory(CodebaseTicketCategory\Category $category) : Category\Category
    {
        $normalisedCategory = new Category\Category(
            $category->getId(),
            $category->getName()
        );

        return $normalisedCategory;
    }

    /**
     * Converts system priority into normalised priority
     * @param CodebaseTicketPriority\Priority $priority 
     * @return Priority\Priority
     */
    private function createNormalisedPriority(CodebaseTicketPriority\Priority $priority) : Priority\Priority
    {
        $normalisedPriority = new Priority\Priority(
            $priority->getId(),
            $priority->getName()
        );

        return $normalisedPriority;
    }

    /**
     * Converts system status into normalised status
     * @param CodebaseTicketStatus\Status $status 
     * @return Status\Status
     */
    private function createNormalisedStatus(CodebaseTicketStatus\Status $status) : Status\Status
    {
        $normalisedStatus = new Status\Status(
            $status->getId(),
            $status->getName()
        );

        return $normalisedStatus;
    }

    /**
     * Converts system type into normalised type
     * @param CodebaseTicketType\Type $type 
     * @return Type\Type
     */
    private function createNormalisedType(CodebaseTicketType\Type $type) : Type\Type
    {
        $normalisedType = new Type\Type(
            $type->getId(),
            $type->getName()
        );

        return $normalisedType;
    }

    /**
     * Converts system time session collection into normalised collection
     * @param CodebaseTimeSession\Collection $collection 
     * @return TimeSession\Collection
     */
    private function createNormalisedTimeSessions(CodebaseTimeSession\Collection $collection) : TimeSession\Collection
    {
        $normalisedTimeSessionCollection = new TimeSession\Collection();

        foreach($collection as $timeSession) {
            $user = $timeSession->getUser() ?
                    $this->createNormalisedUser($timeSession->getUser()) :
                    null;

            $normalisedTimeSessionCollection->addTimeSession(
                new TimeSession\TimeSession(
                    $timeSession->getId(),
                    $timeSession->getSummary(),
                    $timeSession->getMinutes(),
                    $timeSession->getSessionDate(),
                    $user,
                    $timeSession->getUpdatedAt(),
                    $timeSession->getCreatedAt()
                )
            );
        }

        return $normalisedTimeSessionCollection;
    }
}
