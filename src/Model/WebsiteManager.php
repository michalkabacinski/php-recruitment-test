<?php

namespace Snowdog\DevTest\Model;

use PDO;
use Snowdog\DevTest\Core\Database;

class WebsiteManager
{
    /**
     * @var Database|PDO
     */
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }
    
    public function getById($websiteId) {
        $query = $this->database->prepare('SELECT * FROM websites WHERE website_id = :id');
        $query->setFetchMode(PDO::FETCH_CLASS, Website::class);
        $query->bindParam(':id', $websiteId, PDO::PARAM_STR);
        $query->execute();
        /** @var Website $website */
        $website = $query->fetch(PDO::FETCH_CLASS);

        return $website;
    }

    public function getAllByUser(User $user)
    {
        $userId = $user->getUserId();

        $query = $this->database->prepare('
            SELECT websites.website_id, name, hostname, user_id, MAX(page_visits.visit_date) AS visit_date
            FROM websites
            LEFT JOIN pages ON websites.website_id = pages.website_id
            LEFT JOIN page_visits ON pages.page_id = page_visits.page_id
            WHERE user_id = :user
            GROUP BY websites.website_id
        ');
        $query->bindParam(':user', $userId, PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_CLASS, Website::class);
    }

    public function create(User $user, $name, $hostname)
    {
        $userId = $user->getUserId();
        $statement = $this->database->prepare('
            INSERT INTO websites (name, hostname, user_id)
            VALUES (:name, :host, :user)
        ');
        $statement->bindParam(':name', $name, PDO::PARAM_STR);
        $statement->bindParam(':host', $hostname, PDO::PARAM_STR);
        $statement->bindParam(':user', $userId, PDO::PARAM_INT);
        $statement->execute();

        return $this->database->lastInsertId();
    }
}
