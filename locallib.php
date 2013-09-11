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
 * Internal library of functions for module eduplayer
 *
 * All the eduplayer specific functions, needed to implement the module
 * logic, should go here. Never include this file from your lib.php!
 *
 * @package    mod
 * @subpackage eduplayer
 * @author     Humanage Srl <info@humanage.it>
 * @copyright  2013 Humanage Srl <info@humanage.it>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
* Define type of media to serve
* @return array
*/
function eduplayer_list_type() {
    return array(
        'video' => get_string('video','eduplayer'),
        'audio' => get_string('audio','eduplayer'),
        'vplaylist' => get_string('vplaylist','eduplayer'),
        'youtube' => get_string('youtube','eduplayer'),
        'ytplaylist' => get_string('ytplaylist','eduplayer'),
        'vimeo' => get_string('vimeo','eduplayer'),
        'url' => get_string('url','eduplayer'),
        'rtmp' => get_string('rtmp','eduplayer')
    );
}

/**
* Define the path to the skin configuration xml file 
* @return array
*/
function eduplayer_list_skins(){
	$skinlist = array('default'=>'default');
	if ($h = opendir( dirname(__FILE__).'/skins')) {
		while (false !== ($e = readdir($h)) ) {
			if( $e != '.' && $e != '..' ){
				if( is_dir( dirname(__FILE__)."/skins/$e" ) ){
					//get the xml file inside skin directory
					$hh = scandir( dirname(__FILE__)."/skins/$e" ) ;	
					$u = array_values(preg_grep("/(.xml)$/i", $hh));
					if( !empty( $u ) ){
						$n = basename($u[0],".xml");
						$skinlist[$e.'/'.$n] = $n;
					}
				}else{
					$n = basename($e,".xml");	
					$skinlist[$n] = $n;
				}
			}
		}
		closedir($h);
	}

	return $skinlist;
}

/*
 * eduplayer helper
 */
function eduplayer_player_helper($eduplayer, $cm, $context) {
    global $CFG, $COURSE, $CFG;
    
    $fs = get_file_storage();
    $videofiles = $fs->get_area_files($context->id, 'mod_eduplayer', 'file', false, '', false);
    $captionsfiles = $fs->get_area_files($context->id, 'mod_eduplayer', 'captionsfile', false, '', false);
    switch($eduplayer->type): 
        case 'video':
            return eduplayer_player($eduplayer, $context, $videofiles, $captionsfiles);
            break;
        case 'audio':
            return eduplayer_player($eduplayer, $context, $videofiles, $captionsfiles);
            break;
        case 'vplaylist':
            return eduplayer_player_playlist($eduplayer, $context, $videofiles, $captionsfiles);
            break;
        case 'youtube':
            return eduplayer_player($eduplayer, $context, $videofiles, $captionsfiles);
            break;
        case 'ytplaylist': 
            return eduplayer_player_playlist($eduplayer, $context, $videofiles, $captionsfiles);
            break;
        case 'vimeo':
            return eduplayer_player_vimeo($eduplayer);
            break;
        case 'url':
            return eduplayer_player($eduplayer, $context, $videofiles, $captionsfiles);
            break;
        case 'rss':
            return eduplayer_player_playlist($eduplayer, $context, $videofiles, $captionsfiles);
            break;
        case 'rtmp':
            return eduplayer_player($eduplayer, $context, $videofiles, $captionsfiles);
            break;
    endswitch; 
    
}

/*
 * Embed video player
 */
function eduplayer_player($eduplayer, $context, $videofiles, $captionsfiles) {
    global $CFG, $COURSE, $CFG;
    
    $videos = array();
    $captions = array();
    $general_options = array();
    $playlist_options = '';
    $caption_settings = '';
    $caption_attribute = '';
    $videourl = '';
    $img_attribute = '';
	$attributes=array('showlogo:'. showlogo() .'');
    
    //Videos
    if ($eduplayer->urltype == 1) {
        foreach($videofiles as $videofile) {
            $videolabel = explode('.', $videofile->get_filename());
            $videourl = moodle_url::make_file_url('/pluginfile.php', '/'.$context->id.'/mod_eduplayer/file/0/'.$videofile->get_filename());
            $videos[] = '{ file: "'.$videourl.'", label: "'.$videolabel[0].'" }'; 
        }
    } else {
        $videofiles = $eduplayer->streamer.$eduplayer->eduplayerfile;
        $videos[] = '{ file: "'.$videofiles.'" }';
    }
        
    //Start image (Does not work for Vimeo)
    if ($eduplayer->image) {
        $imgurl = moodle_url::make_file_url('/pluginfile.php', '/'.$context->id.'/mod_eduplayer/image/0/'.$eduplayer->image);
        $img_attribute = ' image: "'.$imgurl.'", ';
    }
    
    //Captions
    if ($eduplayer->captionsstate != 'false') {
        foreach($captionsfiles as $captionsfile) {
            $captionlabel = explode('.', $captionsfile->get_filename());
            $captionsurl = moodle_url::make_file_url('/pluginfile.php', '/'.$context->id.'/mod_eduplayer/captionsfile/0/'.$captionsfile->get_filename());
            $captions[] = '{ file: "'.$captionsurl.'", label: "'.$captionlabel[0].'" }';
        }

        //Add captions to params
        if (count($captionsfiles) >= 1) {
            $caption_settings .= 'captions: {'.
                                    'back: '.$eduplayer->captionsback.', '.
                                    'fontsize: '.$eduplayer->captionsfontsize.
                                '} ';
            $caption_attribute .= 'captions: ['.
                                    implode(',',$captions).
                                   '], ';                        
        }  
    }
    $playlist_attributes = array('title');
    $general_attributes = array('controls', 'eduplayerrepeat', 'autostart', 'stretching', 'mute', 'width', 'height');
    
    foreach($eduplayer as $key => $value) {
        if (in_array($key, $playlist_attributes)) {
            $playlist_options .= $key.': "'.$value.'", ';
        }
        if (in_array($key, $general_attributes)) {
            if ($key == 'eduplayerrepeat') {
                $key = 'repeat';
            }
            $general_options[] = $key.': "'.$value.'"';
        }
    }
    
    $attributes[] = 'playlist: [{'.
                    $img_attribute.
                    $caption_attribute.
                    $playlist_options.
                    'sources: ['. implode( ',', $videos ) .']'.
                  '}]';    
	if( $caption_settings != '' )
		$attributes[] = $caption_settings;			
	$attributes[] = implode(',', $general_options);	

	if( isset($eduplayer->skinxml ) || $eduplayer->eduplayerskin=='')
		$attributes[] = " skin:'skins/default.xml'";
	else
		$attributes[] = " skin:'skins/".$eduplayer->eduplayerskin.".xml'";
    //Player
    $player = html_writer::tag('div', '..Loading..', array('id' => 'videoElement'));
    //JS
    $jscode = 'jwplayer("videoElement").setup({'.
               implode( ',', $attributes ).
              '});'; 
    $player .= html_writer::script($jscode);
   
    return $player;
}

/* 
 * Embed Playlist player
 */
function eduplayer_player_playlist($eduplayer, $context, $videofiles, $captionsfiles) {
    global $CFG, $COURSE, $CFG, $OUTPUT;
    
    $videos = '';
    $captions = '';
    $general_options = '';
    $playlist_options = '';
    $caption_settings = '';
    $caption_attribute = '';
    $img_attribute = '';
    $videourl = '';
    $default_image = $OUTPUT->pix_url('icon_large', 'eduplayer');

    //Videos
    if ($eduplayer->urltype == 1) {
        foreach($videofiles as $videofile) {
            $videolabel = explode('.', $videofile->get_filename());
            $videourl = moodle_url::make_file_url('/pluginfile.php', '/'.$context->id.'/mod_eduplayer/file/0/'.$videofile->get_filename());
            $videos[] = '{ file: "'.$videourl.'", title: "'.$videolabel[0].'", image: "'.$default_image.'" }'; 
        }
    } else {
        $videofiles = $eduplayer->eduplayerfile;
        $videos[]= '{ file: "'.$videofiles.'" }';
    }

    $general_attributes = array('title', 'controls', 'eduplayerrepeat', 'autostart', 'stretching', 'mute', 'width', 'height');
    
    foreach($eduplayer as $key => $value) {
        if (in_array($key, $general_attributes)) {
            if ($key == 'eduplayerrepeat') {
                $key = 'repeat';
            }
            $general_options .= $key.': "'.$value.'", ';
        }
    }
    
    //Playlist differences
    if ($eduplayer->type == 'ytplaylist') {
        if (stristr($eduplayer->eduplayerfile, 'youtube.com')) {
            $playlistid = explode('list=', $eduplayer->eduplayerfile);
            $playlistid = explode('&', $playlistid[1]);
            $eduplayer->eduplayerfile = $playlistid[0];
        }
        $playlist_options .= 'playlist: "http://gdata.youtube.com/feeds/api/playlists/'.$eduplayer->eduplayerfile.'?alt=rss", ';
    } else if ($eduplayer->type == 'rss') {
        $playlist_options .= 'playlist: "'.$videourl.'", ';
    } else if ($eduplayer->type == 'vplaylist') {
        $playlist_options .= 'playlist: ['. implode(',', $videos ) .'], ';
    }
    $playlist_options .= 'listbar: { position: "'.$eduplayer->playlistposition.'", size: "'.$eduplayer->playlistsize.'" }, ';
    // $playlist_options .= 'primary: "flash", ';
	
    $playlist_options .= "flashplayer:'./jwplayer.flash.swf',";
    $playlist_options .= "primary:'flash',";
    $playlist_options .= "fallback:false";	
	
    $attributes = $general_options;
    $attributes .= $caption_settings;
    $attributes .= $playlist_options;
	if( isset($eduplayer->skinxml ) || $eduplayer->eduplayerskin=='')
		$attributes.= ",skin:'skins/default.xml'";
	else
		$attributes.= ",skin:'skins/".$eduplayer->eduplayerskin.".xml'";
    //Player
    $player = html_writer::tag('div', '..Loading..', array('id' => 'videoElement'));
    //JS
    $jscode = 'jwplayer("videoElement").setup({'.
               $attributes.
              '});'; 
    $player .= html_writer::script($jscode);
    
    return $player;
}

/*
 * Vimeo Player(iframe)
 */
function eduplayer_player_vimeo($eduplayer) {
    global $CFG, $COURSE, $CFG;
    if (stristr($eduplayer->eduplayerfile, 'vimeo.com')) { 
        $videoid = explode('vimeo.com/', $eduplayer->eduplayerfile);
        $eduplayer->eduplayerfile = $videoid[1];
    }
    $content = '<iframe id="videoPlayer" src="https://player.vimeo.com/video/'.$eduplayer->eduplayerfile.'?api=1&player_id=videoPlayer" width="'.$eduplayer->width.'" height="'.$eduplayer->height.'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
    $player = html_writer::tag('div', $content, array('id' => 'videoElement'));
    
    return $player;
}