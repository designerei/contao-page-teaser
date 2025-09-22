<?php

namespace designerei\ContaoPageTeaserBundle\Event;

use Contao\ContentModel;
use Symfony\Contracts\EventDispatcher\Event;

class PageTeaserEvent extends Event
{
    public const NAME = 'designerei.page_teaser.generate';

    public function __construct(
        private array $teasers,
        private readonly ContentModel $model
    ) {}

    public function getTeasers(): array
    {
        return $this->teasers;
    }

    public function setTeasers(array $teasers): void
    {
        $this->teasers = $teasers;
    }

    public function getModel(): ContentModel
    {
        return $this->model;
    }
}