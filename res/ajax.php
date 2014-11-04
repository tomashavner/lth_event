<?php

// Exit, if script is called directly (must be included via eID in index_ts.php)
if (!defined ('PATH_typo3conf')) die ('Could not access this script directly!');

$id = isset($HTTP_GET_VARS['id'])?$HTTP_GET_VARS['id']:0;
$action = htmlspecialchars(t3lib_div::_GP("action"));
$query = htmlspecialchars(t3lib_div::_GP("query"));
$sid = htmlspecialchars(t3lib_div::_GP("sid"));

tslib_eidtools::connectDB();

//initTSFE($id);
/*require_once(PATH_tslib.'class.tslib_fe.php');
require_once(PATH_t3lib.'class.t3lib_page.php');
require_once(PATH_t3lib.'class.t3lib_tstemplate.php');
require_once(PATH_t3lib.'class.t3lib_cs.php');
require_once(PATH_t3lib.'class.t3lib_userauth.php');
require_once(PATH_tslib.'class.tslib_feuserauth.php');
require_once(PATH_tslib.'class.tslib_content.php');

//$TSFEclassName = t3lib_div::makeInstance('tslib_fe');


// Connect to database:
tslib_eidtools::connectDB();

//$GLOBALS['TSFE'] = new $TSFEclassName($TYPO3_CONF_VARS, $id, '0', 1, '','','','');
$GLOBALS['TSFE'] = t3lib_div::makeInstance('tslib_fe', $TYPO3_CONF_VARS, $id, 0, true);

$GLOBALS['TSFE']->initFEuser();
$GLOBALS['TSFE']->fetch_the_id();
$GLOBALS['TSFE']->getPageAndRootline();
$GLOBALS['TSFE']->initTemplate();
$GLOBALS['TSFE']->tmpl->getFileName_backPath = PATH_site;
$GLOBALS['TSFE']->forceTemplateParsing = 1;
$GLOBALS['TSFE']->getConfigArray();
TSpagegen::pagegenInit();

// Initialize FE user object:
tslib_eidtools::initFeUser();
//$usergroup = $feUserObj->user['usergroup'];
*/
$action = htmlspecialchars(t3lib_div::_GP("action"));

$imageSokvag = "/fileadmin/user_portraits/";

switch($action) {
    case "getEvents":
        echo getEvents($query);
        break;
    case "getEventForm":
        echo getEventForm();
        break;
    case "saveEventForm":
        initTSFE($id);
        tslib_eidtools::connectDB();
        echo saveEventForm($query);
        break;
}

function initTSFE($pageUid=1)
{
    require_once(PATH_tslib.'class.tslib_fe.php');
    require_once(PATH_t3lib.'class.t3lib_userauth.php');
    require_once(PATH_tslib.'class.tslib_feuserauth.php');
    require_once(PATH_t3lib.'class.t3lib_cs.php');
    require_once(PATH_tslib.'class.tslib_content.php');
    require_once(PATH_t3lib.'class.t3lib_tstemplate.php');
    require_once(PATH_t3lib.'class.t3lib_page.php');

    //$TSFEclassName = t3lib_div::makeInstance('tslib_fe');

    if (!is_object($GLOBALS['TT'])) {
        $GLOBALS['TT'] = new t3lib_timeTrack;
        $GLOBALS['TT']->start();
    }

    // Create the TSFE class.
    //$GLOBALS['TSFE'] = new $TSFEclassName($GLOBALS['TYPO3_CONF_VARS'],$pageUid,'0',1,'','','','');
    $GLOBALS['TSFE'] = t3lib_div::makeInstance('tslib_fe');
    $GLOBALS['TSFE']->connectToDB();
    $GLOBALS['TSFE']->initFEuser();
    $GLOBALS['TSFE']->fetch_the_id();
    $GLOBALS['TSFE']->getPageAndRootline();
    $GLOBALS['TSFE']->initTemplate();
    $GLOBALS['TSFE']->tmpl->getFileName_backPath = PATH_site;
    $GLOBALS['TSFE']->forceTemplateParsing = 1;
    $GLOBALS['TSFE']->getConfigArray();
}

function getEvents($query)
{
    //--------------------------------------------------------------------------------------------------
    // This script reads event data from a JSON file and outputs those events which are within the range
    // supplied by the "start" and "end" GET parameters.
    //
    // An optional "timezone" GET parameter will force all ISO8601 date stings to a given timezone.
    //
    // Requires PHP 5.2.0 or higher.
    //--------------------------------------------------------------------------------------------------

    $input_arrays = array();
    // Require our Event class and datetime utilities
    require dirname(__FILE__) . '/utils.php';

    // Short-circuit if the client did not give us a date range.
    if (!isset($_GET['start']) || !isset($_GET['end'])) {
            die("Please provide a date range.");
    }

    // Parse the start/end parameters.
    // These are assumed to be ISO8601 strings with no time nor timezone, like "2013-12-29".
    // Since no timezone will be present, they will parsed as UTC.
    $range_start = parseDateTime($_GET['start']);
    $range_end = parseDateTime($_GET['end']);

    // Parse the timezone parameter if it is present.
    $timezone = null;
    if (isset($_GET['timezone'])) {
            $timezone = new DateTimeZone($_GET['timezone']);
    }

    // Read and parse our events JSON file into an array of event data arrays.
    //$json = file_get_contents(dirname(__FILE__) . '/events.json');
    
    ///////////////////////
    $username = addslashes($GLOBALS['TSFE']->fe_user->user['username']);

    $query = html_entity_decode($query);
    
    $query = str_replace('&quot;','"',$query);
    if (get_magic_quotes_gpc() == 1) {
        $query = stripslashes($query);
    }
    $query = str_replace('\\', '', $query);
    
            //$res = $GLOBALS["TYPO3_DB"]->exec_INSERTquery("tx_devlog", array("msg" =>$query));

    $data = json_decode($query);
    
    $res = $GLOBALS["TYPO3_DB"]->exec_SELECTquery("event,start,end", 
            "tx_lthevents_event", 
            "pid=" . intval($data->storage), "", "", "") or die("127: ".$pid.mysql_error());
    while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
        $event = $row["event"];
        $start = $row["start"];
        $end = $row["end"];
        $input_arrays[] = array("title"=>$event,"start"=>date('Y-m-d H:i:s',$start),"end"=>date('Y-m-d H:i:s',$end));
    }

    $GLOBALS["TYPO3_DB"]->sql_free_result($res);
    
    
    ////////////////////////
    
    
    
    
    
    
    //$input_arrays = json_decode($json, true);

    // Accumulate an output array of event data arrays.
    $output_arrays = array();
    foreach ($input_arrays as $array) {

            // Convert the input array into a useful Event object
            $event = new Event($array, $timezone);

            // If the event is in-bounds, add it to the output
            if ($event->isWithinDayRange($range_start, $range_end)) {
                    $output_arrays[] = $event->toArray();
            }
    }

    // Send JSON to the client.
    return json_encode($output_arrays);
}

function getEventForm()
{
        $content = '<form name="caldlgfrm" id="caldlgfrm" action="" method="POST" style="width:100%;">

    <table>
            <tr>
                    <td>
                            <input type="checkbox" name="dlg_allday" id="dlg_allday" value="1" />allday
                    </td>
                                       <td>
                            <input type="checkbox" onclick="alert(\'Mailform???\');" name="dlg_signup" id="dlg_signup"  value="1" />Sign up
                    </td> 
            </tr>
            <tr>
                    <td>
                            Start:
                    </td>
                    <td align="left">
                            <input class="time" type="text" id="event_start_date" name="event_start_date"  value="" style="width:120px;" />
                            <input class="date" type="text" id="event_start_time" name="event_start_time"  value="" style="width:80px;" />
                    </td>
            </tr>
            <tr>
                    <td>
                            End:
                    </td>
                    <td align="left">
                            <input class="time" type="text" id="event_end_date" name="event_end_date" value="" style="width:120px;" />
                            <input class="date" type="text" id="event_end_time" name="event_end_time" value="" style="width:80px;"/>
                    </td>
            </tr>
            <tr>
                    <td>
                            Subject:
                    </td>
                    <td align="left">
                            <input type="text" id="event_subject" name="event_subject" maxlength="255" style="width:460px;"/>
                    </td>
            </tr>
    </table>

    <center><textarea name="event_description" id="event_description"
                     rows="9" style="width:520px;"></textarea></center>

    <center>
            <button type="button" id="dlgcommit" onclick="saveEditForm();">New event</button>
            <button type="button" id="dlgcancel">Cancel</button>
    </center>
    </form>';
    return json_encode($content);
}

function saveEventForm($query)
{
    $username = addslashes($GLOBALS['TSFE']->fe_user->user['username']);

    $query = str_replace('&quot;','"',$query);
    if (get_magic_quotes_gpc() == 1) {
        $query = stripslashes($query);
    }
    $query = str_replace('\\', '', $query);
    $data = json_decode($query);
    
    //                    query : JSON.stringify({"event_subject":event_subject,"event_description":event_description,"event_start_date":event_start_date,"event_start_time":event_start_time,"event_end_date":event_end_date,"event_end_time":event_end_time,"dlg_all":dlg_allday,"dlg_signin":dlg_signin}),

    
    try {
        $insertArray = array('pid' => $data->storage, 'username' => $username, 'event' => $data->event, 'description' => $data->description, 'start' => strtotime($data->start), 'end' => strtotime($data->end), 'signup' => $data->dlg_signup, 'allday' => $data->dlg_allday, 'tstamp' => time(), 'crdate' => time());
        $res = $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_lthevents_event', $insertArray);
        $content = 'ok';
    }
    catch(Exception $e) {
        $content = '220:' . $e->getMessage();
    }
    return json_encode($content);
}