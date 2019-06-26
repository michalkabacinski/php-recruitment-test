<?php

namespace Snowdog\DevTest\Model;

use DateTime;
use PDO;
use Snowdog\DevTest\Core\Database;

class PageManager
{

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
}