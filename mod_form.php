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
 * Activity creation/editing form for the mod_cinderella plugin.
 *
 * @package   mod_cinderella
 * @copyright 2023 Marcus Röhming  <marcus.roehming@outlook.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

### see https://moodledev.io/docs/apis/plugintypes/mod#mod_formphp---instance-createedit-form
### and for Module Add-Forms: https://moodledev.io/docs/apis/subsystems/form/usage#use-in-activity-modules-add--update-forms
require_once($CFG->dirroot.'/course/moodleform_mod.php');
require_once($CFG->dirroot.'/mod/cinderella/lib.php');

class mod_cinderella_mod_form extends moodleform_mod {

    function definition() {
        global $CFG;

        $mform = $this->_form;

        // General Header.
        $this->make_general($mform);

        // xAPI Header.
        $this->make_xAPI($mform);

        // Standard Elements.
        $this->standard_coursemodule_elements(); // from moodleform_mod, MUST be called
        $this->add_action_buttons();
    }

    /** 
     * Helper for definition() - Generates General Section
     * 
     * @param object $mform form object to be altered
     */
    function make_general(&$mform) {
        global $CFG;

        // General Header.
        $mform->addElement('header', 'h_general', get_string('h_general', 'cinderella'));
        
        // The title of the activity.
        $mform->addElement(
            'text',
            'name',
            get_string('activitytitle', 'cinderella'),
            ['size'=>'100%']
        );
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');
        
        // The description for the activity.
        $this->standard_intro_elements();
                
        // Filemanager to upload the Cinderella file to be displayed.
        $mform->addElement(
            'filepicker',
            'cinderellafile',
            get_string('cinderellafile', 'cinderella'),
            null,
            [
                'maxbytes' => 10485760, // 10 MByte.
                'accepted_types' => ['.html', '.htm'],
            ]
        );
        $mform->addRule('cinderellafile', null, 'required', null, 'client');
        $mform->addHelpButton('cinderellafile','cinderellafile', 'cinderella');
    }

    /** 
     * Helper for definition() - Generates xAPI Section
     * 
     * @param object &$mform pointer to form object to be altered
     */
    function make_xAPI(&$mform) {
        
        // xAPI Header.
        $mform->addElement('header', 'h_xapi', get_string('h_xapi', 'cinderella'));

        // DEV NOTICE
        $mform->addElement('static', 'note_xapi', '*', get_string('note_xapi', 'cinderella'));

        // LRS Settings.
        $mform->addElement(
            'text', 
            'lrs_endpoint', 
            get_string('lrs_endpoint', 'cinderella'), 
            ['size'=>'100%']
        );
        $mform->setType('lrs_endpoint', PARAM_URL);
        $mform->addHelpButton('lrs_endpoint','lrs_endpoint', 'cinderella');

        $mform->addElement(
            'text', 
            'lrs_user', 
            get_string('lrs_user', 'cinderella'), 
            ['size'=>'100%']
        );
        $mform->setType('lrs_user', PARAM_TEXT);
        $mform->addHelpButton('lrs_user','lrs_user', 'cinderella');

        $mform->addElement(
            'passwordunmask', 
            'lrs_password', 
            get_string('lrs_password', 'cinderella')
        );

        // xAPI Tracking Settings.
        $mform->addElement(
            'text', 
            'xapi_string', 
            get_string('xapi_string', 'cinderella'),
            ['size'=>'100%']
        );
        $mform->setType('xapi_string', PARAM_TEXT);
        $mform->addHelpButton('xapi_string','xapi_string', 'cinderella');
    }
 }