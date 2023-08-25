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
 * Languages configuration for the mod_cinderella plugin.
 *
 * @package   mod_cinderella
 * @copyright 2023 Marcus Röhming <marcus.roehming@outlook.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();    // prevents access from outside Moodle

$string["pluginname"] = 'Cinderella';   // see String API: https://docs.moodle.org/dev/String_API
$string['modulename'] = 'Cinderella Activity';   // displayed when activity is added
$string['modulenameplural'] = 'Cinderella Activities';   // displayed when activity is added
$string['modulename_help'] = 'The Cinderella activity module allows a teacher to add a Cinderella file exported as html in their course.';
$string['activitytitle'] = 'Activity Title';

// From mod_form.php.
$string['h_general'] = 'General';
$string['activitydescription'] = 'Description';
$string['cinderellafile'] = 'Cinderella HTML';
$string['cinderellafile_help'] = 'Export your Cinderella File to html and upload it here.';
$string['h_xapi'] = 'LRS and xAPI';
$string['note_xapi'] = 'xAPI Functionality is not fully supported yet';
$string['lrs_endpoint'] = 'LRS Endpoint';
$string['lrs_endpoint_help'] = 'URL to the LRS to connect to.';
$string['lrs_user'] = 'LRS User';
$string['lrs_user_help'] = 'User needs write permissions. Check your LRS settings.';
$string['lrs_password'] = 'LRS Password';
$string['xapi_string'] = 'xAPI Verbs & Objects';
$string['xapi_string_help'] = 'A list of allowed verb-object patterns you want to track seperated by comma. E.g. "moved P, moved Q".
Objects MUST be names of objects in the uploaded cinderella file.
Allowed verbs: moved'; // TODO: Update, also add a link.
