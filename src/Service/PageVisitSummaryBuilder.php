<?php

namespace Snowdog\DevTest\Service;

use Snowdog\DevTest\DataTransferObject\PageVisitSummary;
use Snowdog\DevTest\Model\PageManager;
use Snowdog\DevTest\Model\UserManager;

class PageVisitSummaryBuilder
{
    private $pageManager;
    private $user;

    public function __construct(PageManager $pageManager, UserManager $userManager)
    {
        $this->pageManager = $pageManager;
        if (isset($_SESSION['login'])) {
            $this->user = $userManager->getByLogin($_SESSION['login']);
        }
    }

    public function build(): PageVisitSummary
    {
        if ($this->user) {
            $summary = new PageVisitSummary();

            $summary->setTotalPages($this->getTotalPages());
            $summary->setLeastVisitedPage($this->getLeastVisitedPage());
            $summary->setMostVisitedPage($this->getMostVisitedPage());

            return $summary;
        }

        return new PageVisitSummary();
    }

    private function getTotalPages(): int
    {
        return $this->pageManager->getUsersPagesCount($this->user->getUserId());
    }

    private function getLeastVisitedPage(): string
    {
        return $this->pageManager->getLeastVisitedPage($this->user->getUserId());
    }

    private function getMostVisitedPage(): string
    {
        return $this->pageManager->getMostVisitedPage($this->user->getUserId());
    }
}
