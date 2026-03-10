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
        $authorhtml .= '<a href="https://github.com/Johnrivera7" target="_blank" rel="noopener" style="display: inline-flex; align-items: center; gap: 0.35rem; color: #0369a1; text-decoration: none; min-width: 26px; min-height: 26px; overflow: visible;" title="' . s(get_string('author_github', 'mod_kolbtest')) . '">';
        $authorhtml .= '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 -0.5 25 25" fill="currentColor" style="flex-shrink: 0; display: block;"><path d="m12.301 0h.093c2.242 0 4.34.613 6.137 1.68l-.055-.031c1.871 1.094 3.386 2.609 4.449 4.422l.031.058c1.04 1.769 1.654 3.896 1.654 6.166 0 5.406-3.483 10-8.327 11.658l-.087.026c-.063.02-.135.031-.209.031-.162 0-.312-.054-.433-.144l.002.001c-.128-.115-.208-.281-.208-.466 0-.005 0-.01 0-.014v.001q0-.048.008-1.226t.008-2.154c.007-.075.011-.161.011-.249 0-.792-.323-1.508-.844-2.025.618-.061 1.176-.163 1.718-.305l-.076.017c.573-.16 1.073-.373 1.537-.642l-.031.017c.508-.28.938-.636 1.292-1.058l.006-.007c.372-.476.663-1.036.84-1.645l.009-.035c.209-.683.329-1.468.329-2.281 0-.045 0-.091-.001-.136v.007c0-.022.001-.047.001-.072 0-1.248-.482-2.383-1.269-3.23l.003.003c.168-.44.265-.948.265-1.479 0-.649-.145-1.263-.404-1.814l.011.026c-.115-.022-.246-.035-.381-.035-.334 0-.649.078-.929.216l.012-.005c-.568.21-1.054.448-1.512.726l.038-.022-.609.384c-.922-.264-1.981-.416-3.075-.416s-2.153.152-3.157.436l.081-.02q-.256-.176-.681-.433c-.373-.214-.814-.421-1.272-.595l-.066-.022c-.293-.154-.64-.244-1.009-.244-.124 0-.246.01-.364.03l.013-.002c-.248.524-.393 1.139-.393 1.788 0 .531.097 1.04.275 1.509l-.01-.029c-.785.844-1.266 1.979-1.266 3.227 0 .025 0 .051.001.076v-.004c-.001.039-.001.084-.001.13 0 .809.12 1.591.344 2.327l-.015-.057c.189.643.476 1.202.85 1.693l-.009-.013c.354.435.782.793 1.267 1.062l.022.011c.432.252.933.465 1.46.614l.046.011c.466.125 1.024.227 1.595.284l.046.004c-.431.428-.718 1-.784 1.638l-.001.012c-.207.101-.448.183-.699.236l-.021.004c-.256.051-.549.08-.85.08-.022 0-.044 0-.066 0h.003c-.394-.008-.756-.136-1.055-.348l.006.004c-.371-.259-.671-.595-.881-.986l-.007-.015c-.198-.336-.459-.614-.768-.827l-.009-.006c-.225-.169-.49-.301-.776-.38l-.016-.004-.32-.048c-.023-.002-.05-.003-.077-.003-.14 0-.273.028-.394.077l.007-.003q-.128.072-.08.184c.039.086.087.16.145.225l-.001-.001c.061.072.13.135.205.19l.003.002.112.08c.283.148.516.354.693.603l.004.006c.191.237.359.505.494.792l.01.024.16.368c.135.402.38.738.7.981l.005.004c.3.234.662.402 1.057.478l.016.002c.33.064.714.104 1.106.112h.007c.045.002.097.002.15.002.261 0 .517-.021.767-.062l-.027.004.368-.064q0 .609.008 1.418t.008.873v.014c0 .185-.08.351-.208.466h-.001c-.119.089-.268.143-.431.143-.075 0-.147-.011-.214-.032l.005.001c-4.929-1.689-8.409-6.283-8.409-11.69 0-2.268.612-4.393 1.681-6.219l-.032.058c1.094-1.871 2.609-3.386 4.422-4.449l.058-.031c1.739-1.034 3.835-1.645 6.073-1.645h.098-.005zm-7.64 17.666q.048-.112-.112-.192-.16-.048-.208.032-.048.112.112.192.144.096.208-.032zm.497.545q.112-.08-.032-.256-.16-.144-.256-.048-.112.08.032.256.159.157.256.047zm.48.72q.144-.112 0-.304-.128-.208-.272-.096-.144.08 0 .288t.272.112zm.672.673q.128-.128-.064-.304-.192-.192-.32-.048-.144.128.064.304.192.192.32.044zm.913.4q.048-.176-.208-.256-.24-.064-.304.112t.208.24q.24.097.304-.096zm1.009.08q0-.208-.272-.176-.256 0-.256.176 0 .208.272.176.256.001.256-.175zm.929-.16q-.032-.176-.288-.144-.256.048-.224.24t.288.128.225-.224z"/></svg> GitHub</a>';
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
}
