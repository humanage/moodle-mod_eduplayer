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

defined('MOODLE_INTERNAL') || die();

/**
 * Define the complete eduplayer structure for backup, with file and id annotations
 */
class backup_eduplayer_activity_structure_step extends backup_activity_structure_step {

    protected function define_structure() {

        // To know if we are including userinfo
        // eduplayer user data is in the xmldata field in DB - anyway lets skip that

        // Define each element separated
        $eduplayer = new backup_nested_element('eduplayer', array('id'), array(
            'name', 'intro', 'introformat', 'timecreated', 'timemodified',
            'urltype', 'eduplayerfile', 'type', 'streamer', 'playlistposition', 
            'playlistsize', 'autostart', 'stretching', 'mute', 'controls', 
            'eduplayerrepeat', 'title', 'width', 'height', 'image', 'notes',
            'notesformat', 'captionsback', 'captionsfile', 'captionsfontsize', 
            'captionsstate'));

        // Build the tree

        // Define sources
        $eduplayer->set_source_table('eduplayer', array('id' => backup::VAR_ACTIVITYID));

        // Define id annotations

        // Define file annotations
        $eduplayer->annotate_files('mod_eduplayer', 'intro', null);
        $eduplayer->annotate_files('mod_eduplayer', 'notes', null);
        $eduplayer->annotate_files('mod_eduplayer', 'file', null);
        $eduplayer->annotate_files('mod_eduplayer', 'captionsfile', null);
        $eduplayer->annotate_files('mod_eduplayer', 'image', null);

        // Return the root element (eduplayer), wrapped into standard activity structure
        return $this->prepare_activity_structure($eduplayer);
    }
}
