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
 * Activity view page for the plugintype_pluginname plugin.
 *
 * @package   mod_cinderella
 * @copyright 2023 Marcus Röhming <marcus.roehming@outlook.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require_once('locallib.php');

// Get the course instance to display by ID from GET (https://moodledev.io/docs/apis/plugintypes/mod#viewphp---view-an-activity)
$cmid = required_param('id', PARAM_INT);
[$course, $cm] = get_course_and_cm_from_cmid($cmid, 'cinderella');
$instance = $DB->get_record('cinderella', ['id'=> $cm->instance], '*', MUST_EXIST);

// Setup page.
require_login($course, true, $cm);
$PAGE->set_url('/mod/mymodulename/view.php', array('id' => $cm->id));
$PAGE->set_title($instance->name);

// Display page.
echo $OUTPUT->header();

echo build_cindy_html($instance);

// SAMPLE: Output query from the LRS. Just for demonstration.
// TODO: REMOVE (also remove the corresponding onmouseup event in locallib.php on the CSCanvas div)
echo '<h3>Sample Output from LRS query.</h3>';
echo '<p>This displays a list of the users last moves.<br>The most recent on being the first in the list.<br>The list updates on mouseUp on the Cinderella application.</p>';
echo '<ul id="query-results">
        <li>Placeholder li.</li>
        <li>Second placeholder li.</li>
    </ul>';

// DEBUG
echo "<br><br><br><br><h6>Just for reference</h3>";
echo "First name " . $USER->firstname . '<br>';
echo "Last name " . $USER->lastname . '<br>';
echo "Username " . $USER->username . '<br>';
echo "Email " . $USER->email . '<br>';

// debug_dump($USER);

echo $OUTPUT->footer();