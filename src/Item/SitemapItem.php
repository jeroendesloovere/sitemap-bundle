<?php

namespace JeroenDesloovere\SitemapBundle\Item;

use JeroenDesloovere\SitemapBundle\Exception\SitemapException;

class SitemapItem
{
    /** @var ChangeFrequency */
    private $changeFrequency;

    /** @var \DateTime */
    private $lastModifiedOn;

    /** @var int - Value between 0 and 10, will be divided by 10 */
    private $priority = 10;

    /** @var string */
    private $url;

    public function __construct(
        string $url,
        \DateTime $lastModifiedOn,
        ChangeFrequency $changeFrequency,
        int $priority = 5
    ) {
        $this->url = $url;
        $this->lastModifiedOn = $lastModifiedOn;
        $this->changeFrequency = $changeFrequency;
        $this->setPriority($priority);
    }

    public function getChangeFrequency(): ChangeFrequency
    {
        return $this->changeFrequency;
    }

    public function getLastModifiedOn(): \DateTime
    {
        return $this->lastModifiedOn;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param int $priority
     * @throws SitemapException
     */
    private function setPriority(int $priority): void
    {
        if ($priority < 0 || $priority > 10) {
            throw SitemapException::forPriority();
        }

        $this->priority = $priority;
    }
}
