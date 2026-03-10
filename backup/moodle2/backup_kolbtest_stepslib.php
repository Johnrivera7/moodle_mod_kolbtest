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
 * Backup structure step for mod_kolbtest.
 *
 * @package   mod_kolbtest
 * @copyright 2025
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class backup_kolbtest_activity_structure_step extends backup_activity_structure_step {

    /**
     * Define structure.
     *
     * @return backup_nested_element
     */
    protected function define_structure() {
        $userinfo = $this->get_setting_value('userinfo');
        $kolbtest = new backup_nested_element('kolbtest', ['id'], ['course', 'name', 'intro', 'introformat', 'timecreated', 'timemodified']);
        $attempts = new backup_nested_element('attempts');
        $attempt = new backup_nested_element('attempt', ['id'], ['userid', 'timecreated', 'ce_score', 'ro_score', 'ac_score', 'ae_score', 'style']);
        $kolbtest->add_child($attempts);
        $attempts->add_child($attempt);
        $kolbtest->set_source_table('kolbtest', ['id' => backup::VAR_ACTIVITYID]);
        if ($userinfo) {
            $attempt->set_source_table('kolbtest_attempts', ['kolbtestid' => backup::VAR_PARENTID]);
        }
        $attempt->annotate_ids('user', 'userid');
        return $this->prepare_activity_structure($kolbtest);
    }
}
