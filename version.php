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
 * Version metadata for the mod_cinderella plugin.
 *
 * @package   mod_cinderella
 * @copyright 2023 Marcus Röhming <marcus.roehming@outlook.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();    // prevents access from outside Moodle

$plugin->version   = 2023082004;        // (required) The current module version (Date: YYYYMMDDXX).
$plugin->requires  = 2023041800;        // (required) Required Moodle-Version
$plugin->release   = 'alpha1';          // Release String
$plugin->component = 'mod_cinderella';  // Full name of the plugin (used for diagnostics)
$plugin->maturity  = MATURITY_ALPHA;
$plugin->dependencies = array();        // contains list of dependencies
$plugin->cron      = 0;