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

defined('MOODLE_INTERNAL') || die();

global $CFG;

/**
 * @group local_autounsuspend
 * @group opensourcelearning
 */
class autounsuspendusers_test extends advanced_testcase {
    public function test_positive() {
        global $DB;

        $this->resetAfterTest(true);

        $users = [
            $this->getDataGenerator()->create_user(),
            $this->getDataGenerator()->create_user(),
            $this->getDataGenerator()->create_user(),
        ];

        $task = new \local_autounsuspend\task\autounsuspendusers_task();
        $task->execute();

        set_config('userlist', implode(',', [$users[0]->id, $users[1]->id]), 'local_autounsuspend');

        foreach ($users as $user) {
            $this->assertEquals(0, $DB->get_field('user', 'suspended', ['id' => $user->id]));
        }

        $task->execute();
        foreach ($users as $user) {
            $this->assertEquals(0, $DB->get_field('user', 'suspended', ['id' => $user->id]));
        }

        foreach ($users as $user) {
            $DB->set_field('user', 'suspended', 1, ['id' => $user->id]);
        }

        $task->execute();
        $this->assertEquals(0, $DB->get_field('user', 'suspended', ['id' => $users[0]->id]));
        $this->assertEquals(0, $DB->get_field('user', 'suspended', ['id' => $users[1]->id]));
        $this->assertEquals(1, $DB->get_field('user', 'suspended', ['id' => $users[2]->id]));
    }
}
