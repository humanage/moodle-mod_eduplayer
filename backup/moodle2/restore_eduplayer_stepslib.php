<?php
// This file is part of eduplayer module for Moodle - http://moodle.org/
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
 * @package    mod
 * @subpackage eduplayer
 * @author     Humanage Srl <info@humanage.it>
 * @copyright  2013 Humanage Srl <info@humanage.it>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Structure step to restore one eduplayer activity
 */
class restore_eduplayer_activity_structure_step extends restore_activity_structure_step {

    protected function define_structure() {

        $paths = array();
        $paths[] = new restore_path_element('eduplayer', '/activity/eduplayer');

        // Return the paths wrapped into standard activity structure
        return $this->prepare_activity_structure($paths);
    }

    protected function process_eduplayer($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();
        $data->timecreated = $this->apply_date_offset($data->timecreated);
        $data->timemodified = $this->apply_date_offset($data->timemodified);

        // insert the eduplayer record
        $newitemid = $DB->insert_record('eduplayer', $data);
        // immediately after inserting "activity" record, call this
        $this->apply_activity_instance($newitemid);
    }

    protected function after_execute() {
        // Add eduplayer related files, no need to match by itemname (just internally handled context)
        $this->add_related_files('mod_eduplayer', 'intro', null);
        $this->add_related_files('mod_eduplayer', 'notes', null);
        $this->add_related_files('mod_eduplayer', 'file', null);
        $this->add_related_files('mod_eduplayer', 'captionsfile', null);
        $this->add_related_files('mod_eduplayer', 'image', null);
    }
}
