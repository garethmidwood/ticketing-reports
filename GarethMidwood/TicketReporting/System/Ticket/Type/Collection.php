<?php 

namespace GarethMidwood\TicketReporting\System\Ticket\Type;

use GarethMidwood\TicketReporting\Base\Collection as BaseCollection;

class Collection extends BaseCollection
{
    /**
     * Adds a ticket type to the collection
     * @param Type $type 
     * @return void
     */
    public function addTicketType(Type $type)
    {
        $this->addItem($type->getId(), $type);
    }
}
