<?php 

namespace GarethMidwood\TicketReporting\System\Ticket\Category;

use GarethMidwood\TicketReporting\Base\Collection as BaseCollection;

class Collection extends BaseCollection
{
    /**
     * Adds a ticket priority to the collection
     * @param Priority $priority 
     * @return void
     */
    public function addTicketPriority(Priority $priority)
    {
        $this->addItem($priority->getId(), $priority);
    }
}
