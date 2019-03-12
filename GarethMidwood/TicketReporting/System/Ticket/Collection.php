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

    /**
     * Returns a collection of active tickets
     * @return type
     */
    public function activeOnly() : Collection
    {
        $collection = new Collection();

        foreach($this as $ticket) {
            $status = $ticket->getStatus();

            if (!$status->isClosed()) {
                $collection->addTicket($ticket);
            }
        }

        return $collection;
    }
}
