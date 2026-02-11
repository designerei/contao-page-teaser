<?php

declare(strict_types=1);

namespace designerei\ContaoPageTeaserBundle\Controller\ContentElement;

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\Model\Collection;
use Contao\PageModel;
use Contao\StringUtil;
use designerei\ContaoPageTeaserBundle\Event\PageTeaserEvent;

abstract class AbstractPageTeaserContentElementController extends AbstractContentElementController
{
    protected function generateTeaser(Collection $pages, ?ContentModel $model): array
    {
        $teasers = [];

        foreach ($pages as $index => $page) {
            $images = [];

            if ($page->pageImage != null) {
                $pageImages = StringUtil::deserialize($page->pageImage);

                foreach ($pageImages as $image) {
                    $images[] = $this->studio
                        ->createFigureBuilder()
                        ->fromUuid($image ?: '')
                        ->setSize($model->size ?? null)
                        ->buildIfResourceExists()
                    ;
                }
            }

            $teasers[$index] = [
                'title' => $page->teaserTitle ?: $page->title,
                'href' => $page->getFrontendUrl(),
                'text' => $page->teaserText ?: null,
                'images' => $images ?: null,
                'page' => $page,
            ];

            // hide images
            if ($model->hideImages) {
                $teasers[$index]['images'] = [];
            }

            // overwrite teaser content
            if ($model->overwriteTeaserContent) {
                if ($model->teaserTitle) {
                    $teasers[$index]['title'] = $model->teaserTitle;
                }
                if ($model->teaserText) {
                    $teasers[$index]['text'] = $model->teaserText;
                }
            }
        }

        // dispatch events
        $event = new PageTeaserEvent($teasers, $model);
        $this->eventDispatcher->dispatch($event, PageTeaserEvent::NAME);

        return $event->getTeasers();
    }
}