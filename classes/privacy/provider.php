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

namespace format_sectiontabs\privacy;

use core_privacy\local\metadata\collection;
use core_privacy\local\request\writer;

/**
 * Privacy provider: the format stores, as user preferences, the last section and subsection consulted in each course
 * ("Resume where I left off").
 *
 * @package    format_sectiontabs
 * @copyright  2026 Eric Falcon
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements
    \core_privacy\local\metadata\provider,
    \core_privacy\local\request\user_preference_provider {
    /**
     * Data stored by the plugin.
     *
     * @param collection $collection
     * @return collection
     */
    public static function get_metadata(collection $collection): collection {
        $collection->add_user_preference('format_sectiontabs_last', 'privacy:metadata:preference:last');
        $collection->add_user_preference('format_sectiontabs_lastsub', 'privacy:metadata:preference:lastsub');
        return $collection;
    }

    /**
     * Export the preferences of a user.
     *
     * @param int $userid
     */
    public static function export_user_preferences(int $userid) {
        foreach (get_user_preferences(null, null, $userid) as $name => $value) {
            if (preg_match('/^format_sectiontabs_last(sub)?_(\d+)$/', $name, $matches)) {
                $description = get_string(
                    $matches[1] ? 'privacy:metadata:preference:lastsub' : 'privacy:metadata:preference:last',
                    'format_sectiontabs'
                );
                writer::export_user_preference('format_sectiontabs', $name, $value, $description);
            }
        }
    }
}
