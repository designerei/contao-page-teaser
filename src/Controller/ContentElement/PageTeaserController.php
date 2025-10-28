<?php

namespace designerei\ContaoPageTeaserBundle\Controller\ContentElement;

use Contao\ContentModel;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\CoreBundle\Image\Studio\Studio;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsContentElement('page_teaser', category: 'includes')]
class PageTeaserController extends AbstractPageTeaserContentElementController
{
    public function __construct(
        protected readonly EventDispatcherInterface $eventDispatcher,
        protected readonly Studio $studio,
    ) {}

    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        $pageId  = $model->teaserPage;
        $ok      = is_int($pageId) || (is_string($pageId) && ctype_digit($pageId));
        $pageIds = ($ok && (int)$pageId > 0) ? [(int)$pageId] : [];

        $pageCollection = $this->getPages($pageIds);
        $teasers = $this->generateTeaser($pageCollection, $model);

        $template->set('teasers', $teasers);

        return $template->getResponse();
    }
}