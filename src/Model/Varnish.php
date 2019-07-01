<?php

namespace Snowdog\DevTest\Model;

class Varnish
{
    public $ip;
    public $user_id;
    public $varnish_id;
    
    public function __construct()
    {
        $this->user_id = intval($this->user_id);
        $this->varnish_id = intval($this->varnish_id);
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getVarnishId(): int
    {
        return $this->varnish_id;
    }
}
