<?php 

namespace GarethMidwood\TicketReporting\System\Ticket\Category;

use GarethMidwood\TicketReporting\Base\Collection as BaseCollection;

class Collection extends BaseCollection
{
    /**
     * Adds a ticket category to the collection
     * @param Category $category 
     * @return void
     */
    public function addTicketCategory(Category $category)
    {
        $this->addItem($category->getId(), $category);
    }
}
