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
 * English strings for eduplayer
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod
 * @subpackage eduplayer
 * @copyright  2013 Humanage Srl <info@humanage.it>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['modulename'] = 'Eduplayer';
$string['modulenameplural'] = 'eduplayers';
$string['modulename_help'] = 'The eduplayer module allows to playback .mp4, .flv video files and .mp3, .m4a audio files';
$string['eduplayerfieldset'] = 'Custom example fieldset';
$string['eduplayername'] = 'Eduplayer name';
$string['eduplayername_help'] = 'Type here the name of the activity';
$string['eduplayer'] = 'eduplayer';
$string['pluginadministration'] = 'Eduplayer administration';
$string['pluginname'] = 'eduplayer';
//General
$string['eduplayersource'] = 'Source';
$string['eduplayersource_help'] = 'It is possible to upload video files .mp4, .flv, .webm and mp3, m4a audio file or insert an url to a media file of those extensions';
$string['urltype'] = 'Type';
$string['linkurl'] = 'Link';
$string['eduplayerfile'] = 'File';
$string['eduplayerfile_help'] = 'It is possible to upload .mp4, .flv, .webm video files or mp3, .m4a audio files';
$string['type'] = 'Playback type';
//Playlists
$string['playlists'] = 'Playlist';
$string['eduplayerplaylist'] = 'Playlist';
$string['eduplayerplaylist_help'] = 'Choose where to show the playlist and size in pixels';
$string['playlist'] = 'Position';
$string['playlistsize'] = 'Size (pixels)';
//Behaviour
$string['behaviour'] = 'Behaviour';
$string['eduplayerbehaviour'] = 'Behaviour';
$string['eduplayerbehaviour_help'] = 'Choose how the video is rendered and playback options ( es mute defualt, auto start playback etc )';
$string['autostart'] = 'Auto Start';
$string['stretching'] = 'Stretch the content to the player';
$string['mute'] = 'Mute';
$string['controls'] = 'Controls';
$string['eduplayerrepeat'] = 'Repeat';
//Appearance
$string['appearance'] = 'Appearance';
$string['eduplayerappearance'] = 'Appearance';
$string['eduplayerappearance_help'] = 'Define size and appereance options of the player such as width, height, player skin and poster image';
$string['title'] = 'Title';
$string['width'] = 'Width';
$string['height'] = 'Height';
$string['image'] = 'Poster Image';
$string['image_help'] = 'Poster Image: only file .jpg, .jpeg, .png only';
$string['notes'] = 'Notes';
$string['eduplayerskin'] = 'Skin of the player';
//captions 
$string['captions'] = 'Captions';
$string['eduplayercaptions'] = 'Captions';
$string['eduplayercaptions_help'] = 'Enable captions support';
$string['captionsback'] = 'Transparent Background';
$string['captionsfile'] = 'Captions File';
$string['captionsfile_help'] = 'Captions File: only .vtt, .srt, .xml only';
$string['captionsfontsize'] = 'Font Size';
$string['captionsstate'] = 'Show Captions';
//sharing
$string['share'] = 'Share';
$string['cancel'] = 'Cancel';
$string['sharing'] = 'Sharing';
$string['sharelink'] = 'Link to share';
$string['sharelink_help'] = 'Important: the e-mail sharing fetaures is enabled by filling this field. If the field is empty the link will not be mailed';
$string['sharemessagelabel'] = 'E-mail share message';
$string['sharemailmessage_editor'] = 'E-mail share message';
$string['sharemailmessage_editor_help'] = 'This text wil be send as the e-mail message body with the link in the share url field. Modify this text as you like';
$string['validemail'] = 'Give a valid e-mail address';
$string['sharetext'] = 'Send via e-mail a link to an online copy of this resource';
$string['sharesubbject'] = '{$a->firstname} {$a->lastname} is sharing with you this multimedia resource';
$string['sharemessage'] = '<p>Check this interesting course:<br /></p>';
$string['emailsent'] = 'E-mail sent to {$a}!';
$string['emailnotsent'] = 'Error sending E-mail';
$string['emailnotcorrect'] = 'Invalid E-mail';
$string['downloadfile'] = 'Download media';
$string['download'] = 'Download';
$string['downloadenabled'] = 'Enable download of media';
$string['disclaimer'] = 'Warning to show if the media file is copyright protected';
$string['disclaimerlabel'] = 'Warning to show if the media file is copyright protected';
$string['disclaimer_help'] = 'Important: the warning will be triggered only if this field is not empty';
$string['shareemaillabel'] = 'E-mail address';
//form drop down
$string['URL'] = 'URL';
$string['FILE'] = 'FILE';
$string['true'] = 'YES';
$string['false'] = 'NO';
//eduplayer type
$string['video'] = 'Video';
$string['audio'] = 'Audio';
$string['vplaylist'] = 'Video Playlist';
$string['youtube'] = 'YouTube';
$string['ytplaylist'] = 'Youtube Playlist';
$string['vimeo'] = 'Vimeo';
$string['url'] = 'Full URL';
$string['rtmp'] = 'RTMP Streaming';
//playlist
$string['playlistpositionnull'] = 'None';
$string['playlistpositionbottom'] = 'Bottom';
$string['playlistpositionright'] = 'Right';
//stretching
$string['stretchingnone'] = 'No adjustment';
$string['stretchinguniform'] = 'Fit the player with the content';
$string['stretchingexactfit'] = 'Stretch the content to fit the player';
$string['stretchingfill'] = 'Fill the player without stretching content';
//Access capabilities
$string['eduplayer:addinstance'] = "Add instance";