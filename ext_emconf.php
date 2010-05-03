<?php

########################################################################
# Extension Manager/Repository config file for ext "user_feuser_extension".
#
# Auto generated 30-04-2010 14:44
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Frontend User Extension',
	'description' => 'Extesion of Frontend User Registration',
	'category' => 'plugin',
	'author' => 'Antoine CATHELIN',
	'author_email' => 'anc@wcc-coe.org',
	'shy' => '',
	'dependencies' => 'sr_feuser_register,direct_mail,div2007',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '0.8.2',
	'constraints' => array(
		'depends' => array(
			'sr_feuser_register' => '2.5.22',
			'direct_mail' => '2.6.0-',
			'div2007' => '0.1.22-',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:26:{s:9:"ChangeLog";s:4:"cadb";s:10:"README.txt";s:4:"9fa9";s:21:"ext_conf_template.txt";s:4:"6e5e";s:12:"ext_icon.gif";s:4:"3cb0";s:17:"ext_localconf.php";s:4:"3155";s:14:"ext_tables.php";s:4:"0c7d";s:14:"ext_tables.sql";s:4:"fb65";s:16:"locallang_db.xml";s:4:"1078";s:50:"control/class.tx_srfeuserregister_control_main.php";s:4:"b43a";s:14:"doc/manual.sxw";s:4:"c94e";s:19:"doc/wizard_form.dat";s:4:"49cc";s:20:"doc/wizard_form.html";s:4:"1e2b";s:9:"js/pi2.js";s:4:"b35f";s:38:"pi1/class.user_feuserextension_pi1.php";s:4:"8e29";s:37:"pi2/class.tx_srfeuserregister_tca.php";s:4:"9deb";s:38:"pi2/class.user_feuserextension_pi2.php";s:4:"30db";s:17:"pi2/locallang.xml";s:4:"ef71";s:36:"pi2/tx_srfeuserregister_htmlmail.css";s:4:"f2eb";s:42:"pi2/tx_srfeuserregister_htmlmail_xhtml.css";s:4:"f500";s:41:"pi2/tx_srfeuserregister_pi1_css_tmpl.html";s:4:"23f0";s:31:"static/css_styled/constants.txt";s:4:"2e88";s:27:"static/css_styled/setup.txt";s:4:"98a8";s:53:"static/extension_of_feuser_registration/constants.txt";s:4:"267d";s:49:"static/extension_of_feuser_registration/setup.txt";s:4:"bcec";s:34:"xclass/class.ux_tx_felogin_pi1.php";s:4:"ad93";s:20:"xclass/locallang.xml";s:4:"82b3";}',
	'suggests' => array(
	),
);

?>