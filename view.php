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

use function PHPSTORM_META\type;

require('../../config.php');

// Get the course instance to display by ID from GET (https://moodledev.io/docs/apis/plugintypes/mod#viewphp---view-an-activity)
$cmid = required_param('id', PARAM_INT);
[$course, $cm] = get_course_and_cm_from_cmid($cmid, 'cinderella');
$instance = $DB->get_record('cinderella', ['id'=> $cm->instance], '*', MUST_EXIST);

// Setup page.
require_login($course, true, $cm);

// Display page.
echo $OUTPUT->header();

echo strtoupper($OUTPUT->heading($instance->name));
echo '<h1>Hello World: </h1>';

echo '<p>cmid:' . var_dump($cmid) . '</p>';
echo str_repeat('-', 100);
echo '<br>';

// echo 'Try $course, $cm*, $instance:';
$var = $cm;

echo get_class($var);
echo '<ul>';
foreach ($var as $key => $value) {
    echo '<li>' . $key . " -> " . $value;
}
echo '</ul>';

echo $OUTPUT->footer();