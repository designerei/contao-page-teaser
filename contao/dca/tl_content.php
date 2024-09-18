<?php

$GLOBALS['TL_DCA']['tl_content']['palettes']['page_teaser'] = '
    {type_legend},type;
    {include_legend},page,sortBy,limitPages;
    {template_legend:hide},customTpl;
    {protected_legend:hide},protected;
    {expert_legend:hide},guests,cssID;
    {invisible_legend:hide},invisible,start,stop
';

$GLOBALS['TL_DCA']['tl_content']['fields']['page'] = [
    'inputType' => 'pageTree',
    'foreignKey' => 'tl_page.title',
    'eval' => array('multiple'=>true, 'fieldType'=>'checkbox', 'isSortable'=>true, 'mandatory'=>true),
    'sql' => "blob NULL",
    'relation' => array('type'=>'hasMany', 'load'=>'lazy')
];

$GLOBALS['TL_DCA']['tl_content']['fields']['limitPages'] = [
    'inputType' => 'select',
    'options' => array('selected', 'subpages'),
    'reference' => &$GLOBALS['TL_LANG']['MSC']['limitPages'],
    'eval' => ['tl_class'=>'w50', 'includeBlankOption'=>true],
    'sql' => "varchar(32) COLLATE ascii_bin NOT NULL default ''"
];

