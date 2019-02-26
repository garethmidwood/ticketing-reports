<?php

namespace GarethMidwood\TicketReporting\System;

use GarethMidwood\TicketReporting\System\Project;
use GarethMidwood\TicketReporting\System\User;
use GarethMidwood\TicketReporting\Time\Period;


interface System 
{
    public function users() : User\Collection;

    public function projects() : Project\Collection;

    public function populateUserData();

    public function populateProjectData(Period $period);

    public function getSystemDatePeriod(Period $period);
}
