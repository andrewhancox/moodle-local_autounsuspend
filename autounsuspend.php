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
 * @package    local_autounsuspend
 * @copyright  2024 onwards Andrew Hancox <andrewdchancox@googlemail.com> (https://www.opensourcelearning.co.uk/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_autounsuspend\autounsuspendform;

require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');

admin_externalpage_setup('autounsuspend');

$PAGE->set_url('/local/autounsuspend/autounsuspend.php');

$form = new autounsuspendform();

if (($data = $form->get_data())) {
    set_config('userlist', implode(',', $data->userlist), 'local_autounsuspend');
}

$form->set_data(['userlist' => explode(',', get_config('local_autounsuspend', 'userlist'))]);

echo $OUTPUT->header();
$OUTPUT->heading(get_string('autounsuspend', 'local_autounsuspend'));
echo $form->render();

echo $OUTPUT->footer();
