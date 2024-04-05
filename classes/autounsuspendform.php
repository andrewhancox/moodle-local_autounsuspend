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

namespace local_autounsuspend;

use core_user\external\user_summary_exporter;
use moodleform;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir . '/formslib.php');

class autounsuspendform extends moodleform {

    function definition() {
        $mform = $this->_form;

        $options = [
            'ajax' => 'local_autounsuspend/form-user-selector',
            'multiple' => true,
            'valuehtmlcallback' => function ($value) {
                global $OUTPUT, $PAGE;

                if (!$value) {
                    return false;
                }

                $user = \core_user::get_user($value);

                $exporter = new user_summary_exporter($user);
                $useroptiondata = $exporter->export($PAGE->get_renderer('core'));
                return $OUTPUT->render_from_template('local_autounsuspend/form-user-selector-suggestion', $useroptiondata);
            },
        ];
        $mform->addElement('autocomplete', 'userlist', get_string('selectusers', 'local_autounsuspend'), [], $options);
        $mform->addRule('userlist', get_string('requireduserlist', 'local_autounsuspend'), 'required');

        $this->add_action_buttons(false);
    }
}
