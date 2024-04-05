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

namespace local_autounsuspend\task;

use core\task\adhoc_task;
use core\task\scheduled_task;
use local_autounsuspend\autounsuspend;

defined('MOODLE_INTERNAL') || die();

class autounsuspendusers_task extends scheduled_task {

    /**
     * Run the task.
     */
    public function execute() {
        global $CFG;
        require_once("$CFG->dirroot/user/lib.php");

        foreach (explode(',', get_config('local_autounsuspend', 'userlist')) as $userid) {
            if (empty($userid)) {
                continue;
            }

            $user = \core_user::get_user($userid);
            $user->suspended = 0;
            user_update_user($user, false);
        }
    }

    public function get_name() {
        return get_string('autounsuspend', 'local_autounsuspend');
    }
}
