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
require_once($CFG->dirroot . '/mod/kolbtest/locallib.php');

global $DB, $PAGE, $OUTPUT;

$id = required_param('id', PARAM_INT);
$scope = optional_param('scope', 'activity', PARAM_ALPHA);
$filterstyle = optional_param('filter_style', '', PARAM_ALPHANUMEXT);
$filteruser = optional_param('filter_user', 0, PARAM_INT);

list($course, $cm) = get_course_and_cm_from_cmid($id, 'kolbtest');
$kolbtest = $DB->get_record('kolbtest', ['id' => $cm->instance], '*', MUST_EXIST);

require_login($course, true, $cm);
$context = context_module::instance($cm->id);
require_capability('mod/kolbtest:viewreports', $context);

$PAGE->set_url('/mod/kolbtest/report.php', ['id' => $id, 'scope' => $scope, 'filter_style' => $filterstyle, 'filter_user' => $filteruser]);
$PAGE->set_title(get_string('report', 'mod_kolbtest') . ' - ' . $kolbtest->name);
$PAGE->set_heading($course->fullname);
$PAGE->add_body_class('mod_kolbtest-report');
$PAGE->requires->css(new moodle_url('/mod/kolbtest/styles.css'));

if ($scope === 'course') {
    $contextcourse = context_course::instance($course->id);
    $PAGE->set_context($contextcourse);
    $kolbtestids = $DB->get_fieldset_sql(
        "SELECT k.id FROM {kolbtest} k WHERE k.course = ?",
        [$course->id]
    );
    if (empty($kolbtestids)) {
        $attempts = [];
    } else {
        list($insql, $inparams) = $DB->get_in_or_equal($kolbtestids, SQL_PARAMS_NAMED);
        $sql = "SELECT a.*, k.name as activityname
                  FROM {kolbtest_attempts} a
                  JOIN {kolbtest} k ON k.id = a.kolbtestid
                  WHERE a.kolbtestid $insql";
        $params = $inparams;
        if ($filterstyle !== '') {
            $sql .= " AND a.style = :style";
            $params['style'] = $filterstyle;
        }
        if ($filteruser > 0) {
            $sql .= " AND a.userid = :userid";
            $params['userid'] = $filteruser;
        }
        $sql .= " ORDER BY a.timecreated DESC";
        $attempts = $DB->get_records_sql($sql, $params);
    }
} else {
    $sql = "SELECT a.* FROM {kolbtest_attempts} a WHERE a.kolbtestid = :kid";
    $params = ['kid' => $kolbtest->id];
    if ($filterstyle !== '') {
        $sql .= " AND a.style = :style";
        $params['style'] = $filterstyle;
    }
    if ($filteruser > 0) {
        $sql .= " AND a.userid = :userid";
        $params['userid'] = $filteruser;
    }
    $sql .= " ORDER BY a.timecreated DESC";
    $attempts = $DB->get_records_sql($sql, $params);
}

$userids = array_unique(array_map(function($a) { return $a->userid; }, $attempts));
$users = [];
if (!empty($userids)) {
    $userfields = 'id, firstname, lastname, firstnamephonetic, lastnamephonetic, middlename, alternatename';
    $userlist = $DB->get_records_list('user', 'id', $userids, '', $userfields);
    foreach ($userlist as $u) {
        $users[$u->id] = fullname($u);
    }
}

$styles = [
    'acomodador' => get_string('style_acomodador', 'mod_kolbtest'),
    'divergente' => get_string('style_divergente', 'mod_kolbtest'),
    'asimilador' => get_string('style_asimilador', 'mod_kolbtest'),
    'convergente' => get_string('style_convergente', 'mod_kolbtest'),
];

$reporturl = new moodle_url('/mod/kolbtest/report.php', ['id' => $id]);
$coursereporturl = new moodle_url('/mod/kolbtest/report.php', ['id' => $id, 'scope' => 'course']);
$reportallurl = new moodle_url('/mod/kolbtest/reportall.php');
$backurl = new moodle_url('/mod/kolbtest/view.php', ['id' => $id]);
$canaccessfull = function_exists('kolbtest_can_access_report_full') && kolbtest_can_access_report_full();

$export = optional_param('export', '', PARAM_ALPHA);
if ($export === 'csv' && !empty($attempts)) {
    $csvheaders = [get_string('student', 'mod_kolbtest'), get_string('style', 'mod_kolbtest'), 'CE', 'RO', 'AC', 'AE', get_string('date', 'mod_kolbtest')];
    if ($scope === 'course') {
        array_unshift($csvheaders, get_string('activity', 'mod_kolbtest'));
    }
    $csvrows = [];
    foreach ($attempts as $a) {
        $row = [
            $users[$a->userid] ?? $a->userid,
            $styles[$a->style] ?? $a->style,
            $a->ce_score,
            $a->ro_score,
            $a->ac_score,
            $a->ae_score,
            userdate($a->timecreated, get_string('strftimedatetime', 'langconfig')),
        ];
        if ($scope === 'course') {
            array_unshift($row, $a->activityname ?? '');
        }
        $csvrows[] = $row;
    }
    $filename = 'kolbtest_report_' . ($scope === 'course' ? 'course_' . $course->id : 'activity_' . $cm->instance) . '.csv';
    kolbtest_send_csv($filename, $csvheaders, $csvrows);
}

echo $OUTPUT->header();

echo html_writer::tag('h2', get_string('report', 'mod_kolbtest') . ': ' . ($scope === 'course' ? get_string('report_course', 'mod_kolbtest') : $kolbtest->name));

echo html_writer::start_tag('div', ['class' => 'mod_kolbtest-report-filters']);
echo html_writer::start_tag('form', ['method' => 'get', 'class' => 'form-inline']);
echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'id', 'value' => $id]);
echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'scope', 'value' => $scope]);
$styleopts = ['' => get_string('all_styles', 'mod_kolbtest')] + $styles;
echo html_writer::label(get_string('filter_style', 'mod_kolbtest'), 'filter_style') . ' ';
echo html_writer::select($styleopts, 'filter_style', $filterstyle, false, ['id' => 'filter_style']);
echo ' ';
$coursecontext = context_course::instance($course->id);
$userfields = 'u.id, u.firstname, u.lastname, u.firstnamephonetic, u.lastnamephonetic, u.middlename, u.alternatename';
    $enrolled = get_enrolled_users($coursecontext, '', 0, $userfields, 'lastname, firstname');
$useropts = [0 => get_string('all_students', 'mod_kolbtest')];
foreach ($enrolled as $u) {
    $useropts[$u->id] = fullname($u);
}
echo html_writer::label(get_string('filter_student', 'mod_kolbtest'), 'filter_user') . ' ';
echo html_writer::select($useropts, 'filter_user', $filteruser, false, ['id' => 'filter_user']);
echo ' ';
echo html_writer::empty_tag('input', ['type' => 'submit', 'value' => get_string('apply_filters', 'mod_kolbtest'), 'class' => 'btn btn-secondary']);
echo html_writer::end_tag('form');
echo html_writer::end_tag('div');

if ($scope === 'activity') {
    echo html_writer::link($coursereporturl, get_string('report_all_course', 'mod_kolbtest'), ['class' => 'btn btn-link']);
} else {
    echo html_writer::link($reporturl, get_string('report_student', 'mod_kolbtest') . ' (esta actividad)', ['class' => 'btn btn-link']);
}
if ($canaccessfull) {
    echo ' ' . html_writer::link($reportallurl, get_string('report_full', 'mod_kolbtest'), ['class' => 'btn btn-primary']);
}
if (!empty($attempts)) {
    $exporturl = new moodle_url('/mod/kolbtest/report.php', ['id' => $id, 'scope' => $scope, 'filter_style' => $filterstyle, 'filter_user' => $filteruser, 'export' => 'csv']);
    echo ' ' . html_writer::link($exporturl, get_string('export_csv', 'mod_kolbtest'), ['class' => 'btn btn-secondary']);
}

if (empty($attempts)) {
    echo html_writer::div(get_string('no_attempts', 'mod_kolbtest'), 'alert alert-info');
} else {
    $table = new html_table();
    $table->head = [get_string('student', 'mod_kolbtest'), get_string('style', 'mod_kolbtest'), 'CE', 'RO', 'AC', 'AE', get_string('date', 'mod_kolbtest')];
    if ($scope === 'course') {
        array_unshift($table->head, get_string('activity', 'mod_kolbtest'));
    }
    $table->data = [];
    foreach ($attempts as $a) {
        $row = [
            $users[$a->userid] ?? $a->userid,
            $styles[$a->style] ?? $a->style,
            $a->ce_score,
            $a->ro_score,
            $a->ac_score,
            $a->ae_score,
            userdate($a->timecreated, get_string('strftimedatetime', 'langconfig')),
        ];
        if ($scope === 'course') {
            array_unshift($row, $a->activityname ?? '');
        }
        $table->data[] = $row;
    }
    echo html_writer::table($table);
}

echo html_writer::link($backurl, get_string('back'), ['class' => 'btn btn-secondary']);
echo html_writer::div(get_string('acknowledgments_text', 'mod_kolbtest'), 'mod_kolbtest-report-ack');
echo kolbtest_author_credit();
echo $OUTPUT->footer();
