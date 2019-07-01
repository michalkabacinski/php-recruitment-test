<?php

interface Old_Legacy_CacheWarmer_Resolver_Interface
{
    public function getIp($hostname);
}

class Old_Legacy_CacheWarmer_Resolver_Method implements Old_Legacy_CacheWarmer_Resolver_Interface 
{
    public function getIp($hostname)
    {
        return gethostbyname($hostname);
    }
}

class Old_Legacy_CacheWarmer_Actor
{
    private $callable;

    public function setActor($callable) {
        $this->callable = $callable;
    }
    
    public function act($hostname, $ip, $url)
    {
        call_user_func($this->callable, $hostname, $ip, $url);
    }
}

class Old_Legacy_CacheWarmer_Warmer
{
    /** @var Old_Legacy_CacheWarmer_Actor */
    private $actor;
    /** @var Old_Legacy_CacheWarmer_Resolver_Interface */
    private $resolver;
    /** @var string */
    private $hostname;
    /** @var \Snowdog\DevTest\Model\PageManager $pageManager */
    private $pageManager;

    /**
     * @param Old_Legacy_CacheWarmer_Actor $actor
     */
    public function setActor($actor)
    {
        $this->actor = $actor;
    }

    /**
     * @param string $hostname
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;
    }

    /**
     * @param Old_Legacy_CacheWarmer_Resolver_Interface $resolver
     */
    public function setResolver($resolver)
    {
        $this->resolver = $resolver;
    }


    public function setPageManager($pageManager)
    {
        $this->pageManager = $pageManager;
    }

    public function warm(\Snowdog\DevTest\Model\Page $page, \Snowdog\DevTest\Model\Varnish $varnish) {
        $ip = $varnish->getIp();//$this->resolver->getIp($this->hostname);
        sleep(1); // this emulates visit to http://$hostname/$url via $ip
        $this->actor->act($this->hostname, $ip, $page->getUrl());
        $this->pageManager->addVisitDate($page);
    }
    
}

