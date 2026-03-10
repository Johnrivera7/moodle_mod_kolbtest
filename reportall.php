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

global $DB, $PAGE, $OUTPUT, $USER;

$filtercourse = optional_param('filter_course', 0, PARAM_INT);
$filterstyle = optional_param('filter_style', '', PARAM_ALPHANUMEXT);
$filteruser = optional_param('filter_user', 0, PARAM_INT);

require_login();

// Obtener todos los kolbtest en los que el usuario tiene permiso de ver reportes.
$allkolbtests = $DB->get_records('kolbtest', [], 'course, name', 'id, course, name');
$allowedkolbtestids = [];
foreach ($allkolbtests as $k) {
    $cm = get_coursemodule_from_instance('kolbtest', $k->id, $k->course, false, IGNORE_MISSING);
    if ($cm && has_capability('mod/kolbtest:viewreports', context_module::instance($cm->id))) {
        $allowedkolbtestids[] = $k->id;
    }
}

$PAGE->set_context(context_system::instance());
$PAGE->set_url('/mod/kolbtest/reportall.php', [
    'filter_course' => $filtercourse,
    'filter_style' => $filterstyle,
    'filter_user' => $filteruser,
]);
$PAGE->set_title(get_string('report_full', 'mod_kolbtest'));
$PAGE->set_heading(get_string('report_full', 'mod_kolbtest'));
$PAGE->add_body_class('mod_kolbtest-report');
$PAGE->requires->css(new moodle_url('/mod/kolbtest/styles.css'));

if (empty($allowedkolbtestids)) {
    echo $OUTPUT->header();
    echo $OUTPUT->notification(get_string('no_attempts', 'mod_kolbtest'), 'info');
    echo $OUTPUT->footer();
    exit;
}

list($insql, $inparams) = $DB->get_in_or_equal($allowedkolbtestids, SQL_PARAMS_NAMED);
$sql = "SELECT a.*, k.name as activityname, k.course as courseid
          FROM {kolbtest_attempts} a
          JOIN {kolbtest} k ON k.id = a.kolbtestid
          WHERE a.kolbtestid $insql";
$params = $inparams;
if ($filtercourse > 0) {
    $sql .= " AND k.course = :courseid";
    $params['courseid'] = $filtercourse;
}
if ($filterstyle !== '') {
    $sql .= " AND a.style = :style";
    $params['style'] = $filterstyle;
}
if ($filteruser > 0) {
    $sql .= " AND a.userid = :userid";
    $params['userid'] = $filteruser;
}
$sql .= " ORDER BY k.course, k.name, a.timecreated DESC";
$attempts = $DB->get_records_sql($sql, $params);

$userids = array_unique(array_map(function($a) { return $a->userid; }, $attempts));
$users = [];
if (!empty($userids)) {
    $userfields = 'id, firstname, lastname, firstnamephonetic, lastnamephonetic, middlename, alternatename';
    $userlist = $DB->get_records_list('user', 'id', $userids, '', $userfields);
    foreach ($userlist as $u) {
        $users[$u->id] = fullname($u);
    }
}

$courseids = array_unique(array_map(function($a) { return $a->courseid; }, $attempts));
$courses = [];
if (!empty($courseids)) {
    $courselist = $DB->get_records_list('course', 'id', $courseids, 'fullname', 'id, fullname');
    foreach ($courselist as $c) {
        $courses[$c->id] = $c->fullname;
    }
}

$styles = [
    'acomodador' => get_string('style_acomodador', 'mod_kolbtest'),
    'divergente' => get_string('style_divergente', 'mod_kolbtest'),
    'asimilador' => get_string('style_asimilador', 'mod_kolbtest'),
    'convergente' => get_string('style_convergente', 'mod_kolbtest'),
];

$allowedcourses = [];
foreach ($allowedkolbtestids as $kid) {
    $k = $DB->get_record('kolbtest', ['id' => $kid], 'course');
    if ($k && !isset($allowedcourses[$k->course])) {
        $c = $DB->get_record('course', ['id' => $k->course], 'id, fullname');
        if ($c) {
            $allowedcourses[$c->id] = $c->fullname;
        }
    }
}
asort($allowedcourses);

echo $OUTPUT->header();

echo html_writer::tag('h2', get_string('report_full', 'mod_kolbtest'));

echo html_writer::start_tag('div', ['class' => 'mod_kolbtest-report-filters']);
echo html_writer::start_tag('form', ['method' => 'get', 'class' => 'form-inline']);
$styleopts = ['' => get_string('all_styles', 'mod_kolbtest')] + $styles;
echo html_writer::label(get_string('filter_style', 'mod_kolbtest'), 'filter_style') . ' ';
echo html_writer::select($styleopts, 'filter_style', $filterstyle, false, ['id' => 'filter_style']);
echo ' ';
$courseopts = [0 => get_string('all_courses', 'mod_kolbtest')] + $allowedcourses;
echo html_writer::label(get_string('course', 'mod_kolbtest'), 'filter_course') . ' ';
echo html_writer::select($courseopts, 'filter_course', $filtercourse, false, ['id' => 'filter_course']);
echo ' ';
echo html_writer::empty_tag('input', ['type' => 'submit', 'value' => get_string('apply_filters', 'mod_kolbtest'), 'class' => 'btn btn-secondary']);
echo html_writer::end_tag('form');
echo html_writer::end_tag('div');

if (empty($attempts)) {
    echo html_writer::div(get_string('no_attempts', 'mod_kolbtest'), 'alert alert-info');
} else {
    $table = new html_table();
    $table->head = [
        get_string('course', 'mod_kolbtest'),
        get_string('activity', 'mod_kolbtest'),
        get_string('student', 'mod_kolbtest'),
        get_string('style', 'mod_kolbtest'),
        'CE', 'RO', 'AC', 'AE',
        get_string('date', 'mod_kolbtest'),
    ];
    $table->data = [];
    foreach ($attempts as $a) {
        $table->data[] = [
            $courses[$a->courseid] ?? $a->courseid,
            $a->activityname ?? '',
            $users[$a->userid] ?? $a->userid,
            $styles[$a->style] ?? $a->style,
            $a->ce_score,
            $a->ro_score,
            $a->ac_score,
            $a->ae_score,
            userdate($a->timecreated, get_string('strftimedatetime', 'langconfig')),
        ];
    }
    echo html_writer::table($table);
}

echo kolbtest_author_credit();
echo $OUTPUT->footer();
