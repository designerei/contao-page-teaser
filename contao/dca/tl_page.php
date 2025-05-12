<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$pm = PaletteManipulator::create()
    ->addLegend('teaser_legend', 'meta_legend', PaletteManipulator::POSITION_BEFORE, true)
    ->addField(['teaserTitle', 'teaserText'], 'teaser_legend', PaletteManipulator::POSITION_APPEND)
;

foreach ($GLOBALS['TL_DCA']['tl_page']['palettes'] as $name => $palette) {
    if ('__selector__' === $name) {
        continue;
    }

    $pm->applyToPalette($name, 'tl_page');
}

unset($name, $palette, $pm);

$GLOBALS['TL_DCA']['tl_page']['fields']['teaserTitle'] = [
    'inputType' => 'text',
    'eval' => ['tl_class' => 'w50', 'maxlength' => 200, 'basicEntities' => true],
    'sql' => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_page']['fields']['teaserText'] = [
    'inputType' => 'textarea',
    'eval' => ['tl_class' => 'clr', 'basicEntities' => true],
    'sql' => "text NULL"
];
