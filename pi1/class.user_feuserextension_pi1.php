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

require_once(PATH_tslib.'class.tslib_pibase.php');


/**
 * Plugin '' for the 'user_feuser_extension' extension.
 *
 * @author	Antoine CATHELIN <anc@wcc-coe.org>
 * @package	TYPO3
 * @subpackage	user_feuserextension
 */
class user_feuserextension_pi1 extends tslib_pibase {
	var $prefixId      = 'user_feuserextension_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.user_feuserextension_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'user_feuser_extension';	// The extension key.
	var $pi_checkCHash = true;
	
	var $auth; // object of type tx_srfeuserregister_auth
	var $url;
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content,$conf)	{
		global $TSFE, $TCA, $TYPO3_CONF_VARS;
		$data = "";
		$userArray = array();
		$addon = "";
		
		$theTable = "fe_users";
		//$data .= $this->config['username'];
		$theTable = 'fe_users';
		$adminFieldList = 'username,password,name,disable,usergroup,by_invitation';
		
		$uid = '33';
		//t3lib_div::debug($TSFE->sys_page->getRawRecord($theTable, $uid));
		
		if (t3lib_extMgm::isLoaded('sr_feuser_register')) {
						
			require_once(t3lib_extMgm::extPath('sr_feuser_register').'lib/class.tx_srfeuserregister_auth.php');
			$this->auth = t3lib_div::makeInstance('tx_srfeuserregister_auth');
			require_once(t3lib_extMgm::extPath('sr_feuser_register').'model/class.tx_srfeuserregister_url.php');
			$this->url = t3lib_div::makeInstance('tx_srfeuserregister_url');
			
			$this->auth->conf['authcodeFields'] = 'uid,pid';
			$this->auth->conf['authcodeFields.']['codeLength'] = '8';
			$this->auth->config['codeLength'] = '8';
			//$userArray = $TSFE->fe_user->user;
			$theField = 'uid';
			$theValue = '';
			$whereClause = 'username=anc@wcc-coe.org';
			//t3lib_div::debug(t3lib_pageSelect::getRecordsByField($theTable, $theField, $theValue, $whereClause, $groupBy = '', $orderBy = '', $limit = ''));
			$userArray = $TSFE->sys_page->getRawRecord($theTable, $uid);
			$userArray['usergroup']=""; 
			$addon = array("_FIELDLIST" => "uid,pid,usergroup");
			$userArray = array_merge($userArray,$addon);

			//t3lib_div::debug($userArray);
			//t3lib_div::debug($TSFE->fe_user);
			
			$data .="<b>authCode **</b> function-->".$this->auth->authCode($userArray)."<--<br>";
			$data .="<b>setfixedHash</b> function-->".$this->auth->setfixedHash($userArray, 'uid,pid,usergroup')."<--";
		};
		
		return $data;
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/user_feuser_extension/pi1/class.user_feuserextension_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/user_feuser_extension/pi1/class.user_feuserextension_pi1.php']);
}

?>