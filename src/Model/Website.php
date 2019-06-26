<?php

namespace Snowdog\DevTest\Model;

use DateTime;

class Website
{
    public $website_id;
    public $name;
    public $hostname;
    public $user_id;
    public $visit_date;

    public function __construct()
    {
        $this->user_id = intval($this->user_id);
        $this->website_id = intval($this->website_id);
    }

    /**
     * @return int
     */
    public function getWebsiteId()
    {
        return $this->website_id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
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
}
