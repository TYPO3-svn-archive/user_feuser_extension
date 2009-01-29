<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008 Antoine CATHELIN <anc@wcc-coe.org>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

//require_once('/datas/httpd-data/anc/typo3conf/ext/sr_feuser_register/pi2/class.user_feuserextension_pi2.php');
require_once(PATH_tslib.'class.tslib_pibase.php');
require_once(PATH_user_feuserextension.'control/class.tx_srfeuserregister_control_main.php');


/**
 * Plugin 'Categories group' for the 'user_feuser_extension' extension.
 *
 * @author	Antoine CATHELIN <anc@wcc-coe.org>
 * @package	TYPO3
 * @subpackage	user_feuserextension
 */
 //tx_srfeuserregister_tca
class user_feuserextension_pi2 extends tslib_pibase {
	var $prefixId      = 'user_feuserextension_pi2';		// Same as class name
	var $scriptRelPath = 'pi2/class.user_feuserextension_pi2.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'user_feuser_extension';	// The extension key.
	// var $pi_checkCHash = true;
	
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content, &$conf) {
		global $TSFE;

		$this->pi_setPiVarDefaults();
		$this->conf = &$conf;

		$mainObj = &t3lib_div::getUserObj('&tx_srfeuserregister_control_main');
		$mainObj->cObj = &$this->cObj;
		$content = &$mainObj->main($content, $conf, $this, 'fe_users');
		return $content;
	}	
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/user_feuser_extension/pi2/class.user_feuserextension_pi2.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/user_feuser_extension/pi2/class.user_feuserextension_pi2.php']);
}

?>