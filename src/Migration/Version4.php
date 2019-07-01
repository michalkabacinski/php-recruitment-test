<?php

namespace Snowdog\DevTest\Migration;

use PDO;
use Snowdog\DevTest\Core\Database;
use Snowdog\DevTest\Model\PageManager;
use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\WebsiteManager;

class Version4
{
    /**
     * @var Database|PDO
     */
    private $database;

    public function __construct(
        Database $database,
        UserManager $userManager,
        WebsiteManager $websiteManager,
        PageManager $pageManager
    ) {
        $this->database = $database;
        $this->userManager = $userManager;
        $this->websiteManager = $websiteManager;
        $this->pageManager = $pageManager;
    }

    public function __invoke()
    {
        $this->createVarnishTable();
        $this->createVarnishesXWebsitesTable();
    }

    private function createVarnishTable()
    {
        $createQuery = <<<SQL
CREATE TABLE `varnishes` (
  `varnish_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `ip` varchar(50) NOT NULL,
  PRIMARY KEY (`varnish_id`),
  KEY `varnish_id` (`varnish_id`),
  CONSTRAINT `varnish_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;
        $this->database->exec($createQuery);
    }

    public function createVarnishesXWebsitesTable()
    {
        $createQuery = <<<SQL
CREATE TABLE `varnishes_x_websites` (
  `varnish_id` int(11) unsigned NOT NULL,
  `website_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`varnish_id`, `website_id`),
  CONSTRAINT `varnishwebsites_varnish_fk` FOREIGN KEY (`varnish_id`) REFERENCES `varnishes` (`varnish_id`),
  CONSTRAINT `varnishwebsites_website_fk` FOREIGN KEY (`website_id`) REFERENCES `websites` (`website_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;
        $this->database->exec($createQuery);
    }
}