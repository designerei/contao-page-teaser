<?php

namespace designerei\ContaoPageTeaserBundle\Controller\ContentElement;

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\CoreBundle\Image\Studio\Studio;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\StringUtil;
use Contao\PageModel;
use designerei\ContaoPageTeaserBundle\Event\PageTeaserEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsContentElement('page_teaser', category: 'includes')]
#[AsContentElement('page_teasers', category: 'includes')]
class PageTeaserController extends AbstractContentElementController
{
    private bool $hideImages;
    private bool $overwriteTeaserContent;
    private string $teaserTitle;
    private string $teaserText;

    public function __construct(
        private readonly Studio $studio,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {}

    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        $this->hideImages = $model->hideImages;
        $this->overwriteTeaserContent = $model->overwriteTeaserContent;
        $this->teaserTitle = $model->teaserTitle ?? '';
        $this->teaserText = $model->teaserText ?? '';

        if ($this->getType() == 'page_teaser') {
            $page = (int) $model->teaserPage;
            $pages = (array) $page;
        } else {
            $pages = StringUtil::deserialize($model->teaserPages);
            $showSubpages = $model->showSubpages ?: null;
            $sorting = $model->sortBy ?: null;

            // add subpages
            if ($showSubpages) {
                $pages = $this->addSubpages($pages, $showSubpages);
            }

            // set sorting
            if ($sorting == 'title_asc') {
                $options['order'] = 'title ASC';
            } else if ($sorting == 'title_desc') {
                $options['order'] = 'title DESC';
            }
        }

        // generate pages
        $options['having'] = 'published = 1';
        $pages = PageModel::findMultipleByIds($pages, $options ?? array());
        $teasers = $this->generateTeaser($pages);

        // dispatch events
        $event = new PageTeaserEvent($teasers, $model);
        $this->eventDispatcher->dispatch($event, PageTeaserEvent::NAME);
        $teasers = $event->getTeasers();

        // set template data
        if ($this->getType() == 'page_teaser') {
            $template->set('teaser', current($teasers));
        } else {
            $template->set('teasers', $teasers);
        }

        return $template->getResponse();
    }

    protected function generateTeaser($pages): array
    {
        $teaser = [];

        foreach ($pages as $index => $page) {
            // get page images
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

            $teaser[$index] = [
                'title' => $page->teaserTitle ?: $page->title,
                'href' => $page->getFrontendUrl(),
                'text' => $page->teaserText ?: null,
                'images' => $images ?: null,
                'page' => $page,
            ];

            // hide images
            if ($this->hideImages) {
                $teaser[$index]['images'] = [];
            }

            // overwrite teaser content
            if ($this->overwriteTeaserContent) {
                if ($this->teaserTitle) {
                    $teaser[$index]['title'] = $this->teaserTitle;
                }
                if ($this->teaserText) {
                    $teaser[$index]['text'] = $this->teaserText;
                }
            }
        }

        return $teaser;
    }

    protected function addSubpages(array $pages, string $showSubpages = null): array
    {
        $parentPages = $pages;
        $pages = [];

        foreach ($parentPages as $page) {
            // add parent page
            if ($showSubpages != 'only') {
                $pages[] = $page;
            }

            // add subpages
            $page = PageModel::findOneBy('id', $page);
            $subpages = PageModel::findPublishedByPid($page->id);

            foreach ($subpages as $subpage) {
                $pages[] = $subpage->id;
            }
        }

        return $pages;
    }
}