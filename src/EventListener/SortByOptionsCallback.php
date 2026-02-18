<?php

namespace designerei\ContaoPageTeaserBundle\EventListener;

use Contao\ContentModel;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;

#[AsCallback('tl_content', 'fields.sortBy.options')]
class SortByOptionsCallback
{
    public function __invoke(?DataContainer $dc = null): array
    {
        $options = $GLOBALS['TL_DCA']['tl_content']['fields']['sortBy']['options'];

        if (null === $dc || empty($dc->id)) {
            return $options;
        }

        $element = ContentModel::findById($dc->id);

        if (null === $element) {
            return $options;
        }

        if ($element->type === 'page_teasers') {
            return ['custom', 'title_asc', 'title_desc'];
        }

        return $options;
    }
}