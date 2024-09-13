<?php

$GLOBALS['TL_DCA']['tl_content']['palettes']['page_teaser'] = '
    {type_legend},type;
    {include_legend},page;
    {template_legend:hide},customTpl;
    {protected_legend:hide},protected;
    {expert_legend:hide},guests,cssID;
    {invisible_legend:hide},invisible,start,stop
';

$GLOBALS['TL_DCA']['tl_content']['palettes']['page_teasers'] = '
    {type_legend},type;
    {include_legend},pages;
    {template_legend:hide},customTpl;
    {protected_legend:hide},protected;
    {expert_legend:hide},guests,cssID;
    {invisible_legend:hide},invisible,start,stop
';

$GLOBALS['TL_DCA']['tl_content']['fields']['page'] = [
    'inputType' => 'pageTree',
    'foreignKey' => 'tl_page.title',
    'eval' => array('fieldType'=>'radio'),
    'sql' => "int(10) unsigned NOT NULL default 0",
    'relation' => array('type'=>'hasOne', 'load'=>'lazy')
];

$GLOBALS['TL_DCA']['tl_content']['fields']['pages'] = [
    'inputType' => 'pageTree',
    'foreignKey' => 'tl_page.title',
    'eval' => array('multiple'=>true, 'fieldType'=>'checkbox', 'isSortable'=>true, 'mandatory'=>true),
    'sql' => "blob NULL",
    'relation' => array('type'=>'hasMany', 'load'=>'lazy')
];
