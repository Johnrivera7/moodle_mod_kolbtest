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
 * Library and API for mod_kolbtest.
 *
 * @package   mod_kolbtest
 * @copyright 2025 John Rivera González <johnriveragonzalez7@gmail.com>
 * @author    John Rivera González
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/** Actividad con intro */
define('KOLBTEST_FEATURE_MOD_INTRO', true);

/**
 * Añade una instancia del Test de Kolb.
 *
 * @param object $data datos del formulario
 * @param object|null $mform formulario
 * @return int id de la instancia creada
 */
function kolbtest_add_instance($data, $mform = null) {
    global $DB;
    $data->timecreated = time();
    $data->timemodified = $data->timecreated;
    return $DB->insert_record('kolbtest', $data);
}

/**
 * Actualiza una instancia del Test de Kolb.
 *
 * @param object $data datos del formulario
 * @param object $mform formulario
 * @return bool
 */
function kolbtest_update_instance($data, $mform) {
    global $DB;
    $data->timemodified = time();
    $data->id = $data->instance;
    return $DB->update_record('kolbtest', $data);
}

/**
 * Elimina una instancia y todos sus intentos.
 *
 * @param int $id id de la instancia kolbtest
 * @return bool
 */
function kolbtest_delete_instance($id) {
    global $DB;
    if (!$kolbtest = $DB->get_record('kolbtest', ['id' => $id])) {
        return false;
    }
    $DB->delete_records('kolbtest_attempts', ['kolbtestid' => $id]);
    $DB->delete_records('kolbtest', ['id' => $id]);
    return true;
}

/**
 * Devuelve la información del módulo para la página del curso (nombre, descripción).
 *
 * @param object $cm course module
 * @return cached_cm_info|null
 */
function kolbtest_get_coursemodule_info($cm) {
    global $DB;
    $kolbtest = $DB->get_record('kolbtest', ['id' => $cm->instance], 'name, intro, introformat');
    if (!$kolbtest) {
        return null;
    }
    $info = new cached_cm_info();
    $info->name = $kolbtest->name;
    $info->content = format_module_intro('kolbtest', $kolbtest, $cm->id);
    return $info;
}

/**
 * Callback de soporte de características del módulo.
 *
 * @param string $feature FEATURE_xx constante
 * @return bool|null
 */
/**
 * Devuelve el bloque HTML de crédito del autor con enlaces (correo y GitHub).
 *
 * @return string HTML
 */
function kolbtest_author_credit() {
    if ((string) get_config('mod_kolbtest', 'showauthorcredit') === '0') {
        return '';
    }
    $author = 'John Rivera González';
    $email = 'johnriveragonzalez7@gmail.com';
    $github = 'https://github.com/Johnrivera7';
    $developedby = get_string('developed_by', 'mod_kolbtest');
    $titleemail = get_string('author_email', 'mod_kolbtest');
    $titlegithub = get_string('author_github', 'mod_kolbtest');
    $iconemail = '<svg class="mod_kolbtest-author-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>';
    $icongithub = '<svg class="mod_kolbtest-author-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23 4.5-.465-.015-.96-.225-1.44-.225-.48 0-.975.015-1.44.225-2.295-1.56-3.3-1.23-3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>';
    return '<div class="mod_kolbtest-author-credit">' .
        '<span class="mod_kolbtest-author-label">' . s($developedby) . '</span> ' .
        '<span class="mod_kolbtest-author-name">' . s($author) . '</span> ' .
        '<a href="mailto:' . s($email) . '" class="mod_kolbtest-author-link" title="' . s($titleemail) . '" aria-label="' . s($titleemail) . '">' . $iconemail . '</a> ' .
        '<a href="' . s($github) . '" target="_blank" rel="noopener noreferrer" class="mod_kolbtest-author-link" title="' . s($titlegithub) . '" aria-label="' . s($titlegithub) . '">' . $icongithub . '</a>' .
        '</div>';
}

/**
 * Callback de soporte de características del módulo.
 *
 * @param string $feature FEATURE_xx constante
 * @return bool|null
 */
function kolbtest_supports($feature) {
    switch ($feature) {
        case FEATURE_MOD_INTRO:
            return true;
        case FEATURE_BACKUP_MOODLE2:
            return true;
        case FEATURE_SHOW_DESCRIPTION:
            return true;
        case FEATURE_GRADE_HAS_GRADE:
            return false;
        case FEATURE_GROUPS:
            return false;
        case FEATURE_GROUPINGS:
            return false;
        case FEATURE_MOD_PURPOSE:
            return MOD_PURPOSE_ASSESSMENT;
        default:
            return null;
    }
}
