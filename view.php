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

global $DB, $USER, $PAGE, $OUTPUT;

$id = required_param('id', PARAM_INT); // course_module id

list($course, $cm) = get_course_and_cm_from_cmid($id, 'kolbtest');
$kolbtest = $DB->get_record('kolbtest', ['id' => $cm->instance], '*', MUST_EXIST);

require_login($course, true, $cm);
$context = context_module::instance($cm->id);
require_capability('mod/kolbtest:view', $context);

$PAGE->set_url('/mod/kolbtest/view.php', ['id' => $id]);
$PAGE->set_title($kolbtest->name);
$PAGE->set_heading($course->fullname);
$PAGE->set_activity_record($kolbtest);
$PAGE->add_body_class('mod_kolbtest');

// Envío de respuestas (POST).
if ($_SERVER['REQUEST_METHOD'] === 'POST' && optional_param('sesskey', '', PARAM_RAW) === sesskey()) {
    $order = optional_param('order', '', PARAM_RAW);
    if ($order !== '') {
        $order = json_decode($order, true);
        if (is_array($order)) {
            $scores = kolbtest_calculate_scores($order);
            $style = kolbtest_get_style_from_scores($scores);
            $attempt = (object)[
                'kolbtestid' => $kolbtest->id,
                'userid' => $USER->id,
                'timecreated' => time(),
                'ce_score' => $scores['CE'],
                'ro_score' => $scores['RO'],
                'ac_score' => $scores['AC'],
                'ae_score' => $scores['AE'],
                'style' => $style,
            ];
            $DB->insert_record('kolbtest_attempts', $attempt);
            redirect(new moodle_url('/mod/kolbtest/view.php', ['id' => $id]));
        }
    }
}

$canviewreports = has_capability('mod/kolbtest:viewreports', $context);

// Añadir "Ver reporte" a la barra de pestañas (solo para roles con permiso).
if ($canviewreports) {
    $reporturl = new moodle_url('/mod/kolbtest/report.php', ['id' => $id]);
    $settingsnav = $PAGE->settingsnav;
    $modulenode = $settingsnav->get('modulesettings');
    if ($modulenode) {
        $modulenode->add(get_string('view_report', 'mod_kolbtest'), $reporturl, navigation_node::TYPE_SETTING, null, 'kolbtestreport');
    }
}

$attempt = $DB->get_record('kolbtest_attempts', ['kolbtestid' => $kolbtest->id, 'userid' => $USER->id]);
$questions = kolbtest_get_questions();

$output = $PAGE->get_renderer('mod_kolbtest');
$PAGE->requires->css(new moodle_url('/mod/kolbtest/styles.css'));
if (!$attempt) {
    $PAGE->requires->js_call_amd('mod_kolbtest/dragdrop', 'init', []);
}
echo $OUTPUT->header();

if ($attempt) {
    // Mostrar resultado y retroalimentación.
    $feedback = kolbtest_get_feedback_for_student($attempt->style);
    echo $output->render_result($kolbtest, $attempt, $feedback, false, $cm->id);
} else {
    // Mostrar test arrastrar y soltar.
    echo $output->render_test($kolbtest, $questions, $id);
}

echo kolbtest_author_credit();
echo $OUTPUT->footer();
