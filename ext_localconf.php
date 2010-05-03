<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

if (!defined ('PATH_user_feuserextension')) {
	define('PATH_user_feuserextension', t3lib_extMgm::extPath('user_feuser_extension'));
}

t3lib_extMgm::addPItoST43($_EXTKEY,'pi2/class.user_feuserextension_pi2.php','_pi2','list_type',0);

$_EXTCONF = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['sr_feuser_register']);    // unserializing the configuration so we can use it here:
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['uploadfolder'] = $_EXTCONF['uploadFolder'] ? $_EXTCONF['uploadFolder'] : 'uploads/tx_srfeuserregister';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['imageMaxSize'] = $_EXTCONF['imageMaxSize'] ? $_EXTCONF['imageMaxSize'] : 250;
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['imageTypes'] = $_EXTCONF['imageTypes'] ? $_EXTCONF['imageTypes'] : 'png,jpeg,jpg,gif,tif,tiff';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['useMd5Password'] = $_EXTCONF['useMd5Password'] ? $_EXTCONF['useMd5Password'] : 0;
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['useFlexforms'] = $_EXTCONF['useFlexforms'];


## Extending TypoScript from static template uid=43
//t3lib_extMgm::addTypoScript($_EXTKEY,'setup','
# Typoscript for my extension
//plugin.user_feuserextension_pi2 < plugin.tx_srfeuserregister_pi1
//plugin.user_feuserextension_pi2.includeLibs = '.t3lib_extMgm::siteRelPath('user_feuser_extension').'user_feuser_extension/pi2/class.user_feuserextension_pi2.php
//plugin.user_feuserextension_pi2.userFunc = user_feuserextension_pi2->main
//',0);

// Register XCLASS to to replace forgot password function on felogin
$TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/felogin/pi1/class.tx_felogin_pi1.php'] = t3lib_extMgm::extPath($_EXTKEY) . 'xclass/class.ux_tx_felogin_pi1.php';
// Register XCLASS to protect cn for local users
$TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sr_feuser_register/model/class.tx_srfeuserregister_data.php'] = t3lib_extMgm::extPath($_EXTKEY) . 'xclass/class.ux_tx_srfeuserregister_data.php';

?>