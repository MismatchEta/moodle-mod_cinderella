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
 * Moodle hooks for the mod_cinderella plugin.
 *
 * @package   mod_cinderella
 * @copyright 2023 Marcus Röhming <marcus.roehming@outlook.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();    // prevents access from outside Moodle

require_once($CFG->dirroot.'/mod/cinderella/locallib.php');

/**
 * Add an instance of a Cinderella activity.
 * 
 * @param stdClass $instancedata
 * @param mod_assign_mod_form $mform
 * @return int The instance id of the new assignment 
 */
function cinderella_add_instance($instancedata, $mform = null): int {
    global $DB;

    // Prepare data for DB table.
    $rawhtml = $mform->get_file_content('cinderellafile');
    
    $instancedata->timecreated    = time();
    $instancedata->timemodified   = $instancedata->timecreated;
    $instancedata->file           = find_cindyobj_in_html($rawhtml);

    // Add to DB table and return the id.
    return $DB->insert_record('cinderella', $instancedata);
}

/**
 * Update Cinderella instance.
 *
 * @param stdClass $instancedata
 * @param mod_assign_mod_form $mform
 * @return bool true
 */
function cinderella_update_instance($instancedata, $mform): bool {
    global $DB;

    //Prepare data for DB entry.
    $instancedata->timemodified = time();
    $instancedata->id           = $instancedata->instance;
    $instancedata->file         = $mform->get_file_content('cinderellafile');

    // Update DB table
    return $DB->update_record('cinderella', $instancedata);
}

/**
 * Delete Cinderella instance.
 * @param int $id
 * @return bool true
 */
function cinderella_delete_instance($id): bool {
    global $DB;
    // code copied from: https://github.com/alteregodeveloper/readingspeed/blob/master/lib.php

    $record = $DB->get_record('cinderella', array('id'=>$id));
    if (!$record) {
        return false;
    }
    
    $cm = get_coursemodule_from_instance('cinderella', $id);
    \core_completion\api::update_completion_date_event($cm->id, 'cinderella', $id, null);

    // note: all context files are deleted automatically

    $DB->delete_records('cinderella', array('id'=>$record->id));

    return true;
}