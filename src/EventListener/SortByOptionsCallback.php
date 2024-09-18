<?php

namespace designerei\ContaoPageTeaserBundle\EventListener;

use Contao\ContentModel;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;

#[AsCallback('tl_content', 'fields.sortBy.options')]
class SortByOptionsCallback
{
    public function __invoke(DataContainer $dc = null): array
    {
        $options = $GLOBALS['TL_DCA']['tl_content']['fields']['sortBy']['options'];

        $element = ContentModel::findById($dc->id);
        if ($element->type === 'page_teaser') {
            $options = [
              'custom',
              'title_asc',
              'title_desc'
            ];
            return $options;
        }

        return $options;
    }
}