<?php

namespace designerei\ContaoPageTeaserBundle\Controller\ContentElement;

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\StringUtil;
use Contao\PageModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsContentElement('page_teaser', category: 'includes')]
#[AsContentElement('page_teasers', category: 'includes')]
class PageTeaserController extends AbstractContentElementController
{
    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        // Get pages
        if ($model->type == 'page_teaser') {
            $items[] = $model->page;
        } else {
            $items = StringUtil::deserialize($model->pages);
        }

        foreach ($items as $key => $id) {
            $page = PageModel::findById($id);
            if ($page->published) {

                // title
                $pages[$key]['title'] = $page->title;

                // href
                $href = $page->getFrontendUrl();
                $pages[$key]['href'] = $href;

                // teaser
                if ($page->teaser != null) {
                    $pages[$key]['teaser'] = $page->teaser;
                }

                // model
                // $pages[$key]['model'] = $page;
            }
        }

        $template->set('pages', $pages);

        return $template->getResponse();
    }
}