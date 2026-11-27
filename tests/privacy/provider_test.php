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
 * Tests of the privacy provider: the preferences of "Resume where I left off".
 *
 * @package    format_sectiontabs
 * @category   test
 * @copyright  2026 Eric Falcon
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \format_sectiontabs\privacy\provider
 */
#[\PHPUnit\Framework\Attributes\CoversClass(provider::class)]
final class provider_test extends \core_privacy\tests\provider_testcase {
    /**
     * The two preferences are declared.
     */
    public function test_get_metadata(): void {
        $items = provider::get_metadata(new collection('format_sectiontabs'))->get_collection();
        $this->assertSame(
            ['format_sectiontabs_last', 'format_sectiontabs_lastsub'],
            array_map(fn($item) => $item->get_name(), $items)
        );
    }

    /**
     * The preferences of the user are exported, and only theirs.
     */
    public function test_export_user_preferences(): void {
        $this->resetAfterTest();
        $user = $this->getDataGenerator()->create_user();
        $other = $this->getDataGenerator()->create_user();
        set_user_preference('format_sectiontabs_last_12', 345, $user);
        set_user_preference('format_sectiontabs_lastsub_12', 678, $user);
        set_user_preference('format_sectiontabs_last_99', 1, $other);
        set_user_preference('some_other_preference', 'x', $user);

        provider::export_user_preferences($user->id);
        $writer = writer::with_context(\context_system::instance());
        $this->assertTrue($writer->has_any_data());
        $prefs = (array)$writer->get_user_preferences('format_sectiontabs');
        $this->assertEqualsCanonicalizing(
            ['format_sectiontabs_last_12', 'format_sectiontabs_lastsub_12'],
            array_keys($prefs)
        );
        $this->assertEquals(345, $prefs['format_sectiontabs_last_12']->value);
        $this->assertEquals(678, $prefs['format_sectiontabs_lastsub_12']->value);
    }
}
