<?php

########################################################################
# Extension Manager/Repository config file for ext: "user_feuser_extension"
#
# Auto generated 16-12-2008 11:34
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Frontend User Extension',
	'description' => 'Extesion of Frontend User Registration',
	'category' => 'plugin',
	'author' => 'Antoine CATHELIN',
	'author_email' => 'anc@wcc-coe.org',
	'shy' => '',
	'dependencies' => 'sr_feuser_register,direct_mail',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'alpha',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '0.0.3',
	'constraints' => array(
		'depends' => array(
			'sr_feuser_register' => '2.5.19-',
			'direct_mail' => '2.6.0-',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:16:{s:9:"ChangeLog";s:4:"0c08";s:10:"README.txt";s:4:"ee2d";s:12:"ext_icon.gif";s:4:"3cb0";s:17:"ext_localconf.php";s:4:"60f7";s:14:"ext_tables.php";s:4:"89ad";s:14:"ext_tables.sql";s:4:"f3c6";s:16:"locallang_db.xml";s:4:"69f5";s:50:"control/class.tx_srfeuserregister_control_main.php";s:4:"07d2";s:19:"doc/wizard_form.dat";s:4:"49cc";s:20:"doc/wizard_form.html";s:4:"8a10";s:38:"pi1/class.user_feuserextension_pi1.php";s:4:"8e29";s:37:"pi2/class.tx_srfeuserregister_tca.php";s:4:"3e07";s:38:"pi2/class.user_feuserextension_pi2.php";s:4:"38e8";s:17:"pi2/locallang.xml";s:4:"324e";s:53:"static/extension_of_feuser_registration/constants.txt";s:4:"e0f7";s:49:"static/extension_of_feuser_registration/setup.txt";s:4:"8c5e";}',
	'suggests' => array(
	),
);

?>