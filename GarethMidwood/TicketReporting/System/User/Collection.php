<?php 

namespace GarethMidwood\TicketReporting\System\User;

use GarethMidwood\TicketReporting\Base\Collection as BaseCollection;

class Collection extends BaseCollection
{
    /**
     * Adds a user to the collection
     * @param User $user 
     * @return void
     */
    public function addUser(User $user)
    {
        $this->addItem($user->getId(), $user);
    }
}
