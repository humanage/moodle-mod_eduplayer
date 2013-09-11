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
 * The eduplayer share configuration form
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

require_once("$CFG->libdir/formslib.php");

class shareform_form extends moodleform {

    /**
     * Defines forms elements
     */
    public function definition() {
        global $CFG;
 
        $f = $this->_form;
		$f->addElement('html', '<p>'. get_string('sharetext', 'eduplayer') .'</p>');
        $f->addElement('text', 'email', get_string('email'));
        $f->setType('email', PARAM_NOTAGS);
        $f->addRule('email', get_string('missingemail'), 'required', null, 'server');
        $f->addRule('email', get_string('validemail','eduplayer'), 'email', null, 'server');
		
		$f->addElement('submit', 'intro', get_string('share', 'eduplayer') );
    }

	/**
	 * Share the link provided by email
	 *
	 * @global cgf
	 * @global user
	 * @uses SITEID
	 * @param stdClass $eduplay the activity object
	 * @return bool Returns true if mail was sent OK and false if there was an error.
	 */
	public function shareEmailLink( $eduplay ){
		global $CFG;
		global $USER;
		
		$subject=get_string('sharesubbject', 'eduplayer', $USER );
		$from=$USER;
		$to=$this->get_data()->email;
		$messagetext='';
		$messagehtml=$eduplay->sharemailmessage. "<p>". $eduplay->sharelink ."</p>";
	
		if (!empty($CFG->noemailever)) {
			// hidden setting for development sites, set in config.php if needed
			$noemail = 'Not sending email due to noemailever config setting';
			error_log($noemail);
			if (CLI_SCRIPT) {
				mtrace('Error: lib/moodlelib.php email_to_user(): '.$noemail);
			}
			return true;
		}
		
		// Overwrite the receiver
		if (!empty($CFG->divertallemailsto)) {
			$subject = "[DIVERTED {$to}] $subject";
			$to = $CFG->divertallemailsto;
		}

		// we can not send emails to invalid addresses - it might create security issue or confuse the mailer		
		if (!validate_email($to)) {
			$invalidemail = "Email $to is invalid! Not sending.";
			error_log($invalidemail);
			if (CLI_SCRIPT) {
				mtrace('Error: mod/eduplayer/share_form.php shareEmailLink(): '.$invalidemail);
			}
			return false;
		}
		
		$mail = get_mailer();
		$mail->AddAddress( $to );		
		if (!empty($mail->SMTPDebug)) {
			echo '<pre>' . "\n";
		}	

		$tempreplyto = array();
		$supportuser = generate_email_supportuser();
		
		if ($from->maildisplay) {
			$mail->From     = $from->email;
			$mail->FromName = fullname($from);
			$mail->Sender = $from->email;
		} else {
			$mail->From     = $CFG->noreplyaddress;
			$mail->FromName = fullname($from);
			if (empty($replyto)) {
				$tempreplyto[] = array($CFG->noreplyaddress, get_string('noreplyname'));
			}
		}
		
		if (!empty($replyto)) {
			$tempreplyto[] = array($replyto, $replytoname);
		}
		
		$mail->Subject = substr($subject, 0, 900);
		$mail->WordWrap = 79;
		
		if (!empty($from->customheaders)) {	                // Add custom headers
			if (is_array($from->customheaders)) {
				foreach ($from->customheaders as $customheader) {
					$mail->AddCustomHeader($customheader);
				}
			} else {
				$mail->AddCustomHeader($from->customheaders);
			}
		}
		
		if (!empty($from->priority)) {
			$mail->Priority = $from->priority;
		}
		
        $mail->IsHTML(true);
        $mail->Encoding = 'quoted-printable';           // Encoding to use
        $mail->Body    =  $messagehtml;
        $mail->AltBody =  "\n$messagetext\n";
		
		// Check if the email should be sent in an other charset then the default UTF-8
		if ((!empty($CFG->sitemailcharset) || !empty($CFG->allowusermailcharset))) {

			// use the defined site mail charset or eventually the one preferred by the recipient
			$charset = $CFG->sitemailcharset;
			if (!empty($CFG->allowusermailcharset)) {
				if ($useremailcharset = get_user_preferences('mailcharset', '0', $user->id)) {
					$charset = $useremailcharset;
				}
			}

			// convert all the necessary strings if the charset is supported
			$charsets = get_list_of_charsets();
			unset($charsets['UTF-8']);
			if (in_array($charset, $charsets)) {
				$mail->CharSet  = $charset;
				$mail->FromName = textlib::convert($mail->FromName, 'utf-8', strtolower($charset));
				$mail->Subject  = textlib::convert($mail->Subject, 'utf-8', strtolower($charset));
				$mail->Body     = textlib::convert($mail->Body, 'utf-8', strtolower($charset));
				$mail->AltBody  = textlib::convert($mail->AltBody, 'utf-8', strtolower($charset));

				foreach ($tempreplyto as $key => $values) {
					$tempreplyto[$key][1] = textlib::convert($values[1], 'utf-8', strtolower($charset));
				}
			}
		}
		
		foreach ($tempreplyto as $values) {
			$mail->AddReplyTo($values[0], $values[1]);
		}
		
		if ($mail->Send()) {
			set_send_count($USER);
			$mail->IsSMTP();                               // use SMTP directly
			if (!empty($mail->SMTPDebug)) {
				echo '</pre>';
			}
			return true;
		} else {
			add_to_log(SITEID, 'library', 'mailer', qualified_me(), 'ERROR: '. $mail->ErrorInfo);
			if (CLI_SCRIPT) {
				mtrace('Error: mod/eduplayer/share_form.php shareEmailLink(): '.$mail->ErrorInfo);
			}
			if (!empty($mail->SMTPDebug)) {
				echo '</pre>';
			}
			return false;
		}

	}
	
    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
	
	/**
	 * Print o screen a template customized share form
	 */
	function displayCustom(){
		global $USER;
		$html = '
	<form autocomplete="off" action="?id=10" method="post" accept-charset="utf-8" id="mform1" class="mform">
		<div style="display: none;"><input name="sesskey" type="hidden" value="'. $USER->sesskey .'">
			<input name="_qf__shareform_form" type="hidden" value="1">
		</div>
		<p>'. get_string('sharetext','eduplayer') .'</p>
		<label for="id_email">
			'. get_string('shareemaillabel','eduplayer') .'
			<input name="email" type="text" id="id_email" />
		</label>
		<input name="intro" value="'. get_string('share','eduplayer') .'" type="submit" id="id_intro" />
	</form>';
		echo $html;
	}
}