<?php 

namespace GarethMidwood\TicketReporting\System\TimeSession;

use GarethMidwood\TicketReporting\Base\Collection as BaseCollection;

class Collection extends BaseCollection
{
    /**
     * Adds a time session to the collection
     * @param TimeSession $timeSession 
     * @return void
     */
    public function addTimeSession(TimeSession $timeSession)
    {
        $this->addItem($timeSession->getId(), $timeSession);
    }
}
