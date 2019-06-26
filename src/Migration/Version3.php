<?php

namespace Snowdog\DevTest\Migration;

use Snowdog\DevTest\Core\Database;
use Snowdog\DevTest\Model\PageManager;
use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\WebsiteManager;

class Version3
{
    /**
     * @var Database|\PDO
     */
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function __invoke()
    {
        $this->alterPageTable();
    }

    private function alterPageTable()
    {
        $query = <<<SQL
ALTER TABLE `pages` 
ADD COLUMN `visit_date` DATETIME
SQL;
        $this->database->exec($query);
    }
}