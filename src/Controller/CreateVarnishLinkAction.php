<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\Varnish;
use Snowdog\DevTest\Model\VarnishManager;
use Snowdog\DevTest\Model\Website;
use Snowdog\DevTest\Model\WebsiteManager;

class CreateVarnishLinkAction
{
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var VarnishManager
     */
    private $varnishManager;
    /** @var WebsiteManager */
    private $websiteManager;

    public function __construct(
        UserManager $userManager,
        VarnishManager $varnishManager,
        WebsiteManager $websiteManager
    ) {
        $this->userManager = $userManager;
        $this->varnishManager = $varnishManager;
        $this->websiteManager = $websiteManager;
    }

    public function execute()
    {
        $link = (bool)$_POST['link'];
        $varnishId = $_POST['varnishId'];
        $websiteId = $_POST['websiteId'];

        $varnish = $this->varnishManager->getById($varnishId);
        $website = $this->websiteManager->getById($websiteId);

        if ($link) {
            $this->varnishManager->link($varnish, $website);

            return;
        }

        $this->varnishManager->unlink($varnish, $website);
    }
}