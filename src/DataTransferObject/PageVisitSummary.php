<?php

namespace Snowdog\DevTest\DataTransferObject;

class PageVisitSummary
{
    private $totalPages;
    private $leastVisitedPage;
    private $mostVisitedPage;

    public function getTotalPages(): int
    {
        return $this->totalPages ?? 0;
    }

    public function setTotalPages(int $totalPages)
    {
        $this->totalPages = $totalPages;
    }

    public function getLeastVisitedPage(): string
    {
        return $this->leastVisitedPage ?? '';
    }

    public function setLeastVisitedPage(string $leastVisitedPage)
    {
        $this->leastVisitedPage = $leastVisitedPage;
    }

    public function getMostVisitedPage(): string
    {
        return $this->mostVisitedPage ?? '';
    }

    public function setMostVisitedPage(string $mostVisitedPage)
    {
        $this->mostVisitedPage = $mostVisitedPage;
    }
}
