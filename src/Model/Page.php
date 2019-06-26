<?php

namespace Snowdog\DevTest\Model;

use DateTime;

class Page
{
    public $page_id;
    public $url;
    /** @var \DateTime $visit_date */
    public $visit_date;
    public $website_id;
    
    public function __construct()
    {
        $this->website_id = intval($this->website_id);
        $this->page_id = intval($this->page_id);
    }

    /**
     * @return int
     */
    public function getPageId()
    {
        return $this->page_id;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string|null
     */
    public function getVisitDate()
    {
        return $this->visit_date
            ? (new DateTime($this->visit_date))->format('Y-m-d')
            : null;
    }

    /**
     * @return int
     */
    public function getWebsiteId()
    {
        return $this->website_id;
    }
}
