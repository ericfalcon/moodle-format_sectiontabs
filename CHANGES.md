# Historique des versions

## 1.2 (stable) — 26 novembre 2026

- **Couleur de l'onglet actif reprise du thème par défaut**, toujours modifiable pour le site et pour chaque cours.
  Avec le thème **Épure**, les onglets prennent sa couleur de marque (et le texte lisible associé), en suivant son mode
  sombre, ses préférences d'affichage et les couleurs des entreprises ; ils reprennent aussi ses arrondis, bordures et
  couleur de focus. Avec un autre thème : sa couleur principale (`brandcolor`).
- Soulignement de l'onglet actif : par défaut, la couleur de l'onglet assombrie.
- Mise à niveau : les anciennes couleurs par défaut (jaune `#ecb21e`, soulignement `#2b1a10`), si elles n'avaient pas
  été modifiées, laissent la place aux couleurs du thème.
- Contrôles de visibilité renforcés pour les boutons Précédent / Suivant et les sous-onglets.

## 1.1 (stable) — 18 novembre 2026

- **Compatible de Moodle 4.5 à Moodle 5.3** (testé sur 4.5.16+ et 5.3.1 avec un cours réel de 9 sections et
  38 sous-sections).
- Les onglets de sous-sections mémorisent, pour chaque section, le dernier onglet ouvert ; une sous-section qui vient
  d'être créée s'ouvre automatiquement (aussi sur Moodle 4.5, qui ne l'indique pas dans l'adresse).
- Textes pour lecteurs d'écran masqués par une classe propre au plugin, identique en Bootstrap 4 (Moodle 4.5) et
  Bootstrap 5 (Moodle 5.x).

À savoir pour Moodle 4.5 : l'activité « Sous-section » y est désactivée par défaut
(*Administration du site → Plugins → Activités → Gérer les activités*) ; sans elle, Moodle affiche les sous-sections
comme des sections ordinaires.

## 1.0 (stable) — 17 novembre 2026

Première version stable du format **Onglets et menu de sections** (`format_sectiontabs`) pour Moodle 5.3.

- Barre d'onglets au-dessus de chaque page : un onglet par section, ou **onglets personnalisés** réglés dans les
  paramètres du cours (nom, une ou plusieurs sections, ou adresse web).
- **Menu des sections à gauche** du contenu.
- **Sous-sections en onglets** à l'intérieur d'une section, avec un onglet « Tout afficher » en mode édition.
- **Boutons Précédent / Suivant** : sous-section précédente / suivante, puis section précédente / suivante.
- Couleur de l'onglet actif réglable pour le site et pour chaque cours ; couleur du texte choisie automatiquement
  pour un contraste suffisant.
- Compatible **mode clair et mode sombre** (variables de couleur de Bootstrap).
- Respect des sections cachées et des restrictions d'accès.
- Mode édition : bouton « Ajouter une section » sur les pages de section ; ouverture de l'onglet d'une sous-section
  après son ajout ou sa modification.
- Onglets personnalisés conservés lors d'une sauvegarde / restauration, duplication ou importation du cours.
- Accessibilité vérifiée (WCAG 2.1 / 2.2 AA, clavier, lecteurs d'écran) ; revue de sécurité ; code conforme à
  moodle-cs.

Versions de développement (0.1 à 0.9) : publiées d'abord sous le nom `format_grettabs`, puis `format_sectiontabs`.
