<?php 

namespace GarethMidwood\TicketReporting\System\TimeSession;

use GarethMidwood\TicketReporting\Base\Collection as BaseCollection;
use GarethMidwood\TicketReporting\System\TimeSession\Period;

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

    /**
     * Returns a collection of time sessions that were logged in the given period
     * @param Period $period
     * @return Collection
     */
    public function filterByPeriod(Period $period) : Collection
    {
        $collection = new Collection();

        foreach($this as $timeSession) {
            if ($period->inPeriod($timeSession->getSessionDate())) {
                $collection->addTimeSession($timeSession);
            }
        }

        return $collection;
    }
}
