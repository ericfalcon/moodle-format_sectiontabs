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
 * Tests of the format class: colours, custom tabs, sections without a tab, course form.
 *
 * Written for PHPUnit 9 (Moodle 4.5) and 11 (Moodle 5.x): attributes and annotations, static data providers.
 *
 * @package    format_sectiontabs
 * @category   test
 * @copyright  2026 Eric Falcon
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \format_sectiontabs
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\format_sectiontabs::class)]
final class format_test extends \advanced_testcase {
    /**
     * Load the format library.
     */
    public static function setUpBeforeClass(): void {
        global $CFG;
        require_once($CFG->dirroot . '/course/format/sectiontabs/lib.php');
        parent::setUpBeforeClass();
    }

    /**
     * Colours accepted by the course and site settings.
     *
     * @return array
     */
    public static function colour_provider(): array {
        return [
            'six digits' => ['#2559A8', '#2559a8'],
            'three digits' => ['#abc', '#abc'],
            'without hash' => ['1b5e20', '#1b5e20'],
            'spaces' => ['  #ECB21E ', '#ecb21e'],
            'empty' => ['', null],
            'name' => ['red', null],
            'injection' => ['red;}</style><script>', null],
            'quote' => ['#000" onmouseover="x', null],
        ];
    }

    /**
     * Cleaning of the colours.
     *
     * @dataProvider colour_provider
     * @param string $value
     * @param string|null $expected
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('colour_provider')]
    public function test_clean_colour(string $value, ?string $expected): void {
        $this->assertSame($expected, \format_sectiontabs::clean_colour($value));
    }

    /**
     * The text colour keeps a readable contrast on the tab colour.
     */
    public function test_get_text_colour_for(): void {
        $this->assertSame('#ffffff', \format_sectiontabs::get_text_colour_for('#0f6cbf'));
        $this->assertSame('#ffffff', \format_sectiontabs::get_text_colour_for('#1b5e20'));
        $this->assertSame('#1d2125', \format_sectiontabs::get_text_colour_for('#ecb21e'));
        $this->assertSame('#1d2125', \format_sectiontabs::get_text_colour_for('#ffffff'));
    }

    /**
     * Colour priority: course setting, else site setting, else theme colour.
     */
    public function test_tab_colour_priority(): void {
        $this->resetAfterTest();
        $course = $this->getDataGenerator()->create_course(['format' => 'sectiontabs']);

        set_config('activecolor', '', 'format_sectiontabs');
        $format = course_get_format($course->id);
        $this->assertNull($format->get_tab_colour('activecolor'));
        $this->assertStringNotContainsString('--gtabs-active:', $format->get_tab_style());
        $this->assertStringContainsString('--gtabs-theme:', $format->get_tab_style());

        set_config('activecolor', '#ecb21e', 'format_sectiontabs');
        $format = course_get_format($course->id);
        $this->assertSame('#ecb21e', $format->get_tab_colour('activecolor'));
        $this->assertStringContainsString('--gtabs-active:#ecb21e;--gtabs-active-text:#1d2125;', $format->get_tab_style());

        $format->update_course_format_options(['gtabsactivecolor' => '#1b5e20']);
        $format = course_get_format($course->id);
        $this->assertSame('#1b5e20', $format->get_tab_colour('activecolor'));
        $this->assertStringContainsString('--gtabs-active:#1b5e20;--gtabs-active-text:#ffffff;', $format->get_tab_style());
    }

    /**
     * The custom tabs are saved by section id, and read back cleaned.
     */
    public function test_custom_tabs_roundtrip(): void {
        $this->resetAfterTest();
        $course = $this->getDataGenerator()->create_course(['format' => 'sectiontabs', 'numsections' => 3]);
        $format = course_get_format($course->id);
        $sections = get_fast_modinfo($course->id)->get_section_info_all();

        $format->set_custom_tabs([
            ['label' => ' Accueil ', 'sectionids' => [$sections[0]->id], 'url' => ''],
            ['label' => 'Cours <b>1</b>', 'sectionids' => [$sections[1]->id, $sections[2]->id], 'url' => ''],
            ['label' => 'Site', 'sectionids' => [], 'url' => 'https://example.com/a'],
            ['label' => 'Piège', 'sectionids' => [], 'url' => 'javascript:alert(1)'],
            ['label' => '', 'sectionids' => [$sections[3]->id], 'url' => ''],
        ]);
        $tabs = course_get_format($course->id)->get_custom_tabs();

        $this->assertCount(4, $tabs);
        $this->assertSame('Accueil', $tabs[0]['label']);
        $this->assertSame('Cours <b>1</b>', $tabs[1]['label']);
        $this->assertSame([(int)$sections[1]->id, (int)$sections[2]->id], $tabs[1]['sectionids']);
        $this->assertSame('https://example.com/a', $tabs[2]['url']);
        $this->assertSame('', $tabs[3]['url'], 'Only http(s) addresses are kept.');
    }

    /**
     * Sections that no custom tab opens.
     */
    public function test_sections_without_tab(): void {
        $this->resetAfterTest();
        $course = $this->getDataGenerator()->create_course(['format' => 'sectiontabs', 'numsections' => 3]);
        $format = course_get_format($course->id);
        $this->assertSame([], $format->get_sections_without_tab(), 'Nothing to report before custom tabs are saved.');

        $sections = get_fast_modinfo($course->id)->get_section_info_all();
        $format->set_custom_tabs([
            ['label' => 'A', 'sectionids' => [$sections[0]->id, $sections[1]->id], 'url' => ''],
        ]);
        $orphans = course_get_format($course->id)->get_sections_without_tab();
        $this->assertSame([2, 3], array_map(fn($section) => (int)$section->section, $orphans));
    }

    /**
     * The tab blocks of the course form are saved as custom tabs, blocks without a name being dropped.
     */
    public function test_update_course_format_options_from_form(): void {
        $this->resetAfterTest();
        $course = $this->getDataGenerator()->create_course(['format' => 'sectiontabs', 'numsections' => 2]);
        $sections = get_fast_modinfo($course->id)->get_section_info_all();

        course_get_format($course->id)->update_course_format_options([
            'gtabsmode' => \format_sectiontabs::TABS_CUSTOM,
            'gtabname_0' => 'Accueil',
            'gtabsections_0' => [$sections[0]->id],
            'gtaburl_0' => '',
            'gtabname_1' => '',
            'gtabsections_1' => [$sections[1]->id],
            'gtaburl_1' => '',
            'gtabname_2' => 'Ressources',
            'gtabsections_2' => [],
            'gtaburl_2' => 'https://example.com/r',
        ]);
        $format = course_get_format($course->id);
        $tabs = $format->get_custom_tabs();
        $this->assertSame(\format_sectiontabs::TABS_CUSTOM, (int)$format->get_course()->gtabsmode);
        $this->assertSame(['Accueil', 'Ressources'], array_column($tabs, 'label'));
        $this->assertSame('https://example.com/r', $tabs[1]['url']);
    }

    /**
     * Validation of the tab blocks and colours of the course form.
     */
    public function test_edit_form_validation(): void {
        $this->resetAfterTest();
        $course = $this->getDataGenerator()->create_course(['format' => 'sectiontabs']);
        $format = course_get_format($course->id);
        $sectionid = get_fast_modinfo($course->id)->get_section_info(0)->id;

        $errors = $format->edit_form_validation([
            'id' => $course->id,
            'gtabsmode' => \format_sectiontabs::TABS_CUSTOM,
            'gtabname_0' => 'Sans cible', 'gtabsections_0' => [], 'gtaburl_0' => '',
            'gtabname_1' => 'Les deux', 'gtabsections_1' => [$sectionid], 'gtaburl_1' => 'https://example.com',
            'gtabname_2' => 'Adresse', 'gtabsections_2' => [], 'gtaburl_2' => 'ftp://example.com',
            'gtabname_3' => '', 'gtabsections_3' => [$sectionid], 'gtaburl_3' => '',
            'gtabsactivecolor' => 'rouge',
            'gtabsactiveborder' => '#123456',
        ], [], []);

        $this->assertArrayHasKey('gtabsections_0', $errors);
        $this->assertArrayHasKey('gtaburl_1', $errors);
        $this->assertArrayHasKey('gtaburl_2', $errors);
        $this->assertArrayNotHasKey('gtabname_3', $errors, 'A block without a name is ignored.');
        $this->assertArrayNotHasKey('gtabsections_3', $errors);
        $this->assertArrayHasKey('gtabsactivecolor', $errors);
        $this->assertArrayNotHasKey('gtabsactiveborder', $errors);
    }
}
