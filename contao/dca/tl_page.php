<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_page']['fields']['teaser'] = [
    'inputType' => 'textarea',
    'eval' => array('rte'=>'tinyMCE', 'tl_class'=>'clr'),
    'sql' => "text NULL",
];

PaletteManipulator::create()
    ->addLegend('teaser_legend', 'meta_legend', PaletteManipulator::POSITION_AFTER)
    ->addField('teaser', 'teaser_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('regular', 'tl_page')
;