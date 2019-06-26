<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\DataTransferObject\PageVisitSummary;
use Snowdog\DevTest\Model\User;
use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\WebsiteManager;
use Snowdog\DevTest\Service\PageVisitSummaryBuilder;

class IndexAction
{
    /**
     * @var PageVisitSummaryBuilder $summaryBuilder
     */
    private $summaryBuilder;
    /**
     * @var WebsiteManager
     */
    private $websiteManager;

    /**
     * @var User
     */
    private $user;

    /** @var PageVisitSummary */
    private $summary;

    public function __construct(
        PageVisitSummaryBuilder $summaryBuilder,
        UserManager $userManager,
        WebsiteManager $websiteManager
    ) {
        $this->summaryBuilder = $summaryBuilder;
        $this->websiteManager = $websiteManager;

        if (isset($_SESSION['login'])) {
            $this->user = $userManager->getByLogin($_SESSION['login']);
        }
    }

    protected function getSummary()
    {
        if ($this->summary === null) {
            $this->summary = $this->summaryBuilder->build();
        }

        return $this->summary;
    }

    protected function getWebsites()
    {
        if ($this->user) {
            return $this->websiteManager->getAllByUser($this->user);
        }
        return [];
    }

    public function execute()
    {
        require __DIR__ . '/../view/index.phtml';
    }
}
