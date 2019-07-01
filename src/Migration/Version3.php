<?php

namespace Snowdog\DevTest\Migration;

use Snowdog\DevTest\Core\Database;

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
        $this->createPageVisitTable();
    }

    private function createPageVisitTable()
    {
        $createQuery = <<<SQL
CREATE TABLE `page_visits` (
  `page_visit_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(11) unsigned NOT NULL,
  `visit_date` datetime NOT NULL,
  PRIMARY KEY (`page_visit_id`),
  KEY `page_id` (`page_id`),
  CONSTRAINT `page_visit_page_fk` FOREIGN KEY (`page_id`) REFERENCES `pages` (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;
        $this->database->exec($createQuery);
    }
}