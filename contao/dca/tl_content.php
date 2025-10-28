<?php

$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'overwriteTeaserContent';

$GLOBALS['TL_DCA']['tl_content']['palettes']['page_teaser'] = '
    {type_legend},type;
    {include_legend},teaserPage,hideImages,overwriteTeaserContent;
    {template_legend:hide},customTpl;
    {protected_legend:hide},protected;
    {expert_legend:hide},guests,cssID;
    {invisible_legend:hide},invisible,start,stop
';

$GLOBALS['TL_DCA']['tl_content']['palettes']['page_teasers'] = '
    {type_legend},type;
    {include_legend},teaserPages,sortPages,showSubpages;
    {template_legend:hide},customTpl;
    {protected_legend:hide},protected;
    {expert_legend:hide},guests,cssID;
    {invisible_legend:hide},invisible,start,stop
';

$GLOBALS['TL_DCA']['tl_content']['subpalettes']['overwriteTeaserContent'] = 'teaserTitle,teaserText';


$GLOBALS['TL_DCA']['tl_content']['fields']['teaserPage'] = [
    'inputType' => 'pageTree',
    'foreignKey' => 'tl_page.title',
    'eval' => ['fieldType' => 'radio', 'mandatory' => true],
    'sql' => "blob NULL",
    'relation' => ['type' => 'hasMany', 'load' => 'lazy']
];

$GLOBALS['TL_DCA']['tl_content']['fields']['teaserPages'] = [
    'inputType' => 'pageTree',
    'foreignKey' => 'tl_page.title',
    'eval' => ['multiple' => true, 'fieldType' => 'checkbox', 'isSortable' => true, 'mandatory' => true],
    'sql' => "blob NULL",
    'relation' => ['type' => 'hasMany', 'load' => 'lazy']
];

$GLOBALS['TL_DCA']['tl_content']['fields']['showSubpages'] = [
    'inputType' => 'select',
    'options' => ['all', 'only'],
    'reference' => &$GLOBALS['TL_LANG']['MSC']['showSubpages'],
    'eval' => ['tl_class' => 'w50', 'includeBlankOption' => true],
    'sql' => "varchar(32) COLLATE ascii_bin NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_content']['fields']['sortPages'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_content']['sortBy'],
    'inputType' => 'select',
    'options' => array('custom', 'title_asc', 'title_desc', 'random'),
    'reference' => &$GLOBALS['TL_LANG']['tl_content'],
    'eval' => array('tl_class'=>'w50 clr'),
    'sql' => "varchar(32) COLLATE ascii_bin NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_content']['fields']['hideImages'] = [
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'w50'],
    'sql' => ['type' => 'boolean', 'default' => false]
];

$GLOBALS['TL_DCA']['tl_content']['fields']['overwriteTeaserContent'] = [
    'inputType' => 'checkbox',
    'eval' => ['submitOnChange' => true, 'tl_class' => 'w50 clr'],
    'sql' => ['type' => 'boolean', 'default' => false]
];

$GLOBALS['TL_DCA']['tl_content']['fields']['teaserTitle'] = [
    'inputType' => 'text',
    'eval' => ['tl_class' => 'w50', 'maxlength' => 200, 'basicEntities' => true],
    'sql' => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_content']['fields']['teaserText'] = [
    'inputType' => 'textarea',
    'eval' => ['tl_class' => 'clr', 'basicEntities' => true],
    'sql' => "text NULL"
];