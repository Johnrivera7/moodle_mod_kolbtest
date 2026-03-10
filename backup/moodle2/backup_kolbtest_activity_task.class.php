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

require_once($CFG->dirroot . '/mod/kolbtest/backup/moodle2/backup_kolbtest_stepslib.php');

/**
 * Backup task for mod_kolbtest.
 *
 * @package   mod_kolbtest
 * @copyright 2025
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class backup_kolbtest_activity_task extends backup_activity_task {

    /**
     * Define settings.
     */
    protected function define_my_settings() {
    }

    /**
     * Define steps.
     */
    protected function define_my_steps() {
        $this->add_step(new backup_kolbtest_activity_structure_step('kolbtest_structure', 'kolbtest.xml'));
    }

    /**
     * Encode content links.
     *
     * @param string $content
     * @return string
     */
    public static function encode_content_links($content) {
        return $content;
    }
}
