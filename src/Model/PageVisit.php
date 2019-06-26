<?php

namespace Snowdog\DevTest\Model;

use DateTime;

class PageVisit
{
    public $page_visit_id;
    public $page_id;
    /** @var DateTime $visit_date */
    public $visit_date;

    public function __construct()
    {
        $this->page_visit_id = intval($this->page_visit_id);
        $this->page_id = intval($this->page_id);
    }

    /**
     * @return int
     */
    public function getPageVisitId(): int
    {
        return $this->page_visit_id;
    }

    /**
     * @return int
     */
    public function getPageId(): int
    {
        return $this->page_id;
    }

    /**
     * @return DateTime
     */
    public function getVisitDate()
    {
        return $this->visit_date;
    }
}
