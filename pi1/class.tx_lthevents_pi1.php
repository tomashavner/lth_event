<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Tomas <tomas.havner@kansli.lth.se>
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

// require_once(PATH_tslib . 'class.tslib_pibase.php');

/**
 * Plugin 'LTH Events' for the 'lth_events' extension.
 *
 * @author	Tomas <tomas.havner@kansli.lth.se>
 * @package	TYPO3
 * @subpackage	tx_lthevents
 */
class tx_lthevents_pi1 extends tslib_pibase {
	public $prefixId      = 'tx_lthevents_pi1';		// Same as class name
	public $scriptRelPath = 'pi1/class.tx_lthevents_pi1.php';	// Path to this script relative to the extension dir.
	public $extKey        = 'lth_events';	// The extension key.
	public $pi_checkCHash = TRUE;
	
	/**
	 * The main method of the Plugin.
	 *
	 * @param string $content The Plugin content
	 * @param array $conf The Plugin configuration
	 * @return string The content that is displayed on the website
	 */
	public function main($content, array $conf) {
            $this->conf = $conf;
            $this->pi_setPiVarDefaults();
            $this->pi_loadLL();
            
            /*$this->pi_initPIflexForm();
            $piFlexForm = $this->cObj->data["pi_flexform"];
            $index = $GLOBALS["TSFE"]->sys_language_uid;
            $sDef = current($piFlexForm["data"]);       
            $lDef = array_keys($sDef);
            $storage = $this->pi_getFFvalue($piFlexForm, "storage", "sDEF", $lDef[$index]);*/
            
            if(!$storage) {
                $storage = $this->cObj->data['uid'];
            }

            $GLOBALS["TSFE"]->additionalHeaderData["tx_lthevents_dateformat_js"] = "<script type=\"text/javascript\" src=\"/typo3conf/ext/lth_events/vendor/fullcalendar/lib/dateformat.js\"></script>";
            $GLOBALS["TSFE"]->additionalHeaderData["tx_lthevents_moment_js"] = "<script type=\"text/javascript\" src=\"/typo3conf/ext/lth_events/vendor/fullcalendar/lib/moment.min.js\"></script>";
            $GLOBALS["TSFE"]->additionalHeaderData["tx_lthevents_css"] = "<link rel=\"stylesheet\" type=\"text/css\" href=\"typo3conf/ext/lth_events/vendor/fullcalendar/fullcalendar.min.css\" />"; 
            //$GLOBALS["TSFE"]->additionalHeaderData["tx_lthevents_jqury_ui_js"] = "<script type=\"text/javascript\" src=\"/typo3conf/ext/lth_events/vendor/fullcalendar/lib/jquery-ui.min.js\"></script>";
            $GLOBALS["TSFE"]->additionalHeaderData["tx_lthevents_jquery_timepicker_js"] = "<script type=\"text/javascript\" src=\"/typo3conf/ext/lth_events/vendor/fullcalendar/lib/jquery.timepicker.min.js\"></script>";
            $GLOBALS["TSFE"]->additionalHeaderData["tx_lthevents_jquery_timepicker_css"] = "<link rel=\"stylesheet\" type=\"text/css\" href=\"/typo3conf/ext/lth_events/vendor/fullcalendar/lib/jquery.timepicker.css\" />";
            $GLOBALS["TSFE"]->additionalHeaderData["tx_lthevents_fullcalendar_js"] = "<script type=\"text/javascript\" src=\"/typo3conf/ext/lth_events/vendor/fullcalendar/fullcalendar.js\"></script>";
            $GLOBALS["TSFE"]->additionalHeaderData["tx_lthevents_fullcalendar_css"] = "<link rel=\"stylesheet\" type=\"text/css\" href=\"/typo3conf/ext/lth_events/vendor/fullcalendar/fullcalendar.css\" />";
            //$GLOBALS["TSFE"]->additionalHeaderData["tx_lthevents_fullcalendar_print_css"] = "<link rel=\"stylesheet\" type=\"text/css\" href=\"/typo3conf/ext/lth_events/vendor/fullcalendar/fullcalendar.print.css\" />";
            $GLOBALS["TSFE"]->additionalHeaderData["tx_lthevents_js"] = "<script type=\"text/javascript\" src=\"/typo3conf/ext/lth_events/res/lth_events.js\"></script>";

            $GLOBALS["TSFE"]->additionalHeaderData["tx_lthevents_bootstrap_datepicker_css"] = "<link rel=\"stylesheet\" type=\"text/css\" href=\"/typo3conf/ext/lth_events/res/bootstrap-datepicker.css\" />";
            $GLOBALS["TSFE"]->additionalHeaderData["tx_lthevents_bootstrap_datepicker_js"] = "<script type=\"text/javascript\" src=\"/typo3conf/ext/lth_events/res/bootstrap-datepicker.js\"></script>";
            $GLOBALS["TSFE"]->additionalHeaderData["tx_lthevents_datepair_js"] = "<script type=\"text/javascript\" src=\"/typo3conf/ext/lth_events/res/datepair.js\"></script>";

            $content = $this->htmlForm($storage);

            return $content;
	}
        
        function htmlForm($storage)
        {
            $content = '
            <div id="script-warning">
		<code>php/get-events.php</code> must be running.
            </div>

            <div id="loading">loading...</div>

            <div id="editdialog">
            </div>

            <div id="calendar"></div>
            <form name="calendarCtrlfrm" id="calendarCtrlfrm" action="index.html" method="POST">
                <input type="hidden" name="syear" id="syear" value="{$IN.syear}" />
                <input type="hidden" name="smonth" id="smonth" value="{$IN.smonth}" />
                <input type="hidden" name="sdate" id="sdate" value="{$IN.sdate}" />
                <input type="hidden" name="defaultView" id="defaultView" value="{$IN.defaultView}" />

                <input type="hidden" name="hdn_event_subject" id="hdn_event_subject" value="" />
                <input type="hidden" name="hdn_event_description" id="hdn_event_description" value="" />
                <input type="hidden" name="hdn_event_start_date" id="hdn_event_start_date" value="" />
                <input type="hidden" name="hdn_event_start_time" id="hdn_event_start_time" value="" />
                <input type="hidden" name="hdn_event_end_date" id="hdn_event_end_date" value="" />
                <input type="hidden" name="hdn_event_end_time" id="hdn_event_end_time" value="" />
                <input type="hidden" name="hdn_dlg_allday" id="hdn_dlg_allday" value="" />

                <input type="hidden" name="hdn_event_id" id="hdn_event_id" value="" />
                <input type="hidden" name="storage" id="storage" value="'.$storage.'" />

                <input type="hidden" name="cmd" id="cmd" value="" />
                </form>
                ';
            
            return $content;
        }
}



if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/lth_events/pi1/class.tx_lthevents_pi1.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/lth_events/pi1/class.tx_lthevents_pi1.php']);
}

?>