<?php

namespace designerei\ContaoPageTeaserBundle\Controller\ContentElement;

use Contao\ContentModel;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\CoreBundle\Image\Studio\Studio;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\PageModel;
use Contao\StringUtil;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsContentElement('page_teasers', category: 'includes')]
class PageTeasersController extends AbstractPageTeaserContentElementController
{
    public function __construct(
        protected readonly EventDispatcherInterface $eventDispatcher,
        protected readonly Studio $studio,
    ) {}

    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        $pageIds = $model->teaserPages !== null ? StringUtil::deserialize($model->teaserPages) : [];
        $showSubpages = $model->showSubpages ?? null;
        $sortPages = $model->sortPages ?? null;

        $options = [];

        if ($showSubpages) {
            $pageIds = $this->addSubpages($pageIds, $showSubpages);
        }

        if ($sortPages !== 'custom') {
            $sorting = match ($sortPages) {
                'title_asc'  => 'title ASC',
                'title_desc' => 'title DESC',
                'random'     => 'RAND()'
            };

            $options['sorting'] = $sorting;
        }

        $pageCollection = $this->getPages($pageIds, $options);
        $teasers = $this->generateTeaser($pageCollection, $model);

        $template->set('teasers', $teasers);

        return $template->getResponse();
    }

    protected function addSubpages(array $pages, string $showSubpages = null): array
    {
        $parentPages = $pages;
        $pages = [];

        foreach ($parentPages as $page) {
            if ($showSubpages != 'only') {
                $pages[] = $page;
            }

            $page = PageModel::findOneBy('id', $page);
            $subpages = PageModel::findPublishedByPid($page->id);

            foreach ($subpages as $subpage) {
                $pages[] = $subpage->id;
            }
        }

        return $pages;
    }
}