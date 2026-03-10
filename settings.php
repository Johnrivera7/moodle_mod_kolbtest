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

// Parent node name is set by plugininfo/mod when including this file (load_settings).
if ($hassiteconfig) {
    if (!isset($parentnodename)) {
        $parentnodename = 'modsettingkolbtest';
        $ADMIN->add('modules', new admin_category($parentnodename, get_string('pluginname', 'mod_kolbtest')));
    }
    $parent = $parentnodename;
    $reportfullurl = new moodle_url('/mod/kolbtest/reportall.php');
    $ADMIN->add(
        $parent,
        new admin_externalpage(
            'kolbtestreportfull',
            get_string('report_full', 'mod_kolbtest'),
            $reportfullurl,
            'moodle/site:config'
        )
    );

    $wecreatedsettings = false;
    if (!isset($settings) || !is_object($settings)) {
        $settings = new admin_settingpage('modsettingkolbtest', get_string('settings', 'mod_kolbtest'));
        $wecreatedsettings = true;
    }

    if ($ADMIN->fulltree) {
        $settings->add(new admin_setting_heading(
            'mod_kolbtest/report_full_about',
            get_string('report_full_about', 'mod_kolbtest'),
            get_string('report_full_about_desc', 'mod_kolbtest')
        ));

        $roles = role_get_names(null, ROLENAME_BOTH, true);
        $rolechoices = [];
        foreach ($roles as $rid => $role) {
            $rolechoices[$rid] = is_object($role) ? ($role->localname ?? $role->shortname ?? '') : (string) $role;
        }
        $settings->add(new admin_setting_configmultiselect(
            'mod_kolbtest/roles_report_full',
            get_string('roles_report_full', 'mod_kolbtest'),
            get_string('roles_report_full_desc', 'mod_kolbtest'),
            ['1'],
            $rolechoices
        ));

        $settings->add(new admin_setting_heading(
            'mod_kolbtest/general',
            get_string('general'),
            ''
        ));

        $settings->add(new admin_setting_configcheckbox(
            'mod_kolbtest/allowmultipleattempts',
            get_string('allow_multiple_attempts', 'mod_kolbtest'),
            get_string('allow_multiple_attempts_desc', 'mod_kolbtest'),
            0
        ));

        $authorhtml = '<div class="mod_kolbtest-admin-box mod_kolbtest-admin-author" style="';
        $authorhtml .= 'background: linear-gradient(135deg, #e0f2fe 0%, #fce7f3 100%); border: 1px solid #bae6fd; border-radius: 12px; padding: 1rem 1.25rem; margin: 0.5rem 0; box-shadow: 0 2px 8px rgba(0,0,0,0.06);';
        $authorhtml .= '"><p style="margin: 0 0 0.5rem 0; font-weight: 600; color: #0c4a6e;">John Rivera González</p>';
        $authorhtml .= '<p style="margin: 0; font-size: 0.95rem;">';
        $authorhtml .= '<a href="mailto:johnriveragonzalez7@gmail.com" style="display: inline-flex; align-items: center; gap: 0.35rem; color: #0369a1; text-decoration: none; margin-right: 1rem;" title="' . s(get_string('author_email', 'mod_kolbtest')) . '">';
        $authorhtml .= '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg> johnriveragonzalez7@gmail.com</a>';
        $authorhtml .= '<a href="https://github.com/Johnrivera7" target="_blank" rel="noopener" style="display: inline-flex; align-items: center; gap: 0.35rem; color: #0369a1; text-decoration: none; min-width: 24px; min-height: 24px; overflow: visible;" title="' . s(get_string('author_github', 'mod_kolbtest')) . '">';
        $authorhtml .= '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" style="flex-shrink: 0; display: block;"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23 4.5-.465-.015-.96-.225-1.44-.225-.48 0-.975.015-1.44.225-2.295-1.56-3.3-1.23-3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg> GitHub</a>';
        $authorhtml .= '</p></div>';

        $settings->add(new admin_setting_heading(
            'mod_kolbtest/author',
            get_string('author_credits', 'mod_kolbtest'),
            $authorhtml
        ));

        $ackhtml = '<div class="mod_kolbtest-admin-box mod_kolbtest-admin-ack" style="';
        $ackhtml .= 'background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border: 1px solid #fcd34d; border-radius: 12px; padding: 1rem 1.25rem; margin: 0.5rem 0; box-shadow: 0 2px 8px rgba(0,0,0,0.06); animation: none;';
        $ackhtml .= '"><p style="margin: 0; color: #92400e; font-size: 0.95rem;">' . get_string('acknowledgments_text', 'mod_kolbtest') . '</p></div>';

        $settings->add(new admin_setting_heading(
            'mod_kolbtest/acknowledgments',
            get_string('acknowledgments', 'mod_kolbtest'),
            $ackhtml
        ));
    }

    if ($wecreatedsettings) {
        $ADMIN->add($parent, $settings);
    }
}
