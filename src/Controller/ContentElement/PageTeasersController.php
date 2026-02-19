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
        $sortPages = $model->sortPages ?? '';

        $options = [];
        $options['having'] = "type IN ('regular', 'forward')";

        if ($showSubpages) {
            $pageIds = $this->addSubpages($pageIds, $showSubpages);
        }

        if ($sortPages !== 'custom') {
            $sorting = match ($sortPages) {
                'title_asc'  => 'title ASC',
                'title_desc' => 'title DESC',
                'random'     => 'RAND()',
                default       => null,
            };

            $options['sorting'] = $sorting;
        }

        $pageIds = $pageIds ?? [];

        if ($pageIds === []) {
            $template->set('teasers', []);
            return $template->getResponse();
        }

        $pageCollection = PageModel::findMultipleByIds($pageIds, $options);
        $pageTitlesCollection = PageModel::findMultipleByIds($pageIds)->fetchEach('title');
        $teasers = $pageCollection ? $this->generateTeaser($pageCollection, $model) : [];

        $template->set('teasers', $teasers);
        $template->set('titles', $pageTitlesCollection);

        return $template->getResponse();
    }

    protected function addSubpages(array $pages, ?string $showSubpages = null): array
    {
        $parentPages = $pages;
        $pages = [];

        foreach ($parentPages as $page) {
            if ($showSubpages != 'only') {
                $pages[] = $page;
            }

            $page = PageModel::findOneBy('id', $page);

            if (null === $page) {
                continue;
            }

            $subpages = PageModel::findPublishedRegularByPid($page->id);

            if (null === $subpages) {
                continue;
            }

            foreach ($subpages as $subpage) {
                $pages[] = $subpage->id;
            }
        }

        return $pages;
    }
}