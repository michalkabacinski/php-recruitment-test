<?php

namespace Snowdog\DevTest\Service;

use mk85\sitemap\Exceptions\FileDoesNotExistException;
use mk85\sitemap\Exceptions\InvalidSitemapException;
use mk85\sitemap\Extractor;

class SitemapExtractor
{
    private $sitemapExtractor;

    public function __construct(Extractor $sitemapExtractor)
    {
        $this->sitemapExtractor = $sitemapExtractor;
    }

    /**
     * @param string $sitemapPath
     * @return array
     * @throws FileDoesNotExistException
     * @throws InvalidSitemapException
     */
    public function getUrls(string $sitemapPath): array
    {
        $urls = $this->sitemapExtractor->execute($sitemapPath);

        return $urls;
    }
}
