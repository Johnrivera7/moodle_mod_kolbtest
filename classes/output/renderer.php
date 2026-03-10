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

namespace mod_kolbtest\output;

defined('MOODLE_INTERNAL') || die();

use core\output\plugin_renderer_base;
use html_writer;

/**
 * Renderer for mod_kolbtest.
 *
 * @package   mod_kolbtest
 * @copyright 2025
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class renderer extends plugin_renderer_base {

    /**
     * Render the drag-and-drop test form.
     *
     * @param object $kolbtest
     * @param array $questions
     * @param int $cmid
     * @return string
     */
    public function render_test($kolbtest, $questions, $cmid) {
        global $OUTPUT;
        $intro = '';
        if (trim(strip_tags($kolbtest->intro))) {
            $intro = format_module_intro('kolbtest', $kolbtest, $cmid);
        }
        $data = [
            'intro' => $intro,
            'instructions' => get_string('instructions', 'mod_kolbtest'),
            'questions' => [],
            'cmid' => $cmid,
            'sesskey' => sesskey(),
            'submitlabel' => get_string('submit', 'mod_kolbtest'),
        ];
        foreach ($questions as $num => $q) {
            $opts = [];
            foreach ($q['options'] as $i => $opt) {
                $opts[] = ['text' => $opt['text'], 'dim' => $opt['dim'], 'index' => $i];
            }
            $data['questions'][] = [
                'num' => $num,
                'question' => $q['question'],
                'options' => $opts,
            ];
        }
        return $OUTPUT->render_from_template('mod_kolbtest/test_form', $data);
    }

    /**
     * Render the result and feedback for the student.
     *
     * @param object $kolbtest
     * @param object $attempt
     * @param array $feedback
     * @param bool $canviewreports
     * @param int $cmid
     * @return string
     */
    public function render_result($kolbtest, $attempt, $feedback, $canviewreports, $cmid) {
        global $OUTPUT;
        $dimlabels = [
            'CE' => 'Experiencia Concreta (CE)',
            'RO' => 'Observación Reflexiva (RO)',
            'AC' => 'Conceptualización Abstracta (AC)',
            'AE' => 'Experimentación Activa (AE)',
        ];
        $scores = [
            ['dim' => 'CE', 'label' => $dimlabels['CE'], 'score' => $attempt->ce_score],
            ['dim' => 'RO', 'label' => $dimlabels['RO'], 'score' => $attempt->ro_score],
            ['dim' => 'AC', 'label' => $dimlabels['AC'], 'score' => $attempt->ac_score],
            ['dim' => 'AE', 'label' => $dimlabels['AE'], 'score' => $attempt->ae_score],
        ];
        $stylename = get_string('style_' . $attempt->style, 'mod_kolbtest');
        if (strpos($stylename, 'style_') === 0) {
            $stylename = ucfirst($attempt->style);
        }
        $data = [
            'your_result' => get_string('your_result', 'mod_kolbtest'),
            'already_completed' => get_string('already_completed', 'mod_kolbtest'),
            'scores' => $scores,
            'scores_label' => get_string('scores', 'mod_kolbtest'),
            'style' => $attempt->style,
            'style_label' => get_string('style', 'mod_kolbtest'),
            'style_name' => $stylename,
            'feedback_title' => $feedback['title'],
            'feedback_subtitle' => $feedback['subtitle'],
            'feedback_body' => nl2br(s($feedback['body'])),
            'feedback_tips' => $feedback['tips'],
            'feedback_difficulty' => $feedback['difficulty'],
            'feedback_heading' => get_string('feedback', 'mod_kolbtest'),
            'canviewreports' => $canviewreports,
            'reporturl' => (new \moodle_url('/mod/kolbtest/report.php', ['id' => $cmid]))->out(false),
            'view_report' => get_string('view_report', 'mod_kolbtest'),
        ];
        return $OUTPUT->render_from_template('mod_kolbtest/result', $data);
    }
}
