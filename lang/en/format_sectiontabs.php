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
 * English strings.
 *
 * @package    format_sectiontabs
 * @copyright  2026 Eric Falcon
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['activeborder'] = 'Active tab underline colour';
$string['activeborder_desc'] = 'Colour of the line under the active tab, for every course that does not set its own. Empty: the colour of the active tab, darkened.';
$string['activecolor'] = 'Active tab colour';
$string['activecolor_desc'] = 'Colour of the tab of the displayed section, for every course that does not set its own. Empty: the main colour of the theme (currently {$a}); with the Épure theme, its brand colour, which also follows its dark mode, display preferences and company colours. The text colour is chosen automatically to stay readable.';
$string['currentsection'] = 'Current section';
$string['customtabs_intro'] = 'One block per tab, in the order of the tab bar: its name, then the sections it opens (or a web address). A tab can group several sections: it opens the first one the user can access and stays highlighted on each of them. To delete a tab, empty its name. To add tabs, fill the empty blocks at the end (save to get more). Renaming a section does not break the tabs.';
$string['gtabsactiveborder'] = 'Active tab underline colour';
$string['gtabsactiveborder_help'] = 'Colour of the line under the active tab, as a hexadecimal code (for example #1d3f75). Empty: the colour set for the site, else the colour of the active tab, darkened.';
$string['gtabsactivecolor'] = 'Active tab colour';
$string['gtabsactivecolor_help'] = 'Colour of the tab of the displayed section, as a hexadecimal code (for example #2559a8). Empty: the colour set for the site, else the main colour of the theme (with Épure, its brand colour). The text colour is chosen automatically to stay readable.';
$string['gtabshome'] = 'Course home shows only the general section';
$string['gtabshome_help'] = 'If enabled, the course home page only displays the general section, like a "one section per tab" format. Each other section is displayed on its own page. In editing mode the full list of sections is still displayed so that sections can be added and moved.';
$string['gtabsleftmenu'] = 'Section menu on the left';
$string['gtabsleftmenu_help'] = 'If enabled, the list of the course sections is displayed on the left of the course content, in addition to the tab bar. It is hidden on small screens, where the tab bar remains.';
$string['gtabsmode'] = 'Tabs';
$string['gtabsmode_custom'] = 'Custom tabs';
$string['gtabsmode_help'] = 'One tab per section: the tab bar lists every section of the course.

Custom tabs: you define the tabs yourself, and which sections each of them opens, in the blocks displayed below this option. The section menu on the left still lists every section.';
$string['gtabsmode_sections'] = 'One tab per section';
$string['gtabsnav'] = 'Previous / Next buttons';
$string['gtabsnav_help'] = 'If enabled, a bar with "Previous" and "Next" buttons is displayed at the bottom of each section. While the section has subsections left, the buttons open the previous or next subsection; on the last subsection, "Next" opens the next section. Sections the user cannot access are skipped. This bar replaces the section navigation links of Moodle.';
$string['gtabsshortnames'] = 'Short tab names';
$string['gtabsshortnames_help'] = 'If enabled, only the part of the section name before " : " is used as tab name. For example "Block 1 : Essential acts" becomes "Block 1". The full name is displayed when hovering the tab.';
$string['gtabssubtabs'] = 'Subsections as tabs';
$string['gtabssubtabs_help'] = 'If enabled, on a section page the subsections are displayed as tabs: only the subsection of the selected tab is displayed. In editing mode an extra "Show all" tab displays every subsection, to move them.';
$string['hiddenfromstudents'] = 'hidden from students';
$string['hidefromothers'] = 'Hide';
$string['invalidcolour'] = 'Enter a hexadecimal colour code, for example #ecb21e.';
$string['managetabs_newcourse'] = 'Save the course, then come back to these settings to define the tabs.';
$string['navlabel'] = 'Previous and next';
$string['navnextsection'] = 'Next section';
$string['navnextsubsection'] = 'Next';
$string['navprevsection'] = 'Previous section';
$string['navprevsubsection'] = 'Previous';
$string['newsection'] = 'New section';
$string['notavailable'] = 'not available';
$string['opensinnewwindow'] = 'opens in a new window';
$string['page-course-view-sectiontabs'] = 'Any course main page in tabs and section menu format';
$string['page-course-view-sectiontabs-x'] = 'Any course page in tabs and section menu format';
$string['plugin_description'] = 'One section per page, with a tab bar and a section menu giving access to every section, and subsections displayed as tabs.';
$string['pluginname'] = 'Tabs and section menu';
$string['privacy:metadata'] = 'The Tabs and section menu format plugin does not store any personal data.';
$string['section0name'] = 'General';
$string['sectionname'] = 'Section';
$string['showfromothers'] = 'Show';
$string['sidemenunav'] = 'Course sections menu';
$string['subtabsnav'] = 'Subsections';
$string['subtabsshowall'] = 'Show all';
$string['tabrow_name'] = 'Tab {$a}: name';
$string['tabrow_name_help'] = 'Name displayed on the tab. Empty the name to delete the tab.';
$string['tabrow_sections'] = 'Tab {$a}: sections';
$string['tabrow_url'] = 'Tab {$a}: or web address';
$string['tabsections'] = 'Sections';
$string['tabsections_help'] = 'Sections opened by this tab. With several sections, the tab opens the first one the user can access and stays highlighted on each of them. A tab is not displayed to a user who cannot see any of its sections.';
$string['tabsections_none'] = 'No section selected';
$string['tabsections_placeholder'] = 'Search for a section';
$string['tabsnav'] = 'Course sections';
$string['tabtarget_both'] = 'Choose either sections or a web address, not both.';
$string['tabtarget_required'] = 'Choose at least one section, or enter a web address.';
$string['taburl'] = 'Or web address';
$string['taburl_help'] = 'Instead of sections, the tab can open a web address (starting with http:// or https://). It opens in a new window.';
$string['taburl_invalid'] = 'Enter a web address starting with http:// or https://.';
$string['themecolour_placeholder'] = 'Theme colour';
