<?php 

namespace GarethMidwood\TicketReporting\System\Ticket;

use GarethMidwood\TicketReporting\Base\Collection as BaseCollection;

class Collection extends BaseCollection
{
    /**
     * Adds a ticket to the collection
     * @param Ticket $ticket 
     * @return void
     */
    public function addTicket(Ticket $ticket)
    {
        $this->addItem($ticket->getId(), $ticket);
    }
}
