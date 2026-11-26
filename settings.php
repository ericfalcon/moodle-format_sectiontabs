<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Site settings of the format.
 *
 * @package    format_sectiontabs
 * @copyright  2026 Eric Falcon
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    require_once($CFG->dirroot . '/course/format/sectiontabs/lib.php');
    // Empty by default: the tabs take the main colour of the theme (with Épure, its brand colours).
    $settings->add(new admin_setting_configcolourpicker(
        'format_sectiontabs/activecolor',
        new lang_string('activecolor', 'format_sectiontabs'),
        new lang_string('activecolor_desc', 'format_sectiontabs', format_sectiontabs::get_theme_colour()),
        ''
    ));
    $settings->add(new admin_setting_configcolourpicker(
        'format_sectiontabs/activeborder',
        new lang_string('activeborder', 'format_sectiontabs'),
        new lang_string('activeborder_desc', 'format_sectiontabs'),
        ''
    ));
}
