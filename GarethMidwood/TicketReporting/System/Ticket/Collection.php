<?php 

namespace GarethMidwood\TicketReporting\System\Ticket;

use GarethMidwood\TicketReporting\Base\Collection as BaseCollection;
use GarethMidwood\TicketReporting\System\TimeSession\Period;

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
     * @return Collection
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

    /**
     * Returns a collection of tickets that were created/updated in the given period
     * @param Period $period
     * @return Collection
     */
    public function filterByPeriod(Period $period) : Collection
    {
        $collection = new Collection();

        foreach($this as $ticket) {
            $ticketDateTime = $ticket->getLastUpdatedAt();

            if ($period->inPeriod($ticketDateTime)) {
                $collection->addTicket($ticket);
            }
        }

        return $collection;
    }
}
