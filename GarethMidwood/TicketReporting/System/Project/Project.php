<?php

namespace GarethMidwood\TicketReporting\System\Project;

use GarethMidwood\TicketReporting\System\Ticket;
use GarethMidwood\TicketReporting\System\TimeSession;
use GarethMidwood\TicketReporting\System\User;

class Project
{
    private $id;
    private $name;
    private $status;
    private $permalink;
    private $tickets;
    private $timeSessions;

    /**
     * Constructor
     * @param int $id 
     * @param string $name 
     * @param string $status 
     * @param string $permalink 
     * @param Ticket\Collection $tickets 
     * @param TimeSession\Collection $timeSessions
     * @return type
     */
    public function __construct(
        int $id,
        string $name,
        string $status,
        string $permalink,
        Ticket\Collection $tickets,
        TimeSession\Collection $timeSessions
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->status = $status;
        $this->permalink = $permalink;
        $this->tickets = $tickets;
        $this->timeSessions = $timeSessions;
    }

    /**
     * Gets the project id
     * @return mixed
     */
    public function getId() 
    {
        return $this->id;
    }

    /**
     * Gets the project name
     * @return string
     */
    public function getName() 
    {
        return $this->name;
    }   

    /**
     * Gets the project status
     * @return string
     */
    public function getStatus() 
    {
        return $this->status;
    }

    /**
     * Gets the project permalink
     * @return string
     */
    public function getPermalink() 
    {
        return $this->permalink;
    }

    /**
     * Gets the project tickets
     * @return Ticket\Collection
     */
    public function getTickets(bool $activeOnly = false) : Ticket\Collection
    {
        return $activeOnly ? $this->tickets->activeOnly() : $this->tickets;
    }

    /**
     * Returns time session collection
     * @return TimeSession\Collection
     */
    public function getTimeSessions()
    {
        return $this->timeSessions;
    }    

    /**
     * Adds a time session to this project
     * @param TimeSession\TimeSession $timeSession
     * @return Project
     */
    public function addTimeSession(TimeSession\TimeSession $timeSession)
    {
        $this->timeSessions->addTimeSession($timeSession);

        return $this;
    }

    /**
     * Populates each user with the time sessions in this project
     * @param User\Collection $users
     * @param bool $activeTicketsOnly
     * @return void
     */
    public function populateUserTimeSessions(User\Collection &$users, bool $activeTicketsOnly = false)
    {
        $tickets = $this->getTickets($activeTicketsOnly);
     
        foreach($tickets as $ticket) {
            $timeSessions = $ticket->getTimeSessions();

            foreach($timeSessions as $timeSession) {
                $timeSessionUser = $timeSession->getUser();

                $user = $users->searchById($timeSessionUser->getId());

                if (isset($user)) {
                    $user->addTimeSession($timeSession);
                }
            }
        }
    }
}
