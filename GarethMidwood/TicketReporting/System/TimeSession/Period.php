<?php 

namespace GarethMidwood\TicketReporting\System\TimeSession;

class Period
{
    /**
     * @var \DateTime
     */
    private $startDate;
    /**
     * @var \DateTime
     */
    private $endDate;

    /**
     * Constructor
     * @param \DateTime $startDate 
     * @param \DateTime $endDate 
     * @return void
     */
    public function __construct(\DateTime $startDate, \DateTime $endDate)
    {
        $this->startDate = $startDate < $endDate ? $startDate : $endDate;
        $this->endDate = $startDate > $endDate ? $startDate : $endDate;
    }

    /**
     * Return true/false indicating if this period is a single day
     * @return bool
     */
    public function isDay()
    {
        $interval = $this->startDate->diff($this->endDate);

        return $interval->format('%a') == '0';
    }

    /**
     * Return true/false indicating if this period is a single week
     * @return bool
     */
    public function isWeek()
    {
        $interval = $this->startDate->diff($this->endDate);

        return $interval->format('%a') == '7';
    }

    /**
     * Return true/false indicating if this period is a single month
     * @return bool
     */
    public function isMonth()
    {
        $interval = $this->startDate->diff($this->endDate);

        return $interval->format('%a') <= 31 && $interval->format('%a') >= 28;
    }

    /**
     * Returns the start date for this period
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Returns the end date for this period
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Returns true if supplied date is within this period
     * @param \DateTime $datetime 
     * @return bool
     */
    public function inPeriod(\DateTime $datetime) : bool
    {
        return ($datetime >= $this->startDate && $datetime <= $this->endDate);
    }
}
