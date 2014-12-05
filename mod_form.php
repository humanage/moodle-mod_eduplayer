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
 * The main eduplayer configuration form
 *
 * It uses the standard core Moodle formslib. For more info about them, please
 * visit: http://docs.moodle.org/en/Development:lib/formslib.php
 * 
 * @package    mod
 * @subpackage eduplayer
 * @author     Humanage Srl <info@humanage.it>
 * @copyright  2013 Humanage Srl <info@humanage.it>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');

/**
 * Module instance settings form
 */
class mod_eduplayer_mod_form extends moodleform_mod {

    /**
     * Defines forms elements
     */
    public function definition() {
        global $CFG;

        $mform = $this->_form;

        //	-------------------------------------------------------------------------------
        // Adding the "general" fieldset, where all the common settings are showed
        $mform->addElement('header', 'general', get_string('general', 'form'));
        // Adding the standard "name" field
        $mform->addElement('text', 'name', get_string('eduplayername', 'eduplayer'), array('size'=>'64'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEAN);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('name', 'eduplayername', 'eduplayer');

        // Adding the standard "intro" and "introformat" fields
        $this->add_intro_editor();

		//	--------------------------------------- MEDIA SOURCE ----------------------------------------
        $mform->addElement('header', 'eduplayersource', get_string('eduplayersource', 'eduplayer'));
        
        $mform->addHelpButton('eduplayersource', 'eduplayersource', 'eduplayer');        
        $mform->addElement('select', 'urltype', get_string('urltype', 'eduplayer'), array(0 => get_string('URL', 'eduplayer'), 1 => get_string('FILE','eduplayer')));
        
        $mform->addElement('text', 'linkurl', get_string('linkurl', 'eduplayer'), array('size' => '47')); 
        $mform->setDefault('linkurl', 'http://');
        $mform->setType('linkurl', PARAM_URL);
        // Disable my control if a checkbox is checked.
        $mform->disabledIf('linkurl', 'urltype', 'eq', 1);
        
        // eduplayerfile
        $mform->addElement('filemanager', 'file', get_string('eduplayerfile', 'eduplayer'), null, array('subdirs' => 0, 'accepted_types' => array_merge(eduplayer_video_extensions(),eduplayer_audio_extensions())));
        $mform->addHelpButton('file', 'eduplayerfile', 'eduplayer');
        $mform->disabledIf('file', 'urltype', 'eq', 0);

        // type
        $mform->addElement('select', 'type', get_string('type', 'eduplayer'), eduplayer_list_type());
        $mform->setDefault('type', 'video');
        
		//	--------------------------------------- playlists ---------------------------------------
        $mform->addElement('header', 'playlists', get_string('playlists', 'eduplayer'));
        $mform->addHelpButton('playlists', 'eduplayerplaylist', 'eduplayer');
        // playlist
        $mform->addElement('select', 'playlistposition', get_string('playlist', 'eduplayer'), array('bottom' => get_string('playlistpositionbottom','eduplayer'), 'right' => get_string('playlistpositionright','eduplayer'), 'none' => get_string('playlistpositionnull','eduplayer')));
        $mform->setDefault('playlistposition', 'none');
        // playlistsize
        $mform->addElement('text', 'playlistsize', get_string('playlistsize', 'eduplayer'), array('size' => '6'));
        $mform->setDefault('playlistsize', '260');
        $mform->setType('playlistsize', PARAM_INT);

		//	--------------------------------------- BEHAVIOUR ---------------------------------------
        $mform->addElement('header', 'behaviour', get_string('behaviour', 'eduplayer'));
        $mform->addHelpButton('behaviour', 'eduplayerbehaviour', 'eduplayer');
        // autostart 
        $mform->addElement('select', 'autostart', get_string('autostart', 'eduplayer'), array('true' => get_string('true','eduplayer'), 'false' => get_string('false','eduplayer')));
        $mform->setDefault('autostart', 'false');
        // stretching 
        $mform->addElement('select', 'stretching', get_string('stretching', 'eduplayer'), array('none' => get_string('stretchingnone','eduplayer'), 'uniform' => get_string('stretchinguniform','eduplayer'), 'exactfit' => get_string('stretchingexactfit','eduplayer'), 'fill' => get_string('stretchingfill','eduplayer')));
        $mform->setDefault('stretching', 'uniform');
        $mform->setAdvanced('stretching');
        // mute 
        $mform->addElement('select', 'mute', get_string('mute', 'eduplayer'), array('true' => get_string('true','eduplayer'), 'false' => get_string('false','eduplayer')));
        $mform->setDefault('mute', 'false');
        $mform->setAdvanced('mute');
        //controls
        $mform->addElement('select', 'controls', get_string('controls', 'eduplayer'), array('true' => get_string('true','eduplayer'), 'false' => get_string('false','eduplayer')));
        $mform->setDefault('controls', 'true');
        $mform->setAdvanced('controls');
        //repeat
        $mform->addElement('select', 'eduplayerrepeat', get_string('eduplayerrepeat', 'eduplayer'), array('true' => get_string('true','eduplayer'), 'false' => get_string('false','eduplayer')));
        $mform->setDefault('eduplayerrepeat', 'false');
        $mform->setAdvanced('repeat');
        
		//	--------------------------------------- APPEARANCE ---------------------------------------
        $mform->addElement('header', 'appearance', get_string('appearance', 'eduplayer'));
        $mform->addHelpButton('appearance', 'eduplayerappearance', 'eduplayer');
        // title
        $mform->addElement('text', 'title', get_string('title', 'eduplayer'), array('size' => '80'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('title', PARAM_TEXT);
        } else {
            $mform->setType('title', PARAM_CLEAN);
        }
        // Player Width
        $mform->addElement('text', 'width', get_string('width', 'eduplayer'), array('size' => '6'));
        $mform->setDefault('width', '100%');
        $mform->setType('width', PARAM_TEXT);
        // Player Height
		$mform->addElement('text', 'height', get_string('height', 'eduplayer'), array('size' => '6'));
		$mform->setDefault('height', '480'); 
        $mform->setType('height', PARAM_TEXT);        
		// Player Skin
		$mform->addElement('select', 'eduplayerskin', get_string('eduplayerskin', 'eduplayer'), eduplayer_list_skins() );      
		
        // image
        $mform->addElement('filemanager', 'image', get_string('image', 'eduplayer'), null, array('subdirs' => 0, 'maxfiles' => 1, 'accepted_types' => eduplayer_image_extensions(), 'mainfile' => true ));
        $mform->addHelpButton('image', 'image', 'eduplayer');
        $mform->setAdvanced('image');
        $mform->disabledIf('image', 'type', 'eq', 'ytplaylist');
		
        //notes
        $mform->addElement('editor', 'notes_editor', get_string('notes', 'eduplayer'), null, array('trusttext'=>true, 'subdirs'=>true, 'maxfiles'=>3));
        $mform->setType('notes_editor', PARAM_RAW);
        $mform->setAdvanced('notes_editor');
        
		//	--------------------------------------- captions ---------------------------------------
        $mform->addElement('header', 'captions', get_string('captions', 'eduplayer'));
        $mform->addHelpButton('captions', 'eduplayercaptions', 'eduplayer');
        // captionsback
        // $mform->addElement('select', 'captionsback', get_string('captionsback', 'eduplayer'), array('true' => 'true', 'false' => 'false'));
        $mform->addElement('select', 'captionsback', get_string('captionsback', 'eduplayer'), array('true' => get_string('false','eduplayer'), 'false' => get_string('true','eduplayer')));
        $mform->setDefault('captionsback', 'false');
        // captionsfile
        $mform->addElement('filemanager', 'captionsfile', get_string('captionsfile', 'eduplayer'), null, array('subdirs' => 0, 'accepted_types' => eduplayer_captions_extensions()));
        $mform->addHelpButton('captionsfile', 'captionsfile', 'eduplayer');
        $mform->setAdvanced('captionsfile');
        $mform->disabledIf('captionsfile', 'type', 'eq', 'ytplaylist');
        // captionsfontsize
        $mform->addElement('text', 'captionsfontsize', get_string('captionsfontsize', 'eduplayer'), array('size' => 6));
        $mform->setType('captionsfontsize', PARAM_INT);
        $mform->setDefault('captionsfontsize', '14');
        // captionsstate
        $mform->addElement('select', 'captionsstate', get_string('captionsstate', 'eduplayer'), array('true' => get_string('true','eduplayer'), 'false' => get_string('false','eduplayer')));
        $mform->setDefault('captionsstate', 'false');
        $mform->setAdvanced('captions');

		//	---------------------------------- share ------------------------------------------
        $mform->addElement('header', 'sharing', get_string('sharing', 'eduplayer'));		
        $mform->addElement('text', 'sharelink', get_string('sharelink', 'eduplayer'), array('size' => '80') );
        $mform->setType('sharelink', PARAM_TEXT);
        $mform->addHelpButton('sharelink', 'sharelink', 'eduplayer');

		$mform->addElement('editor', 'sharemailmessage_editor', get_string('sharemessagelabel', 'eduplayer'), null, array('trusttext'=>true, 'subdirs'=>true, 'maxfiles'=>3) );
       
        $mform->setType('sharemailmessage_editor', PARAM_RAW);
		$mform->setAdvanced('sharemailmessage_editor');
		$mform->addHelpButton('sharemailmessage_editor', 'sharemailmessage_editor', 'eduplayer');
		
        $mform->addElement('checkbox', 'downloadenabled', get_string('downloadenabled', 'eduplayer') );
		
		$mform->addElement('textarea', 'disclaimer', get_string('disclaimerlabel', 'eduplayer'),array('cols' => '80','rows'=>'7'));
		$mform->addHelpButton('disclaimer', 'disclaimer', 'eduplayer');
		
        //-	------------------------------------------------------------------------------
        // add standard elements, common to all modules
        $this->standard_coursemodule_elements();
        //	-------------------------------------------------------------------------------
        // add standard buttons, common to all modules
        $this->add_action_buttons();
    }
        
    function data_preprocessing(&$default_values) {
       // var_dump( $this->current->type );
        global $CFG;

        if ($this->current->instance) {
            if ($this->current->urltype == 0) {
                $default_values['linkurl'] = $this->current->eduplayerfile;
            }
            //media file
            $draftitemid = file_get_submitted_draft_itemid('file');
            file_prepare_draft_area($draftitemid, $this->context->id, 'mod_eduplayer', 'file', 0, array('subdirs'=>0));
            $default_values['file'] = $draftitemid;
            //image file
            $draftitemid = file_get_submitted_draft_itemid('image');
            file_prepare_draft_area($draftitemid, $this->context->id, 'mod_eduplayer', 'image', 0, array('subdirs'=>0));
            $default_values['image'] = $draftitemid;
            //notes
            $notes_data = file_prepare_standard_editor($this->current, 'notes', array('trusttext'=>true, 'subdirs'=>0, 'maxfiles'=>3, 'maxbytes'=>$CFG->maxbytes, 'context' => $this->context), $this->context, 'mod_eduplayer', 'notes', 0);
            $default_values['notes_editor'] = $notes_data->notes_editor;
            //captions file
            $draftitemid = file_get_submitted_draft_itemid('captionsfile');
            file_prepare_draft_area($draftitemid, $this->context->id, 'mod_eduplayer', 'captionsfile', 0, array('subdirs'=>0));
            $default_values['captionsfile'] = $draftitemid;
			//sharemailmessage
			$sharemailmessage_data=file_prepare_standard_editor($this->current, 'sharemailmessage', array('trusttext'=>true, 'subdirs'=>0, 'maxfiles'=>3, 'maxbytes'=>$CFG->maxbytes, 'context' => $this->context), $this->context, 'mod_eduplayer', 'sharemailmessage', 0);
			$default_values['sharemailmessage_editor'] = $sharemailmessage_data->sharemailmessage_editor;
			
        }
			if( !isset( $default_values['sharemailmessage_editor'] ) || is_null($default_values['sharemailmessage_editor']['text']) || $default_values['sharemailmessage_editor']['text'] == ''  ){
				$default_values['sharemailmessage_editor']['text'] = get_string('sharemessage', 'eduplayer');
			}		
    }
    
}
