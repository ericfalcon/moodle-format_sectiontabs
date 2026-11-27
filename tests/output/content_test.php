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

namespace format_sectiontabs\output;

use format_sectiontabs\output\courseformat\content;

/**
 * Tests of the course page data: tabs, section menu, subsection tabs, progress, previous / next, resume.
 *
 * @package    format_sectiontabs
 * @category   test
 * @copyright  2026 Eric Falcon
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \format_sectiontabs\output\courseformat\content
 */
#[\PHPUnit\Framework\Attributes\CoversClass(content::class)]
final class content_test extends \advanced_testcase {
    /** @var \stdClass course */
    private $course;

    /** @var \stdClass student */
    private $student;

    /** @var \stdClass teacher */
    private $teacher;

    /** @var int[] ids of the subsection course modules of section 1 */
    private $subcmids = [];

    /** @var int[] ids of the tracked activities, by subsection index */
    private $activities = [];

    /**
     * Course: 4 sections, section 2 hidden, 2 subsections with 2 tracked activities each in section 1.
     */
    protected function setUp(): void {
        global $CFG;
        parent::setUp();
        require_once($CFG->dirroot . '/course/format/sectiontabs/lib.php');
        require_once($CFG->libdir . '/completionlib.php');
        $this->resetAfterTest();
        // The subsection activity is disabled by default on Moodle 4.5.
        \core\plugininfo\mod::enable_plugin('subsection', 1);
        set_config('enablecompletion', 1);

        $generator = $this->getDataGenerator();
        $this->course = $generator->create_course([
            'format' => 'sectiontabs', 'numsections' => 4, 'enablecompletion' => 1, 'coursedisplay' => 1,
        ]);
        $this->student = $generator->create_and_enrol($this->course, 'student');
        $this->teacher = $generator->create_and_enrol($this->course, 'editingteacher');
        foreach ([1 => 'Bloc 1', 2 => 'Bloc 2 caché', 3 => 'Bloc 3', 4 => 'Bloc 4'] as $num => $name) {
            course_update_section($this->course, get_fast_modinfo($this->course)->get_section_info($num), ['name' => $name]);
        }
        course_update_section($this->course, get_fast_modinfo($this->course)->get_section_info(2), ['visible' => 0]);

        foreach (['Cours', 'Exercices'] as $i => $name) {
            $sub = $generator->create_module('subsection', ['course' => $this->course->id, 'section' => 1, 'name' => $name]);
            $this->subcmids[$i] = (int)$sub->cmid;
            $delegated = get_fast_modinfo($this->course)->get_cm($sub->cmid)->get_delegated_section_info();
            foreach ([1, 2] as $n) {
                $page = $generator->create_module('page', ['course' => $this->course->id, 'section' => $delegated->section,
                    'name' => "$name $n", 'completion' => COMPLETION_TRACKING_MANUAL]);
                $this->activities[$i][] = (int)$page->cmid;
            }
        }
        rebuild_course_cache($this->course->id, true);
    }

    /**
     * Data of the course page for the current user.
     *
     * @param int|null $sectionnum section displayed, null for the course home
     * @return \stdClass
     */
    private function export(?int $sectionnum): \stdClass {
        global $PAGE;
        $PAGE = new \moodle_page();
        $PAGE->set_course(get_course($this->course->id));
        $PAGE->set_url('/course/view.php', ['id' => $this->course->id]);
        // The format instance is cached by course, with the course data of the previous user: start afresh,
        // as a new page would.
        \core_courseformat\base::reset_course_cache($this->course->id);
        $format = course_get_format($this->course->id);
        $format->set_sectionnum($sectionnum);
        $renderer = $PAGE->get_renderer('format_sectiontabs');
        return (new content($format))->export_for_template($renderer);
    }

    /**
     * Mark tracked activities as completed by the student.
     *
     * @param int[] $cmids
     */
    private function complete(array $cmids): void {
        $completion = new \completion_info(get_course($this->course->id));
        $modinfo = get_fast_modinfo($this->course->id);
        foreach ($cmids as $cmid) {
            $completion->update_state($modinfo->get_cm($cmid), COMPLETION_COMPLETE, $this->student->id);
        }
    }

    /**
     * One tab per section: hidden sections are left out for students, shown to teachers.
     */
    public function test_tabs_and_hidden_sections(): void {
        $this->setUser($this->student);
        $data = $this->export(1);
        $names = array_column($data->gtabs['tabs'], 'title');
        $this->assertNotContains('Bloc 2 caché', $names);
        $this->assertCount(4, $names);
        $this->assertSame(['Bloc 1'], array_column(array_filter($data->gtabs['tabs'], fn($t) => $t['active']), 'title'));
        $this->assertSame($names, array_column($data->gtabs['sections'], 'title'), 'Same sections in the left menu.');

        $this->setUser($this->teacher);
        $data = $this->export(1);
        $hidden = array_filter($data->gtabs['tabs'], fn($t) => $t['title'] === 'Bloc 2 caché');
        $this->assertCount(1, $hidden);
        $this->assertTrue(reset($hidden)['hidden']);
    }

    /**
     * Subsections of the displayed section as tabs.
     */
    public function test_subtabs(): void {
        $this->setUser($this->student);
        $data = $this->export(1);
        $this->assertSame(['Cours', 'Exercices'], array_column($data->gsubtabs['subtabs'], 'title'));
        $this->assertSame($this->subcmids, array_map('intval', array_column($data->gsubtabs['subtabs'], 'cmid')));
        $this->assertTrue($data->gsubtabs['subtabs'][0]['active']);
        $this->assertNull($this->export(3)->gsubtabs, 'No subsection, no tabs.');
    }

    /**
     * Previous / next: inaccessible sections are skipped.
     */
    public function test_navigation(): void {
        $this->setUser($this->student);
        $data = $this->export(1);
        $this->assertSame('General', $data->gnav['prev']['title']);
        $this->assertSame('Bloc 3', $data->gnav['next']['title'], 'The hidden section 2 is skipped.');
        $this->assertObjectNotHasProperty('sectionnavigation', $data);

        $data = $this->export(4);
        $this->assertSame('Bloc 3', $data->gnav['prev']['title']);
        $this->assertNull($data->gnav['next']);
    }

    /**
     * Progress: a section is complete when all its tracked activities, subsections included, are completed.
     */
    public function test_progress(): void {
        $this->setUser($this->student);
        $this->complete([$this->activities[0][0], $this->activities[0][1], $this->activities[1][0]]);
        $data = $this->export(1);
        $this->assertTrue($data->gsubtabs['subtabs'][0]['complete']);
        $this->assertFalse($data->gsubtabs['subtabs'][1]['complete']);
        $section1 = array_values(array_filter($data->gtabs['sections'], fn($s) => $s['title'] === 'Bloc 1'))[0];
        $this->assertFalse($section1['complete']);

        $this->complete([$this->activities[1][1]]);
        $data = $this->export(1);
        $section1 = array_values(array_filter($data->gtabs['sections'], fn($s) => $s['title'] === 'Bloc 1'))[0];
        $this->assertTrue($section1['complete']);
        $section3 = array_values(array_filter($data->gtabs['sections'], fn($s) => $s['title'] === 'Bloc 3'))[0];
        $this->assertFalse($section3['complete'], 'No tracked activity: never complete.');

        // Not for users whose completion is not tracked, nor when the option is off.
        $this->setUser($this->teacher);
        $this->assertEmpty(array_filter(array_column($this->export(1)->gtabs['sections'], 'complete')));
        $this->setUser($this->student);
        course_get_format($this->course->id)->update_course_format_options(['gtabsprogress' => 0]);
        $this->assertEmpty(array_filter(array_column($this->export(1)->gtabs['sections'], 'complete')));
    }

    /**
     * Custom tabs: grouped sections, completion and the warning about sections without a tab.
     */
    public function test_custom_tabs(): void {
        $modinfo = get_fast_modinfo($this->course->id);
        $format = course_get_format($this->course->id);
        $format->update_course_format_options(['gtabsmode' => \format_sectiontabs::TABS_CUSTOM]);
        $format->set_custom_tabs([
            ['label' => 'Accueil', 'sectionids' => [$modinfo->get_section_info(0)->id], 'url' => ''],
            ['label' => 'Espace de cours', 'sectionids' => [$modinfo->get_section_info(1)->id,
                $modinfo->get_section_info(3)->id], 'url' => ''],
        ]);

        $this->setUser($this->student);
        $this->complete(array_merge(...$this->activities));
        $data = $this->export(3);
        $this->assertSame(['Accueil', 'Espace de cours'], array_column($data->gtabs['tabs'], 'title'));
        $this->assertTrue($data->gtabs['tabs'][1]['active']);
        $this->assertTrue($data->gtabs['tabs'][1]['complete'], 'Section 3 has no tracked activity.');
        $this->assertFalse($data->gtabs['hasorphans'], 'The warning is for editors only.');

        $this->setUser($this->teacher);
        global $USER;
        $USER->editing = 1;
        $data = $this->export(3);
        $this->assertTrue($data->gtabs['hasorphans']);
        $this->assertSame(['Bloc 2 caché', 'Bloc 4'], array_map(fn($o) => strip_tags($o['name']), $data->gtabs['orphans']));
    }

    /**
     * Resume where I left off: last section consulted, and its last subsection opened.
     */
    public function test_resume(): void {
        $this->setUser($this->student);
        $this->assertNull($this->export(null)->gresume, 'Nothing consulted yet.');

        $this->export(1);
        $sectionid = get_fast_modinfo($this->course->id)->get_section_info(1)->id;
        $this->assertEquals($sectionid, get_user_preferences('format_sectiontabs_last_' . $this->course->id));
        set_user_preference('format_sectiontabs_lastsub_' . $this->course->id, $this->subcmids[1]);

        $resume = $this->export(null)->gresume;
        $this->assertSame('Bloc 1', $resume['sectionname']);
        $this->assertSame('Exercices', $resume['subname']);
        $this->assertStringEndsWith('#module-' . $this->subcmids[1], $resume['url']);

        // A subsection of another section is not offered.
        set_user_preference('format_sectiontabs_lastsub_' . $this->course->id, $this->activities[0][0]);
        $this->assertNull($this->export(0)->gresume['subname']);

        // Not offered when the option is off.
        course_get_format($this->course->id)->update_course_format_options(['gtabsresume' => 0]);
        $this->assertNull($this->export(null)->gresume);
    }

    /**
     * The place to resume, read for another user than the current one (as the dashboard of a theme does).
     */
    public function test_get_resume_for_another_user(): void {
        $modinfo = get_fast_modinfo($this->course->id);
        $key = '_' . $this->course->id;
        set_user_preference('format_sectiontabs_last' . $key, $modinfo->get_section_info(1)->id, $this->student);
        set_user_preference('format_sectiontabs_lastsub' . $key, $this->subcmids[1], $this->student);
        $this->setAdminUser();

        $resume = course_get_format($this->course->id)->get_resume((int)$this->student->id);
        $this->assertSame('Bloc 1', $resume['sectionname']);
        $this->assertSame('Exercices', $resume['subname']);
        $this->assertStringEndsWith('#module-' . $this->subcmids[1], $resume['url']->out(false));
        $this->assertNull(course_get_format($this->course->id)->get_resume(), 'Nothing consulted by the admin.');

        // A section hidden from the student is not offered to them, even if the admin can see it.
        set_user_preference('format_sectiontabs_last' . $key, $modinfo->get_section_info(2)->id, $this->student);
        $this->assertNull(course_get_format($this->course->id)->get_resume((int)$this->student->id));
    }

    /**
     * The preference of the last subsection is declared for the browser, for this pattern only.
     */
    public function test_user_preference_definition(): void {
        $definition = \core_user::get_preference_definition('format_sectiontabs_lastsub_42');
        $this->assertSame(PARAM_INT, $definition['type']);
        $this->expectException(\coding_exception::class);
        \core_user::get_preference_definition('format_sectiontabs_last_42');
    }
}
