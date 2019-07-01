<?php

namespace Snowdog\DevTest\Model;

use PDO;
use Snowdog\DevTest\Core\Database;

class VarnishManager
{
    /**
     * @var Database|\PDO
     */
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /** @return Varnish[] */
    public function getAllByUser(User $user): array
    {
        $userId = $user->getUserId();

        $query = $this->database->prepare('
            SELECT varnish_id, user_id, ip
            FROM varnishes
            WHERE user_id = :user
        ');
        $query->bindParam(':user', $userId, PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_CLASS, Varnish::class);
    }

    /** @return Website[] */
    public function getWebsites(Varnish $varnish): array
    {
        $varnishId = $varnish->getVarnishId();
        $query = $this->database->prepare('
            SELECT websites.website_id, websites.name, websites.hostname, websites.user_id
            FROM websites
            INNER JOIN varnishes_x_websites ON websites.website_id = varnishes_x_websites.website_id
            WHERE varnishes_x_websites.varnish_id = :varnish_id
        ');
        $query->bindParam(':varnish_id', $varnishId, PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_CLASS, Website::class);
    }

    /** @return Varnish[] */
    public function getByWebsite(Website $website): array
    {
        $websiteId = $website->getWebsiteId();

        $query = $this->database->prepare('
            SELECT varnishes.varnish_id, user_id, ip
            FROM varnishes
            INNER JOIN varnishes_x_websites ON varnishes.varnish_id = varnishes_x_websites.varnish_id
            WHERE varnishes_x_websites.website_id = :website_id;
        ');
        $query->bindParam(':website_id', $websiteId, PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_CLASS, Varnish::class);
    }

    public function create(User $user, string $ip): int
    {
        $userId = $user->getUserId();

        $statement = $this->database->prepare('
            INSERT INTO varnishes (user_id, ip)
            VALUES (:userId, :ip);
        ');
        $statement->bindParam(':userId', $userId, PDO::PARAM_INT);
        $statement->bindParam(':ip', $ip, PDO::PARAM_STR);
        $statement->execute();

        return $this->database->lastInsertId();
    }

    public function link(Varnish $varnish, Website $website)
    {
        $varnishId = $varnish->getVarnishId();
        $websiteId = $website->getWebsiteId();

        $statement = $this->database->prepare('
            INSERT INTO varnishes_x_websites (varnish_id, website_id)
            VALUES (:varnishId, :websiteId);
        ');
        $statement->bindParam(':varnishId', $varnishId, PDO::PARAM_INT);
        $statement->bindParam(':websiteId', $websiteId, PDO::PARAM_INT);
        $statement->execute();
    }

    public function unlink(Varnish $varnish, Website $website)
    {
        $varnishId = $varnish->getVarnishId();
        $websiteId = $website->getWebsiteId();

        $statement = $this->database->prepare('
            DELETE FROM varnishes_x_websites
            WHERE varnish_id = :varnishId AND website_id = :websiteId;
        ');
        $statement->bindParam(':varnishId', $varnishId, PDO::PARAM_INT);
        $statement->bindParam(':websiteId', $websiteId, PDO::PARAM_INT);
        $statement->execute();
    }

    /** @return Varnish|null */
    public function getById(int $varnishId)
    {
        $query = $this->database->prepare('
            SELECT varnish_id, user_id, ip
            FROM varnishes
            WHERE varnish_id = :varnish_id
        ');
        $query->bindParam(':varnish_id', $varnishId, PDO::PARAM_INT);
        $query->execute();

        return $query->fetchObject(Varnish::class);
    }
}
