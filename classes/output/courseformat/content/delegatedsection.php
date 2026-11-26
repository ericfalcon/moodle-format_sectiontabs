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

namespace format_sectiontabs\output\courseformat\content;

use renderer_base;
use stdClass;

/**
 * Subsection displayed in its parent section: always with its activities.
 *
 * In "one section per page" mode, Moodle 4.5 displays a subsection as a summary card (number of activities and
 * progress) instead of its activities; Moodle 5.0 and later display its activities. The subsection tabs need
 * the activities, so this class gives Moodle 4.5 the behaviour of Moodle 5.
 *
 * @package    format_sectiontabs
 * @copyright  2026 Eric Falcon
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class delegatedsection extends \core_courseformat\output\local\content\delegatedsection {
    /**
     * Add the activities of the subsection, never its summary.
     *
     * @param stdClass $data the current cm data reference
     * @param renderer_base $output typically, the renderer that's calling this function
     * @return bool if the cm has name data
     */
    protected function add_cm_data(stdClass &$data, renderer_base $output): bool {
        if (!$this->section->uservisible) {
            return false;
        }
        $cmlist = new $this->cmlistclass($this->format, $this->section);
        $data->cmlist = $cmlist->export_for_template($output);
        return true;
    }
}
