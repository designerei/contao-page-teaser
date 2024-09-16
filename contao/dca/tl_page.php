<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$pm = PaletteManipulator::create()
    ->addLegend('teaser_legend', 'meta_legend', PaletteManipulator::POSITION_AFTER, true)
    ->addField('teaser', 'teaser_legend', PaletteManipulator::POSITION_APPEND)
;

foreach ($GLOBALS['TL_DCA']['tl_page']['palettes'] as $name => $palette) {
    if ('__selector__' === $name) {
        continue;
    }

    $pm->applyToPalette($name, 'tl_page');
}

unset($name, $palette, $pm);

$GLOBALS['TL_DCA']['tl_page']['fields']['teaser'] = [
    'inputType' => 'textarea',
    'eval' => array('rte'=>'tinyMCE', 'tl_class'=>'clr'),
    'sql' => "text NULL",
];