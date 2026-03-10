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

if ($hassiteconfig) {
    // Enlace al reporte completo (todos los cursos) en Administración > Módulos de actividad > Test de Kolb.
    $reportfullurl = new moodle_url('/mod/kolbtest/reportall.php');
    $ADMIN->add(
        'modsettingskolbtest',
        new admin_externalpage(
            'kolbtestreportfull',
            get_string('report_full', 'mod_kolbtest'),
            $reportfullurl,
            'moodle/site:config'
        )
    );

    $settings = new admin_settingpage('modsettingkolbtest', get_string('settings', 'mod_kolbtest'));

    if ($ADMIN->fulltree) {
        $settings->add(new admin_setting_heading(
            'mod_kolbtest/general',
            get_string('general'),
            ''
        ));

        $settings->add(new admin_setting_configcheckbox(
            'mod_kolbtest/showauthorcredit',
            get_string('show_author_credit', 'mod_kolbtest'),
            get_string('show_author_credit_desc', 'mod_kolbtest'),
            1
        ));

        $settings->add(new admin_setting_configcheckbox(
            'mod_kolbtest/allowmultipleattempts',
            get_string('allow_multiple_attempts', 'mod_kolbtest'),
            get_string('allow_multiple_attempts_desc', 'mod_kolbtest'),
            0
        ));
    }

    $ADMIN->add('modsettingskolbtest', $settings);
}
