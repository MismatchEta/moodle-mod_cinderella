<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This file contains definitions and functions for other parts of the plugin.
 * 
 * @package   mod_cinderella
 * @copyright 2023 Marcus Röhming <marcus.roehming@outlook.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();    // prevents access from outside Moodle

define('CINDYJS_SRC', 'https://cindyjs.org/dist/latest/Cindy.js');
define('CINDYCSS_SRC', 'https://cindyjs.org/dist/latest/CindyJS.css');
define('XAPIWRAPPER_SRC', './js/xapiwrapper.min.js');
define('XAPI_SRC', './js/xCindy.js');
define('BASE_IRI', 'http://moodledev.ddns.net/xapi/');

// Constant holding valid "verb"=>"verb IRI" definitions
// TODO: Populate array
define('VALID_VERBS', array(
    'MOVED' => BASE_IRI . 'verb/moved',
));

// Constant holding valid "type"=>"type IRI" definitions (based on "type" in CindyJS geometry)
// TODO: Populate array
// TODO: Even necessary? Currently unused. Would be used for typeID in write_xapi() CindyScript function.
define('VALID_OBJTYPES', array(
    'FREE'      => BASE_IRI . 'objType/Free',
    'SEGMENT'   => BASE_IRI . 'objType/Segment',
));

/**
 * Builds html from CindyJS file to display in view.php.
 * TODO: Build html more flexible. Get id of divtag from Cindy object, also use renderer.
 * 
 * @param instance the course instance with file field that holds a CindyJS obj in JavaScript.
 * @return string CindyJS obj wrapped with div and requuired JavaScript.
 */
function build_cindy_html($instance) : string {
    global $USER;

    $cindy = '';

    // JS xAPI and CindyJS scripts
    $cindy .= '<script type="text/javascript" src=' . XAPIWRAPPER_SRC .'></script>';    // adlnet xapi wrapper
    $cindy .= '<script type="text/javascript" src=' . XAPI_SRC .'></script>';           // custom xapi functions
    $cindy .= '<script type="text/javascript" src=' . CINDYJS_SRC .'></script>';        // CindyJS
    $cindy .= '<script>' . $instance->file . '</script>';                               // CindyJS object from html

    // SAMPLE: Display Cinderella file.
    // TODO: Remove or redo onmouseup (see <ul> in view.php)
    $cindy  .= '<div id="CSCanvas" onmouseup="queryLRS(&quot;{\'mbox\': \'mailto:'. $USER->email .'\'}&quot;,\'http://moodledev.ddns.net/xapi/verb/moved\',\'http://moodledev.ddns.net/xapi/obj/C\');"></div>';

    // Connect to the LRS.
    $cindy .= '<script type="text/javascript">' . 
                    'connect_to_LRS(' . 
                        '"' . $instance->lrs_endpoint . '", ' .
                        '"' . $instance->lrs_user . '", ' .
                        '"' . $instance->lrs_password . '"' .
                    ');' .
                    'console.log("DEBUG: 10");' .
                '</script>';

    // CSINIT: Helper functions.
    // TODO: Find out how to get the object type from mover() and add to objType IRI
    // TODO: How to handle object description (second from bottom). Maybe Cinderella Definition attribute of mover(). How to get in CindyJS?
    $cindy .= '<script id="csinit" type="text/x-cindyscript">' .
                // function to be called from different events.
                'writeXAPI(mov) := (' .
                    'javascript("sendStatement(' . 
                                    '\'' . $USER->username . '\',' .
                                    '\'' . $USER->email . '\',' .
                                    '\'' . 'MOVED'    . '\',' .
                                    '\'' . VALID_VERBS['MOVED'] . '\',' .
                                    '\'"' . ' + mov.name + ' . '"\',' .
                                    '\'' . BASE_IRI . 'obj/' . '" + mov.name + "' . '\',' .
                                    '\'' . BASE_IRI . 'objType/NOTIMPLEMENTED_PROBABLYFREEPOINT' . '\',' .
                                    '\'' . 'DESCRIPTION NOT IMPLEMENTED' . '\',' .
                                    '\'"' . ' + mov + ' . '"\'' .
                                    ');' .
                    '")' .
                ');' .
                '</script>';
                           
    // CSMOUSEDRAG:  Getting mover().
    $cindy .= '<script id="csmousedrag" type="text/x-cindyscript">' . 
                    'mov = mover();' .
                '</script>';
    
    
    // Trigger statement sending on mouseUp.
    // TODO: Only write, when mover().name is one of the points to track (in $instance->xapi_string).
    $cindy .= '<script id="csmouseup" type="text/x-cindyscript">' .
                    'javascript("console.log(" + mov + ")");' .
                    'javascript("console.log(\'" + mov.name + "\')");' .
                    'writeXAPI(mov);' .
                '</script>';
    
    return $cindy;
}

/**
 * Searches html of a Cinderella export for the CindyJS object definition
 * for storing it in the DB.
 * 
 * @param string $rawhtml the exported html from Cinderella
 * @return string the portion of the html that contains the CindyJS object
 */
function find_cindyobj_in_html(string $rawhtml) : string {

    $start = strpos($rawhtml, 'var cdy = CindyJS(');
    $end = strpos($rawhtml, '</script>', $start);
    
    return substr($rawhtml, $start, $end - $start);
}

/**
 * Converts a string containting a comma seperated list of xAPI verbs and objects
 * to an JSON object that holds a VERB => array of objects. Verbs are capitalized.
 * 
 * @param string $rawxapi string containing the raw list
 */
function xapi_string_to_list(string $rawxapi) : string {
    
    // Clean string as a whole
    $rawxapi = preg_replace('/\s+/', ' ', $rawxapi);  // Remove multiple whitespaces.
    $rawxapi = trim($rawxapi);                        // Remove trailing whitespaces.
    $rawxapi = explode(',', $rawxapi);                // Split string at ','.

    $list = array();                                  // Holds verb => object map of xapi statement
    foreach ($rawxapi as $value) {

        // Trim remaining whitespaces from individual verb object pairs.
        $value = trim($value);

        // Check if $value is empty, TODO: Check if verb is valid (see constant VALID_VERBS above)
        if ($value != '') {
            // get Position of space between verb and object and build verb=>object map
            $spacepos    = strpos($value, ' ');
            $verb        = strtoupper(substr($value, 0, $spacepos));
            $obj         = substr($value, $spacepos+1);

            // Check if verb is already in list.
            if (array_key_exists($verb, $list)) {
                // If true, push new object to the end of the corresponding array.
                array_push($list[$verb], $obj);
            } else {
                // If false, create new key $verb.
                $list[$verb] = [$obj,];
            }
        }
    }
    return json_encode($list);
}

/**
 * DEBUG: Dumps stringified version of object, array, or primitive to file.
 * 
 * @param mixed $stuff data to be dumped
 * @param $topath $path of file, default: realpath($_SERVER["DOCUMENT_ROOT"]) . '/vardump.txt';
 */
function debug_dump(mixed $stuff, string $topath = null) {

// Set default for $topath if none specified.
$topath = (is_null($topath)) ? realpath($_SERVER["DOCUMENT_ROOT"]) . '/vardump.txt' : $topath;

// Open file.
$file = fopen($topath, 'w') or die('!!Unable to open file at: ' . $topath = null);

if (is_object($stuff)) {
    // Dump if object or array.
    foreach ($stuff as $key => $value) {
        $txt = $key . ' => ' . print_r($value) . " : " . gettype($value) . "\n";
        fwrite($file, $txt);
    }
}
else {
    // Dump if other.
    $txt = print_r($stuff) . " : " . gettype($stuff) . "\n";
}

fwrite($file, $txt);
fclose($file);
}