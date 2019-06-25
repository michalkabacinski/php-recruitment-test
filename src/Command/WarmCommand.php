<?php

namespace Snowdog\DevTest\Command;

use Old_Legacy_CacheWarmer_Actor;
use Old_Legacy_CacheWarmer_Resolver_Method;
use Old_Legacy_CacheWarmer_Warmer;
use Snowdog\DevTest\Model\PageManager;
use Snowdog\DevTest\Model\WebsiteManager;
use Symfony\Component\Console\Output\OutputInterface;

class WarmCommand
{

    /**
     * @var Old_Legacy_CacheWarmer_Actor $actor
     */
    private $actor;

    /**
     * @var PageManager $pageManager
     */
    private $pageManager;

    /**
     * @var Old_Legacy_CacheWarmer_Resolver_Method $resolver
     */
    private $resolver;

    /**
     * @var Old_Legacy_CacheWarmer_Warmer $warmer
     */
    private $warmer;

    /**
     * @var WebsiteManager $websiteManager
     */
    private $websiteManager;
    

    public function __construct(
        Old_Legacy_CacheWarmer_Actor $actor,
        Old_Legacy_CacheWarmer_Resolver_Method $resolver,
        Old_Legacy_CacheWarmer_Warmer $warmer,
        PageManager $pageManager,
        WebsiteManager $websiteManager
    ) {
        $this->actor = $actor;
        $this->pageManager = $pageManager;
        $this->resolver = $resolver;
        $this->warmer = $warmer;
        $this->websiteManager = $websiteManager;
    }

    public function __invoke($id, OutputInterface $output)
    {
        $website = $this->websiteManager->getById($id);
        if ($website) {
            $pages = $this->pageManager->getAllByWebsite($website);

            $this->actor->setActor(function ($hostname, $ip, $url) use ($output) {
                $output->writeln('Visited <info>http://' . $hostname . '/' . $url . '</info> via IP: <comment>' . $ip 
                    . '</comment>');
            });
            $this->warmer->setResolver($this->resolver);
            $this->warmer->setHostname($website->getHostname());
            $this->warmer->setActor($this->actor);

            foreach ($pages as $page) {
                $this->warmer->warm($page->getUrl());
            }
        } else {
            $output->writeln('<error>Website with ID ' . $id . ' does not exists!</error>');
        }
    }
}