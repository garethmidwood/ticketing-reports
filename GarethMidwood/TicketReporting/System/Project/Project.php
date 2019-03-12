<?php

namespace GarethMidwood\TicketReporting\System\Project;

use GarethMidwood\TicketReporting\System\Ticket;

class Project
{
    private $id;
    private $name;
    private $status;
    private $permalink;
    private $tickets;

    /**
     * Constructor
     * @param int $id 
     * @param string $name 
     * @param string $status 
     * @param string $permalink 
     * @param Ticket\Collection $tickets 
     * @return type
     */
    public function __construct(
        int $id,
        string $name,
        string $status,
        string $permalink,
        Ticket\Collection $tickets
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->status = $status;
        $this->permalink = $permalink;
        $this->tickets = $tickets;
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
}
