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
/**
*
* This file is inspirate by file in [sr_feuser_register folder]/lib/class.tx_srfeuserregister_tca.php
*
**/
require_once(t3lib_extMgm::extPath('sr_feuser_register').'lib/class.tx_srfeuserregister_tca.php');

class tx_user_feuserextension_tca extends tx_srfeuserregister_tca {

	function chk1label($row, $valuesArray, $titleText, $theTable, $colName, $cmd, $level, $grpVisible) {
		/**
		*
		* Function qui affiche les categories de la manière d'une categorie ou d'un titre
		* en fonction des critères.
		*
		**/
		
		$colContent='';
		$CSSsyle='';
		$CSSclass='';
		$label=false;
		$class='';
		$style='';
		$chkChecked=false;
		$extconf=unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['user_feuser_extension']);
		
		
		//$row2 = $this->getUsergroupOverlay($row2);
		//t3lib_div::debug($grpVisible);
		
		//
		// determine la class de l'objet
		//
		if (in_array($row['uid'], explode(',',$this->conf['catShowAlways'])) XOR in_array($row['user_feuserextension_parent'], explode(',',$this->conf['catShowAlways']))) {
			$CSSclass='allpublic';
		} elseif ($row['user_feuserextension_press']>0) {
			$CSSclass='press';
			if ($extconf['grppress']!=$grpVisible) {
				$CSSstyle .= 'display:none;';
			}
		} else {
			$CSSclass='public';
			if ($extconf['grppublic']!=$grpVisible) {
				$CSSstyle .= 'display:none;';
			}
		}
		
		//
		// determine si on affiche la categorie en tant que label ou non
		//
		
		if ($row['user_feuserextension_parent']==0) {
			if ($CSSclass=='press' XOR $CSSclass=='allpublic') {
				if (!in_array($row['uid'], explode(',',$this->conf['NotLabelList']))) {
					//t3lib_div::debug($titleText);
					$label=true;
					$CSSstyle.= $this->conf['LStyle'];
				}
			}
		}
		
		//
		// initialise le level
		//
		if ($level=='level2') {
			$extLevel=' ext-level2';
		}
		
		//
		// coche les case suivant si on est en mode create ou edit
		//
		if ($cmd=='create') {
			if ($row['user_feuserextension_checked']>0) $chkChecked=true;
		} elseif ($cmd=='edit') {
			$chkChecked = in_array($row['uid'], $valuesArray);
		};

		//
		// affiche les checkbox
		//
		if (!empty($CSSclass)) $class=' class="'.$CSSclass.'" '; 
		if (!empty($CSSstyle)) $style=' style="'.$CSSstyle.'" ';
		if ($label) {
			$colContent .= '<dt'.$class.$style.'>&nbsp;</dt>'."\r\n";
		} else {
			$colContent .= '<dt'.$class.$style.'>'."\r\n";
			$colContent .= '<input class="' . $this->pibase->pi_getClassName('checkbox').$extLevel. '" id="'. $this->pibase->pi_getClassName($colName) . '-' . $row['uid'] .'" name="FE['.$theTable.']['.$colName.']['.$row['uid']. ']" value="'.$row['uid'].'" type="checkbox"' . ($chkChecked ? ' checked="checked"' : '') . ' onClick="javascript:group2chk(this);" />';
			$colContent .= '</dt>'."\r\n";
		}
		
		//
		// affiche les labels
		//
		
		$colContent .= '<dd'.$class.$style.'>'."\r\n";
		if ($label) $colContent .= '<strong>';
		$colContent .= '<label for="'. $this->pibase->pi_getClassName($colName) . '-' . $row['uid'] .'" title="'.$row['user_feuserextension_description'].'" class="'.$level.'">'.$titleText.'</label>';
		if ($label) $colContent .= '</strong>';
		$colContent .= '</dd>'."\r\n";

		return $colContent;
	}

	function createChild(&$row2, &$colConfig,  $whereClause, &$colContent, &$titleText, &$valuesArray, &$titleField, $theTable, $colName, $op, $cmd, $extconf, $usergroup, $grpVisible) {
		/**
		*
		* Function qui affiche les categories enfants
		*
		**/
		global $TYPO3_DB, $TCA, $TSFE;
		
		$searchString = 'AND user_feuserextension_parent=0';
		$replaceString =  'AND user_feuserextension_parent='.$row2['uid'].' ';
		$whereClause = substr_replace($whereClause, $replaceString, stripos($whereClause,$searchString), 33);

		$res = $TYPO3_DB->exec_SELECTquery('*', $colConfig['foreign_table'], $whereClause, '', $TCA[$colConfig['foreign_table']]['ctrl']['sortby']);
		if (!in_array($colName, $this->controlData->getRequiredArray())) {
			if ($colConfig['renderMode'] == 'checkbox' || $colContent)	{
				// nothing
			} else {
				$colContent .= '<option value="" ' . ($valuesArray[0] ? '' : 'selected="selected"') . '></option>';
			}
		}

		while ($row3 = $TYPO3_DB->sql_fetch_assoc($res)) {
			if ($theTable == 'fe_users' && $colName == 'usergroup') {
				if (!in_array($row3['uid'], $reservedValues)) {
					$row3 = $this->getUsergroupOverlay($row3);
					$titleText = htmlspecialchars($row3[$titleField],ENT_QUOTES,$charset);
					$selected = (in_array($row3['uid'], $valuesArray) ? 'selected="selected"' : '');
					if(!$this->conf['allowMultipleUserGroupSelection'] && $selectedValue) {
						$selected = '';
					}
					$selectedValue = $selected ? true: $selectedValue;
					if ($colConfig['renderMode'] == 'checkbox' && $this->conf['templateStyle'] == 'css-styled')	{
						$colContent .= '<dt><input  class="' . $this->pibase->pi_getClassName('checkbox') . ' ext-level2" id="'. $this->pibase->pi_getClassName($colName) . '-' . $row3['uid'] .'" name="FE['.$theTable.']['.$colName.']['.$row3['uid'].'"]" value="'.$row3['uid'].'" type="checkbox"' . ($selected ? ' checked="checked"':'') . ' "/></dt>
						<dd><label for="'. $this->pibase->pi_getClassName($colName) . '-' . $row3['uid'] .'">'.$titleText.'</label></dd>';
					} else {
						$colContent .= '<option value="'.$row3['uid'].'"' . $selected . '>'.$titleText.'</option>';
					}
				}
			} else {
				if ($localizedRow = $TSFE->sys_page->getRecordOverlay($colConfig['foreign_table'], $row3, $this->controlData->sys_language_content)) {
					$row3 = $localizedRow;
				}
				$titleText = htmlspecialchars($row3[$titleField],ENT_QUOTES,$charset);
				if ($colConfig['renderMode']=='checkbox' && $this->conf['templateStyle'] == 'css-styled')	{
					$colContent .= $this->chk1label($row3, $valuesArray, $titleText, $theTable, $colName, $cmd, 'level2', $grpVisible) ;
				} else {
					$colContent .= '<option value="'.$row3['uid'].'"' . (in_array($row3['uid'], $valuesArray) ? 'selected="selected"' : '') . '>'.$titleText.'</option>';
				}
			}
		}

	}

	function addTcaMarkers(&$markerArray, $row, $origRow, $cmd, $cmdKey, $theTable, $viewOnly=false, $activity='', $bChangesOnly=false) {
		global $TYPO3_DB, $TCA, $TSFE;
		
		/* anc 10.02.2010 l+2 */
		$extconf=unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['user_feuser_extension']);
		$grpVisible=$extconf['grppublic'];
		$charset = $TSFE->renderCharset;
		$mode = $this->controlData->getMode();
		$tablesObj = &t3lib_div::getUserObj('&tx_srfeuserregister_lib_tables');
		$addressObj = $tablesObj->get('address');

		if ($bChangesOnly && is_array($origRow))	{
			$mrow = array();
			foreach ($origRow as $k => $v)	{
				if ($v != $row[$k])	{
					$mrow[$k] = $row[$k];
				}
			}
			$mrow['uid'] = $row['uid'];
			$mrow['pid'] = $row['pid'];
			$mrow['tstamp'] = $row['tstamp'];
			$mrow['username'] = $row['username'];
		} else {
			$mrow = $row;
		}
		$fields = $this->conf[$cmdKey.'.']['fields'];

		foreach ($this->TCA['columns'] as $colName => $colSettings) {
			if (t3lib_div::inList($fields, $colName)) {
				$colConfig = $colSettings['config'];
				$colContent = '';
				if (!$bChangesOnly || isset($mrow[$colName]))	{
					if ($mode == MODE_PREVIEW || $viewOnly) {
						// Configure preview based on input type
						switch ($colConfig['type']) {
							//case 'input':
							case 'text':
								$colContent = nl2br(htmlspecialchars($mrow[$colName],ENT_QUOTES,$charset));
								break;
							case 'check':
								if (is_array($colConfig['items'])) {
									$stdWrap = array();

									$bNotLast = FALSE;
									if (is_array($this->conf['check.']) && is_array($this->conf['check.'][$activity.'.']) &&
										is_array($this->conf['check.'][$activity.'.'][$colName.'.']) &&
										is_array($this->conf['check.'][$activity.'.'][$colName.'.']['item.']))	{
										$stdWrap = $this->conf['check.'][$activity.'.'][$colName.'.']['item.'];
										if ($this->conf['check.'][$activity.'.'][$colName.'.']['item.']['notLast'])	{
											$bNotLast = TRUE;
										}
									} else {
										$stdWrap['wrap'] = '<li>|</li>';
									}

									if (is_array($this->conf['check.']) && is_array($this->conf['check.'][$activity.'.']) &&
										is_array($this->conf['check.'][$activity.'.'][$colName.'.']) &&
										is_array($this->conf['check.'][$activity.'.'][$colName.'.']['list.']))	{
										$listWrap = $this->conf['check.'][$activity.'.'][$colName.'.']['list.'];
									} else {
										$listWrap['wrap'] = '<ul class="tx-srfeuserregister-multiple-checked-values">|</ul>';
									}

									$count = 0;
									foreach ($colConfig['items'] as $key => $value) {
										$count++;
										$label = htmlspecialchars($this->langObj->getLLFromString($colConfig['items'][$key][0]),ENT_QUOTES,$charset);
										$checked = ($mrow[$colName] & (1 << $key));
										$label = ($checked ? $label : '');
										$colContent .= ((!$bNotLast || $count < count($colConfig['items'])) ?  $this->cObj->stdWrap($label,$stdWrap) : $label);
									}
									$this->cObj->alternativeData = $colConfig['items'];
									$colContent = $this->cObj->stdWrap($colContent,$listWrap);
								} else {
									$colContent = $mrow[$colName]?htmlspecialchars($this->langObj->getLL('yes'),ENT_QUOTES,$charset):htmlspecialchars($this->langObj->getLL('no'),ENT_QUOTES,$charset);
								}
								break;
							case 'radio':
								if ($mrow[$colName] != '') {
									$valuesArray = is_array($mrow[$colName]) ? $mrow[$colName] : explode(',',$mrow[$colName]);

									$textSchema = $theTable.'.'.$colName.'.I.';
									$itemArray = $this->langObj->getItemsLL($textSchema, true);

									if (!count ($itemArray))	{
										$itemArray = $colConfig['items'];
									}

									if (is_array($itemArray)) {
										$itemKeyArray = $this->getItemKeyArray($itemArray);

										$stdWrap = array();
										$bNotLast = FALSE;
										if (is_array($this->conf['radio.']) && is_array($this->conf['radio.'][$activity.'.']) &&
										is_array($this->conf['radio.'][$activity.'.'][$colName.'.']) &&
										is_array($this->conf['radio.'][$activity.'.'][$colName.'.']['item.']))	{
											$stdWrap = $this->conf['radio.'][$activity.'.'][$colName.'.']['item.'];
											if ($this->conf['radio.'][$activity.'.'][$colName.'.']['item.']['notLast'])	{
												$bNotLast = TRUE;
											}
										} else {
											$stdWrap['wrap'] = '| ';
										}
										for ($i = 0; $i < count ($valuesArray); $i++) {
											$label = $this->langObj->getLLFromString($itemKeyArray[$valuesArray[$i]][0]);
											$label = htmlspecialchars($label,ENT_QUOTES,$charset);
											$colContent .= ((!$bNotLast || $i < count($valuesArray) - 1 ) ?  $this->cObj->stdWrap($label,$stdWrap) : $label);
										}
									}
								}
								break;
							case 'select':
								if ($mrow[$colName] != '') {
									$valuesArray = is_array($mrow[$colName]) ? $mrow[$colName] : explode(',',$mrow[$colName]);
									$textSchema = $theTable.'.'.$colName.'.I.';
									$itemArray = $this->langObj->getItemsLL($textSchema, true);
									if (!count ($itemArray))	{
										$itemArray = $colConfig['items'];
									}
									$stdWrap = array();
									$bNotLast = FALSE;
									if (is_array($this->conf['select.']) && is_array($this->conf['select.'][$activity.'.']) &&
										is_array($this->conf['select.'][$activity.'.'][$colName.'.']) &&
										is_array($this->conf['select.'][$activity.'.'][$colName.'.']['item.']))	{
										$stdWrap = $this->conf['select.'][$activity.'.'][$colName.'.']['item.'];
										if ($this->conf['select.'][$activity.'.'][$colName.'.']['item.']['notLast'])	{
											$bNotLast = TRUE;
										}
									} else {
										$stdWrap['wrap'] = '|<br />';
									}

									if (is_array($itemArray)) {
										$itemKeyArray = $this->getItemKeyArray($itemArray);

										for ($i = 0; $i < count ($valuesArray); $i++) {
											$label = $this->langObj->getLLFromString($itemKeyArray[$valuesArray[$i]][0]);
											$label = htmlspecialchars($label,ENT_QUOTES,$charset);

											$colContent .= ((!$bNotLast || $i < count($valuesArray) - 1 ) ?  $this->cObj->stdWrap($label,$stdWrap) : $label);
										}
									}

									if ($colConfig['foreign_table']) {
										t3lib_div::loadTCA($colConfig['foreign_table']);
										$reservedValues = array();
										if ($theTable == 'fe_users' && $colName == 'usergroup') {
											$userGroupObj = &$addressObj->getFieldObj ('usergroup');
											$reservedValues = $userGroupObj->getReservedValues();
										}
										$valuesArray = array_diff($valuesArray, $reservedValues);
										reset($valuesArray);
										$firstValue = current($valuesArray);
										if (!empty($firstValue) || count ($valuesArray) > 1) {
											$titleField = $TCA[$colConfig['foreign_table']]['ctrl']['label'];
											$where = 'uid IN ('.implode(',', $valuesArray).')';

											$res = $TYPO3_DB->exec_SELECTquery(
												'*',
												$colConfig['foreign_table'],
												$where
												);
											$i = 0;
											/* anc report l+1 */
											$languageUid = $this->controlData->getSysLanguageUid('ALL',$colConfig['foreign_table']);
											while ($row2 = $TYPO3_DB->sql_fetch_assoc($res)) {

												if ($theTable == 'fe_users' && $colName == 'usergroup') {
													$row2 = $this->getUsergroupOverlay($row2);
												} else if ($localizedRow = $TSFE->sys_page->getRecordOverlay($colConfig['foreign_table'], $row2, $this->controlData->sys_language_content)) {
													$row2 = $localizedRow;
												}
												$text = htmlspecialchars($row2[$titleField],ENT_QUOTES,$charset);
												$colContent .= $this->cObj->stdWrap($text,$stdWrap);
	// TODO: consider $bNotLast
											}
										}
									}
								}
								break;
							default:
								// unsupported input type
								$colContent .= $colConfig['type'].':'.htmlspecialchars($this->langObj->getLL('unsupported'),ENT_QUOTES,$charset);
								break;
						}
					//
					// not mode mode preview
					//
					} else {
						// Configure inputs based on TCA type
						/* anc 10.02.2010 report l+14*/
						if (in_array($colConfig['type'], array('check', 'radio', 'select')))	{
							$valuesArray = is_array($mrow[$colName]) ? $mrow[$colName] : explode(',',$mrow[$colName]);

							if (!$valuesArray[0] && $colConfig['default']) {
								$valuesArray[] = $colConfig['default'];
							}
							$textSchema = $theTable.'.'.$colName.'.I.';
							$itemArray = $this->langObj->getItemsLL($textSchema, true);
							$bUseTCA = false;
							if (!count($itemArray))	{
								$itemArray = $colConfig['items'];
								$bUseTCA = true;
							}
						}
						switch ($colConfig['type']) {
							case 'input':
								$colContent = '<input type="input" name="FE['.$this->theTable.']['.$colName.']"'.
									' size="'.($colConfig['size']?$colConfig['size']:30).'"';
								if ($colConfig['max']) {
									$colContent .= ' maxlength="'.$colConfig['max'].'"';
								}
								if ($colConfig['default']) {
									$label = $this->langObj->getLLFromString($colConfig['default']);
									$label = htmlspecialchars($label,ENT_QUOTES,$charset);
									$colContent .= ' value="'.$label.'"';
								}
								$colContent .= ' />';
								break;
							case 'text':
								$label = $this->langObj->getLLFromString($colConfig['default']);
								$label = htmlspecialchars($label,ENT_QUOTES,$charset);
								$colContent = '<textarea id="'. $this->pibase->pi_getClassName($colName) . '" name="FE['.$theTable.']['.$colName.']"'.
									' title="###TOOLTIP_' . (($cmd == 'invite')?'INVITATION_':'') . $this->cObj->caseshift($colName,'upper').'###"'.
									' cols="'.($colConfig['cols']?$colConfig['cols']:30).'"'.
									' rows="'.($colConfig['rows']?$colConfig['rows']:5).'"'.
									'>'.($colConfig['default']?$label:'').'</textarea>';
								break;
							case 'check':
								$label = $this->langObj->getLL('tooltip_' . $colName);
								$label = htmlspecialchars($label,ENT_QUOTES,$charset);
								/* anc 10.02.2010 report +2 */
								//if (is_array($colConfig['items'])) {
								if (is_array($itemArray)) {
									$uidText = $this->pibase->pi_getClassName($colName).'-'.$mrow['uid'];
									$colContent  = '<ul id="'. $uidText . ' " class="tx-srfeuserregister-multiple-checkboxes">';
								/* anc 10.02.2010 report +2 */
								//foreach ($colConfig['items'] as $key => $value) {
								foreach ($itemArray as $key => $value) {
										if ($this->controlData->getSubmit() || $cmd=='edit')	{
											$startVal = $mrow[$colName];
										} else {
											$startVal = $colConfig['default'];
										}
										$checked = ($startVal & (1 << $key))?' checked="checked"':'';
										$label = $this->langObj->getLLFromString($colConfig['items'][$key][0]);
										$label = htmlspecialchars($label,ENT_QUOTES,$charset);
										$colContent .= '<li><input type="checkbox"' . $this->pibase->pi_classParam('checkbox') . ' id="' . $uidText . '-' . $key .  ' " name="FE['.$theTable.']['.$colName.'][]" value="'.$key.'"'.$checked.' /><label for="' . $uidText . '-' . $key .  '">' . $label . '</label></li>';
									}
									$colContent .= '</ul>';
								} else {
									//
									// this line is use for Receive HTML e-mails?
									//
									$colContent = '<input type="checkbox"' . $this->pibase->pi_classParam('checkbox') . ' id="'. $this->pibase->pi_getClassName($colName) . '" name="FE['.$theTable.']['.$colName.']" title="'.$label.'"' . ($mrow[$colName]?' checked="checked"':'') . ' />';
								}
								break;
							case 'radio':
								$startVal = $colConfig['default'];
								if (!isset($startVal))	{
									reset($colConfig['items']);
									list ($startKey, $startConf) = $colConfig['items'];
									$startVal = $startConf[1];
								}
								
								/* anc 10.02.2010 report +5 */
								//if (isset($colConfig['items']) && is_array($colConfig['items']))	{
								if (isset($itemArray) && is_array($itemArray))	{
									$i = 0;
									//foreach ($colConfig['items'] as $key => $confArray) {
									foreach ($itemArray as $key => $confArray) {
										$value = $confArray[1];
										$label = $this->langObj->getLLFromString($confArray[0]);
										$label = htmlspecialchars($label,ENT_QUOTES,$charset);
										/* anc 10.02.2010 report +7 */
										/*$colContent .= '<input type="radio"' . $this->pibase->pi_classParam('radio') . ' id="'. $this->pibase->pi_getClassName($colName) . '" name="FE['.$theTable.']['.$colName.']"'.
												' value="'.$value.'" '.($value==$startVal ? ' checked="checked"' : '').' />' .
												'<label for="' . $this->pibase->pi_getClassName($colName) . '-' . $key . '">' . $label . '</label>';*/
										$colContent .= '<input type="radio"' . $this->pibase->pi_classParam('radio') . ' id="'. $this->pibase->pi_getClassName($colName) . '-' . $i . '" name="FE['.$theTable.']['.$colName.']"' .
																					' value="' . $value . '" ' . ($value==$startVal ? ' checked="checked"' : '') . ' />' .
																					'<label for="' . $this->pibase->pi_getClassName($colName) . '-' . $i . '">' . $label . '</label>';
										$i++;
									}
								}
								break;
							case 'select':
								$colContent ='';
								/* anc 10.02.2010 report +4 */
								/*$valuesArray = is_array($mrow[$colName]) ? $mrow[$colName] : explode(',',$mrow[$colName]);
								if (!$valuesArray[0] && $colConfig['default']) {
									$valuesArray[] = $colConfig['default'];
								}*/
								if ($colConfig['maxitems'] > 1) {
									$multiple = '[]" multiple="multiple';
								} else {
									$multiple = '';
								}
								if ($theTable == 'fe_users' && $colName == 'usergroup' && !$this->conf['allowMultipleUserGroupSelection']) {
									$multiple = '';
								}
								if ($colConfig['renderMode'] == 'checkbox' && $this->conf['templateStyle'] == 'css-styled')	{
									$colContent .='
											<input id="'. $this->pibase->pi_getClassName($colName) . '" name="FE['.$theTable.']['.$colName.']" value="" type="hidden" />';
									$colContent .='
											<dl class="' . $this->pibase->pi_getClassName('multiple-checkboxes') . '" title="###TOOLTIP_' . (($cmd == 'invite')?'INVITATION_':'') . $this->cObj->caseshift($colName,'upper').'###">';
								} else {
									/** anc 26 may 09 
									* This line show the select box for groups
									*/
									//$colContent .= '<select id="'. $this->pibase->pi_getClassName($colName) . '" name="FE['.$theTable.']['.$colName.']' . $multiple . '" title="###TOOLTIP_' . (($cmd == 'invite')?'INVITATION_':'') . $this->cObj->caseshift($colName,'upper').'###">';
									$colContent .= '<select id="'. $this->pibase->pi_getClassName($colName) . '" name="FE['.$theTable.']['.$colName.']' . $multiple . '" title="###TOOLTIP_' . (($cmd == 'invite')?'INVITATION_':'') . $this->cObj->caseshift($colName,'upper').'###"'.(($this->pibase->pi_getClassName($colName)=='user-feuserextension-pi2-usergroup')?' onchange="pressOrNotPress(this);':'').'">';
								}
								/* anc 10.02.2010 report +6 */
								/*$textSchema = $theTable.'.'.$colName.'.I.';
								$itemArray = $this->langObj->getItemsLL($textSchema, true);
								$bUseTCA = false;
								if (!count ($itemArray))	{
									$itemArray = $colConfig['items'];
									$bUseTCA = true;
								}*/

								if (is_array($itemArray)) {
									$itemArray = $this->getItemKeyArray($itemArray);
									$i = 0;
									/* anc 10.02.2010 report +6 */
									/*if ($bUseTCA)	{
										$deftext = $itemArray[$i][0];
										$deftext = substr($deftext, 0, strlen($deftext) - 2);
									}

									$i = 0;*/
									foreach ($itemArray as $k => $item)	{
										$label = $this->langObj->getLLFromString($item[0],true);
										$label = htmlspecialchars($label,ENT_QUOTES,$charset);
										if ($colConfig['renderMode'] == 'checkbox' && $this->conf['templateStyle'] == 'css-styled')	{

											$colContent .= '<dt><input class="' . $this->pibase->pi_getClassName('checkbox') . '" id="'. $this->pibase->pi_getClassName($colName) . '-' . $i .'" name="FE['.$theTable.']['.$colName.']['.$k.']" value="'.$k.'" type="checkbox"  ' . (in_array($k, $valuesArray) ? ' checked="checked"' : '') . ' /></dt>
													<dd><label for="'. $this->pibase->pi_getClassName($colName) . '-' . $i .'">'.$label.'</label></dd>';

										} else {
											$colContent .= '<option value="'.$k. '" ' . (in_array($k, $valuesArray) ? 'selected="selected"' : '') . '>' . $label.'</option>';
										}
										$i++;
									}
								}
								if ($colConfig['foreign_table']) {
									t3lib_div::loadTCA($colConfig['foreign_table']);
									$titleField = $TCA[$colConfig['foreign_table']]['ctrl']['label'];
									if ($theTable == 'fe_users' && $colName == 'usergroup') {
										$userGroupObj = &$addressObj->getFieldObj ('usergroup');
										$reservedValues = $userGroupObj->getReservedValues();
										$selectedValue = false;
									}
									$whereClause = ($theTable == 'fe_users' && $colName == 'usergroup') ? ' pid='.intval($this->controlData->getPid()).' ' : ' 1=1';
									if ($TCA[$colConfig['foreign_table']] && $TCA[$colConfig['foreign_table']]['ctrl']['languageField'] && $TCA[$colConfig['foreign_table']]['ctrl']['transOrigPointerField']) {
										$whereClause .= ' AND '.$TCA[$colConfig['foreign_table']]['ctrl']['transOrigPointerField'].'=0';
									}
									if ($colName == 'module_sys_dmail_category' && $colConfig['foreign_table'] == 'sys_dmail_category' && $this->conf['module_sys_dmail_category_PIDLIST']) {
										/* anc 10.02.2010 report +10 */
										$languageUid = $this->controlData->getSysLanguageUid('ALL',$colConfig['foreign_table']);
										$tmpArray = t3lib_div::trimExplode(',',$this->conf['module_sys_dmail_category_PIDLIST']);
										$pidArray = array();
										foreach ($tmpArray as $v)	{
											if (is_numeric($v))	{
												$pidArray[] = $v;
											}
										}
										$whereClause .= ' AND sys_dmail_category.pid IN (' . $TYPO3_DB->fullQuoteStr($this->conf['module_sys_dmail_category_PIDLIST'], 'sys_dmail_category') . ')'.' AND user_feuserextension_parent=0';
										/* anc 10.02.2010 l+1 ad close user_feuserextension_parent=0*/
										//$whereClause .= ' AND sys_dmail_category.pid IN (' . implode(',',$pidArray) . ')' . ' AND user_feuserextension_parent=0 AND sys_language_uid=' . $languageUid;
										/* anc ? config the order by */
										$TCA[$colConfig['foreign_table']]['ctrl']['sortby']=user_feuserextension_order;

									}
									$whereClause .= $this->cObj->enableFields($colConfig['foreign_table']);
									$res = $TYPO3_DB->exec_SELECTquery('*', $colConfig['foreign_table'], $whereClause, '', $TCA[$colConfig['foreign_table']]['ctrl']['sortby']);
									if (!in_array($colName, $this->controlData->getRequiredArray())) {
										if ($colConfig['renderMode'] == 'checkbox' || $colContent)	{
											// nothing
										} else {
											$colContent .= '<option value="" ' . ($valuesArray[0] ? '' : 'selected="selected"') . '></option>';
										}
									}

									while ($row2 = $TYPO3_DB->sql_fetch_assoc($res)) {
										if ($theTable == 'fe_users' && $colName == 'usergroup') {
											if (!in_array($row2['uid'], $reservedValues)) {
												$row2 = $this->getUsergroupOverlay($row2);
												$titleText = htmlspecialchars($row2[$titleField],ENT_QUOTES,$charset);
												$selected = (in_array($row2['uid'], $valuesArray) ? 'selected="selected"' : '');
												if(!$this->conf['allowMultipleUserGroupSelection'] && $selectedValue) {
													$selected = '';
												}
												$selectedValue = $selected ? true: $selectedValue;
												if ($colConfig['renderMode'] == 'checkbox' && $this->conf['templateStyle'] == 'css-styled')	{
													$colContent .= '<dt><input  class="' . $this->pibase->pi_getClassName('checkbox') . '" id="'. $this->pibase->pi_getClassName($colName) . '-' . $row2['uid'] .'" name="FE['.$theTable.']['.$colName.']['.$row2['uid'].'"]" value="'.$row2['uid'].'" type="checkbox"' . ($selected ? ' checked="checked"':'') . '  /></dt>
													<dd><label for="'. $this->pibase->pi_getClassName($colName) . '-' . $row2['uid'] .'">'.$titleText.'</label></dd>';
												} else {
													/** anc 12.02.10 l+10 */
													
													for ($ct=0; $ct<count($row['usergroup']);$ct++) {
														if ($extconf['grppublic']==$row['usergroup'][$ct] XOR $extconf['grppress']==$row['usergroup'][$ct]) {
															$grpVisible=$row['usergroup'][$ct];
														}
													}
													
													//t3lib_div::debug($grpVisible);
													if ($row2['uid']==$grpVisible) {
														$selected=' selected="selected"';
													} else {
														$selected='';
													};
													// les options du group sont cree ici
													if ($extconf['grppublic']==$row2['uid'] xor $extconf['grppress']==$row2['uid']) {
														$colContent .= '<option value="'.$row2['uid'].'"' . $selected . '>'.$titleText.'</option>';
													}
												}
											}
										} else {
											/* anc 10.02.2010 report +3 */
											$languageUid = $this->controlData->getSysLanguageUid('ALL',$colConfig['foreign_table']);
											//if ($localizedRow = $TSFE->sys_page->getRecordOverlay($colConfig['foreign_table'], $row2, $this->controlData->sys_language_content)) {
											if ($localizedRow = $TSFE->sys_page->getRecordOverlay($colConfig['foreign_table'], $row2, $languageUid)) {
												$row2 = $localizedRow;
											}
											$titleText = htmlspecialchars($row2[$titleField],ENT_QUOTES,$charset);
											if ($colConfig['renderMode']=='checkbox' && $this->conf['templateStyle'] == 'css-styled')	{
												/** anc */
												$op=false;
												/* anc 22.10.2009 */
												// on utilise $this->chk1label pour la categorie parent
												$colContent .= $this->chk1label($row2, $valuesArray, $titleText, $theTable, $colName, $cmd, '',$grpVisible) ;
												// puis on utilise $this->createChild pour creer les enfants
												$colContent .= $this->createChild($row2, $colConfig, $whereClause, $colContent, $titleText, $valuesArray, $titleField, $theTable, $colName, $op, $cmd, $extconf, $row['usergroup'],$grpVisible);


											} else {
												$colContent .= '<option value="'.$row2['uid'].'"' . (in_array($row2['uid'], $valuesArray) ? 'selected="selected"' : '') . '>'.$titleText.'</option>';
											}
										}
									}
								}
								if ($colConfig['renderMode'] == 'checkbox' && $this->conf['templateStyle'] == 'css-styled')	{
									$colContent .= '</dl>';
								} else {
									$colContent .= '</select>';
								}
								break;
							default:
								$colContent .= $colConfig['type'].':'.$this->langObj->getLL('unsupported');
								break;
						}
					}

				} else {
					$colContent = '';
				}

				if ($mode == MODE_PREVIEW || $viewOnly) {
					$markerArray['###TCA_INPUT_VALUE_'.$colName.'###'] = $colContent;
				}
				
				$markerArray['###TCA_INPUT_'.$colName.'###'] = $colContent;
				
			} else {
				// field not in form fields list
			}

		}
		
	}
	
}
?>