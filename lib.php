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
 * Comprueba si un usuario puede acceder al reporte completo (todos los cursos).
 * Pueden: quien tiene moodle/site:config o quien tiene asignado uno de los roles configurados.
 *
 * @param int|null $userid ID del usuario (null = usuario actual)
 * @return bool
 */
function kolbtest_can_access_report_full($userid = null) {
    global $USER, $DB;
    if ($userid === null) {
        $userid = $USER->id;
    }
    if (has_capability('moodle/site:config', context_system::instance(), $userid)) {
        return true;
    }
    $allowed = get_config('mod_kolbtest', 'roles_report_full');
    if ($allowed === false || $allowed === '') {
        return false;
    }
    $roleids = array_map('intval', explode(',', $allowed));
    $roleids = array_filter($roleids);
    if (empty($roleids)) {
        return false;
    }
    list($insql, $params) = $DB->get_in_or_equal($roleids, SQL_PARAMS_NAMED);
    $params['userid'] = $userid;
    return $DB->record_exists_sql(
        "SELECT 1 FROM {role_assignments} WHERE userid = :userid AND roleid $insql",
        $params
    );
}

/**
 * Devuelve el bloque HTML de crédito del autor con enlaces (correo y GitHub).
 *
 * @return string HTML
 */
function kolbtest_author_credit() {
    $author = 'John Rivera González';
    $email = 'johnriveragonzalez7@gmail.com';
    $github = 'https://github.com/Johnrivera7';
    $developedby = get_string('developed_by', 'mod_kolbtest');
    $titleemail = get_string('author_email', 'mod_kolbtest');
    $titlegithub = get_string('author_github', 'mod_kolbtest');
    $iconemail = '<svg class="mod_kolbtest-author-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>';
    $icongithub = '<svg class="mod_kolbtest-author-icon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 -0.5 25 25" fill="currentColor" aria-hidden="true"><path d="m12.301 0h.093c2.242 0 4.34.613 6.137 1.68l-.055-.031c1.871 1.094 3.386 2.609 4.449 4.422l.031.058c1.04 1.769 1.654 3.896 1.654 6.166 0 5.406-3.483 10-8.327 11.658l-.087.026c-.063.02-.135.031-.209.031-.162 0-.312-.054-.433-.144l.002.001c-.128-.115-.208-.281-.208-.466 0-.005 0-.01 0-.014v.001q0-.048.008-1.226t.008-2.154c.007-.075.011-.161.011-.249 0-.792-.323-1.508-.844-2.025.618-.061 1.176-.163 1.718-.305l-.076.017c.573-.16 1.073-.373 1.537-.642l-.031.017c.508-.28.938-.636 1.292-1.058l.006-.007c.372-.476.663-1.036.84-1.645l.009-.035c.209-.683.329-1.468.329-2.281 0-.045 0-.091-.001-.136v.007c0-.022.001-.047.001-.072 0-1.248-.482-2.383-1.269-3.23l.003.003c.168-.44.265-.948.265-1.479 0-.649-.145-1.263-.404-1.814l.011.026c-.115-.022-.246-.035-.381-.035-.334 0-.649.078-.929.216l.012-.005c-.568.21-1.054.448-1.512.726l.038-.022-.609.384c-.922-.264-1.981-.416-3.075-.416s-2.153.152-3.157.436l.081-.02q-.256-.176-.681-.433c-.373-.214-.814-.421-1.272-.595l-.066-.022c-.293-.154-.64-.244-1.009-.244-.124 0-.246.01-.364.03l.013-.002c-.248.524-.393 1.139-.393 1.788 0 .531.097 1.04.275 1.509l-.01-.029c-.785.844-1.266 1.979-1.266 3.227 0 .025 0 .051.001.076v-.004c-.001.039-.001.084-.001.13 0 .809.12 1.591.344 2.327l-.015-.057c.189.643.476 1.202.85 1.693l-.009-.013c.354.435.782.793 1.267 1.062l.022.011c.432.252.933.465 1.46.614l.046.011c.466.125 1.024.227 1.595.284l.046.004c-.431.428-.718 1-.784 1.638l-.001.012c-.207.101-.448.183-.699.236l-.021.004c-.256.051-.549.08-.85.08-.022 0-.044 0-.066 0h.003c-.394-.008-.756-.136-1.055-.348l.006.004c-.371-.259-.671-.595-.881-.986l-.007-.015c-.198-.336-.459-.614-.768-.827l-.009-.006c-.225-.169-.49-.301-.776-.38l-.016-.004-.32-.048c-.023-.002-.05-.003-.077-.003-.14 0-.273.028-.394.077l.007-.003q-.128.072-.08.184c.039.086.087.16.145.225l-.001-.001c.061.072.13.135.205.19l.003.002.112.08c.283.148.516.354.693.603l.004.006c.191.237.359.505.494.792l.01.024.16.368c.135.402.38.738.7.981l.005.004c.3.234.662.402 1.057.478l.016.002c.33.064.714.104 1.106.112h.007c.045.002.097.002.15.002.261 0 .517-.021.767-.062l-.027.004.368-.064q0 .609.008 1.418t.008.873v.014c0 .185-.08.351-.208.466h-.001c-.119.089-.268.143-.431.143-.075 0-.147-.011-.214-.032l.005.001c-4.929-1.689-8.409-6.283-8.409-11.69 0-2.268.612-4.393 1.681-6.219l-.032.058c1.094-1.871 2.609-3.386 4.422-4.449l.058-.031c1.739-1.034 3.835-1.645 6.073-1.645h.098-.005zm-7.64 17.666q.048-.112-.112-.192-.16-.048-.208.032-.048.112.112.192.144.096.208-.032zm.497.545q.112-.08-.032-.256-.16-.144-.256-.048-.112.08.032.256.159.157.256.047zm.48.72q.144-.112 0-.304-.128-.208-.272-.096-.144.08 0 .288t.272.112zm.672.673q.128-.128-.064-.304-.192-.192-.32-.048-.144.128.064.304.192.192.32.044zm.913.4q.048-.176-.208-.256-.24-.064-.304.112t.208.24q.24.097.304-.096zm1.009.08q0-.208-.272-.176-.256 0-.256.176 0 .208.272.176.256.001.256-.175zm.929-.16q-.032-.176-.288-.144-.256.048-.224.24t.288.128.225-.224z"/></svg>';
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

/**
 * Envía al navegador un CSV con cabeceras y filas (compatible con Excel).
 *
 * @param string $filename nombre del archivo (ej. report.csv)
 * @param array $headers títulos de columnas
 * @param array $rows filas (cada fila es un array de celdas)
 */
function kolbtest_send_csv($filename, array $headers, array $rows) {
    $utf8bom = "\xEF\xBB\xBF";
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    $out = fopen('php://output', 'w');
    fwrite($out, $utf8bom);
    fputcsv($out, $headers, ',');
    foreach ($rows as $row) {
        fputcsv($out, $row, ',');
    }
    fclose($out);
    exit;
}
