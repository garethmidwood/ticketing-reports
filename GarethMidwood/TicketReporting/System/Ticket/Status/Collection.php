<?php 

namespace GarethMidwood\TicketReporting\System\Ticket\Status;

use GarethMidwood\TicketReporting\Base\Collection as BaseCollection;

class Collection extends BaseCollection
{
    /**
     * Adds a ticket status to the collection
     * @param Status $status 
     * @return void
     */
    public function addTicketStatus(Status $status)
    {
        $this->addItem($status->getId(), $status);
    }
}
