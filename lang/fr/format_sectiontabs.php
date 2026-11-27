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
 * Chaînes en français.
 *
 * @package    format_sectiontabs
 * @copyright  2026 Eric Falcon
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['activeborder'] = 'Couleur du soulignement de l\'onglet actif';
$string['activeborder_desc'] = 'Couleur du trait sous l\'onglet actif, pour tous les cours qui n\'en définissent pas. Vide : la couleur de l\'onglet actif, assombrie.';
$string['activecolor'] = 'Couleur de l\'onglet actif';
$string['activecolor_desc'] = 'Couleur de l\'onglet de la section affichée, pour tous les cours qui n\'en définissent pas. Vide : la couleur principale du thème (actuellement {$a}) ; avec le thème Épure, sa couleur de marque, qui suit aussi son mode sombre, ses préférences d\'affichage et les couleurs des entreprises. La couleur du texte est choisie automatiquement pour rester lisible.';
$string['completed'] = 'terminé';
$string['currentsection'] = 'Section en cours';
$string['customtabs_intro'] = 'Un bloc par onglet, dans l\'ordre de la barre : son nom, puis les sections qu\'il ouvre (ou une adresse web). Un onglet peut regrouper plusieurs sections : il ouvre la première accessible et reste en surbrillance sur chacune d\'elles. Pour supprimer un onglet, videz son nom. Pour en ajouter, remplissez les blocs vides à la fin (enregistrez pour en obtenir d\'autres). Renommer une section ne casse pas les onglets.';
$string['gtabsactiveborder'] = 'Couleur du soulignement de l\'onglet actif';
$string['gtabsactiveborder_help'] = 'Couleur du trait sous l\'onglet actif, sous forme de code hexadécimal (par exemple #1d3f75). Vide : la couleur définie pour le site, sinon la couleur de l\'onglet actif, assombrie.';
$string['gtabsactivecolor'] = 'Couleur de l\'onglet actif';
$string['gtabsactivecolor_help'] = 'Couleur de l\'onglet de la section affichée, sous forme de code hexadécimal (par exemple #2559a8). Vide : la couleur définie pour le site, sinon la couleur principale du thème (avec Épure, sa couleur de marque). La couleur du texte est choisie automatiquement pour rester lisible.';
$string['gtabshome'] = 'L\'accueil du cours n\'affiche que la section générale';
$string['gtabshome_help'] = 'Si cette option est activée, la page d\'accueil du cours n\'affiche que la section générale, comme un format « une section par onglet ». Chaque autre section s\'affiche sur sa propre page. En mode édition, la liste complète des sections reste affichée pour pouvoir ajouter et déplacer des sections.';
$string['gtabsleftmenu'] = 'Menu des sections à gauche';
$string['gtabsleftmenu_help'] = 'Si cette option est activée, la liste des sections du cours s\'affiche à gauche du contenu, en plus de la barre d\'onglets. Elle est masquée sur les petits écrans, où la barre d\'onglets reste disponible.';
$string['gtabsmode'] = 'Onglets';
$string['gtabsmode_custom'] = 'Onglets personnalisés';
$string['gtabsmode_help'] = 'Un onglet par section : la barre d\'onglets reprend toutes les sections du cours.

Onglets personnalisés : vous définissez vous-même les onglets et les sections que chacun ouvre, dans les blocs affichés sous cette option. Le menu de gauche continue de lister toutes les sections.';
$string['gtabsmode_sections'] = 'Un onglet par section';
$string['gtabsnav'] = 'Boutons Précédent / Suivant';
$string['gtabsnav_help'] = 'Si cette option est activée, une barre avec des boutons « Précédent » et « Suivant » s\'affiche en bas de chaque section. Tant qu\'il reste des sous-sections, les boutons ouvrent la sous-section précédente ou suivante ; sur la dernière sous-section, « Suivant » ouvre la section suivante. Les sections inaccessibles à l\'utilisateur sont sautées. Cette barre remplace les liens de navigation entre sections de Moodle.';
$string['gtabsprogress'] = 'Afficher l\'avancement';
$string['gtabsprogress_help'] = 'Si cette option est activée, une coche marque les onglets, les sections du menu et les sous-sections dont toutes les activités suivies sont achevées par l\'apprenant. Seulement pour les utilisateurs dont l\'achèvement est suivi, quand le suivi d\'achèvement est activé dans le cours.';
$string['gtabsresume'] = 'Bouton « Reprendre où j\'en étais »';
$string['gtabsresume_help'] = 'Si cette option est activée, l\'accueil du cours propose à chaque apprenant un lien vers la dernière section, et sous-section, qu\'il a consultée. La dernière section et la dernière sous-section consultées sont enregistrées dans les préférences de l\'utilisateur.';
$string['gtabsshortnames'] = 'Noms d\'onglets courts';
$string['gtabsshortnames_help'] = 'Si cette option est activée, seule la partie du nom de la section placée avant « : » sert de nom d\'onglet. Par exemple, « Bloc de compétence 1 : Actes essentiels » devient « Bloc de compétence 1 ». Le nom complet s\'affiche au survol de l\'onglet.';
$string['gtabssubtabs'] = 'Sous-sections en onglets';
$string['gtabssubtabs_help'] = 'Si cette option est activée, sur la page d\'une section, les sous-sections s\'affichent en onglets : seule la sous-section de l\'onglet choisi est affichée. En mode édition, un onglet « Tout afficher » permet de voir toutes les sous-sections pour les déplacer.';
$string['hiddenfromstudents'] = 'caché aux étudiants';
$string['hidefromothers'] = 'Cacher';
$string['invalidcolour'] = 'Saisissez un code couleur hexadécimal, par exemple #ecb21e.';
$string['managetabs_newcourse'] = 'Enregistrez le cours, puis revenez dans ces paramètres pour définir les onglets.';
$string['navlabel'] = 'Précédent et suivant';
$string['navnextsection'] = 'Section suivante';
$string['navnextsubsection'] = 'Suivant';
$string['navprevsection'] = 'Section précédente';
$string['navprevsubsection'] = 'Précédent';
$string['newsection'] = 'Nouvelle section';
$string['notavailable'] = 'non disponible';
$string['opensinnewwindow'] = 's\'ouvre dans une nouvelle fenêtre';
$string['page-course-view-sectiontabs'] = 'Toute page principale de cours au format Onglets et menu de sections';
$string['page-course-view-sectiontabs-x'] = 'Toute page de cours au format Onglets et menu de sections';
$string['plugin_description'] = 'Une section par page, avec une barre d\'onglets et un menu des sections donnant accès à chaque section, et les sous-sections affichées en onglets.';
$string['pluginname'] = 'Onglets et menu de sections';
$string['privacy:metadata:preference:last'] = 'Dernière section consultée dans un cours, pour proposer d\'y reprendre.';
$string['privacy:metadata:preference:lastsub'] = 'Dernière sous-section ouverte dans un cours, pour proposer d\'y reprendre.';
$string['resume'] = 'Reprendre où j\'en étais :';
$string['section0name'] = 'Généralités';
$string['sectionname'] = 'Section';
$string['sectionswithouttab'] = 'Aucun onglet n\'ouvre ces sections, accessibles seulement par le menu des sections : {$a}.';
$string['sectionswithouttab_editing'] = 'Aucun onglet n\'ouvre ces sections :';
$string['sectionswithouttab_settings'] = 'Régler les onglets';
$string['showfromothers'] = 'Afficher';
$string['sidemenunav'] = 'Menu des sections du cours';
$string['subtabsnav'] = 'Sous-sections';
$string['subtabsshowall'] = 'Tout afficher';
$string['tabrow_name'] = 'Onglet {$a} : nom';
$string['tabrow_name_help'] = 'Nom affiché sur l\'onglet. Videz le nom pour supprimer l\'onglet.';
$string['tabrow_sections'] = 'Onglet {$a} : sections';
$string['tabrow_url'] = 'Onglet {$a} : ou adresse web';
$string['tabsections'] = 'Sections';
$string['tabsections_help'] = 'Sections ouvertes par cet onglet. Avec plusieurs sections, l\'onglet ouvre la première accessible et reste en surbrillance sur chacune d\'elles. Un onglet ne s\'affiche pas pour un utilisateur qui ne peut voir aucune de ses sections.';
$string['tabsections_none'] = 'Aucune section choisie';
$string['tabsections_placeholder'] = 'Rechercher une section';
$string['tabsnav'] = 'Sections du cours';
$string['tabtarget_both'] = 'Choisissez soit des sections, soit une adresse web, pas les deux.';
$string['tabtarget_required'] = 'Choisissez au moins une section, ou saisissez une adresse web.';
$string['taburl'] = 'Ou adresse web';
$string['taburl_help'] = 'À la place de sections, l\'onglet peut ouvrir une adresse web (commençant par http:// ou https://). Elle s\'ouvre dans une nouvelle fenêtre.';
$string['taburl_invalid'] = 'Saisissez une adresse web commençant par http:// ou https://.';
$string['themecolour_placeholder'] = 'Couleur du thème';
