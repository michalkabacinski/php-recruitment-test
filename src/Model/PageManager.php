<?php

namespace Snowdog\DevTest\Model;

use DateTime;
use PDO;
use Snowdog\DevTest\Core\Database;

class PageManager
{
    const DESC = 'DESC';
    const ASC = 'ASC';

    /**
     * @var Database|PDO
     */
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * @return Page[]
     */
    public function getAllByWebsite(Website $website)
    {
        $websiteId = $website->getWebsiteId();

        $query = $this->database->prepare('
            SELECT pages.*, MAX(visit_date) AS last_visit_date
            FROM pages
            LEFT JOIN page_visits ON pages.page_id = page_visits.page_id
            WHERE website_id = :website
            GROUP BY pages.page_id
        ');
        $query->bindParam(':website', $websiteId, PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_CLASS, Page::class);
    }

    public function create(Website $website, $url)
    {
        $websiteId = $website->getWebsiteId();

        $statement = $this->database->prepare('
            INSERT INTO pages (url, website_id)
            VALUES (:url, :website)
        ');
        $statement->bindParam(':url', $url, PDO::PARAM_STR);
        $statement->bindParam(':website', $websiteId, PDO::PARAM_INT);
        $statement->execute();

        return $this->database->lastInsertId();
    }

    public function addVisitDate(Page $page)
    {
        $pageId = $page->getPageId();
        $visitDate = (new DateTime())->format('Y-m-d H:i:s');

        $statement = $this->database->prepare('
            INSERT INTO page_visits (page_id, visit_date)
            VALUES (:page_id, :visit_date)
        ');
        $statement->bindParam(':visit_date', $visitDate, PDO::PARAM_STR);
        $statement->bindParam(':page_id', $pageId, PDO::PARAM_INT);
        $statement->execute();
    }

    public function getUsersPagesCount(int $userId): int
    {
        $query = $this->database->prepare('
            SELECT COUNT(1) as pages_count
            FROM pages
            JOIN websites ON pages.website_id = websites.website_id
            WHERE websites.user_id = :user_id
        ');

        $query->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $query->execute();

        $queryResult = $query->fetch();

        return $queryResult ? $queryResult['pages_count'] : 0;
    }

    public function getLeastVisitedPage(int $userId): string
    {
        return $this->getLeastOrMostVisitedPage($userId, self::ASC);
    }

    public function getMostVisitedPage(int $userId): string
    {
        return $this->getLeastOrMostVisitedPage($userId, self::DESC);
    }

    private function getLeastOrMostVisitedPage(int $userId, string $sortDirection): string
    {
        $query = $this->database->prepare('
            SELECT CONCAT(websites.hostname, \'/\', pages.url) AS address, COUNT(1) as visits_count
            FROM websites
            JOIN pages ON websites.website_id = pages.website_id
            JOIN page_visits ON pages.page_id = page_visits.page_id
            WHERE websites.user_id = :user_id
            GROUP BY page_visits.page_id
            ORDER BY visits_count ' . $sortDirection . '
            LIMIT 0, 1
        ');

        $query->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $query->execute();

        $queryResult = $query->fetch();

        return $queryResult ? $queryResult['address'] : '';
    }
}