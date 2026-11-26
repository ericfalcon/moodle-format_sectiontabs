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
 * Main class of the "Tabs and section menu" course format.
 *
 * It behaves exactly like the core "Custom sections" (topics) format and adds
 * an automatic tab bar, built from the course sections, above every course page.
 *
 * @package    format_sectiontabs
 * @copyright  2026 Eric Falcon
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/format/lib.php');
require_once($CFG->dirroot . '/course/format/topics/lib.php');

use core\lang_string;

/**
 * Course format class.
 */
class format_sectiontabs extends format_topics {
    /** @var int One tab per section. */
    public const TABS_SECTIONS = 0;

    /** @var int Tabs defined by the teacher. */
    public const TABS_CUSTOM = 1;

    /** @var string Primary colour of Moodle (Boost, Classic) when the theme sets none. */
    public const DEFAULT_THEME_COLOUR = '#0f6cbf';

    /** @var int Number of empty tab blocks offered in the course form to add tabs. */
    public const EMPTY_TAB_BLOCKS = 3;

    /**
     * Course format options: the topics ones plus the tab bar options.
     *
     * @param bool $foreditform
     * @return array
     */
    public function course_format_options($foreditform = false) {
        $options = parent::course_format_options($foreditform);

        $options['gtabsmode'] = [
            'default' => self::TABS_SECTIONS,
            'type' => PARAM_INT,
        ];
        $options['gtabscustom'] = [
            'default' => '',
            'type' => PARAM_TEXT,
        ];
        $options['gtabshome'] = [
            'default' => 1,
            'type' => PARAM_BOOL,
        ];
        $options['gtabsshortnames'] = [
            'default' => 0,
            'type' => PARAM_BOOL,
        ];
        $options['gtabsleftmenu'] = [
            'default' => 1,
            'type' => PARAM_BOOL,
        ];
        $options['gtabssubtabs'] = [
            'default' => 1,
            'type' => PARAM_BOOL,
        ];
        $options['gtabsnav'] = [
            'default' => 1,
            'type' => PARAM_BOOL,
        ];
        $options['gtabsactivecolor'] = [
            'default' => '',
            'type' => PARAM_TEXT,
        ];
        $options['gtabsactiveborder'] = [
            'default' => '',
            'type' => PARAM_TEXT,
        ];

        if ($foreditform) {
            // The custom tabs option is not edited as is: create_edit_form_elements() adds one block of fields per tab.
            unset($options['gtabscustom']);
            $options['gtabsmode'] += [
                'label' => new lang_string('gtabsmode', 'format_sectiontabs'),
                'help' => 'gtabsmode',
                'help_component' => 'format_sectiontabs',
                'element_type' => 'select',
                'element_attributes' => [
                    [
                        self::TABS_SECTIONS => new lang_string('gtabsmode_sections', 'format_sectiontabs'),
                        self::TABS_CUSTOM => new lang_string('gtabsmode_custom', 'format_sectiontabs'),
                    ],
                ],
            ];
            $options['gtabshome'] += [
                'label' => new lang_string('gtabshome', 'format_sectiontabs'),
                'help' => 'gtabshome',
                'help_component' => 'format_sectiontabs',
                'element_type' => 'advcheckbox',
            ];
            $options['gtabsshortnames'] += [
                'label' => new lang_string('gtabsshortnames', 'format_sectiontabs'),
                'help' => 'gtabsshortnames',
                'help_component' => 'format_sectiontabs',
                'element_type' => 'advcheckbox',
            ];
            $options['gtabsleftmenu'] += [
                'label' => new lang_string('gtabsleftmenu', 'format_sectiontabs'),
                'help' => 'gtabsleftmenu',
                'help_component' => 'format_sectiontabs',
                'element_type' => 'advcheckbox',
            ];
            $options['gtabssubtabs'] += [
                'label' => new lang_string('gtabssubtabs', 'format_sectiontabs'),
                'help' => 'gtabssubtabs',
                'help_component' => 'format_sectiontabs',
                'element_type' => 'advcheckbox',
            ];
            $options['gtabsnav'] += [
                'label' => new lang_string('gtabsnav', 'format_sectiontabs'),
                'help' => 'gtabsnav',
                'help_component' => 'format_sectiontabs',
                'element_type' => 'advcheckbox',
            ];
            $placeholder = get_string('themecolour_placeholder', 'format_sectiontabs');
            foreach (['gtabsactivecolor', 'gtabsactiveborder'] as $name) {
                $options[$name] += [
                    'label' => new lang_string($name, 'format_sectiontabs'),
                    'help' => $name,
                    'help_component' => 'format_sectiontabs',
                    'element_type' => 'text',
                    'element_attributes' => [
                        ['size' => 18, 'maxlength' => 7, 'placeholder' => $placeholder],
                    ],
                ];
            }
        }
        return $options;
    }

    /**
     * Keep the custom tabs option, which is built from the tab fields of the course form.
     *
     * @param array $rawdata
     * @param int|null $sectionid
     * @return array
     */
    protected function validate_format_options(array $rawdata, ?int $sectionid = null): array {
        $data = parent::validate_format_options($rawdata, $sectionid);
        if (!$sectionid && array_key_exists('gtabscustom', $rawdata)) {
            $data['gtabscustom'] = clean_param((string)$rawdata['gtabscustom'], PARAM_TEXT);
        }
        return $data;
    }

    /**
     * Add the course format options to the course form, with the custom tabs fields after the "Tabs" option.
     *
     * @param MoodleQuickForm $mform
     * @param bool $forsection
     * @return array
     */
    public function create_edit_form_elements(&$mform, $forsection = false) {
        $elements = parent::create_edit_form_elements($mform, $forsection);
        if ($forsection || !$mform->elementExists('gtabsmode')) {
            return $elements;
        }
        // The returned elements are moved by the course form into the "Course format" section, in this order.
        $extra = $this->create_custom_tabs_elements($mform);
        $position = count($elements);
        foreach ($elements as $index => $element) {
            if ($element->getName() === 'gtabsmode') {
                $position = $index + 1;
            }
        }
        array_splice($elements, $position, 0, $extra);
        return $elements;
    }

    /**
     * Fields of the custom tabs: name, sections and web address of each tab, plus empty blocks to add tabs.
     *
     * @param MoodleQuickForm $mform
     * @return array the added elements
     */
    protected function create_custom_tabs_elements(MoodleQuickForm $mform): array {
        $elements = [];
        if (!$this->courseid || $this->courseid == SITEID) {
            $elements[] = $mform->addElement(
                'static',
                'gtabsnewcourse',
                '',
                get_string('managetabs_newcourse', 'format_sectiontabs')
            );
            $mform->hideIf('gtabsnewcourse', 'gtabsmode', 'neq', self::TABS_CUSTOM);
            return $elements;
        }

        // Sections of the course (subsections are displayed as tabs inside their section).
        $hiddenstr = get_string('hiddenfromstudents', 'format_sectiontabs');
        $sectionnames = [];
        $options = [];
        foreach (get_fast_modinfo($this->courseid)->get_listed_section_info_all() as $section) {
            $name = html_entity_decode(strip_tags($this->get_section_name($section)), ENT_QUOTES, 'UTF-8');
            $sectionnames[$section->id] = $name;
            $options[$section->id] = $name . ($section->visible ? '' : " ($hiddenstr)");
        }

        // Current tabs; when none are defined yet, propose one tab per section.
        $tabs = $this->get_custom_tabs();
        if (!$tabs) {
            foreach ($sectionnames as $sectionid => $name) {
                $tabs[] = ['label' => $name, 'sectionids' => [$sectionid], 'url' => ''];
            }
        }
        $count = count($tabs) + self::EMPTY_TAB_BLOCKS;

        $elements[] = $mform->addElement(
            'static',
            'gtabsintro',
            '',
            html_writer::div(get_string('customtabs_intro', 'format_sectiontabs'), 'alert alert-info mb-0')
        );
        $mform->hideIf('gtabsintro', 'gtabsmode', 'neq', self::TABS_CUSTOM);

        for ($i = 0; $i < $count; $i++) {
            $tab = $tabs[$i] ?? ['label' => '', 'sectionids' => [], 'url' => ''];
            $num = $i + 1;

            $elements[] = $mform->addElement(
                'text',
                "gtabname_$i",
                get_string('tabrow_name', 'format_sectiontabs', $num),
                ['size' => 40]
            );
            $mform->setType("gtabname_$i", PARAM_TEXT);
            $mform->setDefault("gtabname_$i", $tab['label']);

            $elements[] = $mform->addElement(
                'autocomplete',
                "gtabsections_$i",
                get_string('tabrow_sections', 'format_sectiontabs', $num),
                $options,
                [
                    'multiple' => true,
                    'noselectionstring' => get_string('tabsections_none', 'format_sectiontabs'),
                    'placeholder' => get_string('tabsections_placeholder', 'format_sectiontabs'),
                ]
            );
            $mform->setType("gtabsections_$i", PARAM_INT);
            $mform->setDefault("gtabsections_$i", $tab['sectionids']);

            $elements[] = $mform->addElement(
                'text',
                "gtaburl_$i",
                get_string('tabrow_url', 'format_sectiontabs', $num),
                ['size' => 50, 'placeholder' => 'https://']
            );
            $mform->setType("gtaburl_$i", PARAM_RAW_TRIMMED);
            $mform->setDefault("gtaburl_$i", $tab['url']);

            if ($i === 0) {
                $mform->addHelpButton("gtabname_$i", 'tabrow_name', 'format_sectiontabs');
                $mform->addHelpButton("gtabsections_$i", 'tabsections', 'format_sectiontabs');
                $mform->addHelpButton("gtaburl_$i", 'taburl', 'format_sectiontabs');
            }
            foreach (["gtabname_$i", "gtabsections_$i", "gtaburl_$i"] as $name) {
                $mform->hideIf($name, 'gtabsmode', 'neq', self::TABS_CUSTOM);
            }
        }
        return $elements;
    }

    /**
     * The custom tabs entered in the course form.
     *
     * @param array $data submitted data
     * @return array|null list of ['label', 'sectionids', 'url', 'row'], or null when the form has no tab fields
     */
    protected static function get_submitted_custom_tabs(array $data): ?array {
        if (!array_key_exists('gtabname_0', $data)) {
            return null;
        }
        $tabs = [];
        for ($i = 0; array_key_exists("gtabname_$i", $data); $i++) {
            $tabs[] = [
                'row' => $i + 1,
                'label' => trim((string)$data["gtabname_$i"]),
                'sectionids' => array_values(array_filter(array_map('intval', (array)($data["gtabsections_$i"] ?? [])))),
                'url' => trim((string)($data["gtaburl_$i"] ?? '')),
            ];
        }
        return $tabs;
    }

    /**
     * Save the course format options, including the custom tabs entered in the course form.
     *
     * @param stdClass|array $data
     * @param stdClass|null $oldcourse
     * @return bool
     */
    public function update_course_format_options($data, $oldcourse = null) {
        $data = (array)$data;
        $tabs = self::get_submitted_custom_tabs($data);
        if ($tabs !== null) {
            $tabs = array_filter($tabs, fn($tab) => $tab['label'] !== '');
            foreach ($tabs as $i => $tab) {
                $tabs[$i]['url'] = $tab['url'] !== '' ? clean_param($tab['url'], PARAM_URL) : '';
            }
            $data['gtabscustom'] = self::encode_custom_tabs($tabs);
        }
        return parent::update_course_format_options($data, $oldcourse);
    }

    /**
     * Check the custom tabs and the tab colours.
     *
     * @param array $data
     * @param array $files
     * @param array $errors
     * @return array
     */
    public function edit_form_validation($data, $files, $errors) {
        $formerrors = parent::edit_form_validation($data, $files, $errors);
        $tabs = self::get_submitted_custom_tabs($data);
        if ($tabs !== null && (int)($data['gtabsmode'] ?? self::TABS_SECTIONS) === self::TABS_CUSTOM) {
            foreach ($tabs as $i => $tab) {
                if ($tab['label'] === '') {
                    // A block without a name is ignored: emptying the name deletes the tab.
                    continue;
                }
                if (!$tab['sectionids'] && $tab['url'] === '') {
                    $formerrors["gtabsections_$i"] = get_string('tabtarget_required', 'format_sectiontabs');
                } else if ($tab['sectionids'] && $tab['url'] !== '') {
                    $formerrors["gtaburl_$i"] = get_string('tabtarget_both', 'format_sectiontabs');
                }
                $validurl = preg_match('~^https?://~i', $tab['url']) && clean_param($tab['url'], PARAM_URL) !== '';
                if ($tab['url'] !== '' && !$validurl) {
                    $formerrors["gtaburl_$i"] = get_string('taburl_invalid', 'format_sectiontabs');
                }
            }
        }
        foreach (['gtabsactivecolor', 'gtabsactiveborder'] as $name) {
            $value = trim($data[$name] ?? '');
            if ($value !== '' && self::clean_colour($value) === null) {
                $formerrors[$name] = get_string('invalidcolour', 'format_sectiontabs');
            }
        }
        return $formerrors;
    }

    /**
     * The custom tabs of the course.
     *
     * @return array list of ['label' => string, 'sectionids' => int[], 'url' => string]
     */
    public function get_custom_tabs(): array {
        $data = json_decode((string)($this->get_course()->gtabscustom ?? ''), true);
        if (!is_array($data)) {
            return [];
        }
        $tabs = [];
        foreach ($data as $tab) {
            if (!is_array($tab) || trim((string)($tab['label'] ?? '')) === '') {
                continue;
            }
            $url = clean_param((string)($tab['url'] ?? ''), PARAM_URL);
            $tabs[] = [
                'label' => trim((string)$tab['label']),
                'sectionids' => array_values(array_unique(array_filter(array_map('intval', (array)($tab['sectionids'] ?? []))))),
                'url' => preg_match('~^https?://~i', $url) ? $url : '',
            ];
        }
        return $tabs;
    }

    /**
     * Save the custom tabs of the course.
     *
     * @param array $tabs list of ['label' => string, 'sectionids' => int[], 'url' => string]
     */
    public function set_custom_tabs(array $tabs): void {
        $this->update_course_format_options(['gtabscustom' => self::encode_custom_tabs($tabs)]);
        rebuild_course_cache($this->courseid, true);
    }

    /**
     * JSON value of the custom tabs option.
     *
     * @param array $tabs list of ['label' => string, 'sectionids' => int[], 'url' => string]
     * @return string
     */
    protected static function encode_custom_tabs(array $tabs): string {
        $clean = [];
        foreach ($tabs as $tab) {
            $clean[] = [
                'label' => trim((string)$tab['label']),
                'sectionids' => array_values(array_map('intval', $tab['sectionids'] ?? [])),
                'url' => (string)($tab['url'] ?? ''),
            ];
        }
        // JSON_HEX_TAG keeps the "<" and ">" of the labels through the PARAM_TEXT cleaning.
        return json_encode($clean, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG);
    }

    /**
     * Return a valid hexadecimal colour or null.
     *
     * @param string|null $value
     * @return string|null
     */
    public static function clean_colour(?string $value): ?string {
        $value = trim((string)$value);
        if ($value !== '' && $value[0] !== '#') {
            $value = '#' . $value;
        }
        return preg_match('/^#([0-9a-f]{3}|[0-9a-f]{6})$/i', $value) ? strtolower($value) : null;
    }

    /**
     * Text colour readable on a background colour (WCAG contrast): white or near black.
     *
     * @param string $background hexadecimal colour
     * @return string
     */
    public static function get_text_colour_for(string $background): string {
        $hex = ltrim(self::clean_colour($background) ?? self::DEFAULT_THEME_COLOUR, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        $luminance = function (string $hex): float {
            $channels = [];
            foreach ([0, 2, 4] as $offset) {
                $value = hexdec(substr($hex, $offset, 2)) / 255;
                $channels[] = $value <= 0.03928 ? $value / 12.92 : (($value + 0.055) / 1.055) ** 2.4;
            }
            return 0.2126 * $channels[0] + 0.7152 * $channels[1] + 0.0722 * $channels[2];
        };
        $bg = $luminance($hex);
        $dark = $luminance('1d2125');
        $withwhite = 1.05 / ($bg + 0.05);
        $withdark = ($bg + 0.05) / ($dark + 0.05);
        return $withwhite >= $withdark ? '#ffffff' : '#1d2125';
    }

    /**
     * CSS custom properties of the tab colours.
     *
     * --gtabs-theme(-text): main colour of the theme of the page and its readable text, the default.
     * --gtabs-active(-text) and --gtabs-active-border: only when a colour is set for the course or the site.
     * With Épure, styles.css prefers its brand colours (--epure-brand, --epure-on-brand) to --gtabs-theme:
     * they follow its dark mode, display preferences and company colours.
     *
     * @return string
     */
    public function get_tab_style(): string {
        $theme = self::get_theme_colour();
        $style = '--gtabs-theme:' . $theme . ';--gtabs-theme-text:' . self::get_text_colour_for($theme) . ';';
        if ($active = $this->get_tab_colour('activecolor')) {
            $style .= '--gtabs-active:' . $active . ';--gtabs-active-text:' . self::get_text_colour_for($active) . ';';
        }
        if ($border = $this->get_tab_colour('activeborder')) {
            $style .= '--gtabs-active-border:' . $border . ';';
        }
        return $style;
    }

    /**
     * Colour set for a tab element: the course setting, else the site setting, else null (theme colours).
     *
     * @param string $name 'activecolor' or 'activeborder'
     * @return string|null
     */
    public function get_tab_colour(string $name): ?string {
        $course = $this->get_course();
        return self::clean_colour($course->{'gtabs' . $name} ?? null)
            ?? self::clean_colour(get_config('format_sectiontabs', $name));
    }

    /**
     * Main colour of the theme of the page: its "brandcolor" setting (Boost, Moove, Classic, Épure...), or the one
     * of a parent theme, else the default primary colour of Moodle.
     *
     * @return string hexadecimal colour
     */
    public static function get_theme_colour(): string {
        global $PAGE;
        $names = [];
        if (!empty($PAGE) && $PAGE->theme) {
            $names = array_merge([$PAGE->theme->name], $PAGE->theme->parents ?? []);
        }
        foreach ($names as $name) {
            if ($colour = self::clean_colour(get_config('theme_' . $name, 'brandcolor'))) {
                return $colour;
            }
        }
        return self::DEFAULT_THEME_COLOUR;
    }

    /**
     * URL of the tab of a section.
     *
     * @param section_info $section
     * @return moodle_url
     */
    public function get_tab_url(section_info $section): moodle_url {
        $course = $this->get_course();
        if ($section->section == 0) {
            $params = ['id' => $course->id];
            if (empty($course->gtabshome)) {
                $params['section'] = 0;
            }
            return new moodle_url('/course/view.php', $params);
        }
        return new moodle_url('/course/section.php', ['id' => $section->id]);
    }
}

/**
 * Implements callback inplace_editable() allowing to edit values in-place.
 *
 * @param string $itemtype
 * @param int $itemid
 * @param mixed $newvalue
 * @return \core\output\inplace_editable
 */
function format_sectiontabs_inplace_editable($itemtype, $itemid, $newvalue) {
    global $DB, $CFG;
    require_once($CFG->dirroot . '/course/lib.php');
    if ($itemtype === 'sectionname' || $itemtype === 'sectionnamenl') {
        $section = $DB->get_record_sql(
            'SELECT s.* FROM {course_sections} s JOIN {course} c ON s.course = c.id WHERE s.id = ? AND c.format = ?',
            [$itemid, 'sectiontabs'],
            MUST_EXIST
        );
        return course_get_format($section->course)->inplace_editable_update_section_name($section, $itemtype, $newvalue);
    }
}
