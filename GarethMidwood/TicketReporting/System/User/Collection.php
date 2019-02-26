<?php 

namespace GarethMidwood\TicketReporting\System\User;

class Collection
{
    /**
     * @var array
     */
    private $collection = [];

    /**
     * Adds a user to the collection
     * @param User $user 
     * @return void
     */
    public function addUser(User $user)
    {
        $this->collection[] = $user;
    }
}
