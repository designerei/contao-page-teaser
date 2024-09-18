<?php

namespace designerei\ContaoPageTeaserBundle\Controller\ContentElement;

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\CoreBundle\Image\Studio\Studio;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\StringUtil;
use Contao\PageModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsContentElement('page_teaser', category: 'includes')]
class PageTeaserController extends AbstractContentElementController
{
    public function __construct(private readonly Studio $studio)
    {
    }

    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {

        // Generate page array
        $pages = array();
        $items = StringUtil::deserialize($model->page) ?: array();
        $limit = $model->limitPages ?: null;

        foreach ($items as $item) {
            if (PageModel::findPublishedById($item)) {
                if ($limit != 'subpages') {
                    $pages[] = $item;
                }

                if ($limit != 'selected') {
                    $subpages = PageModel::findPublishedByPid($item);
                    if ($subpages !== null) {
                        foreach ($subpages as $subpage) {
                            if (!in_array($subpage->id, $items)) {
                                $pages[] = $subpage->id;
                            }
                        }
                    }
                }
            }
        }
        unset ($items);

        // Get sorted PageModels
        $options = array();
        $sorting = $model->sortBy;

        if ($sorting == 'title_asc') {
            $options['order'] = 'title ASC';
        } else if ($sorting == 'title_desc') {
            $options['order'] = 'title DESC';
        }

        $pages = PageModel::findMultipleByIds($pages, $options) ?: array();
        $items = array();

        // Generate teasers
        foreach ($pages as $key => $page) {

            // title
            $items[$key]['title'] = $page->teaserTitle ?: $page->title;

            // href
            $href = $page->getFrontendUrl();
            $items[$key]['href'] = $href;

            // teaser
            if ($page->teaserText != null) {
                $items[$key]['text'] = $page->teaserText;
            }

            // image
            if ($page->pageImage != null) {

                $pageImage = StringUtil::deserialize($page->pageImage);
                $images = array();

                foreach ($pageImage as $image) {
                    $images[] = $this->studio
                        ->createFigureBuilder()
                        ->fromUuid($image ?: '')
                        ->setSize($model->size)
                        ->buildIfResourceExists()
                    ;
                }

                $items[$key]['images'] = $images;
            }

            // PageModel
            // $items[$key]['model'] = $page;
        }

        $template->set('pages', $items);

        return $template->getResponse();
    }
}