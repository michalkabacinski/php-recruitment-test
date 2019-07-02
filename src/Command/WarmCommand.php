<?php

namespace Snowdog\DevTest\Command;

use mk85\sitemap\Extractor;
use Old_Legacy_CacheWarmer_Actor;
use Old_Legacy_CacheWarmer_Resolver_Method;
use Old_Legacy_CacheWarmer_Warmer;
use Snowdog\DevTest\Model\PageManager;
use Snowdog\DevTest\Model\VarnishManager;
use Snowdog\DevTest\Model\WebsiteManager;
use Symfony\Component\Console\Output\OutputInterface;

class WarmCommand
{

    /** @var Old_Legacy_CacheWarmer_Actor $actor */
    private $actor;

    /** @var PageManager $pageManager */
    private $pageManager;

    /** @var Old_Legacy_CacheWarmer_Resolver_Method $resolver */
    private $resolver;

    /** @var Old_Legacy_CacheWarmer_Warmer $warmer */
    private $warmer;

    /** @var WebsiteManager $websiteManager */
    private $websiteManager;

    /** @var VarnishManager $varnishManager */
    private $varnishManager;

    private $sitemapExtractor;

    public function __construct(
        Old_Legacy_CacheWarmer_Actor $actor,
        Old_Legacy_CacheWarmer_Resolver_Method $resolver,
        Old_Legacy_CacheWarmer_Warmer $warmer,
        PageManager $pageManager,
        VarnishManager $varnishManager,
        WebsiteManager $websiteManager,
        Extractor $sitemapExtractor
    ) {
        $this->actor = $actor;
        $this->pageManager = $pageManager;
        $this->resolver = $resolver;
        $this->sitemapExtractor = $sitemapExtractor;
        $this->warmer = $warmer;
        $this->varnishManager = $varnishManager;
        $this->websiteManager = $websiteManager;
    }

    public function __invoke($id, OutputInterface $output)
    {
        $website = $this->websiteManager->getById($id);
        $varnishes = $this->varnishManager->getByWebsite($website);

        if ($website) {
            if (count($varnishes) == 0) {
                $output->writeln('<error>There are not varnishes connected with website with ID ' . $id . '!</error>');

                return;
            }

            $pages = $this->pageManager->getAllByWebsite($website);

            $this->actor->setActor(function ($hostname, $ip, $url) use ($output) {
                $output->writeln('Visited <info>http://' . $hostname . '/' . $url . '</info> via IP: <comment>' . $ip
                    . '</comment>');
            });
            $this->warmer->setResolver($this->resolver);
            $this->warmer->setHostname($website->getHostname());
            $this->warmer->setActor($this->actor);
            $this->warmer->setPageManager($this->pageManager);

            foreach ($pages as $page) {
                foreach ($varnishes as $varnish) {
                    $this->warmer->warm($page, $varnish);
                }
            }
        } else {
            $output->writeln('<error>Website with ID ' . $id . ' does not exists!</error>');
        }
    }
}