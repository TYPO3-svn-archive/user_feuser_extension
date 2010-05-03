<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');
t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi2']='layout,select_key';

t3lib_extMgm::addPlugin(array('LLL:EXT:user_feuser_extension/locallang_db.xml:tt_content.list_type_pi2', $_EXTKEY.'_pi2'),'list_type');

t3lib_extMgm::addStaticFile($_EXTKEY,"pi2/static/","Improved registration form with categories");

if ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['useFlexforms']==1) {
	$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi2']='layout,select_key';
	$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi2']='pi_flexform';
	t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_pi2', 'FILE:EXT:sr_feuser_register/pi1/flexform_ds_pi1.xml');
} else {
	$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi2'] = 'layout';
}

$tempColumns = array (
	'user_feuserextension_description' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:user_feuser_extension/locallang_db.xml:sys_dmail_category.user_feuserextension_description',		
		'config' => array (
			'type' => 'text',
			'cols' => '30',	
			'rows' => '5',
		)
	),
	'user_feuserextension_parent' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:user_feuser_extension/locallang_db.xml:sys_dmail_category.user_feuserextension_parent',		
		'config' => array (
			'type' => 'select',	
			'items' => array (
				array('',0),
			),
			'foreign_table' => 'sys_dmail_category',	
			'foreign_table_where' => 'ORDER BY sys_dmail_category.uid',	
			'size' => 1,	
			'minitems' => 0,
			'maxitems' => 1,	
			'wizards' => array(
				'_PADDING'  => 2,
				'_VERTICAL' => 1,
				'add' => array(
					'type'   => 'script',
					'title'  => 'Create new record',
					'icon'   => 'add.gif',
					'params' => array(
						'table'    => 'sys_dmail_category',
						'pid'      => '###CURRENT_PID###',
						'setValue' => 'prepend'
					),
					'script' => 'wizard_add.php',
				),
				'edit' => array(
					'type'                     => 'popup',
					'title'                    => 'Edit',
					'script'                   => 'wizard_edit.php',
					'popup_onlyOpenIfSelected' => 1,
					'icon'                     => 'edit2.gif',
					'JSopenParams'             => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
				),
			),
		)
	),
	'user_feuserextension_checked' => array (        
        'exclude' => 0,        
        'label' => 'LLL:EXT:user_feuser_extension/locallang_db.xml:sys_dmail_category.user_feuserextension_checked',        
        'config' => array (
			'type' => 'check',
		)
    ),
	'user_feuserextension_order' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:user_feuser_extension/locallang_db.xml:sys_dmail_category.user_feuserextension_order',		
		'config' => array (
			'type'     => 'input',
			'size'     => '4',
			'max'      => '4',
			'eval'     => 'int',
			'checkbox' => '0',
			'range'    => array (
				'upper' => '1000',
				'lower' => '1'
			),
			'default' => 0
		)
	),
	'user_feuserextension_press' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:user_feuser_extension/locallang_db.xml:sys_dmail_category.user_feuserextension_press',		
		'config' => array (
			'type' => 'check',
		)
	),
);


t3lib_div::loadTCA("sys_dmail_category");
t3lib_extMgm::addTCAcolumns("sys_dmail_category",$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('sys_dmail_category','user_feuserextension_description;;;;1-1-1, user_feuserextension_parent, user_feuserextension_checked, user_feuserextension_order, user_feuserextension_press');
t3lib_extMgm::addStaticFile($_EXTKEY,'static/extension_of_feuser_registration/', 'extension of feuser registration');
t3lib_extMgm::addStaticFile($_EXTKEY,"static/css_styled","CSS extension of feuser registration");


?>