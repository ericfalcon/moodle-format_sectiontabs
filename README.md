# Onglets et menu de sections (format_sectiontabs)

**Version 1.3 (stable)** — Moodle 4.5 à 5.3. Voir [CHANGES.md](CHANGES.md).

Format de cours Moodle qui reprend le format standard **Sections** (`format_topics`) et ajoute
au-dessus de chaque page du cours une **barre d'onglets automatique** donnant accès à toutes les sections,
dans l'esprit du format « une section par onglet ».

- La barre est construite automatiquement à partir des sections : rien à mettre à jour quand on ajoute,
  renomme, déplace ou masque une section.
- L'onglet de la section affichée prend la couleur principale du thème (avec Épure : sa couleur de marque), modifiable pour tout le site et pour chaque cours.
- Les restrictions d'accès (groupe, date, achèvement…) sont respectées : une section ou sous-section restreinte « masquée complètement » n'apparaît pas pour les étudiants concernés ; restreinte « affichée grisée », son onglet est grisé.
- Les étudiants ne voient jamais les sections ni les sous-sections cachées, même si le cours affiche les sections cachées comme « non disponibles ». Les enseignants les voient en grisé.
- Un menu vertical des sections peut aussi s'afficher à gauche du contenu.
- Les sous-sections (activité « Sous-section ») ne sont pas dans la barre principale : sur la page d'une section, elles s'affichent dans une seconde rangée d'onglets, une seule à la fois.
- Les étudiants voient leur **avancement** : une coche ✓ marque les sections et sous-sections dont toutes les activités suivies sont terminées.
- Un bouton **« Reprendre où j'en étais »** sur l'accueil du cours ramène l'étudiant à la dernière section (et sous-section) consultée.
- Sur téléphone, les onglets tiennent sur une ligne qui défile au doigt, l'onglet actif ramené à l'écran.
- Le reste du cours fonctionne exactement comme le format Sections : index du cours, sous-sections,
  mise en évidence, édition, glisser-déposer…

## Compatibilité

Moodle 4.5 à 5.3 (testé sur 4.5.16+ et 5.3.1). Sur Moodle 4.5, activer l'activité « Sous-section » pour bénéficier des sous-sections en onglets. Le format standard **Sections** (`format_topics`) doit être présent (il l'est toujours, c'est un format du cœur de Moodle).

## Installation (administrateur)

1. Copier le dossier dans `public/course/format/sectiontabs` (Moodle 5.1 et suivants) ou `course/format/sectiontabs` (Moodle 4.5 et 5.0) ; le dossier doit s'appeler `sectiontabs`,
   ou installer le zip depuis *Administration du site → Plugins → Installer des plugins*.
2. Lancer la mise à jour depuis *Administration du site → Notifications*.
3. Facultatif : régler les couleurs dans *Administration du site → Plugins → Formats de cours → Onglets et menu de sections*.

## Utilisation (enseignant)

Dans *Paramètres du cours → Format de cours*, choisir **Onglets et menu de sections**. Deux options s'ajoutent :

| Option | Effet |
| --- | --- |
| Onglets | **Un onglet par section**, ou **Onglets personnalisés** : vous choisissez vous-même les onglets et les sections qu'ils ouvrent (voir ci-dessous). |
| L'accueil du cours n'affiche que la section générale | La page d'accueil du cours n'affiche que la section 0, chaque autre section s'ouvre sur sa propre page. En mode édition, la liste complète reste affichée pour pouvoir ajouter et déplacer les sections. |
| Menu des sections à gauche | Affiche aussi la liste des sections à gauche du contenu (masquée sur téléphone). Activé par défaut. |
| Sous-sections en onglets | Sur la page d'une section, les sous-sections s'affichent en onglets, une seule à la fois. En mode édition, un onglet « Tout afficher » permet de les voir toutes pour les déplacer. Activé par défaut. |
| Avancement dans les onglets | Coche ✓ sur les onglets, le menu de gauche et les sous-onglets dont toutes les activités suivies (achèvement d'activité) sont terminées, pour les utilisateurs dont l'achèvement est suivi. Activé par défaut. |
| Bouton « Reprendre où j'en étais » | Sur l'accueil du cours, lien vers la dernière section consultée et la dernière sous-section ouverte. Activé par défaut. |
| Boutons Précédent / Suivant | En bas de chaque section : sous-section précédente / suivante tant qu'il en reste, puis section précédente / suivante (les sections inaccessibles sont sautées). Remplace les liens de navigation entre sections de Moodle. Activé par défaut. |
| Couleur de l'onglet actif / de son soulignement | Code hexadécimal (ex. `#2559a8`). Vide : couleur définie pour le site, sinon couleur du thème. |
| Noms d'onglets courts | « Bloc de compétence 1 : Actes essentiels » devient « Bloc de compétence 1 ». Le nom complet s'affiche au survol. |

Les sections et les activités sont conservées lors du changement de format.

### Onglets personnalisés

Dans *Paramètres du cours → Format de cours → Onglets*, choisir **Onglets personnalisés** : un bloc par onglet
s'affiche juste en dessous, dans l'ordre de la barre (pré-rempli avec un onglet par section la première fois) :

- **Onglet N : nom** — vider le nom supprime l'onglet ;
- **Onglet N : sections** — une ou plusieurs sections, choisies dans une liste avec recherche. Avec plusieurs sections,
  l'onglet ouvre la première accessible et reste en surbrillance sur chacune d'elles ;
- **Onglet N : ou adresse web** — à la place de sections, ouverte dans une nouvelle fenêtre.

Trois blocs vides permettent d'ajouter des onglets ; enregistrer en propose trois autres.

Les onglets désignent les sections par leur identifiant : renommer ou déplacer une section ne casse pas les onglets.
Ils sont conservés lors d'une sauvegarde / restauration, d'une duplication ou d'une importation du cours.
Un onglet n'apparaît pas pour un utilisateur qui ne peut voir aucune de ses sections. Le menu de gauche liste toujours
toutes les sections.

Les sections qu'aucun onglet n'ouvre (par exemple une section ajoutée après la création des onglets) sont signalées
aux enseignants : dans les paramètres du cours, et en mode édition au-dessus des onglets, avec un lien vers les réglages.
Une coche ✓ s'affiche sur un onglet personnalisé quand toutes les activités suivies de ses sections sont terminées.

### Mode édition

- Sur la page d'une section, le bouton **Ajouter une section** est proposé (Moodle ne l'affiche normalement que sur
  l'accueil du cours).
- Après l'ajout ou la modification d'une sous-section, son onglet s'ouvre automatiquement.
- L'onglet **Tout afficher** des sous-sections montre toutes les sous-sections pour les déplacer.

## Personnalisation

L'apparence est définie dans `styles.css` (classes `.gtabs`, `.gtabs-tab`, `.gtabs-active`).
Le gabarit de la barre est `templates/local/tabs.mustache` et peut être surchargé par un thème
(`theme/MONTHEME/templates/format_sectiontabs/local/tabs.mustache`).

## Couleurs

La couleur de l'onglet actif est choisie dans cet ordre :

1. la couleur définie dans les paramètres du **cours** ;
2. sinon, celle définie pour **tout le site** (*Administration du site → Plugins → Formats de cours → Onglets et menu
   de sections*) ;
3. sinon, la **couleur du thème** :
   - avec **Épure**, sa couleur de marque et la couleur de texte lisible dessus (`--epure-brand`, `--epure-on-brand`),
     qui suivent son mode sombre, les préférences d'affichage de l'utilisateur et les couleurs des entreprises (IOMAD) ;
   - avec un autre thème (Boost, Moove, Classic…), son réglage « couleur principale » (`brandcolor`), sinon le bleu
     de Moodle `#0f6cbf` ; la couleur du texte est alors choisie automatiquement pour rester lisible.

Le soulignement de l'onglet actif est, par défaut, la couleur de l'onglet assombrie. Avec Épure, les onglets reprennent
aussi ses arrondis, ses bordures et sa couleur de focus.

## Accessibilité

Les couleurs neutres suivent le mode clair ou sombre du thème (variables de couleur de Bootstrap, par exemple le mode sombre de Moove).

Vérifié avec axe-core (WCAG 2.1 / 2.2 niveau AA), au clavier et avec les attributs lus par les lecteurs d'écran :

- La couleur du texte de l'onglet actif (blanc ou noir) est choisie automatiquement pour garantir un contraste suffisant, quelle que soit la couleur choisie.
- Les onglets cachés ou non disponibles ont une couleur contrastée et un contour en pointillés ; les lecteurs d'écran annoncent « caché aux étudiants », « non disponible » ou « s'ouvre dans une nouvelle fenêtre ».
- La barre d'onglets et le menu de gauche sont des zones de navigation nommées ; la section affichée est signalée (`aria-current`).
- Les onglets de sous-sections suivent le modèle d'onglets WAI-ARIA : flèches, Début et Fin pour changer d'onglet, une seule tabulation pour entrer et sortir de la liste.
- Le focus clavier est bien visible ; les animations sont désactivées si l'utilisateur le demande.

## Sécurité

- Les noms de section et d'onglet passent par `format_string()` et les attributs sont échappés.
- Les adresses web des onglets personnalisés doivent commencer par `http://` ou `https://` et sont nettoyées (`PARAM_URL`).
- Les couleurs sont validées (code hexadécimal) ; une valeur invalide est remplacée par la couleur par défaut.
- Aucune section cachée ou restreinte (« masquer complètement ») n'apparaît pour un utilisateur qui ne peut pas la voir.
- Le plugin n'ajoute aucun service web. Seules données personnelles : deux préférences utilisateur par cours pour
  « Reprendre où j'en étais » (dernière section consultée, dernière sous-section ouverte), déclarées à l'API de
  confidentialité de Moodle et exportées avec les données de l'utilisateur.

## Tests automatiques

- **PHPUnit** (`tests/`) : couleurs, onglets personnalisés, sections sans onglet, formulaire du cours, données de la
  page (onglets, sections cachées, sous-onglets, précédent / suivant, avancement, reprise), confidentialité,
  duplication de cours.
- **Behat** (`tests/behat/sectiontabs.feature`) : navigation par onglets, menu et précédent / suivant, sous-onglets,
  coches d'avancement, bouton de reprise, onglets personnalisés et alerte, contrôle d'accessibilité (axe).

## Publier une nouvelle version (mainteneur)

L'intégration continue (GitHub Actions) contrôle chaque push : syntaxe PHP, règles de code Moodle (moodle-cs),
`version.php` et fichiers de langue (mêmes chaînes en anglais et en français, dans l'ordre alphabétique), puis lance
les tests PHPUnit et Behat sur Moodle 4.5 (PostgreSQL 16) et Moodle 5.3 (PostgreSQL 17) avec
[moodle-plugin-ci](https://github.com/moodlehq/moodle-plugin-ci).

Pour publier une version :

1. Dans `version.php`, augmenter `$plugin->version` (AAAAMMJJXX) et `$plugin->release` (par exemple `1.2`) ;
   `$plugin->maturity = MATURITY_STABLE` pour une version stable (sinon la release est marquée « pré-version »).
2. Ajouter une section `## 1.2` en tête de `CHANGES.md` : elle sert de notes de version.
3. Pousser sur `main`, puis dans GitHub : **Actions → Release → Run workflow**.
   (Autre possibilité : pousser une étiquette `v1.2`, qui doit correspondre à `$plugin->release`.)

Le workflow relance les contrôles, crée l'étiquette `v1.2`, fabrique `format_sectiontabs_1.2.zip` (dossier
`sectiontabs/`, sans le dossier `.github`) et publie la release avec ce zip et les notes de `CHANGES.md`.
Il refuse de republier une version existante.

## Licence

GNU GPL v3 ou ultérieure.

