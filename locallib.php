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

define('CINDYJS_URL', 'https://cindyjs.org/dist/latest/Cindy.js');
define('CINDYCSS_URL', 'https://cindyjs.org/dist/latest/CindyJS.css');

/**
 * Builds html from CindyJS file to display.
 * TODO: Build html more flexible. Get id of divtag from Cindy object, also use renderer.
 * 
 * @param instance the course instance with file field that holds a CindyJS obj in JavaScript.
 * @return string CindyJS obj wrapped with div and requuired JavaScript.
 */
function build_cindy_html($instance) : string {

    // Display Cinderella file.
    $cindy  = '<div id="CSCanvas"></div>';
    $cindy .= '<script type="text/javascript" src=' . CINDYJS_URL .'></script>';
    $cindy .= '<script>' . $instance->file . '</script>';

    //TODO: ADD scripts for xAPI.

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
 * Dumps stringified version of object, array, or primitive to file.
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