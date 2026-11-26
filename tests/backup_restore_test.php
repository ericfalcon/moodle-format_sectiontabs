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

namespace format_sectiontabs;

/**
 * Tests of the restore: the custom tabs follow the sections of the new course.
 *
 * @package    format_sectiontabs
 * @category   test
 * @copyright  2026 Eric Falcon
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \restore_format_sectiontabs_plugin
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\restore_format_sectiontabs_plugin::class)]
final class backup_restore_test extends \advanced_testcase {
    /**
     * Duplicate a course with custom tabs: the tabs point at the sections of the copy.
     */
    public function test_duplicate_course_maps_sections(): void {
        global $CFG, $USER;
        require_once($CFG->dirroot . '/backup/util/includes/backup_includes.php');
        require_once($CFG->dirroot . '/backup/util/includes/restore_includes.php');
        require_once($CFG->dirroot . '/course/externallib.php');
        $this->resetAfterTest();
        $this->setAdminUser();

        $course = $this->getDataGenerator()->create_course(['format' => 'sectiontabs', 'numsections' => 3]);
        $sections = get_fast_modinfo($course->id)->get_section_info_all();
        $format = course_get_format($course->id);
        $format->update_course_format_options(['gtabsmode' => \format_sectiontabs::TABS_CUSTOM]);
        $format->set_custom_tabs([
            ['label' => 'Accueil', 'sectionids' => [$sections[0]->id], 'url' => ''],
            ['label' => 'Espace de cours', 'sectionids' => [$sections[1]->id, $sections[3]->id], 'url' => ''],
            ['label' => 'Ressources', 'sectionids' => [], 'url' => 'https://example.com/r'],
        ]);

        $copy = \core_course_external::duplicate_course($course->id, 'Copie', 'copie', $course->category, 1);
        $copysections = get_fast_modinfo($copy['id'])->get_section_info_all();
        $tabs = course_get_format($copy['id'])->get_custom_tabs();

        $this->assertSame(['Accueil', 'Espace de cours', 'Ressources'], array_column($tabs, 'label'));
        $this->assertSame([(int)$copysections[0]->id], $tabs[0]['sectionids']);
        $this->assertSame([(int)$copysections[1]->id, (int)$copysections[3]->id], $tabs[1]['sectionids']);
        $this->assertSame('https://example.com/r', $tabs[2]['url']);
        $this->assertSame(\format_sectiontabs::TABS_CUSTOM, (int)course_get_format($copy['id'])->get_course()->gtabsmode);
    }
}
