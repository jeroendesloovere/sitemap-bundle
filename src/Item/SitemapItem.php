<?php

namespace JeroenDesloovere\SitemapBundle\Item;

class SitemapItem
{
    /** @var \DateTime */
    private $editedOn;

    /** @var string */
    private $url;

    /** @var ChangeFrequency */
    private $changeFrequency;

    public function __construct(string $url, \DateTime $editedOn, ChangeFrequency $changeFrequency)
    {
        $this->url = $url;
        $this->editedOn = $editedOn;
        $this->changeFrequency = $changeFrequency;
    }

    public function getChangeFrequency(): ChangeFrequency
    {
        return $this->changeFrequency;
    }

    public function getEditedOn(): \DateTime
    {
        return $this->editedOn;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
