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

require_once('../../config.php');
require_once($CFG->dirroot . '/mod/kolbtest/lib.php');

global $DB, $PAGE, $OUTPUT;

$id = required_param('id', PARAM_INT);
$course = $DB->get_record('course', ['id' => $id], '*', MUST_EXIST);
require_course_login($course);

$PAGE->set_url('/mod/kolbtest/index.php', ['id' => $id]);
$PAGE->set_pagelayout('incourse');
$PAGE->set_title($course->shortname . ': ' . get_string('modulenameplural', 'mod_kolbtest'));
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();

$modinfo = get_fast_modinfo($course);
$instances = $modinfo->get_instances_of('kolbtest');

if (empty($instances)) {
    echo $OUTPUT->notification(get_string('no_attempts', 'mod_kolbtest'), 'info');
    echo $OUTPUT->footer();
    exit;
}

$firstcmid = null;
foreach ($instances as $cm) {
    if ($firstcmid === null) {
        $firstcmid = $cm->id;
    }
}

if ($firstcmid && has_capability('mod/kolbtest:viewreports', context_module::instance($firstcmid))) {
    $coursereporturl = new moodle_url('/mod/kolbtest/report.php', ['id' => $firstcmid, 'scope' => 'course']);
    $reportallurl = new moodle_url('/mod/kolbtest/reportall.php');
    echo html_writer::div(
        html_writer::link($coursereporturl, get_string('report_all_course', 'mod_kolbtest'), ['class' => 'btn btn-secondary']) . ' ' .
        html_writer::link($reportallurl, get_string('report_full', 'mod_kolbtest'), ['class' => 'btn btn-primary']),
        'mod_kolbtest-index-report'
    );
    echo html_writer::empty_tag('br');
}

$table = new html_table();
$table->head = [get_string('name', 'mod_kolbtest')];
$table->align = ['left'];
$table->data = [];

foreach ($instances as $cm) {
    $url = new moodle_url('/mod/kolbtest/view.php', ['id' => $cm->id]);
    $row = [html_writer::link($url, $cm->get_name())];
    if (has_capability('mod/kolbtest:viewreports', context_module::instance($cm->id))) {
        $reporturl = new moodle_url('/mod/kolbtest/report.php', ['id' => $cm->id]);
        $row[0] .= ' ' . html_writer::link($reporturl, '(' . get_string('view_report', 'mod_kolbtest') . ')', ['class' => 'small']);
    }
    $table->data[] = $row;
}

echo html_writer::table($table);
echo kolbtest_author_credit();
echo $OUTPUT->footer();
