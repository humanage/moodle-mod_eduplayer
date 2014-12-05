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
 * Prints a particular instance of eduplayer
 *
 *
 * @package    mod
 * @subpackage eduplayer
 * @author     Humanage Srl <info@humanage.it>
 * @copyright  2013 Humanage Srl <info@humanage.it>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

$id = optional_param('id', 0, PARAM_INT);							
$n  = optional_param('n', 0, PARAM_INT); 
$forceDownload = optional_param('forceDownload', 0, PARAM_INT);
		
if ($id) {
    $cm         = get_coursemodule_from_id('eduplayer', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $eduplayer  = $DB->get_record('eduplayer', array('id' => $cm->instance), '*', MUST_EXIST);
} elseif ($n) {
    $eduplayer  = $DB->get_record('eduplayer', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $eduplayer->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('eduplayer', $eduplayer->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}


require_login($course, true, $cm);
$context = CONTEXT_MODULE::instance($cm->id);

if( $forceDownload == 1 ){
	eduplayer_pluginfile($course, $cm, $context, 'file', array('itemid'=>$id,'filename'=>$eduplayer->eduplayerfile), true, array()) ;
	die();
}	
	
add_to_log($course->id, 'eduplayer', 'view', "view.php?id={$cm->id}", $eduplayer->name, $cm->id);

$completion = new completion_info($course);
$completion->set_module_viewed($cm);

$PAGE->set_url('/mod/eduplayer/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($eduplayer->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);
$PAGE->set_cacheable(true);

if ($eduplayer->sharelink) {
	require_once('./share_form.php');
	$f = new shareform_form('?id='.$id);
	if( isset($_POST['ajax']) && $_POST['ajax'] == 1){
		if ( $f->get_data() ){
			if( $f->shareEmailLink( $eduplayer ) ){
				die( json_encode( array('result'=>'success','message'=>get_string('emailsent','eduplayer',$f->get_data()->email) ) ) );
			}else{
				die( json_encode( array('result'=>'error','message'=>get_string('emailnotsent','eduplayer') ) ) );	
			}
		}else{
				die( json_encode( array('result'=>'error','message'=>get_string('emailnotcorrect','eduplayer') ) ) );	
		}
	}
}

// Output starts here
echo $OUTPUT->header();
echo '<script type="text/javascript" src="./jwplayer/jwplayer.js"></script>';
if ($eduplayer->intro) {
    echo $OUTPUT->box(format_module_intro('eduplayer', $eduplayer, $cm->id), 'generalbox mod_introbox', 'eduplayerintro');
}
?>
	<style>
	.yui3-panel{z-index: 1000 !important}
	.hidden{display:none}
	span#result{margin: 0 0 0 10px}
	span#loading{margin: 0 0 0 10px}
	span#loading img{margin: 0 10px 0 0}
	a.button{ display: block; height: 30px; font-weight: bold; color: #fff !important; width: 142px; margin: 5px 10px 5px 0px; padding: 0; float:left; line-height: 30px; color: #000; text-indent: 18px; }
	a.button:hover{ color: #F86F05 !important; text-decoration: none; }
	a#share.button{ background: url('./pix/share_icon.png') no-repeat center left }
	a#download.button{ background: url('./pix/download_icon.png') no-repeat center left }
	div#sharebox{ clear:both; padding: 5px;}
	</style>
<?php

echo eduplayer_video( $eduplayer );
if ($eduplayer->sharelink) {
	echo '<a id="share" href="#" class="button">'. get_string('share','eduplayer') .'</a>';
}
if( $eduplayer->downloadenabled ){
	echo '<a id="download" href="./view.php?id='.$id.'&forceDownload=1" class="button">'. get_string('download','eduplayer') .'</a>';
	if( $eduplayer->disclaimer ){ 
	?>	
<script type='text/javascript'>	
	YUI().use('event','panel', function (Y) {
		var downloadbutton = Y.one('#download');
		downloadbutton.on('click', function(e) {
			e.preventDefault();
			try{		
				if( downloadpanel.get('visible') )
					return false;
			}catch(err){				
			}
			downloadpanel = new Y.Panel({
				bodyContent : '<?php echo addslashes( str_replace(array("\r\n", "\r", "\n"), '<br />', $eduplayer->disclaimer) ); ?>',
				width   : 400,
				centered: true,
				buttons: [
					{	value: "no",
						label:'<?php echo get_string('cancel','eduplayer'); ?>',
						action: function(i) {
							i.preventDefault();					
							downloadpanel.hide();
							return false;
						},
						section: Y.WidgetStdMod.FOOTER
					},
					{	value: "yes",
						label:'<?php echo get_string('download','eduplayer'); ?>',
						action: function(i) {
							i.preventDefault();
							downloadpanel.hide();
							location.href=downloadbutton.getAttribute('href');				
						},		
						section: Y.WidgetStdMod.FOOTER							
					}
				]
			});

			downloadpanel.render();
		});
	});
</script>
<?php
	}
}
if ( isset( $f ) ) {
	echo '<div id="sharebox" class="hidden">';
	if($f->is_cancelled()) {
		$f->display();
	} else if ( $f->get_data() && $f->shareEmailLink( $eduplayer ) ) {
		echo get_string('emailsent','eduplayer',$f->get_data()->email);
	} else if( $f->get_data() ) {
		echo get_string('emailnotsent','eduplayer');
		$f->display();
	} else {
	  $f->displayCustom();
	}
	echo ('</div>');
	?>
<script type='text/javascript'>	
	function makeajaxemail(){
		YUI().use('transition','io-form','node', function(Y) {
			var cfg = {
				method: 'POST',
				data: {'ajax':'1'},
				form: {id:'mform1'},
			};	
			function complete(transactionid, response, arguments) {
				var r = JSON.parse(response.responseText);				
				Y.one('span#loading').replace('<span id="result" class="'+ r.result +'">'+ r.message +'</span>');
				setTimeout(function() {
					Y.one('span#result').hide(true);
				}, 5000);
			}
			function start(transactionid, arguments) {
				if( Y.one('#loading') )
					Y.one('#loading').remove();
				if( Y.one('#result') )
					Y.one('#result').remove();
				Y.one('input[type=submit]').insert('<span id="loading"><img src="./pix/ajax-loader.gif" />loading ....</span>', 'after');
			}
			Y.on('io:start', start, Y, ['lorem', 'ipsum']);
			Y.on('io:complete', complete, Y, ['lorem', 'ipsum']);
			var request = Y.io(window.location.href, cfg);
		});
	}
	YUI().use('node','event','panel', function (Y) {
		Y.one('a#share').on('click', function(e) {
			e.preventDefault();
			Y.one('#sharebox').toggleClass('hidden');
		});

		var shareform = Y.one('#mform1');
		shareform.on('submit', function(e) {
			e.preventDefault();
			if( Y.one('input[name=email]').get('value') == ''){
				Y.one('input[name=email]').focus();
				return false;
			}
<?php if( $eduplayer->disclaimer ){ ?>			
			try{		
				if( sharepanel.get('visible') )
					return false;
			}catch(err){
			}
			sharepanel = new Y.Panel({
				bodyContent : '<?php echo addslashes( str_replace(array("\r\n", "\r", "\n"), '<br />', $eduplayer->disclaimer) ); ?>',
				width   : 400,
				centered: true,
				buttons: [
					{	value: "no",
						label:'<?php echo get_string('cancel','eduplayer'); ?>',
						action: function(i) {
							i.preventDefault();					
							sharepanel.hide();							
						},
						section: Y.WidgetStdMod.FOOTER
					},
					{	value: "yes",
						label:'<?php echo get_string('share','eduplayer') ?>',
						action: function(i) {
							i.preventDefault();
							sharepanel.hide();
							makeajaxemail();							
						},		
						section: Y.WidgetStdMod.FOOTER							
					}
				]
			});

			sharepanel.render();
<?php }else{?>
			makeajaxemail();
<?php } ?>				
		});

	});
</script>
<?php	

}
echo $OUTPUT->footer();
