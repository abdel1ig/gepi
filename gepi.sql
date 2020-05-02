-- Adminer 4.7.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP DATABASE IF EXISTS `gepi`;
CREATE DATABASE `gepi` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `gepi`;

DROP TABLE IF EXISTS `absences`;
CREATE TABLE `absences` (
  `login` varchar(50) NOT NULL DEFAULT '',
  `periode` int(11) NOT NULL DEFAULT '0',
  `nb_absences` char(2) NOT NULL DEFAULT '',
  `non_justifie` char(2) NOT NULL DEFAULT '',
  `nb_retards` char(2) NOT NULL DEFAULT '',
  `appreciation` text NOT NULL,
  PRIMARY KEY (`login`,`periode`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `absences_actions`;
CREATE TABLE `absences_actions` (
  `id_absence_action` int(11) NOT NULL AUTO_INCREMENT,
  `init_absence_action` char(2) NOT NULL DEFAULT '',
  `def_absence_action` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_absence_action`)
) TYPE=MyISAM;

INSERT INTO `absences_actions` (`id_absence_action`, `init_absence_action`, `def_absence_action`) VALUES
(1,	'RC',	'Renvoi du cours'),
(2,	'RD',	'Renvoi d&eacute;finitif'),
(3,	'LP',	'Lettre aux parents'),
(4,	'CE',	'Demande de convocation de l&#039;&eacute;l&egrave;ve en vie scolaire'),
(5,	'A',	'Aucune');

DROP TABLE IF EXISTS `absences_appreciations_grp`;
CREATE TABLE `absences_appreciations_grp` (
  `id_classe` int(11) NOT NULL DEFAULT '0',
  `periode` int(11) NOT NULL DEFAULT '0',
  `appreciation` text NOT NULL,
  PRIMARY KEY (`id_classe`,`periode`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `absences_eleves`;
CREATE TABLE `absences_eleves` (
  `id_absence_eleve` int(11) NOT NULL AUTO_INCREMENT,
  `type_absence_eleve` char(1) NOT NULL DEFAULT '',
  `eleve_absence_eleve` varchar(25) NOT NULL DEFAULT '0',
  `justify_absence_eleve` char(3) NOT NULL DEFAULT '',
  `info_justify_absence_eleve` text NOT NULL,
  `motif_absence_eleve` varchar(4) NOT NULL DEFAULT '',
  `info_absence_eleve` text NOT NULL,
  `d_date_absence_eleve` date NOT NULL DEFAULT '0000-00-00',
  `a_date_absence_eleve` date DEFAULT NULL,
  `d_heure_absence_eleve` time DEFAULT NULL,
  `a_heure_absence_eleve` time DEFAULT NULL,
  `saisie_absence_eleve` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_absence_eleve`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `absences_gep`;
CREATE TABLE `absences_gep` (
  `id_seq` char(2) NOT NULL DEFAULT '',
  `type` char(1) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_seq`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `absences_motifs`;
CREATE TABLE `absences_motifs` (
  `id_motif_absence` int(11) NOT NULL AUTO_INCREMENT,
  `init_motif_absence` char(2) NOT NULL DEFAULT '',
  `def_motif_absence` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_motif_absence`)
) TYPE=MyISAM;

INSERT INTO `absences_motifs` (`id_motif_absence`, `init_motif_absence`, `def_motif_absence`) VALUES
(1,	'A',	'Aucun motif'),
(2,	'AS',	'Accident sport'),
(3,	'AT',	'Absent en retenue'),
(4,	'C',	'Dans la cour'),
(5,	'CF',	'Convenances familiales'),
(6,	'CO',	'Convocation bureau'),
(7,	'CS',	'Compétition sportive'),
(8,	'DI',	'Dispense d\'E.P.S.'),
(9,	'ET',	'Erreur d\'emploi du temps'),
(10,	'EX',	'Examen'),
(11,	'H',	'Hospitalisation'),
(12,	'JP',	'Justification par le Principal'),
(13,	'MA',	'Maladie'),
(14,	'OR',	'Conseiller'),
(15,	'PR',	'Réveil'),
(16,	'RC',	'Refus de venir en cours'),
(17,	'RE',	'Renvoi'),
(18,	'RT',	'Présent en retenue'),
(19,	'RV',	'Renvoi du cours'),
(20,	'SM',	'Refus de justification'),
(21,	'SP',	'Sortie pédagogique'),
(22,	'ST',	'Stage à l\'extérieur'),
(23,	'T',	'Téléphone'),
(24,	'TR',	'Transport'),
(25,	'VM',	'Visite médicale'),
(26,	'IN',	'Infirmerie');

DROP TABLE IF EXISTS `absences_rb`;
CREATE TABLE `absences_rb` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `eleve_id` varchar(30) NOT NULL,
  `retard_absence` varchar(1) NOT NULL DEFAULT 'A',
  `groupe_id` varchar(8) NOT NULL,
  `edt_id` int(5) NOT NULL DEFAULT '0',
  `jour_semaine` varchar(10) NOT NULL,
  `creneau_id` int(5) NOT NULL,
  `debut_ts` int(11) NOT NULL,
  `fin_ts` int(11) NOT NULL,
  `date_saisie` int(20) NOT NULL,
  `login_saisie` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `eleve_debut_fin_retard` (`eleve_id`,`debut_ts`,`fin_ts`,`retard_absence`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `absences_repas`;
CREATE TABLE `absences_repas` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `date_repas` date NOT NULL DEFAULT '0000-00-00',
  `id_groupe` varchar(8) NOT NULL,
  `eleve_id` varchar(30) NOT NULL,
  `pers_id` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `abs_bull_delais`;
CREATE TABLE `abs_bull_delais` (
  `periode` int(11) NOT NULL DEFAULT '0',
  `id_classe` int(11) NOT NULL DEFAULT '0',
  `totaux` char(1) NOT NULL DEFAULT 'n',
  `appreciation` char(1) NOT NULL DEFAULT 'n',
  `date_limite` timestamp NOT NULL,
  `mode` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`periode`,`id_classe`),
  KEY `id_classe` (`id_classe`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `abs_prof`;
CREATE TABLE `abs_prof` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login_user` varchar(50) NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  `titre` varchar(100) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `abs_prof_divers`;
CREATE TABLE `abs_prof_divers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `abs_prof_remplacement`;
CREATE TABLE `abs_prof_remplacement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_absence` int(11) NOT NULL,
  `id_groupe` int(11) NOT NULL,
  `id_aid` int(11) NOT NULL,
  `id_classe` int(11) NOT NULL,
  `jour` char(8) NOT NULL,
  `id_creneau` int(11) NOT NULL,
  `date_debut_r` datetime NOT NULL,
  `date_fin_r` datetime NOT NULL,
  `reponse` varchar(30) NOT NULL,
  `date_reponse` datetime NOT NULL,
  `login_user` varchar(50) NOT NULL,
  `commentaire_prof` text NOT NULL,
  `validation_remplacement` varchar(30) NOT NULL,
  `commentaire_validation` text NOT NULL,
  `salle` varchar(100) NOT NULL,
  `texte_famille` text NOT NULL,
  `info_famille` varchar(10) NOT NULL,
  `duree` varchar(10) NOT NULL DEFAULT '0',
  `heuredeb_dec` varchar(3) NOT NULL DEFAULT '0',
  `jour_semaine` varchar(10) NOT NULL,
  `id_cours_remplaced` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `acces_cdt`;
CREATE TABLE `acces_cdt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL,
  `chemin` varchar(255) NOT NULL DEFAULT '',
  `date1` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date2` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `acces_cdt_groupes`;
CREATE TABLE `acces_cdt_groupes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_acces` int(11) NOT NULL,
  `id_groupe` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `acces_cn`;
CREATE TABLE `acces_cn` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_groupe` int(11) NOT NULL,
  `periode` int(11) NOT NULL,
  `date_limite` timestamp NOT NULL,
  `commentaires` text NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM COMMENT='Acces exceptionnel au CN en periode close';


DROP TABLE IF EXISTS `acces_exceptionnel_matieres_notes`;
CREATE TABLE `acces_exceptionnel_matieres_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_groupe` int(11) NOT NULL,
  `periode` int(11) NOT NULL,
  `date_limite` timestamp NOT NULL,
  `commentaires` text NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM COMMENT='Acces exceptionnel à la modif de notes du bulletin en periode close';


DROP TABLE IF EXISTS `aid`;
CREATE TABLE `aid` (
  `id` varchar(100) NOT NULL DEFAULT '',
  `nom` varchar(100) NOT NULL DEFAULT '',
  `numero` varchar(8) NOT NULL DEFAULT '0',
  `indice_aid` int(11) NOT NULL DEFAULT '0',
  `perso1` varchar(255) NOT NULL DEFAULT '',
  `perso2` varchar(255) NOT NULL DEFAULT '',
  `perso3` varchar(255) NOT NULL DEFAULT '',
  `productions` varchar(100) NOT NULL DEFAULT '',
  `resume` text NOT NULL,
  `famille` smallint(6) NOT NULL DEFAULT '0',
  `mots_cles` varchar(255) NOT NULL DEFAULT '',
  `adresse1` varchar(255) NOT NULL DEFAULT '',
  `adresse2` varchar(255) NOT NULL DEFAULT '',
  `public_destinataire` varchar(50) NOT NULL DEFAULT '',
  `contacts` text NOT NULL,
  `divers` text NOT NULL,
  `matiere1` varchar(100) NOT NULL DEFAULT '',
  `matiere2` varchar(100) NOT NULL DEFAULT '',
  `eleve_peut_modifier` enum('y','n') NOT NULL DEFAULT 'n',
  `prof_peut_modifier` enum('y','n') NOT NULL DEFAULT 'n',
  `cpe_peut_modifier` enum('y','n') NOT NULL DEFAULT 'n',
  `fiche_publique` enum('y','n') NOT NULL DEFAULT 'n',
  `affiche_adresse1` enum('y','n') NOT NULL DEFAULT 'n',
  `en_construction` enum('y','n') NOT NULL DEFAULT 'n',
  `sous_groupe` enum('y','n') NOT NULL DEFAULT 'n',
  `inscrit_direct` enum('y','n') NOT NULL DEFAULT 'n',
  `visibilite_eleve` enum('y','n') NOT NULL DEFAULT 'y',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `aid_appreciations`;
CREATE TABLE `aid_appreciations` (
  `login` varchar(50) NOT NULL DEFAULT '',
  `id_aid` varchar(100) NOT NULL,
  `periode` int(11) NOT NULL DEFAULT '0',
  `appreciation` text NOT NULL,
  `statut` char(10) NOT NULL DEFAULT '',
  `note` float DEFAULT NULL,
  `indice_aid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`login`,`id_aid`,`periode`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `aid_appreciations_grp`;
CREATE TABLE `aid_appreciations_grp` (
  `id_aid` int(11) NOT NULL DEFAULT '0',
  `periode` int(11) NOT NULL DEFAULT '0',
  `appreciation` text NOT NULL,
  `indice_aid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_aid`,`indice_aid`,`periode`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `aid_config`;
CREATE TABLE `aid_config` (
  `nom` char(100) NOT NULL DEFAULT '',
  `nom_complet` char(100) NOT NULL DEFAULT '',
  `note_max` int(11) NOT NULL DEFAULT '0',
  `order_display1` char(1) NOT NULL DEFAULT '0',
  `order_display2` int(11) NOT NULL DEFAULT '0',
  `type_note` char(5) NOT NULL DEFAULT '',
  `type_aid` int(11) NOT NULL DEFAULT '0',
  `display_begin` int(11) NOT NULL DEFAULT '0',
  `display_end` int(11) NOT NULL DEFAULT '0',
  `message` varchar(40) NOT NULL DEFAULT '',
  `display_nom` char(1) NOT NULL DEFAULT '',
  `indice_aid` int(11) NOT NULL DEFAULT '0',
  `display_bulletin` char(1) NOT NULL DEFAULT 'y',
  `bull_simplifie` char(1) NOT NULL DEFAULT 'y',
  `outils_complementaires` enum('y','n') NOT NULL DEFAULT 'n',
  `feuille_presence` enum('y','n') NOT NULL DEFAULT 'n',
  `autoriser_inscript_multiples` char(1) NOT NULL DEFAULT 'n',
  `resume` text NOT NULL,
  `imposer_resume` char(1) NOT NULL DEFAULT 'n',
  PRIMARY KEY (`indice_aid`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `aid_familles`;
CREATE TABLE `aid_familles` (
  `ordre_affichage` smallint(6) NOT NULL DEFAULT '0',
  `id` smallint(6) NOT NULL DEFAULT '0',
  `type` varchar(250) NOT NULL DEFAULT ''
) TYPE=MyISAM;

INSERT INTO `aid_familles` (`ordre_affichage`, `id`, `type`) VALUES
(0,	10,	'Information, presse'),
(1,	11,	'Philosophie et psychologie, pensée'),
(2,	12,	'Religions'),
(3,	13,	'Sciences sociales, société, humanitaire'),
(4,	14,	'Langues, langage'),
(5,	15,	'Sciences (sciences dures)'),
(6,	16,	'Techniques, sciences appliquées, médecine, cuisine...'),
(7,	17,	'Arts, loisirs et sports'),
(8,	18,	'Littérature, théâtre, poésie'),
(9,	19,	'Géographie et Histoire, civilisations anciennes');

DROP TABLE IF EXISTS `aid_productions`;
CREATE TABLE `aid_productions` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

INSERT INTO `aid_productions` (`id`, `nom`) VALUES
(1,	'Dossier papier'),
(2,	'Emission de radio'),
(3,	'Exposition'),
(4,	'Film'),
(5,	'Spectacle'),
(6,	'Réalisation plastique'),
(7,	'Réalisation technique ou scientifique'),
(8,	'Jeu vidéo'),
(9,	'Animation culturelle'),
(10,	'Maquette'),
(11,	'Site internet'),
(12,	'Diaporama'),
(13,	'Production musicale'),
(14,	'Production théâtrale'),
(15,	'Animation en milieu scolaire'),
(16,	'Programmation logicielle'),
(17,	'Journal');

DROP TABLE IF EXISTS `aid_public`;
CREATE TABLE `aid_public` (
  `ordre_affichage` smallint(6) NOT NULL DEFAULT '0',
  `id` smallint(6) NOT NULL DEFAULT '0',
  `public` varchar(100) NOT NULL DEFAULT ''
) TYPE=MyISAM;

INSERT INTO `aid_public` (`ordre_affichage`, `id`, `public`) VALUES
(3,	1,	'Lycéens'),
(2,	2,	'Collègiens'),
(1,	3,	'Ecoliers'),
(6,	4,	'Grand public'),
(5,	5,	'Experts (ou spécialistes)'),
(4,	6,	'Etudiants');

DROP TABLE IF EXISTS `aid_sous_groupes`;
CREATE TABLE `aid_sous_groupes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `aid` varchar(100) NOT NULL,
  `parent` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `aid` (`aid`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `archivage_aids`;
CREATE TABLE `archivage_aids` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `annee` varchar(200) NOT NULL DEFAULT '',
  `nom` varchar(100) NOT NULL DEFAULT '',
  `id_type_aid` int(11) NOT NULL DEFAULT '0',
  `productions` varchar(100) NOT NULL DEFAULT '',
  `resume` text NOT NULL,
  `famille` smallint(6) NOT NULL DEFAULT '0',
  `mots_cles` text NOT NULL,
  `adresse1` varchar(255) NOT NULL DEFAULT '',
  `adresse2` varchar(255) NOT NULL DEFAULT '',
  `public_destinataire` varchar(50) NOT NULL DEFAULT '',
  `contacts` text NOT NULL,
  `divers` text NOT NULL,
  `matiere1` varchar(100) NOT NULL DEFAULT '',
  `matiere2` varchar(100) NOT NULL DEFAULT '',
  `fiche_publique` enum('y','n') NOT NULL DEFAULT 'n',
  `affiche_adresse1` enum('y','n') NOT NULL DEFAULT 'n',
  `en_construction` enum('y','n') NOT NULL DEFAULT 'n',
  `notes_moyenne` varchar(255) NOT NULL,
  `notes_min` varchar(255) NOT NULL,
  `notes_max` varchar(255) NOT NULL,
  `responsables` text NOT NULL,
  `eleves` text NOT NULL,
  `eleves_resp` text NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `archivage_aid_eleve`;
CREATE TABLE `archivage_aid_eleve` (
  `id_aid` int(11) NOT NULL DEFAULT '0',
  `id_eleve` varchar(255) NOT NULL,
  `eleve_resp` char(1) NOT NULL DEFAULT 'n',
  PRIMARY KEY (`id_aid`,`id_eleve`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `archivage_appreciations_aid`;
CREATE TABLE `archivage_appreciations_aid` (
  `id_eleve` varchar(255) NOT NULL,
  `annee` varchar(200) NOT NULL,
  `classe` varchar(255) NOT NULL,
  `id_aid` int(11) NOT NULL,
  `periode` int(11) NOT NULL DEFAULT '0',
  `appreciation` text NOT NULL,
  `note_eleve` varchar(50) NOT NULL,
  `note_moyenne_classe` varchar(255) NOT NULL,
  `note_min_classe` varchar(255) NOT NULL,
  `note_max_classe` varchar(255) NOT NULL,
  PRIMARY KEY (`id_eleve`,`id_aid`,`periode`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `archivage_disciplines`;
CREATE TABLE `archivage_disciplines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `annee` varchar(200) NOT NULL,
  `INE` varchar(255) NOT NULL,
  `classe` varchar(255) NOT NULL,
  `mef_code` varchar(50) NOT NULL,
  `num_periode` tinyint(4) NOT NULL,
  `nom_periode` varchar(255) NOT NULL,
  `special` varchar(255) NOT NULL,
  `matiere` varchar(255) NOT NULL,
  `code_matiere` varchar(255) NOT NULL,
  `id_groupe` int(11) NOT NULL DEFAULT '0',
  `effectif` smallint(6) NOT NULL,
  `prof` varchar(255) NOT NULL,
  `id_prof` varchar(255) NOT NULL DEFAULT '',
  `type_prof` varchar(10) NOT NULL DEFAULT '',
  `nom_prof` varchar(50) NOT NULL,
  `prenom_prof` varchar(50) NOT NULL,
  `note` varchar(255) NOT NULL,
  `moymin` varchar(255) NOT NULL,
  `moymax` varchar(255) NOT NULL,
  `moyclasse` varchar(255) NOT NULL,
  `repar_moins_8` float(4,2) NOT NULL,
  `repar_8_12` float(4,2) NOT NULL,
  `repar_plus_12` float(4,2) NOT NULL,
  `rang` tinyint(4) NOT NULL,
  `appreciation` mediumtext NOT NULL,
  `nb_absences` int(11) NOT NULL,
  `non_justifie` int(11) NOT NULL,
  `nb_retards` int(11) NOT NULL,
  `ordre_matiere` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `annee` (`annee`),
  KEY `INE` (`INE`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `archivage_ects`;
CREATE TABLE `archivage_ects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `annee` varchar(255) NOT NULL COMMENT 'Annee scolaire',
  `ine` varchar(55) NOT NULL COMMENT 'Identifiant de l''eleve',
  `classe` varchar(255) NOT NULL COMMENT 'Classe de l''eleve',
  `num_periode` int(11) NOT NULL COMMENT 'Identifiant de la periode',
  `nom_periode` varchar(255) NOT NULL COMMENT 'Nom complet de la periode',
  `special` varchar(255) NOT NULL COMMENT 'Cle utilisee pour isoler certaines lignes (par exemple un credit ECTS pour une periode et non une matiere)',
  `matiere` varchar(255) DEFAULT NULL COMMENT 'Nom de l''enseignement',
  `profs` varchar(255) DEFAULT NULL COMMENT 'Liste des profs de l''enseignement',
  `valeur` decimal(10,0) NOT NULL COMMENT 'Nombre de crédits obtenus par l''eleve',
  `mention` varchar(255) NOT NULL COMMENT 'Mention obtenue',
  PRIMARY KEY (`id`,`ine`,`num_periode`,`special`),
  KEY `archivage_ects_FI_1` (`ine`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `archivage_eleves`;
CREATE TABLE `archivage_eleves` (
  `ine` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL DEFAULT '',
  `prenom` varchar(255) NOT NULL DEFAULT '',
  `sexe` char(1) NOT NULL,
  `naissance` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`ine`),
  KEY `nom` (`nom`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `archivage_eleves2`;
CREATE TABLE `archivage_eleves2` (
  `annee` varchar(50) NOT NULL DEFAULT '',
  `ine` varchar(50) NOT NULL,
  `doublant` enum('-','R') NOT NULL DEFAULT '-',
  `regime` varchar(255) NOT NULL,
  PRIMARY KEY (`ine`,`annee`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `archivage_engagements`;
CREATE TABLE `archivage_engagements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `annee` varchar(100) NOT NULL,
  `ine` varchar(255) NOT NULL,
  `code_engagement` varchar(10) NOT NULL,
  `nom_engagement` varchar(100) NOT NULL,
  `description_engagement` text NOT NULL,
  `classe` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `archivage_types_aid`;
CREATE TABLE `archivage_types_aid` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `annee` varchar(200) NOT NULL DEFAULT '',
  `nom` varchar(100) NOT NULL DEFAULT '',
  `nom_complet` varchar(100) NOT NULL DEFAULT '',
  `note_sur` int(11) NOT NULL DEFAULT '0',
  `type_note` varchar(5) NOT NULL DEFAULT '',
  `display_bulletin` char(1) NOT NULL DEFAULT 'y',
  `outils_complementaires` enum('y','n') NOT NULL DEFAULT 'n',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `ateliers_config`;
CREATE TABLE `ateliers_config` (
  `nom_champ` char(100) NOT NULL DEFAULT '',
  `content` char(255) NOT NULL DEFAULT '',
  `param` char(100) NOT NULL DEFAULT ''
) TYPE=MyISAM;


DROP TABLE IF EXISTS `avis_conseil_classe`;
CREATE TABLE `avis_conseil_classe` (
  `login` varchar(50) NOT NULL DEFAULT '',
  `periode` int(11) NOT NULL DEFAULT '0',
  `avis` text NOT NULL,
  `id_mention` int(11) NOT NULL DEFAULT '0',
  `statut` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`login`,`periode`),
  KEY `login` (`login`,`periode`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `a_agregation_decompte`;
CREATE TABLE `a_agregation_decompte` (
  `eleve_id` int(11) NOT NULL COMMENT 'id de l''eleve',
  `date_demi_jounee` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date de la demi journée agrégée : 00:00 pour une matinée, 12:00 pour une après midi',
  `manquement_obligation_presence` tinyint(4) DEFAULT '0' COMMENT 'Cette demi journée est comptée comme absence',
  `non_justifiee` tinyint(4) DEFAULT '0' COMMENT 'Si cette demi journée est compté comme absence, y a-t-il une justification',
  `notifiee` tinyint(4) DEFAULT '0' COMMENT 'Si cette demi journée est compté comme absence, y a-t-il une notification à la famille',
  `retards` int(11) DEFAULT '0' COMMENT 'Nombre de retards total décomptés dans la demi journée',
  `retards_non_justifies` int(11) DEFAULT '0' COMMENT 'Nombre de retards non justifiés décomptés dans la demi journée',
  `motifs_absences` text COMMENT 'Liste des motifs (table a_motifs) associés à cette demi-journée d''absence',
  `motifs_retards` text COMMENT 'Liste des motifs (table a_motifs) associés aux retard de cette demi-journée',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`eleve_id`,`date_demi_jounee`)
) TYPE=MyISAM COMMENT='Table d''agregation des decomptes de demi journees d''absence et de retard';


DROP TABLE IF EXISTS `a_droits`;
CREATE TABLE `a_droits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `page` varchar(255) NOT NULL,
  `consultation` varchar(10) NOT NULL DEFAULT 'n',
  `saisie` varchar(10) NOT NULL DEFAULT 'n',
  PRIMARY KEY (`id`),
  KEY `login_page` (`login`,`page`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `a_justifications`;
CREATE TABLE `a_justifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'cle primaire auto-incrementee',
  `nom` varchar(250) NOT NULL COMMENT 'Nom de la justification',
  `commentaire` text COMMENT 'commentaire saisi par l''utilisateur',
  `sortable_rank` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM COMMENT='Liste des justifications possibles pour une absence';


DROP TABLE IF EXISTS `a_lieux`;
CREATE TABLE `a_lieux` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Cle primaire auto-incrementee',
  `nom` varchar(250) NOT NULL COMMENT 'Nom du lieu',
  `commentaire` text COMMENT 'commentaire saisi par l''utilisateur',
  `sortable_rank` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM COMMENT='Lieu pour les types d''absence ou les saisies';


DROP TABLE IF EXISTS `a_motifs`;
CREATE TABLE `a_motifs` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'cle primaire auto-incrementee',
  `nom` varchar(250) NOT NULL COMMENT 'Nom du motif',
  `commentaire` text COMMENT 'commentaire saisi par l''utilisateur',
  `sortable_rank` int(11) DEFAULT NULL,
  `valable` varchar(3) NOT NULL DEFAULT 'y' COMMENT 'caractere valable ou non du motif',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM COMMENT='Liste des motifs possibles pour une absence';


DROP TABLE IF EXISTS `a_notifications`;
CREATE TABLE `a_notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utilisateur_id` varchar(100) DEFAULT NULL COMMENT 'Login de l''utilisateur professionnel qui envoi la notification',
  `a_traitement_id` int(12) NOT NULL COMMENT 'cle etrangere du traitement qu''on notifie',
  `type_notification` int(5) DEFAULT NULL COMMENT 'type de notification (0 : email, 1 : courrier, 2 : sms)',
  `email` varchar(100) DEFAULT NULL COMMENT 'email de destination (pour le type email)',
  `telephone` varchar(100) DEFAULT NULL COMMENT 'numero du telephone de destination (pour le type sms)',
  `adr_id` varchar(10) DEFAULT NULL COMMENT 'cle etrangere vers l''adresse de destination (pour le type courrier)',
  `commentaire` text COMMENT 'commentaire saisi par l''utilisateur',
  `statut_envoi` int(5) DEFAULT '0' COMMENT 'Statut de cet envoi (0 : etat initial, 1 : en cours, 2 : echec, 3 : succes, 4 : succes avec accuse de reception)',
  `date_envoi` datetime DEFAULT NULL COMMENT 'Date envoi',
  `erreur_message_envoi` text COMMENT 'Message d''erreur retourné par le service d''envoi',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `a_notifications_FI_1` (`utilisateur_id`),
  KEY `a_notifications_FI_2` (`a_traitement_id`),
  KEY `a_notifications_FI_3` (`adr_id`)
) TYPE=MyISAM COMMENT='Notification (a la famille) des absences';


DROP TABLE IF EXISTS `a_saisies`;
CREATE TABLE `a_saisies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utilisateur_id` varchar(100) DEFAULT NULL COMMENT 'Login de l''utilisateur professionnel qui a saisi l''absence',
  `eleve_id` int(11) DEFAULT NULL COMMENT 'id_eleve de l''eleve objet de la saisie, egal à null si aucun eleve n''est saisi',
  `commentaire` text COMMENT 'commentaire de l''utilisateur',
  `debut_abs` datetime DEFAULT NULL COMMENT 'Debut de l''absence en timestamp UNIX',
  `fin_abs` datetime DEFAULT NULL COMMENT 'Fin de l''absence en timestamp UNIX',
  `id_edt_creneau` int(12) DEFAULT NULL COMMENT 'identifiant du creneaux de l''emploi du temps',
  `id_edt_emplacement_cours` int(12) DEFAULT NULL COMMENT 'identifiant du cours de l''emploi du temps',
  `id_groupe` int(11) DEFAULT NULL COMMENT 'identifiant du groupe pour lequel la saisie a ete effectuee',
  `id_classe` int(11) DEFAULT NULL COMMENT 'identifiant de la classe pour lequel la saisie a ete effectuee',
  `id_aid` int(11) DEFAULT NULL COMMENT 'identifiant de l''aid pour lequel la saisie a ete effectuee',
  `id_s_incidents` int(11) DEFAULT NULL COMMENT 'identifiant de la saisie d''incident discipline',
  `id_lieu` int(11) DEFAULT NULL COMMENT 'cle etrangere du lieu ou se trouve l''eleve',
  `deleted_by` varchar(100) DEFAULT NULL COMMENT 'Login de l''utilisateur professionnel qui a supprimé la saisie',
  `created_at` datetime DEFAULT NULL COMMENT 'Date de creation de la saisie',
  `updated_at` datetime DEFAULT NULL COMMENT 'Date de modification de la saisie, y compris suppression, restauration et changement de version',
  `deleted_at` datetime DEFAULT NULL,
  `version` int(11) DEFAULT '0',
  `version_created_at` datetime DEFAULT NULL,
  `version_created_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `a_saisies_I_1` (`deleted_at`),
  KEY `a_saisies_I_2` (`debut_abs`),
  KEY `a_saisies_I_3` (`fin_abs`),
  KEY `a_saisies_FI_1` (`utilisateur_id`),
  KEY `a_saisies_FI_2` (`eleve_id`),
  KEY `a_saisies_FI_3` (`id_edt_creneau`),
  KEY `a_saisies_FI_4` (`id_edt_emplacement_cours`),
  KEY `a_saisies_FI_5` (`id_groupe`),
  KEY `a_saisies_FI_6` (`id_classe`),
  KEY `a_saisies_FI_7` (`id_aid`),
  KEY `a_saisies_FI_8` (`id_lieu`)
) TYPE=MyISAM COMMENT='Chaque saisie d''absence doit faire l''objet d''une ligne dans la table a_saisies. Une saisie peut etre : une plage horaire longue durée (plusieurs jours), défini avec les champs debut_abs et fin_abs. Un creneau horaire, le jour etant precisé dans debut_abs. Un cours de l''emploi du temps, le jours du cours etant precisé dans debut_abs.';


DROP TABLE IF EXISTS `a_saisies_version`;
CREATE TABLE `a_saisies_version` (
  `id` int(11) NOT NULL,
  `utilisateur_id` varchar(100) DEFAULT NULL COMMENT 'Login de l''utilisateur professionnel qui a saisi l''absence',
  `eleve_id` int(11) DEFAULT NULL COMMENT 'id_eleve de l''eleve objet de la saisie, egal à null si aucun eleve n''est saisi',
  `commentaire` text COMMENT 'commentaire de l''utilisateur',
  `debut_abs` datetime DEFAULT NULL COMMENT 'Debut de l''absence en timestamp UNIX',
  `fin_abs` datetime DEFAULT NULL COMMENT 'Fin de l''absence en timestamp UNIX',
  `id_edt_creneau` int(12) DEFAULT NULL COMMENT 'identifiant du creneaux de l''emploi du temps',
  `id_edt_emplacement_cours` int(12) DEFAULT NULL COMMENT 'identifiant du cours de l''emploi du temps',
  `id_groupe` int(11) DEFAULT NULL COMMENT 'identifiant du groupe pour lequel la saisie a ete effectuee',
  `id_classe` int(11) DEFAULT NULL COMMENT 'identifiant de la classe pour lequel la saisie a ete effectuee',
  `id_aid` int(11) DEFAULT NULL COMMENT 'identifiant de l''aid pour lequel la saisie a ete effectuee',
  `id_s_incidents` int(11) DEFAULT NULL COMMENT 'identifiant de la saisie d''incident discipline',
  `id_lieu` int(11) DEFAULT NULL COMMENT 'cle etrangere du lieu ou se trouve l''eleve',
  `deleted_by` varchar(100) DEFAULT NULL COMMENT 'Login de l''utilisateur professionnel qui a supprimé la saisie',
  `created_at` datetime DEFAULT NULL COMMENT 'Date de creation de la saisie',
  `updated_at` datetime DEFAULT NULL COMMENT 'Date de modification de la saisie, y compris suppression, restauration et changement de version',
  `deleted_at` datetime DEFAULT NULL,
  `version` int(11) NOT NULL DEFAULT '0',
  `version_created_at` datetime DEFAULT NULL,
  `version_created_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`,`version`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `a_traitements`;
CREATE TABLE `a_traitements` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'cle primaire auto-incremente',
  `utilisateur_id` varchar(100) DEFAULT NULL COMMENT 'Login de l''utilisateur professionnel qui a fait le traitement',
  `a_type_id` int(4) DEFAULT NULL COMMENT 'cle etrangere du type d''absence',
  `a_motif_id` int(4) DEFAULT NULL COMMENT 'cle etrangere du motif d''absence',
  `a_justification_id` int(4) DEFAULT NULL COMMENT 'cle etrangere de la justification de l''absence',
  `commentaire` text COMMENT 'commentaire saisi par l''utilisateur',
  `modifie_par_utilisateur_id` varchar(100) DEFAULT NULL COMMENT 'Login de l''utilisateur professionnel qui a modifie en dernier le traitement',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `a_traitements_I_1` (`deleted_at`),
  KEY `a_traitements_FI_1` (`utilisateur_id`),
  KEY `a_traitements_FI_2` (`a_type_id`),
  KEY `a_traitements_FI_3` (`a_motif_id`),
  KEY `a_traitements_FI_4` (`a_justification_id`),
  KEY `a_traitements_FI_5` (`modifie_par_utilisateur_id`)
) TYPE=MyISAM COMMENT='Un traitement peut gerer plusieurs saisies et consiste à definir les motifs/justifications... de ces absences saisies';


DROP TABLE IF EXISTS `a_types`;
CREATE TABLE `a_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Cle primaire auto-incrementee',
  `nom` varchar(250) NOT NULL COMMENT 'Nom du type d''absence',
  `justification_exigible` tinyint(4) DEFAULT NULL COMMENT 'Ce type d''absence doit entrainer une justification de la part de la famille',
  `sous_responsabilite_etablissement` varchar(255) DEFAULT 'NON_PRECISE' COMMENT 'L''eleve est sous la responsabilite de l''etablissement. Typiquement : absence infirmerie, mettre la propriété à vrai car l''eleve est encore sous la responsabilité de l''etablissement. Possibilite : ''vrai''/''faux''/''non_precise''',
  `manquement_obligation_presence` varchar(50) DEFAULT 'NON_PRECISE' COMMENT 'L''eleve manque à ses obligations de presence (L''absence apparait sur le bulletin). Possibilite : ''vrai''/''faux''/''non_precise''',
  `retard_bulletin` varchar(50) DEFAULT 'NON_PRECISE' COMMENT 'La saisie est comptabilisée dans le bulletin en tant que retard. Possibilite : ''vrai''/''faux''/''non_precise''',
  `mode_interface` varchar(50) DEFAULT 'NON_PRECISE' COMMENT 'Enumeration des possibilités de l''interface de saisie de l''absence pour ce type : DEBUT_ABS, FIN_ABS, DEBUT_ET_FIN_ABS, NON_PRECISE, COMMENTAIRE_EXIGE, DISCIPLINE, CHECKBOX, CHECKBOX_HIDDEN',
  `commentaire` text COMMENT 'commentaire saisi par l''utilisateur',
  `id_lieu` int(11) DEFAULT NULL COMMENT 'cle etrangere du lieu ou se trouve l''élève',
  `sortable_rank` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `a_types_FI_1` (`id_lieu`)
) TYPE=MyISAM COMMENT='Liste des types d''absences possibles dans l''etablissement';


DROP TABLE IF EXISTS `a_types_statut`;
CREATE TABLE `a_types_statut` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Cle primaire auto-incrementee',
  `id_a_type` int(11) NOT NULL COMMENT 'Cle etrangere de la table a_type',
  `statut` varchar(20) NOT NULL COMMENT 'Statut de l''utilisateur',
  PRIMARY KEY (`id`),
  KEY `a_types_statut_FI_1` (`id_a_type`)
) TYPE=MyISAM COMMENT='Liste des statuts autorises à saisir des types d''absences';


DROP TABLE IF EXISTS `b_droits_divers`;
CREATE TABLE `b_droits_divers` (
  `login` varchar(50) NOT NULL DEFAULT '',
  `nom_droit` varchar(50) NOT NULL DEFAULT '',
  `valeur_droit` varchar(50) NOT NULL DEFAULT ''
) TYPE=MyISAM;


DROP TABLE IF EXISTS `calendrier_vacances`;
CREATE TABLE `calendrier_vacances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_calendrier` varchar(100) NOT NULL DEFAULT '',
  `debut_calendrier_ts` varchar(11) NOT NULL,
  `fin_calendrier_ts` varchar(11) NOT NULL,
  `jourdebut_calendrier` date NOT NULL DEFAULT '0000-00-00',
  `heuredebut_calendrier` time NOT NULL DEFAULT '00:00:00',
  `jourfin_calendrier` date NOT NULL DEFAULT '0000-00-00',
  `heurefin_calendrier` time NOT NULL DEFAULT '00:00:00',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `cc_dev`;
CREATE TABLE `cc_dev` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cn_dev` int(11) NOT NULL DEFAULT '0',
  `id_groupe` int(11) NOT NULL DEFAULT '0',
  `nom_court` varchar(32) NOT NULL DEFAULT '',
  `nom_complet` varchar(64) NOT NULL DEFAULT '',
  `description` varchar(128) NOT NULL DEFAULT '',
  `arrondir` char(2) NOT NULL DEFAULT 's1',
  `vision_famille` text NOT NULL COMMENT 'Autorisation de voir pour les familles',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `cc_eval`;
CREATE TABLE `cc_eval` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_dev` int(11) NOT NULL DEFAULT '0',
  `nom_court` varchar(32) NOT NULL DEFAULT '',
  `nom_complet` varchar(64) NOT NULL DEFAULT '',
  `description` varchar(128) NOT NULL DEFAULT '',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `note_sur` float(10,1) DEFAULT '5.0',
  `vision_famille` date NOT NULL COMMENT 'Autorisation de voir pour les familles',
  PRIMARY KEY (`id`),
  KEY `dev_date` (`id_dev`,`date`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `cc_notes_eval`;
CREATE TABLE `cc_notes_eval` (
  `login` varchar(50) NOT NULL DEFAULT '',
  `id_eval` int(11) NOT NULL DEFAULT '0',
  `note` float(10,1) NOT NULL DEFAULT '0.0',
  `statut` char(1) NOT NULL DEFAULT '',
  `comment` text NOT NULL,
  PRIMARY KEY (`login`,`id_eval`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `classes`;
CREATE TABLE `classes` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `classe` varchar(100) NOT NULL DEFAULT '',
  `nom_complet` varchar(100) NOT NULL DEFAULT '',
  `suivi_par` varchar(50) NOT NULL DEFAULT '',
  `formule` varchar(100) NOT NULL DEFAULT '',
  `format_nom` varchar(5) NOT NULL DEFAULT '',
  `format_nom_eleve` varchar(5) NOT NULL DEFAULT 'np',
  `display_rang` char(1) NOT NULL DEFAULT 'n',
  `display_address` char(1) NOT NULL DEFAULT 'n',
  `display_coef` char(1) NOT NULL DEFAULT 'y',
  `display_mat_cat` char(1) NOT NULL DEFAULT 'n',
  `display_nbdev` char(1) NOT NULL DEFAULT 'n',
  `display_moy_gen` char(1) NOT NULL DEFAULT 'y',
  `modele_bulletin_pdf` varchar(255) DEFAULT NULL,
  `rn_nomdev` char(1) NOT NULL DEFAULT 'n',
  `rn_toutcoefdev` char(1) NOT NULL DEFAULT 'n',
  `rn_coefdev_si_diff` char(1) NOT NULL DEFAULT 'n',
  `rn_datedev` char(1) NOT NULL DEFAULT 'n',
  `rn_sign_chefetab` char(1) NOT NULL DEFAULT 'n',
  `rn_sign_pp` char(1) NOT NULL DEFAULT 'n',
  `rn_sign_resp` char(1) NOT NULL DEFAULT 'n',
  `rn_sign_nblig` int(11) NOT NULL DEFAULT '3',
  `rn_formule` text NOT NULL,
  `ects_type_formation` varchar(255) NOT NULL DEFAULT '',
  `ects_parcours` varchar(255) NOT NULL DEFAULT '',
  `ects_code_parcours` varchar(255) NOT NULL DEFAULT '',
  `ects_domaines_etude` varchar(255) NOT NULL DEFAULT '',
  `ects_fonction_signataire_attestation` varchar(255) NOT NULL DEFAULT '',
  `apb_niveau` varchar(15) NOT NULL DEFAULT '',
  `rn_abs_2` varchar(1) NOT NULL DEFAULT 'n',
  `mef_code` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `classe` (`classe`)
) TYPE=MyISAM;

INSERT INTO `classes` (`id`, `classe`, `nom_complet`, `suivi_par`, `formule`, `format_nom`, `format_nom_eleve`, `display_rang`, `display_address`, `display_coef`, `display_mat_cat`, `display_nbdev`, `display_moy_gen`, `modele_bulletin_pdf`, `rn_nomdev`, `rn_toutcoefdev`, `rn_coefdev_si_diff`, `rn_datedev`, `rn_sign_chefetab`, `rn_sign_pp`, `rn_sign_resp`, `rn_sign_nblig`, `rn_formule`, `ects_type_formation`, `ects_parcours`, `ects_code_parcours`, `ects_domaines_etude`, `ects_fonction_signataire_attestation`, `apb_niveau`, `rn_abs_2`, `mef_code`) VALUES
(1,	'1ereIESI',	'1ere IESI',	'Directeur général',	'',	'in',	'pn',	'y',	'n',	'y',	'y',	'n',	'y',	'1',	'n',	'n',	'n',	'n',	'y',	'n',	'n',	2,	'',	'',	'',	'',	'',	'',	'',	'n',	'');

DROP TABLE IF EXISTS `classes_param`;
CREATE TABLE `classes_param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_classe` smallint(6) NOT NULL,
  `name` varchar(100) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_classe_name` (`id_classe`,`name`)
) TYPE=MyISAM;

INSERT INTO `classes_param` (`id`, `id_classe`, `name`, `value`) VALUES
(1,	1,	'rn_aff_classe_nom',	'1'),
(2,	1,	'rn_app',	'y'),
(3,	1,	'rn_moy_classe',	'y'),
(4,	1,	'rn_moy_min_max_classe',	'y'),
(5,	1,	'rn_retour_ligne',	'y'),
(6,	1,	'rn_rapport_standard_min_font',	'n'),
(7,	1,	'rn_adr_resp',	'n'),
(8,	1,	'rn_bloc_obs',	'y'),
(9,	1,	'rn_col_moy',	'y'),
(10,	1,	'rn_type_par_defaut',	'html'),
(11,	1,	'bull_prefixe_periode',	'Bulletin du '),
(12,	1,	'gepi_prof_suivi',	'professeur principal'),
(13,	1,	'suivi_par_alt',	''),
(14,	1,	'suivi_par_alt_fonction',	''),
(15,	1,	'type_classe',	'standard'),
(16,	1,	'display_moy_gen_saisie_avis2',	'y'),
(17,	1,	'rn_moy_gen',	'y');

DROP TABLE IF EXISTS `cn_cahier_notes`;
CREATE TABLE `cn_cahier_notes` (
  `id_cahier_notes` int(11) NOT NULL AUTO_INCREMENT,
  `id_groupe` int(11) NOT NULL,
  `periode` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_cahier_notes`,`id_groupe`,`periode`),
  KEY `groupe_periode` (`id_groupe`,`periode`)
) TYPE=MyISAM;

INSERT INTO `cn_cahier_notes` (`id_cahier_notes`, `id_groupe`, `periode`) VALUES
(1,	1,	1),
(2,	7,	1),
(3,	8,	1),
(4,	10,	1),
(5,	9,	1),
(6,	5,	1),
(7,	2,	1),
(8,	6,	1),
(9,	4,	1),
(10,	3,	1);

DROP TABLE IF EXISTS `cn_conteneurs`;
CREATE TABLE `cn_conteneurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_racine` int(11) NOT NULL DEFAULT '0',
  `nom_court` varchar(32) NOT NULL DEFAULT '',
  `nom_complet` varchar(64) NOT NULL DEFAULT '',
  `description` varchar(128) NOT NULL DEFAULT '',
  `mode` char(1) NOT NULL DEFAULT '2',
  `coef` decimal(3,1) NOT NULL DEFAULT '1.0',
  `arrondir` char(2) NOT NULL DEFAULT 's1',
  `ponderation` decimal(3,1) NOT NULL DEFAULT '0.0',
  `display_parents` char(1) NOT NULL DEFAULT '0',
  `display_bulletin` char(1) NOT NULL DEFAULT '1',
  `parent` int(11) NOT NULL DEFAULT '0',
  `modele_id_conteneur` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `parent_racine` (`parent`,`id_racine`),
  KEY `racine_bulletin` (`id_racine`,`display_bulletin`)
) TYPE=MyISAM;

INSERT INTO `cn_conteneurs` (`id`, `id_racine`, `nom_court`, `nom_complet`, `description`, `mode`, `coef`, `arrondir`, `ponderation`, `display_parents`, `display_bulletin`, `parent`, `modele_id_conteneur`) VALUES
(1,	1,	'Tajwîd, les règles de lecture du',	'Tajwîd, les règles de lecture du Coran',	'',	'2',	1.0,	's1',	0.0,	'0',	'1',	0,	0),
(2,	2,	'Courant de pensée et mouvement r',	'Courant de pensée et mouvement réformiste',	'',	'2',	1.0,	's1',	0.0,	'0',	'1',	0,	0),
(3,	3,	'Al ‘aquida, ilm al kalâm, La Foi',	'Al ‘aquida, ilm al kalâm, La Foi, le Credo, les fondements de la',	'',	'2',	1.0,	's1',	0.0,	'0',	'1',	0,	0),
(4,	4,	'Al firaq wal madhahib,  écoles d',	'Al firaq wal madhahib,  écoles de droit et de pensée, schismes e',	'',	'2',	1.0,	's1',	0.0,	'0',	'1',	0,	0),
(5,	5,	'Le suivi d’apprentissage du Cora',	'Le suivi d’apprentissage du Coran',	'',	'2',	1.0,	's1',	0.0,	'0',	'1',	0,	0),
(6,	6,	'L’histoire musulmane',	'L’histoire musulmane',	'',	'2',	1.0,	's1',	0.0,	'0',	'1',	0,	0),
(7,	7,	'La Sira, La vie du Prophète de l',	'La Sira, La vie du Prophète de l\'Islam',	'',	'2',	1.0,	's1',	0.0,	'0',	'1',	0,	0),
(8,	8,	'L’étude des ahadith (recueils)',	'L’étude des ahadith (recueils)',	'',	'2',	1.0,	's1',	0.0,	'0',	'1',	0,	0),
(9,	9,	'Les sciences du Coran',	'Les sciences du Coran',	'',	'2',	1.0,	's1',	0.0,	'0',	'1',	0,	0),
(10,	10,	'Les sciences du hadith',	'Les sciences du hadith',	'',	'2',	1.0,	's1',	0.0,	'0',	'1',	0,	0);

DROP TABLE IF EXISTS `cn_conteneurs_modele`;
CREATE TABLE `cn_conteneurs_modele` (
  `id_modele` int(11) NOT NULL AUTO_INCREMENT,
  `nom_court` varchar(32) NOT NULL DEFAULT '',
  `description` varchar(128) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_modele`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `cn_conteneurs_modele_conteneurs`;
CREATE TABLE `cn_conteneurs_modele_conteneurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_modele` int(11) NOT NULL DEFAULT '0',
  `id_racine` int(11) NOT NULL DEFAULT '0',
  `nom_court` varchar(32) NOT NULL DEFAULT '',
  `nom_complet` varchar(64) NOT NULL DEFAULT '',
  `description` varchar(128) NOT NULL DEFAULT '',
  `mode` char(1) NOT NULL DEFAULT '2',
  `coef` decimal(3,1) NOT NULL DEFAULT '1.0',
  `arrondir` char(2) NOT NULL DEFAULT 's1',
  `ponderation` decimal(3,1) NOT NULL DEFAULT '0.0',
  `display_parents` char(1) NOT NULL DEFAULT '0',
  `display_bulletin` char(1) NOT NULL DEFAULT '1',
  `parent` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `parent_racine` (`parent`,`id_racine`),
  KEY `racine_bulletin` (`id_racine`,`display_bulletin`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `cn_devoirs`;
CREATE TABLE `cn_devoirs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_conteneur` int(11) NOT NULL DEFAULT '0',
  `id_racine` int(11) NOT NULL DEFAULT '0',
  `nom_court` varchar(32) NOT NULL DEFAULT '',
  `nom_complet` varchar(64) NOT NULL DEFAULT '',
  `description` varchar(128) NOT NULL DEFAULT '',
  `facultatif` char(1) NOT NULL DEFAULT '',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `coef` decimal(3,1) NOT NULL DEFAULT '0.0',
  `note_sur` int(11) DEFAULT '20',
  `ramener_sur_referentiel` char(1) NOT NULL DEFAULT 'F',
  `display_parents` char(1) NOT NULL DEFAULT '',
  `display_parents_app` char(1) NOT NULL DEFAULT '0',
  `date_ele_resp` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `conteneur_date` (`id_conteneur`,`date`)
) TYPE=MyISAM;

INSERT INTO `cn_devoirs` (`id`, `id_conteneur`, `id_racine`, `nom_court`, `nom_complet`, `description`, `facultatif`, `date`, `coef`, `note_sur`, `ramener_sur_referentiel`, `display_parents`, `display_parents_app`, `date_ele_resp`) VALUES
(1,	1,	1,	'eval1',	'Evaluation 1',	'',	'O',	'2020-05-02 00:00:00',	1.0,	20,	'F',	'1',	'0',	'2020-05-02 00:00:00'),
(2,	2,	2,	'eval1',	'Evaluation 1',	'',	'O',	'2020-05-02 00:00:00',	1.0,	20,	'F',	'1',	'0',	'2020-05-02 00:00:00'),
(3,	3,	3,	'eval1',	'Evaluation 1',	'',	'O',	'2020-05-02 00:00:00',	1.0,	20,	'F',	'1',	'0',	'2020-05-02 00:00:00'),
(4,	4,	4,	'eval1',	'Evaluation 1',	'',	'O',	'2020-05-02 00:00:00',	1.0,	20,	'F',	'1',	'0',	'2020-05-02 00:00:00'),
(5,	5,	5,	'eval1',	'Evaluation 1',	'',	'O',	'2020-05-02 00:00:00',	1.0,	20,	'F',	'1',	'0',	'2020-05-02 00:00:00'),
(6,	6,	6,	'eval1',	'Evaluation 1',	'',	'O',	'2020-05-02 00:00:00',	1.0,	20,	'F',	'1',	'0',	'2020-05-02 00:00:00'),
(7,	7,	7,	'eval1',	'Evaluation 1',	'',	'O',	'2020-05-02 00:00:00',	1.0,	20,	'F',	'1',	'0',	'2020-05-02 00:00:00'),
(8,	8,	8,	'eval1',	'Evaluation 1',	'',	'O',	'2020-05-02 00:00:00',	1.0,	20,	'F',	'1',	'0',	'2020-05-02 00:00:00'),
(9,	9,	9,	'eval1',	'Evaluation 1',	'',	'O',	'2020-05-02 00:00:00',	1.0,	20,	'F',	'1',	'0',	'2020-05-02 00:00:00'),
(10,	10,	10,	'eval1',	'Evaluation 1',	'',	'O',	'2020-05-02 00:00:00',	1.0,	20,	'F',	'1',	'0',	'2020-05-02 00:00:00');

DROP TABLE IF EXISTS `cn_notes_conteneurs`;
CREATE TABLE `cn_notes_conteneurs` (
  `login` varchar(50) NOT NULL DEFAULT '',
  `id_conteneur` int(11) NOT NULL DEFAULT '0',
  `note` float(10,1) NOT NULL DEFAULT '0.0',
  `statut` char(1) NOT NULL DEFAULT '',
  `comment` text NOT NULL,
  PRIMARY KEY (`login`,`id_conteneur`)
) TYPE=MyISAM;

INSERT INTO `cn_notes_conteneurs` (`login`, `id_conteneur`, `note`, `statut`, `comment`) VALUES
('A.Mustapha',	1,	10.0,	'y',	''),
('A.Outman',	1,	17.0,	'y',	''),
('H.Youssef',	1,	15.0,	'y',	''),
('I.Abdelhaki',	1,	12.0,	'y',	''),
('K.Abdelhami',	1,	16.0,	'y',	''),
('M.Abdelhaki',	1,	15.0,	'y',	''),
('S.Anass',	1,	18.0,	'y',	''),
('A.Mustapha',	2,	17.0,	'y',	''),
('A.Outman',	2,	16.0,	'y',	''),
('H.Youssef',	2,	12.0,	'y',	''),
('I.Abdelhaki',	2,	14.0,	'y',	''),
('K.Abdelhami',	2,	13.0,	'y',	''),
('M.Abdelhaki',	2,	11.0,	'y',	''),
('S.Anass',	2,	15.0,	'y',	''),
('A.Mustapha',	3,	17.0,	'y',	''),
('A.Outman',	3,	18.0,	'y',	''),
('H.Youssef',	3,	19.0,	'y',	''),
('I.Abdelhaki',	3,	13.0,	'y',	''),
('K.Abdelhami',	3,	14.0,	'y',	''),
('M.Abdelhaki',	3,	15.0,	'y',	''),
('S.Anass',	3,	16.0,	'y',	''),
('A.Mustapha',	4,	10.0,	'y',	''),
('A.Outman',	4,	11.0,	'y',	''),
('H.Youssef',	4,	12.0,	'y',	''),
('I.Abdelhaki',	4,	13.0,	'y',	''),
('K.Abdelhami',	4,	14.0,	'y',	''),
('M.Abdelhaki',	4,	15.0,	'y',	''),
('S.Anass',	4,	16.0,	'y',	''),
('A.Mustapha',	5,	20.0,	'y',	''),
('A.Outman',	5,	19.5,	'y',	''),
('H.Youssef',	5,	19.0,	'y',	''),
('I.Abdelhaki',	5,	18.0,	'y',	''),
('K.Abdelhami',	5,	18.5,	'y',	''),
('M.Abdelhaki',	5,	17.5,	'y',	''),
('S.Anass',	5,	17.0,	'y',	''),
('A.Mustapha',	6,	14.0,	'y',	''),
('A.Outman',	6,	12.0,	'y',	''),
('H.Youssef',	6,	13.0,	'y',	''),
('I.Abdelhaki',	6,	11.0,	'y',	''),
('K.Abdelhami',	6,	15.0,	'y',	''),
('M.Abdelhaki',	6,	12.0,	'y',	''),
('S.Anass',	6,	10.0,	'y',	''),
('A.Mustapha',	7,	15.0,	'y',	''),
('A.Outman',	7,	20.0,	'y',	''),
('H.Youssef',	7,	19.0,	'y',	''),
('I.Abdelhaki',	7,	10.0,	'y',	''),
('K.Abdelhami',	7,	13.0,	'y',	''),
('M.Abdelhaki',	7,	20.0,	'y',	''),
('S.Anass',	7,	17.0,	'y',	''),
('A.Mustapha',	8,	20.0,	'y',	''),
('A.Outman',	8,	11.0,	'y',	''),
('H.Youssef',	8,	15.0,	'y',	''),
('I.Abdelhaki',	8,	20.0,	'y',	''),
('K.Abdelhami',	8,	15.0,	'y',	''),
('M.Abdelhaki',	8,	10.0,	'y',	''),
('S.Anass',	8,	16.0,	'y',	''),
('A.Mustapha',	9,	10.5,	'y',	''),
('A.Outman',	9,	15.3,	'y',	''),
('H.Youssef',	9,	14.0,	'y',	''),
('I.Abdelhaki',	9,	14.0,	'y',	''),
('K.Abdelhami',	9,	12.0,	'y',	''),
('M.Abdelhaki',	9,	17.5,	'y',	''),
('S.Anass',	9,	16.2,	'y',	''),
('A.Mustapha',	10,	14.0,	'y',	''),
('A.Outman',	10,	20.0,	'y',	''),
('H.Youssef',	10,	19.0,	'y',	''),
('I.Abdelhaki',	10,	14.0,	'y',	''),
('K.Abdelhami',	10,	16.5,	'y',	''),
('M.Abdelhaki',	10,	15.0,	'y',	''),
('S.Anass',	10,	14.5,	'y',	'');

DROP TABLE IF EXISTS `cn_notes_devoirs`;
CREATE TABLE `cn_notes_devoirs` (
  `login` varchar(50) NOT NULL DEFAULT '',
  `id_devoir` int(11) NOT NULL DEFAULT '0',
  `note` float(10,1) NOT NULL DEFAULT '0.0',
  `comment` text NOT NULL,
  `statut` varchar(4) NOT NULL DEFAULT '',
  PRIMARY KEY (`login`,`id_devoir`),
  KEY `devoir_statut` (`id_devoir`,`statut`)
) TYPE=MyISAM;

INSERT INTO `cn_notes_devoirs` (`login`, `id_devoir`, `note`, `comment`, `statut`) VALUES
('A.Mustapha',	1,	10.0,	'',	''),
('A.Outman',	1,	17.0,	'',	''),
('H.Youssef',	1,	15.0,	'',	''),
('I.Abdelhaki',	1,	12.0,	'',	''),
('K.Abdelhami',	1,	16.0,	'',	''),
('M.Abdelhaki',	1,	15.0,	'',	''),
('S.Anass',	1,	18.0,	'',	''),
('A.Mustapha',	2,	17.0,	'',	''),
('A.Outman',	2,	16.0,	'',	''),
('H.Youssef',	2,	12.0,	'',	''),
('I.Abdelhaki',	2,	14.0,	'',	''),
('K.Abdelhami',	2,	13.0,	'',	''),
('M.Abdelhaki',	2,	11.0,	'',	''),
('S.Anass',	2,	15.0,	'',	''),
('A.Mustapha',	3,	17.0,	'',	''),
('A.Outman',	3,	18.0,	'',	''),
('H.Youssef',	3,	19.0,	'',	''),
('I.Abdelhaki',	3,	13.0,	'',	''),
('K.Abdelhami',	3,	14.0,	'',	''),
('M.Abdelhaki',	3,	15.0,	'',	''),
('S.Anass',	3,	16.0,	'',	''),
('A.Mustapha',	4,	10.0,	'',	''),
('A.Outman',	4,	11.0,	'',	''),
('H.Youssef',	4,	12.0,	'',	''),
('I.Abdelhaki',	4,	13.0,	'',	''),
('K.Abdelhami',	4,	14.0,	'',	''),
('M.Abdelhaki',	4,	15.0,	'',	''),
('S.Anass',	4,	16.0,	'',	''),
('A.Mustapha',	5,	20.0,	'',	''),
('A.Outman',	5,	19.5,	'',	''),
('H.Youssef',	5,	19.0,	'',	''),
('I.Abdelhaki',	5,	18.0,	'',	''),
('K.Abdelhami',	5,	18.5,	'',	''),
('M.Abdelhaki',	5,	17.5,	'',	''),
('S.Anass',	5,	17.0,	'',	''),
('A.Mustapha',	6,	14.0,	'',	''),
('A.Outman',	6,	12.0,	'',	''),
('H.Youssef',	6,	13.0,	'',	''),
('I.Abdelhaki',	6,	11.0,	'',	''),
('K.Abdelhami',	6,	15.0,	'',	''),
('M.Abdelhaki',	6,	12.0,	'',	''),
('S.Anass',	6,	10.0,	'',	''),
('A.Mustapha',	7,	15.0,	'',	''),
('A.Outman',	7,	20.0,	'',	''),
('H.Youssef',	7,	19.0,	'',	''),
('I.Abdelhaki',	7,	10.0,	'',	''),
('K.Abdelhami',	7,	13.0,	'',	''),
('M.Abdelhaki',	7,	20.0,	'',	''),
('S.Anass',	7,	17.0,	'',	''),
('A.Mustapha',	8,	20.0,	'',	''),
('A.Outman',	8,	11.0,	'',	''),
('H.Youssef',	8,	15.0,	'',	''),
('I.Abdelhaki',	8,	20.0,	'',	''),
('K.Abdelhami',	8,	15.0,	'',	''),
('M.Abdelhaki',	8,	10.0,	'',	''),
('S.Anass',	8,	16.0,	'',	''),
('A.Mustapha',	9,	10.5,	'',	''),
('A.Outman',	9,	15.3,	'',	''),
('H.Youssef',	9,	14.0,	'',	''),
('I.Abdelhaki',	9,	14.0,	'',	''),
('K.Abdelhami',	9,	12.0,	'',	''),
('M.Abdelhaki',	9,	17.5,	'',	''),
('S.Anass',	9,	16.2,	'',	''),
('A.Mustapha',	10,	14.0,	'',	''),
('A.Outman',	10,	20.0,	'',	''),
('H.Youssef',	10,	19.0,	'',	''),
('I.Abdelhaki',	10,	14.0,	'',	''),
('K.Abdelhami',	10,	16.5,	'',	''),
('M.Abdelhaki',	10,	15.0,	'',	''),
('S.Anass',	10,	14.5,	'',	'');

DROP TABLE IF EXISTS `commentaires_types`;
CREATE TABLE `commentaires_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `commentaire` text NOT NULL,
  `num_periode` int(11) NOT NULL,
  `id_classe` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `commentaires_types_d_apres_moy`;
CREATE TABLE `commentaires_types_d_apres_moy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `note_min` float(10,2) NOT NULL DEFAULT '0.00',
  `note_max` float(10,2) NOT NULL DEFAULT '20.10',
  `app` text NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `commentaires_types_profs`;
CREATE TABLE `commentaires_types_profs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `app` text NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `communes`;
CREATE TABLE `communes` (
  `code_commune_insee` varchar(50) NOT NULL,
  `departement` varchar(50) NOT NULL,
  `commune` varchar(255) NOT NULL,
  PRIMARY KEY (`code_commune_insee`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `ct_devoirs_documents`;
CREATE TABLE `ct_devoirs_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_ct_devoir` int(11) NOT NULL DEFAULT '0',
  `titre` varchar(255) NOT NULL DEFAULT '',
  `taille` int(11) NOT NULL DEFAULT '0',
  `emplacement` varchar(255) NOT NULL DEFAULT '',
  `visible_eleve_parent` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `ct_devoirs_entry`;
CREATE TABLE `ct_devoirs_entry` (
  `id_ct` int(11) NOT NULL AUTO_INCREMENT,
  `id_groupe` int(11) NOT NULL,
  `date_ct` int(11) NOT NULL DEFAULT '0',
  `id_login` varchar(32) NOT NULL DEFAULT '',
  `id_sequence` int(11) NOT NULL DEFAULT '0',
  `contenu` text NOT NULL,
  `vise` char(1) NOT NULL DEFAULT 'n',
  `special` varchar(20) NOT NULL DEFAULT '',
  `date_visibilite_eleve` timestamp NOT NULL COMMENT 'Timestamp précisant quand les devoirs sont portés à la connaissance des élèves',
  PRIMARY KEY (`id_ct`),
  KEY `id_groupe` (`id_groupe`),
  KEY `groupe_date` (`id_groupe`,`date_ct`)
) TYPE=MyISAM;

INSERT INTO `ct_devoirs_entry` (`id_ct`, `id_groupe`, `date_ct`, `id_login`, `id_sequence`, `contenu`, `vise`, `special`, `date_visibilite_eleve`) VALUES
(1,	1,	1588543200,	'prof_a',	0,	'<p>Lire sourate alfatiha avec tajwid</p>\r\n',	'n',	'',	'2020-05-02 01:12:00');

DROP TABLE IF EXISTS `ct_devoirs_faits`;
CREATE TABLE `ct_devoirs_faits` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_ct` int(11) unsigned NOT NULL,
  `login` varchar(255) NOT NULL,
  `etat` varchar(50) NOT NULL,
  `date_initiale` datetime DEFAULT NULL,
  `date_modif` datetime DEFAULT NULL,
  `commentaire` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `ct_documents`;
CREATE TABLE `ct_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_ct` int(11) NOT NULL DEFAULT '0',
  `titre` varchar(255) NOT NULL DEFAULT '',
  `taille` int(11) NOT NULL DEFAULT '0',
  `emplacement` varchar(255) NOT NULL DEFAULT '',
  `visible_eleve_parent` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `ct_entry`;
CREATE TABLE `ct_entry` (
  `id_ct` int(11) NOT NULL AUTO_INCREMENT,
  `heure_entry` time NOT NULL DEFAULT '00:00:00',
  `id_groupe` int(11) NOT NULL,
  `date_ct` int(11) NOT NULL DEFAULT '0',
  `id_login` varchar(32) NOT NULL DEFAULT '',
  `id_sequence` int(11) NOT NULL DEFAULT '0',
  `contenu` text NOT NULL,
  `vise` char(1) NOT NULL DEFAULT 'n',
  `visa` char(1) NOT NULL DEFAULT 'n',
  PRIMARY KEY (`id_ct`),
  KEY `id_groupe` (`id_groupe`),
  KEY `id_date_heure` (`id_groupe`,`date_ct`,`heure_entry`)
) TYPE=MyISAM;

INSERT INTO `ct_entry` (`id_ct`, `heure_entry`, `id_groupe`, `date_ct`, `id_login`, `id_sequence`, `contenu`, `vise`, `visa`) VALUES
(1,	'03:11:00',	1,	1588370400,	'prof_a',	0,	'<p>compte rendu de la première séance.</p>\r\n\r\n<p>Le \"<em>cahier de textes de classe [...] constitue un document officiel, à valeur juridique.</em></p>\r\n',	'n',	'n');

DROP TABLE IF EXISTS `ct_private_entry`;
CREATE TABLE `ct_private_entry` (
  `id_ct` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Cle primaire de la cotice privee',
  `heure_entry` time NOT NULL DEFAULT '00:00:00' COMMENT 'heure de l''entree',
  `date_ct` int(11) NOT NULL DEFAULT '0' COMMENT 'date du compte rendu',
  `contenu` text NOT NULL COMMENT 'contenu redactionnel du compte rendu',
  `id_groupe` int(11) NOT NULL COMMENT 'Cle etrangere du groupe auquel appartient le compte rendu',
  `id_login` varchar(32) DEFAULT NULL COMMENT 'Cle etrangere de l''utilisateur auquel appartient le compte rendu',
  `id_sequence` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_ct`),
  KEY `ct_private_entry_FI_1` (`id_groupe`),
  KEY `ct_private_entry_FI_2` (`id_login`)
) TYPE=MyISAM COMMENT='Notice privee du cahier de texte';


DROP TABLE IF EXISTS `ct_sequences`;
CREATE TABLE `ct_sequences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `ct_tag`;
CREATE TABLE `ct_tag` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_ct` int(11) unsigned NOT NULL,
  `type_ct` char(1) NOT NULL DEFAULT '',
  `id_tag` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idct_idtag` (`id_ct`,`type_ct`,`id_tag`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `ct_tag_type`;
CREATE TABLE `ct_tag_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nom_tag` varchar(255) NOT NULL DEFAULT '',
  `tag_compte_rendu` char(1) NOT NULL DEFAULT 'y',
  `tag_devoir` char(1) NOT NULL DEFAULT 'y',
  `tag_notice_privee` char(1) NOT NULL DEFAULT 'y',
  `drapeau` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `ct_types_documents`;
CREATE TABLE `ct_types_documents` (
  `id_type` bigint(21) NOT NULL AUTO_INCREMENT,
  `titre` text NOT NULL,
  `extension` varchar(10) NOT NULL DEFAULT '',
  `upload` enum('oui','non') NOT NULL DEFAULT 'oui',
  PRIMARY KEY (`id_type`),
  UNIQUE KEY `extension` (`extension`)
) TYPE=MyISAM;

INSERT INTO `ct_types_documents` (`id_type`, `titre`, `extension`, `upload`) VALUES
(1,	'JPEG',	'jpg',	'oui'),
(2,	'PNG',	'png',	'oui'),
(3,	'GIF',	'gif',	'oui'),
(4,	'BMP',	'bmp',	'oui'),
(5,	'Photoshop',	'psd',	'oui'),
(6,	'TIFF',	'tif',	'oui'),
(7,	'AIFF',	'aiff',	'oui'),
(8,	'Windows Media',	'asf',	'oui'),
(9,	'Windows Media',	'avi',	'oui'),
(10,	'Midi',	'mid',	'oui'),
(12,	'QuickTime',	'mov',	'oui'),
(13,	'MP3',	'mp3',	'oui'),
(14,	'MPEG',	'mpg',	'oui'),
(15,	'Ogg',	'ogg',	'oui'),
(16,	'QuickTime',	'qt',	'oui'),
(17,	'RealAudio',	'ra',	'oui'),
(18,	'RealAudio',	'ram',	'oui'),
(19,	'RealAudio',	'rm',	'oui'),
(20,	'Flash',	'swf',	'oui'),
(21,	'WAV',	'wav',	'oui'),
(22,	'Windows Media',	'wmv',	'oui'),
(23,	'Adobe Illustrator',	'ai',	'oui'),
(24,	'BZip',	'bz2',	'oui'),
(25,	'C source',	'c',	'oui'),
(26,	'Debian',	'deb',	'oui'),
(27,	'Word',	'doc',	'oui'),
(29,	'LaTeX DVI',	'dvi',	'oui'),
(30,	'PostScript',	'eps',	'oui'),
(31,	'GZ',	'gz',	'oui'),
(32,	'C header',	'h',	'oui'),
(33,	'HTML',	'html',	'oui'),
(34,	'Pascal',	'pas',	'oui'),
(35,	'PDF',	'pdf',	'oui'),
(36,	'PowerPoint',	'ppt',	'oui'),
(37,	'PostScript',	'ps',	'oui'),
(38,	'gr',	'gr',	'oui'),
(39,	'RTF',	'rtf',	'oui'),
(40,	'StarOffice',	'sdd',	'oui'),
(41,	'StarOffice',	'sdw',	'oui'),
(42,	'Stuffit',	'sit',	'oui'),
(43,	'OpenOffice Calc',	'sxc',	'oui'),
(44,	'OpenOffice Impress',	'sxi',	'oui'),
(45,	'OpenOffice',	'sxw',	'oui'),
(46,	'LaTeX',	'tex',	'oui'),
(47,	'TGZ',	'tgz',	'oui'),
(48,	'texte',	'txt',	'oui'),
(49,	'GIMP multi-layer',	'xcf',	'oui'),
(50,	'Excel',	'xls',	'oui'),
(51,	'XML',	'xml',	'oui'),
(52,	'Zip',	'zip',	'oui'),
(53,	'Texte OpenDocument',	'odt',	'oui'),
(54,	'Classeur OpenDocument',	'ods',	'oui'),
(55,	'Présentation OpenDocument',	'odp',	'oui'),
(56,	'Dessin OpenDocument',	'odg',	'oui'),
(57,	'Base de données OpenDocument',	'odb',	'oui'),
(58,	'GeoGebra',	'ggb',	'oui'),
(59,	'Word',	'docx',	'oui'),
(60,	'PowerPoint',	'pptx',	'oui'),
(61,	'Excel',	'xlsx',	'oui');

DROP TABLE IF EXISTS `droits`;
CREATE TABLE `droits` (
  `id` varchar(200) NOT NULL DEFAULT '',
  `administrateur` char(1) NOT NULL DEFAULT '',
  `professeur` char(1) NOT NULL DEFAULT '',
  `cpe` char(1) NOT NULL DEFAULT '',
  `scolarite` char(1) NOT NULL DEFAULT '',
  `eleve` char(1) NOT NULL DEFAULT '',
  `responsable` char(1) NOT NULL DEFAULT '',
  `secours` char(1) NOT NULL DEFAULT '',
  `autre` char(1) NOT NULL DEFAULT 'F',
  `description` varchar(255) NOT NULL DEFAULT '',
  `statut` char(1) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

INSERT INTO `droits` (`id`, `administrateur`, `professeur`, `cpe`, `scolarite`, `eleve`, `responsable`, `secours`, `autre`, `description`, `statut`) VALUES
('/absences/index.php',	'F',	'F',	'V',	'F',	'F',	'F',	'V',	'F',	'Saisie des absences',	''),
('/absences/saisie_absences.php',	'F',	'F',	'V',	'F',	'F',	'F',	'V',	'F',	'Saisie des absences',	''),
('/accueil_admin.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'',	''),
('/accueil_modules.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'',	''),
('/accueil.php',	'V',	'V',	'V',	'V',	'V',	'V',	'V',	'F',	'',	''),
('/aid/add_aid.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Configuration des AID',	''),
('/aid/config_aid.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Configuration des AID',	''),
('/aid/export_csv_aid.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Configuration des AID',	''),
('/aid/help.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Configuration des AID',	''),
('/aid/index.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Configuration des AID',	''),
('/aid/index2.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Configuration des AID',	''),
('/aid/modify_aid.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Configuration des AID',	''),
('/aid/modify_aid_new.php',	'V',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Configuration des AID',	''),
('/bulletin/edit.php',	'V',	'V',	'F',	'V',	'F',	'F',	'F',	'F',	'Edition des bulletins',	'1'),
('/bulletin/param_bull.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Edition des bulletins',	'1'),
('/bulletin/verif_bulletins.php',	'F',	'V',	'F',	'V',	'F',	'F',	'F',	'F',	'Vérification du remplissage des bulletins',	''),
('/bulletin/verrouillage.php',	'F',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'(de)Verrouillage des périodes',	''),
('/cahier_notes_admin/index.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Gestion des carnets de notes',	''),
('/cahier_notes/add_modif_conteneur.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Carnet de notes',	'1'),
('/cahier_notes/add_modif_dev.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Carnet de notes',	'1'),
('/cahier_notes/index.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Carnet de notes',	'1'),
('/cahier_notes/saisie_notes.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Carnet de notes',	'1'),
('/cahier_notes/toutes_notes.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Carnet de notes',	'1'),
('/cahier_notes/visu_releve_notes.php',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Visualisation et impression des relevés de notes',	''),
('/cahier_texte_admin/admin_ct.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Gestion des cahier de texte',	''),
('/cahier_texte_admin/index.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Gestion des cahier de texte',	''),
('/cahier_texte_admin/modify_limites.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Gestion des cahier de texte',	''),
('/cahier_texte_admin/modify_type_doc.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Gestion des cahier de texte',	''),
('/cahier_texte/index.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Cahier de texte',	'1'),
('/cahier_texte/traite_doc.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Cahier de texte',	'1'),
('/classes/classes_ajout.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Configuration et gestion des classes',	''),
('/classes/classes_const.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Configuration et gestion des classes',	''),
('/classes/cpe_resp.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Affectation des CPE aux classes',	''),
('/classes/duplicate_class.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Configuration et gestion des classes',	''),
('/classes/eleve_options.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Configuration et gestion des classes',	''),
('/classes/index.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Configuration et gestion des classes',	''),
('/classes/modify_nom_class.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Configuration et gestion des classes',	''),
('/classes/periodes.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Configuration et gestion des classes',	''),
('/classes/prof_suivi.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Configuration et gestion des classes',	''),
('/classes/scol_resp.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Affectation des comptes scolarité aux classes',	''),
('/eleves/help.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Configuration et gestion des élèves',	''),
('/eleves/import_eleves_csv.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Configuration et gestion des élèves',	''),
('/eleves/index.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Gestion des élèves',	''),
('/eleves/modify_eleve.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Gestion des élèves',	''),
('/etablissements/help.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Configuration et gestion des établissements',	''),
('/etablissements/index.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Configuration et gestion des établissements',	''),
('/etablissements/modify_etab.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Configuration et gestion des établissements',	''),
('/gestion/gestion_base_test.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'gestion données de test',	''),
('/groupes/index.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Edition des groupes',	''),
('/groupes/add_group.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Ajout de groupes',	''),
('/groupes/edit_group.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Edition de groupes',	''),
('/groupes/edit_eleves.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Edition des élèves des groupes',	''),
('/groupes/edit_class.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Edition des groupes de la classe',	''),
('/gestion/accueil_sauve.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Restauration, suppression et sauvegarde de la base',	''),
('/gestion/savebackup.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Téléchargement de sauvegardes la base',	''),
('/gestion/efface_base.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Restauration, suppression et sauvegarde de la base',	''),
('/gestion/gestion_connect.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Gestion des connexions',	''),
('/gestion/help_import.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation de l\'année scolaire',	''),
('/gestion/help.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'',	''),
('/gestion/import_csv.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation de l\'année scolaire',	''),
('/gestion/index.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'',	''),
('/gestion/modify_impression.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Gestion des paramètres de la feuille de bienvenue',	''),
('/gestion/param_gen.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Configuration générale',	''),
('/gestion/traitement_csv.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation de l\'année scolaire',	''),
('/init_csv/index.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation CSV de l\'année scolaire',	''),
('/init_csv/eleves.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation CSV de l\'année scolaire',	''),
('/init_csv/responsables.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation CSV de l\'année scolaire',	''),
('/init_csv/disciplines.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation CSV de l\'année scolaire',	''),
('/init_csv/professeurs.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation CSV de l\'année scolaire',	''),
('/init_csv/eleves_classes.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation CSV de l\'année scolaire',	''),
('/init_csv/prof_disc_classes.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation CSV de l\'année scolaire',	''),
('/init_csv/eleves_options.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation CSV de l\'année scolaire',	''),
('/init_scribe/index.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation de l\'année scolaire',	''),
('/init_scribe/professeurs.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation de l\'année scolaire',	''),
('/init_scribe/eleves.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation de l\'année scolaire',	''),
('/init_scribe/eleves_options.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation de l\'année scolaire',	''),
('/init_scribe/prof_disc_classes.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation de l\'année scolaire',	''),
('/init_scribe/disciplines.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation de l\'année scolaire',	''),
('/init_lcs/index.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation LCS de l\'année scolaire',	''),
('/init_lcs/eleves.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation LCS de l\'année scolaire',	''),
('/init_lcs/professeurs.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation LCS de l\'année scolaire',	''),
('/init_lcs/disciplines.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation LCS de l\'année scolaire',	''),
('/init_lcs/affectations.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation LCS de l\'année scolaire',	''),
('/lib/confirm_query.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'',	''),
('/matieres/help.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Configuration et gestion des matières',	''),
('/matieres/index.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Configuration et gestion des matières',	''),
('/matieres/modify_matiere.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Configuration et gestion des matières',	''),
('/matieres/matieres_param.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Configuration et gestion des classes',	''),
('/matieres/matieres_categories.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Edition des catégories de matière',	''),
('/prepa_conseil/edit_limite.php',	'V',	'V',	'V',	'V',	'V',	'V',	'F',	'F',	'Edition des bulletins simplifiés (documents de travail)',	''),
('/prepa_conseil/help.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'',	''),
('/prepa_conseil/index1.php',	'F',	'V',	'F',	'V',	'F',	'F',	'V',	'F',	'Visualisation des notes et appréciations',	'1'),
('/prepa_conseil/index2.php',	'F',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Visualisation des notes par classes',	''),
('/prepa_conseil/index3.php',	'F',	'V',	'V',	'V',	'V',	'V',	'F',	'F',	'Edition des bulletins simplifiés (documents de travail)',	''),
('/prepa_conseil/visu_aid.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Visualisation des notes et appréciations AID',	''),
('/prepa_conseil/visu_toutes_notes.php',	'F',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Visualisation des notes par classes',	''),
('/responsables/index.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Configuration et gestion des responsables élèves',	''),
('/responsables/modify_resp.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Configuration et gestion des responsables élèves',	''),
('/saisie/help.php',	'F',	'V',	'F',	'F',	'F',	'F',	'V',	'F',	'',	''),
('/saisie/import_class_csv.php',	'F',	'V',	'F',	'V',	'F',	'F',	'V',	'F',	'',	''),
('/saisie/import_note_app.php',	'F',	'V',	'F',	'F',	'F',	'F',	'V',	'F',	'',	''),
('/saisie/index.php',	'F',	'V',	'F',	'F',	'F',	'F',	'V',	'F',	'',	''),
('/saisie/saisie_aid.php',	'F',	'V',	'F',	'F',	'F',	'F',	'V',	'F',	'Saisie des notes et appréciations AID',	''),
('/saisie/saisie_appreciations.php',	'F',	'V',	'F',	'F',	'F',	'F',	'V',	'F',	'Saisie des appréciations du bulletins',	''),
('/saisie/ajax_appreciations.php',	'F',	'V',	'V',	'V',	'F',	'F',	'V',	'F',	'Sauvegarde des appréciations du bulletins',	''),
('/saisie/saisie_avis.php',	'F',	'V',	'V',	'V',	'F',	'F',	'V',	'F',	'Saisie des avis du conseil de classe',	''),
('/saisie/saisie_avis1.php',	'F',	'V',	'V',	'V',	'F',	'F',	'V',	'F',	'Saisie des avis du conseil de classe',	''),
('/saisie/saisie_avis2.php',	'F',	'V',	'V',	'V',	'F',	'F',	'V',	'F',	'Saisie des avis du conseil de classe',	''),
('/saisie/saisie_notes.php',	'F',	'V',	'F',	'F',	'F',	'F',	'V',	'F',	'Saisie des notes du bulletins',	''),
('/saisie/traitement_csv.php',	'F',	'V',	'F',	'F',	'F',	'F',	'V',	'F',	'Saisie des notes du bulletins',	''),
('/utilisateurs/change_pwd.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Configuration et gestion des utilisateurs',	''),
('/utilisateurs/help.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Configuration et gestion des utilisateurs',	''),
('/utilisateurs/import_prof_csv.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Configuration et gestion des utilisateurs',	''),
('/utilisateurs/impression_bienvenue.php',	'V',	'V',	'V',	'V',	'V',	'V',	'V',	'V',	'Configuration et gestion des utilisateurs',	''),
('/utilisateurs/index.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Configuration et gestion des utilisateurs',	''),
('/utilisateurs/reset_passwords.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Réinitialisation des mots de passe',	''),
('/utilisateurs/modify_user.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Configuration et gestion des utilisateurs',	''),
('/utilisateurs/mon_compte.php',	'V',	'V',	'V',	'V',	'V',	'V',	'V',	'F',	'Gestion du compte (informations personnelles, mot de passe, ...)',	''),
('/visualisation/classe_classe.php',	'F',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Visualisation graphique des résultats scolaires',	''),
('/visualisation/eleve_classe.php',	'F',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Visualisation graphique des résultats scolaires',	''),
('/visualisation/eleve_eleve.php',	'F',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Visualisation graphique des résultats scolaires',	''),
('/visualisation/evol_eleve_classe.php',	'F',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Visualisation graphique des résultats scolaires',	''),
('/visualisation/evol_eleve.php',	'F',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Visualisation graphique des résultats scolaires',	''),
('/visualisation/index.php',	'F',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Visualisation graphique des résultats scolaires',	''),
('/visualisation/stats_classe.php',	'F',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Visualisation graphique des résultats scolaires',	''),
('/classes/classes_param.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Configuration et gestion des classes',	''),
('/fpdf/imprime_pdf.php',	'V',	'V',	'V',	'V',	'F',	'F',	'V',	'F',	'',	''),
('/etablissements/import_etab_csv.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Configuration et gestion des établissements',	''),
('/saisie/import_app_cons.php',	'F',	'V',	'F',	'V',	'F',	'F',	'V',	'F',	'Importation csv des avis du conseil de classe',	''),
('/messagerie/index.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Gestion de la messagerie',	''),
('/absences/import_absences_gep.php',	'F',	'F',	'V',	'F',	'F',	'F',	'V',	'F',	'Saisie des absences',	''),
('/absences/seq_gep_absences.php',	'F',	'F',	'V',	'F',	'F',	'F',	'V',	'F',	'Saisie des absences',	''),
('/utilitaires/clean_tables.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Maintenance',	''),
('/gestion/contacter_admin.php',	'V',	'V',	'V',	'V',	'V',	'V',	'V',	'F',	'',	''),
('/mod_absences/gestion/gestion_absences.php',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'Gestion des absences',	''),
('/mod_absences/gestion/impression_absences_liste.php',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'Gestion des absences',	''),
('/mod_absences/gestion/impression_absences.php',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'Gestion des absences',	''),
('/mod_absences/gestion/select.php',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'Gestion des absences',	''),
('/mod_absences/gestion/ajout_ret.php',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'Gestion des absences',	''),
('/mod_absences/gestion/ajout_dip.php',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'Gestion des absences',	''),
('/mod_absences/gestion/ajout_inf.php',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'Gestion des absences',	''),
('/mod_absences/gestion/ajout_abs.php',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'Gestion des absences',	''),
('/mod_absences/gestion/bilan_absence.php',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'Gestion des absences',	''),
('/mod_absences/gestion/bilan.php',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'Gestion des absences',	''),
('/mod_absences/gestion/lettre_aux_parents.php',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'Gestion des absences',	''),
('/mod_absences/lib/tableau.php',	'F',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'',	''),
('/mod_absences/lib/tableau_pdf.php',	'F',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'',	''),
('/mod_absences/admin/index.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Administration du module absences',	''),
('/mod_absences/admin/admin_motifs_absences.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Administration du module absences',	''),
('/edt_organisation/admin_periodes_absences.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Administration du module absences',	''),
('/mod_absences/lib/liste_absences.php',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'F',	'',	''),
('/mod_absences/lib/graphiques.php',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'',	''),
('/mod_absences/professeurs/prof_ajout_abs.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Ajout des absences en classe',	''),
('/mod_absences/admin/admin_actions_absences.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Gestion des actions absences',	''),
('/mod_trombinoscopes/trombinoscopes.php',	'V',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'Visualiser le trombinoscope',	''),
('/mod_trombinoscopes/trombi_impr.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Visualiser le trombinoscope',	''),
('/mod_trombinoscopes/trombinoscopes_admin.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Administration du trombinoscope',	''),
('/cahier_notes/visu_toutes_notes2.php',	'F',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Visualisation des moyennes des carnets de notes',	''),
('/cahier_notes/index2.php',	'F',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Visualisation des moyennes des carnets de notes',	''),
('/utilitaires/verif_groupes.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Vérification des incohérences d appartenances à des groupes',	''),
('/referencement.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Référencement de Gepi sur la base centralisée des utilisateurs de Gepi',	''),
('/cahier_notes/visu_cc_elv.php',	'F',	'F',	'F',	'F',	'V',	'V',	'F',	'F',	'Carnet de notes - visualisation par les élèves',	''),
('/mod_listes_perso/index.php',	'F',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Listes personnelles',	''),
('/mod_listes_perso/index_admin.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Listes personnelles',	''),
('/utilisateurs/tab_profs_matieres.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Affectation des matieres aux professeurs',	''),
('/matieres/matieres_csv.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Importation des matières depuis un fichier CSV',	''),
('/groupes/edit_class_grp_lot.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Gestion des enseignements simples par lot.',	''),
('/groupes/visu_profs_class.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Visualisation des équipes pédagogiques',	''),
('/groupes/popup.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Visualisation des équipes pédagogiques',	''),
('/visualisation/affiche_eleve.php',	'F',	'V',	'V',	'V',	'V',	'V',	'F',	'F',	'Visualisation graphique des résultats scolaires',	''),
('/visualisation/draw_graphe.php',	'F',	'V',	'V',	'V',	'V',	'V',	'F',	'F',	'Visualisation graphique des résultats scolaires',	''),
('/visualisation/draw_graphe_star.php',	'F',	'V',	'V',	'V',	'V',	'V',	'F',	'F',	'Visualisation graphique des résultats scolaires',	''),
('/visualisation/draw_graphe_svg.php',	'F',	'V',	'V',	'V',	'V',	'V',	'F',	'F',	'Visualisation graphique des résultats scolaires',	''),
('/groupes/mes_listes.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Accès aux CSV des listes d élèves',	''),
('/groupes/get_csv.php',	'V',	'V',	'V',	'V',	'F',	'F',	'V',	'F',	'Génération de CSV élèves',	''),
('/visualisation/choix_couleurs.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Choix des couleurs des graphiques des résultats scolaires',	''),
('/visualisation/couleur.php',	'F',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Choix d une couleur pour le graphique des résultats scolaires',	''),
('/gestion/config_prefs.php',	'V',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Définition des préférences d utilisateurs',	''),
('/utilitaires/recalcul_moy_conteneurs.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Correction des moyennes des conteneurs',	''),
('/saisie/commentaires_types.php',	'V',	'V',	'V',	'V',	'F',	'F',	'V',	'F',	'Saisie de commentaires-types',	''),
('/mod_absences/lib/fiche_eleve.php',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'F',	'Fiche du suivie de l\'élève',	''),
('/cahier_notes/releve_pdf.php',	'V',	'V',	'V',	'V',	'F',	'F',	'V',	'F',	'Relevé de note au format PDF',	''),
('/impression/parametres_impression_pdf.php',	'F',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Impression des listes PDF; réglage des paramètres',	''),
('/impression/impression_serie.php',	'F',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Impression des listes (PDF) en série',	''),
('/impression/impression.php',	'F',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Impression rapide d une listes (PDF) ',	''),
('/impression/liste_pdf.php',	'F',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Impression des listes (PDF)',	''),
('/init_xml/lecture_xml_sconet.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation année scolaire',	''),
('/init_xml/init_pp.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation année scolaire',	''),
('/init_xml/clean_tables.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation année scolaire',	''),
('/init_xml/step2.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation année scolaire',	''),
('/init_xml/step1.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation année scolaire',	''),
('/init_xml/disciplines_csv.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation année scolaire',	''),
('/init_xml/prof_disc_classe_csv.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation année scolaire',	''),
('/init_xml/lecture_xml_sts_emp.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation année scolaire',	''),
('/init_xml/prof_csv.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation année scolaire',	''),
('/init_xml/index.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation année scolaire',	''),
('/init_xml/init_options.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation année scolaire',	''),
('/init_xml/save_csv.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation année scolaire',	''),
('/init_xml/responsables.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation année scolaire',	''),
('/init_xml/step3.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation année scolaire',	''),
('/responsables/maj_import.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Mise à jour depuis Sconet',	''),
('/responsables/conversion.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Conversion des données responsables',	''),
('/utilisateurs/create_responsable.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Création des utilisateurs au statut responsable',	''),
('/utilisateurs/create_eleve.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Création des utilisateurs au statut responsable',	''),
('/utilisateurs/edit_responsable.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Edition des utilisateurs au statut responsable',	''),
('/utilisateurs/edit_eleve.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Edition des utilisateurs au statut élève',	''),
('/cahier_texte/consultation.php',	'F',	'F',	'F',	'F',	'V',	'V',	'F',	'F',	'Consultation des cahiers de texte',	''),
('/cahier_texte/see_all.php',	'F',	'V',	'V',	'F',	'V',	'V',	'F',	'F',	'Consultation des cahiers de texte',	''),
('/cahier_texte/visu_prof_jour.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Acces_a_son_cahier_de_textes_personnel',	''),
('/gestion/droits_acces.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Paramétrage des droits d accès',	''),
('/groupes/visu_profs_eleve.php',	'F',	'F',	'F',	'F',	'V',	'V',	'F',	'F',	'Consultation équipe pédagogique',	''),
('/saisie/impression_avis.php',	'F',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Impression des avis trimestrielles des conseils de classe.',	''),
('/impression/avis_pdf.php',	'F',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Impression des avis trimestrielles des conseils de classe. Module PDF',	''),
('/impression/parametres_impression_pdf_avis.php',	'F',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Impression des avis conseil classe PDF; reglage des parametres',	''),
('/utilisateurs/password_csv.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Export des identifiants et mots de passe en csv',	''),
('/impression/password_pdf.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Impression des identifiants et des mots de passe en PDF',	''),
('/bulletin/buletin_pdf.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Bulletin scolaire au format PDF',	''),
('/mod_absences/gestion/etiquette_pdf.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Etiquette au format PDF',	''),
('/mod_absences/lib/export_csv.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Fichier d\'exportation en csv des absences',	''),
('/mod_absences/gestion/statistiques.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Statistique du module vie scolaire',	'1'),
('/mod_absences/lib/graph_camembert.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'graphique camembert',	''),
('/mod_absences/lib/graph_ligne.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'graphique camembert',	''),
('/edt_organisation/admin_horaire_ouverture.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Définition des horaires d\'ouverture de l\'établissement',	''),
('/edt_organisation/admin_config_semaines.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Configuration des types de semaines',	''),
('/mod_absences/gestion/fiche_pdf.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Fiche récapitulatif des absences',	''),
('/mod_absences/lib/graph_double_ligne.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'graphique absence et retard sur le même graphique',	''),
('/bulletin/param_bull_pdf.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'page de gestion des parametres du bulletin pdf',	''),
('/bulletin/bulletin_pdf_avec_modele_classe.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'page generant le bulletin pdf en fonction du modele affecte a la classe ',	''),
('/gestion/security_panel.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Panneau de controle des atteintes a la securite',	''),
('/gestion/security_policy.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'definition des politiques de securite',	''),
('/mod_absences/gestion/alert_suivi.php',	'V',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'système d\'alerte de suivi d\'élève',	''),
('/gestion/efface_photos.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Suppression des photos non associées à des élèves',	''),
('/responsables/gerer_adr.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Gestion des adresses de responsables',	''),
('/responsables/choix_adr_existante.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Choix adresse de responsable existante',	''),
('/cahier_notes/export_cahier_notes.php',	'F',	'V',	'F',	'F',	'F',	'F',	'V',	'F',	'Export CSV/ODS du cahier de notes',	''),
('/cahier_notes/import_cahier_notes.php',	'F',	'V',	'F',	'F',	'F',	'F',	'V',	'F',	'Import CSV du cahier de notes',	''),
('/gestion/options_connect.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Options de connexions',	''),
('/eleves/add_eleve.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Gestion des élèves',	''),
('/saisie/export_class_ods.php',	'F',	'V',	'F',	'F',	'F',	'F',	'V',	'F',	'Export ODS des notes/appréciations',	''),
('/gestion/gestion_temp_dir.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Gestion des dossiers temporaires d utilisateurs',	''),
('/gestion/param_couleurs.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Définition des couleurs pour Gepi',	''),
('/utilisateurs/creer_remplacant.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'script de création d un remplaçant',	''),
('/mod_absences/gestion/lettre_pdf.php',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'Publipostage des lettres d absences PDF',	'1'),
('/accueil_simpl_prof.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Page d accueil simplifiée pour les profs',	''),
('/init_xml2/index.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation année scolaire',	''),
('/init_xml2/step1.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation année scolaire',	''),
('/init_xml2/step2.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation année scolaire',	''),
('/init_xml2/step3.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation année scolaire',	''),
('/init_xml2/responsables.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation année scolaire',	''),
('/init_xml2/matieres.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation année scolaire',	''),
('/init_xml2/professeurs.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation année scolaire',	''),
('/init_xml2/prof_disc_classe_csv.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation année scolaire',	''),
('/init_xml2/init_options.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation année scolaire',	''),
('/init_xml2/init_pp.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation année scolaire',	''),
('/init_xml2/clean_tables.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation année scolaire',	''),
('/init_xml2/clean_temp.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation année scolaire',	''),
('/mod_annees_anterieures/conservation_annee_anterieure.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Conservation des données antérieures',	''),
('/mod_annees_anterieures/consultation_annee_anterieure.php',	'V',	'V',	'V',	'V',	'V',	'V',	'F',	'F',	'Consultation des données d années antérieures',	''),
('/mod_annees_anterieures/index.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Index données antérieures',	''),
('/mod_annees_anterieures/popup_annee_anterieure.php',	'V',	'V',	'V',	'V',	'V',	'V',	'F',	'F',	'Consultation des données antérieures',	''),
('/mod_annees_anterieures/admin.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Activation/désactivation du module données antérieures',	''),
('/mod_annees_anterieures/nettoyer_annee_anterieure.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Suppression de données antérieures',	''),
('/responsables/maj_import1.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Mise à jour depuis Sconet',	''),
('/responsables/maj_import2.php',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Mise à jour depuis Sconet',	''),
('/mod_annees_anterieures/corriger_ine.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Correction d INE dans la table annees_anterieures',	''),
('/mod_annees_anterieures/liste_eleves_ajax.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Recherche d élèves',	''),
('/mod_absences/lib/graph_double_ligne_fiche.php',	'V',	'V',	'V',	'F',	'F',	'F',	'V',	'F',	'Graphique de la fiche élève',	'1'),
('/edt_organisation/edt_calendrier.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation du calendrier',	''),
('/edt_organisation/index_edt.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Gestion des emplois du temps',	''),
('/edt_organisation/edt_initialiser.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation des emplois du temps',	''),
('/edt_organisation/effacer_cours.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Effacer un cours des EdT',	''),
('/edt_organisation/ajouter_salle.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Gestion des salles',	''),
('/edt_organisation/edt_parametrer.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Gérer les paramètres EdT',	''),
('/edt_organisation/voir_groupe.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Voir les groupes de Gepi',	''),
('/edt_organisation/modif_edt_tempo.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Modification temporaire des EdT',	''),
('/edt_organisation/edt_init_xml.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation EdT par xml',	''),
('/edt_organisation/edt_init_csv.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'initialisation EdT par csv',	''),
('/edt_organisation/edt_init_csv2.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'initialisation EdT par un autre csv',	''),
('/edt_organisation/edt_init_texte.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'initialisation EdT par un fichier texte',	''),
('/edt_organisation/edt_init_concordance.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'initialisation EdT par un fichier texte',	''),
('/edt_organisation/edt_init_concordance2.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'initialisation EdT par un autre fichier csv',	''),
('/edt_organisation/modifier_cours.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Modifier un cours',	''),
('/edt_organisation/modifier_cours_popup.php',	'V',	'V',	'F',	'V',	'F',	'F',	'F',	'F',	'Modifier un cours',	''),
('/edt_organisation/edt.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Régler le module emploi du temps',	''),
('/edt_organisation/edt_eleve.php',	'F',	'F',	'F',	'F',	'V',	'V',	'F',	'F',	'Régler le module emploi du temps',	''),
('/edt_organisation/edt_param_couleurs.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Régler les couleurs des matières (EdT)',	''),
('/edt_organisation/ajax_edtcouleurs.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Changer les couleurs des matières (EdT)',	''),
('/absences/import_absences_sconet.php',	'F',	'F',	'V',	'F',	'F',	'F',	'V',	'F',	'Saisie des absences',	''),
('/mod_absences/admin/admin_config_calendrier.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Définir les différentes périodes',	''),
('/bulletin/export_modele_pdf.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'exportation en csv des modeles de bulletin pdf',	''),
('/absences/consulter_absences.php',	'F',	'F',	'V',	'F',	'F',	'F',	'V',	'F',	'Consulter les absences',	''),
('/mod_absences/professeurs/bilan_absences_professeur.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Bilan des absences pour chaque professeur',	''),
('/mod_absences/professeurs/bilan_absences_classe.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Bilan des absences pour chaque professeur',	''),
('/mod_absences/gestion/voir_absences_viescolaire.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Consulter les absences du jour',	''),
('/mod_absences/gestion/bilan_absences_quotidien.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Consulter les absences par créneau',	''),
('/mod_absences/gestion/bilan_absences_quotidien_pdf.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Consulter les absences par créneau en pdf',	''),
('/mod_absences/gestion/bilan_absences_classe.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Consulter les absences par classe',	''),
('/mod_absences/gestion/bilan_repas_quotidien.php',	'F',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Consulter l inscription aux repas',	''),
('/mod_absences/absences.php',	'F',	'F',	'F',	'F',	'F',	'V',	'F',	'F',	'Consulter les absences de son enfant',	''),
('/mod_absences/admin/interface_abs.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Paramétrer les interfaces des professeurs',	''),
('/absences/import_absences_gepi.php',	'F',	'F',	'V',	'V',	'F',	'F',	'V',	'F',	'Page d\'importation des absences de gepi mod_absences',	'1'),
('/lib/change_mode_header.php',	'V',	'V',	'V',	'V',	'V',	'V',	'V',	'F',	'Page AJAX pour changer la variable cacher_header',	'1'),
('/saisie/recopie_moyennes.php',	'F',	'F',	'F',	'F',	'F',	'F',	'V',	'F',	'Recopie des moyennes',	''),
('/groupes/fusion_group.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Fusionner des groupes',	''),
('/gestion/security_panel_archives.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'page archive du panneau de sécurité',	''),
('/lib/header_barre_menu.php/',	'V',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Barre horizontale de menu',	''),
('/responsables/corrige_ele_id.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Correction des ELE_ID d apres Sconet',	''),
('/mod_inscription/inscription_admin.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'(De)activation du module inscription',	''),
('/mod_inscription/inscription_index.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'accès au module configuration',	''),
('/mod_inscription/inscription_config.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Configuration du module inscription',	''),
('/mod_inscription/help.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Configuration du module inscription',	''),
('/aid/index_fiches.php',	'V',	'V',	'V',	'F',	'V',	'F',	'F',	'F',	'Outils complémentaires de gestion des AIDs',	''),
('/aid/visu_fiches.php',	'V',	'V',	'V',	'F',	'V',	'F',	'F',	'F',	'Outils complémentaires de gestion des AIDs',	''),
('/aid/modif_fiches.php',	'V',	'V',	'V',	'V',	'V',	'V',	'F',	'F',	'Outils complémentaires de gestion des AIDs',	''),
('/aid/config_aid_fiches_projet.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Configuration des outils complémentaires de gestion des AIDs',	''),
('/aid/config_aid_matieres.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Configuration des outils complémentaires de gestion des AIDs',	''),
('/aid/config_aid_productions.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Configuration des outils complémentaires de gestion des AIDs',	''),
('/aid/annees_anterieures_accueil.php',	'V',	'V',	'V',	'F',	'V',	'F',	'F',	'F',	'Configuration des AID',	''),
('/classes/acces_appreciations.php',	'V',	'V',	'F',	'V',	'F',	'F',	'F',	'F',	'Configuration de la restriction d accès aux appréciations pour les élèves et responsables',	''),
('/mod_notanet/notanet_admin.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Gestion du module NOTANET',	''),
('/mod_notanet/index.php',	'V',	'V',	'F',	'V',	'F',	'F',	'F',	'F',	'Notanet: Accueil',	''),
('/mod_notanet/extract_moy.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Notanet: Extraction des moyennes',	''),
('/mod_notanet/corrige_extract_moy.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Notanet: Extraction des moyennes',	''),
('/mod_notanet/select_eleves.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Notanet: Associations élèves/type de brevet',	''),
('/mod_notanet/select_matieres.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Notanet: Associations matières/type de brevet',	''),
('/mod_notanet/saisie_app.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Notanet: Saisie des appréciations',	''),
('/mod_notanet/generer_csv.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Notanet: Génération de CSV',	''),
('/mod_notanet/choix_generation_csv.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Notanet: Génération de CSV',	''),
('/mod_notanet/verrouillage_saisie_app.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Notanet: (Dé)Verrouillage des saisies',	''),
('/bulletin/bull_index.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Edition des bulletins',	'1'),
('/cahier_notes/visu_releve_notes_bis.php',	'F',	'V',	'V',	'V',	'V',	'V',	'V',	'F',	'Relevé de notes',	'1'),
('/cahier_notes/param_releve_html.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Paramètres du relevé de notes',	'1'),
('/utilisateurs/creer_statut.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Créer des statuts personnalisés',	''),
('/utilisateurs/creer_statut_admin.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Autoriser la création des statuts personnalisés',	''),
('/classes/changement_eleve_classe.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Changement de classe pour un élève',	'1'),
('/edt_gestion_gr/edt_aff_gr.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Gérer les groupes du module EdT',	''),
('/edt_gestion_gr/edt_ajax_win.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Gérer les groupes du module EdT',	''),
('/edt_gestion_gr/edt_liste_eleves.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Gérer les groupes du module EdT',	''),
('/edt_gestion_gr/edt_liste_profs.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Gérer les groupes du module EdT',	''),
('/edt_gestion_gr/edt_win.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Gérer les groupes du module EdT',	''),
('/mod_notanet/saisie_avis.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Notanet: Saisie avis chef etablissement',	''),
('/mod_notanet/saisie_b2i_a2.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Notanet: Saisie socles B2i et A2',	''),
('/mod_notanet/poitiers/fiches_brevet.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Accès à l export NOTANET',	''),
('/mod_notanet/poitiers/param_fiche_brevet.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Notanet: Paramètres d impression',	''),
('/mod_notanet/rouen/fiches_brevet.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Accès à l export NOTANET',	''),
('/eleves/liste_eleves.php',	'V',	'V',	'V',	'V',	'F',	'F',	'V',	'F',	'Lister_des_eleves',	''),
('/eleves/visu_eleve.php',	'V',	'V',	'V',	'V',	'F',	'F',	'V',	'F',	'Consultation_d_un_eleve',	''),
('/cahier_texte_admin/rss_cdt_admin.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Gerer les flux rss du cdt',	''),
('/matieres/suppr_matiere.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Suppression d une matiere',	''),
('/mod_annees_anterieures/archivage_aid.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Fiches projets',	'1'),
('/eleves/import_bull_eleve.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Importation bulletin élève',	''),
('/eleves/export_bull_eleve.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Exportation bulletin élève',	''),
('/mod_ent/index.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Gestion de l intégration de GEPI dans un ENT',	''),
('/mod_ent/gestion_ent_eleves.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Gestion de l intégration de GEPI dans un ENT',	''),
('/mod_ent/gestion_ent_profs.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Gestion de l intégration de GEPI dans un ENT',	''),
('/mod_ent/miseajour_ent_eleves.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Gestion de l intégration de GEPI dans un ENT',	''),
('/cahier_texte_admin/visa_ct.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Page de signature des cahiers de texte',	''),
('/public/index.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Gestion des cahier de texte',	''),
('/saisie/saisie_cmnt_type_prof.php',	'F',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Saisie appréciations-types pour les profs',	''),
('/mod_discipline/traiter_incident.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Discipline: Traitement',	''),
('/mod_discipline/saisie_incident.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Discipline: Saisie incident',	''),
('/mod_discipline/occupation_lieu_heure.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Discipline: Occupation lieu',	''),
('/mod_discipline/liste_sanctions_jour.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Discipline: Liste',	''),
('/mod_discipline/index.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Discipline: Index',	''),
('/mod_discipline/incidents_sans_protagonistes.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Discipline: Incidents sans protagonistes',	''),
('/mod_discipline/edt_eleve.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Discipline: EDT élève',	''),
('/mod_discipline/ajout_sanction.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Discipline: Ajout sanction',	''),
('/mod_discipline/saisie_sanction.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Discipline: Saisie sanction',	''),
('/mod_discipline/definir_roles.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Discipline: Définition des rôles',	''),
('/mod_discipline/definir_mesures.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Discipline: Définition des mesures',	''),
('/mod_discipline/sauve_role.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Discipline: Svg rôle incident',	''),
('/mod_discipline/definir_autres_sanctions.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Discipline: Définir types sanctions',	''),
('/mod_discipline/liste_retenues_jour.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Discipline: Liste des retenues du jour',	''),
('/mod_discipline/avertir_famille.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Discipline: Avertir famille incident',	''),
('/mod_discipline/avertir_famille_html.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Discipline: Avertir famille incident',	''),
('/mod_discipline/sauve_famille_avertie.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Discipline: Svg famille avertie',	''),
('/mod_discipline/discipline_admin.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Discipline: Activation/desactivation du module',	''),
('/classes/classes_ajax_lib.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Page appelée via ajax.',	''),
('/saisie/saisie_secours_eleve.php',	'F',	'F',	'F',	'F',	'F',	'F',	'V',	'F',	'Saisie notes/appréciations pour un élève en compte secours',	''),
('/responsables/dedoublonnage_adresses.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Dédoublonnage des adresses responsables',	''),
('/mod_ooo/rapport_incident.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Modèle Ooo : Rapport Incident',	''),
('/mod_ooo/gerer_modeles_ooo.php',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'F',	'Modèle Ooo : Gérer et utiliser les modèles',	''),
('/mod_ooo/ooo_admin.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Modèle Ooo : Admin',	''),
('/mod_ooo/retenue.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Modèle Ooo : Retenue',	''),
('/mod_ooo/formulaire_retenue.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Modèle Ooo : formulaire retenue',	''),
('/mod_ooo/index.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Modèle Ooo: Index : Index',	''),
('/mod_discipline/update_colonne_retenue.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Discipline: Affichage d une imprimante pour le responsable d un incident',	''),
('/mod_discipline/definir_lieux.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Discipline: Définir les lieux',	''),
('/mod_notanet/fb_rouen_pdf.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Fiches brevet PDF pour Rouen',	''),
('/mod_notanet/fb_montpellier_pdf.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Fiches brevet PDF pour Montpellier',	''),
('/mod_genese_classes/index.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Génèse des classes: Accueil',	''),
('/mod_genese_classes/admin.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Génèse des classes: Activation/désactivation',	''),
('/mod_genese_classes/select_options.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Génèse des classes: Choix des options',	''),
('/mod_genese_classes/select_eleves_options.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Génèse des classes: Choix des options des élèves',	''),
('/mod_genese_classes/select_classes.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Génèse des classes: Choix des classes',	''),
('/mod_genese_classes/saisie_contraintes_opt_classe.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Génèse des classes: Saisie des contraintes options/classes',	''),
('/mod_genese_classes/liste_classe_fut.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Génèse des classes: Liste des classes futures (appel ajax)',	''),
('/mod_genese_classes/affiche_listes.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Génèse des classes: Affichage de listes',	''),
('/mod_genese_classes/genere_ods.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Génèse des classes: Génération d un fichier ODS de listes',	''),
('/mod_genese_classes/affect_eleves_classes.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Génèse des classes: Affectation des élèves',	''),
('/mod_genese_classes/select_arriv_red.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Génèse des classes: Sélection des arrivants/redoublants',	''),
('/mod_genese_classes/liste_options.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Génèse des classes: Liste des options de classes existantes',	''),
('/mod_genese_classes/import_options.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Génèse des classes: Import options depuis CSV',	''),
('/eleves/import_communes.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Import des communes de naissance',	''),
('/mod_notanet/fb_lille_pdf.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Fiches brevet PDF pour Lille',	''),
('/mod_notanet/fb_creteil_pdf.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Fiches brevet PDF pour Creteil',	''),
('/mod_plugins/index.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Ajouter/enlever des plugins',	''),
('/saisie/export_cmnt_type_prof.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Export appréciations-types pour les profs',	''),
('/mod_discipline/disc_stat.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Discipline: Statistiques',	''),
('/mod_epreuve_blanche/admin.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Epreuves blanches: Activation/désactivation du module',	''),
('/mod_examen_blanc/admin.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Examens blancs: Activation/désactivation du module',	''),
('/mod_epreuve_blanche/index.php',	'V',	'V',	'F',	'V',	'F',	'F',	'F',	'F',	'Epreuve blanche: Accueil',	''),
('/mod_epreuve_blanche/transfert_cn.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Epreuve blanche: Transfert vers carnet de notes',	''),
('/mod_epreuve_blanche/saisie_notes.php',	'V',	'V',	'F',	'V',	'F',	'F',	'F',	'F',	'Epreuve blanche: Saisie des notes',	''),
('/mod_epreuve_blanche/genere_emargement.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Epreuve blanche: Génération émargement',	''),
('/mod_epreuve_blanche/definir_salles.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Epreuve blanche: Définir les salles',	''),
('/mod_epreuve_blanche/attribuer_copies.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Epreuve blanche: Attribuer les copies aux professeurs',	''),
('/mod_epreuve_blanche/bilan.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Epreuve blanche: Bilan',	''),
('/mod_epreuve_blanche/genere_etiquettes.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Epreuve blanche: Génération étiquettes',	''),
('/mod_examen_blanc/saisie_notes.php',	'V',	'V',	'F',	'V',	'F',	'F',	'F',	'F',	'Examen blanc: Saisie devoir hors enseignement',	''),
('/mod_examen_blanc/index.php',	'V',	'V',	'F',	'V',	'F',	'F',	'F',	'F',	'Examen blanc: Accueil',	''),
('/mod_examen_blanc/releve.php',	'V',	'V',	'F',	'V',	'F',	'F',	'F',	'F',	'Examen blanc: Relevé',	''),
('/mod_examen_blanc/bull_exb.php',	'V',	'V',	'F',	'V',	'F',	'F',	'F',	'F',	'Examen blanc: Bulletins',	''),
('/saisie/saisie_synthese_app_classe.php',	'F',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Synthèse des appréciations sur le groupe classe.',	''),
('/gestion/saisie_message_connexion.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Saisie de messages de connexion.',	''),
('/groupes/repartition_ele_grp.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Répartir des élèves dans des groupes',	''),
('/prepa_conseil/edit_limite_bis.php',	'V',	'V',	'V',	'V',	'V',	'V',	'F',	'F',	'Edition des bulletins simplifiés (documents de travail)',	''),
('/prepa_conseil/index2bis.php',	'F',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Visualisation des notes par classes',	''),
('/prepa_conseil/index3bis.php',	'F',	'V',	'V',	'V',	'V',	'V',	'F',	'F',	'Edition des bulletins simplifiés (documents de travail)',	''),
('/prepa_conseil/visu_toutes_notes_bis.php',	'F',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Visualisation des notes par classes',	''),
('/utilitaires/import_pays.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Import des pays',	''),
('/mod_apb/admin.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Gestion du module Admissions PostBac',	''),
('/mod_apb/index.php',	'F',	'F',	'F',	'V',	'F',	'F',	'F',	'V',	'Export XML pour le système Admissions Post-Bac',	''),
('/mod_apb/export_xml.php',	'F',	'F',	'F',	'V',	'F',	'F',	'F',	'V',	'Export XML pour le système Admissions Post-Bac',	''),
('/mod_gest_aid/admin.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Gestionnaires AID',	''),
('/saisie/ajax_edit_limite.php',	'V',	'V',	'V',	'V',	'V',	'V',	'F',	'F',	'Edition des bulletins simplifiés (documents de travail)',	''),
('/mod_discipline/check_nature_incident.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'V',	'Discipline: Recherche de natures d incident',	''),
('/groupes/signalement_eleves.php',	'F',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Groupes: signalement des erreurs d affectation élève',	''),
('/bulletin/envoi_mail.php',	'V',	'F',	'V',	'V',	'F',	'F',	'V',	'F',	'Envoi de mail via ajax',	''),
('/mod_discipline/destinataires_alertes.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Parametrage des destinataires de mail d alerte',	''),
('/init_scribe_ng/index.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation Scribe NG - index',	''),
('/init_scribe_ng/etape1.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation Scribe NG - etape 1',	''),
('/init_scribe_ng/etape2.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation Scribe NG - etape 2',	''),
('/init_scribe_ng/etape3.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation Scribe NG - etape 3',	''),
('/init_scribe_ng/etape4.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation Scribe NG - etape 4',	''),
('/init_scribe_ng/etape5.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation Scribe NG - etape 5',	''),
('/init_scribe_ng/etape6.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation Scribe NG - etape 6',	''),
('/init_scribe_ng/etape7.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation Scribe NG - etape 7',	''),
('/mod_ects/ects_admin.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Module ECTS : Admin',	''),
('/mod_ects/index_saisie.php',	'F',	'V',	'F',	'V',	'F',	'F',	'F',	'F',	'Module ECTS : Accueil saisie',	''),
('/mod_ects/saisie_ects.php',	'F',	'V',	'F',	'V',	'F',	'F',	'F',	'F',	'Module ECTS : Saisie',	''),
('/mod_ects/edition.php',	'F',	'V',	'F',	'V',	'F',	'F',	'F',	'F',	'Module ECTS : Edition des documents',	''),
('/mod_ooo/documents_ects.php',	'F',	'V',	'F',	'V',	'F',	'F',	'F',	'F',	'Module ECTS : Génération des documents',	''),
('/mod_ects/recapitulatif.php',	'F',	'V',	'F',	'V',	'F',	'F',	'F',	'F',	'Module ECTS : Recapitulatif globaux',	''),
('/mod_discipline/stats2/index.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Module discipline: Statistiques',	''),
('/mod_discipline/definir_categories.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Discipline: Définir les catégories',	''),
('/mod_abs2/admin/index.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'V',	'Administration du module absences',	''),
('/mod_abs2/admin/admin_motifs_absences.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'V',	'Administration du module absences',	''),
('/mod_abs2/admin/admin_types_absences.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'V',	'Administration du module absences',	''),
('/mod_abs2/admin/admin_lieux_absences.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'V',	'Administration du module absences',	''),
('/mod_abs2/admin/admin_justifications_absences.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'V',	'Administration du module absences',	''),
('/mod_abs2/admin/admin_table_agregation.php',	'V',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'Administration du module absences',	''),
('/mod_abs2/admin/admin_actions_absences.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Administration du module absences',	''),
('/mod_abs2/index.php',	'F',	'V',	'V',	'V',	'F',	'F',	'V',	'V',	'Administration du module absences',	''),
('/mod_abs2/saisir_groupe.php',	'V',	'V',	'V',	'V',	'F',	'F',	'V',	'V',	'Affichage du formulaire de saisie de absences',	''),
('/mod_abs2/absences_du_jour.php',	'V',	'F',	'V',	'V',	'F',	'F',	'V',	'F',	'Affichage des absences du jour',	''),
('/mod_abs2/enregistrement_saisie_groupe.php',	'V',	'V',	'V',	'V',	'F',	'F',	'V',	'V',	'Enregistrement des saisies d un groupe',	''),
('/mod_abs2/liste_saisies.php',	'V',	'V',	'V',	'V',	'F',	'F',	'V',	'V',	'Liste des saisies',	''),
('/mod_abs2/liste_traitements.php',	'V',	'F',	'V',	'V',	'F',	'F',	'V',	'F',	'Liste des traitements',	''),
('/mod_abs2/liste_notifications.php',	'V',	'F',	'V',	'V',	'F',	'F',	'V',	'F',	'Liste des notifications',	''),
('/mod_abs2/liste_saisies_selection_traitement.php',	'V',	'F',	'V',	'V',	'F',	'F',	'V',	'F',	'Liste des saisits pour faire les traitement',	''),
('/mod_abs2/visu_saisie.php',	'V',	'V',	'V',	'V',	'F',	'F',	'V',	'V',	'Visualisation d une saisies',	''),
('/mod_abs2/visu_traitement.php',	'V',	'V',	'V',	'V',	'F',	'F',	'V',	'V',	'Visualisation d une saisie',	''),
('/mod_abs2/visu_notification.php',	'V',	'F',	'V',	'V',	'F',	'F',	'V',	'F',	'Visualisation d une notification',	''),
('/mod_abs2/enregistrement_modif_saisie.php',	'V',	'V',	'V',	'V',	'F',	'F',	'V',	'V',	'Modification d une saisies',	''),
('/mod_abs2/enregistrement_modif_traitement.php',	'V',	'F',	'V',	'V',	'F',	'F',	'V',	'F',	'Modification d un traitement',	''),
('/mod_abs2/enregistrement_modif_notification.php',	'V',	'F',	'V',	'V',	'F',	'F',	'V',	'F',	'Modification d une notification',	''),
('/mod_abs2/generer_notification.php',	'V',	'F',	'V',	'V',	'F',	'F',	'V',	'F',	'generer une notification',	''),
('/mod_abs2/saisir_eleve.php',	'V',	'F',	'V',	'V',	'F',	'F',	'V',	'V',	'Saisir l absence d un eleve',	''),
('/mod_abs2/enregistrement_saisie_eleve.php',	'V',	'F',	'V',	'V',	'F',	'F',	'V',	'V',	'Enregistrer absence d un eleve',	''),
('/mod_abs2/creation_traitement.php',	'V',	'F',	'V',	'V',	'F',	'F',	'V',	'F',	'Crer un traitement',	''),
('/mod_discipline/saisie_incident_abs2.php',	'V',	'V',	'V',	'V',	'F',	'F',	'V',	'V',	'Saisir un incident relatif a une absence',	''),
('/mod_abs2/tableau_des_appels.php',	'V',	'F',	'V',	'V',	'F',	'F',	'V',	'F',	'Visualisation du tableaux des saisies',	''),
('/mod_abs2/bilan_du_jour.php',	'V',	'F',	'V',	'V',	'F',	'F',	'V',	'F',	'Visualisation du bilan du jour',	''),
('/mod_abs2/extraction_saisies.php',	'V',	'F',	'V',	'V',	'F',	'F',	'V',	'F',	'Extraction des saisies',	''),
('/mod_abs2/extraction_demi-journees.php',	'V',	'F',	'V',	'V',	'F',	'F',	'V',	'F',	'Extraction des saisies',	''),
('/mod_abs2/ajax_edt_eleve.php',	'V',	'F',	'V',	'V',	'F',	'F',	'V',	'F',	'Affichage edt',	''),
('/mod_abs2/generer_notifications_par_lot.php',	'F',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Génération groupée des courriers',	''),
('/mod_abs2/bilan_individuel.php',	'F',	'V',	'V',	'V',	'F',	'F',	'F',	'V',	'Bilan individuel des absences eleve',	''),
('/mod_abs2/totaux_du_jour.php',	'F',	'F',	'V',	'V',	'F',	'F',	'F',	'V',	'Totaux du jour des absences',	''),
('/mod_abs2/statistiques.php',	'F',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Statistiques des absences',	''),
('/mod_abs2/stat_justifications.php',	'F',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Statistiques des justifications des absences',	''),
('/bulletin/autorisation_exceptionnelle_saisie_app.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Autorisation exceptionnelle de saisie d appréciation',	''),
('/init_csv/export_tables.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation CSV: Export tables',	''),
('/cahier_texte_2/index.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Cahier de textes',	'1'),
('/cahier_texte_2/ajax_edition_compte_rendu.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Cahier de textes',	'1'),
('/cahier_texte_2/ajax_edition_notice_privee.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Cahier de textes',	'1'),
('/cahier_texte_2/ajax_duplication_notice.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Cahier de textes',	'1'),
('/cahier_texte_2/ajax_affichage_duplication_notice.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Cahier de textes',	'1'),
('/cahier_texte_2/ajax_deplacement_notice.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Cahier de textes',	'1'),
('/cahier_texte_2/ajax_affichage_deplacement_notice.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Cahier de textes',	'1'),
('/cahier_texte_2/ajax_suppression_notice.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Cahier de textes',	'1'),
('/cahier_texte_2/ajax_enregistrement_compte_rendu.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Cahier de textes',	'1'),
('/cahier_texte_2/ajax_enregistrement_notice_privee.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Cahier de textes',	'1'),
('/cahier_texte_2/ajax_edition_devoir.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Cahier de textes',	'1'),
('/cahier_texte_2/ajax_enregistrement_devoir.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Cahier de textes',	'1'),
('/cahier_texte_2/ajax_affichages_liste_notices.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Cahier de textes',	'1'),
('/cahier_texte_2/ajax_affichage_dernieres_notices.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Cahier de textes',	'1'),
('/cahier_texte_2/traite_doc.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Cahier de textes',	'1'),
('/cahier_texte_2/exportcsv.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Cahier de textes',	'1'),
('/cahier_texte_2/consultation.php',	'F',	'F',	'F',	'F',	'V',	'V',	'F',	'F',	'Consultation des cahiers de textes',	''),
('/cahier_texte_2/see_all.php',	'F',	'V',	'V',	'F',	'V',	'V',	'F',	'F',	'Consultation des cahiers de texte',	''),
('/cahier_texte_2/creer_sequence.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Cahier de texte - sequences',	'1'),
('/cahier_texte_2/creer_seq_ajax_step1.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Cahier de texte - sequences',	'1'),
('/mod_trombinoscopes/trombino_pdf.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'V',	'Trombinoscopes PDF',	''),
('/mod_trombinoscopes/trombino_decoupe.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Génération d une grille PDF pour les trombinoscopes,...',	''),
('/groupes/menage_eleves_groupes.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Groupes: Desinscription des eleves sans notes ni appreciations',	''),
('/statistiques/export_donnees_bulletins.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Export de données des bulletins',	''),
('/statistiques/index.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Statistiques: Index',	''),
('/statistiques/classes_effectifs.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Statistiques: classe, effectifs',	''),
('/mod_annees_anterieures/ajax_bulletins.php',	'V',	'V',	'V',	'V',	'V',	'V',	'F',	'V',	'Accès aux bulletins d années antérieures',	''),
('/mod_annees_anterieures/ajax_signaler_faute.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'V',	'Possibilité de signaler une faute de frappe dans une appréciation',	''),
('/eleves/ajax_modif_eleve.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Enregistrement des modifications élève',	''),
('/classes/ajouter_periode.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Classes: Ajouter des périodes',	''),
('/classes/supprimer_periode.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Classes: Supprimer des périodes',	''),
('/groupes/visu_mes_listes.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Accès aux listes d élèves',	''),
('/cahier_notes/index_cc.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Carnet de notes',	'1'),
('/cahier_notes/add_modif_cc_dev.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Carnet de notes',	'1'),
('/cahier_notes/add_modif_cc_eval.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Carnet de notes',	'1'),
('/cahier_notes/saisie_notes_cc.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Carnet de notes',	'1'),
('/cahier_notes/visu_cc.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Carnet de notes',	'1'),
('/responsables/synchro_mail.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Synchronisation des mail responsables',	''),
('/eleves/synchro_mail.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Synchronisation des mail élèves',	''),
('/cahier_texte_2/archivage_cdt.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Archivage des CDT',	''),
('/documents/archives/index.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'V',	'Archives des CDT',	''),
('/saisie/saisie_vocabulaire.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Saisie de vocabulaire',	''),
('/mod_epreuve_blanche/genere_liste_affichage.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Epreuve blanche: Génération liste affichage',	''),
('/cahier_texte_2/ajax_devoirs_classe.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'V',	'Cahiers de textes : Devoirs d une classe pour tel jour',	''),
('/cahier_texte_2/ajax_liste_notices_privees.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Cahiers de textes : Liste des notices privées',	''),
('/mod_ooo/publipostage_ooo.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'V',	'Modèle Ooo : Publipostage',	''),
('/saisie/saisie_mentions.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Saisie de mentions',	''),
('/mod_discipline/visu_disc.php',	'F',	'F',	'F',	'F',	'V',	'V',	'F',	'F',	'Discipline: Accès élève/parent',	''),
('/mod_discipline/definir_natures.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Discipline: Définir les natures',	''),
('/init_xml2/traite_csv_udt.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Import des enseignements via un Export CSV UDT',	''),
('/init_xml2/init_alternatif.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Initialisation année scolaire',	''),
('/mod_examen_blanc/copie_exam.php',	'V',	'V',	'F',	'V',	'F',	'F',	'F',	'F',	'Examen blanc: Copie',	''),
('/mod_sso_table/index.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Gestion de la table de correspondance sso',	''),
('/gestion/changement_d_annee.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Changement d\'année.',	''),
('/absences/import_absences_csv.php',	'F',	'F',	'V',	'F',	'F',	'F',	'V',	'F',	'Saisie des absences',	''),
('/statistiques/stat_connexions.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Statistiques de connexion',	''),
('/groupes/check_enseignements.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Controle des enseignements',	''),
('/bulletin/index.php',	'V',	'V',	'F',	'V',	'F',	'F',	'F',	'F',	'Edition des bulletins',	'1'),
('/lib/ajax_corriger_app.php',	'F',	'V',	'F',	'V',	'F',	'F',	'F',	'V',	'Correction appreciation',	''),
('/mod_annees_anterieures/archivage_bull_pdf.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Génération archives bulletins PDF',	''),
('/mod_abs2/liste_eleves.php',	'F',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Liste des élèves avec les filtes absence',	''),
('/mod_notanet/OOo/imprime_ooo.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Imprime fiches brevet OpenOffice',	''),
('/mod_notanet/OOo/fiches_brevet.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Fiches brevet OpenOffice',	''),
('/mod_notanet/verif_saisies.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Notanet: Verification avant impression des fiches brevet',	''),
('/eleves/modif_sexe.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Eleves: Modification ajax du sexe d un eleve',	''),
('/cahier_texte_2/correction_notices_cdt_formules_maths.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Correction des notices CDT',	''),
('/gestion/gestion_signature.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Gestion signature',	''),
('/mod_abs2/saisir_groupe_plan.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Affichage du formulaire de saisie de absences sur plan de classe',	''),
('/matieres/matiere_ajax_lib.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Ajax',	''),
('/gestion/gestion_infos_actions.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Gestion des actions en attente signalées en page d accueil.',	'1'),
('/responsables/maj_import3.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Mise à jour Sconet',	''),
('/mod_discipline/mod_discipline_extraction_ooo.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Discipline : Extrait OOo des incidents',	''),
('/responsables/consult_maj_sconet.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Consultation des compte-rendus de mise à jour Sconet',	''),
('/cahier_notes/visu_releve_notes_ter.php',	'F',	'F',	'F',	'F',	'V',	'V',	'F',	'F',	'Relevé de notes : accès parents et élèves',	'1'),
('/utilisateurs/modif_par_lots.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Personnels : Traitements par lots',	'1'),
('/bulletin/index_admin.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Bulletins : Activation du module bulletins',	'1'),
('/a_lire.php',	'V',	'V',	'V',	'V',	'V',	'V',	'V',	'V',	'A lire...',	''),
('/mod_alerte/admin.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Dispositif d alerte : Administration du module',	''),
('/mod_alerte/form_message.php',	'V',	'V',	'V',	'V',	'F',	'F',	'V',	'V',	'Dispositif d alerte',	''),
('/cahier_notes/autorisation_exceptionnelle_saisie.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Autorisation exceptionnelle de saisie dans le carnet de notes.',	''),
('/bulletin/autorisation_exceptionnelle_saisie_note.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Autorisation exceptionnelle de saisie de notes du bulletin.',	''),
('/cahier_notes/copie_dev.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Carnet de notes',	'1'),
('/cahier_texte_2/consultation2.php',	'V',	'V',	'V',	'V',	'V',	'V',	'F',	'V',	'Cahiers de textes: Consultation',	''),
('/mod_trombinoscopes/plan_de_classe.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Plan de classe',	''),
('/gestion/param_ordre_item.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Modifier l ordre des items dans les menus',	''),
('/mod_annees_anterieures/gerer_annees_anterieures.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Gérer les années antérieures',	''),
('/eleves/cherche_login.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Ajax: Recherche d un login',	''),
('/classes/ajout_eleve_classe.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Enregistrement des inscriptions élève/classe',	''),
('/mod_abs2/export_stat.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Exports statistiques',	''),
('/mod_abs2/calcul_score.php',	'V',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'Exports statistiques',	''),
('/saisie/validation_corrections.php',	'V',	'V',	'F',	'V',	'F',	'F',	'V',	'F',	'Validation des corrections proposées par des professeurs après la cloture d une période',	''),
('/mod_abs2/admin/admin_table_totaux_absences.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'',	''),
('/responsables/infos_parent.php',	'F',	'F',	'F',	'F',	'F',	'V',	'F',	'F',	'',	''),
('/cahier_texte_2/ajax_cdt.php',	'F',	'F',	'F',	'F',	'V',	'V',	'F',	'F',	'Enregistrement des modifications sur CDT',	''),
('/cahier_notes_admin/creation_conteneurs_par_lots.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Création de conteneurs/boites par lots',	'1'),
('/mod_abs2/traitements_par_lots.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Abs2: Creation lot de traitements',	''),
('/mod_discipline/aide.php',	'V',	'V',	'V',	'V',	'V',	'V',	'V',	'V',	'Discipline : Aide',	''),
('/eleves/recherche.php',	'V',	'V',	'V',	'V',	'F',	'F',	'V',	'V',	'Effectuer une recherche sur une personne',	''),
('/classes/dates_classes.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Définition de dates pour les classes',	''),
('/mod_discipline/definir_bilan_periode.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Discipline: Définir les sanctions/avertissements de fin de période',	''),
('/mod_discipline/imprimer_bilan_periode.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Discipline: Imprimer les avertissements de fin de période',	''),
('/mod_discipline/saisie_avertissement_fin_periode.php',	'V',	'V',	'V',	'V',	'F',	'F',	'V',	'F',	'Discipline: Saisie des sanctions/avertissements de fin de période',	''),
('/mod_discipline/afficher_incidents_eleve.php',	'V',	'V',	'V',	'V',	'F',	'F',	'V',	'F',	'Discipline: Affichage des incidents pour un élève.',	''),
('/groupes/grp_groupes_edit_eleves.php',	'F',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Edition des élèves des groupes de groupes',	''),
('/groupes/modify_grp_group.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Gestion des groupes de groupes',	''),
('/mod_abs2/saisie_bulletin.php',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'Saisie des absences et appréciations sur les bulletins',	''),
('/cahier_texte_2/correction_notices_url_absolues_docs_joints.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Correction des notices CDT',	''),
('/mod_notanet/saisie_notes.php',	'V',	'V',	'F',	'V',	'F',	'F',	'V',	'F',	'Notanet: Saisie notes',	''),
('/edt/index.php',	'V',	'V',	'V',	'V',	'V',	'V',	'F',	'F',	'EDT ICAL : Index',	''),
('/edt/index_admin.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'EDT ICAL : Administration',	''),
('/cahier_texte_admin/suppr_docs_joints_cdt.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Suppression des documents joints aux CDT',	''),
('/mod_abs_prof/index.php',	'V',	'V',	'V',	'V',	'V',	'V',	'F',	'F',	'Absences et remplacements de professeurs',	''),
('/mod_abs_prof/saisir_absence.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Saisie des absences de professeurs',	''),
('/mod_abs_prof/proposer_remplacement.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Proposer des remplacements aux professeurs',	''),
('/mod_abs_prof/attribuer_remplacement.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Attribuer les remplacements de professeurs',	''),
('/mod_abs_prof/index_admin.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Absences/remplacements de professeurs : Administration',	''),
('/mod_abs_prof/afficher_remplacements.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Afficher les remplacements de professeurs',	''),
('/mod_engagements/index_admin.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Engagements',	''),
('/mod_engagements/imprimer_documents.php',	'V',	'V',	'V',	'V',	'V',	'V',	'F',	'F',	'Imprimer documents',	''),
('/mod_engagements/saisie_engagements.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Saisie des engagements',	''),
('/mod_engagements/saisie_engagements_user.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Saisie des engagements pour un utilisateur',	''),
('/groupes/correction_inscriptions_grp_csv.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Correction des inscriptions dans des groupes d après un CSV',	''),
('/edt_organisation/import_edt_edt.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Import des EDT depuis un XML EDT',	''),
('/mod_engagements/extraction_engagements.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Extraction des engagements',	''),
('/cahier_texte_2/ajax_affichage_car_spec.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'CDT2: Caractères spéciaux à insérer',	''),
('/cahier_texte_2/cdt_choix_caracteres.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Choix de caractères spéciaux pour le CDT2',	''),
('/groupes/maj_inscript_ele_d_apres_edt.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Import des inscriptions élèves depuis un XML EDT',	''),
('/eleves/resume_ele.php',	'V',	'V',	'V',	'V',	'V',	'V',	'V',	'F',	'Accueil élève résumé',	''),
('/mod_abs_prof/consulter_remplacements.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Consulter les remplacements de professeurs',	''),
('/groupes/export_groupes_sconet.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Exporter les groupes Gepi vers Sconet',	''),
('/eleves/ajax_consultation.php',	'V',	'V',	'V',	'V',	'F',	'F',	'V',	'V',	'Recherches/consultations classes/élèves via ajax',	''),
('/classes/info_dates_classes.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Informer des dates d événements pour les classes',	''),
('/cahier_notes/extraction_notes_cn.php',	'F',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Extraction et export des notes des carnets de notes',	''),
('/etablissements/chercheRNE.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Recherche des RNE sans établissements',	''),
('/classes/dates_classes2.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Définition de dates pour les classes',	''),
('/classes/export_ele_opt.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Export options élèves',	''),
('/mod_discipline/saisie_pointages.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Discipline: Pointages petits incidents',	''),
('/mod_discipline/param_pointages.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Discipline: Definir les types de pointages de petits incidents',	''),
('/mod_abs2/visu_eleve_calendrier.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Absences2 : Visualisation absences élève dans un calendrier',	''),
('/mod_notanet/recherche_ine.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Notanet : Recherche INE',	''),
('/visualisation/graphes_classe.php',	'F',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Tous les graphes sur une page pour une classe donnée',	''),
('/lib/ajax_action.php',	'V',	'V',	'V',	'V',	'F',	'F',	'V',	'F',	'Action ajax',	''),
('/mod_genese_classes/affiche_listes2.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Genèse des classes: Affichage de listes (2)',	''),
('/edt/import_vacances_ics.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'EDT : Import des vacances depuis l ICAL officiel EducNat',	''),
('/edt/index2.php',	'V',	'V',	'V',	'V',	'V',	'V',	'V',	'F',	'EDT 2 : Index',	''),
('/bulletin/bulletins_et_conseils_classes.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Bulletins et conseils de classe',	''),
('/aid/popup.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Visualisation des membres d un AID',	''),
('/init_xml2/traite_xml_edt.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Import des enseignements via un Export XML EDT',	''),
('/mod_sso_table/traite_export_csv.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'SSO table : Export CSV',	''),
('/mod_sso_table/publipostage.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'SSO table : Publipostage',	''),
('/mod_ent/index_lea.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Rapprochement des comptes ENT/GEPI : ENT LEA',	''),
('/mod_abs2/visu_saisies_ele_jour.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Voir les saisies d absences pour un élève tel jour',	''),
('/mod_discipline/ajout_travail_sanction.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Discipline: Ajout de travail pour une sanction',	''),
('/bulletin/impression_avis_grp.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Impression des avis sur les groupes-classes',	''),
('/mod_abs2/alerte_nj.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Absences non justifiées depuis un certain temps',	''),
('/mod_orientation/index.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Accueil orientation',	''),
('/mod_orientation/saisie_types_orientation.php',	'V',	'V',	'F',	'V',	'F',	'F',	'F',	'F',	'Saisie des types d orientation',	''),
('/mod_orientation/admin.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Module Orientation : Administration du module',	''),
('/mod_orientation/saisie_orientation.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Saisie orientation élève',	''),
('/mod_orientation/saisie_voeux.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Saisie des voeux',	''),
('/mod_orientation/consulter_orientation.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Consultation des voeux et orientation proposée',	''),
('/mod_annees_anterieures/recuperation_donnees_manquantes.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Ajax: Acces aux appreciations et avis des bulletins',	''),
('/mod_genese_classes/saisie_profils_eleves.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Genèse des classes: Saisie des profils des élèves',	''),
('/cahier_texte_2/extract_tag.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'V',	'Cahiers de textes: Extraction tags',	''),
('/bulletin/param_bull_pdf_2016.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Paramètres des bulletins PDF Réforme CLG 2016',	''),
('/saisie/gerer_mep.php',	'V',	'V',	'F',	'V',	'F',	'F',	'F',	'F',	'Gérer les éléments de programme',	''),
('/responsables/dedoublonner_responsables.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Dédoublonner les responsables.',	''),
('/responsables/recup_comptes_parents.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Dédoublonner les responsables.',	''),
('/impression/avis_pdf_absences.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Avis PDF absences',	''),
('/mod_LSUN/index.php',	'V',	'F',	'F',	'F',	'F',	'F',	'V',	'F',	'Extraction du livret',	''),
('/mod_LSUN/admin.php',	'V',	'F',	'F',	'F',	'F',	'F',	'V',	'F',	'Extraction du livret',	''),
('/gestion/gerer_modalites_election_enseignements.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Gérer les modalités d élection des enseignements.',	''),
('/saisie/import_note_app_aid.php',	'F',	'V',	'F',	'F',	'F',	'F',	'V',	'F',	'',	''),
('/saisie/import_note_app_aid2.php',	'F',	'V',	'F',	'F',	'F',	'F',	'V',	'F',	'',	''),
('/aid/transfert_groupe_aid.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Transfert Groupe/AID',	''),
('/groupes/visu_groupes_prof.php',	'V',	'V',	'V',	'V',	'F',	'F',	'V',	'F',	'Voir les enseignements du professeur',	''),
('/mod_abs2/admin/admin_droits.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'ABS2: Droits non admin',	''),
('/mod_listes_perso/export_liste.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Listes perso: Export',	''),
('/saisie/saisie_socle.php',	'F',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Socle: Saisie',	''),
('/saisie/socle_verrouillage.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Socle: Verrouillage',	''),
('/saisie/socle_verif.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Socle: Vérification du remplissage',	''),
('/saisie/socle_import.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Socle: Import',	''),
('/matieres/associations_matieres_enseignements.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Associations matières/enseignements',	''),
('/gestion/saisie_modalites_accompagnement.php',	'V',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'Saisie des modalités d accompagnement',	''),
('/groupes/remplir_enseignement_moyenne.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Création d enseignement moyenne',	''),
('/gestion/saisie_contact.php',	'V',	'F',	'V',	'V',	'F',	'F',	'V',	'F',	'Saisie contact téléphonique, mail',	''),
('/lib/calendrier_crob.php',	'V',	'V',	'V',	'V',	'V',	'V',	'F',	'F',	'Calendrier',	''),
('/mod_discipline/extraire_pointages.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Discipline: Pointages petits incidents: Extraction',	''),
('/mod_abs2/liste_saisies_selection_traitement_decompte_matiere.php',	'V',	'F',	'V',	'V',	'F',	'F',	'V',	'F',	'Liste des saisies pour faire les traitements avec décompte matière',	''),
('/accueil_professeur.php',	'V',	'V',	'F',	'F',	'F',	'F',	'V',	'F',	' ',	''),
('/mod_absences/gestion/gestion_absences_liste.php',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'Gestion des absences',	''),
('/mod_abs2/bilan_parent.php',	'F',	'F',	'F',	'F',	'F',	'V',	'F',	'F',	'Affichage parents des absences de leurs enfants',	''),
('/mod_discipline/delegation.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Discipline: Définir les délégations pour exclusion temporaire',	''),
('/mef/admin_mef.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Mef : administration des niveau et formations',	''),
('/mef/associer_eleve_mef.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Mef : administration des niveau et formations',	''),
('/mef/enregistrement_eleve_mef.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Mef : administration des niveau et formations',	''),
('/lib/ajax_signaler_faute.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'V',	'Possibilité de signaler une faute de frappe dans une appréciation',	''),
('/gestion/admin_nomenclatures.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Import des nomenclatures',	''),
('/cahier_texte_2/affiche_notice.php',	'F',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'Cahier de texte 2 : Affichage notice',	''),
('/saisie/ct_app_moy.php',	'F',	'V',	'F',	'F',	'F',	'F',	'V',	'F',	'Saisie de commentaires-types d apres moyenne',	''),
('/cahier_texte_admin/admin_tag.php',	'V',	'F',	'V',	'V',	'F',	'F',	'F',	'F',	'Définition des tags pour les notices de Cahiers de textes',	''),
('/mod_actions/index_admin.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'Actions : Administration',	''),
('/mod_actions/index.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'V',	'Actions : Consultation/saisie',	''),
('/mod_actions/accueil.php',	'F',	'F',	'F',	'F',	'V',	'V',	'F',	'F',	'Actions : Consultation parent/élève',	''),
('/gestion/registre_traitements.php',	'V',	'V',	'V',	'V',	'V',	'V',	'V',	'V',	'Registre des traitements',	''),
('/edt_organisation/voir_base.php',	'V',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'voir la table edt_cours',	''),
('/classes/acces_appreciations_ajax.php',	'V',	'V',	'V',	'V',	'F',	'F',	'F',	'F',	'Ajax: Acces aux appreciations et avis des bulletins',	'');

DROP TABLE IF EXISTS `droits_acces_fichiers`;
CREATE TABLE `droits_acces_fichiers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fichier` varchar(255) NOT NULL,
  `identite` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `droits_aid`;
CREATE TABLE `droits_aid` (
  `id` varchar(200) NOT NULL DEFAULT '',
  `public` char(1) NOT NULL DEFAULT '',
  `professeur` char(1) NOT NULL DEFAULT '',
  `cpe` char(1) NOT NULL DEFAULT '',
  `scolarite` char(1) NOT NULL DEFAULT '',
  `eleve` char(1) NOT NULL DEFAULT '',
  `responsable` char(1) NOT NULL DEFAULT 'F',
  `secours` char(1) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `statut` char(1) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

INSERT INTO `droits_aid` (`id`, `public`, `professeur`, `cpe`, `scolarite`, `eleve`, `responsable`, `secours`, `description`, `statut`) VALUES
('nom',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'A préciser',	'1'),
('numero',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'A préciser',	'1'),
('perso1',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'A préciser',	'1'),
('perso2',	'F',	'F',	'V',	'F',	'F',	'F',	'F',	'A préciser',	'1'),
('productions',	'V',	'V',	'F',	'F',	'V',	'F',	'F',	'Production',	'1'),
('resume',	'V',	'V',	'F',	'F',	'V',	'F',	'F',	'Résumé',	'1'),
('famille',	'V',	'V',	'F',	'F',	'V',	'F',	'F',	'Famille',	'1'),
('mots_cles',	'V',	'V',	'F',	'F',	'V',	'F',	'F',	'Mots clés',	'1'),
('adresse1',	'V',	'V',	'F',	'F',	'V',	'F',	'F',	'Adresse publique',	'1'),
('adresse2',	'V',	'V',	'F',	'F',	'V',	'F',	'F',	'Adresse privée',	'1'),
('public_destinataire',	'V',	'V',	'F',	'F',	'V',	'F',	'F',	'Public destinataire',	'1'),
('contacts',	'F',	'V',	'F',	'F',	'V',	'F',	'F',	'Contacts, ressources',	'1'),
('divers',	'F',	'V',	'F',	'F',	'V',	'F',	'F',	'Divers',	'1'),
('matiere1',	'V',	'V',	'F',	'F',	'V',	'F',	'F',	'Discipline principale',	'1'),
('matiere2',	'V',	'V',	'F',	'F',	'V',	'F',	'F',	'Discipline secondaire',	'1'),
('eleve_peut_modifier',	'-',	'-',	'-',	'-',	'-',	'-',	'-',	'A préciser',	'1'),
('cpe_peut_modifier',	'-',	'-',	'-',	'-',	'-',	'-',	'-',	'A préciser',	'1'),
('prof_peut_modifier',	'-',	'-',	'-',	'-',	'-',	'-',	'-',	'A préciser',	'0'),
('fiche_publique',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'A préciser',	'1'),
('affiche_adresse1',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'A préciser',	'1'),
('en_construction',	'F',	'F',	'F',	'F',	'F',	'F',	'F',	'A préciser',	'1'),
('perso3',	'V',	'F',	'V',	'F',	'F',	'F',	'F',	'A préciser',	'0');

DROP TABLE IF EXISTS `droits_speciaux`;
CREATE TABLE `droits_speciaux` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_statut` int(11) NOT NULL,
  `nom_fichier` varchar(200) NOT NULL,
  `autorisation` char(1) NOT NULL,
  `commentaire` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `droits_statut`;
CREATE TABLE `droits_statut` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_statut` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `droits_utilisateurs`;
CREATE TABLE `droits_utilisateurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_statut` int(11) NOT NULL,
  `login_user` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `d_dates_evenements`;
CREATE TABLE `d_dates_evenements` (
  `id_ev` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL DEFAULT '',
  `periode` int(11) NOT NULL DEFAULT '0',
  `texte_avant` text NOT NULL,
  `texte_apres` text NOT NULL,
  `texte_apres_ele_resp` text NOT NULL,
  `date_debut` timestamp NOT NULL,
  PRIMARY KEY (`id_ev`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `d_dates_evenements_classes`;
CREATE TABLE `d_dates_evenements_classes` (
  `id_ev_classe` int(11) NOT NULL AUTO_INCREMENT,
  `id_ev` int(11) NOT NULL,
  `id_classe` int(11) NOT NULL DEFAULT '0',
  `date_evenement` timestamp NOT NULL,
  `id_salle` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_ev_classe`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `d_dates_evenements_utilisateurs`;
CREATE TABLE `d_dates_evenements_utilisateurs` (
  `id_ev` int(11) NOT NULL,
  `statut` varchar(20) NOT NULL,
  KEY `id_ev_u` (`id_ev`,`statut`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `eb_copies`;
CREATE TABLE `eb_copies` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login_ele` varchar(255) NOT NULL,
  `n_anonymat` varchar(255) NOT NULL,
  `id_salle` int(11) NOT NULL DEFAULT '-1',
  `login_prof` varchar(255) NOT NULL,
  `note` float(10,1) NOT NULL DEFAULT '0.0',
  `statut` varchar(255) NOT NULL DEFAULT '',
  `id_epreuve` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `eb_epreuves`;
CREATE TABLE `eb_epreuves` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `intitule` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `type_anonymat` varchar(255) NOT NULL,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `etat` varchar(255) NOT NULL,
  `note_sur` int(11) unsigned NOT NULL DEFAULT '20',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `eb_groupes`;
CREATE TABLE `eb_groupes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_epreuve` int(11) unsigned NOT NULL,
  `id_groupe` int(11) unsigned NOT NULL,
  `transfert` varchar(1) NOT NULL DEFAULT 'n',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `eb_profs`;
CREATE TABLE `eb_profs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_epreuve` int(11) unsigned NOT NULL,
  `login_prof` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `eb_salles`;
CREATE TABLE `eb_salles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `salle` varchar(255) NOT NULL,
  `id_epreuve` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `ects_credits`;
CREATE TABLE `ects_credits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_eleve` int(11) NOT NULL COMMENT 'Identifiant de l''eleve',
  `num_periode` int(11) NOT NULL COMMENT 'Identifiant de la periode',
  `id_groupe` int(11) NOT NULL COMMENT 'Identifiant du groupe',
  `valeur` decimal(3,1) DEFAULT NULL COMMENT 'Nombre de credits obtenus par l''eleve',
  `mention` varchar(255) DEFAULT NULL COMMENT 'Mention obtenue',
  `mention_prof` varchar(255) DEFAULT NULL COMMENT 'Mention presaisie par le prof',
  PRIMARY KEY (`id`,`id_eleve`,`num_periode`,`id_groupe`),
  KEY `ects_credits_FI_1` (`id_eleve`),
  KEY `ects_credits_FI_2` (`id_groupe`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `ects_global_credits`;
CREATE TABLE `ects_global_credits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_eleve` int(11) NOT NULL COMMENT 'Identifiant de l''eleve',
  `mention` varchar(255) NOT NULL COMMENT 'Mention obtenue',
  PRIMARY KEY (`id`,`id_eleve`),
  KEY `ects_global_credits_FI_1` (`id_eleve`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `edt_calendrier`;
CREATE TABLE `edt_calendrier` (
  `id_calendrier` int(11) NOT NULL AUTO_INCREMENT,
  `classe_concerne_calendrier` text NOT NULL,
  `nom_calendrier` varchar(100) NOT NULL DEFAULT '',
  `debut_calendrier_ts` varchar(11) NOT NULL,
  `fin_calendrier_ts` varchar(11) NOT NULL,
  `jourdebut_calendrier` date NOT NULL DEFAULT '0000-00-00',
  `heuredebut_calendrier` time NOT NULL DEFAULT '00:00:00',
  `jourfin_calendrier` date NOT NULL DEFAULT '0000-00-00',
  `heurefin_calendrier` time NOT NULL DEFAULT '00:00:00',
  `numero_periode` tinyint(4) NOT NULL DEFAULT '0',
  `etabferme_calendrier` tinyint(4) NOT NULL,
  `etabvacances_calendrier` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_calendrier`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `edt_classes`;
CREATE TABLE `edt_classes` (
  `id_edt_classe` int(11) NOT NULL AUTO_INCREMENT,
  `groupe_edt_classe` int(11) NOT NULL,
  `prof_edt_classe` varchar(25) NOT NULL,
  `matiere_edt_classe` varchar(10) NOT NULL,
  `semaine_edt_classe` varchar(5) NOT NULL,
  `jour_edt_classe` tinyint(4) NOT NULL,
  `datedebut_edt_classe` date NOT NULL,
  `datefin_edt_classe` date NOT NULL,
  `heuredebut_edt_classe` time NOT NULL,
  `heurefin_edt_classe` time NOT NULL,
  `salle_edt_classe` varchar(50) NOT NULL,
  PRIMARY KEY (`id_edt_classe`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `edt_corresp`;
CREATE TABLE `edt_corresp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `champ` varchar(100) NOT NULL DEFAULT '',
  `nom_edt` varchar(255) NOT NULL DEFAULT '',
  `nom_gepi` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `edt_corresp2`;
CREATE TABLE `edt_corresp2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_groupe` int(11) NOT NULL,
  `mat_code_edt` varchar(255) NOT NULL DEFAULT '',
  `nom_groupe_edt` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `edt_cours`;
CREATE TABLE `edt_cours` (
  `id_cours` int(11) NOT NULL AUTO_INCREMENT,
  `id_groupe` varchar(10) NOT NULL,
  `id_aid` varchar(10) NOT NULL,
  `id_salle` varchar(3) NOT NULL,
  `jour_semaine` varchar(10) NOT NULL,
  `id_definie_periode` varchar(3) NOT NULL,
  `duree` varchar(10) NOT NULL DEFAULT '2',
  `heuredeb_dec` varchar(3) NOT NULL DEFAULT '0',
  `id_semaine` varchar(10) NOT NULL DEFAULT '0',
  `id_calendrier` varchar(3) NOT NULL DEFAULT '0',
  `modif_edt` varchar(3) NOT NULL DEFAULT '0',
  `login_prof` varchar(50) NOT NULL,
  PRIMARY KEY (`id_cours`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `edt_cours_remplacements`;
CREATE TABLE `edt_cours_remplacements` (
  `id_cours` int(11) NOT NULL AUTO_INCREMENT,
  `id_groupe` varchar(10) NOT NULL,
  `id_aid` varchar(10) NOT NULL,
  `id_salle` varchar(3) NOT NULL,
  `jour_semaine` varchar(10) NOT NULL,
  `id_definie_periode` varchar(3) NOT NULL,
  `duree` varchar(10) NOT NULL DEFAULT '2',
  `heuredeb_dec` varchar(3) NOT NULL DEFAULT '0',
  `id_semaine` varchar(10) NOT NULL DEFAULT '0',
  `id_calendrier` varchar(3) NOT NULL DEFAULT '0',
  `modif_edt` varchar(3) NOT NULL DEFAULT '0',
  `login_prof` varchar(50) NOT NULL,
  `id_absence` int(11) NOT NULL,
  `jour` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_cours`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `edt_creneaux`;
CREATE TABLE `edt_creneaux` (
  `id_definie_periode` int(11) NOT NULL AUTO_INCREMENT,
  `nom_definie_periode` varchar(10) NOT NULL DEFAULT '',
  `heuredebut_definie_periode` time NOT NULL DEFAULT '00:00:00',
  `heurefin_definie_periode` time NOT NULL DEFAULT '00:00:00',
  `suivi_definie_periode` tinyint(4) NOT NULL,
  `type_creneaux` varchar(15) NOT NULL,
  `jour_creneau` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_definie_periode`),
  KEY `heures_debut_fin` (`heuredebut_definie_periode`,`heurefin_definie_periode`)
) TYPE=MyISAM;

INSERT INTO `edt_creneaux` (`id_definie_periode`, `nom_definie_periode`, `heuredebut_definie_periode`, `heurefin_definie_periode`, `suivi_definie_periode`, `type_creneaux`, `jour_creneau`) VALUES
(1,	'M1',	'08:00:00',	'08:55:00',	1,	'cours',	''),
(2,	'M2',	'08:55:00',	'09:50:00',	1,	'cours',	''),
(3,	'M3',	'10:05:00',	'11:00:00',	1,	'cours',	''),
(4,	'M4',	'11:00:00',	'11:55:00',	1,	'cours',	''),
(5,	'M5',	'11:55:00',	'12:30:00',	1,	'cours',	''),
(6,	'S1',	'13:30:00',	'14:25:00',	1,	'cours',	''),
(7,	'S2',	'14:25:00',	'15:20:00',	1,	'cours',	''),
(8,	'S3',	'15:35:00',	'16:30:00',	1,	'cours',	''),
(9,	'S4',	'16:30:00',	'17:30:00',	1,	'cours',	''),
(10,	'S5',	'17:30:00',	'18:25:00',	1,	'cours',	''),
(11,	'P1',	'09:50:00',	'10:05:00',	1,	'pause',	''),
(12,	'P2',	'15:20:00',	'15:35:00',	1,	'pause',	''),
(13,	'R',	'12:00:00',	'13:00:00',	1,	'repas',	''),
(14,	'R1',	'13:00:00',	'13:30:00',	1,	'pause',	'');

DROP TABLE IF EXISTS `edt_creneaux_bis`;
CREATE TABLE `edt_creneaux_bis` (
  `id_definie_periode` int(11) NOT NULL AUTO_INCREMENT,
  `nom_definie_periode` varchar(10) NOT NULL DEFAULT '',
  `heuredebut_definie_periode` time NOT NULL DEFAULT '00:00:00',
  `heurefin_definie_periode` time NOT NULL DEFAULT '00:00:00',
  `suivi_definie_periode` tinyint(4) NOT NULL,
  `type_creneaux` varchar(15) NOT NULL,
  PRIMARY KEY (`id_definie_periode`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `edt_dates_special`;
CREATE TABLE `edt_dates_special` (
  `id_edt_date_special` int(11) NOT NULL AUTO_INCREMENT,
  `nom_edt_date_special` varchar(200) NOT NULL,
  `debut_edt_date_special` date NOT NULL,
  `fin_edt_date_special` date NOT NULL,
  PRIMARY KEY (`id_edt_date_special`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `edt_eleves_lignes`;
CREATE TABLE `edt_eleves_lignes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL DEFAULT '',
  `prenom` varchar(255) NOT NULL DEFAULT '',
  `date_naiss` varchar(255) NOT NULL DEFAULT '',
  `sexe` varchar(255) NOT NULL DEFAULT '',
  `n_national` varchar(255) NOT NULL DEFAULT '',
  `classe` varchar(255) NOT NULL DEFAULT '',
  `groupes` varchar(255) NOT NULL DEFAULT '',
  `option_1` varchar(255) NOT NULL DEFAULT '',
  `option_2` varchar(255) NOT NULL DEFAULT '',
  `option_3` varchar(255) NOT NULL DEFAULT '',
  `option_4` varchar(255) NOT NULL DEFAULT '',
  `option_5` varchar(255) NOT NULL DEFAULT '',
  `option_6` varchar(255) NOT NULL DEFAULT '',
  `option_7` varchar(255) NOT NULL DEFAULT '',
  `option_8` varchar(255) NOT NULL DEFAULT '',
  `option_9` varchar(255) NOT NULL DEFAULT '',
  `option_10` varchar(255) NOT NULL DEFAULT '',
  `option_11` varchar(255) NOT NULL DEFAULT '',
  `option_12` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `edt_ics`;
CREATE TABLE `edt_ics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_classe` int(11) NOT NULL,
  `classe_ics` varchar(100) NOT NULL DEFAULT '',
  `prof_ics` varchar(200) NOT NULL DEFAULT '',
  `matiere_ics` varchar(100) NOT NULL DEFAULT '',
  `salle_ics` varchar(100) NOT NULL DEFAULT '',
  `jour_semaine` varchar(10) NOT NULL DEFAULT '',
  `num_semaine` varchar(10) NOT NULL DEFAULT '',
  `annee` char(4) NOT NULL DEFAULT '',
  `date_debut` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_fin` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `edt_ics_matiere`;
CREATE TABLE `edt_ics_matiere` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `matiere` varchar(100) NOT NULL DEFAULT '',
  `matiere_ics` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `edt_ics_prof`;
CREATE TABLE `edt_ics_prof` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login_prof` varchar(100) NOT NULL DEFAULT '',
  `prof_ics` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `edt_init`;
CREATE TABLE `edt_init` (
  `id_init` int(11) NOT NULL AUTO_INCREMENT,
  `ident_export` varchar(100) NOT NULL,
  `nom_export` varchar(200) NOT NULL,
  `nom_gepi` varchar(200) NOT NULL,
  PRIMARY KEY (`id_init`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `edt_lignes`;
CREATE TABLE `edt_lignes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero` varchar(255) NOT NULL DEFAULT '',
  `classe` varchar(255) NOT NULL DEFAULT '',
  `mat_code` varchar(255) NOT NULL DEFAULT '',
  `mat_libelle` varchar(255) NOT NULL DEFAULT '',
  `prof_nom` varchar(255) NOT NULL DEFAULT '',
  `prof_prenom` varchar(255) NOT NULL DEFAULT '',
  `salle` varchar(255) NOT NULL DEFAULT '',
  `jour` varchar(255) NOT NULL DEFAULT '',
  `h_debut` varchar(255) NOT NULL DEFAULT '',
  `duree` varchar(255) NOT NULL DEFAULT '',
  `frequence` varchar(10) NOT NULL DEFAULT '',
  `alternance` varchar(10) NOT NULL DEFAULT '',
  `effectif` varchar(255) NOT NULL DEFAULT '',
  `modalite` varchar(255) NOT NULL DEFAULT '',
  `co_ens` varchar(255) NOT NULL DEFAULT '',
  `pond` varchar(255) NOT NULL DEFAULT '',
  `traitement` varchar(100) NOT NULL DEFAULT '',
  `details_cours` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `edt_semaines`;
CREATE TABLE `edt_semaines` (
  `id_edt_semaine` int(11) NOT NULL AUTO_INCREMENT,
  `num_edt_semaine` int(11) NOT NULL DEFAULT '0',
  `type_edt_semaine` varchar(10) NOT NULL DEFAULT '',
  `num_semaines_etab` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_edt_semaine`)
) TYPE=MyISAM;

INSERT INTO `edt_semaines` (`id_edt_semaine`, `num_edt_semaine`, `type_edt_semaine`, `num_semaines_etab`) VALUES
(1,	1,	'A',	0),
(2,	2,	'A',	0),
(3,	3,	'A',	0),
(4,	4,	'A',	0),
(5,	5,	'A',	0),
(6,	6,	'A',	0),
(7,	7,	'A',	0),
(8,	8,	'A',	0),
(9,	9,	'A',	0),
(10,	10,	'A',	0),
(11,	11,	'A',	0),
(12,	12,	'A',	0),
(13,	13,	'A',	0),
(14,	14,	'A',	0),
(15,	15,	'A',	0),
(16,	16,	'A',	0),
(17,	17,	'A',	0),
(18,	18,	'A',	0),
(19,	19,	'A',	0),
(20,	20,	'A',	0),
(21,	21,	'A',	0),
(22,	22,	'A',	0),
(23,	23,	'A',	0),
(24,	24,	'A',	0),
(25,	25,	'A',	0),
(26,	26,	'A',	0),
(27,	27,	'A',	0),
(28,	28,	'A',	0),
(29,	29,	'A',	0),
(30,	30,	'A',	0),
(31,	31,	'A',	0),
(32,	32,	'A',	0),
(33,	33,	'A',	0),
(34,	34,	'A',	0),
(35,	35,	'A',	0),
(36,	36,	'A',	0),
(37,	37,	'A',	0),
(38,	38,	'A',	0),
(39,	39,	'A',	0),
(40,	40,	'A',	0),
(41,	41,	'A',	0),
(42,	42,	'A',	0),
(43,	43,	'A',	0),
(44,	44,	'A',	0),
(45,	45,	'A',	0),
(46,	46,	'A',	0),
(47,	47,	'A',	0),
(48,	48,	'A',	0),
(49,	49,	'A',	0),
(50,	50,	'A',	0),
(51,	51,	'A',	0),
(52,	52,	'A',	0),
(53,	53,	'A',	0);

DROP TABLE IF EXISTS `edt_setting`;
CREATE TABLE `edt_setting` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `reglage` varchar(30) NOT NULL,
  `valeur` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

INSERT INTO `edt_setting` (`id`, `reglage`, `valeur`) VALUES
(1,	'nom_creneaux_s',	'1'),
(2,	'edt_aff_salle',	'nom'),
(3,	'edt_aff_matiere',	'long'),
(4,	'edt_aff_creneaux',	'noms'),
(5,	'edt_aff_init_infos',	'oui'),
(6,	'edt_aff_couleur',	'nb'),
(7,	'edt_aff_init_infos2',	'oui'),
(8,	'aff_cherche_salle',	'tous'),
(9,	'param_menu_edt',	'mouseover'),
(10,	'scolarite_modif_cours',	'y'),
(11,	'edt_aff_couleur_salle',	'nb'),
(12,	'edt_aff_couleur_prof',	'nb');

DROP TABLE IF EXISTS `edt_tempo`;
CREATE TABLE `edt_tempo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `col1` varchar(255) NOT NULL DEFAULT '',
  `col2` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `elements_programmes`;
CREATE TABLE `elements_programmes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cycle` tinyint(1) NOT NULL,
  `matiere` varchar(255) NOT NULL,
  `rubrique` varchar(255) NOT NULL DEFAULT '',
  `item` text,
  `resume` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `cycle_matiere` (`cycle`,`matiere`)
) TYPE=MyISAM;

INSERT INTO `elements_programmes` (`id`, `cycle`, `matiere`, `rubrique`, `item`, `resume`) VALUES
(1,	3,	'Arts plastiques',	'Expérimenter, produire, créer',	'Choisir, organiser et mobiliser des gestes, des outils et des matériaux en fonction des effets qu\'ils produisent.',	''),
(2,	3,	'Arts plastiques',	'Expérimenter, produire, créer',	'Intégrer l\'usage des outils informatiques de travail de l\'image et de recherche d\'information, au service de la pratique plastique.',	''),
(3,	3,	'Arts plastiques',	'Expérimenter, produire, créer',	'Rechercher une expression personnelle en s\'éloignant des stéréotypes.',	''),
(4,	3,	'Arts plastiques',	'Expérimenter, produire, créer',	'Représenter le monde environnant ou donner forme à son imaginaire en explorant divers domaines (dessin, collage, modelage, sculpture, photographie, vidéo…).',	''),
(5,	3,	'Arts plastiques',	'Mettre en œuvre un projet artistique',	'Adapter son projet en fonction des contraintes de réalisation et de la prise en compte du spectateur.',	''),
(6,	3,	'Arts plastiques',	'Mettre en œuvre un projet artistique',	'Identifier et assumer sa part de responsabilité dans un processus coopératif de création.',	''),
(7,	3,	'Arts plastiques',	'Mettre en œuvre un projet artistique',	'Identifier les principaux outils et compétences nécessaires à la réalisation d\'un projet artistique.',	''),
(8,	3,	'Arts plastiques',	'Mettre en œuvre un projet artistique',	'Se repérer dans les étapes de la réalisation d\'une production plastique individuelle ou collective, anticiper les difficultés éventuelles.',	''),
(9,	3,	'Arts plastiques',	'Se repérer dans les domaines liés aux arts plastiques, être sensible aux questions de l\'art',	'Décrire des œuvres d\'art, en proposer une compréhension personnelle argumentée.',	''),
(10,	3,	'Arts plastiques',	'Se repérer dans les domaines liés aux arts plastiques, être sensible aux questions de l\'art',	'Identifier quelques caractéristiques qui inscrivent une œuvre d\'art dans une aire géographique ou culturelle et dans un temps historique, contemporain, proche ou lointain.',	''),
(11,	3,	'Arts plastiques',	'Se repérer dans les domaines liés aux arts plastiques, être sensible aux questions de l\'art',	'Repérer, pour les dépasser, certains a priori et stéréotypes culturels et artistiques.',	''),
(12,	3,	'Arts plastiques',	'S\'exprimer, analyser sa pratique, celle de ses pairs ; établir une relation avec celle des artistes, s\'ouvrir à l\'altérité',	'Décrire et interroger à l\'aide d\'un vocabulaire spécifique ses productions plastiques, celles de ses pairs et des œuvres d\'art étudiées en classe.',	''),
(13,	3,	'Arts plastiques',	'S\'exprimer, analyser sa pratique, celle de ses pairs ; établir une relation avec celle des artistes, s\'ouvrir à l\'altérité',	'Formuler une expression juste de ses émotions, en prenant appui sur ses propres réalisations plastiques, celles des autres élèves et des œuvres d\'art.',	''),
(14,	3,	'Arts plastiques',	'S\'exprimer, analyser sa pratique, celle de ses pairs ; établir une relation avec celle des artistes, s\'ouvrir à l\'altérité',	'Justifier des choix pour rendre compte du cheminement qui conduit de l\'intention à la réalisation.',	''),
(15,	3,	'Éducation musicale',	'Chanter et interpréter',	'Interpréter un répertoire varié avec expressivité.',	'Interpréter un répertoire varié avec expressivité.'),
(16,	3,	'Éducation musicale',	'Chanter et interpréter',	'Reproduire et interpréter un modèle mélodique et rythmique.',	'Reproduire et interpréter un modèle mélodique et rythmique.'),
(17,	3,	'Éducation musicale',	'Échanger, partager et argumenter',	'Argumenter un jugement sur une musique.',	'Argumenter un jugement sur une musique.'),
(18,	3,	'Éducation musicale',	'Échanger, partager et argumenter',	'Écouter et respecter le point de vue des autres et l\'expression de leur sensibilité.',	'Écouter, respecter point de vue et sensibilité des autres'),
(19,	3,	'Éducation musicale',	'Écouter, comparer et commenter',	'Décrire et comparer des éléments sonores issus de contextes musicaux différents.',	'Décrire, comparer des éléments de contextes différents.'),
(20,	3,	'Éducation musicale',	'Écouter, comparer et commenter',	'Identifier et nommer ressemblances et différences dans deux extraits musicaux.',	'Identifier ressemblances et différences dans deux extraits'),
(21,	3,	'Éducation musicale',	'Écouter, comparer et commenter',	'Identifier quelques caractéristiques qui inscrivent une œuvre musicale dans une aire géographique ou culturelle et dans un temps historique contemporain, proche ou lointain.',	'Situer une œuvre (pér. historique, géographique, culturelle)'),
(22,	3,	'Éducation musicale',	'Explorer, imaginer et créer',	'Faire des propositions personnelles lors de moments de création, d\'invention et d\'interprétation.',	'Faire des propositions en création et en interprétation.'),
(23,	3,	'Éducation musicale',	'Explorer, imaginer et créer',	'Imaginer l\'organisation de différents éléments sonores.',	'Imaginer l\'organisation de différents éléments sonores.'),
(24,	3,	'Éducation physique et sportive',	'Apprendre à entretenir sa santé par une activité physique régulière',	'Adapter l\'intensité de son engagement physique à ses possibilités pour ne pas se mettre en danger.',	'Adapter l\'intensité de son engagement à ses possibilités'),
(25,	3,	'Éducation physique et sportive',	'Apprendre à entretenir sa santé par une activité physique régulière',	'Connaitre et appliquer des principes d\'une bonne hygiène de vie.',	'Appliquer des principes d\'une bonne hygiène de vie.'),
(26,	3,	'Éducation physique et sportive',	'Apprendre à entretenir sa santé par une activité physique régulière',	'Évaluer la quantité et la qualité de son activité physique quotidienne dans et hors l\'école.',	'Évaluer la quantité et la qualité de son activité physique'),
(27,	3,	'Éducation physique et sportive',	'Développer sa motricité et construire un langage du corps',	'Acquérir des techniques spécifiques pour améliorer son efficacité.',	'Acquérir des techniques pour améliorer son efficacité.'),
(28,	3,	'Éducation physique et sportive',	'Développer sa motricité et construire un langage du corps',	'Adapter sa motricité à des situations variées.',	'Adapter sa motricité à des situations variées.'),
(29,	3,	'Éducation physique et sportive',	'Développer sa motricité et construire un langage du corps',	'Mobiliser différentes ressources (physiologique, biomécanique, psychologique, émotionnelle) pour agir de manière efficiente.',	'Mobiliser différentes ressources'),
(30,	3,	'Éducation physique et sportive',	'Partager des règles, assumer des rôles et des responsabilités',	'Assumer les rôles sociaux spécifiques aux différentes APSA et à la classe (joueur, coach, arbitre, juge, observateur, tuteur, médiateur, organisateur…).',	'Assumer les rôles sociaux spécifiques aux différentes APSA'),
(31,	3,	'Éducation physique et sportive',	'Partager des règles, assumer des rôles et des responsabilités',	'Assurer sa sécurité et celle d\'autrui dans des situations variées.',	'Assurer sa sécurité et celle d\'autrui'),
(32,	3,	'Éducation physique et sportive',	'Partager des règles, assumer des rôles et des responsabilités',	'Comprendre, respecter et faire respecter règles et règlements.',	'Comprendre, respecter et faire respecter règles et règlement'),
(33,	3,	'Éducation physique et sportive',	'Partager des règles, assumer des rôles et des responsabilités',	'S\'engager dans les activités sportives et artistiques collectives.',	'S\'engager dans les APSA collectives'),
(34,	3,	'Éducation physique et sportive',	'S\'approprier seul ou à plusieurs par la pratique, les méthodes et outils pour apprendre',	'Apprendre par l\'action, l\'observation, l\'analyse de son activité et de celle des autres.',	'Apprendre par l\'action, l\'observation, l\'analyse'),
(35,	3,	'Éducation physique et sportive',	'S\'approprier seul ou à plusieurs par la pratique, les méthodes et outils pour apprendre',	'Répéter un geste pour le stabiliser et le rendre plus efficace.',	'Répéter un geste pour le stabiliser et devenir efficace'),
(36,	3,	'Éducation physique et sportive',	'S\'approprier seul ou à plusieurs par la pratique, les méthodes et outils pour apprendre',	'Utiliser des outils numériques pour observer, évaluer et modifier ses actions.',	'Utiliser des outils numériques'),
(37,	3,	'Éducation physique et sportive',	'S\'approprier une culture physique sportive et artistique',	'Comprendre et respecter l\'environnement des pratiques physiques et sportives.',	'Comprendre et respecter l\'environnement des pratiques'),
(38,	3,	'Éducation physique et sportive',	'S\'approprier une culture physique sportive et artistique',	'Savoir situer des performances à l\'échelle de la performance humaine.',	'Savoir situer des performances \"humaines\"'),
(39,	3,	'Enseignement moral et civique',	'L\'engagement : agir individuellement et collectivement',	'Prendre en charge des aspects de la vie collective et de l\'environnement et développer une conscience citoyenne, sociale et écologique.',	''),
(40,	3,	'Enseignement moral et civique',	'L\'engagement : agir individuellement et collectivement',	'S\'engager et assumer des responsabilités dans l\'école et dans l\'établissement.',	''),
(41,	3,	'Enseignement moral et civique',	'La sensibilité : soi et les autres',	'Identifier et exprimer en les régulant ses émotions et ses sentiments.',	''),
(42,	3,	'Enseignement moral et civique',	'La sensibilité : soi et les autres',	'S\'estimer et être capable d\'écoute et d\'empathie.',	''),
(43,	3,	'Enseignement moral et civique',	'La sensibilité : soi et les autres',	'Se sentir membre d\'une collectivité.',	''),
(44,	3,	'Enseignement moral et civique',	'Le droit et la règle : des principes pour vivre avec les autres',	'Comprendre les principes et les valeurs de la République française et des sociétés démocratiques.',	''),
(45,	3,	'Enseignement moral et civique',	'Le droit et la règle : des principes pour vivre avec les autres',	'Comprendre les raisons de l\'obéissance aux règles et à la loi dans une société démocratique.',	''),
(46,	3,	'Enseignement moral et civique',	'Le jugement : penser par soi-même et avec les autres',	'Développer les aptitudes à la réflexion critique : en recherchant les critères de validité des jugements moraux ; en confrontant ses jugements à ceux d\'autrui dans une discussion ou un débat argumenté',	''),
(47,	3,	'Enseignement moral et civique',	'Le jugement : penser par soi-même et avec les autres',	'Différencier son intérêt particulier de l\'intérêt général.',	''),
(48,	3,	'Français',	'Comprendre et s\'exprimer à l\'oral',	'Adopter une attitude critique par rapport au langage produit.',	'Adopter une attitude critique / au langage produit.'),
(49,	3,	'Français',	'Comprendre et s\'exprimer à l\'oral',	'Écouter pour comprendre un message oral, un propos, un discours, un texte lu.',	'Écouter pr comprendre message oral, propos, disc., texte lu.'),
(50,	3,	'Français',	'Comprendre et s\'exprimer à l\'oral',	'Parler en prenant en compte son auditoire.',	'Parler en prenant en compte son auditoire.'),
(51,	3,	'Français',	'Comprendre et s\'exprimer à l\'oral',	'Participer à des échanges dans des situations diversifiées.',	'Participer à des échanges dans des situations diversifiées.'),
(52,	3,	'Français',	'Comprendre le fonctionnement de la langue',	'Acquérir la structure, le sens et l\'orthographe des mots.',	'Acquérir la structure, le sens et l\'orthographe des mots.'),
(53,	3,	'Français',	'Comprendre le fonctionnement de la langue',	'Identifier les constituants d\'une phrase simple en relation avec son sens ; distinguer phrase simple et phrase complexe.',	'Identifier constit. phrase simple; ph. simp. /ph. complexe.'),
(54,	3,	'Français',	'Comprendre le fonctionnement de la langue',	'Maitriser la forme des mots en lien avec la syntaxe.',	'Maitriser la forme des mots en lien avec la syntaxe.'),
(55,	3,	'Français',	'Comprendre le fonctionnement de la langue',	'Maitriser les relations entre l\'oral et l\'écrit.',	'Maitriser les relations entre l\'oral et l\'écrit.'),
(56,	3,	'Français',	'Comprendre le fonctionnement de la langue',	'Observer le fonctionnement du verbe et l\'orthographier.',	'Observer le fonctionnement du verbe et l\'orthographier.'),
(57,	3,	'Français',	'Écrire',	'Écrire à la main de manière fluide et efficace.',	'Écrire à la main de manière fluide et efficace.'),
(58,	3,	'Français',	'Écrire',	'Écrire avec un clavier rapidement et efficacement.',	'Écrire avec un clavier rapidement et efficacement.'),
(59,	3,	'Français',	'Écrire',	'Prendre en compte les normes de l\'écrit pour formuler, transcrire et réviser.',	'Prdre en cpte normes écrit pr formuler, transcrire, réviser.'),
(60,	3,	'Français',	'Écrire',	'Produire des écrits variés.',	'Produire des écrits variés.'),
(61,	3,	'Français',	'Écrire',	'Recourir à l\'écriture pour réfléchir et pour apprendre.',	'Recourir à l\'écriture pour réfléchir et pour apprendre.'),
(62,	3,	'Français',	'Écrire',	'Réécrire à partir de nouvelles consignes ou faire évoluer son texte.',	'Récrire selon de nouv. consignes; faire évoluer son texte.'),
(63,	3,	'Français',	'Lire',	'Comprendre des textes, des documents et des images et les interpréter.',	'Comprendre textes, documents et images et les interpréter.'),
(64,	3,	'Français',	'Lire',	'Comprendre un texte littéraire et l\'interpréter.',	'Comprendre un texte littéraire et l\'interpréter.'),
(65,	3,	'Français',	'Lire',	'Contrôler sa compréhension, être un lecteur autonome.',	'Contrôler sa compréhension, être un lecteur autonome.'),
(66,	3,	'Français',	'Lire',	'Lire avec fluidité.',	'Lire avec fluidité.'),
(67,	3,	'Histoire des arts',	'Analyser',	'Dégager d\'une œuvre d\'art, par l\'observation ou l\'écoute, ses principales caractéristiques techniques et formelles.',	'Dégager les principales caractéristiques d\'une œuvre d\'art'),
(68,	3,	'Histoire des arts',	'Identifier',	'Donner un avis argumenté sur ce que représente ou exprime une œuvre d\'art.',	'Donner un avis argumenté sur une œuvre d\'art.'),
(69,	3,	'Histoire des arts',	'Se repérer',	'Se repérer dans un musée, un lieu d\'art, un site patrimonial.',	'Se repérer dans un musée, un lieu d\'art'),
(70,	3,	'Histoire des arts',	'Situer',	'Relier des caractéristiques d\'une œuvre d\'art à des usages ainsi qu\'au contexte historique et culturel de sa création.',	'Relier une œuvre d\'art à des usages ainsi qu\'à son contexte'),
(71,	3,	'Histoire et géographie',	'Comprendre un document',	'Comprendre le sens général d\'un document.',	'Comprendre le sens général d\'un document.'),
(72,	3,	'Histoire et géographie',	'Comprendre un document',	'Extraire des informations pertinentes pour répondre à une question.',	'Extraire des informations pour répondre à une question'),
(73,	3,	'Histoire et géographie',	'Comprendre un document',	'Identifier le document et savoir pourquoi il doit être identifié.',	''),
(74,	3,	'Histoire et géographie',	'Comprendre un document',	'Savoir que le document exprime un point de vue, identifier et questionner le sens implicite d\'un document.',	''),
(75,	3,	'Histoire et géographie',	'Coopérer et mutualiser',	'Apprendre à utiliser les outils numériques qui peuvent conduire à des réalisations collectives.',	'Apprendre à utiliser les outils numériques'),
(76,	3,	'Histoire et géographie',	'Coopérer et mutualiser',	'Organiser son travail dans le cadre d\'un groupe pour élaborer une tâche commune et/ou une production collective et mettre à la disposition des autres ses compétences et ses connaissances.',	'S\'organiser et réaliser une production en groupe'),
(77,	3,	'Histoire et géographie',	'Coopérer et mutualiser',	'Travailler en commun pour faciliter les apprentissages individuels.',	''),
(78,	3,	'Histoire et géographie',	'Pratiquer différents langages en histoire et en géographie',	'Écrire pour structurer sa pensée et son savoir, pour argumenter et écrire pour communiquer et échanger.',	''),
(79,	3,	'Histoire et géographie',	'Pratiquer différents langages en histoire et en géographie',	'Réaliser ou compléter des productions graphiques.',	''),
(80,	3,	'Histoire et géographie',	'Pratiquer différents langages en histoire et en géographie',	'Reconnaitre un récit historique.',	'Reconnaitre un récit historique.'),
(81,	3,	'Histoire et géographie',	'Pratiquer différents langages en histoire et en géographie',	'S\'approprier et utiliser un lexique historique et géographique approprié.',	'S\'approprier un lexique historique et géographique précis'),
(82,	3,	'Histoire et géographie',	'Pratiquer différents langages en histoire et en géographie',	'S\'exprimer à l\'oral pour penser, communiquer et échanger.',	''),
(83,	3,	'Histoire et géographie',	'Pratiquer différents langages en histoire et en géographie',	'Utiliser des cartes analogiques et numériques à différentes échelles, des photographies de paysages ou de lieux.',	''),
(84,	3,	'Histoire et géographie',	'Raisonner, justifier une démarche et les choix effectués',	'Formuler des hypothèses.',	'Formuler des hypothèses.'),
(85,	3,	'Histoire et géographie',	'Raisonner, justifier une démarche et les choix effectués',	'Justifier.',	'Justifier.'),
(86,	3,	'Histoire et géographie',	'Raisonner, justifier une démarche et les choix effectués',	'Poser des questions, se poser des questions.',	'Poser des questions, se poser des questions.'),
(87,	3,	'Histoire et géographie',	'Raisonner, justifier une démarche et les choix effectués',	'Vérifier.',	'Vérifier.'),
(88,	3,	'Histoire et géographie',	'Se repérer dans le temps : construire des repères historiques',	'Manipuler et réinvestir le repère historique dans différents contextes.',	''),
(89,	3,	'Histoire et géographie',	'Se repérer dans le temps : construire des repères historiques',	'Mémoriser les repères historiques liés au programme et savoir les mobiliser dans différents contextes.',	''),
(90,	3,	'Histoire et géographie',	'Se repérer dans le temps : construire des repères historiques',	'Ordonner des faits les uns par rapport aux autres et les situer dans une époque ou une période donnée.',	''),
(91,	3,	'Histoire et géographie',	'Se repérer dans le temps : construire des repères historiques',	'Situer chronologiquement des grandes périodes historiques.',	''),
(92,	3,	'Histoire et géographie',	'Se repérer dans le temps : construire des repères historiques',	'Utiliser des documents donnant à voir une représentation du temps (dont les frises chronologiques), à différentes échelles, et le lexique relatif au découpage du temps et suscitant la mise en perspect',	'Se repérer dans l\'espace'),
(93,	3,	'Histoire et géographie',	'Se repérer dans l\'espace : construire des repères géographiques',	'Appréhender la notion d\'échelle géographique.',	'Appréhender la notion d\'échelle géographique.'),
(94,	3,	'Histoire et géographie',	'Se repérer dans l\'espace : construire des repères géographiques',	'Mémoriser les repères géographiques liés au programme et savoir les mobiliser dans différents contextes.',	''),
(95,	3,	'Histoire et géographie',	'Se repérer dans l\'espace : construire des repères géographiques',	'Nommer et localiser les grands repères géographiques.',	''),
(96,	3,	'Histoire et géographie',	'Se repérer dans l\'espace : construire des repères géographiques',	'Nommer et localiser un lieu dans un espace géographique.',	''),
(97,	3,	'Histoire et géographie',	'Se repérer dans l\'espace : construire des repères géographiques',	'Nommer, localiser et caractériser des espaces.',	'Nommer, localiser et caractériser des espaces.'),
(98,	3,	'Histoire et géographie',	'Se repérer dans l\'espace : construire des repères géographiques',	'Situer des lieux et des espaces les uns par rapport aux autres.',	''),
(99,	3,	'Histoire et géographie',	'S\'informer dans le monde du numérique',	'Connaitre différents systèmes d\'information, les utiliser.',	''),
(100,	3,	'Histoire et géographie',	'S\'informer dans le monde du numérique',	'Identifier la ressource numérique utilisée.',	'Identifier la ressource numérique utilisée.'),
(101,	3,	'Histoire et géographie',	'S\'informer dans le monde du numérique',	'Trouver, sélectionner et exploiter des informations dans une ressource numérique.',	''),
(102,	3,	'Langues vivantes',	'Découvrir les aspects culturels d\'une langue vivante étrangère et régionale',	'Identifier quelques grands repères culturels de l\'environnement quotidien des élèves du même âge dans les pays ou régions étudiés.',	'Se familiariser avec des nouveaux repères culturels.'),
(103,	3,	'Langues vivantes',	'Découvrir les aspects culturels d\'une langue vivante étrangère et régionale',	'Mobiliser ses connaissances culturelles pour décrire ou raconter des personnages réels ou imaginaires.',	'S\'appuyer sur la culture pour raconter ou décrire.'),
(104,	3,	'Langues vivantes',	'Écouter et comprendre',	'Écouter et comprendre des messages oraux simples relevant de la vie quotidienne, des histoires simples.',	'Écouter et comprendre des messages oraux simples.'),
(105,	3,	'Langues vivantes',	'Écouter et comprendre',	'Exercer sa mémoire auditive à court et à long terme pour mémoriser des mots, des expressions courantes.',	'Mémoriser des mots, des expressions courantes.'),
(106,	3,	'Langues vivantes',	'Écouter et comprendre',	'Utiliser des indices sonores et visuels pour déduire le sens de mots inconnus, d\'un message.',	'Utiliser des indices pour comprendre un message.'),
(107,	3,	'Langues vivantes',	'Écrire',	'Écrire des mots et des expressions dont l\'orthographe et la syntaxe ont été mémorisées.',	'Écrire des mots et des expressions correctement.'),
(108,	3,	'Langues vivantes',	'Écrire',	'Mobiliser des structures simples pour écrire des phrases en s\'appuyant sur une trame connue.',	'Mobiliser des structures simples pour écrire des phrases.'),
(109,	3,	'Langues vivantes',	'Lire et comprendre',	'Percevoir la relation entre certains graphèmes et phonèmes spécifiques à la langue.',	'Percevoir la relation entre certains graphèmes et phonèmes.'),
(110,	3,	'Langues vivantes',	'Lire et comprendre',	'Reconnaitre des mots isolés dans un énoncé, un court texte.',	'Reconnaitre des mots isolés.'),
(111,	3,	'Langues vivantes',	'Lire et comprendre',	'S\'appuyer sur des mots outils, des structures simples, des expressions rituelles.',	'S\'appuyer sur des structures simples ( rituelles ).'),
(112,	3,	'Langues vivantes',	'Lire et comprendre',	'Utiliser le contexte, les illustrations et les connaissances pour comprendre un texte.',	'Utiliser le paratexte  pour comprendre un document.'),
(113,	3,	'Langues vivantes',	'Parler en continu',	'Mémoriser et reproduire des énoncés.',	'Mémoriser et reproduire des énoncés.'),
(114,	3,	'Langues vivantes',	'Parler en continu',	'Participer à des échanges simples en mobilisant ses connaissances phonologiques, grammaticales, lexicales, pour être entendu et compris dans quelques situations diversifiées de la vie quotidienne.',	'Mobiliser ses connaissances pour parler simplement.'),
(115,	3,	'Langues vivantes',	'Parler en continu',	'S\'exprimer de manière audible, en modulant débit et voix.',	'S\'exprimer de manière audible, en modulant débit et voix.'),
(116,	3,	'Langues vivantes',	'Réagir et dialoguer',	'Mobiliser des énoncés adéquats au contexte dans une succession d\'échanges ritualisés.',	'Mobiliser des énoncés dans des échanges ritualisés.'),
(117,	3,	'Langues vivantes',	'Réagir et dialoguer',	'Poser des questions simples.',	'Poser des questions simples.'),
(118,	3,	'Langues vivantes',	'Réagir et dialoguer',	'Utiliser des procédés très simples pour commencer, poursuivre et terminer une conversation brève.',	'Participer à une conversation simple .'),
(119,	3,	'Mathématiques',	'Calculer',	'Calculer avec des nombres décimaux, de manière exacte ou approchée, en utilisant des stratégies ou des techniques appropriées (mentalement, en ligne, ou en posant les opérations).',	'Calcul avec des décimaux  (mental, posé ou instrumenté)'),
(120,	3,	'Mathématiques',	'Calculer',	'Contrôler la vraisemblance de ses résultats.',	'Contrôler la vraisemblance de ses résultats.'),
(121,	3,	'Mathématiques',	'Calculer',	'Utiliser une calculatrice pour trouver ou vérifier un résultat.',	'Utiliser la calculatrice pour trouver/ vérifier un résultat.'),
(122,	3,	'Mathématiques',	'Chercher',	'Prélever et organiser les informations nécessaires à la résolution de problèmes à partir de supports variés : textes, tableaux, diagrammes, graphiques, dessins, schémas, etc.',	'Prélever/utiliser une information pour résoudre un problème'),
(123,	3,	'Mathématiques',	'Chercher',	'S\'engager dans une démarche, observer, questionner, manipuler, expérimenter, émettre des hypothèses, en mobilisant des outils ou des procédures mathématiques déjà rencontrées, en élaborant un raisonne',	'S\'engager dans une démarche, élaborer un raisonnement.'),
(124,	3,	'Mathématiques',	'Chercher',	'Tester, essayer plusieurs pistes de résolution.',	'Tester, essayer plusieurs pistes de résolution.'),
(125,	3,	'Mathématiques',	'Communiquer',	'Expliquer sa démarche ou son raisonnement, comprendre les explications d\'un autre et argumenter dans l\'échange.',	'Savoir expliquer, comprendre les explications et argumenter.'),
(126,	3,	'Mathématiques',	'Communiquer',	'Utiliser progressivement un vocabulaire adéquat et/ou des notations adaptées pour décrire une situation, exposer une argumentation.',	'Utiliser le vocabulaire adéquat à la situation'),
(127,	3,	'Mathématiques',	'Modéliser',	'Reconnaitre des situations réelles pouvant être modélisées par des relations géométriques (alignement, parallélisme, perpendicularité, symétrie).',	'Etre capable de modéliser un cas réel avec la géométrie.'),
(128,	3,	'Mathématiques',	'Modéliser',	'Reconnaitre et distinguer des problèmes relevant de situations additives, multiplicatives, de proportionnalité.',	'Identifier des types de problèmes (additifs, multiplicatifs)'),
(129,	3,	'Mathématiques',	'Modéliser',	'Utiliser des propriétés géométriques pour reconnaitre des objets.',	'Reconnaître des objets à l\'aide de propriétés géométriques.'),
(130,	3,	'Mathématiques',	'Modéliser',	'Utiliser les mathématiques pour résoudre quelques problèmes issus de situations de la vie quotidienne.',	'Résoudre des problèmes issus de la vie quotidienne.'),
(131,	3,	'Mathématiques',	'Raisonner',	'En géométrie, passer progressivement de la perception au contrôle par les instruments pour amorcer des raisonnements s\'appuyant uniquement sur des propriétés des figures et sur des relations entre obj',	'En géométrie, amorcer des raisonnements sans instruments.'),
(132,	3,	'Mathématiques',	'Raisonner',	'Justifier ses affirmations et rechercher la validité des informations dont on dispose.',	'Justifier, rechercher la validité des informations.'),
(133,	3,	'Mathématiques',	'Raisonner',	'Progresser collectivement dans une investigation en sachant prendre en compte le point de vue d\'autrui.',	'Progresser collectivement dans une investigation.'),
(134,	3,	'Mathématiques',	'Raisonner',	'Résoudre des problèmes nécessitant l\'organisation de données multiples ou la construction d\'une démarche qui combine des étapes de raisonnement.',	'Résoudre des problèmes complexes (étapes, données multiples)'),
(135,	3,	'Mathématiques',	'Représenter',	'Analyser une figure plane sous différents aspects (surface, contour de celle-ci, lignes et points).',	'Analyser une figure plane sous différents aspects'),
(136,	3,	'Mathématiques',	'Représenter',	'Produire et utiliser diverses représentations des fractions simples et des nombres décimaux.',	'Utiliser diverses écritures de fractions et de décimaux.'),
(137,	3,	'Mathématiques',	'Représenter',	'Reconnaitre et utiliser des premiers éléments de codages d\'une figure plane ou d\'un solide.',	'Reconnaitre et utiliser le codage de figures planes/solide.'),
(138,	3,	'Mathématiques',	'Représenter',	'Utiliser des outils pour représenter un problème : dessins, schémas, diagrammes, graphiques, écritures avec parenthésages, …',	'Utiliser divers outils pour représenter un problème'),
(139,	3,	'Mathématiques',	'Représenter',	'Utiliser et produire des représentations de solides et de situations spatiales.',	'Utiliser/produire des représentations de solides.'),
(140,	3,	'Sciences et technologie',	'Adopter un comportement éthique et responsable',	'Mettre en œuvre une action responsable et citoyenne, individuellement ou collectivement, en et hors milieu scolaire, et en témoigner.',	'Réaliser une action responsable et citoyenne et en témoigner'),
(141,	3,	'Sciences et technologie',	'Adopter un comportement éthique et responsable',	'Relier des connaissances acquises en sciences et technologie à des questions de santé, de sécurité et d\'environnement.',	'Relier ses connaissances à des questions d\'actualité'),
(142,	3,	'Sciences et technologie',	'Concevoir, créer, réaliser',	'Décrire le fonctionnement d\'objets techniques, leurs fonctions et leurs composants.',	'Décrire un objet technique (fonctionnement, composants…)'),
(143,	3,	'Sciences et technologie',	'Concevoir, créer, réaliser',	'Identifier les évolutions des besoins et des objets techniques dans leur contexte.',	'Identifier les évolutions des besoins et des objets tech.'),
(144,	3,	'Sciences et technologie',	'Concevoir, créer, réaliser',	'Identifier les principales familles de matériaux.',	'Identifier les principales familles de matériaux'),
(145,	3,	'Sciences et technologie',	'Concevoir, créer, réaliser',	'Réaliser en équipe tout ou une partie d\'un objet technique répondant à un besoin.',	'Réaliser en équipe un objet technique en lien avec un besoin'),
(146,	3,	'Sciences et technologie',	'Concevoir, créer, réaliser',	'Repérer et comprendre la communication et la gestion de l\'information.',	'Repérer, comprendre la communication et la gestion de l\'info'),
(147,	3,	'Sciences et technologie',	'Mobiliser des outils numériques',	'Identifier des sources d\'informations fiables.',	'Identifier des sources d\'informations fiables.'),
(148,	3,	'Sciences et technologie',	'Mobiliser des outils numériques',	'Utiliser des outils numériques pour : communiquer des résultats.',	'Utiliser le numérique pour : communiquer des résultats'),
(149,	3,	'Sciences et technologie',	'Mobiliser des outils numériques',	'Utiliser des outils numériques pour : simuler des phénomènes.',	'Utiliser des outils numériques pour : simuler des phénomènes'),
(150,	3,	'Sciences et technologie',	'Mobiliser des outils numériques',	'Utiliser des outils numériques pour : traiter des données.',	'Utiliser des outils numériques pour : traiter des données.'),
(151,	3,	'Sciences et technologie',	'Mobiliser des outils numériques',	'Utiliser le numérique pour : représenter des objets techniques.',	'Utiliser le numérique : représenter des objets techniques'),
(152,	3,	'Sciences et technologie',	'Pratiquer des démarches scientifiques et technologiques',	'Proposer, avec l\'aide du professeur, une démarche pour résoudre un problème ou répondre à une question de nature scientifique ou technologique : formaliser une partie de sa recherche sous une forme éc',	'Proposer une démarche pour résoudre un problème'),
(153,	3,	'Sciences et technologie',	'Pratiquer des démarches scientifiques et technologiques',	'Proposer, avec l\'aide du professeur, une démarche pour résoudre un problème ou répondre à une question de nature scientifique ou technologique : formuler une question ou une problématique scientifique',	'Formuler une question ou une problématique simple'),
(154,	3,	'Sciences et technologie',	'Pratiquer des démarches scientifiques et technologiques',	'Proposer, avec l\'aide du professeur, une démarche pour résoudre un problème ou répondre à une question de nature scientifique ou technologique : interpréter un résultat, en tirer une conclusion.',	'Interpréter des résultats et en tirer des conclusions'),
(155,	3,	'Sciences et technologie',	'Pratiquer des démarches scientifiques et technologiques',	'Proposer, avec l\'aide du professeur, une démarche pour résoudre un problème ou répondre à une question de nature scientifique ou technologique : proposer des expériences simples pour tester une hypoth',	'Proposer une expèrience pour tester une hypothese'),
(156,	3,	'Sciences et technologie',	'Pratiquer des démarches scientifiques et technologiques',	'Proposer, avec l\'aide du professeur, une démarche pour résoudre un problème ou répondre à une question de nature scientifique ou technologique : proposer une ou des hypothèses pour répondre à une ques',	'Proposer une hypothèse pour répondre au problème'),
(157,	3,	'Sciences et technologie',	'Pratiquer des langages',	'Expliquer un phénomène à l\'oral et à l\'écrit.',	'Expliquer un phénomène à l\'oral et à l\'écrit.'),
(158,	3,	'Sciences et technologie',	'Pratiquer des langages',	'Exploiter un document constitué de divers supports (texte, schéma, graphique, tableau, algorithme simple).',	'Exploiter un document constitué de divers supports'),
(159,	3,	'Sciences et technologie',	'Pratiquer des langages',	'Rendre compte des observations, expériences, hypothèses, conclusions en utilisant un vocabulaire précis.',	'Communiquer un travail en utilisant un vocabulaire précis'),
(160,	3,	'Sciences et technologie',	'Pratiquer des langages',	'Utiliser différents modes de représentation formalisés (schéma, dessin, croquis, tableau, graphique, texte).',	'Utiliser différents modes de représentation'),
(161,	3,	'Sciences et technologie',	'Se situer dans l\'espace et dans le temps',	'Replacer des évolutions scientifiques et technologiques dans un contexte historique, géographique, économique et culturel.',	'Identifier le contexte des évolutions scientifiques'),
(162,	3,	'Sciences et technologie',	'Se situer dans l\'espace et dans le temps',	'Se situer dans l\'environnement et maitriser les notions d\'échelle.',	'Se situer dans l\'environnement (échelles espace et temps)'),
(163,	3,	'Sciences et technologie',	'S\'approprier des outils et des méthodes',	'Choisir ou utiliser le matériel adapté pour mener une observation, effectuer une mesure, réaliser une expérience ou une production.',	'Choisir et utiliser le matériel adapté au travail réalisé'),
(164,	3,	'Sciences et technologie',	'S\'approprier des outils et des méthodes',	'Effectuer des recherches bibliographiques simples et ciblées. Extraire les informations pertinentes d\'un document et les mettre en relation pour répondre à une question.',	'Effectuer un recherche d\'informations pertinente'),
(165,	3,	'Sciences et technologie',	'S\'approprier des outils et des méthodes',	'Faire le lien entre la mesure réalisée, les unités et l\'outil utilisés.',	'Faire le lien entre mesure, unité et outil utilisé'),
(166,	3,	'Sciences et technologie',	'S\'approprier des outils et des méthodes',	'Garder une trace écrite ou numérique des recherches, des observations et des expériences réalisées.',	'Garder une trace écrite ou numérique de son travail'),
(167,	3,	'Sciences et technologie',	'S\'approprier des outils et des méthodes',	'Organiser seul ou en groupe un espace de réalisation expérimentale.',	'Organiser seul ou en groupe un espace d\'expérimentation'),
(168,	3,	'Sciences et technologie',	'S\'approprier des outils et des méthodes',	'Utiliser les outils mathématiques adaptés.',	'Utiliser les outils mathématiques adaptés.'),
(169,	4,	'Arts plastiques',	'Expérimenter, produire, créer',	'Choisir, mobiliser et adapter des langages et des moyens plastiques variés en fonction de leurs effets dans une intention artistique en restant attentif à l\'inattendu.',	'Choix des moyens plastiques variés et effets produits.'),
(170,	4,	'Arts plastiques',	'Expérimenter, produire, créer',	'Exploiter des informations et de la documentation, notamment iconique, pour servir un projet de création.',	'Exploiter des documents iconiques pour ses projets.'),
(171,	4,	'Arts plastiques',	'Expérimenter, produire, créer',	'Explorer l\'ensemble des champs de la pratique plastique et leurs hybridations, notamment avec les pratiques numériques.',	'Expérimenter le numérique et ses hybridations possibles.'),
(172,	4,	'Arts plastiques',	'Expérimenter, produire, créer',	'Prendre en compte les conditions de la réception de sa production dès la démarche de création, en prêtant attention aux modalités de sa présentation, y compris numérique.',	'S\'interroger sur le présentation de sa production.'),
(173,	4,	'Arts plastiques',	'Expérimenter, produire, créer',	'Recourir à des outils numériques de captation et de réalisation à des fins de création artistique.',	'Recourir à des outils numériques à des fins de création.'),
(174,	4,	'Arts plastiques',	'Expérimenter, produire, créer',	'S\'approprier des questions artistiques en prenant appui sur une pratique artistique et réflexive.',	'Comprendre les questions artistiques par la pratique.'),
(175,	4,	'Arts plastiques',	'Mettre en œuvre un projet',	'Concevoir, réaliser, donner à voir des projets artistiques, individuels ou collectifs.',	'Concevoir, réaliser, présenter des projets artistiques.'),
(176,	4,	'Arts plastiques',	'Mettre en œuvre un projet',	'Confronter intention et réalisation dans la conduite d\'un projet pour l\'adapter et le réorienter, s\'assurer de la dimension artistique de celui-ci.',	'Confronter intention et réalisation dans son projet.'),
(177,	4,	'Arts plastiques',	'Mettre en œuvre un projet',	'Faire preuve d\'autonomie, d\'initiative, de responsabilité, d\'engagement et d\'esprit critique dans la conduite d\'un projet artistique.',	'Faire preuve d\'autonomie dans son projet artistique.'),
(178,	4,	'Arts plastiques',	'Mettre en œuvre un projet',	'Mener à terme une production individuelle dans le cadre d\'un projet accompagné par le professeur.',	'Mener à terme une production individuelle en classe.'),
(179,	4,	'Arts plastiques',	'Mettre en œuvre un projet',	'Se repérer dans les étapes de la réalisation d\'une production plastique et en anticiper les difficultés éventuelles.',	'Anticiper les difficultés éventuelles dans sa pratique.'),
(180,	4,	'Arts plastiques',	'Se repérer dans les domaines liés aux arts plastiques, être sensible aux questions de l\'art',	'Identifier des caractéristiques (plastiques, culturelles, sémantiques, symboliques) inscrivant une œuvre dans une aire géographique ou culturelle et dans un temps historique.',	'Identifier les caractéristiques spécifiques d\'une oeuvre.'),
(181,	4,	'Arts plastiques',	'Se repérer dans les domaines liés aux arts plastiques, être sensible aux questions de l\'art',	'Interroger et situer œuvres et démarches artistiques du point de vue de l\'auteur et de celui du spectateur.',	'Interroger l\' oeuvre de divers points de vue.'),
(182,	4,	'Arts plastiques',	'Se repérer dans les domaines liés aux arts plastiques, être sensible aux questions de l\'art',	'Prendre part au débat suscité par le fait artistique.',	'Prendre part au débat suscité par le fait artistique.'),
(183,	4,	'Arts plastiques',	'Se repérer dans les domaines liés aux arts plastiques, être sensible aux questions de l\'art',	'Proposer et soutenir l\'analyse et l\'interprétation d\'une œuvre.',	'Proposer l\'analyse et l\'interprétation d\'une oeuvre.'),
(184,	4,	'Arts plastiques',	'Se repérer dans les domaines liés aux arts plastiques, être sensible aux questions de l\'art',	'Reconnaitre et connaitre des œuvres de domaines et d\'époques variés appartenant au patrimoine national et mondial, en saisir le sens et l\'intérêt.',	'Reconnaitre et connaitre des oeuvres variées.'),
(185,	4,	'Arts plastiques',	'S\'exprimer, analyser sa pratique, celle de ses pairs ; établir une relation avec celle des artistes, s\'ouvrir à l\'altérité',	'Dire avec un vocabulaire approprié ce que l\'on fait, ressent, imagine, observe, analyse ; s\'exprimer pour soutenir des intentions artistiques ou une interprétation d\'œuvre.',	'Dire avec un vocabulaire approprié ce que l\'on fait.'),
(186,	4,	'Arts plastiques',	'S\'exprimer, analyser sa pratique, celle de ses pairs ; établir une relation avec celle des artistes, s\'ouvrir à l\'altérité',	'Établir des liens entre son propre travail, les œuvres rencontrées ou les démarches observées.',	'Établir des liens entre son travail, les oeuvres rencontrées'),
(187,	4,	'Arts plastiques',	'S\'exprimer, analyser sa pratique, celle de ses pairs ; établir une relation avec celle des artistes, s\'ouvrir à l\'altérité',	'Expliciter la pratique individuelle ou collective, écouter et accepter les avis divers et contradictoires.',	'Expliciter sa pratique, écouter les avis divers.'),
(188,	4,	'Arts plastiques',	'S\'exprimer, analyser sa pratique, celle de ses pairs ; établir une relation avec celle des artistes, s\'ouvrir à l\'altérité',	'Porter un regard curieux et avisé sur son environnement artistique et culturel, proche et lointain, notamment sur la diversité des images fixes et animées, analogiques et numériques.',	'Porter un regard curieux sur son environnement artistique.'),
(189,	4,	'Education aux médias et à l\'information',	'Exploiter l\'information de manière raisonnée',	'Apprendre à distinguer subjectivité et objectivité dans l\'étude d\'un objet médiatique.',	''),
(190,	4,	'Education aux médias et à l\'information',	'Exploiter l\'information de manière raisonnée',	'Découvrir des représentations du monde véhiculées par les médias.',	''),
(191,	4,	'Education aux médias et à l\'information',	'Exploiter l\'information de manière raisonnée',	'Distinguer les sources d\'information, s\'interroger sur la validité et sur la fiabilité d\'une information, son degré de pertinence.',	''),
(192,	4,	'Education aux médias et à l\'information',	'Exploiter l\'information de manière raisonnée',	'S\'entrainer à distinguer une information scientifique vulgarisée d\'une information pseudo-scientifique grâce à des indices textuels ou paratextuels et à la validation de la source.',	''),
(193,	4,	'Education aux médias et à l\'information',	'Exploiter l\'information de manière raisonnée',	'S\'interroger sur l\'influence des médias sur la consommation et la vie démocratique.',	''),
(194,	4,	'Education aux médias et à l\'information',	'Produire, communiquer, partager des informations',	'Développer des pratiques culturelles à partir d\'outils de production numérique.',	''),
(195,	4,	'Education aux médias et à l\'information',	'Produire, communiquer, partager des informations',	'Distinguer la citation du plagiat.',	''),
(196,	4,	'Education aux médias et à l\'information',	'Produire, communiquer, partager des informations',	'Distinguer la simple collecte d\'informations de la structuration des connaissances.',	''),
(197,	4,	'Education aux médias et à l\'information',	'Produire, communiquer, partager des informations',	'Participer à une production coopérative multimédia en prenant en compte les destinataires.',	''),
(198,	4,	'Education aux médias et à l\'information',	'Produire, communiquer, partager des informations',	'S\'engager dans un projet de création et publication sur papier ou en ligne utile à une communauté d\'utilisateurs dans ou hors de l\'établissement qui respecte droit et éthique de l\'information.',	''),
(199,	4,	'Education aux médias et à l\'information',	'Produire, communiquer, partager des informations',	'Utiliser les plates formes collaboratives numériques pour coopérer avec les autres.',	''),
(200,	4,	'Education aux médias et à l\'information',	'Utiliser les médias de manière responsable',	'Comprendre ce que sont l\'identité et la trace numériques.',	''),
(201,	4,	'Education aux médias et à l\'information',	'Utiliser les médias de manière responsable',	'Pouvoir se référer aux règles de base du droit d\'expression et de publication en particulier sur les réseaux.',	''),
(202,	4,	'Education aux médias et à l\'information',	'Utiliser les médias de manière responsable',	'Se familiariser avec les notions d\'espace privé et d\'espace public.',	''),
(203,	4,	'Education aux médias et à l\'information',	'Utiliser les médias de manière responsable',	'Se questionner sur les enjeux démocratiques liés à la production participative d\'informations et à l\'information journalistique.',	''),
(204,	4,	'Education aux médias et à l\'information',	'Utiliser les médias de manière responsable',	'S\'initier à la déontologie des journalistes.',	''),
(205,	4,	'Education aux médias et à l\'information',	'Utiliser les médias et les informations de manière autonome',	'Acquérir une méthode de recherche exploratoire d\'informations et de leur exploitation par l\'utilisation avancée des moteurs de recherche.',	''),
(206,	4,	'Education aux médias et à l\'information',	'Utiliser les médias et les informations de manière autonome',	'Adopter progressivement une démarche raisonnée dans la recherche d\'informations.',	''),
(207,	4,	'Education aux médias et à l\'information',	'Utiliser les médias et les informations de manière autonome',	'Avoir connaissance du fonds d\'ouvrages en langue étrangère ou régionale disponible au CDI et les utiliser régulièrement.',	''),
(208,	4,	'Education aux médias et à l\'information',	'Utiliser les médias et les informations de manière autonome',	'Classer ses propres documents sur sa tablette, son espace personnel, au collège ou chez soi sur des applications mobiles ou dans le « nuage ». Organiser des portefeuilles thématiques.',	''),
(209,	4,	'Education aux médias et à l\'information',	'Utiliser les médias et les informations de manière autonome',	'Découvrir comment l\'information est indexée et hiérarchisée, comprendre les principaux termes techniques associés.',	''),
(210,	4,	'Education aux médias et à l\'information',	'Utiliser les médias et les informations de manière autonome',	'Exploiter le centre de ressources comme outil de recherche de l\'information.',	''),
(211,	4,	'Education aux médias et à l\'information',	'Utiliser les médias et les informations de manière autonome',	'Exploiter les modes d\'organisation de l\'information dans un corpus documentaire (clés du livre documentaire, rubriquage d\'un périodique, arborescence d\'un site).',	''),
(212,	4,	'Education aux médias et à l\'information',	'Utiliser les médias et les informations de manière autonome',	'Se familiariser avec les différents modes d\'expression des médias en utilisant leurs canaux de diffusion.',	''),
(213,	4,	'Education aux médias et à l\'information',	'Utiliser les médias et les informations de manière autonome',	'Utiliser des dictionnaires et encyclopédies sur tous supports.',	''),
(214,	4,	'Education aux médias et à l\'information',	'Utiliser les médias et les informations de manière autonome',	'Utiliser des documents de vulgarisation scientifique.',	''),
(215,	4,	'Education aux médias et à l\'information',	'Utiliser les médias et les informations de manière autonome',	'Utiliser les genres et les outils d\'information à disposition adaptés à ses recherches.',	''),
(216,	4,	'Éducation musicale',	'Échanger, partager, argumenter et débattre',	'Argumenter une critique adossée à une analyse objective.',	'Argumenter une critique adossée à une analyse objective.'),
(217,	4,	'Éducation musicale',	'Échanger, partager, argumenter et débattre',	'Développer une critique constructive sur une production collective.',	'Développer une critique constructive sur une production coll'),
(218,	4,	'Éducation musicale',	'Échanger, partager, argumenter et débattre',	'Distinguer les postures de créateur, d\'interprète et d\'auditeur.',	'Distinguer créateur, interprète et auditeur.'),
(219,	4,	'Éducation musicale',	'Échanger, partager, argumenter et débattre',	'Porter un regard critique sur sa production individuelle.',	'Porter un regard critique sur sa production individuelle.'),
(220,	4,	'Éducation musicale',	'Échanger, partager, argumenter et débattre',	'Respecter les sources et les droits d\'auteur et l\'utilisation de sons libres de droit.',	'Respecter sources, droits d\'auteur et sons libres de droits'),
(221,	4,	'Éducation musicale',	'Écouter, comparer, construire une culture musicale commune',	'Analyser des œuvres musicales en utilisant un vocabulaire précis.',	'Analyser des œuvres en utilisant un vocabulaire précis.'),
(222,	4,	'Éducation musicale',	'Écouter, comparer, construire une culture musicale commune',	'Identifier par comparaison les différences et ressemblances dans l\'interprétation d\'une œuvre donnée.',	'Identifier les différences et ressemblances d\'interprétation'),
(223,	4,	'Éducation musicale',	'Écouter, comparer, construire une culture musicale commune',	'Situer et comparer des musiques de styles proches ou éloignés dans l\'espace et/ou dans le temps pour construire des repères techniques et culturels.',	'Situer, comparer des musiques proches ou éloignées'),
(224,	4,	'Éducation musicale',	'Explorer, imaginer, créer et produire',	'Concevoir, réaliser, arranger, pasticher une courte pièce préexistante, notamment à l\'aide d\'outils numériques.',	'Concevoir, réaliser, arranger, pasticher une courte pièce'),
(225,	4,	'Éducation musicale',	'Explorer, imaginer, créer et produire',	'Réinvestir ses expériences personnelles de la création musicale pour écouter, comprendre et commenter celles des autres.',	'Comprendre commenter une création à partir de son expérience'),
(226,	4,	'Éducation musicale',	'Explorer, imaginer, créer et produire',	'Réutiliser certaines caractéristiques (style, technique, etc.) d\'une œuvre connue pour nourrir son travail.',	'Utiliser les caractéristiques d\'une œuvre dans son travail.'),
(227,	4,	'Éducation musicale',	'Réaliser des projets musicaux d\'interprétation ou de création',	'Définir les caractéristiques musicales d\'un projet, puis en assurer la mise en œuvre en mobilisant les ressources adaptées.',	'Mobiliser les ressources, assurer la mise en œuvre du projet'),
(228,	4,	'Éducation musicale',	'Réaliser des projets musicaux d\'interprétation ou de création',	'Interpréter un projet devant d\'autres élèves et présenter les choix artistiques effectués.',	'Interpréter un projet et présenter ses choix artistiques'),
(229,	4,	'Éducation physique et sportive',	'Apprendre à entretenir sa santé par une activité physique régulière, raisonnée et raisonnable',	'Adapter l\'intensité de son engagement physique à ses possibilités pour ne pas se mettre en     danger.',	'Adapter son engagement physique'),
(230,	4,	'Éducation physique et sportive',	'Apprendre à entretenir sa santé par une activité physique régulière, raisonnée et raisonnable',	'Connaître et utiliser des indicateurs objectifs pour caractériser l\'effort physique.',	'Utiliser les indicateurs pour caracteriser l\'effort physique'),
(231,	4,	'Éducation physique et sportive',	'Apprendre à entretenir sa santé par une activité physique régulière, raisonnée et raisonnable',	'Connaître les effets d\'une pratique physique régulière sur son état de bien-être et de santé.',	'Connaître les effets d\'une pratique physique régulière'),
(232,	4,	'Éducation physique et sportive',	'Apprendre à entretenir sa santé par une activité physique régulière, raisonnée et raisonnable',	'Evaluer la quantité et qualité de son activité physique quotidienne dans et hors l\'école.',	'Evaluer son activité physique quotidienne'),
(233,	4,	'Éducation physique et sportive',	'Développer sa motricité et apprendre à s\'exprimer avec son corps',	'Acquérir des techniques spécifiques pour améliorer son efficience.',	'Acquérir des techniques spécifiques pour s\'améliorer'),
(234,	4,	'Éducation physique et sportive',	'Développer sa motricité et apprendre à s\'exprimer avec son corps',	'Communiquer des intentions et des émotions avec son corps devant un groupe.',	'Communiquer des intentions et des émotions avec son corps'),
(235,	4,	'Éducation physique et sportive',	'Développer sa motricité et apprendre à s\'exprimer avec son corps',	'Utiliser un vocabulaire adapté pour décrire la motricité d\'autrui et la sienne.',	'Utiliser un vocabulaire adapté pour décrire la motricité'),
(236,	4,	'Éducation physique et sportive',	'Développer sa motricité et apprendre à s\'exprimer avec son corps',	'Verbaliser les émotions et sensations ressenties.',	'Verbaliser les émotions et sensations ressenties.'),
(237,	4,	'Éducation physique et sportive',	'Partager des règles, assumer des rôles  et des responsabilités',	'Accepter la défaite et gagner avec modestie et simplicité.',	'Accepter la défaite et gagner avec modestie et simplicité.'),
(238,	4,	'Éducation physique et sportive',	'Partager des règles, assumer des rôles  et des responsabilités',	'Agir avec et pour les autres, en prenant en compte les différences.',	'Agir avec et pour les autres, accepter les différences'),
(239,	4,	'Éducation physique et sportive',	'Partager des règles, assumer des rôles  et des responsabilités',	'Prendre et assumer des responsabilités au sein d\'un collectif pour réaliser un projet ou remplir un contrat.',	'Prendre et assumer des responsabilités dans un groupe'),
(240,	4,	'Éducation physique et sportive',	'Partager des règles, assumer des rôles  et des responsabilités',	'Respecter, construire et faire respecter règles et règlements.',	'Respecter, construire / faire respecter règles et règlements'),
(241,	4,	'Éducation physique et sportive',	'S\'approprier seul ou à plusieurs par la pratique, les méthodes et outils  pour apprendre',	'Construire et mettre en œuvre des projets d\'apprentissage individuel ou collectif.',	'Construire et mettre en œuvre des projets d\'apprentissage.'),
(242,	4,	'Éducation physique et sportive',	'S\'approprier seul ou à plusieurs par la pratique, les méthodes et outils  pour apprendre',	'Préparer-planifier-se représenter une action avant de la réaliser.',	'Préparer-planifier-se représenter une action avant.'),
(243,	4,	'Éducation physique et sportive',	'S\'approprier seul ou à plusieurs par la pratique, les méthodes et outils  pour apprendre',	'Répéter un geste sportif ou artistique pour le stabiliser et le rendre plus efficace.',	'Répéter un geste sportif ou artistique'),
(244,	4,	'Éducation physique et sportive',	'S\'approprier seul ou à plusieurs par la pratique, les méthodes et outils  pour apprendre',	'Utiliser des outils numériques pour analyser et évaluer ses actions et celles des autres.',	'Utiliser des outils numériques pour analyser les pratiques'),
(245,	4,	'Éducation physique et sportive',	'S\'approprier une culture physique sportive et artistique pour construire progressivement un regard lucide sur le monde contemporain',	'Acquérir les bases d\'une attitude réflexive et critique  vis-à-vis du spectacle sportif.',	'Acquérir les bases d\'une attitude réflexive et critique'),
(246,	4,	'Éducation physique et sportive',	'S\'approprier une culture physique sportive et artistique pour construire progressivement un regard lucide sur le monde contemporain',	'Connaître  des éléments essentiels de l\'histoire des pratiques corporelles éclairant les activités physiques contemporaines.',	'Connaître  des éléments de l\'histoire des pratiques'),
(247,	4,	'Éducation physique et sportive',	'S\'approprier une culture physique sportive et artistique pour construire progressivement un regard lucide sur le monde contemporain',	'Découvrir l\'impact des nouvelles technologies appliquées à la pratique physique et sportive.',	'Découvrir l\'impact des nouvelles technologies'),
(248,	4,	'Éducation physique et sportive',	'S\'approprier une culture physique sportive et artistique pour construire progressivement un regard lucide sur le monde contemporain',	'S\'approprier, exploiter et savoir expliquer les principes d\'efficacité d\'un geste technique.',	'Définir les principes d\'efficacité d\'un geste technique.'),
(249,	4,	'Enseignement moral et civique',	'L\'engagement : agir individuellement et collectivement',	'Prendre en charge des aspects de la vie collective et de l\'environnement et développer une conscience citoyenne, sociale et écologique.',	''),
(250,	4,	'Enseignement moral et civique',	'L\'engagement : agir individuellement et collectivement',	'S\'engager et assumer des responsabilités dans l\'école et dans l\'établissement.',	''),
(251,	4,	'Enseignement moral et civique',	'La sensibilité : soi et les autres',	'Identifier et exprimer en les régulant ses émotions et ses sentiments.',	''),
(252,	4,	'Enseignement moral et civique',	'La sensibilité : soi et les autres',	'S\'estimer et être capable d\'écoute et d\'empathie.',	''),
(253,	4,	'Enseignement moral et civique',	'La sensibilité : soi et les autres',	'Se sentir membre d\'une collectivité.',	''),
(254,	4,	'Enseignement moral et civique',	'Le droit et la règle : des principes pour vivre avec les autres',	'Comprendre les principes et les valeurs de la République française et des sociétés démocratiques.',	''),
(255,	4,	'Enseignement moral et civique',	'Le droit et la règle : des principes pour vivre avec les autres',	'Comprendre les raisons de l\'obéissance aux règles et à la loi dans une société démocratique.',	''),
(256,	4,	'Enseignement moral et civique',	'Le jugement : penser par soi-même et avec les autres',	'Développer les aptitudes à la réflexion critique : en recherchant les critères de validité des jugements moraux ; en confrontant ses jugements à ceux d\'autrui dans une discussion ou un débat argumenté',	''),
(257,	4,	'Enseignement moral et civique',	'Le jugement : penser par soi-même et avec les autres',	'Différencier son intérêt particulier de l\'intérêt général.',	''),
(258,	4,	'Français',	'Acquérir des éléments de culture littéraire et artistique',	'Établir des liens entre des productions littéraires et artistiques issues de cultures  et d\'époques diverses.',	'Rapprocher œuvres littér. et artist. de cult. et époq. dif.'),
(259,	4,	'Français',	'Acquérir des éléments de culture littéraire et artistique',	'Mobiliser des références culturelles pour interpréter les textes et les productions artistiques et littéraires et pour enrichir son expression personnelle.',	'Mobiliser des réf. culturelles pour interpréter des œuvres.'),
(260,	4,	'Français',	'Comprendre et s\'exprimer à l\'oral',	'Comprendre et interpréter des messages et des discours oraux complexes.',	'Comprendre et interpréter des messages et des discours oraux'),
(261,	4,	'Français',	'Comprendre et s\'exprimer à l\'oral',	'Exploiter les ressources expressives et créatives de la parole.',	'Exploiter les ressources expressives/créatives de la parole'),
(262,	4,	'Français',	'Comprendre et s\'exprimer à l\'oral',	'Participer de façon constructive à des échanges oraux.',	'Participer de façon constructive à des échanges oraux.'),
(263,	4,	'Français',	'Comprendre et s\'exprimer à l\'oral',	'S\'exprimer de façon maitrisée en s\'adressant à un auditoire.',	'S\'exprimer de façon maitrisée en s\'adressant à un auditoire.'),
(264,	4,	'Français',	'Comprendre le fonctionnement de la langue',	'Connaitre les aspects fondamentaux du fonctionnement syntaxique.',	'Connaitre les aspects du fonctionnement syntaxique.'),
(265,	4,	'Français',	'Comprendre le fonctionnement de la langue',	'Connaitre les différences entre l\'oral et l\'écrit.',	'Connaitre les différences entre l\'oral et l\'écrit.'),
(266,	4,	'Français',	'Comprendre le fonctionnement de la langue',	'Construire les notions permettant l\'analyse et la production des textes et des discours.',	''),
(267,	4,	'Français',	'Comprendre le fonctionnement de la langue',	'Maitriser la forme des mots en lien avec la syntaxe.',	'Maitriser la forme des mots en lien avec la syntaxe.'),
(268,	4,	'Français',	'Comprendre le fonctionnement de la langue',	'Maitriser la structure, le sens et l\'orthographe des mots.',	'Maitriser la structure, le sens et l\'orthographe des mots.'),
(269,	4,	'Français',	'Comprendre le fonctionnement de la langue',	'Maitriser le fonctionnement du verbe et son orthographe.',	'Maitriser le fonctionnement du verbe et son orthographe.'),
(270,	4,	'Français',	'Comprendre le fonctionnement de la langue',	'Utiliser des repères étymologiques et d\'histoire de la langue.',	'Utiliser des repères étymologiques/d\'histoire de la langue'),
(271,	4,	'Français',	'Écrire',	'Adopter des stratégies et des procédures d\'écriture efficaces.',	'Adopter des stratégies et des procédures d\'écriture efficace'),
(272,	4,	'Français',	'Écrire',	'Exploiter des lectures pour enrichir son écrit.',	'Exploiter des lectures pour enrichir son écrit.'),
(273,	4,	'Français',	'Écrire',	'Utiliser l\'écrit pour penser et pour apprendre.',	'Utiliser l\'écrit pour penser et pour apprendre.'),
(274,	4,	'Français',	'Lire',	'Élaborer une interprétation de textes littéraires.',	'Élaborer une interprétation de textes littéraires.'),
(275,	4,	'Français',	'Lire',	'Lire des images, des documents composites (y compris numériques) et des textes non littéraires.',	'Lire des images, des doc. composites et des textes non lit.'),
(276,	4,	'Français',	'Lire',	'Lire des œuvres littéraires, fréquenter des œuvres d\'art.',	'Lire des œuvres littéraires, fréquenter des œuvres d\'art.'),
(277,	4,	'Histoire des arts',	'Associer une œuvre à une époque et une civilisation à partir des éléments observés.',	'Associer une œuvre à une époque et une civilisation à partir des éléments observés.',	'Associer une œuvre à une époque et une civilisation'),
(278,	4,	'Histoire des arts',	'Construire un exposé de quelques minutes sur un petit ensemble d\'œuvres ou une problématique artistique.',	'Construire un exposé de quelques minutes sur un petit ensemble d\'œuvres ou une problématique artistique.',	'Construire un exposé sur des œuvres ou une problématique'),
(279,	4,	'Histoire des arts',	'Décrire une œuvre d\'art en employant un lexique simple adapté.',	'Décrire une œuvre d\'art en employant un lexique simple adapté.',	'Décrire une œuvre d\'art en employant un lexique adapté.'),
(280,	4,	'Histoire des arts',	'Proposer une analyse critique simple et une interprétation d\'une œuvre.',	'Proposer une analyse critique simple et une interprétation d\'une œuvre.',	'Proposer une analyse critique simple et une interprétation'),
(281,	4,	'Histoire des arts',	'Rendre compte de la visite d\'un lieu de conservation ou de diffusion artistique ou de la rencontre avec un métier du patrimoine.',	'Rendre compte de la visite d\'un lieu de conservation ou de diffusion artistique ou de la rencontre avec un métier du patrimoine.',	'Rendre compte d\'une visite ou de la rencontre avec un métier'),
(282,	4,	'Histoire et géographie',	'Analyser et comprendre un document',	'Comprendre le sens général d\'un document.',	'Comprendre le sens général d\'un document.'),
(283,	4,	'Histoire et géographie',	'Analyser et comprendre un document',	'Confronter un document à ce qu\'on peut connaître par ailleurs du sujet étudié.',	'Relier des documents, les confronter à ses connaissances'),
(284,	4,	'Histoire et géographie',	'Analyser et comprendre un document',	'Extraire des informations pertinentes pour répondre à une question portant sur un document ou plusieurs documents, les classer, les hiérarchiser.',	''),
(285,	4,	'Histoire et géographie',	'Analyser et comprendre un document',	'Identifier le document et son point de vue particulier.',	''),
(286,	4,	'Histoire et géographie',	'Analyser et comprendre un document',	'Utiliser ses connaissances pour expliciter, expliquer le document et exercer son esprit critique.',	''),
(287,	4,	'Histoire et géographie',	'Coopérer et mutualiser',	'Adapter son rythme de travail à celui du groupe.',	''),
(288,	4,	'Histoire et géographie',	'Coopérer et mutualiser',	'Apprendre à utiliser les outils numériques qui peuvent conduire à des réalisations collectives.',	''),
(289,	4,	'Histoire et géographie',	'Coopérer et mutualiser',	'Discuter, expliquer, confronter ses représentations, argumenter pour défendre ses choix.',	''),
(290,	4,	'Histoire et géographie',	'Coopérer et mutualiser',	'Négocier une solution commune si une production collective est demandée.',	''),
(291,	4,	'Histoire et géographie',	'Coopérer et mutualiser',	'Organiser son travail dans le cadre d\'un groupe pour élaborer une tâche commune et/ou une production collective et mettre à la disposition des autres ses compétences et ses connaissances.',	''),
(292,	4,	'Histoire et géographie',	'Pratiquer différents langages en histoire et en géographie',	'Écrire pour construire sa pensée et son savoir, pour argumenter et écrire pour communiquer et échanger.',	'Écrire pour se construire, communiquer et échanger.'),
(293,	4,	'Histoire et géographie',	'Pratiquer différents langages en histoire et en géographie',	'Réaliser des productions graphiques et cartographiques.',	'Réaliser des productions graphiques et cartographiques.'),
(294,	4,	'Histoire et géographie',	'Pratiquer différents langages en histoire et en géographie',	'Réaliser une production audio-visuelle, un diaporama.',	'Réaliser une production audio-visuelle, un diaporama.'),
(295,	4,	'Histoire et géographie',	'Pratiquer différents langages en histoire et en géographie',	'S\'approprier et utiliser un lexique spécifique en contexte.',	'S\'approprier et utiliser un lexique spécifique en contexte.'),
(296,	4,	'Histoire et géographie',	'Pratiquer différents langages en histoire et en géographie',	'S\'exprimer à l\'oral pour penser, communiquer et échanger. Connaître les caractéristiques des récits historiques et des descriptions employées en histoire et en géographie, et en réaliser.',	'S\'exprimer à l\'oral pour penser, communiquer et échanger.'),
(297,	4,	'Histoire et géographie',	'Pratiquer différents langages en histoire et en géographie',	'S\'initier aux techniques d\'argumentation.',	'S\'initier aux techniques d\'argumentation.'),
(298,	4,	'Histoire et géographie',	'Raisonner, justifier une démarche et les choix effectués',	'Construire des hypothèses d\'interprétation de phénomènes historiques ou géographiques.',	''),
(299,	4,	'Histoire et géographie',	'Raisonner, justifier une démarche et les choix effectués',	'Justifier une démarche, une interprétation.',	''),
(300,	4,	'Histoire et géographie',	'Raisonner, justifier une démarche et les choix effectués',	'Poser des questions, se poser des questions à propos de situations historiques ou/et géographiques.',	''),
(301,	4,	'Histoire et géographie',	'Raisonner, justifier une démarche et les choix effectués',	'Vérifier des données et des sources.',	''),
(302,	4,	'Histoire et géographie',	'Se repérer dans le temps : construire des repères historiques',	'Identifier des continuités et des ruptures chronologiques pour s\'approprier la périodisation de l\'histoire et pratiquer de conscients allers-retours au sein de la chronologie.',	'Maitriser et contextualiser des évènements historiques'),
(303,	4,	'Histoire et géographie',	'Se repérer dans le temps : construire des repères historiques',	'Mettre en relation des faits d\'une époque ou d\'une période donnée.',	''),
(304,	4,	'Histoire et géographie',	'Se repérer dans le temps : construire des repères historiques',	'Ordonner des faits les uns par rapport aux autres.',	'Ordonner des faits les uns par rapport aux autres.'),
(305,	4,	'Histoire et géographie',	'Se repérer dans le temps : construire des repères historiques',	'Situer un fait dans une époque ou une période donnée.',	'Situer un fait dans une époque ou une période donnée.'),
(306,	4,	'Histoire et géographie',	'Se repérer dans l\'espace : construire des repères géographiques',	'Nommer et localiser les grands repères géographiques.',	'Maitriser les repères géographiques fondamentaux'),
(307,	4,	'Histoire et géographie',	'Se repérer dans l\'espace : construire des repères géographiques',	'Nommer, localiser et caractériser des espaces plus complexes.',	''),
(308,	4,	'Histoire et géographie',	'Se repérer dans l\'espace : construire des repères géographiques',	'Nommer, localiser et caractériser un lieu dans un espace géographique.',	''),
(309,	4,	'Histoire et géographie',	'Se repérer dans l\'espace : construire des repères géographiques',	'Situer des lieux et des espaces les uns par rapport aux autres.',	''),
(310,	4,	'Histoire et géographie',	'Se repérer dans l\'espace : construire des repères géographiques',	'Utiliser des représentations analogiques et numériques des espaces à différentes échelles ainsi que différents modes de projection.',	''),
(311,	4,	'Histoire et géographie',	'S\'informer dans le monde du numérique',	'Connaître différents systèmes d\'information, les utiliser.',	''),
(312,	4,	'Histoire et géographie',	'S\'informer dans le monde du numérique',	'Exercer son esprit critique sur les données numériques, en apprenant à les comparer à celles qu\'on peut tirer de documents de divers types.',	''),
(313,	4,	'Histoire et géographie',	'S\'informer dans le monde du numérique',	'S\'informer dans le monde du numérique',	'S\'informer dans le monde du numérique'),
(314,	4,	'Histoire et géographie',	'S\'informer dans le monde du numérique',	'Trouver, sélectionner et exploiter des informations.',	''),
(315,	4,	'Histoire et géographie',	'S\'informer dans le monde du numérique',	'Utiliser des moteurs de recherche, des dictionnaires et des encyclopédies en ligne, des sites et des réseaux de ressources documentaires, des manuels numériques, des systèmes d\'information géographiqu',	''),
(316,	4,	'Histoire et géographie',	'S\'informer dans le monde du numérique',	'Vérifier l\'origine/la source des informations et leur pertinence.',	''),
(317,	4,	'Langues vivantes',	'Découvrir les aspects culturels d\'une langue vivante étrangère et régionale',	'Mobiliser des références culturelles pour interpréter les éléments d\'un message, d\'un texte, d\'un document sonore.',	'Mobiliser des références culturelles pour interpréter l\'ecri'),
(318,	4,	'Langues vivantes',	'Découvrir les aspects culturels d\'une langue vivante étrangère et régionale',	'Mobiliser ses connaissances culturelles pour décrire des personnages réels ou imaginaires, raconter.',	'Mobiliser ses connaissances culturelles, décrire, raconter'),
(319,	4,	'Langues vivantes',	'Découvrir les aspects culturels d\'une langue vivante étrangère et régionale',	'Percevoir les spécificités culturelles des pays et des régions de la langue étudiée en dépassant la vision figée et schématique des stéréotypes et des clichés.',	'Percevoir les spécificités culturelles au-delà des clichés'),
(320,	4,	'Langues vivantes',	'Écouter et comprendre',	'Comprendre des messages oraux et des documents sonores de nature et de complexité variables.',	'Comprendre des documents sonores de complexité variable'),
(321,	4,	'Langues vivantes',	'Écouter et comprendre',	'Repérer des indices pertinents, extralinguistiques ou linguistiques, pour identifier la situation d\'énonciation et déduire le sens d\'un message.',	'Repérer des indices pertinents pr déduire le sens d\'1message'),
(322,	4,	'Langues vivantes',	'Écouter et comprendre',	'Savoir lire des documents vidéo et savoir mettre en relation images et documents sonores.',	'Savoir mettre en relation images et documents sonores'),
(323,	4,	'Langues vivantes',	'Écouter et comprendre',	'Se familiariser aux réalités sonores de la langue, et s\'entrainer à la mémorisation.',	'Se familiariser aux sonorités, s\'entrainer, mémoriser'),
(324,	4,	'Langues vivantes',	'Écrire',	'Mobiliser les outils pour écrire, corriger, modifier son écrit.',	'Mobiliser les outils pour écrire, corriger, modifier l\' écri'),
(325,	4,	'Langues vivantes',	'Écrire',	'Reformuler un message, rendre compte, raconter, décrire, expliquer, argumenter.',	'Reformuler un message, raconter, décrire, argumenter'),
(326,	4,	'Langues vivantes',	'Écrire',	'S\'appuyer sur les stratégies développées à l\'oral pour apprendre à structurer son écrit.',	'S\'appuyer sur stratégies de l\'oral pour structurer son écrit'),
(327,	4,	'Langues vivantes',	'Lire',	'Comprendre des documents écrits de nature et de difficultés variées issus de sources diverses.',	'Comprendre des documents écrits de difficulté variée'),
(328,	4,	'Langues vivantes',	'Lire',	'Développer des stratégies de lecteur par le biais de lectures régulières.',	'Développer des stratégies de lecteur par lectures régulières'),
(329,	4,	'Langues vivantes',	'Lire',	'S\'approprier le document en utilisant des repérages de nature différente : indices extralinguistiques, linguistiques, reconstitution du sens, mise en relation d\'éléments significatifs.',	'S\'approprier le document en repérant des indices'),
(330,	4,	'Langues vivantes',	'Parler en continu',	'Développer des stratégies pour surmonter un manque lexical lors d\'une prise de parole, s\'autocorriger et reformuler pour se faire comprendre.',	'Développer des stratégies pour surmonter un manque lexical'),
(331,	4,	'Langues vivantes',	'Parler en continu',	'Mettre en voix son discours par la prononciation, l\'intonation et la gestuelle adéquates.',	'Mettre en voix son discours par la prononciation+intonation'),
(332,	4,	'Langues vivantes',	'Parler en continu',	'Mobiliser à bon escient ses connaissances lexicales, culturelles, grammaticales pour produire un texte oral sur des sujets variés.',	'Mobiliser ses connaissances pour produire un texte oral'),
(333,	4,	'Langues vivantes',	'Parler en continu',	'Prendre la parole pour raconter, décrire, expliquer, argumenter.',	'Prendre la parole pour raconter-décrire-expliquer-argumenter'),
(334,	4,	'Langues vivantes',	'Parler en continu',	'Respecter un registre et un niveau de langue.',	'Respecter un registre et un niveau de langue'),
(335,	4,	'Langues vivantes',	'Réagir et dialoguer',	'Développer des stratégies de compréhension orale en repérant des indices extralinguistiques ou linguistiques et en élaborant un discours commun.',	'Repérer des indices pour mieux comprendre l\'oral'),
(336,	4,	'Langues vivantes',	'Réagir et dialoguer',	'Réagir spontanément à des sollicitations verbales, en mobilisant des énoncés adéquats au contexte, dans une succession d\'échanges qui alimentent le message ou le contredisent.',	'Réagir spontanément à des sollicitations verbales'),
(337,	4,	'Latin',	'Comprendre, appliquer',	'Comprendre le fonctionnement de la langue',	'Comprendre le fonctionnement de la langue'),
(338,	4,	'Latin',	'Comprendre, appliquer',	'Comprendre le principe des langues à déclinaison',	'Comprendre le principe des langues à déclinaison'),
(339,	4,	'Latin',	'Comprendre, appliquer',	'Utiliser les connaissances en morphologie et en syntaxe',	'Utiliser les connaissances en morphologie et en syntaxe'),
(340,	4,	'Latin',	'Comprendre, appliquer',	'Utiliser les ressources et outils',	'Utiliser les ressources et outils'),
(341,	4,	'Latin',	'Découvrir les aspects culturels du latin',	'Acquerir des éléments de culture antique',	'Acquerir des éléments de culture antique'),
(342,	4,	'Latin',	'Découvrir les aspects culturels du latin',	'Disposer de connaissances sur les oeuvres',	'Disposer de connaissances sur les oeuvres'),
(343,	4,	'Latin',	'Découvrir les aspects culturels du latin',	'Repérer l\'influence des oeuvres antiques',	'Repérer l\'influence des oeuvres antiques'),
(344,	4,	'Latin',	'Lire, traduire, écrire, interpréter',	'Accéder au sens d\'un énoncé simple',	'Accéder au sens d\'un énoncé simple'),
(345,	4,	'Latin',	'Lire, traduire, écrire, interpréter',	'Justifier la traduction d\'un passage',	'Justifier la traduction d\'un passage'),
(346,	4,	'Latin',	'Lire, traduire, écrire, interpréter',	'Lire, comprendre, traduire, interpréter',	'Lire, comprendre, traduire, interpréter'),
(347,	4,	'Mathématiques',	'Calculer',	'Calculer avec des nombres rationnels, de manière exacte ou approchée, en combinant de façon appropriée le calcul mental, le calcul posé et le calcul instrumenté (calculatrice ou logiciel).',	'Calcul avec des  rationnels  (mental, posé ou instrumenté)'),
(348,	4,	'Mathématiques',	'Calculer',	'Calculer en utilisant le langage algébrique (lettres, symboles, etc.).',	'Calculer avec le langage algébrique (lettres, symboles, ...)'),
(349,	4,	'Mathématiques',	'Calculer',	'Contrôler la vraisemblance de ses résultats, notamment en estimant des ordres de grandeur ou en utilisant des encadrements.',	'Contrôler la vraisemblance de ses résultats.'),
(350,	4,	'Mathématiques',	'Chercher',	'Décomposer un problème en sous-problèmes.',	'Décomposer un problème en sous-problèmes.'),
(351,	4,	'Mathématiques',	'Chercher',	'Extraire d\'un document les informations utiles, les reformuler, les organiser, les confronter à ses connaissances.',	'Extraire les informations utiles et savoir les utiliser.'),
(352,	4,	'Mathématiques',	'Chercher',	'S\'engager dans une démarche scientifique, observer, questionner, manipuler, expérimenter (sur une feuille de papier, avec des objets, à l\'aide de logiciels), émettre des hypothèses, chercher des exemp',	'S\'engager dans une démarche scientifique.'),
(353,	4,	'Mathématiques',	'Chercher',	'Tester, essayer plusieurs pistes de résolution.',	'Tester, essayer plusieurs pistes de résolution.'),
(354,	4,	'Mathématiques',	'Communiquer',	'Expliquer à l\'oral ou à l\'écrit (sa démarche, son raisonnement, un calcul, un protocole de construction géométrique, un algorithme), comprendre les explications d\'un autre et argumenter dans l\'échange',	'Savoir expliquer, comprendre les explications et argumenter.'),
(355,	4,	'Mathématiques',	'Communiquer',	'Faire le lien entre le langage naturel et le langage algébrique. Distinguer des spécificités du langage mathématique par rapport à la langue française.',	'Faire le lien entre langage naturel et langage algébrique.'),
(356,	4,	'Mathématiques',	'Communiquer',	'Vérifier la validité d\'une information et distinguer ce qui est objectif et ce qui est subjectif ; lire, interpréter, commenter, produire des tableaux, des graphiques, des diagrammes.',	'Vérifier la validité d\'une information. Graphiques.'),
(357,	4,	'Mathématiques',	'Modéliser',	'Comprendre et utiliser une simulation numérique ou géométrique.',	'Comprendre / utiliser une simulation numérique /géométrique.'),
(358,	4,	'Mathématiques',	'Modéliser',	'Reconnaître des situations de proportionnalité et résoudre les problèmes correspondants.',	'Reconnaître des situations de proportionnalité, problèmes.'),
(359,	4,	'Mathématiques',	'Modéliser',	'Traduire en langage mathématique une situation réelle (par exemple à l\'aide d\'équations, de fonctions, de configurations géométriques, d\'outils statistiques).',	'Traduire en langage mathématique une situation réelle.'),
(360,	4,	'Mathématiques',	'Modéliser',	'Valider ou invalider un modèle, comparer une situation à un modèle connu (par exemple un modèle aléatoire).',	'Valider ou invalider un modèle (par exemple aléatoire)'),
(361,	4,	'Mathématiques',	'Raisonner',	'Démontrer : utiliser un raisonnement logique et des règles établies (propriétés, théorèmes, formules) pour parvenir à une conclusion.',	'Démontrer :  raisonner, utiliser des règles établies.'),
(362,	4,	'Mathématiques',	'Raisonner',	'Fonder et défendre ses jugements en s\'appuyant sur des résultats établis et sur sa maîtrise de l\'argumentation.',	'Fonder/défendre ses jugements, maitrise de l\'argumentation'),
(363,	4,	'Mathématiques',	'Raisonner',	'Mener collectivement une investigation en sachant prendre en compte le point de vue d\'autrui.',	'Mener collectivement une investigation.'),
(364,	4,	'Mathématiques',	'Raisonner',	'Résoudre des problèmes impliquant des grandeurs variées (géométriques, physiques, économiques) : mobiliser les connaissances nécessaires, analyser et exploiter ses erreurs, mettre à l\'essai plusieurs',	'Résoudre des problèmes impliquant des grandeurs variées'),
(365,	4,	'Mathématiques',	'Représenter',	'Choisir et mettre en relation des cadres (numérique, algébrique, géométrique) adaptés pour traiter un problème ou pour étudier un objet mathématique.',	'Choisir des cadres adaptés pour traiter un problème.'),
(366,	4,	'Mathématiques',	'Représenter',	'Produire et utiliser plusieurs représentations des nombres.',	'Produire et utiliser plusieurs représentations des nombres.'),
(367,	4,	'Mathématiques',	'Représenter',	'Représenter des données sous forme d\'une série statistique.',	'Représenter des données sous forme d\'une série statistique.'),
(368,	4,	'Mathématiques',	'Représenter',	'Utiliser, produire et mettre en relation des représentations de solides (par exemple perspective ou vue de dessus/de dessous) et de situations spatiales (schémas, croquis, maquettes, patrons, figures',	'Solides : utiliser, produire, relier  des représentations.'),
(369,	4,	'Physique-Chimie',	'Adopter un comportement éthique et responsable',	'Adopter un comportement éthique et responsable',	'Adopter un comportement éthique et responsable'),
(370,	4,	'Physique-Chimie',	'Adopter un comportement éthique et responsable',	'Expliquer les fondements des règles de sécurité en chimie, électricité et acoustique. Réinvestir ces connaissances ainsi que celles sur les ressources et sur l\'énergie, pour agir de façon responsable.',	''),
(371,	4,	'Physique-Chimie',	'Adopter un comportement éthique et responsable',	'S\'impliquer dans un projet ayant une dimension citoyenne.',	''),
(372,	4,	'Physique-Chimie',	'Concevoir, créer, réaliser',	'Concevoir et réaliser un dispositif de mesure ou d\'observation.',	'Concevoir / réaliser un dispositif de mesure / d\'observation'),
(373,	4,	'Physique-Chimie',	'Mobiliser des outils numériques',	'Mobiliser des outils numériques au cours de la démarche',	'Mobiliser des outils numériques au cours de la démarche'),
(374,	4,	'Physique-Chimie',	'Mobiliser des outils numériques',	'Produire des documents scientifiques grâce à des outils numériques, en utilisant l\'argumentation et le vocabulaire spécifique à la physique et à la chimie.',	''),
(375,	4,	'Physique-Chimie',	'Mobiliser des outils numériques',	'Utiliser des outils d\'acquisition et de traitement de données, de simulations et de modèles numériques.',	''),
(376,	4,	'Physique-Chimie',	'Pratiquer des démarches scientifiques',	'Développer des modèles simples pour expliquer des faits d\'observations et mettre en œuvre des démarches propres aux sciences.',	''),
(377,	4,	'Physique-Chimie',	'Pratiquer des démarches scientifiques',	'Identifier des questions de nature scientifique.',	''),
(378,	4,	'Physique-Chimie',	'Pratiquer des démarches scientifiques',	'Interpréter des résultats expérimentaux, en tirer des conclusions et les communiquer en argumentant.',	''),
(379,	4,	'Physique-Chimie',	'Pratiquer des démarches scientifiques',	'Interpréter des résultats, conclure et communiquer',	'Interpréter des résultats, conclure et communiquer'),
(380,	4,	'Physique-Chimie',	'Pratiquer des démarches scientifiques',	'Mesurer des grandeurs physiques de manière directe ou indirecte.',	'Mesurer des grandeurs de manière directe / indirecte'),
(381,	4,	'Physique-Chimie',	'Pratiquer des démarches scientifiques',	'Proposer une ou des hypothèses pour répondre à une question scientifique. Concevoir une expérience pour la ou les tester.',	'Proposer une hypothèse / expérience, la tester et modéliser'),
(382,	4,	'Physique-Chimie',	'Pratiquer des langages',	'Lire et comprendre des documents scientifiques.',	'Lire et comprendre des documents scientifiques'),
(383,	4,	'Physique-Chimie',	'Pratiquer des langages',	'Passer d\'une forme de langage scientifique à une autre.',	'Passer d\'une forme de langage scientifique à une autre'),
(384,	4,	'Physique-Chimie',	'Pratiquer des langages',	'Rendre compte de la démarche avec un langage adapté',	'Rendre compte de la démarche avec un langage adapté'),
(385,	4,	'Physique-Chimie',	'Pratiquer des langages',	'S\'exprimer à l\'oral lors d\'un débat scientifique.',	''),
(386,	4,	'Physique-Chimie',	'Pratiquer des langages',	'Utiliser la langue française en cultivant précision, richesse de vocabulaire et syntaxe pour rendre compte des observations, expériences, hypothèses et conclusions.',	''),
(387,	4,	'Physique-Chimie',	'Se situer dans l\'espace et dans le temps',	'Expliquer, par l\'histoire des sciences et des techniques, comment les sciences évoluent et influencent la société.',	''),
(388,	4,	'Physique-Chimie',	'Se situer dans l\'espace et dans le temps',	'Identifier les différentes échelles de structuration de l\'Univers.',	''),
(389,	4,	'Physique-Chimie',	'Se situer dans l\'espace et dans le temps',	'Se situer dans l\'espace et dans le temps',	'Se situer dans l\'espace et dans le temps'),
(390,	4,	'Physique-Chimie',	'S\'approprier des outils et des méthodes',	'Effectuer des recherches bibliographiques.',	''),
(391,	4,	'Physique-Chimie',	'S\'approprier des outils et des méthodes',	'Planifier une tâche expérimentale, organiser son espace de travail, garder des traces des étapes suivies et des résultats obtenus.',	''),
(392,	4,	'Physique-Chimie',	'S\'approprier des outils et des méthodes',	'S\'approprier des outils et des méthodes',	'S\'approprier des outils et des méthodes'),
(393,	4,	'Physique-Chimie',	'S\'approprier des outils et des méthodes',	'Utiliser des outils numériques pour mutualiser des informations sur un sujet scientifique.',	''),
(394,	4,	'Sciences de la vie et de la Terre',	'Adopter un comportement éthique et responsable',	'Comprendre les responsabilités individuelle et collective en matière de préservation des ressources de la planète (biodiversité, ressources minérales et ressources énergétiques) et de santé.',	''),
(395,	4,	'Sciences de la vie et de la Terre',	'Adopter un comportement éthique et responsable',	'Distinguer ce qui relève d\'une croyance ou d\'une idée et ce qui constitue un savoir scientifique.',	''),
(396,	4,	'Sciences de la vie et de la Terre',	'Adopter un comportement éthique et responsable',	'Fonder ses choix de comportement responsable vis-à-vis de sa santé ou de l\'environnement sur des arguments scientifiques.',	''),
(397,	4,	'Sciences de la vie et de la Terre',	'Adopter un comportement éthique et responsable',	'Identifier les impacts (bénéfices et nuisances) des activités humaines sur l\'environnement à différentes échelles.',	''),
(398,	4,	'Sciences de la vie et de la Terre',	'Adopter un comportement éthique et responsable',	'Participer à l\'élaboration de règles de sécurité et les appliquer au laboratoire et sur le terrain.',	''),
(399,	4,	'Sciences de la vie et de la Terre',	'Competences à utiliser',	'Adopter un comportement éthique et responsable',	'Adopter un comportement éthique et responsable'),
(400,	4,	'Sciences de la vie et de la Terre',	'Competences à utiliser',	'Communiquer, interpréter ses résultats et conclure',	'Communiquer, interpréter ses résultats et conclure'),
(401,	4,	'Sciences de la vie et de la Terre',	'Competences à utiliser',	'Lire et exploiter des données présentées sous formes variées',	'Lire et exploiter des données présentées sous formes variées'),
(402,	4,	'Sciences de la vie et de la Terre',	'Competences à utiliser',	'Mettre en œuvre un protocole',	'Mettre en œuvre un protocole'),
(403,	4,	'Sciences de la vie et de la Terre',	'Competences à utiliser',	'Organiser son travail personnel',	'Organiser son travail personnel'),
(404,	4,	'Sciences de la vie et de la Terre',	'Competences à utiliser',	'Proposer une hypothèse ou expérience répondant à un problème',	'Proposer une hypothèse ou expérience répondant à un problème'),
(405,	4,	'Sciences de la vie et de la Terre',	'Competences à utiliser',	'Réfléchir, respecter et juger objectivement',	'Réfléchir, respecter et juger objectivement'),
(406,	4,	'Sciences de la vie et de la Terre',	'Competences à utiliser',	'Représenter des données sous une forme pertinente',	'Représenter des données sous une forme pertinente'),
(407,	4,	'Sciences de la vie et de la Terre',	'Competences à utiliser',	'Se situer dans l\'espace et dans le temps',	'Se situer dans l\'espace et dans le temps'),
(408,	4,	'Sciences de la vie et de la Terre',	'Competences à utiliser',	'Utiliser des outils numériques',	'Utiliser des outils numériques'),
(409,	4,	'Sciences de la vie et de la Terre',	'Competences à utiliser',	'Utiliser ses connaissances/capacités pour mener une démarche',	'Utiliser ses connaissances/capacités pour mener une démarche'),
(410,	4,	'Sciences de la vie et de la Terre',	'Concevoir, créer, réaliser',	'Concevoir et mettre en œuvre un protocole expérimental.',	''),
(411,	4,	'Sciences de la vie et de la Terre',	'Pratiquer des démarches scientifiques',	'Communiquer sur ses démarches, ses résultats et ses choix, en argumentant.',	''),
(412,	4,	'Sciences de la vie et de la Terre',	'Pratiquer des démarches scientifiques',	'Formuler une question ou un problème scientifique.',	''),
(413,	4,	'Sciences de la vie et de la Terre',	'Pratiquer des démarches scientifiques',	'Identifier et choisir des notions, des outils et des techniques, ou des modèles simples pour mettre en œuvre une démarche scientifique.',	''),
(414,	4,	'Sciences de la vie et de la Terre',	'Pratiquer des démarches scientifiques',	'Interpréter des résultats et en tirer des conclusions.',	''),
(415,	4,	'Sciences de la vie et de la Terre',	'Pratiquer des démarches scientifiques',	'Proposer une ou des hypothèses pour résoudre un problème ou une question. Concevoir des expériences pour la ou les tester.',	''),
(416,	4,	'Sciences de la vie et de la Terre',	'Pratiquer des démarches scientifiques',	'Utiliser des instruments d\'observation, de mesures et des techniques de préparation et de collecte.',	''),
(417,	4,	'Sciences de la vie et de la Terre',	'Pratiquer des langages',	'Lire et exploiter des données présentées sous différentes formes : tableaux, graphiques, diagrammes, dessins, conclusions de recherches, cartes heuristiques, etc.',	''),
(418,	4,	'Sciences de la vie et de la Terre',	'Pratiquer des langages',	'Représenter des données sous différentes formes, passer d\'une représentation à une autre et choisir celle qui est adaptée à la situation de travail.',	''),
(419,	4,	'Sciences de la vie et de la Terre',	'Se situer dans l\'espace et dans le temps',	'Appréhender différentes échelles de temps géologique et biologique (ex : histoire de la Terre ; apparition de la vie, évolution et extinction des espèces vivantes…).',	''),
(420,	4,	'Sciences de la vie et de la Terre',	'Se situer dans l\'espace et dans le temps',	'Appréhender différentes échelles spatiales d\'un même phénomène/d\'une même fonction (ex : nutrition : niveau de l\'organisme, niveau des organes et niveau cellulaire).',	''),
(421,	4,	'Sciences de la vie et de la Terre',	'Se situer dans l\'espace et dans le temps',	'Identifier par l\'histoire des sciences et des techniques comment se construit un savoir scientifique.',	''),
(422,	4,	'Sciences de la vie et de la Terre',	'Se situer dans l\'espace et dans le temps',	'Situer l\'espèce humaine dans l\'évolution des espèces.',	''),
(423,	4,	'Sciences de la vie et de la Terre',	'Utiliser des outils et mobiliser des méthodes pour apprendre',	'Apprendre à organiser son travail (par ex. pour mettre en œuvre un protocole expérimental).',	''),
(424,	4,	'Sciences de la vie et de la Terre',	'Utiliser des outils et mobiliser des méthodes pour apprendre',	'Identifier et choisir les outils et les techniques pour garder trace de ses recherches (à l\'oral et à l\'écrit).',	''),
(425,	4,	'Sciences de la vie et de la Terre',	'Utiliser des outils numériques',	'Conduire une recherche d\'informations sur internet pour répondre à une question ou un problème scientifique, en choisissant des mots-clés pertinents, et en évaluant la fiabilité des sources et la vali',	''),
(426,	4,	'Sciences de la vie et de la Terre',	'Utiliser des outils numériques',	'Utiliser des logiciels d\'acquisition de données, de simulation et des bases de données.',	''),
(427,	4,	'Technologie',	'Adopter un comportement éthique et responsable',	'Analyser le cycle de vie d\'un objet',	'Analyser le cycle de vie d\'un objet'),
(428,	4,	'Technologie',	'Adopter un comportement éthique et responsable',	'Analyser l\'impact environnemental d\'un objet et de ses constituants.',	'Analyser l\'impact environnemental d\'un objet'),
(429,	4,	'Technologie',	'Adopter un comportement éthique et responsable',	'Développer les bonnes pratiques de l\'usage des objets communicants.',	'Développer les bonnes pratiques  des objets communicants'),
(430,	4,	'Technologie',	'Concevoir, créer, réaliser',	'Associer des solutions techniques à des fonctions.',	'Associer des solutions techniques à des fonctions.'),
(431,	4,	'Technologie',	'Concevoir, créer, réaliser',	'Identifier le(s) matériau(x), les flux d\'énergie et d\'information dans le cadre d\'une production technique sur un objet et décrire les transformations qui s\'opèrent.',	'Identifier le(s) matériau(x), les flux d\'énergie'),
(432,	4,	'Technologie',	'Concevoir, créer, réaliser',	'Identifier un besoin et énoncer un problème technique, identifier les conditions, contraintes (normes et règlements) et ressources correspondantes.',	'Identifier un besoin, énoncer un problème technique'),
(433,	4,	'Technologie',	'Concevoir, créer, réaliser',	'Imaginer des solutions en réponse au besoin.',	'Imaginer des solutions en réponse au besoin.'),
(434,	4,	'Technologie',	'Concevoir, créer, réaliser',	'Imaginer, concevoir et programmer des applications informatiques nomades.',	'Concevoir des programmes pour des appareils nomades.'),
(435,	4,	'Technologie',	'Concevoir, créer, réaliser',	'Réaliser, de manière collaborative, le prototype de tout ou partie d\'un objet pour valider une solution.',	'Réaliser une partie du prototype pour valider une solution.'),
(436,	4,	'Technologie',	'Concevoir, créer, réaliser',	'S\'approprier un cahier des charges.',	'S\'approprier un cahier des charges.'),
(437,	4,	'Technologie',	'Mobiliser des outils numériques',	'Lire, utiliser et produire des représentations numériques d\'objets.',	'Utiliser, produire des représentations numériques d\'objets.'),
(438,	4,	'Technologie',	'Mobiliser des outils numériques',	'Modifier ou paramétrer le fonctionnement d\'un objet communicant.',	'aramétrer le fonctionnement d\'un objet communicant.'),
(439,	4,	'Technologie',	'Mobiliser des outils numériques',	'Organiser, structurer et stocker des ressources numériques.',	'Organiser, structurer et stocker des ressources numériques.'),
(440,	4,	'Technologie',	'Mobiliser des outils numériques',	'Piloter un système connecté localement ou à distance.',	'Piloter un système connecté localement ou à distance.'),
(441,	4,	'Technologie',	'Mobiliser des outils numériques',	'Simuler numériquement la structure et/ou le comportement d\'un objet.',	'Simuler numériquement la structure le comportement d\'objets'),
(442,	4,	'Technologie',	'Pratiquer des démarches scientifiques et technologiques',	'Imaginer, synthétiser, formaliser et respecter une procédure, un protocole.',	'Imaginer, synthétiser, formaliser et respecter un protocole'),
(443,	4,	'Technologie',	'Pratiquer des démarches scientifiques et technologiques',	'Mesurer des grandeurs de manière directe ou indirecte.',	'Mesurer des grandeurs de manière directe ou indirecte.'),
(444,	4,	'Technologie',	'Pratiquer des démarches scientifiques et technologiques',	'Participer à l\'organisation et au déroulement de projets.',	'Participer à l\'organisation et au déroulement de projets.'),
(445,	4,	'Technologie',	'Pratiquer des démarches scientifiques et technologiques',	'Rechercher des solutions techniques à un problème posé, expliciter ses choix et les communiquer en argumentant.',	'Rechercher des solutions techniques à un problème posé'),
(446,	4,	'Technologie',	'Pratiquer des langages',	'Appliquer les principes élémentaires de l\'algorithmique et du codage à la résolution d\'un problème simple.',	'Appliquer l\'algorithmique à la résolution d\'un problème'),
(447,	4,	'Technologie',	'Pratiquer des langages',	'Décrire, en utilisant les outils et langages de descriptions adaptés, la structure et le comportement des objets.',	'Décrire la structure et le comportement des objets.'),
(448,	4,	'Technologie',	'Se situer dans l\'espace et dans le temps',	'Regrouper des objets en familles et lignées.',	'Regrouper des objets en familles et lignées.'),
(449,	4,	'Technologie',	'Se situer dans l\'espace et dans le temps',	'Relier les évolutions technologiques aux inventions et innovations qui marquent des ruptures dans les solutions techniques.',	'Influence des évolutions technologiques'),
(450,	4,	'Technologie',	'S\'approprier des outils et des méthodes',	'Exprimer sa pensée à l\'aide d\'outils de description adaptés : croquis, schémas, graphes, diagrammes, tableaux (représentations non normées).',	'S\'exprimer à l\' aide de croquis, schémas, tableaux'),
(451,	4,	'Technologie',	'S\'approprier des outils et des méthodes',	'Présenter à l\'oral et à l\'aide de supports numériques multimédia des solutions techniques au moment des revues de projet.',	'Présenter numériquement à l\'oral des solutions'),
(452,	4,	'Technologie',	'S\'approprier des outils et des méthodes',	'Traduire, à l\'aide d\'outils de représentation numérique, des choix de solutions sous forme de croquis, de dessins ou de schémas.',	'Traduire avec des outils numériques des choix de solutions');

DROP TABLE IF EXISTS `eleves`;
CREATE TABLE `eleves` (
  `no_gep` varchar(50) NOT NULL COMMENT 'Ancien numero GEP, Numero national de l''eleve',
  `login` varchar(50) NOT NULL COMMENT 'Login de l''eleve, est conserve pour le login utilisateur',
  `nom` varchar(100) NOT NULL COMMENT 'Nom eleve',
  `prenom` varchar(100) NOT NULL COMMENT 'Prenom eleve',
  `sexe` varchar(1) NOT NULL COMMENT 'M ou F',
  `naissance` date NOT NULL COMMENT 'Date de naissance AAAA-MM-JJ',
  `lieu_naissance` varchar(50) NOT NULL DEFAULT '' COMMENT 'Code de Sconet',
  `elenoet` varchar(50) NOT NULL COMMENT 'Numero interne de l''eleve dans l''etablissement',
  `ereno` varchar(50) NOT NULL COMMENT 'Plus utilise',
  `ele_id` varchar(10) NOT NULL DEFAULT '' COMMENT 'cle utilise par Sconet dans ses fichiers xml',
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT 'Courriel de l''eleve',
  `tel_pers` varchar(255) NOT NULL DEFAULT '' COMMENT 'Telephone personnel de l''eleve',
  `tel_port` varchar(255) NOT NULL DEFAULT '' COMMENT 'Telephone portable de l''eleve',
  `tel_prof` varchar(255) NOT NULL DEFAULT '' COMMENT 'Telephone professionnel (?) de l''eleve',
  `id_eleve` int(11) NOT NULL AUTO_INCREMENT COMMENT 'cle primaire autoincremente',
  `date_entree` datetime DEFAULT NULL COMMENT 'Timestamp d''entrée de l''élève de l''établissement (début d''inscription)',
  `date_sortie` datetime DEFAULT NULL COMMENT 'Timestamp de sortie de l''élève de l''établissement (fin d''inscription)',
  `mef_code` varchar(50) NOT NULL DEFAULT '' COMMENT 'code mef de la formation de l''eleve',
  `adr_id` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_eleve`),
  KEY `eleves_FI_1` (`mef_code`),
  KEY `I_referenced_j_eleves_classes_FK_1_1` (`login`),
  KEY `I_referenced_responsables2_FK_1_2` (`ele_id`),
  KEY `I_referenced_archivage_ects_FK_1_3` (`no_gep`)
) TYPE=MyISAM COMMENT='Liste des eleves de l''etablissement';

INSERT INTO `eleves` (`no_gep`, `login`, `nom`, `prenom`, `sexe`, `naissance`, `lieu_naissance`, `elenoet`, `ereno`, `ele_id`, `email`, `tel_pers`, `tel_port`, `tel_prof`, `id_eleve`, `date_entree`, `date_sortie`, `mef_code`, `adr_id`) VALUES
('',	'H.Youssef',	'H.',	'Youssef',	'M',	'0101-01-01',	'',	'',	'',	'e000000001',	'',	'',	'',	'',	1,	NULL,	NULL,	'10310019110',	''),
('',	'S.Anass',	'S.',	'Anass',	'M',	'0101-01-01',	'',	'',	'',	'e000000002',	'',	'',	'',	'',	2,	NULL,	NULL,	'10310019110',	''),
('',	'I.Abdelhaki',	'I.',	'Abdelhakim',	'M',	'0101-01-01',	'',	'',	'',	'e000000003',	'',	'',	'',	'',	3,	NULL,	NULL,	'10310019110',	''),
('',	'A.Mustapha',	'A.',	'Mustapha',	'M',	'0101-01-01',	'',	'',	'',	'e000000004',	'',	'',	'',	'',	4,	NULL,	NULL,	'10310019110',	''),
('',	'M.Abdelhaki',	'M.',	'Abdelhamid',	'M',	'0101-01-01',	'',	'',	'',	'e000000005',	'',	'',	'',	'',	5,	NULL,	NULL,	'10310019110',	''),
('',	'A.Outman',	'E.',	'Outman',	'M',	'0101-01-01',	'',	'',	'',	'e000000006',	'',	'',	'',	'',	6,	NULL,	NULL,	'10310019110',	''),
('',	'K.Abdelhami',	'K.',	'Abdelhamid',	'M',	'0101-01-01',	'',	'',	'',	'e000000007',	'',	'',	'',	'',	7,	NULL,	NULL,	'10310019110',	''),
('',	'K.Mustapha',	'K.',	'Mustapha',	'M',	'0101-01-01',	'',	'',	'',	'e000000008',	'',	'',	'',	'',	8,	NULL,	NULL,	'10210001110',	''),
('',	'M.Outman',	'M.',	'Outman',	'M',	'0101-01-01',	'',	'',	'',	'e000000009',	'',	'',	'',	'',	9,	NULL,	NULL,	'10210001110',	''),
('',	'S.Youssef',	'S.',	'Youssef',	'M',	'0101-01-01',	'',	'',	'',	'e000000010',	'',	'',	'',	'',	10,	NULL,	NULL,	'10210001110',	'');

DROP TABLE IF EXISTS `eleves_groupes_settings`;
CREATE TABLE `eleves_groupes_settings` (
  `login` varchar(50) NOT NULL,
  `id_groupe` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` varchar(50) NOT NULL,
  PRIMARY KEY (`id_groupe`,`login`,`name`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `ele_adr`;
CREATE TABLE `ele_adr` (
  `adr_id` varchar(10) NOT NULL,
  `adr1` varchar(100) NOT NULL,
  `adr2` varchar(100) NOT NULL,
  `adr3` varchar(100) NOT NULL,
  `adr4` varchar(100) NOT NULL,
  `cp` varchar(6) NOT NULL,
  `pays` varchar(50) NOT NULL,
  `commune` varchar(50) NOT NULL,
  PRIMARY KEY (`adr_id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `engagements`;
CREATE TABLE `engagements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `type` varchar(20) NOT NULL,
  `conseil_de_classe` varchar(10) NOT NULL,
  `ConcerneEleve` varchar(10) NOT NULL,
  `ConcerneResponsable` varchar(10) NOT NULL,
  `SaisieScol` varchar(10) NOT NULL,
  `SaisieCpe` varchar(10) NOT NULL,
  `SaisiePP` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

INSERT INTO `engagements` (`id`, `code`, `nom`, `description`, `type`, `conseil_de_classe`, `ConcerneEleve`, `ConcerneResponsable`, `SaisieScol`, `SaisieCpe`, `SaisiePP`) VALUES
(1,	'C',	'Délégué de classe',	'Délégué de classe',	'id_classe',	'yes',	'yes',	'',	'yes',	'',	''),
(2,	'V',	'Délégué du conseil de la vie lycéenne',	'Délégué du conseil de la vie lycéenne',	'',	'',	'yes',	'',	'yes',	'',	''),
(3,	'A',	'Membre du conseil d\'administration',	'Membre du conseil d\'administration',	'',	'',	'yes',	'',	'yes',	'',	''),
(4,	'E',	'Membre du comité d\'éducation à la santé et à la citoyenneté',	'Membre du comité d\'éducation à la santé et à la citoyenneté',	'',	'',	'',	'',	'yes',	'',	''),
(5,	'S',	'Membre de l\'association sportive',	'Membre de l\'association sportive',	'',	'',	'yes',	'',	'yes',	'',	'');

DROP TABLE IF EXISTS `engagements_droit_saisie`;
CREATE TABLE `engagements_droit_saisie` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_engagement` int(11) NOT NULL,
  `login` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `engagements_pages`;
CREATE TABLE `engagements_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant unique',
  `page` varchar(255) NOT NULL DEFAULT '' COMMENT 'Page ou module',
  `id_type` int(11) NOT NULL COMMENT 'identifiant du type d engagement',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `engagements_user`;
CREATE TABLE `engagements_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_engagement` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `id_type` varchar(20) NOT NULL,
  `valeur` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `etablissements`;
CREATE TABLE `etablissements` (
  `id` char(8) NOT NULL DEFAULT '',
  `nom` char(50) NOT NULL DEFAULT '',
  `niveau` char(50) NOT NULL DEFAULT '',
  `type` char(50) NOT NULL DEFAULT '',
  `cp` varchar(10) NOT NULL DEFAULT '0',
  `ville` char(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

INSERT INTO `etablissements` (`id`, `nom`, `niveau`, `type`, `cp`, `ville`) VALUES
('999',	'étranger',	'aucun',	'aucun',	'999',	''),
('IESI',	'Institut ESI',	'universite',	'prive',	'75000',	'Paris');

DROP TABLE IF EXISTS `etiquettes_formats`;
CREATE TABLE `etiquettes_formats` (
  `id_etiquette_format` int(11) NOT NULL AUTO_INCREMENT,
  `nom_etiquette_format` varchar(150) NOT NULL,
  `xcote_etiquette_format` float NOT NULL,
  `ycote_etiquette_format` float NOT NULL,
  `espacementx_etiquette_format` float NOT NULL,
  `espacementy_etiquette_format` float NOT NULL,
  `largeur_etiquette_format` float NOT NULL,
  `hauteur_etiquette_format` float NOT NULL,
  `nbl_etiquette_format` tinyint(4) NOT NULL,
  `nbh_etiquette_format` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_etiquette_format`)
) TYPE=MyISAM;

INSERT INTO `etiquettes_formats` (`id_etiquette_format`, `nom_etiquette_format`, `xcote_etiquette_format`, `ycote_etiquette_format`, `espacementx_etiquette_format`, `espacementy_etiquette_format`, `largeur_etiquette_format`, `hauteur_etiquette_format`, `nbl_etiquette_format`, `nbh_etiquette_format`) VALUES
(1,	'Avery - A4 - 63,5 x 33,9 mm',	2,	2,	5,	5,	63.5,	33,	3,	8);

DROP TABLE IF EXISTS `ex_classes`;
CREATE TABLE `ex_classes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_exam` int(11) unsigned NOT NULL,
  `id_classe` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `ex_examens`;
CREATE TABLE `ex_examens` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `intitule` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `etat` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `ex_groupes`;
CREATE TABLE `ex_groupes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_exam` int(11) unsigned NOT NULL,
  `matiere` varchar(50) NOT NULL,
  `id_groupe` int(11) unsigned NOT NULL,
  `type` varchar(255) NOT NULL,
  `id_dev` int(11) NOT NULL DEFAULT '0',
  `valeur` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `ex_matieres`;
CREATE TABLE `ex_matieres` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_exam` int(11) unsigned NOT NULL,
  `matiere` varchar(255) NOT NULL,
  `coef` decimal(3,1) NOT NULL DEFAULT '1.0',
  `bonus` char(1) NOT NULL DEFAULT 'n',
  `ordre` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `ex_notes`;
CREATE TABLE `ex_notes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_ex_grp` int(11) unsigned NOT NULL,
  `login` varchar(50) NOT NULL DEFAULT '',
  `note` float(10,1) NOT NULL DEFAULT '0.0',
  `statut` varchar(4) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `gc_affichages`;
CREATE TABLE `gc_affichages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_aff` int(11) unsigned NOT NULL,
  `id_req` int(11) unsigned NOT NULL,
  `projet` varchar(255) NOT NULL,
  `nom_requete` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `valeur` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `gc_divisions`;
CREATE TABLE `gc_divisions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `projet` varchar(255) NOT NULL,
  `id_classe` smallint(6) unsigned NOT NULL,
  `classe` varchar(100) NOT NULL DEFAULT '',
  `statut` enum('actuelle','future','red','arriv') NOT NULL DEFAULT 'future',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `gc_eleves_options`;
CREATE TABLE `gc_eleves_options` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `profil` varchar(10) NOT NULL DEFAULT 'RAS',
  `moy` varchar(255) NOT NULL,
  `nb_absences` varchar(255) NOT NULL,
  `non_justifie` varchar(255) NOT NULL,
  `nb_retards` varchar(255) NOT NULL,
  `projet` varchar(255) NOT NULL,
  `id_classe_actuelle` varchar(255) NOT NULL,
  `classe_future` varchar(255) NOT NULL,
  `liste_opt` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `gc_eleves_profils`;
CREATE TABLE `gc_eleves_profils` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `profil` varchar(10) NOT NULL DEFAULT 'RAS',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `gc_ele_arriv_red`;
CREATE TABLE `gc_ele_arriv_red` (
  `login` varchar(50) NOT NULL,
  `statut` enum('Arriv','Red') NOT NULL,
  `projet` varchar(255) NOT NULL,
  PRIMARY KEY (`login`,`projet`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `gc_noms_affichages`;
CREATE TABLE `gc_noms_affichages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_aff` int(11) NOT NULL,
  `projet` varchar(255) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `description` tinytext NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `gc_options`;
CREATE TABLE `gc_options` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `projet` varchar(255) NOT NULL,
  `opt` varchar(255) NOT NULL,
  `type` enum('lv1','lv2','lv3','autre') NOT NULL,
  `obligatoire` enum('o','n') NOT NULL,
  `exclusive` smallint(6) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `gc_options_classes`;
CREATE TABLE `gc_options_classes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `projet` varchar(255) NOT NULL,
  `opt_exclue` varchar(255) NOT NULL,
  `classe_future` varchar(255) NOT NULL,
  `commentaire` text NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `gc_projets`;
CREATE TABLE `gc_projets` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `projet` varchar(255) NOT NULL,
  `commentaire` text NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `groupes`;
CREATE TABLE `groupes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `recalcul_rang` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `id_name` (`id`,`name`)
) TYPE=MyISAM;

INSERT INTO `groupes` (`id`, `name`, `description`, `recalcul_rang`) VALUES
(1,	'Tajwid',	'Tajwîd, les règles de lecture du Coran',	'n'),
(2,	'Sira',	'La Sira, La vie du Prophète de l\\\'Islam',	'n'),
(3,	'Scienc_hadith',	'Les sciences du hadith',	'n'),
(4,	'Scienc_cor',	'Les sciences du Coran',	'n'),
(5,	'histoire',	'L’histoire musulmane',	'n'),
(6,	'Etud_hadith',	'L’étude des ahadith (recueils)',	'n'),
(7,	'Cour_pensee',	'Courant de pensée et mouvement réformiste',	'n'),
(8,	'aquida',	'Al ‘aquida, ilm al kalâm, La Foi, le Credo, les fondements de la religion',	'n'),
(9,	'Apprentis_cor',	'Le suivi d’apprentissage du Coran',	'n'),
(10,	'Alfiraq',	'Al firaq wal madhahib,  écoles de droit et de pensée, schismes et disciplines intellectuelles en Islam',	'n');

DROP TABLE IF EXISTS `groupes_param`;
CREATE TABLE `groupes_param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_groupe` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `value` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `groupes_types`;
CREATE TABLE `groupes_types` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nom_court` varchar(255) NOT NULL DEFAULT '',
  `nom_complet` varchar(255) NOT NULL DEFAULT '',
  `nom_complet_pluriel` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

INSERT INTO `groupes_types` (`id`, `nom_court`, `nom_complet`, `nom_complet_pluriel`) VALUES
(1,	'AP',	'Accompagnement personnalisé',	'Accompagnements personnalisés'),
(2,	'EPI',	'Enseignement pratique interdisciplinaire',	'Enseignements pratiques interdisciplinaires'),
(3,	'Parcours',	'Parcours éducatif',	'Parcours éducatifs'),
(4,	'local',	'Enseignement local',	'Enseignements locaux');

DROP TABLE IF EXISTS `grp_groupes`;
CREATE TABLE `grp_groupes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_court` varchar(20) NOT NULL,
  `nom_complet` varchar(100) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `grp_groupes_admin`;
CREATE TABLE `grp_groupes_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_grp_groupe` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `grp_groupes_groupes`;
CREATE TABLE `grp_groupes_groupes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_grp_groupe` int(11) NOT NULL,
  `id_groupe` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `horaires_etablissement`;
CREATE TABLE `horaires_etablissement` (
  `id_horaire_etablissement` int(11) NOT NULL AUTO_INCREMENT,
  `date_horaire_etablissement` date NOT NULL,
  `jour_horaire_etablissement` varchar(15) NOT NULL,
  `ouverture_horaire_etablissement` time NOT NULL,
  `fermeture_horaire_etablissement` time NOT NULL,
  `pause_horaire_etablissement` time NOT NULL,
  `ouvert_horaire_etablissement` tinyint(4) NOT NULL,
  `num_jour_table_horaires_etablissement` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_horaire_etablissement`)
) TYPE=MyISAM;

INSERT INTO `horaires_etablissement` (`id_horaire_etablissement`, `date_horaire_etablissement`, `jour_horaire_etablissement`, `ouverture_horaire_etablissement`, `fermeture_horaire_etablissement`, `pause_horaire_etablissement`, `ouvert_horaire_etablissement`, `num_jour_table_horaires_etablissement`) VALUES
(1,	'0000-00-00',	'lundi',	'08:00:00',	'17:30:00',	'00:45:00',	1,	0),
(2,	'0000-00-00',	'mardi',	'08:00:00',	'17:30:00',	'00:45:00',	1,	1),
(3,	'0000-00-00',	'mercredi',	'08:00:00',	'12:00:00',	'00:00:00',	1,	2),
(4,	'0000-00-00',	'jeudi',	'08:00:00',	'17:30:00',	'00:45:00',	1,	3),
(5,	'0000-00-00',	'vendredi',	'08:00:00',	'17:30:00',	'00:45:00',	1,	4);

DROP TABLE IF EXISTS `infos_actions`;
CREATE TABLE `infos_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_titre` (`id`,`titre`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `infos_actions_destinataires`;
CREATE TABLE `infos_actions_destinataires` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_info` int(11) NOT NULL,
  `nature` enum('statut','individu') DEFAULT 'individu',
  `valeur` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `id_info` (`id_info`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `inscription_items`;
CREATE TABLE `inscription_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` varchar(10) NOT NULL DEFAULT '',
  `heure` varchar(20) NOT NULL DEFAULT '',
  `description` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `inscription_j_login_items`;
CREATE TABLE `inscription_j_login_items` (
  `login` varchar(50) NOT NULL DEFAULT '',
  `id` int(11) NOT NULL DEFAULT '0'
) TYPE=MyISAM;


DROP TABLE IF EXISTS `j_aidcateg_super_gestionnaires`;
CREATE TABLE `j_aidcateg_super_gestionnaires` (
  `indice_aid` int(11) NOT NULL,
  `id_utilisateur` varchar(50) NOT NULL
) TYPE=MyISAM;


DROP TABLE IF EXISTS `j_aidcateg_utilisateurs`;
CREATE TABLE `j_aidcateg_utilisateurs` (
  `indice_aid` int(11) NOT NULL,
  `id_utilisateur` varchar(50) NOT NULL
) TYPE=MyISAM;


DROP TABLE IF EXISTS `j_aid_eleves`;
CREATE TABLE `j_aid_eleves` (
  `id_aid` varchar(100) NOT NULL DEFAULT '',
  `login` varchar(60) NOT NULL DEFAULT '',
  `indice_aid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_aid`,`login`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `j_aid_eleves_resp`;
CREATE TABLE `j_aid_eleves_resp` (
  `id_aid` varchar(100) NOT NULL DEFAULT '',
  `login` varchar(60) NOT NULL DEFAULT '',
  `indice_aid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_aid`,`login`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `j_aid_utilisateurs`;
CREATE TABLE `j_aid_utilisateurs` (
  `id_aid` varchar(100) NOT NULL DEFAULT '',
  `id_utilisateur` varchar(50) NOT NULL DEFAULT '',
  `indice_aid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_aid`,`id_utilisateur`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `j_aid_utilisateurs_gest`;
CREATE TABLE `j_aid_utilisateurs_gest` (
  `id_aid` varchar(100) NOT NULL DEFAULT '',
  `id_utilisateur` varchar(50) NOT NULL DEFAULT '',
  `indice_aid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_aid`,`id_utilisateur`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `j_eleves_classes`;
CREATE TABLE `j_eleves_classes` (
  `login` varchar(50) NOT NULL DEFAULT '',
  `id_classe` int(11) NOT NULL DEFAULT '0',
  `periode` int(11) NOT NULL DEFAULT '0',
  `rang` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`login`,`id_classe`,`periode`),
  KEY `id_classe` (`id_classe`),
  KEY `login_periode` (`login`,`periode`)
) TYPE=MyISAM;

INSERT INTO `j_eleves_classes` (`login`, `id_classe`, `periode`, `rang`) VALUES
('A.Mustapha',	1,	1,	5),
('A.Mustapha',	1,	2,	0),
('A.Mustapha',	1,	3,	0),
('A.Outman',	1,	1,	1),
('A.Outman',	1,	2,	0),
('A.Outman',	1,	3,	0),
('H.Youssef',	1,	1,	2),
('H.Youssef',	1,	2,	0),
('H.Youssef',	1,	3,	0),
('I.Abdelhaki',	1,	1,	7),
('I.Abdelhaki',	1,	2,	0),
('I.Abdelhaki',	1,	3,	0),
('K.Abdelhami',	1,	1,	5),
('K.Abdelhami',	1,	2,	0),
('K.Abdelhami',	1,	3,	0),
('M.Abdelhaki',	1,	1,	4),
('M.Abdelhaki',	1,	2,	0),
('M.Abdelhaki',	1,	3,	0),
('S.Anass',	1,	1,	3),
('S.Anass',	1,	2,	0),
('S.Anass',	1,	3,	0);

DROP TABLE IF EXISTS `j_eleves_cpe`;
CREATE TABLE `j_eleves_cpe` (
  `e_login` varchar(50) NOT NULL DEFAULT '',
  `cpe_login` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`e_login`,`cpe_login`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `j_eleves_etablissements`;
CREATE TABLE `j_eleves_etablissements` (
  `id_eleve` varchar(50) NOT NULL DEFAULT '',
  `id_etablissement` varchar(8) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_eleve`,`id_etablissement`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `j_eleves_groupes`;
CREATE TABLE `j_eleves_groupes` (
  `login` varchar(50) NOT NULL DEFAULT '',
  `id_groupe` int(11) NOT NULL DEFAULT '0',
  `periode` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_groupe`,`login`,`periode`),
  KEY `login` (`login`)
) TYPE=MyISAM;

INSERT INTO `j_eleves_groupes` (`login`, `id_groupe`, `periode`) VALUES
('A.Mustapha',	1,	1),
('A.Mustapha',	1,	2),
('A.Mustapha',	1,	3),
('A.Outman',	1,	1),
('A.Outman',	1,	2),
('A.Outman',	1,	3),
('H.Youssef',	1,	1),
('H.Youssef',	1,	2),
('H.Youssef',	1,	3),
('I.Abdelhaki',	1,	1),
('I.Abdelhaki',	1,	2),
('I.Abdelhaki',	1,	3),
('K.Abdelhami',	1,	1),
('K.Abdelhami',	1,	2),
('K.Abdelhami',	1,	3),
('M.Abdelhaki',	1,	1),
('M.Abdelhaki',	1,	2),
('M.Abdelhaki',	1,	3),
('S.Anass',	1,	1),
('S.Anass',	1,	2),
('S.Anass',	1,	3),
('A.Mustapha',	2,	1),
('A.Mustapha',	2,	2),
('A.Mustapha',	2,	3),
('A.Outman',	2,	1),
('A.Outman',	2,	2),
('A.Outman',	2,	3),
('H.Youssef',	2,	1),
('H.Youssef',	2,	2),
('H.Youssef',	2,	3),
('I.Abdelhaki',	2,	1),
('I.Abdelhaki',	2,	2),
('I.Abdelhaki',	2,	3),
('K.Abdelhami',	2,	1),
('K.Abdelhami',	2,	2),
('K.Abdelhami',	2,	3),
('M.Abdelhaki',	2,	1),
('M.Abdelhaki',	2,	2),
('M.Abdelhaki',	2,	3),
('S.Anass',	2,	1),
('S.Anass',	2,	2),
('S.Anass',	2,	3),
('A.Mustapha',	3,	1),
('A.Mustapha',	3,	2),
('A.Mustapha',	3,	3),
('A.Outman',	3,	1),
('A.Outman',	3,	2),
('A.Outman',	3,	3),
('H.Youssef',	3,	1),
('H.Youssef',	3,	2),
('H.Youssef',	3,	3),
('I.Abdelhaki',	3,	1),
('I.Abdelhaki',	3,	2),
('I.Abdelhaki',	3,	3),
('K.Abdelhami',	3,	1),
('K.Abdelhami',	3,	2),
('K.Abdelhami',	3,	3),
('M.Abdelhaki',	3,	1),
('M.Abdelhaki',	3,	2),
('M.Abdelhaki',	3,	3),
('S.Anass',	3,	1),
('S.Anass',	3,	2),
('S.Anass',	3,	3),
('A.Mustapha',	4,	1),
('A.Mustapha',	4,	2),
('A.Mustapha',	4,	3),
('A.Outman',	4,	1),
('A.Outman',	4,	2),
('A.Outman',	4,	3),
('H.Youssef',	4,	1),
('H.Youssef',	4,	2),
('H.Youssef',	4,	3),
('I.Abdelhaki',	4,	1),
('I.Abdelhaki',	4,	2),
('I.Abdelhaki',	4,	3),
('K.Abdelhami',	4,	1),
('K.Abdelhami',	4,	2),
('K.Abdelhami',	4,	3),
('M.Abdelhaki',	4,	1),
('M.Abdelhaki',	4,	2),
('M.Abdelhaki',	4,	3),
('S.Anass',	4,	1),
('S.Anass',	4,	2),
('S.Anass',	4,	3),
('A.Mustapha',	5,	1),
('A.Mustapha',	5,	2),
('A.Mustapha',	5,	3),
('A.Outman',	5,	1),
('A.Outman',	5,	2),
('A.Outman',	5,	3),
('H.Youssef',	5,	1),
('H.Youssef',	5,	2),
('H.Youssef',	5,	3),
('I.Abdelhaki',	5,	1),
('I.Abdelhaki',	5,	2),
('I.Abdelhaki',	5,	3),
('K.Abdelhami',	5,	1),
('K.Abdelhami',	5,	2),
('K.Abdelhami',	5,	3),
('M.Abdelhaki',	5,	1),
('M.Abdelhaki',	5,	2),
('M.Abdelhaki',	5,	3),
('S.Anass',	5,	1),
('S.Anass',	5,	2),
('S.Anass',	5,	3),
('A.Mustapha',	6,	1),
('A.Mustapha',	6,	2),
('A.Mustapha',	6,	3),
('A.Outman',	6,	1),
('A.Outman',	6,	2),
('A.Outman',	6,	3),
('H.Youssef',	6,	1),
('H.Youssef',	6,	2),
('H.Youssef',	6,	3),
('I.Abdelhaki',	6,	1),
('I.Abdelhaki',	6,	2),
('I.Abdelhaki',	6,	3),
('K.Abdelhami',	6,	1),
('K.Abdelhami',	6,	2),
('K.Abdelhami',	6,	3),
('M.Abdelhaki',	6,	1),
('M.Abdelhaki',	6,	2),
('M.Abdelhaki',	6,	3),
('S.Anass',	6,	1),
('S.Anass',	6,	2),
('S.Anass',	6,	3),
('A.Mustapha',	7,	1),
('A.Mustapha',	7,	2),
('A.Mustapha',	7,	3),
('A.Outman',	7,	1),
('A.Outman',	7,	2),
('A.Outman',	7,	3),
('H.Youssef',	7,	1),
('H.Youssef',	7,	2),
('H.Youssef',	7,	3),
('I.Abdelhaki',	7,	1),
('I.Abdelhaki',	7,	2),
('I.Abdelhaki',	7,	3),
('K.Abdelhami',	7,	1),
('K.Abdelhami',	7,	2),
('K.Abdelhami',	7,	3),
('M.Abdelhaki',	7,	1),
('M.Abdelhaki',	7,	2),
('M.Abdelhaki',	7,	3),
('S.Anass',	7,	1),
('S.Anass',	7,	2),
('S.Anass',	7,	3),
('A.Mustapha',	8,	1),
('A.Mustapha',	8,	2),
('A.Mustapha',	8,	3),
('A.Outman',	8,	1),
('A.Outman',	8,	2),
('A.Outman',	8,	3),
('H.Youssef',	8,	1),
('H.Youssef',	8,	2),
('H.Youssef',	8,	3),
('I.Abdelhaki',	8,	1),
('I.Abdelhaki',	8,	2),
('I.Abdelhaki',	8,	3),
('K.Abdelhami',	8,	1),
('K.Abdelhami',	8,	2),
('K.Abdelhami',	8,	3),
('M.Abdelhaki',	8,	1),
('M.Abdelhaki',	8,	2),
('M.Abdelhaki',	8,	3),
('S.Anass',	8,	1),
('S.Anass',	8,	2),
('S.Anass',	8,	3),
('A.Mustapha',	9,	1),
('A.Mustapha',	9,	2),
('A.Mustapha',	9,	3),
('A.Outman',	9,	1),
('A.Outman',	9,	2),
('A.Outman',	9,	3),
('H.Youssef',	9,	1),
('H.Youssef',	9,	2),
('H.Youssef',	9,	3),
('I.Abdelhaki',	9,	1),
('I.Abdelhaki',	9,	2),
('I.Abdelhaki',	9,	3),
('K.Abdelhami',	9,	1),
('K.Abdelhami',	9,	2),
('K.Abdelhami',	9,	3),
('M.Abdelhaki',	9,	1),
('M.Abdelhaki',	9,	2),
('M.Abdelhaki',	9,	3),
('S.Anass',	9,	1),
('S.Anass',	9,	2),
('S.Anass',	9,	3),
('A.Mustapha',	10,	1),
('A.Mustapha',	10,	2),
('A.Mustapha',	10,	3),
('A.Outman',	10,	1),
('A.Outman',	10,	2),
('A.Outman',	10,	3),
('H.Youssef',	10,	1),
('H.Youssef',	10,	2),
('H.Youssef',	10,	3),
('I.Abdelhaki',	10,	1),
('I.Abdelhaki',	10,	2),
('I.Abdelhaki',	10,	3),
('K.Abdelhami',	10,	1),
('K.Abdelhami',	10,	2),
('K.Abdelhami',	10,	3),
('M.Abdelhaki',	10,	1),
('M.Abdelhaki',	10,	2),
('M.Abdelhaki',	10,	3),
('S.Anass',	10,	1),
('S.Anass',	10,	2),
('S.Anass',	10,	3);

DROP TABLE IF EXISTS `j_eleves_groupes_delestage`;
CREATE TABLE `j_eleves_groupes_delestage` (
  `login` varchar(50) DEFAULT NULL,
  `id_groupe` int(11) DEFAULT NULL,
  `periode` int(11) DEFAULT NULL
) TYPE=MyISAM;


DROP TABLE IF EXISTS `j_eleves_groupes_delestage2`;
CREATE TABLE `j_eleves_groupes_delestage2` (
  `login` varchar(50) DEFAULT NULL,
  `id_groupe` int(11) DEFAULT NULL,
  `periode` int(11) DEFAULT NULL
) TYPE=MyISAM;


DROP TABLE IF EXISTS `j_eleves_professeurs`;
CREATE TABLE `j_eleves_professeurs` (
  `login` varchar(50) NOT NULL DEFAULT '',
  `professeur` varchar(50) NOT NULL DEFAULT '',
  `id_classe` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`login`,`professeur`,`id_classe`),
  KEY `classe_professeur` (`id_classe`,`professeur`),
  KEY `professeur_classe` (`professeur`,`id_classe`)
) TYPE=MyISAM;

INSERT INTO `j_eleves_professeurs` (`login`, `professeur`, `id_classe`) VALUES
('A.Mustapha',	'prof_a',	1),
('A.Outman',	'prof_a',	1),
('H.Youssef',	'prof_a',	1),
('I.Abdelhaki',	'prof_b',	1),
('K.Abdelhami',	'prof_b',	1),
('M.Abdelhaki',	'prof_c',	1),
('S.Anass',	'prof_c',	1);

DROP TABLE IF EXISTS `j_eleves_regime`;
CREATE TABLE `j_eleves_regime` (
  `login` varchar(50) NOT NULL DEFAULT '',
  `doublant` char(1) NOT NULL DEFAULT '',
  `regime` varchar(5) NOT NULL DEFAULT '',
  PRIMARY KEY (`login`)
) TYPE=MyISAM;

INSERT INTO `j_eleves_regime` (`login`, `doublant`, `regime`) VALUES
('H.Youssef',	'-',	'd/p'),
('S.Anass',	'-',	'd/p'),
('I.Abdelhaki',	'-',	'd/p'),
('A.Mustapha',	'-',	'd/p'),
('M.Abdelhaki',	'-',	'd/p'),
('A.Outman',	'-',	'd/p'),
('K.Abdelhami',	'-',	'd/p'),
('K.Mustapha',	'-',	'd/p'),
('M.Outman',	'-',	'd/p'),
('S.Youssef',	'-',	'd/p');

DROP TABLE IF EXISTS `j_groupes_aid`;
CREATE TABLE `j_groupes_aid` (
  `id_groupe` int(11) NOT NULL DEFAULT '0',
  `id_aid` int(11) NOT NULL DEFAULT '0',
  `indice_aid` int(11) NOT NULL DEFAULT '0',
  `etat` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_groupe`,`id_aid`),
  KEY `id_groupe_id_aid` (`id_groupe`,`id_aid`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `j_groupes_classes`;
CREATE TABLE `j_groupes_classes` (
  `id_groupe` int(11) NOT NULL DEFAULT '0',
  `id_classe` int(11) NOT NULL DEFAULT '0',
  `priorite` smallint(6) NOT NULL,
  `coef` decimal(3,1) NOT NULL,
  `categorie_id` int(11) NOT NULL DEFAULT '1',
  `saisie_ects` tinyint(1) NOT NULL DEFAULT '0',
  `valeur_ects` int(11) DEFAULT NULL,
  `mode_moy` enum('-','sup10','bonus') NOT NULL DEFAULT '-',
  `apb_langue_vivante` varchar(3) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_groupe`,`id_classe`),
  KEY `id_classe_coef` (`id_classe`,`coef`),
  KEY `saisie_ects_id_groupe` (`saisie_ects`,`id_groupe`)
) TYPE=MyISAM;

INSERT INTO `j_groupes_classes` (`id_groupe`, `id_classe`, `priorite`, `coef`, `categorie_id`, `saisie_ects`, `valeur_ects`, `mode_moy`, `apb_langue_vivante`) VALUES
(1,	1,	13,	2.0,	2,	0,	0,	'-',	''),
(2,	1,	16,	1.0,	4,	0,	0,	'-',	''),
(3,	1,	20,	2.0,	3,	0,	0,	'-',	''),
(4,	1,	11,	3.0,	2,	0,	0,	'-',	''),
(5,	1,	18,	1.0,	4,	0,	0,	'-',	''),
(6,	1,	19,	2.0,	3,	0,	0,	'-',	''),
(7,	1,	17,	1.0,	4,	0,	0,	'-',	''),
(8,	1,	14,	2.0,	5,	0,	0,	'-',	''),
(9,	1,	12,	3.0,	2,	0,	0,	'-',	''),
(10,	1,	15,	1.0,	5,	0,	0,	'-',	'');

DROP TABLE IF EXISTS `j_groupes_eleves_modalites`;
CREATE TABLE `j_groupes_eleves_modalites` (
  `id_groupe` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `code_modalite_elect` varchar(6) NOT NULL,
  UNIQUE KEY `id_groupe_login_modalite` (`id_groupe`,`login`,`code_modalite_elect`),
  KEY `login` (`login`),
  KEY `id_groupe` (`id_groupe`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `j_groupes_enseignements_complement`;
CREATE TABLE `j_groupes_enseignements_complement` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_groupe` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `j_groupes_lvr`;
CREATE TABLE `j_groupes_lvr` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_groupe` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `j_groupes_matieres`;
CREATE TABLE `j_groupes_matieres` (
  `id_groupe` int(11) NOT NULL DEFAULT '0',
  `id_matiere` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_groupe`,`id_matiere`)
) TYPE=MyISAM;

INSERT INTO `j_groupes_matieres` (`id_groupe`, `id_matiere`) VALUES
(1,	'Tajwid'),
(2,	'Sira'),
(3,	'Scienc_hadith'),
(4,	'Scienc_cor'),
(5,	'histoire'),
(6,	'Etud_hadith'),
(7,	'Cour_pensee'),
(8,	'aquida'),
(9,	'Apprentis_cor'),
(10,	'Alfiraq');

DROP TABLE IF EXISTS `j_groupes_professeurs`;
CREATE TABLE `j_groupes_professeurs` (
  `id_groupe` int(11) NOT NULL DEFAULT '0',
  `login` varchar(50) NOT NULL DEFAULT '',
  `ordre_prof` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_groupe`,`login`),
  KEY `login` (`login`)
) TYPE=MyISAM;

INSERT INTO `j_groupes_professeurs` (`id_groupe`, `login`, `ordre_prof`) VALUES
(1,	'prof_a',	0),
(2,	'prof_b',	0),
(3,	'prof_c',	0),
(4,	'prof_c',	0),
(5,	'prof_b',	0),
(6,	'prof_c',	0),
(7,	'prof_a',	0),
(8,	'prof_a',	0),
(9,	'prof_b',	0),
(10,	'prof_a',	0);

DROP TABLE IF EXISTS `j_groupes_types`;
CREATE TABLE `j_groupes_types` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_groupe` int(11) NOT NULL,
  `id_type` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `j_groupes_visibilite`;
CREATE TABLE `j_groupes_visibilite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_groupe` int(11) NOT NULL,
  `domaine` varchar(255) NOT NULL DEFAULT '',
  `visible` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `id_groupe_domaine` (`id_groupe`,`domaine`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `j_matieres_categories_classes`;
CREATE TABLE `j_matieres_categories_classes` (
  `categorie_id` int(11) NOT NULL DEFAULT '0',
  `classe_id` int(11) NOT NULL DEFAULT '0',
  `priority` smallint(6) NOT NULL DEFAULT '0',
  `affiche_moyenne` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`categorie_id`,`classe_id`)
) TYPE=MyISAM;

INSERT INTO `j_matieres_categories_classes` (`categorie_id`, `classe_id`, `priority`, `affiche_moyenne`) VALUES
(1,	1,	5,	0),
(2,	1,	1,	1),
(3,	1,	2,	1),
(4,	1,	3,	1),
(5,	1,	4,	1);

DROP TABLE IF EXISTS `j_mentions_classes`;
CREATE TABLE `j_mentions_classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_mention` int(11) NOT NULL,
  `id_classe` int(11) NOT NULL,
  `ordre` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `j_mep_eleve`;
CREATE TABLE `j_mep_eleve` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'identifiant unique',
  `idEP` int(11) DEFAULT NULL COMMENT 'identifiant unique de l''élément de programme',
  `idEleve` varchar(50) DEFAULT NULL COMMENT 'login élève',
  `idGroupe` int(11) DEFAULT NULL COMMENT 'identifiant du groupe',
  `annee` varchar(4) DEFAULT NULL COMMENT 'année sur 4 caractères',
  `periode` int(11) DEFAULT NULL COMMENT 'période sur 4 caractères',
  `date_insert` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `jointMapProf` (`idEP`,`idEleve`,`annee`,`periode`)
) TYPE=MyISAM COMMENT='Jointure éléments de programme travaillé ↔ groupe enseignement';


DROP TABLE IF EXISTS `j_mep_groupe`;
CREATE TABLE `j_mep_groupe` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'identifiant unique',
  `idEP` int(11) DEFAULT NULL COMMENT 'identifiant unique de l''élément de programme',
  `idGroupe` int(11) DEFAULT NULL COMMENT 'identifiant du groupe',
  `annee` varchar(4) DEFAULT NULL COMMENT 'année sur 4 caractères',
  `periode` int(11) DEFAULT NULL COMMENT 'période sur 4 caractères',
  PRIMARY KEY (`id`),
  UNIQUE KEY `jointGroupe` (`idEP`,`idGroupe`,`annee`,`periode`)
) TYPE=MyISAM COMMENT='Jointure éléments de programme travaillé ↔ groupe enseignement';


DROP TABLE IF EXISTS `j_mep_mat`;
CREATE TABLE `j_mep_mat` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'identifiant unique',
  `idMat` varchar(50) DEFAULT NULL COMMENT 'identifiant unique de la matière',
  `idEP` int(11) DEFAULT NULL COMMENT 'identifiant unique de l''élément de programme',
  PRIMARY KEY (`id`),
  UNIQUE KEY `jointMapMat` (`idMat`,`idEP`)
) TYPE=MyISAM COMMENT='Jointure éléments de programme travaillé ↔ matière';


DROP TABLE IF EXISTS `j_mep_niveau`;
CREATE TABLE `j_mep_niveau` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'identifiant unique',
  `idEP` int(11) DEFAULT NULL COMMENT 'identifiant unique de l''élément de programme',
  `idNiveau` varchar(50) DEFAULT NULL COMMENT 'niveau auquel se réfère l''élément',
  PRIMARY KEY (`id`),
  UNIQUE KEY `niveau` (`idEP`,`idNiveau`)
) TYPE=MyISAM COMMENT='Jointure éléments de programme travaillé ↔ Niveau';


DROP TABLE IF EXISTS `j_mep_prof`;
CREATE TABLE `j_mep_prof` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'identifiant unique',
  `idEP` int(11) DEFAULT NULL COMMENT 'identifiant unique de l''élément de programme',
  `id_prof` varchar(50) DEFAULT NULL COMMENT 'identifiant unique du professeur',
  PRIMARY KEY (`id`),
  UNIQUE KEY `jointMapProf` (`id_prof`,`idEP`)
) TYPE=MyISAM COMMENT='Jointure éléments de programme travaillé ↔ enseignant';


DROP TABLE IF EXISTS `j_modalite_accompagnement_eleve`;
CREATE TABLE `j_modalite_accompagnement_eleve` (
  `code` varchar(10) NOT NULL DEFAULT '',
  `id_eleve` int(11) NOT NULL DEFAULT '0',
  `periode` int(11) NOT NULL DEFAULT '0',
  `commentaire` text,
  PRIMARY KEY (`code`,`id_eleve`,`periode`),
  KEY `code_id_eleve_periode` (`code`,`id_eleve`,`periode`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `j_notifications_resp_pers`;
CREATE TABLE `j_notifications_resp_pers` (
  `a_notification_id` int(12) NOT NULL COMMENT 'cle etrangere de la notification',
  `pers_id` varchar(10) NOT NULL COMMENT 'cle etrangere des personnes',
  PRIMARY KEY (`a_notification_id`,`pers_id`),
  KEY `j_notifications_resp_pers_FI_2` (`pers_id`)
) TYPE=MyISAM COMMENT='Table de jointure entre la notification et les personnes dont on va mettre le nom dans le message.';


DROP TABLE IF EXISTS `j_professeurs_matieres`;
CREATE TABLE `j_professeurs_matieres` (
  `id_professeur` varchar(50) NOT NULL DEFAULT '',
  `id_matiere` varchar(50) NOT NULL DEFAULT '',
  `ordre_matieres` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_professeur`,`id_matiere`)
) TYPE=MyISAM;

INSERT INTO `j_professeurs_matieres` (`id_professeur`, `id_matiere`, `ordre_matieres`) VALUES
('prof_a',	'aquida',	3),
('prof_c',	'Scienc_hadith',	1),
('prof_c',	'Scienc_cor',	2),
('prof_c',	'Etud_hadith',	3),
('prof_b',	'Sira',	1),
('prof_b',	'histoire',	2),
('prof_a',	'Tajwid',	1),
('prof_a',	'Cour_pensee',	2),
('prof_b',	'Apprentis_cor',	3),
('prof_a',	'Alfiraq',	4);

DROP TABLE IF EXISTS `j_scol_classes`;
CREATE TABLE `j_scol_classes` (
  `login` varchar(50) NOT NULL,
  `id_classe` int(11) NOT NULL
) TYPE=MyISAM;

INSERT INTO `j_scol_classes` (`login`, `id_classe`) VALUES
('scolarite',	1);

DROP TABLE IF EXISTS `j_signalement`;
CREATE TABLE `j_signalement` (
  `id_groupe` int(11) NOT NULL DEFAULT '0',
  `login` varchar(50) NOT NULL DEFAULT '',
  `periode` int(11) NOT NULL DEFAULT '0',
  `nature` varchar(50) NOT NULL DEFAULT '',
  `valeur` varchar(50) NOT NULL DEFAULT '',
  `declarant` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_groupe`,`login`,`periode`,`nature`),
  KEY `login` (`login`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `j_traitements_saisies`;
CREATE TABLE `j_traitements_saisies` (
  `a_saisie_id` int(12) NOT NULL COMMENT 'cle etrangere de l''absence saisie',
  `a_traitement_id` int(12) NOT NULL COMMENT 'cle etrangere du traitement de ces absences',
  PRIMARY KEY (`a_saisie_id`,`a_traitement_id`),
  KEY `j_traitements_saisies_FI_2` (`a_traitement_id`)
) TYPE=MyISAM COMMENT='Table de jointure entre la saisie et le traitement des absences';


DROP TABLE IF EXISTS `ldap_bx`;
CREATE TABLE `ldap_bx` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login_u` varchar(200) NOT NULL,
  `nom_u` varchar(200) NOT NULL,
  `prenom_u` varchar(200) NOT NULL,
  `statut_u` varchar(50) NOT NULL,
  `identite_u` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `lettres_cadres`;
CREATE TABLE `lettres_cadres` (
  `id_lettre_cadre` int(11) NOT NULL AUTO_INCREMENT,
  `nom_lettre_cadre` varchar(150) NOT NULL,
  `x_lettre_cadre` float NOT NULL,
  `y_lettre_cadre` float NOT NULL,
  `l_lettre_cadre` float NOT NULL,
  `h_lettre_cadre` float NOT NULL,
  `texte_lettre_cadre` text NOT NULL,
  `encadre_lettre_cadre` tinyint(4) NOT NULL,
  `couleurdefond_lettre_cadre` varchar(11) NOT NULL,
  PRIMARY KEY (`id_lettre_cadre`)
) TYPE=MyISAM;

INSERT INTO `lettres_cadres` (`id_lettre_cadre`, `nom_lettre_cadre`, `x_lettre_cadre`, `y_lettre_cadre`, `l_lettre_cadre`, `h_lettre_cadre`, `texte_lettre_cadre`, `encadre_lettre_cadre`, `couleurdefond_lettre_cadre`) VALUES
(1,	'adresse responsable',	100,	40,	100,	5,	'A l\'attention de\r\n<civilitee_court_responsable> <nom_responsable> <prenom_responsable>\r\n<adresse_responsable>\r\n<cp_responsable> <commune_responsable>\r\n',	0,	'||'),
(2,	'adresse etablissement',	0,	0,	0,	0,	'',	0,	''),
(3,	'datation',	0,	0,	0,	0,	'',	0,	''),
(4,	'corp avertissement',	10,	70,	0,	5,	'<u>Objet: </u> <g>Avertissement</g>\r\n\r\n\r\n<nom_civilitee_long>,\r\n\r\nJe me vois dans l\'obligation de donner un <b>AVERTISSEMENT</b>\r\n\r\nà <g><nom_eleve> <prenom_eleve></g> élève de la classe <g><classe_eleve></g>.\r\n\r\n\r\npour la raison suivante : <g><sujet_eleve></g>\r\n\r\n<remarque_eleve>\r\n\r\n\r\n\r\nComme le prévoit le règlement intérieur de l\'établissement, il pourra être sanctionné à partir de ce jour.\r\nSanction(s) possible(s) :\r\n\r\n\r\n\r\n\r\nJe vous remercie de me renvoyer cet exemplaire après l\'avoir daté et signé.\r\nVeuillez agréer <nom_civilitee_long> <nom_responsable> l\'assurance de ma considération distinguée.\r\n\r\n\r\n\r\nDate et signatures des parents :	',	0,	'||'),
(5,	'corp blame',	10,	70,	0,	5,	'<u>Objet</u>: <g>Blâme</g>\r\n\r\n\r\n<nom_civilitee_long>\r\n\r\nJe me vois dans l\'obligation de donner un BLAME \r\n\r\nà <g><nom_eleve> <prenom_eleve></g> élève de la classe <g><classe_eleve></g>.\r\n\r\nDemandé par: <g><courrier_demande_par></g>\r\n\r\npour la raison suivante: <g><raison></g>\r\n\r\n<remarque>\r\n\r\nJe vous remercie de me renvoyer cet exemplaire après l\'avoir daté et signé.\r\nVeuillez agréer <g><nom_civilitee_long> <nom_responsable></g> l\'assurance de ma considération distinguée.\r\n\r\n<u>Date et signatures des parents:</u>\r\n\r\n\r\n\r\n\r\n\r\nNous demandons un entretien avec la personne ayant demandé la sanction OUI / NON.\r\n(La prise de rendez-vous est à votre initiative)\r\n',	0,	'||'),
(6,	'corp convocation parents',	10,	70,	0,	5,	'<u>Objet</u>: <g>Convocation des parents</g>\r\n\r\n\r\n<nom_civilitee_long>,\r\n\r\nVous êtes prié de prendre contact avec le Conseiller Principal d\'Education dans les plus brefs délais, au sujet de <g><nom_eleve> <prenom_eleve></g> inscrit en classe de <g><classe_eleve></g>.\r\n\r\npour le motif suivant:\r\n\r\n<remarque>\r\n\r\n\r\n\r\nSans nouvelle de votre part avant le ........................................., je serai dans l\'obligation de procéder à la descolarisation de l\'élève, avec les conséquences qui en résulteront, jusqu\'à votre rencontre.\r\n\r\n\r\nVeuillez agréer <g><nom_civilitee_long> <nom_responsable></g> l\'assurance de ma considération distinguée.',	0,	'||'),
(7,	'corp exclusion',	10,	70,	0,	5,	'<u>Objet: </u> <g>Sanction - Exclusion de l\'établissement</g>\r\n\r\n\r\n<nom_civilitee_long>,\r\n\r\nPar la présente, je tiens à vous signaler que <nom_eleve>\r\n\r\ninscrit en classe de  <classe_eleve>\r\n\r\n\r\ns\'étant rendu coupable des faits suivants : \r\n\r\n<remarque>\r\n\r\n\r\n\r\nEst exclu de l\'établissement,\r\nà compter du: <b><date_debut></b> à <b><heure_debut></b>,\r\njusqu\'au: <b><date_fin></b> à <b><heure_fin></b>.\r\n\r\n\r\nIl devra se présenter, au bureau de la Vie Scolaire \r\n\r\nle ....................................... à ....................................... ACCOMPAGNE DE SES PARENTS.\r\n\r\n\r\n\r\n\r\nVeuillez agréer &lt;TYPEPARENT&gt; &lt;NOMPARENT&gt; l\'assurance de ma considération distinguée.',	0,	'||'),
(8,	'corp demande justificatif absence',	10,	70,	0,	5,	'<u>Objet: </u> <g>Demande de justificatif d\'absence</g>\n\n<civilitee_long_responsable>,\n\nJ\'ai le regret de vous informer que <b><nom_eleve> <prenom_eleve></b>, élève en classe de <b><classe_eleve></b> n\'a pas assisté au(x) cours:\n\n<liste>\n\nJe vous prie de bien vouloir me faire connaître le motif de son absence.\n\nPour permettre un contrôle efficace des présences, toute absence d\'un élève doit être justifiée par sa famille, le jour même soit par téléphone, soit par écrit, soit par fax.\n\nAvant de regagner les cours, l\'élève absent devra se présenter au bureau du Conseiller Principal d\'Education muni de son carnet de correspondance avec un justificatif signé des parents.\n\nVeuillez agréer <civilitee_long_responsable> <nom_responsable>, l\'assurance de ma considération distinguée.\n                                               \nCPE\n<civilitee_long_cpe> <nom_cpe> <prenom_cpe>\n\nPrière de renvoyer, par retour du courrier, le présent avis signé des parents :\n\nMotif de l\'absence : ________________________________________________________________________________\n\n____________________________________________________________________________________________________\n\n____________________________________________________________________________________________________\n\n____________________________________________________________________________________________________\n\nDate et signatures des parents :  \n',	0,	'||'),
(10,	'signature',	100,	180,	0,	5,	'<b><courrier_signe_par_fonction></b>,\r\n<courrier_signe_par>\r\n',	0,	'||');

DROP TABLE IF EXISTS `lettres_suivis`;
CREATE TABLE `lettres_suivis` (
  `id_lettre_suivi` int(11) NOT NULL AUTO_INCREMENT,
  `lettresuitealettren_lettre_suivi` int(11) NOT NULL,
  `quirecois_lettre_suivi` varchar(50) NOT NULL,
  `partde_lettre_suivi` varchar(200) NOT NULL,
  `partdenum_lettre_suivi` text NOT NULL,
  `quiemet_lettre_suivi` varchar(150) NOT NULL,
  `emis_date_lettre_suivi` date NOT NULL,
  `emis_heure_lettre_suivi` time NOT NULL,
  `quienvoi_lettre_suivi` varchar(150) NOT NULL,
  `envoye_date_lettre_suivi` date NOT NULL,
  `envoye_heure_lettre_suivi` time NOT NULL,
  `type_lettre_suivi` int(11) NOT NULL,
  `quireception_lettre_suivi` varchar(150) NOT NULL,
  `reponse_date_lettre_suivi` date NOT NULL,
  `reponse_remarque_lettre_suivi` varchar(250) NOT NULL,
  `statu_lettre_suivi` varchar(20) NOT NULL,
  PRIMARY KEY (`id_lettre_suivi`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `lettres_tcs`;
CREATE TABLE `lettres_tcs` (
  `id_lettre_tc` int(11) NOT NULL AUTO_INCREMENT,
  `type_lettre_tc` int(11) NOT NULL,
  `cadre_lettre_tc` int(11) NOT NULL,
  `x_lettre_tc` float NOT NULL,
  `y_lettre_tc` float NOT NULL,
  `l_lettre_tc` float NOT NULL,
  `h_lettre_tc` float NOT NULL,
  `encadre_lettre_tc` int(1) NOT NULL,
  PRIMARY KEY (`id_lettre_tc`)
) TYPE=MyISAM;

INSERT INTO `lettres_tcs` (`id_lettre_tc`, `type_lettre_tc`, `cadre_lettre_tc`, `x_lettre_tc`, `y_lettre_tc`, `l_lettre_tc`, `h_lettre_tc`, `encadre_lettre_tc`) VALUES
(1,	3,	0,	0,	0,	0,	0,	0),
(2,	3,	0,	0,	0,	0,	0,	0),
(3,	3,	0,	0,	0,	0,	0,	0),
(4,	3,	0,	0,	0,	0,	0,	0),
(5,	3,	0,	0,	0,	0,	0,	0),
(6,	3,	0,	0,	0,	0,	0,	0),
(7,	3,	0,	0,	0,	0,	0,	0),
(8,	3,	0,	0,	0,	0,	0,	0),
(9,	3,	0,	0,	0,	0,	0,	0),
(10,	3,	0,	0,	0,	0,	0,	0),
(11,	3,	0,	0,	0,	0,	0,	0),
(12,	3,	0,	0,	0,	0,	0,	0),
(13,	3,	0,	0,	0,	0,	0,	0),
(14,	3,	0,	0,	0,	0,	0,	0),
(15,	3,	0,	0,	0,	0,	0,	0),
(16,	3,	0,	0,	0,	0,	0,	0),
(17,	3,	0,	0,	0,	0,	0,	0),
(18,	3,	0,	0,	0,	0,	0,	0),
(19,	3,	0,	0,	0,	0,	0,	0),
(20,	3,	0,	0,	0,	0,	0,	0),
(21,	3,	0,	0,	0,	0,	0,	0),
(22,	3,	0,	0,	0,	0,	0,	0),
(23,	3,	0,	0,	0,	0,	0,	0),
(24,	3,	0,	0,	0,	0,	0,	0),
(25,	3,	0,	0,	0,	0,	0,	0),
(26,	3,	0,	0,	0,	0,	0,	0),
(27,	3,	0,	0,	0,	0,	0,	0),
(28,	3,	0,	0,	0,	0,	0,	0),
(29,	3,	0,	0,	0,	0,	0,	0),
(30,	3,	0,	0,	0,	0,	0,	0),
(31,	3,	0,	0,	0,	0,	0,	0),
(32,	3,	0,	0,	0,	0,	0,	0),
(33,	3,	0,	0,	0,	0,	0,	0),
(34,	3,	0,	0,	0,	0,	0,	0),
(35,	3,	0,	0,	0,	0,	0,	0),
(36,	3,	0,	0,	0,	0,	0,	0),
(37,	3,	0,	0,	0,	0,	0,	0),
(38,	3,	0,	0,	0,	0,	0,	0),
(39,	3,	0,	0,	0,	0,	0,	0),
(40,	3,	0,	0,	0,	0,	0,	0),
(41,	3,	0,	0,	0,	0,	0,	0),
(42,	3,	0,	0,	0,	0,	0,	0),
(43,	3,	0,	0,	0,	0,	0,	0),
(44,	3,	0,	0,	0,	0,	0,	0),
(45,	3,	0,	0,	0,	0,	0,	0),
(46,	3,	0,	0,	0,	0,	0,	0),
(47,	3,	0,	0,	0,	0,	0,	0),
(48,	3,	0,	0,	0,	0,	0,	0),
(49,	3,	0,	0,	0,	0,	0,	0),
(50,	3,	0,	0,	0,	0,	0,	0),
(51,	3,	0,	0,	0,	0,	0,	0),
(52,	3,	0,	0,	0,	0,	0,	0),
(53,	3,	0,	0,	0,	0,	0,	0),
(56,	3,	1,	100,	40,	100,	5,	0),
(57,	3,	4,	10,	70,	190,	15,	0),
(58,	1,	0,	0,	0,	0,	0,	0),
(59,	1,	0,	0,	0,	0,	0,	0),
(60,	1,	0,	0,	0,	0,	0,	0),
(61,	1,	0,	0,	0,	0,	0,	0),
(62,	1,	0,	0,	0,	0,	0,	0),
(63,	1,	0,	0,	0,	0,	0,	0),
(64,	1,	0,	0,	0,	0,	0,	0),
(65,	1,	1,	100,	40,	100,	5,	0),
(66,	1,	5,	10,	70,	190,	15,	0),
(68,	2,	1,	100,	40,	100,	5,	0),
(69,	2,	6,	10,	70,	190,	10,	0),
(70,	4,	1,	100,	40,	100,	5,	0),
(71,	4,	7,	10,	70,	190,	15,	0),
(72,	6,	0,	0,	0,	0,	0,	0),
(73,	6,	0,	0,	0,	0,	0,	0),
(74,	6,	0,	0,	0,	0,	0,	0),
(75,	6,	0,	0,	0,	0,	0,	0),
(76,	6,	0,	0,	0,	0,	0,	0),
(77,	6,	0,	0,	0,	0,	0,	0),
(78,	6,	0,	0,	0,	0,	0,	0),
(79,	6,	0,	0,	0,	0,	0,	0),
(80,	6,	0,	0,	0,	0,	0,	0),
(81,	6,	0,	0,	0,	0,	0,	0),
(82,	6,	0,	0,	0,	0,	0,	0),
(83,	6,	0,	0,	0,	0,	0,	0),
(84,	6,	0,	0,	0,	0,	0,	0),
(85,	6,	0,	0,	0,	0,	0,	0),
(86,	6,	0,	0,	0,	0,	0,	0),
(87,	6,	0,	0,	0,	0,	0,	0),
(88,	6,	0,	0,	0,	0,	0,	0),
(89,	6,	1,	100,	40,	100,	5,	0),
(90,	6,	8,	10,	70,	190,	20,	0),
(91,	7,	0,	0,	0,	0,	0,	0),
(92,	7,	0,	0,	0,	0,	0,	0),
(93,	7,	0,	0,	0,	0,	0,	0),
(94,	7,	0,	0,	0,	0,	0,	0),
(95,	7,	0,	0,	0,	0,	0,	0),
(96,	7,	0,	0,	0,	0,	0,	0),
(97,	7,	0,	0,	0,	0,	0,	0),
(98,	7,	0,	0,	0,	0,	0,	0),
(99,	7,	0,	0,	0,	0,	0,	0),
(100,	7,	0,	0,	0,	0,	0,	0),
(101,	7,	0,	0,	0,	0,	0,	0),
(102,	7,	0,	0,	0,	0,	0,	0),
(103,	7,	0,	0,	0,	0,	0,	0),
(104,	7,	0,	0,	0,	0,	0,	0),
(105,	7,	0,	0,	0,	0,	0,	0),
(106,	7,	0,	0,	0,	0,	0,	0),
(107,	7,	0,	0,	0,	0,	0,	0),
(108,	7,	0,	0,	0,	0,	0,	0),
(109,	7,	0,	0,	0,	0,	0,	0),
(110,	7,	0,	0,	0,	0,	0,	0),
(111,	1,	0,	0,	0,	0,	0,	0),
(112,	1,	0,	0,	0,	0,	0,	0),
(113,	1,	0,	0,	0,	0,	0,	0),
(114,	1,	0,	0,	0,	0,	0,	0),
(115,	1,	0,	0,	0,	0,	0,	0),
(116,	1,	0,	0,	0,	0,	0,	0),
(117,	1,	0,	0,	0,	0,	0,	0),
(118,	1,	0,	0,	0,	0,	0,	0),
(119,	1,	0,	0,	0,	0,	0,	0),
(120,	1,	0,	0,	0,	0,	0,	0),
(121,	1,	0,	0,	0,	0,	0,	0),
(122,	1,	0,	0,	0,	0,	0,	0),
(123,	1,	0,	0,	0,	0,	0,	0),
(124,	1,	0,	0,	0,	0,	0,	0),
(125,	1,	0,	0,	0,	0,	0,	0),
(126,	1,	0,	0,	0,	0,	0,	0),
(127,	1,	0,	0,	0,	0,	0,	0),
(128,	1,	0,	0,	0,	0,	0,	0),
(129,	1,	0,	0,	0,	0,	0,	0),
(130,	1,	0,	0,	0,	0,	0,	0),
(131,	2,	10,	100,	180,	190,	5,	0),
(132,	6,	0,	0,	0,	0,	0,	0),
(133,	6,	0,	0,	0,	0,	0,	0),
(134,	6,	0,	0,	0,	0,	0,	0),
(135,	6,	0,	0,	0,	0,	0,	0),
(136,	6,	0,	0,	0,	0,	0,	0),
(137,	6,	0,	0,	0,	0,	0,	0),
(138,	6,	0,	0,	0,	0,	0,	0),
(139,	6,	0,	0,	0,	0,	0,	0),
(140,	6,	0,	0,	0,	0,	0,	0),
(141,	6,	0,	0,	0,	0,	0,	0),
(142,	6,	0,	0,	0,	0,	0,	0),
(143,	6,	0,	0,	0,	0,	0,	0),
(144,	6,	0,	0,	0,	0,	0,	0),
(145,	6,	0,	0,	0,	0,	0,	0),
(146,	6,	0,	0,	0,	0,	0,	0),
(147,	6,	0,	0,	0,	0,	0,	0),
(148,	6,	0,	0,	0,	0,	0,	0),
(149,	6,	0,	0,	0,	0,	0,	0),
(150,	6,	0,	0,	0,	0,	0,	0),
(151,	6,	0,	0,	0,	0,	0,	0),
(152,	6,	0,	0,	0,	0,	0,	0),
(153,	6,	0,	0,	0,	0,	0,	0),
(154,	6,	0,	0,	0,	0,	0,	0),
(155,	6,	0,	0,	0,	0,	0,	0),
(156,	6,	0,	0,	0,	0,	0,	0),
(157,	6,	0,	0,	0,	0,	0,	0),
(158,	6,	0,	0,	0,	0,	0,	0),
(159,	6,	0,	0,	0,	0,	0,	0),
(160,	6,	0,	0,	0,	0,	0,	0),
(161,	6,	0,	0,	0,	0,	0,	0),
(162,	6,	0,	0,	0,	0,	0,	0),
(163,	6,	0,	0,	0,	0,	0,	0),
(164,	6,	0,	0,	0,	0,	0,	0),
(165,	6,	0,	0,	0,	0,	0,	0),
(166,	6,	0,	0,	0,	0,	0,	0),
(167,	6,	0,	0,	0,	0,	0,	0),
(168,	6,	0,	0,	0,	0,	0,	0),
(169,	6,	0,	0,	0,	0,	0,	0),
(170,	6,	0,	0,	0,	0,	0,	0),
(171,	6,	0,	0,	0,	0,	0,	0),
(172,	6,	0,	0,	0,	0,	0,	0),
(173,	6,	0,	0,	0,	0,	0,	0),
(174,	6,	0,	0,	0,	0,	0,	0),
(175,	6,	0,	0,	0,	0,	0,	0),
(176,	6,	0,	0,	0,	0,	0,	0),
(177,	6,	0,	0,	0,	0,	0,	0),
(178,	6,	0,	0,	0,	0,	0,	0),
(179,	6,	0,	0,	0,	0,	0,	0),
(180,	6,	0,	0,	0,	0,	0,	0),
(181,	6,	0,	0,	0,	0,	0,	0),
(182,	6,	0,	0,	0,	0,	0,	0),
(183,	6,	0,	0,	0,	0,	0,	0),
(184,	6,	0,	0,	0,	0,	0,	0),
(185,	6,	0,	0,	0,	0,	0,	0),
(186,	6,	0,	0,	0,	0,	0,	0),
(187,	6,	0,	0,	0,	0,	0,	0),
(188,	6,	0,	0,	0,	0,	0,	0),
(189,	6,	0,	0,	0,	0,	0,	0),
(190,	6,	0,	0,	0,	0,	0,	0),
(191,	6,	0,	0,	0,	0,	0,	0),
(192,	6,	0,	0,	0,	0,	0,	0),
(193,	6,	0,	0,	0,	0,	0,	0),
(194,	6,	0,	0,	0,	0,	0,	0),
(195,	6,	0,	0,	0,	0,	0,	0),
(196,	6,	0,	0,	0,	0,	0,	0),
(197,	6,	0,	0,	0,	0,	0,	0),
(198,	6,	0,	0,	0,	0,	0,	0),
(199,	6,	0,	0,	0,	0,	0,	0),
(200,	6,	0,	0,	0,	0,	0,	0);

DROP TABLE IF EXISTS `lettres_types`;
CREATE TABLE `lettres_types` (
  `id_lettre_type` int(11) NOT NULL AUTO_INCREMENT,
  `titre_lettre_type` varchar(250) NOT NULL,
  `categorie_lettre_type` varchar(250) NOT NULL,
  `reponse_lettre_type` varchar(3) NOT NULL,
  PRIMARY KEY (`id_lettre_type`)
) TYPE=MyISAM;

INSERT INTO `lettres_types` (`id_lettre_type`, `titre_lettre_type`, `categorie_lettre_type`, `reponse_lettre_type`) VALUES
(1,	'blame',	'sanction',	''),
(2,	'convocation des parents',	'suivi',	''),
(3,	'avertissement',	'sanction',	''),
(4,	'exclusion',	'sanction',	''),
(5,	'certificat de scolarité',	'suivi',	''),
(6,	'demande de justificatif d\'absence',	'suivi',	'oui'),
(7,	'demande de justificatif de retard',	'suivi',	''),
(8,	'rapport d\'incident',	'sanction',	''),
(9,	'regime de sortie',	'suivi',	''),
(10,	'retenue',	'sanction',	'');

DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
  `LOGIN` varchar(50) NOT NULL DEFAULT '',
  `START` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `SESSION_ID` varchar(255) NOT NULL DEFAULT '',
  `REMOTE_ADDR` varchar(16) NOT NULL DEFAULT '',
  `USER_AGENT` varchar(255) NOT NULL DEFAULT '',
  `REFERER` varchar(64) NOT NULL DEFAULT '',
  `AUTOCLOSE` enum('0','1','2','3','4') NOT NULL DEFAULT '0',
  `END` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`SESSION_ID`,`START`),
  KEY `start_time` (`START`),
  KEY `end_time` (`END`),
  KEY `login_session_start` (`LOGIN`,`SESSION_ID`,`START`)
) TYPE=MyISAM;

INSERT INTO `log` (`LOGIN`, `START`, `SESSION_ID`, `REMOTE_ADDR`, `USER_AGENT`, `REFERER`, `AUTOCLOSE`, `END`) VALUES
('ADMIN',	'2020-04-30 14:46:49',	'vko3p5tgvjafh92q1pgfnj62b2',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'',	'1',	'2020-04-30 15:11:19'),
('ighilabdelhakim@hotmail.com',	'2020-04-30 15:11:25',	'',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'http://localhost/gepi/login.php',	'4',	'2020-04-30 15:11:25'),
('ADMIN',	'2020-04-30 15:11:40',	'hmt10kt24ffr5lvkj9gp08kp2h',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'',	'3',	'2020-04-30 16:47:59'),
('admin',	'2020-04-30 19:51:58',	'',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'http://localhost/gepi/login.php',	'4',	'2020-04-30 19:51:58'),
('ADMIN',	'2020-04-30 19:52:08',	'9ue4g1r9varoqsqgvi863vf9cl',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'',	'3',	'2020-04-30 21:39:29'),
('scolarite',	'2020-05-01 20:36:44',	'u5o3qmr0nkpp9anf37ckro0ta6',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'',	'0',	'2020-05-01 20:49:18'),
('prof_a',	'2020-05-01 20:49:25',	'83pjra39c1ieebc1p074rreu0d',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'',	'3',	'2020-05-01 23:14:39'),
('prof_a',	'2020-05-02 00:22:25',	'onjd5u83e4k7gcll1f8ph24l2r',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'',	'0',	'2020-05-02 00:37:20'),
('prof_b',	'2020-05-02 00:37:26',	'rou68pv7iinspd9rg669la85hj',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'',	'0',	'2020-05-02 00:42:06'),
('prof_c',	'2020-05-02 00:42:13',	'eihrpkp1aebb8sm0kuiuvlelus',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'',	'0',	'2020-05-02 00:47:19'),
('scolarite',	'2020-05-02 00:47:29',	'ds80060ul7do9ncsjbn7743e61',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'',	'0',	'2020-05-02 00:58:12'),
('prof_a',	'2020-05-02 00:58:17',	'o09kolcftecf0565pd8ddgsuvc',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'',	'0',	'2020-05-02 00:58:58'),
('scolarite',	'2020-05-02 00:59:04',	'8iq7aroqm6vsatjd0gutv56s21',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'',	'0',	'2020-05-02 00:59:46'),
('prof_a',	'2020-05-02 00:59:51',	'f9f3e1eqssio1pnnarae4q4r2j',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'',	'0',	'2020-05-02 01:01:26'),
('prof_b',	'2020-05-02 01:01:32',	'incjqaulr53hbfg2g3ng7q882m',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'',	'0',	'2020-05-02 01:09:08'),
('prof_c',	'2020-05-02 01:09:20',	'tku34f4np5eic0pmmuoblemjl2',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'',	'0',	'2020-05-02 01:13:36'),
('scolarite',	'2020-05-02 01:13:49',	'r828l618krroo6p6j1e6iu6oaf',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'',	'0',	'2020-05-02 01:42:43'),
('ADMIN',	'2020-05-02 01:42:48',	'1emhoccmhu4v05q6e3c6ihm85b',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'',	'0',	'2020-05-02 01:43:42'),
('scolarite',	'2020-05-02 01:43:49',	'aelboddf1880c83t26072uoppv',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'',	'0',	'2020-05-02 01:44:03'),
('ADMIN',	'2020-05-02 01:44:07',	'k6o5urotg4fjsvpdhor8t251r4',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'',	'0',	'2020-05-02 01:48:36'),
('scolarite',	'2020-05-02 01:48:42',	'qtlkt75251abrpjklp10t40cab',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'',	'0',	'2020-05-02 01:52:37'),
('ADMIN',	'2020-05-02 01:52:43',	'mac4dijtehhvqjsfouvehd4dpf',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'',	'0',	'2020-05-02 02:04:20'),
('scolarite',	'2020-05-02 02:04:27',	'bae3r4dtcft0et4jgkf825i7fh',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'',	'0',	'2020-05-02 02:21:40'),
('ADMIN',	'2020-05-02 02:21:45',	'lvorietrj4jmo73np46dac0uve',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'',	'0',	'2020-05-02 02:26:47'),
('scolarite',	'2020-05-02 02:27:23',	'8kqrk2dkesg1ql3cof1q9cinf1',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'',	'0',	'2020-05-02 02:37:57'),
('prof_a',	'2020-05-02 02:38:03',	'v2njhmpm2te1juab5th58ffgp3',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'',	'0',	'2020-05-02 02:50:23'),
('ADMIN',	'2020-05-02 02:50:28',	'f8pvlfoe9t5reic815psgdrpd1',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'',	'0',	'2020-05-02 02:53:43'),
('scolarite',	'2020-05-02 02:53:56',	'ttq72um7p7djh6frpv4golr8fk',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'',	'0',	'2020-05-02 03:05:15'),
('ADMIN',	'2020-05-02 03:05:21',	'p1crjvp8rurminv2bv1qjpt3gn',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'',	'0',	'2020-05-02 03:06:50'),
('A.Mustapha',	'2020-05-02 03:07:17',	'',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'http://localhost/gepi/login.php',	'4',	'2020-05-02 03:07:17'),
('A.Mustapha',	'2020-05-02 03:08:01',	'7ip5sll6bramjr334ipa523v9k',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'',	'0',	'2020-05-02 03:10:55'),
('prof_a',	'2020-05-02 03:11:00',	'1biqbk2c45vmqoa609776n84mg',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'',	'0',	'2020-05-02 03:13:45'),
('A.Mustapha',	'2020-05-02 03:13:50',	'jj7rt0ruu85pk1o99la7tlcia1',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'',	'0',	'2020-05-02 03:14:48'),
('ADMIN',	'2020-05-02 03:14:53',	'rav9ntj39clmafqr2ce66tm5tu',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'',	'0',	'2020-05-02 03:32:44'),
('ADMIN',	'2020-05-02 03:32:47',	'i8auufdu0s3ofedt8f2145ill1',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'',	'0',	'2020-05-02 03:47:15'),
('scolarite',	'2020-05-02 03:47:21',	'295addcq1t7obqn1289veumbhb',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'',	'0',	'2020-05-02 04:11:42'),
('ADMIN',	'2020-05-02 04:11:47',	'hv4e9rqqmg9fvov6gvdfm8e4sj',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'',	'0',	'2020-05-02 04:16:34'),
('prof_a',	'2020-05-02 04:16:41',	'vmc7t7kftski176qggescgeb9g',	'::1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',	'',	'1',	'2020-05-02 04:46:50');

DROP TABLE IF EXISTS `log_maj_sconet`;
CREATE TABLE `log_maj_sconet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `texte` longtext NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `lsun_ap_communs`;
CREATE TABLE `lsun_ap_communs` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant unique',
  `intituleAP` varchar(150) NOT NULL COMMENT 'Intitulé de l''AP',
  `descriptionAP` varchar(600) NOT NULL COMMENT 'Description de l''AP',
  PRIMARY KEY (`id`),
  UNIQUE KEY `intituleAP` (`intituleAP`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `lsun_epi_communs`;
CREATE TABLE `lsun_epi_communs` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant unique',
  `periode` int(11) NOT NULL COMMENT 'Periode de référence de l''epi',
  `codeEPI` varchar(10) NOT NULL COMMENT 'Code officiel de l''epi',
  `intituleEpi` varchar(150) NOT NULL COMMENT 'Intitulé de l''epi',
  `descriptionEpi` text NOT NULL COMMENT 'Description de l''epi',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `lsun_j_aid_parcours`;
CREATE TABLE `lsun_j_aid_parcours` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant unique',
  `id_aid` int(11) NOT NULL COMMENT 'id de l''aid',
  `id_parcours` int(11) NOT NULL COMMENT 'id du parcour',
  PRIMARY KEY (`id`),
  UNIQUE KEY `parcours` (`id_parcours`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `lsun_j_ap_aid`;
CREATE TABLE `lsun_j_ap_aid` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant unique',
  `id_aid` int(11) NOT NULL COMMENT 'id de l''aid',
  `id_ap` int(11) NOT NULL COMMENT 'id de l''ap',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ap` (`id_ap`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `lsun_j_ap_matiere`;
CREATE TABLE `lsun_j_ap_matiere` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant unique',
  `id_enseignements` varchar(10) NOT NULL COMMENT 'id de l''enseignement',
  `modalite` varchar(1) DEFAULT NULL COMMENT 'modalite d''élection de la matiere',
  `id_ap` int(11) NOT NULL COMMENT 'id de l''ap',
  PRIMARY KEY (`id`),
  UNIQUE KEY `triplette` (`id_enseignements`,`id_ap`,`modalite`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `lsun_j_epi_classes`;
CREATE TABLE `lsun_j_epi_classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant unique',
  `id_epi` int(11) NOT NULL COMMENT 'id de l''epi',
  `id_classe` int(11) NOT NULL COMMENT 'id de la classe',
  PRIMARY KEY (`id`),
  UNIQUE KEY `couple` (`id_epi`,`id_classe`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `lsun_j_epi_enseignements`;
CREATE TABLE `lsun_j_epi_enseignements` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant unique',
  `id_epi` int(11) NOT NULL COMMENT 'id de l''epi',
  `id_enseignements` int(11) NOT NULL COMMENT 'id de l''enseignement',
  `aid` int(11) NOT NULL COMMENT '0 si enseignement, 1 si AID',
  PRIMARY KEY (`id`),
  UNIQUE KEY `couple` (`id_epi`,`id_enseignements`,`aid`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `lsun_j_epi_matieres`;
CREATE TABLE `lsun_j_epi_matieres` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant unique',
  `id_matiere` varchar(50) NOT NULL COMMENT 'id de la matiere',
  `modalite` varchar(1) DEFAULT NULL COMMENT 'modalite d''élection de la matiere',
  `id_epi` int(11) NOT NULL COMMENT 'id de l''epi',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `lsun_parcours_communs`;
CREATE TABLE `lsun_parcours_communs` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant unique',
  `periode` int(11) NOT NULL COMMENT 'Periode de référence du parcours',
  `classe` int(11) NOT NULL COMMENT 'id de la classe concernée',
  `codeParcours` varchar(10) NOT NULL COMMENT 'Code officiel du parcours',
  `description` text NOT NULL COMMENT 'Description du parcours',
  PRIMARY KEY (`id`),
  UNIQUE KEY `parcours` (`periode`,`classe`,`codeParcours`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `lsun_responsables`;
CREATE TABLE `lsun_responsables` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant unique',
  `login` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `matieres`;
CREATE TABLE `matieres` (
  `matiere` varchar(255) NOT NULL DEFAULT '',
  `nom_complet` varchar(200) NOT NULL DEFAULT '',
  `priority` smallint(6) NOT NULL DEFAULT '0',
  `categorie_id` int(11) NOT NULL DEFAULT '1',
  `matiere_aid` char(1) NOT NULL DEFAULT 'n',
  `matiere_atelier` char(1) NOT NULL DEFAULT 'n',
  `code_matiere` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`matiere`)
) TYPE=MyISAM;

INSERT INTO `matieres` (`matiere`, `nom_complet`, `priority`, `categorie_id`, `matiere_aid`, `matiere_atelier`, `code_matiere`) VALUES
('Scienc_cor',	'Les sciences du Coran',	11,	2,	'n',	'n',	''),
('Apprentis_cor',	'Le suivi d’apprentissage du Coran',	12,	2,	'n',	'n',	''),
('Tajwid',	'Tajwîd, les règles de lecture du Coran',	13,	2,	'n',	'n',	''),
('aquida',	'Al ‘aquida, ilm al kalâm, La Foi, le Credo, les fondements de la religion',	14,	5,	'n',	'n',	''),
('Alfiraq',	'Al firaq wal madhahib,  écoles de droit et de pensée, schismes et disciplines intellectuelles en Islam',	15,	5,	'n',	'n',	''),
('Sira',	'La Sira, La vie du Prophète de l\'Islam',	16,	4,	'n',	'n',	''),
('histoire',	'L’histoire musulmane',	18,	4,	'n',	'n',	''),
('Cour_pensee',	'Courant de pensée et mouvement réformiste',	17,	4,	'n',	'n',	''),
('Etud_hadith',	'L’étude des ahadith (recueils)',	19,	3,	'n',	'n',	''),
('Scienc_hadith',	'Les sciences du hadith',	20,	3,	'n',	'n',	'');

DROP TABLE IF EXISTS `matieres_appreciations`;
CREATE TABLE `matieres_appreciations` (
  `login` varchar(50) NOT NULL DEFAULT '',
  `id_groupe` int(11) NOT NULL DEFAULT '0',
  `periode` int(11) NOT NULL DEFAULT '0',
  `appreciation` text NOT NULL,
  PRIMARY KEY (`login`,`id_groupe`,`periode`)
) TYPE=MyISAM;

INSERT INTO `matieres_appreciations` (`login`, `id_groupe`, `periode`, `appreciation`) VALUES
('A.Mustapha',	9,	1,	'Félicitations '),
('A.Outman',	9,	1,	'très bon travail'),
('H.Youssef',	9,	1,	'très bon travail'),
('I.Abdelhaki',	9,	1,	'très bon travail'),
('K.Abdelhami',	9,	1,	'très bon travail'),
('M.Abdelhaki',	9,	1,	'très bon travail'),
('S.Anass',	9,	1,	'très bon travail'),
('A.Mustapha',	2,	1,	'très bon travail'),
('A.Outman',	2,	1,	'Félicitations'),
('H.Youssef',	2,	1,	'Félicitations'),
('M.Abdelhaki',	2,	1,	'Félicitations'),
('S.Anass',	2,	1,	'Très bon travail'),
('A.Mustapha',	6,	1,	'Félicitations'),
('I.Abdelhaki',	6,	1,	'Félicitations'),
('A.Mustapha',	4,	1,	'Félicitations'),
('A.Outman',	4,	1,	'Félicitations'),
('H.Youssef',	4,	1,	'Félicitations'),
('I.Abdelhaki',	4,	1,	'Félicitations'),
('K.Abdelhami',	4,	1,	'Félicitations'),
('M.Abdelhaki',	4,	1,	'Félicitations'),
('S.Anass',	4,	1,	'Félicitations'),
('A.Mustapha',	3,	1,	'Félicitations'),
('A.Outman',	3,	1,	'Résultats remarquables'),
('H.Youssef',	3,	1,	'Résultats remarquables'),
('I.Abdelhaki',	3,	1,	'Résultats remarquables'),
('K.Abdelhami',	3,	1,	'Résultats remarquables'),
('M.Abdelhaki',	3,	1,	'Résultats remarquables'),
('S.Anass',	3,	1,	'Résultats remarquables');

DROP TABLE IF EXISTS `matieres_appreciations_acces`;
CREATE TABLE `matieres_appreciations_acces` (
  `id_classe` int(11) NOT NULL,
  `statut` varchar(255) NOT NULL,
  `periode` int(11) NOT NULL,
  `date` date NOT NULL,
  `acces` enum('y','n','date','d') NOT NULL
) TYPE=MyISAM;

INSERT INTO `matieres_appreciations_acces` (`id_classe`, `statut`, `periode`, `date`, `acces`) VALUES
(1,	'responsable',	1,	'0000-00-00',	'y'),
(1,	'responsable',	2,	'0000-00-00',	'n'),
(1,	'responsable',	3,	'0000-00-00',	'n'),
(1,	'eleve',	1,	'0000-00-00',	'n');

DROP TABLE IF EXISTS `matieres_appreciations_acces_eleve`;
CREATE TABLE `matieres_appreciations_acces_eleve` (
  `login` varchar(50) NOT NULL,
  `periode` int(11) NOT NULL,
  `acces` enum('y','n') NOT NULL
) TYPE=MyISAM;


DROP TABLE IF EXISTS `matieres_appreciations_grp`;
CREATE TABLE `matieres_appreciations_grp` (
  `id_groupe` int(11) NOT NULL DEFAULT '0',
  `periode` int(11) NOT NULL DEFAULT '0',
  `appreciation` text NOT NULL,
  PRIMARY KEY (`id_groupe`,`periode`)
) TYPE=MyISAM;

INSERT INTO `matieres_appreciations_grp` (`id_groupe`, `periode`, `appreciation`) VALUES
(9,	1,	'très bonne trimestre'),
(5,	1,	'très bon travail'),
(2,	1,	'très bon travail'),
(4,	1,	'Félicitations'),
(3,	1,	'Résultats remarquables');

DROP TABLE IF EXISTS `matieres_appreciations_tempo`;
CREATE TABLE `matieres_appreciations_tempo` (
  `login` varchar(50) NOT NULL DEFAULT '',
  `id_groupe` int(11) NOT NULL DEFAULT '0',
  `periode` int(11) NOT NULL DEFAULT '0',
  `appreciation` text NOT NULL,
  PRIMARY KEY (`login`,`id_groupe`,`periode`),
  KEY `groupe_periode` (`id_groupe`,`periode`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `matieres_app_corrections`;
CREATE TABLE `matieres_app_corrections` (
  `login` varchar(50) NOT NULL DEFAULT '',
  `id_groupe` int(11) NOT NULL DEFAULT '0',
  `periode` int(11) NOT NULL DEFAULT '0',
  `appreciation` text NOT NULL,
  PRIMARY KEY (`login`,`id_groupe`,`periode`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `matieres_app_delais`;
CREATE TABLE `matieres_app_delais` (
  `periode` int(11) NOT NULL DEFAULT '0',
  `id_groupe` int(11) NOT NULL DEFAULT '0',
  `date_limite` timestamp NOT NULL,
  `mode` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`periode`,`id_groupe`),
  KEY `id_groupe` (`id_groupe`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `matieres_categories`;
CREATE TABLE `matieres_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_court` varchar(255) NOT NULL DEFAULT '',
  `nom_complet` varchar(255) NOT NULL DEFAULT '',
  `priority` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

INSERT INTO `matieres_categories` (`id`, `nom_court`, `nom_complet`, `priority`) VALUES
(1,	'Autres',	'Autres',	5),
(2,	'Coran',	'Coran',	1),
(3,	'Hadiths',	'Hadiths',	2),
(4,	'Sira&amp;histoire',	'Sira et histoire',	3),
(5,	'Tawhid',	'Tawhid',	4);

DROP TABLE IF EXISTS `matieres_notes`;
CREATE TABLE `matieres_notes` (
  `login` varchar(50) NOT NULL DEFAULT '',
  `id_groupe` int(11) NOT NULL DEFAULT '0',
  `periode` int(11) NOT NULL DEFAULT '0',
  `note` float(10,1) DEFAULT NULL,
  `statut` varchar(10) NOT NULL DEFAULT '',
  `rang` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`login`,`id_groupe`,`periode`),
  KEY `groupe_periode_statut` (`id_groupe`,`periode`,`statut`)
) TYPE=MyISAM;

INSERT INTO `matieres_notes` (`login`, `id_groupe`, `periode`, `note`, `statut`, `rang`) VALUES
('A.Mustapha',	1,	1,	10.0,	'',	7),
('A.Outman',	1,	1,	17.0,	'',	2),
('H.Youssef',	1,	1,	15.0,	'',	4),
('I.Abdelhaki',	1,	1,	12.0,	'',	6),
('K.Abdelhami',	1,	1,	16.0,	'',	3),
('M.Abdelhaki',	1,	1,	15.0,	'',	4),
('S.Anass',	1,	1,	18.0,	'',	1),
('A.Mustapha',	7,	1,	17.0,	'',	1),
('A.Outman',	7,	1,	16.0,	'',	2),
('H.Youssef',	7,	1,	12.0,	'',	6),
('I.Abdelhaki',	7,	1,	14.0,	'',	4),
('K.Abdelhami',	7,	1,	13.0,	'',	5),
('M.Abdelhaki',	7,	1,	11.0,	'',	7),
('S.Anass',	7,	1,	15.0,	'',	3),
('A.Mustapha',	8,	1,	17.0,	'',	3),
('A.Outman',	8,	1,	18.0,	'',	2),
('H.Youssef',	8,	1,	19.0,	'',	1),
('I.Abdelhaki',	8,	1,	13.0,	'',	7),
('K.Abdelhami',	8,	1,	14.0,	'',	6),
('M.Abdelhaki',	8,	1,	15.0,	'',	5),
('S.Anass',	8,	1,	16.0,	'',	4),
('A.Mustapha',	10,	1,	10.0,	'',	7),
('A.Outman',	10,	1,	11.0,	'',	6),
('H.Youssef',	10,	1,	12.0,	'',	5),
('I.Abdelhaki',	10,	1,	13.0,	'',	4),
('K.Abdelhami',	10,	1,	14.0,	'',	3),
('M.Abdelhaki',	10,	1,	15.0,	'',	2),
('S.Anass',	10,	1,	16.0,	'',	1),
('A.Mustapha',	2,	1,	15.0,	'',	5),
('A.Outman',	2,	1,	20.0,	'',	1),
('H.Youssef',	2,	1,	19.0,	'',	3),
('I.Abdelhaki',	2,	1,	10.0,	'',	7),
('K.Abdelhami',	2,	1,	13.0,	'',	6),
('M.Abdelhaki',	2,	1,	20.0,	'',	1),
('S.Anass',	2,	1,	17.0,	'',	4),
('A.Mustapha',	5,	1,	14.0,	'',	2),
('A.Outman',	5,	1,	12.0,	'',	4),
('H.Youssef',	5,	1,	13.0,	'',	3),
('I.Abdelhaki',	5,	1,	11.0,	'',	6),
('K.Abdelhami',	5,	1,	15.0,	'',	1),
('M.Abdelhaki',	5,	1,	12.0,	'',	4),
('S.Anass',	5,	1,	10.0,	'',	7),
('A.Mustapha',	9,	1,	20.0,	'',	1),
('A.Outman',	9,	1,	19.5,	'',	2),
('H.Youssef',	9,	1,	19.0,	'',	3),
('I.Abdelhaki',	9,	1,	18.0,	'',	5),
('K.Abdelhami',	9,	1,	18.5,	'',	4),
('M.Abdelhaki',	9,	1,	17.5,	'',	6),
('S.Anass',	9,	1,	17.0,	'',	7),
('A.Mustapha',	6,	1,	20.0,	'',	1),
('A.Outman',	6,	1,	11.0,	'',	6),
('H.Youssef',	6,	1,	15.0,	'',	4),
('I.Abdelhaki',	6,	1,	20.0,	'',	1),
('K.Abdelhami',	6,	1,	15.0,	'',	4),
('M.Abdelhaki',	6,	1,	10.0,	'',	7),
('S.Anass',	6,	1,	16.0,	'',	3),
('A.Mustapha',	4,	1,	10.5,	'',	7),
('A.Outman',	4,	1,	15.3,	'',	3),
('H.Youssef',	4,	1,	14.0,	'',	4),
('I.Abdelhaki',	4,	1,	14.0,	'',	4),
('K.Abdelhami',	4,	1,	12.0,	'',	6),
('M.Abdelhaki',	4,	1,	17.5,	'',	1),
('S.Anass',	4,	1,	16.2,	'',	2),
('A.Mustapha',	3,	1,	14.0,	'',	6),
('A.Outman',	3,	1,	20.0,	'',	1),
('H.Youssef',	3,	1,	19.0,	'',	2),
('I.Abdelhaki',	3,	1,	14.0,	'',	6),
('K.Abdelhami',	3,	1,	16.5,	'',	3),
('M.Abdelhaki',	3,	1,	15.0,	'',	4),
('S.Anass',	3,	1,	14.5,	'',	5);

DROP TABLE IF EXISTS `matiere_element_programme`;
CREATE TABLE `matiere_element_programme` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'identifiant unique',
  `libelle` varchar(255) NOT NULL DEFAULT '' COMMENT 'Libellé de l''élément de programme',
  `id_user` varchar(50) NOT NULL DEFAULT '' COMMENT 'Auteur/proprio de l''élément de programme',
  PRIMARY KEY (`id`),
  UNIQUE KEY `libelle` (`libelle`)
) TYPE=MyISAM COMMENT='Éléments de programme travaillé';


DROP TABLE IF EXISTS `mef`;
CREATE TABLE `mef` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Cle primaire de la classe',
  `mef_code` varchar(50) NOT NULL DEFAULT '' COMMENT 'Numero de la nomenclature officielle (numero MEF)',
  `libelle_court` varchar(50) NOT NULL COMMENT 'libelle de la formation',
  `libelle_long` varchar(300) NOT NULL COMMENT 'libelle de la formation',
  `libelle_edition` varchar(300) NOT NULL COMMENT 'libelle de la formation pour presentation',
  `code_mefstat` varchar(50) NOT NULL DEFAULT '',
  `mef_rattachement` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) TYPE=MyISAM COMMENT='Module élémentaire de formation';

INSERT INTO `mef` (`id`, `mef_code`, `libelle_court`, `libelle_long`, `libelle_edition`, `code_mefstat`, `mef_rattachement`) VALUES
(1,	'10310019110',	'1ERE',	'1ERE',	'1ére',	'21160010019',	'10310019110'),
(2,	'10210001110',	'2EME',	'2EME',	'2ème',	'21150010001',	'10210001110'),
(3,	'10110001110',	'3EME',	'3EME',	'3ème',	'21120010001',	'10110001110');

DROP TABLE IF EXISTS `mef_matieres`;
CREATE TABLE `mef_matieres` (
  `mef_code` varchar(50) NOT NULL,
  `code_matiere` varchar(250) NOT NULL,
  `code_modalite_elect` varchar(6) NOT NULL
) TYPE=MyISAM;


DROP TABLE IF EXISTS `mentions`;
CREATE TABLE `mentions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mention` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `messagerie`;
CREATE TABLE `messagerie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `in_reply_to` int(11) NOT NULL,
  `login_src` varchar(50) NOT NULL,
  `login_dest` varchar(50) NOT NULL,
  `sujet` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `date_msg` timestamp NOT NULL,
  `date_visibilite` timestamp NOT NULL,
  `vu` tinyint(4) NOT NULL,
  `date_vu` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `texte` text NOT NULL,
  `date_debut` int(11) NOT NULL DEFAULT '0',
  `date_fin` int(11) NOT NULL DEFAULT '0',
  `auteur` varchar(50) NOT NULL DEFAULT '',
  `statuts_destinataires` varchar(10) NOT NULL DEFAULT '',
  `login_destinataire` varchar(50) NOT NULL DEFAULT '',
  `date_decompte` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `date_debut_fin` (`date_debut`,`date_fin`),
  KEY `login_destinataire` (`login_destinataire`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `message_login`;
CREATE TABLE `message_login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `texte` text NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

INSERT INTO `message_login` (`id`, `texte`) VALUES
(1,	'Espace pour un message en page de login paramétrable en Gestion des connexions.');

DROP TABLE IF EXISTS `miseajour`;
CREATE TABLE `miseajour` (
  `id_miseajour` int(11) NOT NULL AUTO_INCREMENT,
  `fichier_miseajour` varchar(250) NOT NULL,
  `emplacement_miseajour` varchar(250) NOT NULL,
  `date_miseajour` date NOT NULL,
  `heure_miseajour` time NOT NULL,
  PRIMARY KEY (`id_miseajour`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `modalites_accompagnement`;
CREATE TABLE `modalites_accompagnement` (
  `code` varchar(10) NOT NULL DEFAULT '',
  `libelle` varchar(255) NOT NULL DEFAULT '',
  `avec_commentaire` char(1) NOT NULL DEFAULT 'n',
  PRIMARY KEY (`code`)
) TYPE=MyISAM;

INSERT INTO `modalites_accompagnement` (`code`, `libelle`, `avec_commentaire`) VALUES
('PAP',	'Plan d’accompagnement personnalisé',	'n'),
('PAI',	'Projet d’accueil individualisé',	'n'),
('PPRE',	'Programme personnalisé de réussite éducative',	'y'),
('PPS',	'Projet personnalisé de scolarisation',	'n'),
('ULIS',	'Unité localisée pour l’inclusion scolaire',	'n'),
('UPE2A',	'Unité pédagogique pour élèves allophones arrivants',	'n'),
('SEGPA',	'Section d’enseignement général adapté',	'n'),
('CTR',	'Contrat de réussite',	'y');

DROP TABLE IF EXISTS `modeles_grilles_pdf`;
CREATE TABLE `modeles_grilles_pdf` (
  `id_modele` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL DEFAULT '',
  `nom_modele` varchar(255) NOT NULL,
  `par_defaut` enum('y','n') DEFAULT 'n',
  PRIMARY KEY (`id_modele`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `modeles_grilles_pdf_valeurs`;
CREATE TABLE `modeles_grilles_pdf_valeurs` (
  `id_modele` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL DEFAULT '',
  `valeur` varchar(255) NOT NULL,
  KEY `id_modele_champ` (`id_modele`,`nom`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `modele_bulletin`;
CREATE TABLE `modele_bulletin` (
  `id_model_bulletin` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `valeur` varchar(255) NOT NULL
) TYPE=MyISAM;

INSERT INTO `modele_bulletin` (`id_model_bulletin`, `nom`, `valeur`) VALUES
(1,	'id_model_bulletin',	'1'),
(1,	'nom_model_bulletin',	'Standard'),
(1,	'active_colonne_Elements_Programmes',	'0'),
(1,	'largeur_Elements_Programmes',	'50'),
(1,	'active_bloc_datation',	'1'),
(1,	'active_bloc_eleve',	'1'),
(1,	'active_bloc_adresse_parent',	'0'),
(1,	'active_bloc_absence',	'0'),
(1,	'active_bloc_note_appreciation',	'1'),
(1,	'active_bloc_avis_conseil',	'1'),
(1,	'active_bloc_chef',	'1'),
(1,	'active_photo',	'0'),
(1,	'active_coef_moyenne',	'1'),
(1,	'active_nombre_note',	'0'),
(1,	'active_nombre_note_case',	'0'),
(1,	'active_moyenne',	'1'),
(1,	'active_moyenne_eleve',	'1'),
(1,	'active_moyenne_classe',	'1'),
(1,	'active_moyenne_min',	'1'),
(1,	'active_moyenne_max',	'1'),
(1,	'active_regroupement_cote',	'1'),
(1,	'active_entete_regroupement',	'1'),
(1,	'active_moyenne_regroupement',	'1'),
(1,	'active_rang',	'1'),
(1,	'active_graphique_niveau',	'0'),
(1,	'active_appreciation',	'1'),
(1,	'affiche_doublement',	'1'),
(1,	'affiche_date_naissance',	'1'),
(1,	'affiche_dp',	'0'),
(1,	'affiche_nom_court',	'0'),
(1,	'affiche_effectif_classe',	'1'),
(1,	'affiche_numero_impression',	'0'),
(1,	'caractere_utilse',	'Arial'),
(1,	'X_parent',	'110'),
(1,	'Y_parent',	'40'),
(1,	'X_eleve',	'5'),
(1,	'Y_eleve',	'40'),
(1,	'cadre_eleve',	'1'),
(1,	'X_datation_bul',	'110'),
(1,	'Y_datation_bul',	'5'),
(1,	'cadre_datation_bul',	'1'),
(1,	'hauteur_info_categorie',	'5'),
(1,	'X_note_app',	'5'),
(1,	'Y_note_app',	'72'),
(1,	'longeur_note_app',	'200'),
(1,	'hauteur_note_app',	'175'),
(1,	'largeur_coef_moyenne',	'8'),
(1,	'largeur_nombre_note',	'8'),
(1,	'largeur_d_une_moyenne',	'12'),
(1,	'largeur_niveau',	'18'),
(1,	'largeur_rang',	'10'),
(1,	'X_absence',	'5'),
(1,	'Y_absence',	'246.3'),
(1,	'hauteur_entete_moyenne_general',	'5'),
(1,	'X_avis_cons',	'5'),
(1,	'Y_avis_cons',	'250'),
(1,	'longeur_avis_cons',	'130'),
(1,	'hauteur_avis_cons',	'37'),
(1,	'cadre_avis_cons',	'1'),
(1,	'X_sign_chef',	'138'),
(1,	'Y_sign_chef',	'250'),
(1,	'longeur_sign_chef',	'67'),
(1,	'hauteur_sign_chef',	'37'),
(1,	'cadre_sign_chef',	'1'),
(1,	'affiche_filigrame',	'0'),
(1,	'texte_filigrame',	'DUPLICATA INTERNET'),
(1,	'affiche_logo_etab',	'1'),
(1,	'entente_mel',	'1'),
(1,	'entente_tel',	'1'),
(1,	'entente_fax',	'1'),
(1,	'L_max_logo',	'75'),
(1,	'H_max_logo',	'75'),
(1,	'toute_moyenne_meme_col',	'0'),
(1,	'active_reperage_eleve',	'1'),
(1,	'couleur_reperage_eleve1',	'253'),
(1,	'couleur_reperage_eleve2',	'241'),
(1,	'couleur_reperage_eleve3',	'184'),
(1,	'couleur_categorie_entete',	'1'),
(1,	'couleur_categorie_entete1',	'239'),
(1,	'couleur_categorie_entete2',	'239'),
(1,	'couleur_categorie_entete3',	'239'),
(1,	'couleur_categorie_cote',	'1'),
(1,	'couleur_categorie_cote1',	'239'),
(1,	'couleur_categorie_cote2',	'239'),
(1,	'couleur_categorie_cote3',	'239'),
(1,	'couleur_moy_general',	'1'),
(1,	'couleur_moy_general1',	'239'),
(1,	'couleur_moy_general2',	'239'),
(1,	'couleur_moy_general3',	'239'),
(1,	'titre_entete_matiere',	'Matière'),
(1,	'titre_entete_coef',	'coef.'),
(1,	'titre_entete_nbnote',	'nb. n.'),
(1,	'titre_entete_rang',	'rang'),
(1,	'titre_entete_appreciation',	'Appréciation / Conseils'),
(1,	'active_coef_sousmoyene',	'0'),
(1,	'arrondie_choix',	'0.01'),
(1,	'nb_chiffre_virgule',	'2'),
(1,	'chiffre_avec_zero',	'0'),
(1,	'autorise_sous_matiere',	'1'),
(1,	'affichage_haut_responsable',	'1'),
(1,	'entete_model_bulletin',	'1'),
(1,	'ordre_entete_model_bulletin',	'1'),
(1,	'affiche_etab_origine',	'0'),
(1,	'imprime_pour',	'1'),
(1,	'largeur_matiere',	'40'),
(1,	'nom_etab_gras',	'1'),
(1,	'taille_texte_date_edition',	'0'),
(1,	'taille_texte_matiere',	'0'),
(1,	'active_moyenne_general',	'1'),
(1,	'titre_bloc_avis_conseil',	''),
(1,	'taille_titre_bloc_avis_conseil',	'0'),
(1,	'taille_profprincipal_bloc_avis_conseil',	'0'),
(1,	'affiche_fonction_chef',	'0'),
(1,	'taille_texte_fonction_chef',	'0'),
(1,	'taille_texte_identitee_chef',	'0'),
(1,	'tel_image',	''),
(1,	'tel_texte',	'Tel. :'),
(1,	'fax_image',	''),
(1,	'fax_texte',	'Fax :'),
(1,	'courrier_image',	''),
(1,	'courrier_texte',	''),
(1,	'largeur_bloc_eleve',	'0'),
(1,	'hauteur_bloc_eleve',	'0'),
(1,	'largeur_bloc_adresse',	'0'),
(1,	'hauteur_bloc_adresse',	'0'),
(1,	'largeur_bloc_datation',	'0'),
(1,	'hauteur_bloc_datation',	'0'),
(1,	'taille_texte_classe',	'0'),
(1,	'type_texte_classe',	''),
(1,	'taille_texte_annee',	'0'),
(1,	'type_texte_annee',	''),
(1,	'taille_texte_periode',	'0'),
(1,	'type_texte_periode',	''),
(1,	'taille_texte_categorie_cote',	'0'),
(1,	'taille_texte_categorie',	'0'),
(1,	'type_texte_date_datation',	''),
(1,	'cadre_adresse',	'0'),
(1,	'centrage_logo',	'0'),
(1,	'Y_centre_logo',	'18'),
(1,	'ajout_cadre_blanc_photo',	'0'),
(1,	'affiche_moyenne_mini_general',	'1'),
(1,	'affiche_moyenne_maxi_general',	'1'),
(1,	'affiche_date_edition',	'0'),
(2,	'id_model_bulletin',	'2'),
(2,	'nom_model_bulletin',	'Standard avec photo'),
(2,	'active_colonne_Elements_Programmes',	'1'),
(2,	'largeur_Elements_Programmes',	'50'),
(2,	'active_bloc_datation',	'1'),
(2,	'active_bloc_eleve',	'1'),
(2,	'active_bloc_adresse_parent',	'1'),
(2,	'active_bloc_absence',	'1'),
(2,	'active_bloc_note_appreciation',	'1'),
(2,	'active_bloc_avis_conseil',	'1'),
(2,	'active_bloc_chef',	'1'),
(2,	'active_photo',	'1'),
(2,	'active_coef_moyenne',	'0'),
(2,	'active_nombre_note',	'0'),
(2,	'active_nombre_note_case',	'0'),
(2,	'active_moyenne',	'1'),
(2,	'active_moyenne_eleve',	'1'),
(2,	'active_moyenne_classe',	'1'),
(2,	'active_moyenne_min',	'1'),
(2,	'active_moyenne_max',	'1'),
(2,	'active_regroupement_cote',	'0'),
(2,	'active_entete_regroupement',	'0'),
(2,	'active_moyenne_regroupement',	'0'),
(2,	'active_rang',	'0'),
(2,	'active_graphique_niveau',	'0'),
(2,	'active_appreciation',	'1'),
(2,	'affiche_doublement',	'1'),
(2,	'affiche_date_naissance',	'1'),
(2,	'affiche_dp',	'1'),
(2,	'affiche_nom_court',	'0'),
(2,	'affiche_effectif_classe',	'0'),
(2,	'affiche_numero_impression',	'0'),
(2,	'caractere_utilse',	'Arial'),
(2,	'X_parent',	'110'),
(2,	'Y_parent',	'40'),
(2,	'X_eleve',	'5'),
(2,	'Y_eleve',	'40'),
(2,	'cadre_eleve',	'1'),
(2,	'X_datation_bul',	'110'),
(2,	'Y_datation_bul',	'5'),
(2,	'cadre_datation_bul',	'1'),
(2,	'hauteur_info_categorie',	'5'),
(2,	'X_note_app',	'5'),
(2,	'Y_note_app',	'72'),
(2,	'longeur_note_app',	'200'),
(2,	'hauteur_note_app',	'175'),
(2,	'largeur_coef_moyenne',	'8'),
(2,	'largeur_nombre_note',	'8'),
(2,	'largeur_d_une_moyenne',	'10'),
(2,	'largeur_niveau',	'18'),
(2,	'largeur_rang',	'5'),
(2,	'X_absence',	'5'),
(2,	'Y_absence',	'246.3'),
(2,	'hauteur_entete_moyenne_general',	'5'),
(2,	'X_avis_cons',	'5'),
(2,	'Y_avis_cons',	'250'),
(2,	'longeur_avis_cons',	'130'),
(2,	'hauteur_avis_cons',	'37'),
(2,	'cadre_avis_cons',	'1'),
(2,	'X_sign_chef',	'138'),
(2,	'Y_sign_chef',	'250'),
(2,	'longeur_sign_chef',	'67'),
(2,	'hauteur_sign_chef',	'37'),
(2,	'cadre_sign_chef',	'0'),
(2,	'affiche_filigrame',	'0'),
(2,	'texte_filigrame',	'DUPLICATA INTERNET'),
(2,	'affiche_logo_etab',	'1'),
(2,	'entente_mel',	'1'),
(2,	'entente_tel',	'1'),
(2,	'entente_fax',	'1'),
(2,	'L_max_logo',	'75'),
(2,	'H_max_logo',	'75'),
(2,	'toute_moyenne_meme_col',	'0'),
(2,	'active_reperage_eleve',	'1'),
(2,	'couleur_reperage_eleve1',	'255'),
(2,	'couleur_reperage_eleve2',	'255'),
(2,	'couleur_reperage_eleve3',	'207'),
(2,	'couleur_categorie_entete',	'1'),
(2,	'couleur_categorie_entete1',	'239'),
(2,	'couleur_categorie_entete2',	'239'),
(2,	'couleur_categorie_entete3',	'239'),
(2,	'couleur_categorie_cote',	'1'),
(2,	'couleur_categorie_cote1',	'239'),
(2,	'couleur_categorie_cote2',	'239'),
(2,	'couleur_categorie_cote3',	'239'),
(2,	'couleur_moy_general',	'1'),
(2,	'couleur_moy_general1',	'239'),
(2,	'couleur_moy_general2',	'239'),
(2,	'couleur_moy_general3',	'239'),
(2,	'titre_entete_matiere',	'Matière'),
(2,	'titre_entete_coef',	'coef.'),
(2,	'titre_entete_nbnote',	'nb. n.'),
(2,	'titre_entete_rang',	'rang'),
(2,	'titre_entete_appreciation',	'Appréciation / Conseils'),
(2,	'active_coef_sousmoyene',	'0'),
(2,	'arrondie_choix',	'0'),
(2,	'nb_chiffre_virgule',	'2'),
(2,	'chiffre_avec_zero',	'0'),
(2,	'autorise_sous_matiere',	'1'),
(2,	'affichage_haut_responsable',	'1'),
(2,	'entete_model_bulletin',	'1'),
(2,	'ordre_entete_model_bulletin',	'1'),
(2,	'affiche_etab_origine',	'0'),
(2,	'imprime_pour',	'0'),
(2,	'largeur_matiere',	'40'),
(2,	'nom_etab_gras',	'0'),
(2,	'taille_texte_date_edition',	'0'),
(2,	'taille_texte_matiere',	'0'),
(2,	'active_moyenne_general',	'0'),
(2,	'titre_bloc_avis_conseil',	''),
(2,	'taille_titre_bloc_avis_conseil',	'0'),
(2,	'taille_profprincipal_bloc_avis_conseil',	'0'),
(2,	'affiche_fonction_chef',	'0'),
(2,	'taille_texte_fonction_chef',	'0'),
(2,	'taille_texte_identitee_chef',	'0'),
(2,	'tel_image',	''),
(2,	'tel_texte',	''),
(2,	'fax_image',	''),
(2,	'fax_texte',	''),
(2,	'courrier_image',	''),
(2,	'courrier_texte',	''),
(2,	'largeur_bloc_eleve',	'0'),
(2,	'hauteur_bloc_eleve',	'0'),
(2,	'largeur_bloc_adresse',	'0'),
(2,	'hauteur_bloc_adresse',	'0'),
(2,	'largeur_bloc_datation',	'0'),
(2,	'hauteur_bloc_datation',	'0'),
(2,	'taille_texte_classe',	'0'),
(2,	'type_texte_classe',	''),
(2,	'taille_texte_annee',	'0'),
(2,	'type_texte_annee',	''),
(2,	'taille_texte_periode',	'0'),
(2,	'type_texte_periode',	''),
(2,	'taille_texte_categorie_cote',	'0'),
(2,	'taille_texte_categorie',	'0'),
(2,	'type_texte_date_datation',	''),
(2,	'cadre_adresse',	'0'),
(2,	'centrage_logo',	'0'),
(2,	'Y_centre_logo',	'18'),
(2,	'ajout_cadre_blanc_photo',	'0'),
(2,	'affiche_moyenne_mini_general',	'1'),
(2,	'affiche_moyenne_maxi_general',	'1'),
(2,	'affiche_date_edition',	'1'),
(3,	'id_model_bulletin',	'3'),
(3,	'nom_model_bulletin',	'Affiche tout'),
(3,	'active_colonne_Elements_Programmes',	'1'),
(3,	'largeur_Elements_Programmes',	'50'),
(3,	'active_bloc_datation',	'1'),
(3,	'active_bloc_eleve',	'1'),
(3,	'active_bloc_adresse_parent',	'0'),
(3,	'active_bloc_absence',	'0'),
(3,	'active_bloc_note_appreciation',	'1'),
(3,	'active_bloc_avis_conseil',	'1'),
(3,	'active_bloc_chef',	'1'),
(3,	'active_photo',	'1'),
(3,	'active_coef_moyenne',	'1'),
(3,	'active_nombre_note',	'1'),
(3,	'active_nombre_note_case',	'1'),
(3,	'active_moyenne',	'1'),
(3,	'active_moyenne_eleve',	'1'),
(3,	'active_moyenne_classe',	'1'),
(3,	'active_moyenne_min',	'1'),
(3,	'active_moyenne_max',	'1'),
(3,	'active_regroupement_cote',	'1'),
(3,	'active_entete_regroupement',	'1'),
(3,	'active_moyenne_regroupement',	'1'),
(3,	'active_rang',	'1'),
(3,	'active_graphique_niveau',	'1'),
(3,	'active_appreciation',	'1'),
(3,	'affiche_doublement',	'1'),
(3,	'affiche_date_naissance',	'1'),
(3,	'affiche_dp',	'0'),
(3,	'affiche_nom_court',	'1'),
(3,	'affiche_effectif_classe',	'1'),
(3,	'affiche_numero_impression',	'1'),
(3,	'caractere_utilse',	'Arial'),
(3,	'X_parent',	'110'),
(3,	'Y_parent',	'40'),
(3,	'X_eleve',	'5'),
(3,	'Y_eleve',	'40'),
(3,	'cadre_eleve',	'1'),
(3,	'X_datation_bul',	'110'),
(3,	'Y_datation_bul',	'5'),
(3,	'cadre_datation_bul',	'1'),
(3,	'hauteur_info_categorie',	'5'),
(3,	'X_note_app',	'5'),
(3,	'Y_note_app',	'72'),
(3,	'longeur_note_app',	'200'),
(3,	'hauteur_note_app',	'175'),
(3,	'largeur_coef_moyenne',	'8'),
(3,	'largeur_nombre_note',	'8'),
(3,	'largeur_d_une_moyenne',	'10'),
(3,	'largeur_niveau',	'16.5'),
(3,	'largeur_rang',	'6.5'),
(3,	'X_absence',	'5'),
(3,	'Y_absence',	'246.3'),
(3,	'hauteur_entete_moyenne_general',	'5'),
(3,	'X_avis_cons',	'5'),
(3,	'Y_avis_cons',	'250'),
(3,	'longeur_avis_cons',	'130'),
(3,	'hauteur_avis_cons',	'37'),
(3,	'cadre_avis_cons',	'1'),
(3,	'X_sign_chef',	'138'),
(3,	'Y_sign_chef',	'250'),
(3,	'longeur_sign_chef',	'67'),
(3,	'hauteur_sign_chef',	'37'),
(3,	'cadre_sign_chef',	'1'),
(3,	'affiche_filigrame',	'0'),
(3,	'texte_filigrame',	'DUPLICATA INTERNET'),
(3,	'affiche_logo_etab',	'0'),
(3,	'entente_mel',	'1'),
(3,	'entente_tel',	'1'),
(3,	'entente_fax',	'1'),
(3,	'L_max_logo',	'75'),
(3,	'H_max_logo',	'75'),
(3,	'toute_moyenne_meme_col',	'1'),
(3,	'active_reperage_eleve',	'1'),
(3,	'couleur_reperage_eleve1',	'255'),
(3,	'couleur_reperage_eleve2',	'255'),
(3,	'couleur_reperage_eleve3',	'207'),
(3,	'couleur_categorie_entete',	'1'),
(3,	'couleur_categorie_entete1',	'239'),
(3,	'couleur_categorie_entete2',	'239'),
(3,	'couleur_categorie_entete3',	'239'),
(3,	'couleur_categorie_cote',	'1'),
(3,	'couleur_categorie_cote1',	'239'),
(3,	'couleur_categorie_cote2',	'239'),
(3,	'couleur_categorie_cote3',	'239'),
(3,	'couleur_moy_general',	'1'),
(3,	'couleur_moy_general1',	'239'),
(3,	'couleur_moy_general2',	'239'),
(3,	'couleur_moy_general3',	'239'),
(3,	'titre_entete_matiere',	'Matière'),
(3,	'titre_entete_coef',	'coef.'),
(3,	'titre_entete_nbnote',	'nb. n.'),
(3,	'titre_entete_rang',	'rang'),
(3,	'titre_entete_appreciation',	'Appréciation / Conseils'),
(3,	'active_coef_sousmoyene',	'1'),
(3,	'arrondie_choix',	'0.01'),
(3,	'nb_chiffre_virgule',	'2'),
(3,	'chiffre_avec_zero',	'0'),
(3,	'autorise_sous_matiere',	'1'),
(3,	'affichage_haut_responsable',	'1'),
(3,	'entete_model_bulletin',	'2'),
(3,	'ordre_entete_model_bulletin',	'1'),
(3,	'affiche_etab_origine',	'1'),
(3,	'imprime_pour',	'1'),
(3,	'largeur_matiere',	'40'),
(3,	'nom_etab_gras',	'1'),
(3,	'taille_texte_date_edition',	'0'),
(3,	'taille_texte_matiere',	'0'),
(3,	'active_moyenne_general',	'0'),
(3,	'titre_bloc_avis_conseil',	''),
(3,	'taille_titre_bloc_avis_conseil',	'0'),
(3,	'taille_profprincipal_bloc_avis_conseil',	'0'),
(3,	'affiche_fonction_chef',	'0'),
(3,	'taille_texte_fonction_chef',	'0'),
(3,	'taille_texte_identitee_chef',	'0'),
(3,	'tel_image',	''),
(3,	'tel_texte',	''),
(3,	'fax_image',	''),
(3,	'fax_texte',	''),
(3,	'courrier_image',	''),
(3,	'courrier_texte',	''),
(3,	'largeur_bloc_eleve',	'0'),
(3,	'hauteur_bloc_eleve',	'0'),
(3,	'largeur_bloc_adresse',	'0'),
(3,	'hauteur_bloc_adresse',	'0'),
(3,	'largeur_bloc_datation',	'0'),
(3,	'hauteur_bloc_datation',	'0'),
(3,	'taille_texte_classe',	'0'),
(3,	'type_texte_classe',	''),
(3,	'taille_texte_annee',	'0'),
(3,	'type_texte_annee',	''),
(3,	'taille_texte_periode',	'0'),
(3,	'type_texte_periode',	''),
(3,	'taille_texte_categorie_cote',	'0'),
(3,	'taille_texte_categorie',	'0'),
(3,	'type_texte_date_datation',	''),
(3,	'cadre_adresse',	'0'),
(3,	'centrage_logo',	'0'),
(3,	'Y_centre_logo',	'18'),
(3,	'ajout_cadre_blanc_photo',	'0'),
(3,	'affiche_moyenne_mini_general',	'1'),
(3,	'affiche_moyenne_maxi_general',	'1'),
(3,	'affiche_date_edition',	'1'),
(3,	'afficher_abs_tot',	'0'),
(3,	'afficher_abs_nj',	'0'),
(3,	'afficher_abs_ret',	'0'),
(3,	'afficher_abs_cpe',	'0'),
(3,	'presentation_proflist',	'1'),
(3,	'affiche_lieu_naissance',	'0'),
(3,	'largeur_cadre_absences',	'200'),
(3,	'affich_mentions',	'y'),
(3,	'affich_intitule_mentions',	'y'),
(3,	'affich_coches_mentions',	'y'),
(3,	'entete_info_etab_suppl',	'n'),
(3,	'entete_info_etab_suppl_texte',	'Site web'),
(3,	'entete_info_etab_suppl_valeur',	'http://'),
(3,	'moyennes_periodes_precedentes',	'n'),
(3,	'evolution_moyenne_periode_precedente',	'n'),
(3,	'evolution_moyenne_periode_precedente_seuil',	'0'),
(3,	'moyennes_annee',	'n'),
(3,	'afficher_tous_profprincipaux',	'0'),
(3,	'affiche_totalpoints_sur_totalcoefs',	'2'),
(3,	'affiche_ine',	'0'),
(3,	'affiche_moyenne_general_coef_1',	'0'),
(3,	'affiche_moyenne_generale_annuelle',	'n'),
(3,	'affiche_moyenne_generale_annuelle_derniere_periode',	'n'),
(3,	'affiche_numero_responsable',	'0'),
(3,	'affiche_nom_etab',	'1'),
(3,	'affiche_adresse_etab',	'1'),
(3,	'adresse_resp_fontsize',	'12'),
(3,	'cell_ajustee_texte_matiere',	'1'),
(3,	'cell_ajustee_texte_matiere_ratio_min_max',	'3'),
(3,	'orientation_periodes',	''),
(3,	'largeur_cadre_orientation',	'200'),
(3,	'hauteur_cadre_orientation',	'15'),
(3,	'X_cadre_orientation',	'5'),
(3,	'Y_cadre_orientation',	'250'),
(3,	'cadre_voeux_orientation',	'1'),
(3,	'cadre_orientation_proposee',	'1'),
(3,	'X_cadre_orientation_proposee',	'70'),
(3,	'titre_voeux_orientation',	'Voeux'),
(3,	'titre_orientation_proposee',	'Orientation proposée'),
(3,	'titre_avis_orientation_proposee',	'Commentaire'),
(1,	'afficher_abs_tot',	'0'),
(1,	'afficher_abs_nj',	'0'),
(1,	'afficher_abs_ret',	'0'),
(1,	'afficher_abs_cpe',	'0'),
(1,	'presentation_proflist',	'1'),
(1,	'affiche_lieu_naissance',	'0'),
(1,	'largeur_cadre_absences',	'200'),
(1,	'affich_mentions',	'y'),
(1,	'affich_intitule_mentions',	'y'),
(1,	'affich_coches_mentions',	'y'),
(1,	'entete_info_etab_suppl',	'n'),
(1,	'entete_info_etab_suppl_texte',	'Site web'),
(1,	'entete_info_etab_suppl_valeur',	'http://www.iesi.fr'),
(1,	'moyennes_periodes_precedentes',	'n'),
(1,	'evolution_moyenne_periode_precedente',	'n'),
(1,	'evolution_moyenne_periode_precedente_seuil',	'0'),
(1,	'moyennes_annee',	'n'),
(1,	'afficher_tous_profprincipaux',	'0'),
(1,	'affiche_totalpoints_sur_totalcoefs',	'2'),
(1,	'affiche_ine',	'0'),
(1,	'affiche_moyenne_general_coef_1',	'0'),
(1,	'affiche_moyenne_generale_annuelle',	'y'),
(1,	'affiche_moyenne_generale_annuelle_derniere_periode',	'y'),
(1,	'affiche_numero_responsable',	'0'),
(1,	'affiche_nom_etab',	'1'),
(1,	'affiche_adresse_etab',	'1'),
(1,	'adresse_resp_fontsize',	'12'),
(1,	'cell_ajustee_texte_matiere',	'1'),
(1,	'cell_ajustee_texte_matiere_ratio_min_max',	'3'),
(1,	'orientation_periodes',	''),
(1,	'largeur_cadre_orientation',	'200'),
(1,	'hauteur_cadre_orientation',	'15'),
(1,	'X_cadre_orientation',	'5'),
(1,	'Y_cadre_orientation',	'250'),
(1,	'cadre_voeux_orientation',	'1'),
(1,	'cadre_orientation_proposee',	'1'),
(1,	'X_cadre_orientation_proposee',	'70'),
(1,	'titre_voeux_orientation',	'Voeux'),
(1,	'titre_orientation_proposee',	'Orientation proposée'),
(1,	'titre_avis_orientation_proposee',	'Commentaire');

DROP TABLE IF EXISTS `model_bulletin`;
CREATE TABLE `model_bulletin` (
  `id_model_bulletin` int(11) NOT NULL AUTO_INCREMENT,
  `nom_model_bulletin` varchar(100) NOT NULL DEFAULT '',
  `active_bloc_datation` decimal(4,0) NOT NULL DEFAULT '0',
  `active_bloc_eleve` tinyint(4) NOT NULL DEFAULT '0',
  `active_bloc_adresse_parent` tinyint(4) NOT NULL DEFAULT '0',
  `active_bloc_absence` tinyint(4) NOT NULL DEFAULT '0',
  `active_bloc_note_appreciation` tinyint(4) NOT NULL DEFAULT '0',
  `active_bloc_avis_conseil` tinyint(4) NOT NULL DEFAULT '0',
  `active_bloc_chef` tinyint(4) NOT NULL DEFAULT '0',
  `active_photo` tinyint(4) NOT NULL DEFAULT '0',
  `active_coef_moyenne` tinyint(4) NOT NULL DEFAULT '0',
  `active_nombre_note` tinyint(4) NOT NULL DEFAULT '0',
  `active_nombre_note_case` tinyint(4) NOT NULL DEFAULT '0',
  `active_moyenne` tinyint(4) NOT NULL DEFAULT '0',
  `active_moyenne_eleve` tinyint(4) NOT NULL DEFAULT '0',
  `active_moyenne_classe` tinyint(4) NOT NULL DEFAULT '0',
  `active_moyenne_min` tinyint(4) NOT NULL DEFAULT '0',
  `active_moyenne_max` tinyint(4) NOT NULL DEFAULT '0',
  `active_regroupement_cote` tinyint(4) NOT NULL DEFAULT '0',
  `active_entete_regroupement` tinyint(4) NOT NULL DEFAULT '0',
  `active_moyenne_regroupement` tinyint(4) NOT NULL DEFAULT '0',
  `active_rang` tinyint(4) NOT NULL DEFAULT '0',
  `active_graphique_niveau` tinyint(4) NOT NULL DEFAULT '0',
  `active_appreciation` tinyint(4) NOT NULL DEFAULT '0',
  `affiche_doublement` tinyint(4) NOT NULL DEFAULT '0',
  `affiche_date_naissance` tinyint(4) NOT NULL DEFAULT '0',
  `affiche_dp` tinyint(4) NOT NULL DEFAULT '0',
  `affiche_nom_court` tinyint(4) NOT NULL DEFAULT '0',
  `affiche_effectif_classe` tinyint(4) NOT NULL DEFAULT '0',
  `affiche_numero_impression` tinyint(4) NOT NULL DEFAULT '0',
  `caractere_utilse` varchar(20) NOT NULL DEFAULT '',
  `X_parent` float NOT NULL DEFAULT '0',
  `Y_parent` float NOT NULL DEFAULT '0',
  `X_eleve` float NOT NULL DEFAULT '0',
  `Y_eleve` float NOT NULL DEFAULT '0',
  `cadre_eleve` tinyint(4) NOT NULL DEFAULT '0',
  `X_datation_bul` float NOT NULL DEFAULT '0',
  `Y_datation_bul` float NOT NULL DEFAULT '0',
  `cadre_datation_bul` tinyint(4) NOT NULL DEFAULT '0',
  `hauteur_info_categorie` float NOT NULL DEFAULT '0',
  `X_note_app` float NOT NULL DEFAULT '0',
  `Y_note_app` float NOT NULL DEFAULT '0',
  `longeur_note_app` float NOT NULL DEFAULT '0',
  `hauteur_note_app` float NOT NULL DEFAULT '0',
  `largeur_coef_moyenne` float NOT NULL DEFAULT '0',
  `largeur_nombre_note` float NOT NULL DEFAULT '0',
  `largeur_d_une_moyenne` float NOT NULL DEFAULT '0',
  `largeur_niveau` float NOT NULL DEFAULT '0',
  `largeur_rang` float NOT NULL DEFAULT '0',
  `X_absence` float NOT NULL DEFAULT '0',
  `Y_absence` float NOT NULL DEFAULT '0',
  `hauteur_entete_moyenne_general` float NOT NULL DEFAULT '0',
  `X_avis_cons` float NOT NULL DEFAULT '0',
  `Y_avis_cons` float NOT NULL DEFAULT '0',
  `longeur_avis_cons` float NOT NULL DEFAULT '0',
  `hauteur_avis_cons` float NOT NULL DEFAULT '0',
  `cadre_avis_cons` tinyint(4) NOT NULL DEFAULT '0',
  `X_sign_chef` float NOT NULL DEFAULT '0',
  `Y_sign_chef` float NOT NULL DEFAULT '0',
  `longeur_sign_chef` float NOT NULL DEFAULT '0',
  `hauteur_sign_chef` float NOT NULL DEFAULT '0',
  `cadre_sign_chef` tinyint(4) NOT NULL DEFAULT '0',
  `affiche_filigrame` tinyint(4) NOT NULL DEFAULT '0',
  `texte_filigrame` varchar(100) NOT NULL DEFAULT '',
  `affiche_logo_etab` tinyint(4) NOT NULL DEFAULT '0',
  `entente_mel` tinyint(4) NOT NULL DEFAULT '0',
  `entente_tel` tinyint(4) NOT NULL DEFAULT '0',
  `entente_fax` tinyint(4) NOT NULL DEFAULT '0',
  `L_max_logo` tinyint(4) NOT NULL DEFAULT '0',
  `H_max_logo` tinyint(4) NOT NULL DEFAULT '0',
  `toute_moyenne_meme_col` tinyint(4) NOT NULL DEFAULT '0',
  `active_reperage_eleve` tinyint(4) NOT NULL DEFAULT '0',
  `couleur_reperage_eleve1` smallint(6) NOT NULL DEFAULT '0',
  `couleur_reperage_eleve2` smallint(6) NOT NULL DEFAULT '0',
  `couleur_reperage_eleve3` smallint(6) NOT NULL DEFAULT '0',
  `couleur_categorie_entete` tinyint(4) NOT NULL DEFAULT '0',
  `couleur_categorie_entete1` smallint(6) NOT NULL DEFAULT '0',
  `couleur_categorie_entete2` smallint(6) NOT NULL DEFAULT '0',
  `couleur_categorie_entete3` smallint(6) NOT NULL DEFAULT '0',
  `couleur_categorie_cote` tinyint(4) NOT NULL DEFAULT '0',
  `couleur_categorie_cote1` smallint(6) NOT NULL DEFAULT '0',
  `couleur_categorie_cote2` smallint(6) NOT NULL DEFAULT '0',
  `couleur_categorie_cote3` smallint(6) NOT NULL DEFAULT '0',
  `couleur_moy_general` tinyint(4) NOT NULL DEFAULT '0',
  `couleur_moy_general1` smallint(6) NOT NULL DEFAULT '0',
  `couleur_moy_general2` smallint(6) NOT NULL DEFAULT '0',
  `couleur_moy_general3` smallint(6) NOT NULL DEFAULT '0',
  `titre_entete_matiere` varchar(50) NOT NULL DEFAULT '',
  `titre_entete_coef` varchar(20) NOT NULL DEFAULT '',
  `titre_entete_nbnote` varchar(20) NOT NULL DEFAULT '',
  `titre_entete_rang` varchar(20) NOT NULL DEFAULT '',
  `titre_entete_appreciation` varchar(50) NOT NULL DEFAULT '',
  `active_coef_sousmoyene` tinyint(4) NOT NULL DEFAULT '0',
  `arrondie_choix` float NOT NULL DEFAULT '0',
  `nb_chiffre_virgule` tinyint(4) NOT NULL DEFAULT '0',
  `chiffre_avec_zero` tinyint(4) NOT NULL DEFAULT '0',
  `autorise_sous_matiere` tinyint(4) NOT NULL DEFAULT '0',
  `affichage_haut_responsable` tinyint(4) NOT NULL DEFAULT '0',
  `entete_model_bulletin` tinyint(4) NOT NULL DEFAULT '0',
  `ordre_entete_model_bulletin` tinyint(4) NOT NULL DEFAULT '0',
  `affiche_etab_origine` tinyint(4) NOT NULL DEFAULT '0',
  `imprime_pour` tinyint(4) NOT NULL DEFAULT '0',
  `largeur_matiere` float NOT NULL DEFAULT '0',
  `nom_etab_gras` tinyint(4) NOT NULL DEFAULT '0',
  `taille_texte_date_edition` float NOT NULL,
  `taille_texte_matiere` float NOT NULL,
  `active_moyenne_general` tinyint(4) NOT NULL,
  `titre_bloc_avis_conseil` varchar(50) NOT NULL,
  `taille_titre_bloc_avis_conseil` float NOT NULL,
  `taille_profprincipal_bloc_avis_conseil` float NOT NULL,
  `affiche_fonction_chef` tinyint(4) NOT NULL,
  `taille_texte_fonction_chef` float NOT NULL,
  `taille_texte_identitee_chef` float NOT NULL,
  `tel_image` varchar(20) NOT NULL,
  `tel_texte` varchar(20) NOT NULL,
  `fax_image` varchar(20) NOT NULL,
  `fax_texte` varchar(20) NOT NULL,
  `courrier_image` varchar(20) NOT NULL,
  `courrier_texte` varchar(20) NOT NULL,
  `largeur_bloc_eleve` float NOT NULL,
  `hauteur_bloc_eleve` float NOT NULL,
  `largeur_bloc_adresse` float NOT NULL,
  `hauteur_bloc_adresse` float NOT NULL,
  `largeur_bloc_datation` float NOT NULL,
  `hauteur_bloc_datation` float NOT NULL,
  `taille_texte_classe` float NOT NULL,
  `type_texte_classe` varchar(1) NOT NULL,
  `taille_texte_annee` float NOT NULL,
  `type_texte_annee` varchar(1) NOT NULL,
  `taille_texte_periode` float NOT NULL,
  `type_texte_periode` varchar(1) NOT NULL,
  `taille_texte_categorie_cote` float NOT NULL,
  `taille_texte_categorie` float NOT NULL,
  `type_texte_date_datation` varchar(1) NOT NULL,
  `cadre_adresse` tinyint(4) NOT NULL,
  `centrage_logo` tinyint(4) NOT NULL DEFAULT '0',
  `Y_centre_logo` float NOT NULL DEFAULT '18',
  `ajout_cadre_blanc_photo` tinyint(4) NOT NULL DEFAULT '0',
  `affiche_moyenne_mini_general` tinyint(4) NOT NULL DEFAULT '1',
  `affiche_moyenne_maxi_general` tinyint(4) NOT NULL DEFAULT '1',
  `affiche_date_edition` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_model_bulletin`)
) TYPE=MyISAM;

INSERT INTO `model_bulletin` (`id_model_bulletin`, `nom_model_bulletin`, `active_bloc_datation`, `active_bloc_eleve`, `active_bloc_adresse_parent`, `active_bloc_absence`, `active_bloc_note_appreciation`, `active_bloc_avis_conseil`, `active_bloc_chef`, `active_photo`, `active_coef_moyenne`, `active_nombre_note`, `active_nombre_note_case`, `active_moyenne`, `active_moyenne_eleve`, `active_moyenne_classe`, `active_moyenne_min`, `active_moyenne_max`, `active_regroupement_cote`, `active_entete_regroupement`, `active_moyenne_regroupement`, `active_rang`, `active_graphique_niveau`, `active_appreciation`, `affiche_doublement`, `affiche_date_naissance`, `affiche_dp`, `affiche_nom_court`, `affiche_effectif_classe`, `affiche_numero_impression`, `caractere_utilse`, `X_parent`, `Y_parent`, `X_eleve`, `Y_eleve`, `cadre_eleve`, `X_datation_bul`, `Y_datation_bul`, `cadre_datation_bul`, `hauteur_info_categorie`, `X_note_app`, `Y_note_app`, `longeur_note_app`, `hauteur_note_app`, `largeur_coef_moyenne`, `largeur_nombre_note`, `largeur_d_une_moyenne`, `largeur_niveau`, `largeur_rang`, `X_absence`, `Y_absence`, `hauteur_entete_moyenne_general`, `X_avis_cons`, `Y_avis_cons`, `longeur_avis_cons`, `hauteur_avis_cons`, `cadre_avis_cons`, `X_sign_chef`, `Y_sign_chef`, `longeur_sign_chef`, `hauteur_sign_chef`, `cadre_sign_chef`, `affiche_filigrame`, `texte_filigrame`, `affiche_logo_etab`, `entente_mel`, `entente_tel`, `entente_fax`, `L_max_logo`, `H_max_logo`, `toute_moyenne_meme_col`, `active_reperage_eleve`, `couleur_reperage_eleve1`, `couleur_reperage_eleve2`, `couleur_reperage_eleve3`, `couleur_categorie_entete`, `couleur_categorie_entete1`, `couleur_categorie_entete2`, `couleur_categorie_entete3`, `couleur_categorie_cote`, `couleur_categorie_cote1`, `couleur_categorie_cote2`, `couleur_categorie_cote3`, `couleur_moy_general`, `couleur_moy_general1`, `couleur_moy_general2`, `couleur_moy_general3`, `titre_entete_matiere`, `titre_entete_coef`, `titre_entete_nbnote`, `titre_entete_rang`, `titre_entete_appreciation`, `active_coef_sousmoyene`, `arrondie_choix`, `nb_chiffre_virgule`, `chiffre_avec_zero`, `autorise_sous_matiere`, `affichage_haut_responsable`, `entete_model_bulletin`, `ordre_entete_model_bulletin`, `affiche_etab_origine`, `imprime_pour`, `largeur_matiere`, `nom_etab_gras`, `taille_texte_date_edition`, `taille_texte_matiere`, `active_moyenne_general`, `titre_bloc_avis_conseil`, `taille_titre_bloc_avis_conseil`, `taille_profprincipal_bloc_avis_conseil`, `affiche_fonction_chef`, `taille_texte_fonction_chef`, `taille_texte_identitee_chef`, `tel_image`, `tel_texte`, `fax_image`, `fax_texte`, `courrier_image`, `courrier_texte`, `largeur_bloc_eleve`, `hauteur_bloc_eleve`, `largeur_bloc_adresse`, `hauteur_bloc_adresse`, `largeur_bloc_datation`, `hauteur_bloc_datation`, `taille_texte_classe`, `type_texte_classe`, `taille_texte_annee`, `type_texte_annee`, `taille_texte_periode`, `type_texte_periode`, `taille_texte_categorie_cote`, `taille_texte_categorie`, `type_texte_date_datation`, `cadre_adresse`, `centrage_logo`, `Y_centre_logo`, `ajout_cadre_blanc_photo`, `affiche_moyenne_mini_general`, `affiche_moyenne_maxi_general`, `affiche_date_edition`) VALUES
(1,	'Standard',	1,	1,	1,	1,	1,	1,	1,	0,	0,	0,	0,	1,	1,	1,	1,	1,	0,	0,	0,	0,	0,	1,	1,	1,	1,	0,	0,	0,	'Arial',	110,	40,	5,	40,	1,	110,	5,	1,	5,	5,	72,	200,	175,	8,	8,	10,	18,	5,	5,	246.3,	5,	5,	250,	130,	37,	1,	138,	250,	67,	37,	0,	0,	'DUPLICATA INTERNET',	1,	1,	1,	1,	75,	75,	0,	1,	255,	255,	207,	1,	239,	239,	239,	1,	239,	239,	239,	1,	239,	239,	239,	'Matière',	'coef.',	'nb. n.',	'rang',	'Appréciation / Conseils',	0,	0.01,	2,	0,	1,	1,	1,	1,	0,	0,	40,	0,	0,	0,	0,	'',	0,	0,	0,	0,	0,	'',	'',	'',	'',	'',	'',	0,	0,	0,	0,	0,	0,	0,	'',	0,	'',	0,	'',	0,	0,	'',	0,	0,	18,	0,	1,	1,	1),
(2,	'Standard avec photo',	1,	1,	1,	1,	1,	1,	1,	1,	0,	0,	0,	1,	1,	1,	1,	1,	0,	0,	0,	0,	0,	1,	1,	1,	1,	0,	0,	0,	'Arial',	110,	40,	5,	40,	1,	110,	5,	1,	5,	5,	72,	200,	175,	8,	8,	10,	18,	5,	5,	246.3,	5,	5,	250,	130,	37,	1,	138,	250,	67,	37,	0,	0,	'DUPLICATA INTERNET',	1,	1,	1,	1,	75,	75,	0,	1,	255,	255,	207,	1,	239,	239,	239,	1,	239,	239,	239,	1,	239,	239,	239,	'Matière',	'coef.',	'nb. n.',	'rang',	'Appréciation / Conseils',	0,	0,	2,	0,	1,	1,	1,	1,	0,	0,	40,	0,	0,	0,	0,	'',	0,	0,	0,	0,	0,	'',	'',	'',	'',	'',	'',	0,	0,	0,	0,	0,	0,	0,	'',	0,	'',	0,	'',	0,	0,	'',	0,	0,	18,	0,	1,	1,	1),
(3,	'Affiche tout',	1,	1,	1,	1,	1,	1,	1,	1,	1,	1,	1,	1,	1,	1,	1,	1,	1,	1,	1,	1,	1,	1,	1,	1,	1,	1,	1,	1,	'Arial',	110,	40,	5,	40,	1,	110,	5,	1,	5,	5,	72,	200,	175,	8,	8,	10,	16.5,	6.5,	5,	246.3,	5,	5,	250,	130,	37,	1,	138,	250,	67,	37,	1,	0,	'DUPLICATA INTERNET',	1,	1,	1,	1,	75,	75,	1,	1,	255,	255,	207,	1,	239,	239,	239,	1,	239,	239,	239,	1,	239,	239,	239,	'Matière',	'coef.',	'nb. n.',	'rang',	'Appréciation / Conseils',	1,	0.01,	2,	0,	1,	1,	2,	1,	1,	1,	40,	0,	0,	0,	0,	'',	0,	0,	0,	0,	0,	'',	'',	'',	'',	'',	'',	0,	0,	0,	0,	0,	0,	0,	'',	0,	'',	0,	'',	0,	0,	'',	0,	0,	18,	0,	1,	1,	1);

DROP TABLE IF EXISTS `mod_actions_action`;
CREATE TABLE `mod_actions_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_categorie` int(11) NOT NULL DEFAULT '0',
  `nom` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `date_action` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `mod_actions_categories`;
CREATE TABLE `mod_actions_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `mod_actions_gestionnaires`;
CREATE TABLE `mod_actions_gestionnaires` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_categorie` int(11) NOT NULL DEFAULT '0',
  `login_user` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `mod_actions_inscriptions`;
CREATE TABLE `mod_actions_inscriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_action` int(11) NOT NULL DEFAULT '0',
  `login_ele` varchar(50) NOT NULL DEFAULT '',
  `presence` varchar(10) NOT NULL DEFAULT '',
  `date_pointage` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `login_pointage` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `mod_alerte_divers`;
CREATE TABLE `mod_alerte_divers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `mod_listes_perso_colonnes`;
CREATE TABLE `mod_listes_perso_colonnes` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant unique',
  `id_def` int(11) NOT NULL COMMENT 'identifiant de la liste',
  `titre` varchar(30) NOT NULL DEFAULT '' COMMENT 'Titre de la colonne',
  `placement` int(11) NOT NULL COMMENT 'Place de la colonne dans le tableau',
  PRIMARY KEY (`id`)
) TYPE=MyISAM COMMENT='Liste personnelle : Définition des colonnes';


DROP TABLE IF EXISTS `mod_listes_perso_contenus`;
CREATE TABLE `mod_listes_perso_contenus` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant unique',
  `id_def` int(11) NOT NULL COMMENT 'identifiant de la liste',
  `login` varchar(50) NOT NULL DEFAULT '' COMMENT 'identifiant des élèves',
  `colonne` int(11) NOT NULL COMMENT 'identifiant de la colonne',
  `contenu` varchar(50) NOT NULL DEFAULT '' COMMENT 'contenu de la cellule',
  PRIMARY KEY (`id`),
  KEY `contenu` (`id_def`,`login`,`contenu`)
) TYPE=MyISAM COMMENT='Liste personnelle : contenu du tableau';


DROP TABLE IF EXISTS `mod_listes_perso_definition`;
CREATE TABLE `mod_listes_perso_definition` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant unique',
  `nom` varchar(50) NOT NULL DEFAULT '' COMMENT 'Nom de la liste',
  `sexe` tinyint(1) DEFAULT '1' COMMENT 'Affichage ou non du sexe des élèves ',
  `classe` tinyint(1) DEFAULT '1' COMMENT 'Affichage ou non de la classe des élèves',
  `photo` tinyint(1) DEFAULT '1' COMMENT 'Affichage ou non de la photo',
  `proprietaire` varchar(50) NOT NULL COMMENT 'Nom du créateur de la liste',
  PRIMARY KEY (`id`)
) TYPE=MyISAM COMMENT='Liste personnelle : création';


DROP TABLE IF EXISTS `mod_listes_perso_eleves`;
CREATE TABLE `mod_listes_perso_eleves` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant unique',
  `id_def` int(11) NOT NULL COMMENT 'identifiant de la liste',
  `login` varchar(50) NOT NULL DEFAULT '' COMMENT 'identifiant des élèves',
  PRIMARY KEY (`id`),
  KEY `combinaison` (`id_def`,`login`)
) TYPE=MyISAM COMMENT='Liste personnelle : élèves de la liste';


DROP TABLE IF EXISTS `nomenclatures`;
CREATE TABLE `nomenclatures` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `nomenclatures_valeurs`;
CREATE TABLE `nomenclatures_valeurs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `valeur` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

INSERT INTO `nomenclatures_valeurs` (`id`, `type`, `code`, `nom`, `valeur`) VALUES
(1,	'enseignement_complement',	'LCA',	'LCA',	'Langues et cultures de l\'Antiquité'),
(2,	'enseignement_complement',	'LCR',	'LCR',	'Langue et culture régionale'),
(3,	'enseignement_complement',	'PRO',	'PRO',	'Découverte professionnelle'),
(4,	'enseignement_complement',	'LSF',	'LSF',	'Langue des signes française'),
(5,	'enseignement_complement',	'LVE',	'LVE',	'Langue vivante étrangère'),
(6,	'enseignement_complement',	'CHK',	'CHK',	'Chant Choral'),
(7,	'enseignement_complement',	'LCE',	'LCE',	'Langues et cultures européennes'),
(8,	'domaine_socle',	'CPD_FRA',	'CPD_FRA',	'Comprendre, s\'exprimer en utilisant la langue française à l\'oral et à l\'écrit'),
(9,	'domaine_socle',	'CPD_ETR',	'CPD_ETR',	'Comprendre, s\'exprimer en utilisant une langue étrangère et, le cas échéant, une langue régionale'),
(10,	'domaine_socle',	'CPD_SCI',	'CPD_SCI',	'Comprendre, s\'exprimer en utilisant les langages mathématiques, scientifiques et informatiques'),
(11,	'domaine_socle',	'CPD_ART',	'CPD_ART',	'Comprendre, s\'exprimer en utilisant les langages des arts et du corps'),
(12,	'domaine_socle',	'MET_APP',	'MET_APP',	'Les méthodes et outils pour apprendre'),
(13,	'domaine_socle',	'FRM_CIT',	'FRM_CIT',	'La formation de la personne et du citoyen'),
(14,	'domaine_socle',	'SYS_NAT',	'SYS_NAT',	'Les systèmes naturels et les systèmes techniques'),
(15,	'domaine_socle',	'REP_MND',	'REP_MND',	'Les représentations du monde et l\'activité humaine');

DROP TABLE IF EXISTS `nomenclature_modalites_election`;
CREATE TABLE `nomenclature_modalites_election` (
  `code_modalite_elect` varchar(6) NOT NULL,
  `libelle_court` varchar(50) NOT NULL,
  `libelle_long` varchar(250) NOT NULL,
  PRIMARY KEY (`code_modalite_elect`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `notanet`;
CREATE TABLE `notanet` (
  `login` varchar(50) NOT NULL DEFAULT '',
  `ine` text NOT NULL,
  `id_mat` int(4) NOT NULL,
  `notanet_mat` varchar(255) NOT NULL,
  `matiere` varchar(50) NOT NULL,
  `note` varchar(4) NOT NULL DEFAULT '',
  `note_notanet` varchar(4) NOT NULL,
  `id_classe` smallint(6) NOT NULL DEFAULT '0'
) TYPE=MyISAM;


DROP TABLE IF EXISTS `notanet_app`;
CREATE TABLE `notanet_app` (
  `login` varchar(50) NOT NULL,
  `id_mat` int(4) NOT NULL,
  `matiere` varchar(50) NOT NULL,
  `appreciation` text NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `notanet_avis`;
CREATE TABLE `notanet_avis` (
  `login` varchar(50) NOT NULL,
  `favorable` enum('O','N') NOT NULL,
  `avis` text NOT NULL,
  PRIMARY KEY (`login`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `notanet_corresp`;
CREATE TABLE `notanet_corresp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_brevet` tinyint(4) NOT NULL,
  `id_mat` int(4) NOT NULL,
  `notanet_mat` varchar(255) NOT NULL DEFAULT '',
  `matiere` varchar(50) NOT NULL DEFAULT '',
  `statut` enum('imposee','optionnelle','non dispensee dans l etablissement') NOT NULL DEFAULT 'imposee',
  `mode` varchar(20) NOT NULL DEFAULT 'extract_moy',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `notanet_ele_type`;
CREATE TABLE `notanet_ele_type` (
  `login` varchar(50) NOT NULL,
  `type_brevet` tinyint(4) NOT NULL,
  PRIMARY KEY (`login`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `notanet_saisie`;
CREATE TABLE `notanet_saisie` (
  `login` varchar(50) NOT NULL,
  `id_mat` int(4) NOT NULL,
  `matiere` varchar(50) DEFAULT NULL,
  `note` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`login`,`id_mat`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `notanet_socles`;
CREATE TABLE `notanet_socles` (
  `login` varchar(50) NOT NULL,
  `b2i` enum('MS','ME','MN','AB') NOT NULL,
  `a2` enum('MS','ME','AB') NOT NULL,
  `lv` varchar(50) NOT NULL,
  PRIMARY KEY (`login`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `notanet_verrou`;
CREATE TABLE `notanet_verrou` (
  `id_classe` smallint(6) NOT NULL,
  `type_brevet` tinyint(4) NOT NULL,
  `verrouillage` char(1) NOT NULL
) TYPE=MyISAM;


DROP TABLE IF EXISTS `o_avis`;
CREATE TABLE `o_avis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `avis` varchar(255) NOT NULL,
  `saisi_par` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `o_mef`;
CREATE TABLE `o_mef` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mef_code` varchar(50) NOT NULL,
  `affichage` char(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mef_code_affichage` (`mef_code`,`affichage`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `o_orientations`;
CREATE TABLE `o_orientations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `id_orientation` int(11) NOT NULL,
  `rang` int(3) NOT NULL,
  `commentaire` text NOT NULL,
  `date_orientation` datetime NOT NULL,
  `saisi_par` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login_rang` (`login`,`rang`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `o_orientations_base`;
CREATE TABLE `o_orientations_base` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `o_orientations_mefs`;
CREATE TABLE `o_orientations_mefs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_orientation` int(11) NOT NULL,
  `mef_code` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `o_voeux`;
CREATE TABLE `o_voeux` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `id_orientation` int(11) NOT NULL,
  `rang` int(3) NOT NULL,
  `date_voeu` datetime NOT NULL,
  `commentaire` varchar(255) NOT NULL,
  `saisi_par` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login_rang` (`login`,`rang`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `pays`;
CREATE TABLE `pays` (
  `code_pays` varchar(50) NOT NULL,
  `nom_pays` varchar(255) NOT NULL,
  PRIMARY KEY (`code_pays`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `periodes`;
CREATE TABLE `periodes` (
  `nom_periode` varchar(50) NOT NULL DEFAULT '',
  `num_periode` int(11) NOT NULL DEFAULT '0',
  `verouiller` char(1) NOT NULL DEFAULT '',
  `id_classe` int(11) NOT NULL DEFAULT '0',
  `date_verrouillage` timestamp NOT NULL,
  `date_fin` timestamp NULL DEFAULT NULL,
  `date_conseil_classe` timestamp NOT NULL,
  PRIMARY KEY (`num_periode`,`id_classe`),
  KEY `id_classe` (`id_classe`)
) TYPE=MyISAM;

INSERT INTO `periodes` (`nom_periode`, `num_periode`, `verouiller`, `id_classe`, `date_verrouillage`, `date_fin`, `date_conseil_classe`) VALUES
('trimestre 1',	1,	'O',	1,	'2020-05-01 23:14:11',	'2019-08-31 22:00:00',	'2019-11-29 23:00:00'),
('trimestre 2',	2,	'N',	1,	'0000-00-00 00:00:00',	'2019-11-30 23:00:00',	'2020-02-28 23:00:00'),
('trimestre 3',	3,	'N',	1,	'0000-00-00 00:00:00',	'2020-02-29 23:00:00',	'2020-05-29 22:00:00');

DROP TABLE IF EXISTS `plugins`;
CREATE TABLE `plugins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `repertoire` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `ouvert` char(1) DEFAULT 'n',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `plugins_autorisations`;
CREATE TABLE `plugins_autorisations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_id` int(11) NOT NULL,
  `fichier` varchar(100) NOT NULL,
  `user_statut` varchar(50) NOT NULL,
  `auth` char(1) DEFAULT 'n',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `plugins_menus`;
CREATE TABLE `plugins_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_id` int(11) NOT NULL,
  `user_statut` varchar(50) NOT NULL,
  `titre_item` varchar(255) NOT NULL,
  `lien_item` varchar(255) NOT NULL,
  `description_item` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `preferences`;
CREATE TABLE `preferences` (
  `login` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  KEY `login_name` (`login`,`name`)
) TYPE=MyISAM;

INSERT INTO `preferences` (`login`, `name`, `value`) VALUES
('prof_a',	'accueil_simpl',	'n'),
('prof_a',	'accueil_infobulles',	'n'),
('prof_a',	'accueil_ct',	'y'),
('prof_a',	'accueil_cn',	'y'),
('prof_a',	'accueil_bull',	'n'),
('prof_a',	'accueil_visu',	'n'),
('prof_a',	'accueil_trombino',	'n'),
('prof_a',	'accueil_liste_pdf',	'y'),
('prof_a',	'accueil_simpl_id_groupe_order_10',	'01'),
('prof_a',	'accueil_simpl_id_groupe_order_8',	'02'),
('prof_a',	'accueil_simpl_id_groupe_order_7',	'03'),
('prof_a',	'accueil_simpl_id_groupe_order_1',	'04'),
('prof_a',	'cn_mode_saisie',	'0'),
('prof_a',	'vtn_couleur_texte0',	'red'),
('prof_a',	'vtn_couleur_texte1',	'orangered'),
('prof_a',	'vtn_couleur_texte2',	'green'),
('prof_a',	'vtn_couleur_cellule0',	''),
('prof_a',	'vtn_couleur_cellule1',	''),
('prof_a',	'vtn_couleur_cellule2',	''),
('prof_a',	'vtn_borne_couleur0',	'6.7'),
('prof_a',	'vtn_borne_couleur1',	'13.4'),
('prof_a',	'vtn_borne_couleur2',	'20'),
('prof_a',	'vtn_pref_num_periode',	'1'),
('prof_a',	'vtn_pref_larg_tab',	'680'),
('prof_a',	'vtn_pref_bord',	'1'),
('prof_a',	'vtn_pref_couleur_alterne',	'y'),
('prof_a',	'vtn_pref_aff_abs',	'y'),
('prof_a',	'vtn_pref_aff_reg',	'y'),
('prof_a',	'vtn_pref_aff_doub',	'y'),
('prof_a',	'vtn_pref_aff_date_naiss',	'y'),
('prof_a',	'vtn_pref_aff_rang',	'y'),
('prof_a',	'vtn_pref_avec_moy_gen_periodes_precedentes',	'n'),
('prof_a',	'vtn_pref_aff_moy_gen',	'y'),
('prof_a',	'vtn_pref_aff_moy_cat',	'y'),
('prof_a',	'vtn_pref_coloriser_resultats',	'y'),
('prof_b',	'cn_mode_saisie',	'0'),
('prof_c',	'cn_mode_saisie',	'0'),
('scolarite',	'rn_couleurs_alternees',	'y'),
('prof_b',	'ElementsProgrammesQuePerso',	''),
('prof_b',	'ElementsProgrammesQueMat',	''),
('prof_b',	'ElementsProgrammesQueNiveau',	''),
('prof_c',	'ElementsProgrammesQuePerso',	''),
('prof_c',	'ElementsProgrammesQueMat',	''),
('prof_c',	'ElementsProgrammesQueNiveau',	''),
('scolarite',	'utiliserMenuBarre',	'yes'),
('A.Mustapha',	'bull_simp_pref_couleur_alterne',	'n'),
('A.Mustapha',	'bull_simp_larg_tab',	'680'),
('A.Mustapha',	'bull_simp_larg_col1',	'120'),
('A.Mustapha',	'bull_simp_larg_col2',	'38'),
('A.Mustapha',	'bull_simp_larg_col3',	'38'),
('A.Mustapha',	'bull_simp_larg_col4',	'20'),
('A.Mustapha',	'rn_couleurs_alternees',	'y'),
('scolarite',	'vtn_couleur_texte0',	'red'),
('scolarite',	'vtn_couleur_texte1',	'orangered'),
('scolarite',	'vtn_couleur_texte2',	'green'),
('scolarite',	'vtn_couleur_cellule0',	''),
('scolarite',	'vtn_couleur_cellule1',	''),
('scolarite',	'vtn_couleur_cellule2',	''),
('scolarite',	'vtn_borne_couleur0',	'6.7'),
('scolarite',	'vtn_borne_couleur1',	'13.4'),
('scolarite',	'vtn_borne_couleur2',	'20'),
('scolarite',	'vtn_pref_num_periode',	'1'),
('scolarite',	'vtn_pref_larg_tab',	'680'),
('scolarite',	'vtn_pref_bord',	'1'),
('scolarite',	'vtn_pref_couleur_alterne',	'y'),
('scolarite',	'vtn_pref_aff_abs',	'y'),
('scolarite',	'vtn_pref_aff_reg',	'y'),
('scolarite',	'vtn_pref_aff_doub',	'y'),
('scolarite',	'vtn_pref_aff_rang',	'y'),
('scolarite',	'vtn_pref_coloriser_resultats',	'y'),
('scolarite',	'visu_toutes_notes_forcer_h_cell_pdf',	'y'),
('scolarite',	'visu_toutes_notes_h_cell_pdf',	'10'),
('scolarite',	'bull_simp_pref_couleur_alterne',	'n'),
('scolarite',	'bull_simp_larg_tab',	'680'),
('scolarite',	'bull_simp_larg_col1',	'120'),
('scolarite',	'bull_simp_larg_col2',	'38'),
('scolarite',	'bull_simp_larg_col3',	'38'),
('scolarite',	'bull_simp_larg_col4',	'20');

DROP TABLE IF EXISTS `ref_wiki`;
CREATE TABLE `ref_wiki` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ref` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ref` (`ref`)
) TYPE=MyISAM;

INSERT INTO `ref_wiki` (`id`, `ref`, `url`) VALUES
(1,	'enseignement_invisible',	'http://www.sylogix.org/projects/gepi/wiki/Enseignement_invisible');

DROP TABLE IF EXISTS `responsables`;
CREATE TABLE `responsables` (
  `ereno` varchar(10) NOT NULL DEFAULT '',
  `nom1` varchar(50) NOT NULL DEFAULT '',
  `prenom1` varchar(50) NOT NULL DEFAULT '',
  `adr1` varchar(100) NOT NULL DEFAULT '',
  `adr1_comp` varchar(100) NOT NULL DEFAULT '',
  `commune1` varchar(50) NOT NULL DEFAULT '',
  `cp1` varchar(6) NOT NULL DEFAULT '',
  `nom2` varchar(50) NOT NULL DEFAULT '',
  `prenom2` varchar(50) NOT NULL DEFAULT '',
  `adr2` varchar(100) NOT NULL DEFAULT '',
  `adr2_comp` varchar(100) NOT NULL DEFAULT '',
  `commune2` varchar(50) NOT NULL DEFAULT '',
  `cp2` varchar(6) NOT NULL DEFAULT '',
  PRIMARY KEY (`ereno`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `responsables2`;
CREATE TABLE `responsables2` (
  `ele_id` varchar(10) NOT NULL,
  `pers_id` varchar(10) NOT NULL,
  `resp_legal` varchar(1) NOT NULL,
  `pers_contact` varchar(1) NOT NULL,
  `acces_sp` varchar(1) NOT NULL,
  `envoi_bulletin` char(1) NOT NULL DEFAULT 'n' COMMENT 'Envoi des bulletins pour les resp_legal=0',
  `niveau_responsabilite` varchar(10) NOT NULL DEFAULT '',
  `code_parente` varchar(10) NOT NULL DEFAULT '',
  `paie_frais_scolaires` varchar(10) NOT NULL DEFAULT '',
  KEY `pers_id` (`pers_id`),
  KEY `ele_id` (`ele_id`),
  KEY `resp_legal` (`resp_legal`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `resp_adr`;
CREATE TABLE `resp_adr` (
  `adr_id` varchar(10) NOT NULL,
  `adr1` varchar(100) NOT NULL,
  `adr2` varchar(100) NOT NULL,
  `adr3` varchar(100) NOT NULL,
  `adr4` varchar(100) NOT NULL,
  `cp` varchar(6) NOT NULL,
  `pays` varchar(50) NOT NULL,
  `commune` varchar(50) NOT NULL,
  PRIMARY KEY (`adr_id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `resp_pers`;
CREATE TABLE `resp_pers` (
  `pers_id` varchar(10) NOT NULL,
  `login` varchar(50) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `civilite` varchar(5) NOT NULL,
  `tel_pers` varchar(255) NOT NULL,
  `tel_port` varchar(255) NOT NULL,
  `tel_prof` varchar(255) NOT NULL,
  `mel` varchar(100) NOT NULL,
  `adr_id` varchar(10) NOT NULL,
  PRIMARY KEY (`pers_id`),
  KEY `login` (`login`),
  KEY `adr_id` (`adr_id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `rss_users`;
CREATE TABLE `rss_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_login` varchar(30) NOT NULL,
  `user_uri` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `salle_cours`;
CREATE TABLE `salle_cours` (
  `id_salle` int(3) NOT NULL AUTO_INCREMENT,
  `numero_salle` varchar(10) NOT NULL,
  `nom_salle` varchar(50) NOT NULL,
  PRIMARY KEY (`id_salle`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `sconet_ele_options`;
CREATE TABLE `sconet_ele_options` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ele_id` varchar(10) NOT NULL DEFAULT '',
  `code_matiere` varchar(255) NOT NULL DEFAULT '',
  `code_modalite_elect` char(1) NOT NULL DEFAULT '',
  `num_option` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `setting`;
CREATE TABLE `setting` (
  `NAME` varchar(255) NOT NULL DEFAULT '',
  `VALUE` text NOT NULL,
  PRIMARY KEY (`NAME`)
) TYPE=MyISAM;

INSERT INTO `setting` (`NAME`, `VALUE`) VALUES
('version',	'1.7.4'),
('sessionMaxLength',	'30'),
('Impression',	'<center><p class = \"grand\">Gestion des Elèves Par Internet</p></center>\r\n<br />\r\n<p class = \"grand\">Qu\'est-ce que GEPI ?</p>\r\n\r\n<p>Afin d\'étudier les modalités d\'informatisation des bulletins scolaires : notes et appréciations via Internet, une expérimentation (baptisée Gestion des Elèves Par Internet)a été mise en place. Cette expérimentation concerne les classes suivantes : \r\n<br />* ....\r\n<br />* ....\r\n<br />\r\n<br />\r\nCeci vous concerne car vous êtes professeur enseignant dans l\'une ou l\'autre de ces classes.\r\n<br />\r\n<br />\r\nA partir de la réception de ce document, vous pourrez remplir les bulletins informatisés :\r\n<span class = \"norme\">\r\n<UL><li>soit au lycée à partir de n\'importe quel poste connecté à Internet,\r\n<li>soit chez vous si vous disposez d\'une connexion Internet.\r\n</ul>\r\n</span>\r\n<p class = \"grand\">Comment accéder au module de saisie (notes et appréciations) :</p>\r\n<span class = \"norme\">\r\n<UL>\r\n    <LI>Se connecter à Internet\r\n    <LI>Lancer un navigateur (FireFox de préférence, Opera, Internet Explorer, ...)\r\n    <LI>Se connecter au site : https://adresse_du_site/gepi\r\n    <LI>Après quelques instants une page apparaît vous invitant à entrer un nom d\'identifiant et un mot de passe (cesinformations figurent en haut de cette page).\r\n    <br />ATTENTION : votre mot de passe est strictement confidentiel.\r\n    <br />\r\n    <br />Une fois ces informations fournies, cliquez sur le bouton \"Ok\".\r\n    <LI> Après quelques instants une page d\'accueil apparaît.<br />\r\nLa première fois, Gepi vous demande de changer votre mot de passe.\r\nChoisissez-en un facile à retenir, mais non trivial (évitez toute date\r\nde naissance, nom d\'animal familier, prénom, etc.), et contenant\r\nlettre(s), chiffre(s), et caractère(s) non alphanumérique(s).<br />\r\nLes fois suivantes, vous arriverez directement au menu général de\r\nl\'application. Pour bien prendre connaissance des possibilités de\r\nl\'application, n\'hésitez pas à essayer tous les liens disponibles !\r\n</ul></span>\r\n<p class = \"grand\">Remarque :</p>\r\n<p>GEPI est prévu pour que chaque professeur ne puisse modifier les notes ou les appréciations que dans les rubriques qui le concernent et uniquement pour ses élèves.\r\n<br />\r\nJe reste à votre disposition pour tout renseignement complémentaire.\r\n    <br />\r\n    Le proviseur adjoint\r\n</p>'),
('gepiYear',	'2019/2020'),
('gepiSchoolName',	'IESI'),
('gepiSchoolAdress1',	'17 avenue Montparnasse'),
('gepiSchoolAdress2',	'12'),
('gepiSchoolZipCode',	'7500'),
('gepiSchoolCity',	'Paris'),
('gepiAdminAdress',	'email.admin@example.com'),
('titlesize',	'14'),
('textsize',	'8'),
('cellpadding',	'3'),
('cellspacing',	'1'),
('largeurtableau',	'800'),
('col_matiere_largeur',	'150'),
('begin_bookings',	'1567375200'),
('end_bookings',	'1593727200'),
('max_size',	'307200'),
('total_max_size',	'5242880'),
('col_note_largeur',	'30'),
('active_cahiers_texte',	'y'),
('active_carnets_notes',	'y'),
('logo_etab',	''),
('longmin_pwd',	'5'),
('duree_conservation_logs',	'365'),
('GepiRubConseilProf',	'yes'),
('GepiRubConseilScol',	'yes'),
('bull_ecart_entete',	'0'),
('gepi_prof_suivi',	'professeur principal'),
('gepi_cpe_suivi',	'C.P.E.'),
('GepiProfImprBul',	'no'),
('GepiProfImprBulSettings',	'no'),
('GepiScolImprBulSettings',	'yes'),
('GepiAdminImprBulSettings',	'no'),
('GepiAccesReleveScol',	'yes'),
('GepiAccesReleveCpe',	'no'),
('GepiAccesReleveProf',	'no'),
('GepiAccesReleveProfTousEleves',	'no'),
('GepiAccesReleveProfToutesClasses',	'no'),
('GepiAccesReleveProfP',	'yes'),
('page_garde_imprime',	'no'),
('page_garde_texte',	'<p>Madame, Monsieur<br>\r\n<br>\r\nVeuillez trouvez ci-joint le bulletin scolaire de votre enfant. Nous vous rappelons que la journée <span style=\"font-weight:bold;\">Portes ouvertes</span> du Lycée aura lieu samedi 20 mai entre 10 h et 17 h.<br>\r\n<br>\r\nVeuillez agréer, Madame, Monsieur, l\'expression de mes meilleurs sentiments.<br>\r\n </p>\r\n\r\n<div style=\"text-align:right;\">Le proviseur</div>\r\n'),
('page_garde_padding_top',	'4'),
('page_garde_padding_left',	'11'),
('page_garde_padding_text',	'6'),
('addressblock_padding_top',	'400'),
('addressblock_padding_right',	'200'),
('addressblock_padding_text',	'200'),
('addressblock_length',	'600'),
('cnv_addressblock_dim_144',	'y'),
('p_bulletin_margin',	'5'),
('bull_espace_avis',	'5'),
('change_ordre_aff_matieres',	'ok'),
('disable_login',	'no'),
('bull_formule_bas',	'Bulletin à conserver précieusement. Aucun duplicata ne sera délivré.'),
('delai_devoirs',	'7'),
('active_module_absence',	'2'),
('active_module_absence_professeur',	'y'),
('gepiSchoolTel',	'06 42 75 01 01'),
('gepiSchoolFax',	'04 42 75 01 02'),
('gepiSchoolEmail',	'ce.iesi@ac-paris.fr'),
('col_boite_largeur',	'120'),
('bull_mention_doublant',	'no'),
('bull_affiche_numero',	'no'),
('nombre_tentatives_connexion',	'5'),
('temps_compte_verrouille',	'60'),
('bull_affiche_appreciations',	'y'),
('bull_affiche_absences',	'n'),
('bull_affiche_avis',	'y'),
('bull_affiche_aid',	'y'),
('bull_affiche_formule',	'y'),
('bull_affiche_signature',	'y'),
('l_max_aff_trombinoscopes',	'120'),
('h_max_aff_trombinoscopes',	'160'),
('l_max_imp_trombinoscopes',	'70'),
('h_max_imp_trombinoscopes',	'100'),
('bull_affiche_tel',	'n'),
('bull_affiche_fax',	'n'),
('note_autre_que_sur_20',	'F'),
('gepi_denom_boite',	'boite'),
('gepi_denom_boite_genre',	'f'),
('addressblock_font_size',	'12'),
('addressblock_logo_etab_prop',	'50'),
('addressblock_classe_annee',	'35'),
('bull_ecart_bloc_nom',	'1'),
('addressblock_debug',	'n'),
('GepiAccesReleveEleve',	'yes'),
('GepiAccesCahierTexteEleve',	'yes'),
('GepiAccesReleveParent',	'yes'),
('GepiAccesCahierTexteParent',	'yes'),
('enable_password_recovery',	'no'),
('GepiPasswordReinitProf',	'no'),
('GepiPasswordReinitScolarite',	'no'),
('GepiPasswordReinitCpe',	'no'),
('GepiPasswordReinitAdmin',	'no'),
('GepiPasswordReinitEleve',	'yes'),
('GepiPasswordReinitParent',	'yes'),
('cahier_texte_acces_public',	'no'),
('GepiAccesEquipePedaEleve',	'yes'),
('GepiAccesEquipePedaEmailEleve',	'no'),
('GepiAccesEquipePedaParent',	'yes'),
('GepiAccesEquipePedaEmailParent',	'no'),
('GepiAccesBulletinSimpleParent',	'yes'),
('GepiAccesBulletinSimpleEleve',	'yes'),
('GepiAccesGraphEleve',	'yes'),
('GepiAccesGraphParent',	'yes'),
('choix_bulletin',	'1'),
('min_max_moyclas',	'0'),
('bull_categ_font_size_avis',	'10'),
('bull_police_avis',	'Times New Roman'),
('bull_font_style_avis',	'Gras'),
('bull_affiche_eleve_une_ligne',	'yes'),
('bull_mention_nom_court',	'yes'),
('option_modele_bulletin',	'2'),
('security_alert_email_admin',	'yes'),
('security_alert_email_min_level',	'2'),
('security_alert1_normal_cumulated_level',	'3'),
('security_alert1_normal_email_admin',	'yes'),
('security_alert1_normal_block_user',	'no'),
('security_alert1_probation_cumulated_level',	'1'),
('security_alert1_probation_email_admin',	'yes'),
('security_alert1_probation_block_user',	'no'),
('security_alert2_normal_cumulated_level',	'6'),
('security_alert2_normal_email_admin',	'yes'),
('security_alert2_normal_block_user',	'yes'),
('security_alert2_probation_cumulated_level',	'3'),
('security_alert2_probation_email_admin',	'yes'),
('security_alert2_probation_block_user',	'yes'),
('deverouillage_auto_periode_suivante',	'n'),
('bull_intitule_app',	'Appréciations / Conseils'),
('GepiAccesMoyennesProf',	'yes'),
('GepiAccesMoyennesProfTousEleves',	'yes'),
('GepiAccesMoyennesProfToutesClasses',	'yes'),
('GepiAccesBulletinSimpleProf',	'yes'),
('GepiAccesBulletinSimpleProfTousEleves',	'no'),
('GepiAccesBulletinSimpleProfToutesClasses',	'no'),
('gepi_stylesheet',	'style'),
('edt_calendrier_ouvert',	'y'),
('scolarite_modif_cours',	'y'),
('active_annees_anterieures',	'y'),
('active_notanet',	'n'),
('longmax_login',	'8'),
('autorise_edt_tous',	'y'),
('autorise_edt_admin',	'y'),
('autorise_edt_eleve',	'no'),
('utiliserMenuBarre',	'yes'),
('active_absences_parents',	'no'),
('creneau_different',	'n'),
('active_inscription',	'n'),
('active_inscription_utilisateurs',	'n'),
('mod_inscription_explication',	'<p> <strong>Pr&eacute;sentation des dispositifs du Lyc&eacute;e dans les coll&egrave;ges qui organisent des rencontres avec les parents.</strong> <br />\r\n<br />\r\nChacun d&rsquo;entre vous conna&icirc;t la situation dans laquelle sont plac&eacute;s les &eacute;tablissements : </p>\r\n<ul>\r\n    <li>baisse d&eacute;mographique</li>\r\n    <li>r&eacute;gulation des moyens</li>\r\n    <li>- ... </li>\r\n</ul>\r\nCette ann&eacute;e encore nous devons &ecirc;tre pr&eacute;sents dans les r&eacute;unions organis&eacute;es au sein des coll&egrave;ges afin de pr&eacute;senter nos sp&eacute;cificit&eacute;s, notre valeur ajout&eacute;e, les &eacute;volution du projet, le label international, ... <br />\r\nsur cette feuille, vous avez la possibilit&eacute; de vous inscrire afin d\'intervenir dans un ou plusieurs coll&egrave;ges selon vos convenances.'),
('mod_inscription_titre',	'Intervention dans les collèges'),
('active_ateliers',	'n'),
('GepiAccesRestrAccesAppProfP',	'no'),
('l_resize_trombinoscopes',	'120'),
('h_resize_trombinoscopes',	'160'),
('multisite',	'n'),
('statuts_prives',	'n'),
('mod_edt_gr',	'n'),
('use_ent',	'n'),
('rss_cdt_eleve',	'n'),
('auth_locale',	'yes'),
('auth_ldap',	'no'),
('auth_sso',	'none'),
('ldap_write_access',	'no'),
('may_import_user_profile',	'no'),
('statut_utilisateur_defaut',	'professeur'),
('texte_visa_cdt',	'Cahier de textes visé ce jour <br />Le Principal <br /> M. XXXXX<br />'),
('visa_cdt_inter_modif_notices_visees',	'yes'),
('denomination_eleve',	'étudiant'),
('denomination_eleves',	'étudiants'),
('denomination_professeur',	'professeur'),
('denomination_professeurs',	'professeurs'),
('denomination_responsable',	'responsable légal'),
('denomination_responsables',	'responsables légaux'),
('delais_apres_cloture',	'0'),
('active_mod_ooo',	'n'),
('use_only_cdt',	'n'),
('edt_remplir_prof',	'n'),
('active_mod_genese_classes',	'y'),
('active_mod_ects',	'n'),
('GepiAccesSaisieEctsProf',	'no'),
('GepiAccesSaisieEctsPP',	'no'),
('GepiAccesSaisieEctsScolarite',	'yes'),
('GepiAccesRecapitulatifEctsScolarite',	'yes'),
('GepiAccesRecapitulatifEctsProf',	'yes'),
('GepiAccesEditionDocsEctsPP',	'no'),
('GepiAccesEditionDocsEctsScolarite',	'yes'),
('gepiSchoolStatut',	'prive_sous_contrat'),
('gepiSchoolAcademie',	''),
('note_autre_que_sur_referentiel',	'F'),
('referentiel_note',	'20'),
('active_mod_apb',	'n'),
('active_mod_gest_aid',	'n'),
('unzipped_max_filesize',	'10'),
('autorise_commentaires_mod_disc',	'no'),
('sso_cas_table',	'no'),
('longueur_encodage_photo',	'10'),
('alea_nom_photo',	'33d5ef53c0142bbc7c9e050da4ccf923'),
('gepi_en_production',	'y'),
('GepiAccesBulletinSimpleColonneMoyClasseResp',	'y'),
('GepiAccesBulletinSimpleColonneMoyClasseEleve',	'y'),
('MessagerieDelaisTest',	'1'),
('MessagerieLargeurImg',	'16'),
('mod_disc_terme_incident',	'incident'),
('mod_disc_terme_sanction',	'sanction'),
('GepiPeutCreerBoitesProf',	'yes'),
('active_recherche_lapsus',	'y'),
('active_bulletins',	'y'),
('ping_host',	'173.194.40.183'),
('imprDiscProfAvtOOo',	'n'),
('groupe_de_groupes',	'groupe de groupes'),
('groupes_de_groupes',	'groupes de groupes'),
('abs_prof_modele_message_eleve',	'En raison de l\'absence de __PROF_ABSENT__, le cours __COURS__ du __DATE_HEURE__ sera remplacé par un cours avec __PROF_REMPLACANT__ en salle __SALLE__.'),
('denom_groupe_de_groupes',	'ensemble de groupes'),
('denom_groupes_de_groupes',	'ensembles de groupes'),
('force_error_reporting',	'n'),
('active_mod_discipline',	'n'),
('commentaires_mod_disc_visible_eleve',	'no'),
('commentaires_mod_disc_visible_parent',	'no'),
('mod_disc_terme_avertissement_fin_periode',	'avertissement de fin de période'),
('mod_disc_acces_avertissements',	'n'),
('message_login',	'1'),
('sso_scribe',	'no'),
('cas_attribut_prenom',	''),
('cas_attribut_nom',	''),
('cas_attribut_email',	''),
('mode_generation_login',	'nnnnnnnp'),
('mode_generation_login_eleve',	'nnnnnnnnn_p'),
('mode_generation_login_responsable',	'nnnnnnnnn.p'),
('GepiAccesCDTToutesClasses',	'yes'),
('cdt_afficher_volume_docs_joints',	'y'),
('graphe_affiche_moy_classe',	'oui'),
('cnBoitesModeMoy',	'2'),
('bull_affiche_abs_tot',	'y'),
('bull_affiche_abs_nj',	'y'),
('bull_affiche_abs_ret',	'y'),
('active_mod_alerte',	'y'),
('PeutPosterMessageAdministrateur',	'y'),
('PeutPosterMessageScolarite',	'y'),
('PeutPosterMessageCpe',	'y'),
('output_mode_pdf',	'I'),
('bull_affiche_abs_cpe',	'y'),
('autoriser_correction_bulletin',	'y'),
('GepiAccesCpePPEmailEleve',	'no'),
('GepiAccesCpePPEmailParent',	'no'),
('ImpressionParent',	''),
('ImpressionEleve',	''),
('ImpressionNombre',	'1'),
('ImpressionNombreParent',	'1'),
('ImpressionNombreEleve',	'1'),
('param_module_trombinoscopes',	'no_gep'),
('active_module_trombinoscopes',	'y'),
('sso_display_portail',	'no'),
('sso_url_portail',	'https://www.example.com'),
('sso_hide_logout',	'no'),
('autorise_envoi_sms',	'n'),
('sms_prestataire',	'aucun'),
('sms_username',	''),
('sms_password',	''),
('sms_identite',	''),
('abs2_afficher_alerte_nj',	'y'),
('abs2_afficher_alerte_nb_nj',	'4'),
('abs2_afficher_alerte_nj_delai',	'30'),
('active_mod_orientation',	'n'),
('OrientationNbMaxOrientation',	'3'),
('OrientationNbMaxVoeux',	'3'),
('affiche_vacances_eleresp',	'yes'),
('bullNoSaisieElementsProgrammes',	'no'),
('bull_orientation_periodes',	''),
('bull_voeux_orientation',	'y'),
('bull_titre_voeux_orientation',	'Voeux'),
('bull_orientation',	'y'),
('bull_titre_orientation',	'Orientation proposée'),
('bull_aff_Elements_Programmes',	'n'),
('bull_largeur_col_Elements_Programmes',	'150'),
('mod_disc_avertissements_mi_periode',	'y'),
('FormatAdressePostaleCheck',	'y'),
('bull2016_pas_espace_reserve_EPI_AP_Parcours',	'y'),
('active_module_LSUN',	'y'),
('log_envoi_SMS',	'n'),
('acces_moy_ele_resp',	'immediat'),
('acces_moy_ele_resp_cn',	'immediat'),
('active_mod_actions',	'n'),
('terme_mod_action',	'Action'),
('mod_actions_affichage_familles',	'y'),
('backup_directory',	'16k5Jpr5XmWnuICK8H9i32H9dX0b5rEpsuc4UIFrI9'),
('backupdir_lastchange',	'1588250960'),
('longmax_login_eleve',	'11'),
('display_users',	'tous'),
('cn_affiche_moy_gen',	'y'),
('export_cn_ods',	'n'),
('utiliser_sacoche',	'no'),
('sacocheUrl',	''),
('sacoche_base',	''),
('cn_affiche_date_fin_periode',	'y'),
('bull_cell_pp_textsize',	'8'),
('bull_cell_signature_textsize',	'8'),
('bull_body_marginleft',	'1'),
('bulletin_logo_max_size',	'100'),
('col_hauteur',	'0'),
('bull_affiche_graphiques',	'no'),
('bull_hauteur_img_signature',	'200'),
('bull_largeur_img_signature',	'200'),
('bull_affiche_etab',	'n'),
('bull_bordure_classique',	'n'),
('moyennes_periodes_precedentes',	'n'),
('moyennes_annee',	'n'),
('bull_moyenne_generale_annuelle',	'n'),
('bull_moyenne_generale_annuelle_derniere_periode',	'n'),
('activer_photo_bulletin',	'n'),
('bull_photo_hauteur_max',	'80'),
('bull_photo_largeur_max',	'80'),
('bull_categ_font_size',	'10'),
('bull_affiche_INE_eleve',	'n'),
('bull_categ_bgcolor',	'gray'),
('bull_affich_nom_etab',	'y'),
('bull_affich_adr_etab',	'y'),
('bull_affich_mentions',	'y'),
('bull_affich_intitule_mentions',	'y'),
('AutoriserTypesEnseignements',	'n'),
('utiliser_phpmailer',	'n'),
('phpmailer_smtp_auth',	'n'),
('phpmailer_securite',	''),
('phpmailer_debug',	'n'),
('gepiSchoolRne',	''),
('gepiSchoolPays',	'France'),
('gepiSchoolInspectionAcademique',	'l’Inspection Académique de '),
('gepiAdminNom',	''),
('gepiAdminPrenom',	''),
('gepiAdminFonction',	''),
('gepiAdminAdressPageLogin',	'n'),
('login_mode_alerte_capslock',	'1'),
('contact_admin_mailto',	'n'),
('envoi_mail_liste',	'n'),
('gepiAdminAdressFormHidden',	'y'),
('gepiPrefixeSujetMail',	''),
('mode_generation_pwd_majmin',	'y'),
('mode_generation_pwd_excl',	'n'),
('mode_email_resp',	'sconet'),
('mode_email_ele',	'sconet'),
('informer_scolarite_modif_mail',	'y'),
('email_dest_info_modif_mail',	'ce.XXXXXXXX@ac-xxxxx.fr'),
('email_dest_info_erreur_affect_grp',	''),
('ele_tel_pers',	'no'),
('ele_tel_port',	'no'),
('ele_tel_prof',	'no'),
('type_bulletin_par_defaut',	'pdf'),
('exp_imp_chgt_etab',	'no'),
('aff_temoin_check_serveur',	'n'),
('url_racine_gepi',	''),
('url_racine_gepi_interne',	''),
('ele_lieu_naissance',	'no'),
('avis_conseil_classe_a_la_mano',	'n'),
('langue_vivante_regionale',	'n'),
('gepi_denom_mention',	'mention'),
('num_enregistrement_cnil',	''),
('registre_traitements',	'n'),
('mode_generation_login_casse',	'min'),
('mode_generation_login_eleve_casse',	'min'),
('mode_generation_login_responsable_casse',	'min'),
('FiltrageStrictAlphaNomPrenomPourLogin',	'n'),
('bul_rel_nom_matieres',	'nom_complet_matiere'),
('acces_app_ele_resp',	'manuel'),
('SocleOuvertureSaisieComposantes_administrateur',	'y');

DROP TABLE IF EXISTS `signature_classes`;
CREATE TABLE `signature_classes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `id_classe` int(11) NOT NULL,
  `id_fichier` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `signature_droits`;
CREATE TABLE `signature_droits` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `signature_fichiers`;
CREATE TABLE `signature_fichiers` (
  `id_fichier` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fichier` varchar(255) NOT NULL,
  `login` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`id_fichier`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `socle_classes_syntheses_numeriques`;
CREATE TABLE `socle_classes_syntheses_numeriques` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_classe` int(11) NOT NULL,
  `classe` varchar(50) NOT NULL,
  `annee` varchar(10) NOT NULL DEFAULT '',
  `synthese` text,
  `login_saisie` varchar(50) NOT NULL DEFAULT '',
  `date_saisie` datetime DEFAULT '1970-01-01 00:00:01',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_classe` (`id_classe`,`annee`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `socle_eleves_competences_numeriques`;
CREATE TABLE `socle_eleves_competences_numeriques` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ine` varchar(50) NOT NULL DEFAULT '',
  `cycle` tinyint(2) NOT NULL DEFAULT '0',
  `annee` varchar(10) NOT NULL DEFAULT '',
  `code_competence` varchar(10) NOT NULL DEFAULT '',
  `niveau_maitrise` varchar(10) NOT NULL DEFAULT '',
  `periode` int(11) NOT NULL DEFAULT '1',
  `login_saisie` varchar(50) NOT NULL DEFAULT '',
  `date_saisie` datetime DEFAULT '1970-01-01 00:00:01',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ine` (`ine`,`cycle`,`code_competence`,`periode`),
  KEY `ine_cycle_id_competence_periode` (`ine`,`cycle`,`code_competence`,`periode`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `socle_eleves_composantes`;
CREATE TABLE `socle_eleves_composantes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ine` varchar(50) NOT NULL,
  `cycle` tinyint(2) NOT NULL,
  `annee` varchar(10) NOT NULL DEFAULT '',
  `code_composante` varchar(10) NOT NULL DEFAULT '',
  `niveau_maitrise` varchar(10) NOT NULL DEFAULT '',
  `periode` int(11) NOT NULL DEFAULT '1',
  `login_saisie` varchar(50) NOT NULL DEFAULT '',
  `date_saisie` datetime DEFAULT '1970-01-01 00:00:01',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ine` (`ine`,`cycle`,`code_composante`,`periode`,`annee`),
  KEY `ine_cycle_id_composante_periode` (`ine`,`cycle`,`code_composante`,`periode`,`annee`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `socle_eleves_enseignements_complements`;
CREATE TABLE `socle_eleves_enseignements_complements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ine` varchar(50) NOT NULL,
  `id_groupe` int(11) NOT NULL,
  `positionnement` varchar(10) NOT NULL DEFAULT '',
  `login_saisie` varchar(50) NOT NULL DEFAULT '',
  `date_saisie` datetime DEFAULT '1970-01-01 00:00:01',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ine` (`ine`,`id_groupe`),
  KEY `ine_id_groupe` (`ine`,`id_groupe`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `socle_eleves_lvr`;
CREATE TABLE `socle_eleves_lvr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ine` varchar(50) NOT NULL,
  `id_groupe` int(11) NOT NULL,
  `positionnement` varchar(10) NOT NULL DEFAULT '',
  `login_saisie` varchar(50) NOT NULL DEFAULT '',
  `date_saisie` datetime DEFAULT '1970-01-01 00:00:01',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ine` (`ine`,`id_groupe`),
  KEY `ine_id_groupe` (`ine`,`id_groupe`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `socle_eleves_syntheses`;
CREATE TABLE `socle_eleves_syntheses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ine` varchar(50) NOT NULL,
  `cycle` tinyint(2) NOT NULL,
  `annee` varchar(10) NOT NULL DEFAULT '',
  `synthese` text,
  `login_saisie` varchar(50) NOT NULL DEFAULT '',
  `date_saisie` datetime DEFAULT '1970-01-01 00:00:01',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ine` (`ine`,`cycle`,`annee`),
  KEY `ine_cycle_annee` (`ine`,`cycle`,`annee`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `socle_eleves_syntheses_numeriques`;
CREATE TABLE `socle_eleves_syntheses_numeriques` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ine` varchar(50) NOT NULL,
  `cycle` tinyint(2) NOT NULL,
  `annee` varchar(10) NOT NULL DEFAULT '',
  `periode` int(11) NOT NULL DEFAULT '1',
  `synthese` text,
  `login_saisie` varchar(50) NOT NULL DEFAULT '',
  `date_saisie` datetime DEFAULT '1970-01-01 00:00:01',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ine` (`ine`,`cycle`,`annee`,`periode`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `sp_saisies`;
CREATE TABLE `sp_saisies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_type` int(11) NOT NULL,
  `login` varchar(50) NOT NULL DEFAULT '',
  `date_sp` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `commentaire` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `sp_seuils`;
CREATE TABLE `sp_seuils` (
  `id_seuil` int(11) NOT NULL AUTO_INCREMENT,
  `seuil` int(11) NOT NULL,
  `periode` char(1) NOT NULL DEFAULT 'y',
  `type` varchar(255) NOT NULL DEFAULT '',
  `administrateur` char(1) NOT NULL DEFAULT '',
  `scolarite` char(1) NOT NULL DEFAULT '',
  `cpe` char(1) NOT NULL DEFAULT '',
  `eleve` char(1) NOT NULL DEFAULT '',
  `responsable` char(1) NOT NULL DEFAULT '',
  `professeur_principal` char(1) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_seuil`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `sp_types_saisies`;
CREATE TABLE `sp_types_saisies` (
  `id_type` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `rang` int(11) NOT NULL,
  PRIMARY KEY (`id_type`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `sso_table_correspondance`;
CREATE TABLE `sso_table_correspondance` (
  `login_gepi` varchar(100) NOT NULL DEFAULT '',
  `login_sso` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`login_gepi`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `suivi_eleve_cpe`;
CREATE TABLE `suivi_eleve_cpe` (
  `id_suivi_eleve_cpe` int(11) NOT NULL AUTO_INCREMENT,
  `eleve_suivi_eleve_cpe` varchar(30) NOT NULL DEFAULT '',
  `parqui_suivi_eleve_cpe` varchar(150) NOT NULL,
  `date_suivi_eleve_cpe` date NOT NULL DEFAULT '0000-00-00',
  `heure_suivi_eleve_cpe` time NOT NULL,
  `komenti_suivi_eleve_cpe` text NOT NULL,
  `niveau_message_suivi_eleve_cpe` varchar(1) NOT NULL,
  `action_suivi_eleve_cpe` varchar(2) NOT NULL,
  `support_suivi_eleve_cpe` tinyint(4) NOT NULL,
  `courrier_suivi_eleve_cpe` int(11) NOT NULL,
  PRIMARY KEY (`id_suivi_eleve_cpe`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `synthese_app_classe`;
CREATE TABLE `synthese_app_classe` (
  `id_classe` int(11) NOT NULL DEFAULT '0',
  `periode` int(11) NOT NULL DEFAULT '0',
  `synthese` text NOT NULL,
  PRIMARY KEY (`id_classe`,`periode`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `s_alerte_mail`;
CREATE TABLE `s_alerte_mail` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_classe` smallint(6) unsigned NOT NULL,
  `destinataire` varchar(50) NOT NULL DEFAULT '',
  `login` varchar(50) NOT NULL DEFAULT '',
  `adresse` varchar(250) DEFAULT NULL,
  `type` varchar(50) DEFAULT 'mail',
  PRIMARY KEY (`id`),
  KEY `id_classe` (`id_classe`,`destinataire`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `s_autres_sanctions`;
CREATE TABLE `s_autres_sanctions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_sanction` int(11) NOT NULL,
  `id_nature` int(11) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `s_avertissements`;
CREATE TABLE `s_avertissements` (
  `id_avertissement` int(11) NOT NULL AUTO_INCREMENT,
  `login_ele` varchar(50) NOT NULL DEFAULT '',
  `id_type_avertissement` int(11) NOT NULL DEFAULT '0',
  `periode` int(11) NOT NULL DEFAULT '0',
  `s_periode` char(1) NOT NULL DEFAULT 'n',
  `date_avertissement` datetime NOT NULL,
  `declarant` varchar(50) NOT NULL DEFAULT '',
  `commentaire` text NOT NULL,
  PRIMARY KEY (`id_avertissement`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `s_categories`;
CREATE TABLE `s_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categorie` varchar(50) NOT NULL DEFAULT '',
  `sigle` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `s_communication`;
CREATE TABLE `s_communication` (
  `id_communication` int(11) NOT NULL AUTO_INCREMENT,
  `id_incident` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `nature` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id_communication`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `s_delegation`;
CREATE TABLE `s_delegation` (
  `id_delegation` int(11) NOT NULL AUTO_INCREMENT,
  `fct_delegation` varchar(100) NOT NULL,
  `fct_autorite` varchar(50) NOT NULL,
  `nom_autorite` varchar(50) NOT NULL,
  PRIMARY KEY (`id_delegation`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `s_exclusions`;
CREATE TABLE `s_exclusions` (
  `id_exclusion` int(11) NOT NULL AUTO_INCREMENT,
  `id_sanction` int(11) NOT NULL DEFAULT '0',
  `date_debut` date NOT NULL DEFAULT '0000-00-00',
  `heure_debut` varchar(20) NOT NULL DEFAULT '',
  `date_fin` date NOT NULL DEFAULT '0000-00-00',
  `heure_fin` varchar(20) NOT NULL DEFAULT '',
  `travail` text NOT NULL,
  `lieu` varchar(255) NOT NULL DEFAULT '',
  `nombre_jours` varchar(50) NOT NULL,
  `qualification_faits` text NOT NULL,
  `num_courrier` varchar(50) NOT NULL,
  `type_exclusion` varchar(50) NOT NULL,
  `id_signataire` int(11) NOT NULL,
  PRIMARY KEY (`id_exclusion`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `s_incidents`;
CREATE TABLE `s_incidents` (
  `id_incident` int(11) NOT NULL AUTO_INCREMENT,
  `declarant` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `heure` varchar(20) NOT NULL,
  `id_lieu` int(11) NOT NULL,
  `nature` varchar(255) NOT NULL,
  `id_categorie` int(11) DEFAULT NULL,
  `description` text NOT NULL,
  `etat` varchar(20) NOT NULL,
  `message_id` varchar(50) NOT NULL,
  `primo_declarant` varchar(50) DEFAULT NULL,
  `commentaire` text NOT NULL,
  PRIMARY KEY (`id_incident`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `s_lieux_incidents`;
CREATE TABLE `s_lieux_incidents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lieu` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

INSERT INTO `s_lieux_incidents` (`id`, `lieu`) VALUES
(1,	'Classe'),
(2,	'Couloir'),
(3,	'Cour'),
(4,	'Réfectoire'),
(5,	'Autre');

DROP TABLE IF EXISTS `s_mesures`;
CREATE TABLE `s_mesures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('prise','demandee') DEFAULT NULL,
  `mesure` varchar(50) NOT NULL,
  `commentaire` text NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

INSERT INTO `s_mesures` (`id`, `type`, `mesure`, `commentaire`) VALUES
(1,	'prise',	'Travail supplémentaire',	''),
(2,	'prise',	'Mot dans le carnet de liaison',	''),
(3,	'demandee',	'Retenue',	''),
(4,	'demandee',	'Exclusion',	'');

DROP TABLE IF EXISTS `s_natures`;
CREATE TABLE `s_natures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nature` varchar(50) NOT NULL DEFAULT '',
  `id_categorie` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `s_protagonistes`;
CREATE TABLE `s_protagonistes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_incident` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `statut` varchar(50) NOT NULL,
  `qualite` varchar(50) NOT NULL,
  `avertie` enum('N','O') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `s_qualites`;
CREATE TABLE `s_qualites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `qualite` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

INSERT INTO `s_qualites` (`id`, `qualite`) VALUES
(1,	'Responsable'),
(2,	'Victime'),
(3,	'Témoin'),
(4,	'Autre');

DROP TABLE IF EXISTS `s_reports`;
CREATE TABLE `s_reports` (
  `id_report` int(11) NOT NULL AUTO_INCREMENT,
  `id_sanction` int(11) NOT NULL,
  `id_type_sanction` int(11) NOT NULL,
  `nature_sanction` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `informations` text NOT NULL,
  `motif_report` varchar(255) NOT NULL,
  PRIMARY KEY (`id_report`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `s_retenues`;
CREATE TABLE `s_retenues` (
  `id_retenue` int(11) NOT NULL AUTO_INCREMENT,
  `id_sanction` int(11) NOT NULL,
  `date` date NOT NULL,
  `heure_debut` varchar(20) NOT NULL,
  `duree` float NOT NULL,
  `travail` text NOT NULL,
  `lieu` varchar(255) NOT NULL,
  `materiel` varchar(150) NOT NULL,
  PRIMARY KEY (`id_retenue`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `s_sanctions`;
CREATE TABLE `s_sanctions` (
  `id_sanction` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `nature` varchar(255) NOT NULL,
  `id_nature_sanction` int(11) DEFAULT NULL,
  `effectuee` enum('N','O') NOT NULL,
  `id_incident` int(11) NOT NULL,
  `saisie_par` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_sanction`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `s_sanctions_check`;
CREATE TABLE `s_sanctions_check` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_sanction` int(11) NOT NULL,
  `etat` varchar(100) NOT NULL,
  `login` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `s_traitement_incident`;
CREATE TABLE `s_traitement_incident` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_incident` int(11) NOT NULL,
  `login_ele` varchar(50) NOT NULL,
  `login_u` varchar(50) NOT NULL,
  `id_mesure` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `s_travail`;
CREATE TABLE `s_travail` (
  `id_travail` int(11) NOT NULL AUTO_INCREMENT,
  `id_sanction` int(11) NOT NULL,
  `date_retour` date NOT NULL,
  `heure_retour` varchar(20) NOT NULL,
  `travail` text NOT NULL,
  PRIMARY KEY (`id_travail`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `s_travail_mesure`;
CREATE TABLE `s_travail_mesure` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_incident` int(11) NOT NULL,
  `login_ele` varchar(50) NOT NULL,
  `travail` text NOT NULL,
  `materiel` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `s_types_avertissements`;
CREATE TABLE `s_types_avertissements` (
  `id_type_avertissement` int(11) NOT NULL AUTO_INCREMENT,
  `nom_court` varchar(50) NOT NULL,
  `nom_complet` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id_type_avertissement`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `s_types_sanctions2`;
CREATE TABLE `s_types_sanctions2` (
  `id_nature` int(11) NOT NULL AUTO_INCREMENT,
  `nature` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'autre',
  `saisie_prof` char(1) NOT NULL DEFAULT 'n',
  `rang` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_nature`)
) TYPE=MyISAM;

INSERT INTO `s_types_sanctions2` (`id_nature`, `nature`, `type`, `saisie_prof`, `rang`) VALUES
(1,	'Travail',	'autre',	'n',	1),
(2,	'Retenue',	'autre',	'n',	2),
(3,	'Exclusion',	'autre',	'n',	3),
(4,	'Avertissement travail',	'autre',	'n',	4),
(5,	'Avertissement comportement',	'autre',	'n',	5);

DROP TABLE IF EXISTS `tempo`;
CREATE TABLE `tempo` (
  `id_classe` int(11) NOT NULL DEFAULT '0',
  `max_periode` int(11) NOT NULL DEFAULT '0',
  `num` varchar(255) NOT NULL DEFAULT '0'
) TYPE=MyISAM;


DROP TABLE IF EXISTS `tempo2`;
CREATE TABLE `tempo2` (
  `col1` varchar(100) NOT NULL DEFAULT '',
  `col2` varchar(100) NOT NULL DEFAULT ''
) TYPE=MyISAM;


DROP TABLE IF EXISTS `tempo3`;
CREATE TABLE `tempo3` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `col1` varchar(255) NOT NULL,
  `col2` text,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `tempo3_cdt`;
CREATE TABLE `tempo3_cdt` (
  `id_classe` int(11) NOT NULL DEFAULT '0',
  `classe` varchar(255) NOT NULL DEFAULT '',
  `matiere` varchar(255) NOT NULL DEFAULT '',
  `enseignement` varchar(255) NOT NULL DEFAULT '',
  `id_groupe` int(11) NOT NULL DEFAULT '0',
  `fichier` varchar(255) NOT NULL DEFAULT ''
) TYPE=MyISAM;


DROP TABLE IF EXISTS `tempo4`;
CREATE TABLE `tempo4` (
  `col1` varchar(100) NOT NULL DEFAULT '',
  `col2` varchar(100) NOT NULL DEFAULT '',
  `col3` varchar(255) NOT NULL DEFAULT '',
  `col4` varchar(100) NOT NULL DEFAULT ''
) TYPE=MyISAM;


DROP TABLE IF EXISTS `tempo_utilisateurs`;
CREATE TABLE `tempo_utilisateurs` (
  `login` varchar(50) NOT NULL,
  `password` varchar(128) NOT NULL,
  `salt` varchar(128) NOT NULL,
  `email` varchar(50) NOT NULL,
  `identifiant1` varchar(10) NOT NULL COMMENT 'eleves.ele_id ou resp_pers.pers_id',
  `identifiant2` varchar(50) NOT NULL COMMENT 'eleves.elenoet',
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `statut` varchar(20) NOT NULL,
  `auth_mode` enum('gepi','ldap','sso') NOT NULL DEFAULT 'gepi',
  `date_reserve` date DEFAULT '0000-00-00',
  `temoin` varchar(50) NOT NULL,
  PRIMARY KEY (`login`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `temp_abs_extract`;
CREATE TABLE `temp_abs_extract` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL DEFAULT '',
  `date_extract` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `login_ele` varchar(50) NOT NULL DEFAULT '',
  `item` varchar(100) NOT NULL DEFAULT '',
  `valeur` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `temp_abs_import`;
CREATE TABLE `temp_abs_import` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL DEFAULT '',
  `cpe_login` varchar(50) NOT NULL DEFAULT '',
  `elenoet` varchar(50) NOT NULL DEFAULT '',
  `libelle` varchar(50) NOT NULL DEFAULT '',
  `nbAbs` int(11) NOT NULL DEFAULT '0',
  `nbNonJustif` int(11) NOT NULL DEFAULT '0',
  `nbRet` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `elenoet` (`elenoet`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `temp_gep_import`;
CREATE TABLE `temp_gep_import` (
  `ID_TEMPO` varchar(40) NOT NULL DEFAULT '',
  `LOGIN` varchar(40) NOT NULL DEFAULT '',
  `ELENOM` varchar(40) NOT NULL DEFAULT '',
  `ELEPRE` varchar(40) NOT NULL DEFAULT '',
  `ELESEXE` varchar(40) NOT NULL DEFAULT '',
  `ELEDATNAIS` varchar(40) NOT NULL DEFAULT '',
  `ELENOET` varchar(40) NOT NULL DEFAULT '',
  `ERENO` varchar(40) NOT NULL DEFAULT '',
  `ELEDOUBL` varchar(40) NOT NULL DEFAULT '',
  `ELENONAT` varchar(40) NOT NULL DEFAULT '',
  `ELEREG` varchar(40) NOT NULL DEFAULT '',
  `DIVCOD` varchar(40) NOT NULL DEFAULT '',
  `ETOCOD_EP` varchar(40) NOT NULL DEFAULT '',
  `ELEOPT1` varchar(40) NOT NULL DEFAULT '',
  `ELEOPT2` varchar(40) NOT NULL DEFAULT '',
  `ELEOPT3` varchar(40) NOT NULL DEFAULT '',
  `ELEOPT4` varchar(40) NOT NULL DEFAULT '',
  `ELEOPT5` varchar(40) NOT NULL DEFAULT '',
  `ELEOPT6` varchar(40) NOT NULL DEFAULT '',
  `ELEOPT7` varchar(40) NOT NULL DEFAULT '',
  `ELEOPT8` varchar(40) NOT NULL DEFAULT '',
  `ELEOPT9` varchar(40) NOT NULL DEFAULT '',
  `ELEOPT10` varchar(40) NOT NULL DEFAULT '',
  `ELEOPT11` varchar(40) NOT NULL DEFAULT '',
  `ELEOPT12` varchar(40) NOT NULL DEFAULT ''
) TYPE=MyISAM;


DROP TABLE IF EXISTS `temp_gep_import2`;
CREATE TABLE `temp_gep_import2` (
  `ID_TEMPO` varchar(40) NOT NULL DEFAULT '',
  `LOGIN` varchar(40) NOT NULL DEFAULT '',
  `ELENOM` varchar(40) NOT NULL DEFAULT '',
  `ELEPRE` varchar(40) NOT NULL DEFAULT '',
  `ELESEXE` varchar(40) NOT NULL DEFAULT '',
  `ELEDATNAIS` varchar(40) NOT NULL DEFAULT '',
  `ELENOET` varchar(40) NOT NULL DEFAULT '',
  `ELE_ID` varchar(40) NOT NULL DEFAULT '',
  `ELEDOUBL` varchar(40) NOT NULL DEFAULT '',
  `ELENONAT` varchar(40) NOT NULL DEFAULT '',
  `ELEREG` varchar(40) NOT NULL DEFAULT '',
  `DIVCOD` varchar(40) NOT NULL DEFAULT '',
  `ETOCOD_EP` varchar(40) NOT NULL DEFAULT '',
  `ELEOPT1` varchar(40) NOT NULL DEFAULT '',
  `ELEOPT2` varchar(40) NOT NULL DEFAULT '',
  `ELEOPT3` varchar(40) NOT NULL DEFAULT '',
  `ELEOPT4` varchar(40) NOT NULL DEFAULT '',
  `ELEOPT5` varchar(40) NOT NULL DEFAULT '',
  `ELEOPT6` varchar(40) NOT NULL DEFAULT '',
  `ELEOPT7` varchar(40) NOT NULL DEFAULT '',
  `ELEOPT8` varchar(40) NOT NULL DEFAULT '',
  `ELEOPT9` varchar(40) NOT NULL DEFAULT '',
  `ELEOPT10` varchar(40) NOT NULL DEFAULT '',
  `ELEOPT11` varchar(40) NOT NULL DEFAULT '',
  `ELEOPT12` varchar(40) NOT NULL DEFAULT '',
  `LIEU_NAISSANCE` varchar(50) NOT NULL DEFAULT '',
  `MEL` varchar(255) NOT NULL DEFAULT '',
  `TEL_PERS` varchar(255) NOT NULL DEFAULT '',
  `TEL_PORT` varchar(255) NOT NULL DEFAULT '',
  `TEL_PROF` varchar(255) NOT NULL DEFAULT ''
) TYPE=MyISAM;


DROP TABLE IF EXISTS `tentatives_intrusion`;
CREATE TABLE `tentatives_intrusion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(255) DEFAULT NULL,
  `adresse_ip` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `niveau` smallint(6) NOT NULL,
  `fichier` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `statut` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`,`login`)
) TYPE=MyISAM;

INSERT INTO `tentatives_intrusion` (`id`, `login`, `adresse_ip`, `date`, `niveau`, `fichier`, `description`, `statut`) VALUES
(1,	'ADMIN',	'::1',	'2020-04-30 15:11:19',	1,	'/initialisation/index.php',	'Tentative d\'accès à un fichier sans avoir les droits nécessaires',	'new'),
(2,	'-',	'::1',	'2020-04-30 15:11:25',	1,	'/login.php',	'Tentative de connexion avec un login incorrect (n\'existe pas dans la base Gepi). Ce peut être simplement une faute de frappe. Cette alerte n\'est significative qu\'en cas de répétition. (login utilisé : ighilabdelhakim@hotmail.com)',	'new'),
(3,	'-',	'::1',	'2020-04-30 19:51:55',	1,	'/matieres/matieres_categories.php',	'Accès à une page sans être logué (peut provenir d\'un timeout de session).',	'new'),
(4,	'-',	'::1',	'2020-04-30 19:51:58',	1,	'/login.php',	'Tentative de connexion avec un mot de passe incorrect. Ce peut être simplement une faute de frappe. Cette alerte n\'est significative qu\'en cas de répétition. (login : admin)',	'new'),
(5,	'-',	'::1',	'2020-05-01 20:36:35',	1,	'/utilisateurs/impression_bienvenue.php',	'Accès à une page sans être logué (peut provenir d\'un timeout de session).',	'new'),
(6,	'-',	'::1',	'2020-05-02 00:22:22',	1,	'/cahier_notes/index.php',	'Accès à une page sans être logué (peut provenir d\'un timeout de session).',	'new'),
(7,	'-',	'::1',	'2020-05-02 02:27:12',	1,	'/bulletin/param_bull_pdf.php',	'Accès à une page sans être logué (peut provenir d\'un timeout de session).',	'new'),
(8,	'-',	'::1',	'2020-05-02 03:07:17',	1,	'/login.php',	'Tentative de connexion avec un mot de passe incorrect. Ce peut être simplement une faute de frappe. Cette alerte n\'est significative qu\'en cas de répétition. (login : A.Mustapha)',	'new');

DROP TABLE IF EXISTS `t_plan_de_classe`;
CREATE TABLE `t_plan_de_classe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_groupe` int(11) NOT NULL,
  `login_prof` varchar(50) NOT NULL,
  `dim_photo` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `t_plan_de_classe_ele`;
CREATE TABLE `t_plan_de_classe_ele` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan` int(11) NOT NULL,
  `login_ele` varchar(50) NOT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `udt_corresp`;
CREATE TABLE `udt_corresp` (
  `champ` varchar(255) NOT NULL DEFAULT '',
  `nom_udt` varchar(255) NOT NULL DEFAULT '',
  `nom_gepi` varchar(255) NOT NULL DEFAULT ''
) TYPE=MyISAM;


DROP TABLE IF EXISTS `udt_lignes`;
CREATE TABLE `udt_lignes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `division` varchar(255) NOT NULL DEFAULT '',
  `matiere` varchar(255) NOT NULL DEFAULT '',
  `prof` varchar(255) NOT NULL DEFAULT '',
  `groupe` varchar(255) NOT NULL DEFAULT '',
  `regroup` varchar(255) NOT NULL DEFAULT '',
  `mo` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE `utilisateurs` (
  `login` varchar(50) NOT NULL DEFAULT '',
  `nom` varchar(50) NOT NULL DEFAULT '',
  `prenom` varchar(50) NOT NULL DEFAULT '',
  `civilite` varchar(5) NOT NULL DEFAULT '',
  `password` varchar(128) NOT NULL DEFAULT '',
  `salt` varchar(128) DEFAULT NULL,
  `email` varchar(50) NOT NULL DEFAULT '',
  `show_email` varchar(3) NOT NULL DEFAULT 'no',
  `statut` varchar(20) NOT NULL DEFAULT '',
  `etat` varchar(20) NOT NULL DEFAULT '',
  `change_mdp` char(1) NOT NULL DEFAULT 'n',
  `date_verrouillage` datetime NOT NULL DEFAULT '2006-01-01 00:00:00',
  `password_ticket` varchar(255) NOT NULL DEFAULT '',
  `ticket_expiration` datetime NOT NULL,
  `niveau_alerte` smallint(6) NOT NULL DEFAULT '0',
  `observation_securite` tinyint(4) NOT NULL DEFAULT '0',
  `temp_dir` varchar(255) NOT NULL,
  `numind` varchar(255) NOT NULL,
  `type` varchar(10) NOT NULL,
  `auth_mode` enum('gepi','ldap','sso') NOT NULL DEFAULT 'gepi',
  PRIMARY KEY (`login`),
  KEY `statut` (`statut`),
  KEY `etat` (`etat`)
) TYPE=MyISAM;

INSERT INTO `utilisateurs` (`login`, `nom`, `prenom`, `civilite`, `password`, `salt`, `email`, `show_email`, `statut`, `etat`, `change_mdp`, `date_verrouillage`, `password_ticket`, `ticket_expiration`, `niveau_alerte`, `observation_securite`, `temp_dir`, `numind`, `type`, `auth_mode`) VALUES
('ADMIN',	'GEPI',	'Administrateur',	'M.',	'8ea13140ee5e4021345107d27d31925a9c3a53266db4f21d34fd8228351ea27f',	'9c2dc514b14afddb3ad2d0caa8bc715e',	'ighilabdelhakim@hotmail.com',	'no',	'administrateur',	'actif',	'n',	'2006-01-01 00:00:00',	'',	'0000-00-00 00:00:00',	1,	0,	'ADMIN_81ObPwy14Tf3Wj7nmSIUJHdg3TVGjd8fo00z8jWd',	'',	'',	'gepi'),
('prof_a',	'Prof',	'A',	'M.',	'6493dc660b00854b54ea657a2817ddce7f3d4fd2a1e41cb38c0d92a737f8186e',	'5bae4817229084895f6809203e320e37',	'',	'no',	'professeur',	'actif',	'n',	'2006-01-01 00:00:00',	'',	'0000-00-00 00:00:00',	0,	0,	'prof_a_743F1u4uxxur6K236FvU17GF6D40qxTXRa36',	'',	'',	'gepi'),
('prof_b',	'Prof',	'B',	'M.',	'c824ea46e4d1e4283b871fec7bf72b80c7d1eccc63056cd9513800357c7e5b30',	'a6418df20ec0af50fb2448e6cf5fcfc1',	'',	'no',	'professeur',	'actif',	'n',	'2006-01-01 00:00:00',	'',	'0000-00-00 00:00:00',	0,	0,	'prof_b_zxwh97YAP88kWOL76DrIi4xbBKYX9H6CFSP7w6Q6k7',	'',	'',	'gepi'),
('prof_c',	'Prof',	'C',	'M.',	'63ebd641c17891ff8fb03c9fc3d9600f79824111e1d1647e073d913400823304',	'b443f220f7b5977b11de934958dea79c',	'',	'no',	'professeur',	'actif',	'n',	'2006-01-01 00:00:00',	'',	'0000-00-00 00:00:00',	0,	0,	'prof_c_8rZ4vO4gZ7K2120zD3ajTS9AmJ58Sa5s8A2VHm045No',	'',	'',	'gepi'),
('scolarite',	'Scolarite',	'',	'M.',	'befe4b05ff03d0a4806cdc87fc35dfa8fa58ed2e1d0fe3316b2cad3297262c5f',	'053e2c8fa1a19dac38ff5d708110e5d7',	'',	'no',	'scolarite',	'actif',	'n',	'2006-01-01 00:00:00',	'',	'0000-00-00 00:00:00',	0,	0,	'scolarite_rpqiW8O784U9yuWnCrIDjl27XPiW2i9o6uuAl2v0I9v',	'',	'',	'gepi'),
('A.Mustapha',	'A.',	'Mustapha',	'M.',	'2ff66bdaf9b76c8e0d34d5b154c30edb639a924c62cb63cdb742b814249c65c2',	'b4f063143f91505b5732cac095d0caf0',	'',	'no',	'eleve',	'actif',	'n',	'2006-01-01 00:00:00',	'',	'0000-00-00 00:00:00',	0,	0,	'A.Mustapha_nqJ8EzcVgMq469E9lhEHkOpf8M98219uqtLOAo6CAG',	'',	'',	'gepi');

DROP TABLE IF EXISTS `vocabulaire`;
CREATE TABLE `vocabulaire` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `terme` varchar(255) NOT NULL DEFAULT '',
  `terme_corrige` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

INSERT INTO `vocabulaire` (`id`, `terme`, `terme_corrige`) VALUES
(1,	'jute',	'juste'),
(2,	'il peu',	'il peut'),
(3,	'elle peu',	'elle peut'),
(4,	'un peut',	'un peu'),
(5,	'trop peut',	'trop peu'),
(6,	'baise',	'baisse'),
(7,	'baisé',	'baissé'),
(8,	'baiser',	'baisser'),
(9,	'courge',	'courage'),
(10,	'camer',	'calmer'),
(11,	'camé',	'calmé'),
(12,	'came',	'calme'),
(13,	'tu est',	'tu es'),
(14,	'tu et',	'tu es'),
(15,	'il et',	'il est'),
(16,	'il es',	'il est'),
(17,	'elle et',	'elle est'),
(18,	'elle es',	'elle est');

DROP TABLE IF EXISTS `vs_alerts_eleves`;
CREATE TABLE `vs_alerts_eleves` (
  `id_alert_eleve` int(11) NOT NULL AUTO_INCREMENT,
  `eleve_alert_eleve` varchar(100) NOT NULL,
  `date_alert_eleve` date NOT NULL,
  `groupe_alert_eleve` int(11) NOT NULL,
  `type_alert_eleve` int(11) NOT NULL,
  `nb_trouve` int(11) NOT NULL,
  `temp_insert` varchar(100) NOT NULL,
  `etat_alert_eleve` tinyint(4) NOT NULL,
  `etatpar_alert_eleve` varchar(100) NOT NULL,
  PRIMARY KEY (`id_alert_eleve`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `vs_alerts_groupes`;
CREATE TABLE `vs_alerts_groupes` (
  `id_alert_groupe` int(11) NOT NULL AUTO_INCREMENT,
  `nom_alert_groupe` varchar(150) NOT NULL,
  `creerpar_alert_groupe` varchar(100) NOT NULL,
  PRIMARY KEY (`id_alert_groupe`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `vs_alerts_types`;
CREATE TABLE `vs_alerts_types` (
  `id_alert_type` int(11) NOT NULL AUTO_INCREMENT,
  `groupe_alert_type` int(11) NOT NULL,
  `type_alert_type` varchar(10) NOT NULL,
  `specifisite_alert_type` varchar(25) NOT NULL,
  `eleve_concerne` text NOT NULL,
  `date_debut_comptage` date NOT NULL,
  `nb_comptage_limit` varchar(200) NOT NULL,
  PRIMARY KEY (`id_alert_type`)
) TYPE=MyISAM;


-- 2020-05-02 04:14:14
