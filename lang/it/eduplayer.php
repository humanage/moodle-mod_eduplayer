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
$string['modulename_help'] = 'Il modulo Eduplayer permette la riproduzione di file video .mp4, .flv e audio .mp3, .m4a';
$string['eduplayerfieldset'] = 'Esempio campo custom';
$string['eduplayername'] = 'Nome eduplayer';
$string['eduplayername_help'] = 'Inserisci il nome della risorsa';
$string['eduplayer'] = 'eduplayer';
$string['pluginadministration'] = 'Amministrazione Eduplayer';
$string['pluginname'] = 'eduplayer';
//General
$string['eduplayersource'] = 'Sorgente';
$string['eduplayersource_help'] = 'E\' possibile caricare file video del tipo .mp4, .flv, .webm e file audio mp3 e m4a oppure richiamare direttamente una URL';
$string['urltype'] = 'Tipo';
$string['linkurl'] = 'Link';
$string['eduplayerfile'] = 'File';
$string['eduplayerfile_help'] = 'E\' possibile caricare file video del tipo .mp4, .flv, .webm e file audio mp3';
$string['type'] = 'Tipo di riproduzione';
//Playlists
$string['playlists'] = 'Playlist';
$string['eduplayerplaylist'] = 'Playlist';
$string['eduplayerplaylist_help'] = 'E\' possibile indicare la posizione della Playlist e la sua dimensione in pixel';
$string['playlist'] = 'Posizione';
$string['playlistsize'] = 'Dimensione (pixels)';
//Behaviour
$string['behaviour'] = 'Comportamento';
$string['eduplayerbehaviour'] = 'Comportamento';
$string['eduplayerbehaviour_help'] = 'Questa sezione permette di definire la modalità di visualizzazione del video e le modalità di riproduzione (es. riproduzione automatica, muto...)';
$string['autostart'] = 'Riproduzione automatica';
$string['stretching'] = 'Adattamento del contenuto al player';
$string['mute'] = 'Muto';
$string['controls'] = 'Controlli';
$string['eduplayerrepeat'] = 'Ripeti';
//Appearance
$string['appearance'] = 'Aspetto';
$string['eduplayerappearance'] = 'Aspetto';
$string['eduplayerappearance_help'] = 'Questa sezione consente di definire i parametri legati alla dimensione del player, all\'eventuale titolo da far comparire sul pulsante Play centrale, alle skin del player ed alla immagine di copertina';
$string['title'] = 'Titolo';
$string['width'] = 'Larghezza';
$string['height'] = 'Altezza';
$string['image'] = 'Immagine di copertina';
$string['image_help'] = 'Immagine di copertina: solo file .jpg, .jpeg, .png';
$string['notes'] = 'Note';
$string['eduplayerskin'] = 'Skin del player';
//captions 
$string['captions'] = 'Sottotitoli';
$string['eduplayercaptions'] = 'Sottotitoli';
$string['eduplayercaptions_help'] = 'Questa sezione permette di abilitare il supporto ai sottotitoli';
$string['captionsback'] = 'Sfondo trasparente';
$string['captionsfile'] = 'File sottotitoli';
$string['captionsfile_help'] = 'File Sottotitoli: solo file con estensione .vtt, .srt, .xml';
$string['captionsfontsize'] = 'Grandezza dei font';
$string['captionsstate'] = 'Mostra Sottotitoli';
//sharing
$string['share'] = 'Invia';
$string['cancel'] = 'Annulla';
$string['sharing'] = 'Condivisione';
$string['sharelink'] = 'Url da condividere';
$string['sharelink_help'] = 'Importante: la funzionalità di condivisione via email si attiva solo se questo campo viene valorizzato con una URL. Se il campo viene lasciato vuoto, la funzionalità di condivisione non sarà attiva';
$string['sharemessagelabel'] = 'Messaggio e-mail per la condivisione';
$string['sharemailmessage_editor'] = 'Messaggio e-mail per la condivisione';
$string['sharemailmessage_editor_help'] = 'E\' il testo che verrà inviato via email insieme al link inserito nel campo Url da condividere. Il testo di default può essere modificato a proprio piacimento';
$string['validemail'] = 'Inserisci un indirizzo e-mail valido';
$string['sharetext'] = 'Condividi via e-mail un link ad una copia on-line della risorsa';
$string['sharesubbject'] = '{$a->firstname} {$a->lastname} vuole condividere con te questa risorsa multimediale';
$string['sharemessage'] = '<p>Ti segnalo un corso che ti potrebbe interessare:<br /></p>';
$string['emailsent'] = 'E-mail spedita a {$a}!';
$string['emailnotsent'] = 'Errore nell\'invio della e-mail';
$string['emailnotcorrect'] = 'E-mail non valida';
$string['downloadfile'] = 'Scarica il file multimediale';
$string['download'] = 'Scarica';
$string['downloadenabled'] = 'Abilita il download del file multimediale';
$string['disclaimer'] = 'Avviso da mostrare se materiale protetto';
$string['disclaimerlabel'] = 'Avviso da mostrare se materiale protetto';
$string['disclaimer_help'] = 'Importante: l\'avviso viene mostrato solo se questo campo contiene testo. Se lasciato vuoto, la funzionalità non sarà attiva';
$string['shareemaillabel'] = 'Indirizzo email';
//form drop down
$string['URL'] = 'URL';
$string['FILE'] = 'FILE';
$string['true'] = 'SI';
$string['false'] = 'NO';
//eduplayer type
$string['video'] = 'Video';
$string['audio'] = 'Audio';
$string['vplaylist'] = 'Video Playlist';
$string['youtube'] = 'YouTube';
$string['ytplaylist'] = 'Youtube Playlist';
$string['vimeo'] = 'Vimeo';
$string['url'] = 'URL completo';
$string['rtmp'] = 'RTMP Streaming';
//playlist
$string['playlistpositionnull'] = 'Nessuna';
$string['playlistpositionbottom'] = 'In basso';
$string['playlistpositionright'] = 'A destra';
//stretching
$string['stretchingnone'] = 'Nessun adattamento';
$string['stretchinguniform'] = "Ridimensiona uniformemente all'area del player";
$string['stretchingexactfit'] = "Deforma il contenuto per adattarlo a tutta l'area del player";
$string['stretchingfill'] = "Riempi l'intera area del player";
//Access capabilities
$string['eduplayer:addinstance'] = "Aggiungere istanza";
