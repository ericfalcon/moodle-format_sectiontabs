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
 * Upgrade steps of the "Tabs and section menu" format.
 *
 * @package    format_sectiontabs
 * @copyright  2026 Eric Falcon
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Upgrade the plugin.
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_format_sectiontabs_upgrade($oldversion) {
    if ($oldversion < 2026112600) {
        // Version 1.2: the tabs take the colours of the theme by default. The former default colours
        // (yellow #ecb21e, underline #2b1a10), never changed by the administrator, give way to the theme.
        if (get_config('format_sectiontabs', 'activecolor') === '#ecb21e') {
            set_config('activecolor', '', 'format_sectiontabs');
        }
        if (get_config('format_sectiontabs', 'activeborder') === '#2b1a10') {
            set_config('activeborder', '', 'format_sectiontabs');
        }
        upgrade_plugin_savepoint(true, 2026112600, 'format', 'sectiontabs');
    }
    return true;
}
