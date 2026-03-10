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

defined('MOODLE_INTERNAL') || die();

/**
 * Restore structure step for mod_kolbtest.
 *
 * @package   mod_kolbtest
 * @copyright 2025
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_kolbtest_activity_structure_step extends restore_activity_structure_step {

    /**
     * Define structure.
     *
     * @return array
     */
    protected function define_structure() {
        $paths = [];
        $paths[] = new restore_path_element('kolbtest', '/activity/kolbtest');
        $paths[] = new restore_path_element('kolbtest_attempt', '/activity/kolbtest/attempts/attempt');
        return $this->prepare_activity_structure($paths);
    }

    /**
     * Process kolbtest.
     *
     * @param object $data
     */
    protected function process_kolbtest($data) {
        global $DB;
        $data = (object)$data;
        $data->course = $this->get_courseid();
        $newid = $DB->insert_record('kolbtest', $data);
        $this->apply_activity_instance($newid);
    }

    /**
     * Process attempt.
     *
     * @param object $data
     */
    protected function process_kolbtest_attempt($data) {
        global $DB;
        $data = (object)$data;
        $data->kolbtestid = $this->get_new_parentid('kolbtest');
        $data->userid = $this->get_mappingid('user', $data->userid);
        $DB->insert_record('kolbtest_attempts', $data);
    }

    /**
     * After execute.
     */
    protected function after_execute() {
    }
}
