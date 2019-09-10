/*
SQLyog Community Edition- MySQL GUI v6.55
MySQL - 5.6.21 : Database - stockprobd
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;



/*Table structure for table `abonne` */

DROP TABLE IF EXISTS `abonne`;

CREATE TABLE `abonne` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profil_id` int(11) DEFAULT NULL,
  `type_abonne_id` int(11) DEFAULT NULL,
  `username_abonne` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nom_abonne` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `prenoms_abonne` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `code_base_abonne` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_abonne` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tel1_abonne` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tel2_abonne` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `adresse_abonne` longtext COLLATE utf8_unicode_ci,
  `etat_abonne` smallint(6) NOT NULL,
  `attempt_abonne` smallint(6) DEFAULT NULL,
  `password_abonne` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `c_password_abonne` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `salt_abonne` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `etat_connecte` tinyint(1) DEFAULT NULL,
  `adresse_ip_abonne` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `num_compte` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sexe_abonne` smallint(6) DEFAULT NULL,
  `date_ajout_abonne` datetime NOT NULL,
  `date_edit_abonne` datetime DEFAULT NULL,
  `loss_password_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_loss_password` datetime DEFAULT NULL,
  `nom_contact` varchar(70) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_76328BF0BA85800` (`email_abonne`),
  KEY `IDX_76328BF0275ED078` (`profil_id`),
  KEY `IDX_76328BF077E3E786` (`type_abonne_id`),
  CONSTRAINT `FK_76328BF0275ED078` FOREIGN KEY (`profil_id`) REFERENCES `profil` (`id`),
  CONSTRAINT `FK_76328BF077E3E786` FOREIGN KEY (`type_abonne_id`) REFERENCES `type_abonne` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `abonne` */

/*Table structure for table `action` */

DROP TABLE IF EXISTS `action`;

CREATE TABLE `action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `controleur_id` int(11) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  `nom` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_47CC8C926C6E55B5` (`nom`),
  KEY `IDX_47CC8C92B13E6101` (`controleur_id`),
  KEY `IDX_47CC8C92AFC2B591` (`module_id`),
  CONSTRAINT `FK_47CC8C92AFC2B591` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`),
  CONSTRAINT `FK_47CC8C92B13E6101` FOREIGN KEY (`controleur_id`) REFERENCES `controleur` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `action` */

insert  into `action`(`id`,`controleur_id`,`module_id`,`nom`,`description`) values (1,1,1,'historiqueConnexionAction','Affichage de l\'historique de connexion d\'un administrateur'),(2,2,1,'listeProfilAction','Affichage de la liste des profils'),(3,3,2,'infosEleveAction','Affichage des informations d\'un utilisateur'),(4,1,1,'ajoutUtilisateurAction','Ajout d\'un nouvel administrateur'),(5,3,2,'changerEtatEleveAction','Activation, suppression, désactivation d\'un utilisateur'),(6,4,2,'listeAbonneByProfilAction','Affichage de la liste des Utilisateurs'),(7,3,2,'modifierEleveAction','Modification d\'un utilisateur'),(8,5,3,'profilAccessAction','Affichage de la page d\'attribution de droits à un profil d\'utiliateur'),(9,5,3,'updateDroitProfilAction','Mise à jour des droits d\'un profil'),(10,1,1,'monProfilAction','Affichage des infos de l\'administrateur connecté'),(11,1,1,'listeUserByProfilAction','Affichage de la liste des administrateurs'),(12,1,1,'detailsConnexionAction','Affichage des détails d\'une connexion'),(13,2,1,'ajoutProfilAction','Ajout d\'un nouveau profil'),(14,1,1,'modifierUtilisateurAction','Modification d\'un administrateur'),(15,2,1,'modifierProfilAction','Modification d\'un profil'),(16,1,1,'modifierMyPasswordUtilisateurAction','Modification du mot de passe par l\'administrateur connecté '),(17,6,1,'getMatiereAction','recupération des informations concernant les matières '),(18,6,1,'traiteMatiereUtilisateurAction','recupération des informations concernant les matières '),(19,7,1,'getNiveauAction','recupération des informations concernant les natures '),(20,7,1,'getNiveauInfoMatiereTypeAction','recupération des informations matieres et type de matière '),(21,8,1,'traiteConfigurationAction','traitement des configurations liéé au filiere '),(22,1,1,'getUserAction','recupération des informations concernant les utilisateurs '),(23,3,2,'carteEleveAction','Affichage des informations d\'un utilisateur'),(24,9,4,'listePlanComptableAction','Lister un plan comptable '),(25,9,4,'ajouterPlanComptableAction','Ajouter un plan comptable'),(26,9,4,'modifierPlanComptableAction','modifier un plan comptable'),(27,10,5,'listeTypeOperationAction','Liste des types operations'),(28,10,5,'ajoutTypeOperationAction','Ajouter un type operation'),(29,11,6,'ajoutSchemaAction','Ajouter un schema comptable'),(30,11,6,'listeSchemaAction','Lister un Schema comptable'),(31,11,6,'modifierSchemaAction','Modifier un schema comptable'),(32,12,7,'getPageOperationAction','Avoir accès à la page de paiement des commandes livrées'),(33,12,8,'getInfoCommandeAction','recuperation de la liste des operations d\'une commande'),(34,12,8,'traiteCommandeAction','traitement du paiement avec l\'espece'),(35,13,2,'listeEleveClasseAction','Affichage des informations d\'un utilisateur'),(36,13,2,'listeSoldeClasseAction','Affichage des informations d\'un utilisateur'),(37,13,2,'carteClasseAction','Affichage des informations d\'un utilisateur'),(38,3,2,'listeEleveByProfilAction','Affichage de la liste des Utilisateurs'),(39,8,1,'ajoutConfigurationAction','Modification des configurations'),(40,13,1,'AjoutNoteEleveClasseAction','Affichage de la liste des classes'),(41,1,1,'getMatiereUserAction','recupération des informations concernant les matières d\'un enseignant '),(42,13,2,'emploiTempsClasseAction','Affichage des informations d\'un utilisateur'),(43,13,1,'listeInfoNoteEleveClasseAction','Affichage de la liste des classes');

/*Table structure for table `annee_scolaire` */

DROP TABLE IF EXISTS `annee_scolaire`;

CREATE TABLE `annee_scolaire` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `etablissement_id` int(11) DEFAULT NULL,
  `libelle_annee_scolaire` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_rentre_annee_scolaire` datetime NOT NULL,
  `date_vacance_annee_scolaire` datetime NOT NULL,
  `date_initial_annee_scolaire` datetime NOT NULL,
  `etat_annee_scolaire` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_97150C2BFF631228` (`etablissement_id`),
  CONSTRAINT `FK_97150C2BFF631228` FOREIGN KEY (`etablissement_id`) REFERENCES `etablissement` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `annee_scolaire` */

insert  into `annee_scolaire`(`id`,`etablissement_id`,`libelle_annee_scolaire`,`date_rentre_annee_scolaire`,`date_vacance_annee_scolaire`,`date_initial_annee_scolaire`,`etat_annee_scolaire`) values (1,1,'2016-2017','2016-09-01 00:00:00','2016-09-30 00:00:00','2016-09-07 00:00:00',1);

/*Table structure for table `caisse` */

DROP TABLE IF EXISTS `caisse`;

CREATE TABLE `caisse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `compte` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nom_caisse` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `date_publication` date NOT NULL,
  `etat_ville` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B2A353C8CFF65260` (`compte`),
  CONSTRAINT `FK_B2A353C8CFF65260` FOREIGN KEY (`compte`) REFERENCES `plancomptable` (`compte`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `caisse` */

insert  into `caisse`(`id`,`compte`,`nom_caisse`,`date_publication`,`etat_ville`) values (1,'CPTE00001','Caisse 1','2016-08-29',1);

/*Table structure for table `categorieproduit` */

DROP TABLE IF EXISTS `categorieproduit`;

CREATE TABLE `categorieproduit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `etat` smallint(6) NOT NULL,
  `type` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `categorieproduit` */

/*Table structure for table `classe` */

DROP TABLE IF EXISTS `classe`;

CREATE TABLE `classe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `indice_id` int(11) DEFAULT NULL,
  `etablissement_id` int(11) DEFAULT NULL,
  `libelle_classe` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `etat_classe` int(11) NOT NULL,
  `niveau_id` int(11) DEFAULT NULL,
  `filiere_id` int(11) DEFAULT NULL,
  `description_classe` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8F87BF96C8C0B132` (`indice_id`),
  KEY `IDX_8F87BF96FF631228` (`etablissement_id`),
  KEY `IDX_8F87BF96B3E9C81` (`niveau_id`),
  KEY `IDX_8F87BF96180AA129` (`filiere_id`),
  CONSTRAINT `FK_8F87BF96180AA129` FOREIGN KEY (`filiere_id`) REFERENCES `filiere` (`id`),
  CONSTRAINT `FK_8F87BF96B3E9C81` FOREIGN KEY (`niveau_id`) REFERENCES `niveau` (`id`),
  CONSTRAINT `FK_8F87BF96C8C0B132` FOREIGN KEY (`indice_id`) REFERENCES `indice` (`id`),
  CONSTRAINT `FK_8F87BF96FF631228` FOREIGN KEY (`etablissement_id`) REFERENCES `etablissement` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `classe` */

insert  into `classe`(`id`,`indice_id`,`etablissement_id`,`libelle_classe`,`etat_classe`,`niveau_id`,`filiere_id`,`description_classe`) values (1,1,NULL,'6 éme - 1',1,2,1,'Classe de 6ème');

/*Table structure for table `code_base` */

DROP TABLE IF EXISTS `code_base`;

CREATE TABLE `code_base` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `date_ajout` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_F65F94A077153098` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `code_base` */

insert  into `code_base`(`id`,`code`,`date_ajout`) values (1,'KIV0Y','2016-08-08 13:34:37'),(2,'1D39U','2016-08-08 13:35:32'),(3,'TDJZV','2016-08-08 16:35:24'),(4,'38NVU','2016-08-09 01:18:37'),(5,'JVDUC','2016-08-10 14:54:06'),(6,'NARY8','2016-08-10 14:55:25'),(7,'5KEI3','2016-08-10 14:55:45'),(8,'E7R5D','2016-08-10 14:56:10'),(9,'75BD6','2016-08-10 14:56:45'),(10,'ZG51B','2016-08-11 01:09:17'),(11,'IZFJW','2016-08-11 19:13:54'),(12,'PDRJF','2016-08-11 19:22:25'),(13,'H93WT','2016-08-29 19:30:37'),(14,'E5KWR','2016-09-22 18:01:25'),(15,'Z82WS','2016-09-22 18:03:44'),(18,'Y9L67','2016-09-22 18:43:30'),(21,'CGPJ0','2016-09-30 18:36:40'),(22,'1XWRA','2016-09-30 18:44:17'),(23,'9G75K','2016-09-30 18:54:14'),(25,'5NFQ3','2016-10-01 01:10:33');

/*Table structure for table `commande` */

DROP TABLE IF EXISTS `commande`;

CREATE TABLE `commande` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `abonne_id` int(11) DEFAULT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `fournisseur_id` int(11) DEFAULT NULL,
  `code_commande` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description_produit` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_publication` date NOT NULL,
  `date_commande` date NOT NULL,
  `date_modification` date DEFAULT NULL,
  `montant_commande` int(11) DEFAULT NULL,
  `montant_reste_commande` int(11) DEFAULT NULL,
  `etat_commande` int(11) NOT NULL,
  `type_commande` int(11) NOT NULL,
  `refboncommande` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `annule` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_979CC42BC325A696` (`abonne_id`),
  KEY `IDX_979CC42BFB88E14F` (`utilisateur_id`),
  KEY `IDX_979CC42B670C757F` (`fournisseur_id`),
  CONSTRAINT `FK_979CC42B670C757F` FOREIGN KEY (`fournisseur_id`) REFERENCES `fournisseur` (`id`),
  CONSTRAINT `FK_979CC42BC325A696` FOREIGN KEY (`abonne_id`) REFERENCES `abonne` (`id`),
  CONSTRAINT `FK_979CC42BFB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `commande` */

/*Table structure for table `commandetmp` */

DROP TABLE IF EXISTS `commandetmp`;

CREATE TABLE `commandetmp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int(11) DEFAULT NULL,
  `fournisseur_id` int(11) DEFAULT NULL,
  `abonne_id` int(11) DEFAULT NULL,
  `code_commande` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `description_produit` longtext COLLATE utf8_unicode_ci,
  `date_publication` date DEFAULT NULL,
  `date_commande` date DEFAULT NULL,
  `date_modification` date DEFAULT NULL,
  `type_commande` int(11) NOT NULL,
  `annule` tinyint(1) NOT NULL,
  `refboncommande` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C844962CFB88E14F` (`utilisateur_id`),
  KEY `IDX_C844962C670C757F` (`fournisseur_id`),
  KEY `IDX_C844962CC325A696` (`abonne_id`),
  CONSTRAINT `FK_C844962C670C757F` FOREIGN KEY (`fournisseur_id`) REFERENCES `fournisseur` (`id`),
  CONSTRAINT `FK_C844962CC325A696` FOREIGN KEY (`abonne_id`) REFERENCES `abonne` (`id`),
  CONSTRAINT `FK_C844962CFB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `commandetmp` */

/*Table structure for table `compteur` */

DROP TABLE IF EXISTS `compteur`;

CREATE TABLE `compteur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `annee` int(11) NOT NULL,
  `mois` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `compteur` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `entite` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `type_compt` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `compteur` */

insert  into `compteur`(`id`,`annee`,`mois`,`type`,`compteur`,`entite`,`type_compt`) values (1,2016,10,0,'37','LIVRER','1');

/*Table structure for table `concerner` */

DROP TABLE IF EXISTS `concerner`;

CREATE TABLE `concerner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typedecoupage_id` int(11) DEFAULT NULL,
  `enseignement_id` int(11) DEFAULT NULL,
  `anneescolaire_id` int(11) DEFAULT NULL,
  `etat_concerner` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_ABE9A8667DEA6356` (`anneescolaire_id`),
  KEY `IDX_ABE9A866A8361C65` (`typedecoupage_id`),
  KEY `IDX_ABE9A866ABEC3B20` (`enseignement_id`),
  CONSTRAINT `FK_ABE9A8667DEA6356` FOREIGN KEY (`anneescolaire_id`) REFERENCES `annee_scolaire` (`id`),
  CONSTRAINT `FK_ABE9A866A8361C65` FOREIGN KEY (`typedecoupage_id`) REFERENCES `type_decoupage` (`id`),
  CONSTRAINT `FK_ABE9A866ABEC3B20` FOREIGN KEY (`enseignement_id`) REFERENCES `enseignement` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `concerner` */

insert  into `concerner`(`id`,`typedecoupage_id`,`enseignement_id`,`anneescolaire_id`,`etat_concerner`) values (1,1,1,1,1),(2,1,2,1,1),(3,2,3,1,1),(4,2,4,1,1);

/*Table structure for table `connexion` */

DROP TABLE IF EXISTS `connexion`;

CREATE TABLE `connexion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int(11) DEFAULT NULL,
  `abonne_id` int(11) DEFAULT NULL,
  `eleve_id` int(11) DEFAULT NULL,
  `date_connexion` datetime NOT NULL,
  `date_last_action` datetime DEFAULT NULL,
  `date_deconnexion` datetime DEFAULT NULL,
  `adresse_ip` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `tab_id_actions` longtext COLLATE utf8_unicode_ci NOT NULL,
  `tab_date_actions` longtext COLLATE utf8_unicode_ci NOT NULL,
  `duree_connexion` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_936BF99CFB88E14F` (`utilisateur_id`),
  KEY `IDX_936BF99CC325A696` (`abonne_id`),
  KEY `IDX_936BF99CA6CC7B2` (`eleve_id`),
  CONSTRAINT `FK_936BF99CA6CC7B2` FOREIGN KEY (`eleve_id`) REFERENCES `eleve` (`id`),
  CONSTRAINT `FK_936BF99CC325A696` FOREIGN KEY (`abonne_id`) REFERENCES `abonne` (`id`),
  CONSTRAINT `FK_936BF99CFB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=144 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `connexion` */

insert  into `connexion`(`id`,`utilisateur_id`,`abonne_id`,`eleve_id`,`date_connexion`,`date_last_action`,`date_deconnexion`,`adresse_ip`,`tab_id_actions`,`tab_date_actions`,`duree_connexion`) values (1,1,NULL,NULL,'2016-08-08 11:58:42','2016-08-08 12:16:34','2016-08-08 12:16:34','::1','[0]','[\"08-08-2016 11:58:49\"]','0 heures 17 minutes 52 secondes'),(2,1,NULL,NULL,'2016-08-08 12:16:44','2016-08-08 12:31:49','2016-08-08 12:31:49','::1','','','0 heures 15 minutes 5 secondes'),(3,1,NULL,NULL,'2016-08-08 12:32:46','2016-08-08 12:50:45','2016-08-08 12:50:45','::1','','','0 heures 17 minutes 59 secondes'),(4,1,NULL,NULL,'2016-08-08 12:50:55','2016-08-08 13:06:42','2016-08-08 13:06:42','::1','','','0 heures 15 minutes 47 secondes'),(5,1,NULL,NULL,'2016-08-08 13:06:52','2016-08-08 13:25:51','2016-08-08 13:25:51','::1','[2]','[\"08-08-2016 13:07:12\"]','0 heures 18 minutes 59 secondes'),(6,1,NULL,NULL,'2016-08-08 13:25:59','2016-08-08 13:42:14','2016-08-08 13:42:14','::1','','','0 heures 16 minutes 15 secondes'),(7,1,NULL,NULL,'2016-08-08 13:42:23','2016-08-08 13:57:31','2016-08-08 13:57:31','::1','','','0 heures 15 minutes 8 secondes'),(8,1,NULL,NULL,'2016-08-08 13:57:42','2016-08-08 14:17:09','2016-08-08 14:17:09','::1','','','0 heures 19 minutes 27 secondes'),(9,1,NULL,NULL,'2016-08-08 14:17:19','2016-08-08 15:06:49','2016-08-08 15:06:49','::1','','','0 heures 49 minutes 30 secondes'),(10,1,NULL,NULL,'2016-08-08 15:06:59','2016-08-08 15:22:34','2016-08-08 15:22:34','::1','','','0 heures 15 minutes 35 secondes'),(11,1,NULL,NULL,'2016-08-08 15:22:42','2016-08-08 15:38:13','2016-08-08 15:38:13','::1','','','0 heures 15 minutes 31 secondes'),(12,1,NULL,NULL,'2016-08-08 15:38:22','2016-08-08 16:23:32','2016-08-08 16:23:32','::1','','','0 heures 45 minutes 10 secondes'),(13,1,NULL,NULL,'2016-08-08 16:23:50','2016-08-08 17:25:05','2016-08-08 17:25:05','::1','[3,3,3,3,3,3,3,3,3,3,3,3]','[\"08-08-2016 16:32:15\",\"08-08-2016 16:32:34\",\"08-08-2016 16:36:47\",\"08-08-2016 16:39:49\",\"08-08-2016 16:40:10\",\"08-08-2016 16:40:49\",\"08-08-2016 16:41:56\",\"08-08-2016 16:42:29\",\"08-08-2016 16:43:15\",\"08-08-2016 16:44:19\",\"08-08-2016 16:44:40\",\"08-08-2016 17:25:04\"]','1 heures 1 minutes 15 secondes'),(14,1,NULL,NULL,'2016-08-08 17:25:15','2016-08-08 17:42:17',NULL,'::1','[3,3,3,3,3,3,3,3,3]','[\"08-08-2016 17:25:17\",\"08-08-2016 17:26:00\",\"08-08-2016 17:26:25\",\"08-08-2016 17:26:43\",\"08-08-2016 17:27:13\",\"08-08-2016 17:32:34\",\"08-08-2016 17:37:56\",\"08-08-2016 17:38:48\",\"08-08-2016 17:42:17\"]',''),(15,1,NULL,NULL,'2016-08-08 17:53:28','2016-08-08 19:28:56',NULL,'::1','[0,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3]','[\"08-08-2016 17:53:32\",\"08-08-2016 17:53:56\",\"08-08-2016 17:54:45\",\"08-08-2016 17:57:44\",\"08-08-2016 17:59:29\",\"08-08-2016 18:00:21\",\"08-08-2016 18:01:07\",\"08-08-2016 18:05:04\",\"08-08-2016 18:05:21\",\"08-08-2016 18:05:41\",\"08-08-2016 18:05:42\",\"08-08-2016 18:06:33\",\"08-08-2016 18:13:22\",\"08-08-2016 18:13:42\",\"08-08-2016 18:14:41\",\"08-08-2016 18:15:18\",\"08-08-2016 18:15:39\",\"08-08-2016 18:15:55\",\"08-08-2016 18:16:36\",\"08-08-2016 18:16:48\",\"08-08-2016 18:17:26\",\"08-08-2016 18:17:37\",\"08-08-2016 18:18:03\",\"08-08-2016 18:18:14\",\"08-08-2016 18:18:23\",\"08-08-2016 18:27:19\",\"08-08-2016 18:28:45\",\"08-08-2016 18:32:30\",\"08-08-2016 18:32:46\",\"08-08-2016 18:33:16\",\"08-08-2016 18:33:57\",\"08-08-2016 18:37:15\",\"08-08-2016 18:37:51\",\"08-08-2016 18:38:16\",\"08-08-2016 18:38:56\",\"08-08-2016 18:39:11\",\"08-08-2016 18:39:23\",\"08-08-2016 18:40:27\",\"08-08-2016 18:40:40\",\"08-08-2016 18:41:08\",\"08-08-2016 18:45:07\",\"08-08-2016 18:45:28\",\"08-08-2016 18:45:55\",\"08-08-2016 18:46:16\",\"08-08-2016 18:46:29\",\"08-08-2016 18:47:58\",\"08-08-2016 18:48:08\",\"08-08-2016 18:49:48\",\"08-08-2016 18:50:24\",\"08-08-2016 18:50:34\",\"08-08-2016 18:53:33\",\"08-08-2016 18:55:06\",\"08-08-2016 18:55:24\",\"08-08-2016 18:57:06\",\"08-08-2016 18:57:45\",\"08-08-2016 18:59:32\",\"08-08-2016 19:00:56\",\"08-08-2016 19:01:47\",\"08-08-2016 19:02:13\",\"08-08-2016 19:02:46\",\"08-08-2016 19:03:49\",\"08-08-2016 19:10:05\",\"08-08-2016 19:10:33\",\"08-08-2016 19:11:34\",\"08-08-2016 19:15:31\",\"08-08-2016 19:16:49\",\"08-08-2016 19:18:46\",\"08-08-2016 19:19:09\",\"08-08-2016 19:19:44\",\"08-08-2016 19:25:44\",\"08-08-2016 19:27:05\",\"08-08-2016 19:27:26\",\"08-08-2016 19:28:56\"]',''),(16,1,NULL,NULL,'2016-08-08 19:34:14','2016-08-08 19:58:48',NULL,'::1','[0,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3]','[\"08-08-2016 19:34:18\",\"08-08-2016 19:34:58\",\"08-08-2016 19:35:09\",\"08-08-2016 19:38:26\",\"08-08-2016 19:41:15\",\"08-08-2016 19:42:27\",\"08-08-2016 19:42:59\",\"08-08-2016 19:45:24\",\"08-08-2016 19:47:39\",\"08-08-2016 19:48:28\",\"08-08-2016 19:49:20\",\"08-08-2016 19:50:50\",\"08-08-2016 19:51:22\",\"08-08-2016 19:52:18\",\"08-08-2016 19:54:53\",\"08-08-2016 19:55:40\",\"08-08-2016 19:56:19\",\"08-08-2016 19:57:17\",\"08-08-2016 19:57:44\",\"08-08-2016 19:57:56\",\"08-08-2016 19:58:12\",\"08-08-2016 19:58:23\",\"08-08-2016 19:58:35\",\"08-08-2016 19:58:48\"]',''),(17,1,NULL,NULL,'2016-08-08 21:44:30','2016-08-09 01:09:18','2016-08-09 01:09:18','::1','[0,3,3]','[\"08-08-2016 21:44:34\",\"08-08-2016 21:45:35\",\"09-08-2016 01:09:14\"]','3 heures 24 minutes 48 secondes'),(18,1,NULL,NULL,'2016-08-09 01:09:33','2016-08-09 01:28:54','2016-08-09 01:28:54','::1','[3,3,3]','[\"09-08-2016 01:09:36\",\"09-08-2016 01:11:55\",\"09-08-2016 01:28:51\"]','0 heures 19 minutes 21 secondes'),(19,1,NULL,NULL,'2016-08-09 01:29:07','2016-08-09 01:33:38','2016-08-09 01:33:38','::1','[3,3,3]','[\"09-08-2016 01:29:10\",\"09-08-2016 01:32:40\",\"09-08-2016 01:33:38\"]',''),(20,1,NULL,NULL,'2016-08-09 11:28:44','2016-08-09 11:28:47',NULL,'::1','[0]','[\"09-08-2016 11:28:47\"]',''),(21,1,NULL,NULL,'2016-08-09 13:05:29','2016-08-09 22:15:10','2016-08-09 22:15:10','::1','[0,3,3]','[\"09-08-2016 13:05:33\",\"09-08-2016 13:05:57\",\"09-08-2016 22:15:00\"]','9 heures 9 minutes 41 secondes'),(22,1,NULL,NULL,'2016-08-09 22:15:31','2016-08-09 22:35:17','2016-08-09 22:35:17','::1','[3]','[\"09-08-2016 22:15:35\"]','0 heures 19 minutes 46 secondes'),(23,1,NULL,NULL,'2016-08-09 22:35:48','2016-08-09 22:54:29','2016-08-09 22:54:29','::1','[0]','[\"09-08-2016 22:35:51\"]','0 heures 18 minutes 41 secondes'),(24,1,NULL,NULL,'2016-08-09 22:54:44','2016-08-09 23:12:21','2016-08-09 23:12:21','::1','','','0 heures 17 minutes 37 secondes'),(25,1,NULL,NULL,'2016-08-09 23:12:51','2016-08-10 01:06:30','2016-08-10 01:06:30','::1','','','1 heures 53 minutes 39 secondes'),(26,1,NULL,NULL,'2016-08-10 01:06:40','2016-08-10 01:25:20','2016-08-10 01:25:20','::1','','','0 heures 18 minutes 40 secondes'),(27,1,NULL,NULL,'2016-08-10 01:25:33','2016-08-10 11:13:13','2016-08-10 11:13:13','::1','[3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3]','[\"10-08-2016 01:25:46\",\"10-08-2016 01:28:04\",\"10-08-2016 01:28:45\",\"10-08-2016 01:29:00\",\"10-08-2016 01:31:07\",\"10-08-2016 01:32:35\",\"10-08-2016 01:33:03\",\"10-08-2016 01:35:38\",\"10-08-2016 01:36:35\",\"10-08-2016 01:37:53\",\"10-08-2016 01:38:29\",\"10-08-2016 01:39:14\",\"10-08-2016 01:39:46\",\"10-08-2016 01:40:15\",\"10-08-2016 01:40:45\",\"10-08-2016 01:41:26\",\"10-08-2016 01:41:53\",\"10-08-2016 01:42:44\",\"10-08-2016 01:43:08\",\"10-08-2016 01:43:47\",\"10-08-2016 11:13:10\"]','9 heures 47 minutes 40 secondes'),(28,1,NULL,NULL,'2016-08-10 11:13:24','2016-08-10 12:00:15','2016-08-10 12:00:15','::1','[3,3,3,3,3,3,3,3,3,3,3,3,3]','[\"10-08-2016 11:13:27\",\"10-08-2016 11:13:53\",\"10-08-2016 11:14:25\",\"10-08-2016 11:23:41\",\"10-08-2016 11:31:49\",\"10-08-2016 11:32:45\",\"10-08-2016 11:33:53\",\"10-08-2016 11:34:31\",\"10-08-2016 11:35:37\",\"10-08-2016 11:36:33\",\"10-08-2016 11:36:56\",\"10-08-2016 11:40:18\",\"10-08-2016 11:43:51\"]','0 heures 46 minutes 51 secondes'),(29,1,NULL,NULL,'2016-08-10 12:00:24','2016-08-10 12:15:45','2016-08-10 12:15:45','::1','','','0 heures 15 minutes 21 secondes'),(30,1,NULL,NULL,'2016-08-10 12:15:53','2016-08-10 12:41:02','2016-08-10 12:41:02','::1','','','0 heures 25 minutes 9 secondes'),(31,1,NULL,NULL,'2016-08-10 12:44:30','2016-08-10 13:00:05','2016-08-10 13:00:05','::1','','','0 heures 15 minutes 35 secondes'),(32,1,NULL,NULL,'2016-08-10 13:00:13','2016-08-10 13:16:46','2016-08-10 13:16:46','::1','','','0 heures 16 minutes 33 secondes'),(33,1,NULL,NULL,'2016-08-10 13:25:48','2016-08-10 14:33:55','2016-08-10 14:33:55','::1','','','1 heures 8 minutes 7 secondes'),(34,1,NULL,NULL,'2016-08-10 14:34:04','2016-08-10 14:49:55','2016-08-10 14:49:55','::1','','','0 heures 15 minutes 51 secondes'),(35,1,NULL,NULL,'2016-08-10 14:50:02','2016-08-10 16:20:41','2016-08-10 16:20:41','::1','[3]','[\"10-08-2016 16:20:38\"]','1 heures 30 minutes 39 secondes'),(36,1,NULL,NULL,'2016-08-10 16:20:50','2016-08-10 16:22:16','2016-08-10 16:22:16','::1','[3,3]','[\"10-08-2016 16:20:52\",\"10-08-2016 16:22:16\"]',''),(37,1,NULL,NULL,'2016-08-10 19:31:52','2016-08-11 00:25:44','2016-08-11 00:25:44','::1','[0,3,3]','[\"10-08-2016 19:31:57\",\"10-08-2016 19:33:10\",\"11-08-2016 00:25:39\"]','4 heures 53 minutes 52 secondes'),(38,1,NULL,NULL,'2016-08-11 00:25:55','2016-08-11 01:05:42','2016-08-11 01:05:42','::1','[3]','[\"11-08-2016 00:25:58\"]','0 heures 39 minutes 47 secondes'),(39,1,NULL,NULL,'2016-08-11 01:05:59','2016-08-11 01:22:14','2016-08-11 01:22:14','::1','','','0 heures 16 minutes 15 secondes'),(40,1,NULL,NULL,'2016-08-11 11:49:45','2016-08-11 13:38:07','2016-08-11 13:38:07','::1','[0]','[\"11-08-2016 11:49:49\"]','1 heures 48 minutes 22 secondes'),(41,1,NULL,NULL,'2016-08-11 13:38:24','2016-08-11 14:59:09','2016-08-11 14:59:09','::1','','','1 heures 20 minutes 45 secondes'),(42,1,NULL,NULL,'2016-08-11 14:59:38','2016-08-11 15:18:15','2016-08-11 15:18:15','::1','','','0 heures 18 minutes 37 secondes'),(43,1,NULL,NULL,'2016-08-11 15:18:35','2016-08-11 15:45:17','2016-08-11 15:45:17','::1','','','0 heures 26 minutes 42 secondes'),(44,1,NULL,NULL,'2016-08-11 15:45:35','2016-08-11 15:45:35','2016-08-11 15:45:35','::1','','',''),(45,1,NULL,NULL,'2016-08-11 19:08:03','2016-08-11 19:23:49','2016-08-11 19:23:49','::1','[0,3]','[\"11-08-2016 19:08:05\",\"11-08-2016 19:23:46\"]','0 heures 15 minutes 46 secondes'),(46,1,NULL,NULL,'2016-08-11 19:24:00','2016-08-11 19:50:18','2016-08-11 19:50:18','::1','[3,3]','[\"11-08-2016 19:24:03\",\"11-08-2016 19:50:15\"]','0 heures 26 minutes 18 secondes'),(47,1,NULL,NULL,'2016-08-11 19:50:29','2016-08-11 20:48:25','2016-08-11 20:48:25','::1','[3,3,3,3,3,3]','[\"11-08-2016 19:50:31\",\"11-08-2016 19:51:15\",\"11-08-2016 19:52:46\",\"11-08-2016 19:53:22\",\"11-08-2016 19:55:02\",\"11-08-2016 20:48:22\"]','0 heures 57 minutes 56 secondes'),(48,1,NULL,NULL,'2016-08-11 20:48:33','2016-08-12 14:22:58','2016-08-12 14:22:58','::1','[3]','[\"11-08-2016 20:48:35\"]','17 heures 34 minutes 25 secondes'),(49,1,NULL,NULL,'2016-08-12 14:23:35','2016-08-12 18:55:47','2016-08-12 18:55:47','::1','','','4 heures 32 minutes 12 secondes'),(50,1,NULL,NULL,'2016-08-29 15:47:24','2016-08-29 18:28:07','2016-08-29 18:28:07','::1','[0]','[\"29-08-2016 15:47:30\"]','2 heures 40 minutes 43 secondes'),(51,1,NULL,NULL,'2016-08-29 18:34:24','2016-08-29 18:51:52','2016-08-29 18:51:52','::1','','','0 heures 17 minutes 28 secondes'),(52,1,NULL,NULL,'2016-08-29 18:52:02','2016-08-29 19:24:35','2016-08-29 19:24:35','::1','','','0 heures 32 minutes 33 secondes'),(53,1,NULL,NULL,'2016-08-29 19:24:46','2016-08-29 19:24:46','2016-08-29 19:24:46','::1','','',''),(54,1,NULL,NULL,'2016-08-30 14:49:39','2016-08-30 15:47:21','2016-08-30 15:47:21','::1','[0,8,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7]','[\"30-08-2016 14:49:41\",\"30-08-2016 14:51:26\",\"30-08-2016 14:58:19\",\"30-08-2016 15:02:17\",\"30-08-2016 15:08:39\",\"30-08-2016 15:09:49\",\"30-08-2016 15:13:31\",\"30-08-2016 15:14:38\",\"30-08-2016 15:15:31\",\"30-08-2016 15:18:29\",\"30-08-2016 15:20:36\",\"30-08-2016 15:21:21\",\"30-08-2016 15:22:14\",\"30-08-2016 15:25:30\",\"30-08-2016 15:27:29\",\"30-08-2016 15:32:05\",\"30-08-2016 15:47:19\"]','0 heures 57 minutes 42 secondes'),(55,1,NULL,NULL,'2016-08-30 15:47:36','2016-08-30 16:07:54',NULL,'::1','[7,7,7,7,0]','[\"30-08-2016 15:47:37\",\"30-08-2016 15:54:33\",\"30-08-2016 15:55:23\",\"30-08-2016 16:07:39\",\"30-08-2016 16:07:54\"]',''),(56,1,NULL,NULL,'2016-08-30 16:03:18','2016-08-30 16:11:41',NULL,'::1','[0,7,7]','[\"30-08-2016 16:03:22\",\"30-08-2016 16:04:40\",\"30-08-2016 16:11:41\"]',''),(57,1,NULL,NULL,'2016-08-30 16:16:36','2016-08-30 16:27:54','2016-08-30 16:27:54','::1','[0,7,7,7,7]','[\"30-08-2016 16:16:37\",\"30-08-2016 16:17:53\",\"30-08-2016 16:19:07\",\"30-08-2016 16:23:43\",\"30-08-2016 16:27:54\"]',''),(58,1,NULL,NULL,'2016-08-31 09:36:44','2016-08-31 09:44:02','2016-08-31 09:44:02','::1','[0,0,1,2,8,9,8,8]','[\"31-08-2016 09:36:46\",\"31-08-2016 09:37:39\",\"31-08-2016 09:38:10\",\"31-08-2016 09:38:17\",\"31-08-2016 09:38:32\",\"31-08-2016 09:38:43\",\"31-08-2016 09:38:45\",\"31-08-2016 09:44:02\"]',''),(59,1,NULL,NULL,'2016-09-01 11:01:06','2016-09-02 10:56:12','2016-09-02 10:56:12','::1','[0,2,1,8,9,8,1,12,1,12,1,12,6]','[\"01-09-2016 11:01:11\",\"01-09-2016 11:01:27\",\"01-09-2016 11:01:38\",\"01-09-2016 11:04:36\",\"01-09-2016 11:04:47\",\"01-09-2016 11:04:50\",\"01-09-2016 11:05:00\",\"01-09-2016 11:05:06\",\"01-09-2016 11:05:39\",\"01-09-2016 11:05:49\",\"01-09-2016 11:05:59\",\"01-09-2016 11:06:05\",\"02-09-2016 10:56:11\"]','23 heures 55 minutes 6 secondes'),(60,1,NULL,NULL,'2016-09-02 10:26:13','2016-09-02 10:42:54','2016-09-02 10:42:54','::1','[0]','[\"02-09-2016 10:26:19\"]','0 heures 16 minutes 41 secondes'),(61,1,NULL,NULL,'2016-09-02 10:43:12','2016-09-27 11:26:46','2016-09-27 11:26:46','::1','','','0 heures 43 minutes 34 secondes'),(62,1,NULL,NULL,'2016-09-05 21:34:37','2016-09-05 23:40:00','2016-09-05 23:40:00','::1','[0,2,10]','[\"05-09-2016 21:34:42\",\"05-09-2016 21:35:29\",\"05-09-2016 23:39:55\"]','2 heures 5 minutes 23 secondes'),(63,1,NULL,NULL,'2016-09-05 23:40:12','2016-09-05 23:48:34','2016-09-05 23:48:34','::1','[10,0,2,10,0,1,2,2,2,1,2,1,2]','[\"05-09-2016 23:40:14\",\"05-09-2016 23:40:29\",\"05-09-2016 23:42:41\",\"05-09-2016 23:43:53\",\"05-09-2016 23:43:59\",\"05-09-2016 23:44:06\",\"05-09-2016 23:44:19\",\"05-09-2016 23:44:45\",\"05-09-2016 23:45:21\",\"05-09-2016 23:46:56\",\"05-09-2016 23:47:06\",\"05-09-2016 23:48:02\",\"05-09-2016 23:48:34\"]',''),(64,1,NULL,NULL,'2016-09-07 10:29:09','2016-09-07 15:15:19','2016-09-07 15:15:19','::1','[0,0,0]','[\"07-09-2016 10:29:13\",\"07-09-2016 13:18:11\",\"07-09-2016 15:15:17\"]','4 heures 46 minutes 10 secondes'),(65,1,NULL,NULL,'2016-09-08 13:29:30','2016-09-08 13:47:12','2016-09-08 13:47:12','::1','[0]','[\"08-09-2016 13:29:32\"]','0 heures 17 minutes 42 secondes'),(66,1,NULL,NULL,'2016-09-08 13:47:59','2016-09-08 14:12:58','2016-09-08 14:12:58','::1','','','0 heures 24 minutes 59 secondes'),(67,1,NULL,NULL,'2016-09-08 14:13:18','2016-09-08 14:43:49','2016-09-08 14:43:49','::1','','','0 heures 30 minutes 31 secondes'),(68,1,NULL,NULL,'2016-09-08 14:43:58','2016-09-08 15:02:42','2016-09-08 15:02:42','::1','','','0 heures 18 minutes 44 secondes'),(69,1,NULL,NULL,'2016-09-08 15:02:51','2016-09-08 15:18:40','2016-09-08 15:18:40','::1','','','0 heures 15 minutes 49 secondes'),(70,1,NULL,NULL,'2016-09-08 16:05:25','2016-09-08 16:32:23','2016-09-08 16:32:23','::1','','','0 heures 26 minutes 58 secondes'),(71,1,NULL,NULL,'2016-09-08 16:32:43','2016-09-08 16:32:43','2016-09-08 16:32:43','::1','','',''),(72,1,NULL,NULL,'2016-09-21 17:47:35','2016-09-21 19:13:08','2016-09-21 19:13:08','::1','[0]','[\"21-09-2016 17:47:39\"]','1 heures 25 minutes 33 secondes'),(73,1,NULL,NULL,'2016-09-21 19:13:17','2016-09-22 17:24:14','2016-09-22 17:24:14','::1','[3,3]','[\"21-09-2016 19:13:27\",\"22-09-2016 17:24:05\"]','22 heures 10 minutes 57 secondes'),(74,1,NULL,NULL,'2016-09-22 17:25:11','2016-09-22 17:46:36','2016-09-22 17:46:36','::1','[0,7,7,3]','[\"22-09-2016 17:25:17\",\"22-09-2016 17:28:08\",\"22-09-2016 17:29:06\",\"22-09-2016 17:30:15\"]','0 heures 21 minutes 25 secondes'),(75,1,NULL,NULL,'2016-09-22 17:46:59','2016-09-22 18:13:56','2016-09-22 18:13:56','::1','[7,7,7,7]','[\"22-09-2016 17:48:57\",\"22-09-2016 17:50:02\",\"22-09-2016 17:52:58\",\"22-09-2016 17:57:43\"]','0 heures 26 minutes 57 secondes'),(76,1,NULL,NULL,'2016-09-22 18:19:20','2016-09-22 18:38:20','2016-09-22 18:38:20','::1','','','0 heures 19 minutes 0 secondes'),(77,1,NULL,NULL,'2016-09-22 18:38:34','2016-09-22 19:01:27','2016-09-22 19:01:27','::1','[7,7,3,11,4,11,8,8,8,8,8,9,8,11,14,3,3,14,3]','[\"22-09-2016 18:43:44\",\"22-09-2016 18:44:00\",\"22-09-2016 18:44:56\",\"22-09-2016 18:45:48\",\"22-09-2016 18:48:00\",\"22-09-2016 18:48:59\",\"22-09-2016 18:49:16\",\"22-09-2016 18:50:03\",\"22-09-2016 18:52:52\",\"22-09-2016 18:53:37\",\"22-09-2016 18:54:15\",\"22-09-2016 18:54:25\",\"22-09-2016 18:54:27\",\"22-09-2016 18:54:37\",\"22-09-2016 18:54:47\",\"22-09-2016 19:00:16\",\"22-09-2016 19:00:49\",\"22-09-2016 19:01:04\",\"22-09-2016 19:01:27\"]',''),(78,1,NULL,NULL,'2016-09-27 09:53:57','2016-09-27 10:43:25','2016-09-27 10:43:25','::1','[0,10,0,8,0,0,0,0,0,0,0,0,0]','[\"27-09-2016 09:54:01\",\"27-09-2016 09:55:49\",\"27-09-2016 09:55:57\",\"27-09-2016 09:56:04\",\"27-09-2016 09:56:23\",\"27-09-2016 10:07:10\",\"27-09-2016 10:14:22\",\"27-09-2016 10:15:03\",\"27-09-2016 10:15:20\",\"27-09-2016 10:19:53\",\"27-09-2016 10:21:28\",\"27-09-2016 10:22:29\",\"27-09-2016 10:27:24\"]','0 heures 49 minutes 28 secondes'),(79,1,NULL,NULL,'2016-09-27 10:43:36','2016-09-27 10:59:39','2016-09-27 10:59:39','::1','[0]','[\"27-09-2016 10:44:24\"]','0 heures 16 minutes 3 secondes'),(80,1,NULL,NULL,'2016-09-27 10:59:49','2016-09-27 11:15:08','2016-09-27 11:15:08','::1','','','0 heures 15 minutes 19 secondes'),(81,1,NULL,NULL,'2016-09-27 11:15:16','2016-09-27 13:01:22','2016-09-27 13:01:22','::1','','','1 heures 46 minutes 6 secondes'),(82,1,NULL,NULL,'2016-09-27 11:27:10','2016-09-27 13:01:01','2016-09-27 13:01:01','::1','','','1 heures 33 minutes 51 secondes'),(83,1,NULL,NULL,'2016-09-27 13:01:34','2016-09-27 13:16:50','2016-09-27 13:16:50','::1','','','0 heures 15 minutes 16 secondes'),(84,1,NULL,NULL,'2016-09-27 13:16:59','2016-09-27 13:36:15','2016-09-27 13:36:15','::1','','','0 heures 19 minutes 16 secondes'),(85,1,NULL,NULL,'2016-09-27 13:36:26','2016-09-27 13:51:30','2016-09-27 13:51:30','::1','','','0 heures 15 minutes 4 secondes'),(86,1,NULL,NULL,'2016-09-27 13:51:43','2016-09-27 14:13:57','2016-09-27 14:13:57','::1','','','0 heures 22 minutes 14 secondes'),(87,1,NULL,NULL,'2016-09-27 14:14:06','2016-09-27 14:31:06','2016-09-27 14:31:06','::1','','','0 heures 17 minutes 0 secondes'),(88,1,NULL,NULL,'2016-09-27 14:35:46','2016-09-27 15:06:26','2016-09-27 15:06:26','::1','[5]','[\"27-09-2016 14:41:55\"]','0 heures 30 minutes 40 secondes'),(89,1,NULL,NULL,'2016-09-27 15:07:22','2016-09-27 19:31:30','2016-09-27 19:31:30','::1','[1,12,2,2,2,2,2,2,2,2,13,3,3,7,13,13,13,13,13,13,2,2,8,8,8,8,8,8,8,8,2,13,13,8,2,8,9,8,2,15,10,15,10,15,1,1,1,1,1,1,12,1,12,12,12,12,12,10,10,10,10,10,10,10,1,10,10,2,15,15,11,11,4,4,4,11,14,14]','[\"27-09-2016 15:12:01\",\"27-09-2016 15:12:16\",\"27-09-2016 15:15:11\",\"27-09-2016 15:19:07\",\"27-09-2016 15:19:30\",\"27-09-2016 15:21:26\",\"27-09-2016 15:22:11\",\"27-09-2016 15:25:13\",\"27-09-2016 15:27:39\",\"27-09-2016 15:29:19\",\"27-09-2016 15:31:50\",\"27-09-2016 15:34:58\",\"27-09-2016 15:35:24\",\"27-09-2016 15:35:51\",\"27-09-2016 15:39:10\",\"27-09-2016 15:39:24\",\"27-09-2016 15:40:05\",\"27-09-2016 15:41:26\",\"27-09-2016 15:42:07\",\"27-09-2016 15:43:03\",\"27-09-2016 15:43:17\",\"27-09-2016 15:44:00\",\"27-09-2016 15:45:06\",\"27-09-2016 15:45:38\",\"27-09-2016 15:46:13\",\"27-09-2016 15:46:41\",\"27-09-2016 15:48:32\",\"27-09-2016 15:49:10\",\"27-09-2016 15:49:40\",\"27-09-2016 15:49:56\",\"27-09-2016 15:50:26\",\"27-09-2016 15:50:45\",\"27-09-2016 15:51:20\",\"27-09-2016 15:51:47\",\"27-09-2016 15:52:07\",\"27-09-2016 15:52:14\",\"27-09-2016 15:52:24\",\"27-09-2016 15:52:25\",\"27-09-2016 15:53:00\",\"27-09-2016 15:53:15\",\"27-09-2016 15:53:31\",\"27-09-2016 15:53:37\",\"27-09-2016 15:53:46\",\"27-09-2016 15:53:50\",\"27-09-2016 15:53:58\",\"27-09-2016 15:56:01\",\"27-09-2016 15:58:02\",\"27-09-2016 15:58:50\",\"27-09-2016 15:59:24\",\"27-09-2016 16:00:37\",\"27-09-2016 16:01:06\",\"27-09-2016 16:01:14\",\"27-09-2016 16:01:29\",\"27-09-2016 16:03:15\",\"27-09-2016 16:03:47\",\"27-09-2016 16:04:24\",\"27-09-2016 16:04:45\",\"27-09-2016 16:07:39\",\"27-09-2016 16:09:28\",\"27-09-2016 16:12:41\",\"27-09-2016 16:13:35\",\"27-09-2016 16:13:55\",\"27-09-2016 16:15:00\",\"27-09-2016 16:17:15\",\"27-09-2016 16:17:26\",\"27-09-2016 16:17:36\",\"27-09-2016 16:19:15\",\"27-09-2016 16:21:47\",\"27-09-2016 16:22:01\",\"27-09-2016 16:25:03\",\"27-09-2016 16:25:33\",\"27-09-2016 16:31:00\",\"27-09-2016 16:35:49\",\"27-09-2016 16:39:40\",\"27-09-2016 16:40:28\",\"27-09-2016 16:42:05\",\"27-09-2016 16:42:16\",\"27-09-2016 19:31:27\"]','4 heures 24 minutes 8 secondes'),(90,1,NULL,NULL,'2016-09-27 19:34:39','2016-09-27 19:36:15','2016-09-27 19:36:15','::1','[14,10,10,2,11,8]','[\"27-09-2016 19:34:42\",\"27-09-2016 19:35:27\",\"27-09-2016 19:35:34\",\"27-09-2016 19:35:44\",\"27-09-2016 19:35:46\",\"27-09-2016 19:36:15\"]',''),(91,1,NULL,NULL,'2016-09-28 10:54:17','2016-09-28 14:31:48','2016-09-28 14:31:48','::1','[0,10,1,2,11,8,11,4]','[\"28-09-2016 10:54:21\",\"28-09-2016 10:54:33\",\"28-09-2016 10:54:44\",\"28-09-2016 10:54:57\",\"28-09-2016 10:55:04\",\"28-09-2016 10:55:12\",\"28-09-2016 10:55:19\",\"28-09-2016 10:55:25\"]','3 heures 37 minutes 31 secondes'),(92,1,NULL,NULL,'2016-09-28 14:32:00','2016-09-28 14:56:03','2016-09-28 14:56:03','::1','[11,14,14,11,4,11,14,14]','[\"28-09-2016 14:33:00\",\"28-09-2016 14:33:06\",\"28-09-2016 14:35:27\",\"28-09-2016 14:36:24\",\"28-09-2016 14:37:34\",\"28-09-2016 14:37:53\",\"28-09-2016 14:37:59\",\"28-09-2016 14:38:38\"]','0 heures 24 minutes 3 secondes'),(93,1,NULL,NULL,'2016-09-28 14:56:15','2016-09-28 15:13:36','2016-09-28 15:13:36','::1','','','0 heures 17 minutes 21 secondes'),(94,1,NULL,NULL,'2016-09-28 15:13:47','2016-09-28 15:29:12','2016-09-28 15:29:12','::1','','','0 heures 15 minutes 25 secondes'),(95,1,NULL,NULL,'2016-09-28 15:29:23','2016-09-28 15:47:53','2016-09-28 15:47:53','::1','','','0 heures 18 minutes 30 secondes'),(96,1,NULL,NULL,'2016-09-28 15:48:28','2016-09-28 16:07:03','2016-09-28 16:07:03','::1','','','0 heures 18 minutes 35 secondes'),(97,1,NULL,NULL,'2016-09-28 16:07:15','2016-09-28 16:34:26','2016-09-28 16:34:26','::1','','','0 heures 27 minutes 11 secondes'),(98,1,NULL,NULL,'2016-09-28 16:34:36','2016-09-28 18:19:31','2016-09-28 18:19:31','::1','','','1 heures 44 minutes 55 secondes'),(99,1,NULL,NULL,'2016-09-28 18:19:45','2016-09-28 20:29:50','2016-09-28 20:29:50','::1','[11,4,3,3,3,14,11,14,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,7,7,7,3,3,3,3,3,3,7,3,7,3,3]','[\"28-09-2016 18:19:56\",\"28-09-2016 18:20:10\",\"28-09-2016 18:21:23\",\"28-09-2016 18:24:10\",\"28-09-2016 18:26:27\",\"28-09-2016 18:31:29\",\"28-09-2016 18:31:38\",\"28-09-2016 18:31:46\",\"28-09-2016 18:31:55\",\"28-09-2016 18:35:51\",\"28-09-2016 18:36:12\",\"28-09-2016 18:36:48\",\"28-09-2016 18:37:09\",\"28-09-2016 18:39:17\",\"28-09-2016 18:39:40\",\"28-09-2016 18:40:36\",\"28-09-2016 18:40:55\",\"28-09-2016 18:42:00\",\"28-09-2016 18:43:45\",\"28-09-2016 18:49:50\",\"28-09-2016 18:50:27\",\"28-09-2016 18:51:21\",\"28-09-2016 18:52:06\",\"28-09-2016 18:54:50\",\"28-09-2016 18:55:59\",\"28-09-2016 18:56:17\",\"28-09-2016 18:56:44\",\"28-09-2016 18:58:25\",\"28-09-2016 18:58:42\",\"28-09-2016 18:59:02\",\"28-09-2016 18:59:17\",\"28-09-2016 19:00:18\",\"28-09-2016 19:06:09\",\"28-09-2016 19:06:23\",\"28-09-2016 19:13:31\",\"28-09-2016 19:13:56\",\"28-09-2016 19:14:27\",\"28-09-2016 19:14:58\",\"28-09-2016 19:15:16\",\"28-09-2016 19:15:47\",\"28-09-2016 19:16:12\",\"28-09-2016 19:16:30\",\"28-09-2016 19:16:55\",\"28-09-2016 19:17:08\",\"28-09-2016 19:17:24\",\"28-09-2016 19:17:40\",\"28-09-2016 19:17:57\",\"28-09-2016 19:18:06\",\"28-09-2016 19:18:17\",\"28-09-2016 19:18:31\",\"28-09-2016 19:18:54\",\"28-09-2016 19:19:15\",\"28-09-2016 19:20:41\",\"28-09-2016 19:21:03\",\"28-09-2016 19:21:04\",\"28-09-2016 19:21:31\",\"28-09-2016 19:27:43\",\"28-09-2016 19:34:32\",\"28-09-2016 19:35:19\",\"28-09-2016 19:35:57\",\"28-09-2016 19:36:32\",\"28-09-2016 19:37:22\",\"28-09-2016 19:41:26\",\"28-09-2016 19:41:45\",\"28-09-2016 19:42:15\",\"28-09-2016 19:43:50\",\"28-09-2016 19:43:59\",\"28-09-2016 19:44:36\",\"28-09-2016 19:44:42\",\"28-09-2016 19:44:55\",\"28-09-2016 20:29:47\"]','2 heures 10 minutes 5 secondes'),(100,1,NULL,NULL,'2016-09-28 20:30:04','2016-09-28 22:37:39','2016-09-28 22:37:39','::1','[3,3,3,3,3]','[\"28-09-2016 20:30:06\",\"28-09-2016 20:32:29\",\"28-09-2016 20:33:37\",\"28-09-2016 20:34:13\",\"28-09-2016 22:37:37\"]','2 heures 7 minutes 35 secondes'),(101,1,NULL,NULL,'2016-09-28 21:16:17','2016-09-28 21:25:20',NULL,'::1','[0,3,3,3,3,3,3]','[\"28-09-2016 21:16:20\",\"28-09-2016 21:16:57\",\"28-09-2016 21:17:30\",\"28-09-2016 21:19:29\",\"28-09-2016 21:23:16\",\"28-09-2016 21:24:01\",\"28-09-2016 21:25:20\"]',''),(102,1,NULL,NULL,'2016-09-28 21:33:37','2016-09-28 22:45:15','2016-09-28 22:45:15','::1','[0,3,3,3,3,3,3,3,3,3,3,3,11,1,11,11,0]','[\"28-09-2016 21:33:41\",\"28-09-2016 21:34:05\",\"28-09-2016 21:36:55\",\"28-09-2016 21:37:08\",\"28-09-2016 21:38:07\",\"28-09-2016 21:38:11\",\"28-09-2016 21:39:44\",\"28-09-2016 21:42:37\",\"28-09-2016 21:46:31\",\"28-09-2016 21:49:32\",\"28-09-2016 21:50:01\",\"28-09-2016 21:52:15\",\"28-09-2016 22:02:18\",\"28-09-2016 22:02:31\",\"28-09-2016 22:10:36\",\"28-09-2016 22:11:34\",\"28-09-2016 22:45:12\"]','1 heures 11 minutes 38 secondes'),(103,1,NULL,NULL,'2016-09-28 22:37:59','2016-09-28 23:50:01','2016-09-28 23:50:01','::1','[3,3,3,3,0,0,0,0,1,1,1]','[\"28-09-2016 22:38:01\",\"28-09-2016 22:38:41\",\"28-09-2016 22:41:34\",\"28-09-2016 22:42:09\",\"28-09-2016 22:42:35\",\"28-09-2016 22:42:58\",\"28-09-2016 22:44:51\",\"28-09-2016 22:46:42\",\"28-09-2016 22:47:04\",\"28-09-2016 22:48:42\",\"28-09-2016 23:49:59\"]','1 heures 12 minutes 2 secondes'),(104,1,NULL,NULL,'2016-09-28 22:45:31','2016-09-28 23:04:13',NULL,'::1','[0,3,3,3,3,3,3,1,1,11,10]','[\"28-09-2016 22:45:33\",\"28-09-2016 22:46:04\",\"28-09-2016 22:48:36\",\"28-09-2016 22:49:36\",\"28-09-2016 22:50:01\",\"28-09-2016 22:50:15\",\"28-09-2016 22:50:47\",\"28-09-2016 22:51:28\",\"28-09-2016 22:59:01\",\"28-09-2016 23:03:57\",\"28-09-2016 23:04:13\"]',''),(105,1,NULL,NULL,'2016-09-28 23:50:12','2016-09-29 00:32:51','2016-09-29 00:32:51','::1','[1,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10]','[\"28-09-2016 23:50:14\",\"28-09-2016 23:50:20\",\"28-09-2016 23:50:41\",\"28-09-2016 23:50:55\",\"28-09-2016 23:51:55\",\"28-09-2016 23:52:20\",\"28-09-2016 23:53:17\",\"28-09-2016 23:54:20\",\"28-09-2016 23:59:51\",\"29-09-2016 00:01:10\",\"29-09-2016 00:01:35\",\"29-09-2016 00:02:22\",\"29-09-2016 00:03:26\",\"29-09-2016 00:08:57\",\"29-09-2016 00:10:00\",\"29-09-2016 00:11:35\",\"29-09-2016 00:13:25\",\"29-09-2016 00:14:08\",\"29-09-2016 00:14:29\",\"29-09-2016 00:32:49\"]','0 heures 42 minutes 39 secondes'),(106,1,NULL,NULL,'2016-09-29 00:33:03','2016-09-29 01:59:18','2016-09-29 01:59:18','::1','[10,10,10,1,12,1,10,10,10,10,10,0,10,11,8,0,11,11,11,2,0,0,0,11,11,0,0,0,0,1,0,11,11,2,1,11,11,2,10,2,1,11,8]','[\"29-09-2016 00:33:05\",\"29-09-2016 00:33:31\",\"29-09-2016 00:34:23\",\"29-09-2016 00:34:35\",\"29-09-2016 00:34:52\",\"29-09-2016 00:35:10\",\"29-09-2016 00:35:18\",\"29-09-2016 00:36:54\",\"29-09-2016 00:37:55\",\"29-09-2016 00:40:04\",\"29-09-2016 00:40:27\",\"29-09-2016 00:40:35\",\"29-09-2016 00:40:46\",\"29-09-2016 00:46:16\",\"29-09-2016 00:56:18\",\"29-09-2016 00:57:27\",\"29-09-2016 01:04:45\",\"29-09-2016 01:07:44\",\"29-09-2016 01:11:04\",\"29-09-2016 01:11:12\",\"29-09-2016 01:11:20\",\"29-09-2016 01:12:03\",\"29-09-2016 01:14:06\",\"29-09-2016 01:14:17\",\"29-09-2016 01:17:34\",\"29-09-2016 01:17:50\",\"29-09-2016 01:18:42\",\"29-09-2016 01:18:52\",\"29-09-2016 01:23:07\",\"29-09-2016 01:26:09\",\"29-09-2016 01:26:49\",\"29-09-2016 01:26:58\",\"29-09-2016 01:29:41\",\"29-09-2016 01:29:52\",\"29-09-2016 01:30:00\",\"29-09-2016 01:34:03\",\"29-09-2016 01:37:27\",\"29-09-2016 01:39:11\",\"29-09-2016 01:39:20\",\"29-09-2016 01:39:57\",\"29-09-2016 01:40:03\",\"29-09-2016 01:43:20\",\"29-09-2016 01:43:39\"]','1 heures 26 minutes 15 secondes'),(107,1,NULL,NULL,'2016-09-29 01:59:30','2016-09-29 02:23:57','2016-09-29 02:23:57','::1','','','0 heures 24 minutes 27 secondes'),(108,1,NULL,NULL,'2016-09-29 02:24:07','2016-09-29 02:58:56','2016-09-29 02:58:56','::1','[10,3,10,10]','[\"29-09-2016 02:36:21\",\"29-09-2016 02:37:26\",\"29-09-2016 02:39:34\",\"29-09-2016 02:40:40\"]','0 heures 34 minutes 49 secondes'),(109,1,NULL,NULL,'2016-09-29 03:11:59','2016-09-29 09:11:58','2016-09-29 09:11:58','::1','','','5 heures 59 minutes 59 secondes'),(110,1,NULL,NULL,'2016-09-29 09:12:19','2016-09-29 09:29:33','2016-09-29 09:29:33','::1','','','0 heures 17 minutes 14 secondes'),(111,1,NULL,NULL,'2016-09-29 09:29:50','2016-09-29 10:25:30','2016-09-29 10:25:30','::1','[2,8,8,8,8,8,8,11]','[\"29-09-2016 09:32:55\",\"29-09-2016 09:33:03\",\"29-09-2016 09:37:41\",\"29-09-2016 09:38:06\",\"29-09-2016 09:38:24\",\"29-09-2016 09:38:44\",\"29-09-2016 09:39:06\",\"29-09-2016 09:45:57\"]','0 heures 55 minutes 40 secondes'),(112,1,NULL,NULL,'2016-09-29 10:25:43','2016-09-29 10:25:43','2016-09-29 10:25:43','::1','','',''),(113,1,NULL,NULL,'2016-09-29 16:36:39','2016-09-29 18:01:19','2016-09-29 18:01:19','::1','[0,3]','[\"29-09-2016 16:36:42\",\"29-09-2016 16:38:47\"]','1 heures 24 minutes 40 secondes'),(114,1,NULL,NULL,'2016-09-29 18:02:13','2016-09-29 18:02:46','2016-09-29 18:02:46','::1','[0,10]','[\"29-09-2016 18:02:17\",\"29-09-2016 18:02:46\"]',''),(115,1,NULL,NULL,'2016-09-30 13:09:52','2016-09-30 17:47:36','2016-09-30 17:47:36','::1','[0,11,4,2,13,13,2,13,13,13,2,13,11,4,11,4,4,4,4,4,4,17,8,9,8,4,17,4,17,4,17,4,17,4,17,17,4,4,17,17,4,4,4,4,4,4,11,10,10,10,10,10,17,10,17,10,10,17,18,8,9,8,18,10,10,10,10,17,18,10,18,10,10,17,10,10,10,17,10,17,17,10]','[\"30-09-2016 13:09:55\",\"30-09-2016 13:10:26\",\"30-09-2016 13:10:32\",\"30-09-2016 13:12:57\",\"30-09-2016 13:13:03\",\"30-09-2016 13:13:26\",\"30-09-2016 13:13:31\",\"30-09-2016 13:13:45\",\"30-09-2016 13:16:18\",\"30-09-2016 13:17:11\",\"30-09-2016 13:17:14\",\"30-09-2016 13:20:52\",\"30-09-2016 13:21:50\",\"30-09-2016 13:34:19\",\"30-09-2016 13:46:10\",\"30-09-2016 13:46:14\",\"30-09-2016 13:46:41\",\"30-09-2016 13:48:05\",\"30-09-2016 13:52:48\",\"30-09-2016 13:53:50\",\"30-09-2016 13:54:45\",\"30-09-2016 13:54:54\",\"30-09-2016 13:55:13\",\"30-09-2016 13:55:19\",\"30-09-2016 13:55:21\",\"30-09-2016 13:55:31\",\"30-09-2016 13:55:44\",\"30-09-2016 13:56:24\",\"30-09-2016 13:56:34\",\"30-09-2016 13:57:05\",\"30-09-2016 13:57:12\",\"30-09-2016 14:00:26\",\"30-09-2016 14:00:36\",\"30-09-2016 14:01:25\",\"30-09-2016 14:01:38\",\"30-09-2016 14:01:46\",\"30-09-2016 14:02:05\",\"30-09-2016 14:06:33\",\"30-09-2016 14:07:59\",\"30-09-2016 14:08:03\",\"30-09-2016 14:08:10\",\"30-09-2016 14:08:36\",\"30-09-2016 14:08:54\",\"30-09-2016 14:09:57\",\"30-09-2016 14:10:09\",\"30-09-2016 14:10:46\",\"30-09-2016 14:10:48\",\"30-09-2016 14:22:57\",\"30-09-2016 14:26:50\",\"30-09-2016 14:34:54\",\"30-09-2016 14:35:43\",\"30-09-2016 14:36:20\",\"30-09-2016 14:36:33\",\"30-09-2016 14:37:17\",\"30-09-2016 14:37:26\",\"30-09-2016 14:37:56\",\"30-09-2016 14:38:36\",\"30-09-2016 14:38:45\",\"30-09-2016 14:38:57\",\"30-09-2016 14:39:04\",\"30-09-2016 14:39:12\",\"30-09-2016 14:39:14\",\"30-09-2016 14:39:26\",\"30-09-2016 14:39:28\",\"30-09-2016 14:39:54\",\"30-09-2016 14:40:13\",\"30-09-2016 14:40:45\",\"30-09-2016 14:40:57\",\"30-09-2016 14:41:03\",\"30-09-2016 14:41:05\",\"30-09-2016 14:41:21\",\"30-09-2016 14:41:23\",\"30-09-2016 14:48:20\",\"30-09-2016 14:48:42\",\"30-09-2016 14:49:07\",\"30-09-2016 14:50:05\",\"30-09-2016 14:50:39\",\"30-09-2016 14:50:49\",\"30-09-2016 14:51:29\",\"30-09-2016 14:51:39\",\"30-09-2016 14:53:45\",\"30-09-2016 17:47:34\"]','4 heures 37 minutes 44 secondes'),(116,1,NULL,NULL,'2016-09-30 17:47:47','2016-09-30 18:06:46','2016-09-30 18:06:46','::1','[10]','[\"30-09-2016 17:47:48\"]','0 heures 18 minutes 59 secondes'),(117,1,NULL,NULL,'2016-09-30 18:06:55','2016-09-30 18:25:15','2016-09-30 18:25:15','::1','','','0 heures 18 minutes 20 secondes'),(118,1,NULL,NULL,'2016-09-30 18:25:27','2016-09-30 18:41:24','2016-09-30 18:41:24','::1','','','0 heures 15 minutes 57 secondes'),(119,1,NULL,NULL,'2016-09-30 18:41:36','2016-09-30 18:41:36','2016-09-30 18:41:36','::1','','',''),(120,1,NULL,NULL,'2016-09-30 22:33:32','2016-09-30 22:33:35','2016-09-30 22:33:35','::1','[0]','[\"30-09-2016 22:33:35\"]',''),(121,1,NULL,NULL,'2016-10-01 01:05:01','2016-10-01 01:20:43','2016-10-01 01:20:43','::1','[0,3]','[\"01-10-2016 01:05:04\",\"01-10-2016 01:20:43\"]',''),(122,1,NULL,NULL,'2016-10-02 10:29:08','2016-10-02 11:05:08','2016-10-02 11:05:08','::1','[0,11,4,4,4,4,4,4,17,4,11,4,17,4,11,10,10,10,10,10]','[\"02-10-2016 10:29:11\",\"02-10-2016 10:29:24\",\"02-10-2016 10:29:33\",\"02-10-2016 10:42:49\",\"02-10-2016 10:43:46\",\"02-10-2016 10:46:53\",\"02-10-2016 10:49:53\",\"02-10-2016 10:51:42\",\"02-10-2016 10:52:13\",\"02-10-2016 10:53:41\",\"02-10-2016 10:53:45\",\"02-10-2016 10:54:57\",\"02-10-2016 10:56:11\",\"02-10-2016 10:56:19\",\"02-10-2016 10:56:22\",\"02-10-2016 11:02:03\",\"02-10-2016 11:02:16\",\"02-10-2016 11:02:20\",\"02-10-2016 11:02:22\",\"02-10-2016 11:05:01\"]','0 heures 36 minutes 0 secondes'),(123,1,NULL,NULL,'2016-10-02 11:05:21','2016-10-04 00:14:43','2016-10-04 00:14:43','::1','[0,11,10,10,10,10,10,8,9,8,19,19,19,19,19,19,19,19,19,19,19,19,19,19,19,19,19,19,19,19,19,19,19,19,19,19,19,19,19,19,19,19,19,19,19,19,19,19,19,19,19,20,8,9,8,19,20,19,20,19,20,19,20,19,20,19,20,19,20,19,20,19,20,19,20,19,20,19,20,19,20,19,20,19,20,19,20,19,20,19,20,19,20,19,20,19,20,19,20,19,19,20,19,19,20,19,20,19,20,19,20,19,20,19,19,20,19,19,20,19,19,20,19,19,20,19,20,19,20,19,20,19,20,19,20,19,20,19,19,19,20,19,20,19,19,20,19,20,19,20,19,20,19,20,19,20,19,20,19,20,19,20,19,20,19,20,19,20,19,20,19,20,19,20,19,20,19,20,20,19,20,20,20,19,20,20,19,20,20,19,20,19,20,20,19,20,19,19,20,19,19,20,19,20,20,19,20,20,20,20,19,20,19,19,20,19,20,19,20,19,20,19,20,19,20,19,20,19,20,20,19,20,20,20,19,20,20,20,20,20,19,20,20,20,20,20,19,20,19,20,19,20,20,20,20,20,20,20,19,20,20,20,19,20,19,20,19,20,20,20,20,19,20,20,20,20,19,20,20,20,20,20,19,20,20,20,19,20,20,20,19,20,19,20,20,20,19,20,20,20,20,19,20,20,20,19,20,20,20,20,19,20,20,20,19,20,20,20,19,20,20,19,20,20,19,20,20,20,20,19,20,20,19,20,20,19,20,20,19,20,20,19,20,20,19,20,20,20,19,20,20,19,20,20,19,20,20,19,20,20,19,20,20,19,20,20,19,20,20,19,20,20,19,20,20,20,19,20,20,20,20,20,19,20,20,20,20,20,19,20,20,20,20,19,20,20,20,19,20,20,20,20,19,19,20,19,20,20,20,20,19,20,20,20,20,20,19,20,20,20,19,20,20,20,20,20,19,20,20,20]','[\"02-10-2016 11:05:25\",\"02-10-2016 11:05:33\",\"02-10-2016 11:05:38\",\"02-10-2016 11:05:51\",\"02-10-2016 11:06:55\",\"02-10-2016 11:07:19\",\"02-10-2016 11:09:27\",\"03-10-2016 13:05:14\",\"03-10-2016 13:05:24\",\"03-10-2016 13:05:27\",\"03-10-2016 13:05:52\",\"03-10-2016 13:06:49\",\"03-10-2016 13:07:41\",\"03-10-2016 13:08:22\",\"03-10-2016 13:09:50\",\"03-10-2016 13:11:29\",\"03-10-2016 13:13:12\",\"03-10-2016 13:14:08\",\"03-10-2016 13:15:08\",\"03-10-2016 13:21:10\",\"03-10-2016 13:22:39\",\"03-10-2016 13:23:04\",\"03-10-2016 13:23:53\",\"03-10-2016 13:24:53\",\"03-10-2016 13:26:21\",\"03-10-2016 13:51:19\",\"03-10-2016 13:52:51\",\"03-10-2016 13:55:19\",\"03-10-2016 13:55:32\",\"03-10-2016 13:57:52\",\"03-10-2016 13:58:00\",\"03-10-2016 14:05:12\",\"03-10-2016 14:05:29\",\"03-10-2016 14:05:39\",\"03-10-2016 14:06:07\",\"03-10-2016 14:08:40\",\"03-10-2016 14:09:09\",\"03-10-2016 14:10:05\",\"03-10-2016 14:10:15\",\"03-10-2016 14:36:11\",\"03-10-2016 14:36:20\",\"03-10-2016 14:41:11\",\"03-10-2016 14:41:16\",\"03-10-2016 14:42:25\",\"03-10-2016 14:42:29\",\"03-10-2016 14:44:16\",\"03-10-2016 14:44:20\",\"03-10-2016 14:44:40\",\"03-10-2016 14:44:49\",\"03-10-2016 14:45:34\",\"03-10-2016 14:46:57\",\"03-10-2016 14:47:02\",\"03-10-2016 14:47:16\",\"03-10-2016 14:47:25\",\"03-10-2016 14:47:27\",\"03-10-2016 14:47:47\",\"03-10-2016 14:47:52\",\"03-10-2016 14:48:33\",\"03-10-2016 14:48:38\",\"03-10-2016 14:49:10\",\"03-10-2016 14:49:15\",\"03-10-2016 14:50:02\",\"03-10-2016 14:50:07\",\"03-10-2016 14:56:44\",\"03-10-2016 14:56:50\",\"03-10-2016 15:03:29\",\"03-10-2016 15:03:33\",\"03-10-2016 15:03:44\",\"03-10-2016 15:03:48\",\"03-10-2016 15:04:10\",\"03-10-2016 15:04:14\",\"03-10-2016 15:09:36\",\"03-10-2016 15:09:41\",\"03-10-2016 15:09:48\",\"03-10-2016 15:09:52\",\"03-10-2016 15:13:53\",\"03-10-2016 15:13:58\",\"03-10-2016 15:15:16\",\"03-10-2016 15:15:20\",\"03-10-2016 15:16:12\",\"03-10-2016 15:16:17\",\"03-10-2016 15:17:11\",\"03-10-2016 15:17:15\",\"03-10-2016 15:17:23\",\"03-10-2016 15:17:27\",\"03-10-2016 15:23:35\",\"03-10-2016 15:23:40\",\"03-10-2016 15:23:45\",\"03-10-2016 15:23:52\",\"03-10-2016 15:26:26\",\"03-10-2016 15:26:31\",\"03-10-2016 15:27:31\",\"03-10-2016 15:27:35\",\"03-10-2016 15:27:43\",\"03-10-2016 15:27:49\",\"03-10-2016 15:31:55\",\"03-10-2016 15:32:24\",\"03-10-2016 15:33:55\",\"03-10-2016 15:34:02\",\"03-10-2016 15:34:58\",\"03-10-2016 15:36:01\",\"03-10-2016 15:36:05\",\"03-10-2016 15:36:11\",\"03-10-2016 15:39:40\",\"03-10-2016 15:39:44\",\"03-10-2016 15:40:57\",\"03-10-2016 15:41:01\",\"03-10-2016 15:42:04\",\"03-10-2016 15:42:42\",\"03-10-2016 15:44:12\",\"03-10-2016 15:44:16\",\"03-10-2016 15:46:05\",\"03-10-2016 15:46:10\",\"03-10-2016 15:46:14\",\"03-10-2016 15:47:02\",\"03-10-2016 15:47:07\",\"03-10-2016 15:47:12\",\"03-10-2016 15:48:29\",\"03-10-2016 15:48:37\",\"03-10-2016 15:48:39\",\"03-10-2016 15:50:10\",\"03-10-2016 15:50:16\",\"03-10-2016 15:50:19\",\"03-10-2016 15:51:41\",\"03-10-2016 15:51:48\",\"03-10-2016 15:51:53\",\"03-10-2016 15:51:58\",\"03-10-2016 16:44:56\",\"03-10-2016 16:45:02\",\"03-10-2016 16:45:06\",\"03-10-2016 16:45:11\",\"03-10-2016 16:46:53\",\"03-10-2016 16:46:58\",\"03-10-2016 16:49:18\",\"03-10-2016 16:49:23\",\"03-10-2016 16:49:27\",\"03-10-2016 16:50:04\",\"03-10-2016 16:53:33\",\"03-10-2016 16:53:45\",\"03-10-2016 16:54:17\",\"03-10-2016 16:54:21\",\"03-10-2016 16:54:25\",\"03-10-2016 16:54:29\",\"03-10-2016 16:54:34\",\"03-10-2016 16:56:26\",\"03-10-2016 16:56:30\",\"03-10-2016 16:56:36\",\"03-10-2016 16:56:43\",\"03-10-2016 16:56:46\",\"03-10-2016 16:56:54\",\"03-10-2016 16:56:58\",\"03-10-2016 16:57:03\",\"03-10-2016 16:59:41\",\"03-10-2016 16:59:46\",\"03-10-2016 17:01:17\",\"03-10-2016 17:01:22\",\"03-10-2016 18:04:17\",\"03-10-2016 18:04:23\",\"03-10-2016 18:05:01\",\"03-10-2016 18:05:05\",\"03-10-2016 18:05:14\",\"03-10-2016 18:05:21\",\"03-10-2016 18:14:39\",\"03-10-2016 18:14:44\",\"03-10-2016 18:17:17\",\"03-10-2016 18:17:21\",\"03-10-2016 18:17:31\",\"03-10-2016 18:17:35\",\"03-10-2016 18:32:26\",\"03-10-2016 18:32:32\",\"03-10-2016 18:35:09\",\"03-10-2016 18:35:17\",\"03-10-2016 18:38:03\",\"03-10-2016 18:38:07\",\"03-10-2016 18:38:58\",\"03-10-2016 18:39:02\",\"03-10-2016 18:42:01\",\"03-10-2016 18:42:05\",\"03-10-2016 18:42:09\",\"03-10-2016 18:43:28\",\"03-10-2016 18:43:32\",\"03-10-2016 18:43:41\",\"03-10-2016 18:44:04\",\"03-10-2016 18:45:51\",\"03-10-2016 18:45:57\",\"03-10-2016 18:46:01\",\"03-10-2016 18:46:55\",\"03-10-2016 18:47:01\",\"03-10-2016 18:47:06\",\"03-10-2016 18:47:34\",\"03-10-2016 18:47:38\",\"03-10-2016 18:49:19\",\"03-10-2016 18:49:23\",\"03-10-2016 18:49:26\",\"03-10-2016 18:54:49\",\"03-10-2016 18:54:54\",\"03-10-2016 18:54:59\",\"03-10-2016 18:55:55\",\"03-10-2016 18:56:00\",\"03-10-2016 18:56:05\",\"03-10-2016 18:56:37\",\"03-10-2016 18:56:41\",\"03-10-2016 18:56:47\",\"03-10-2016 18:56:55\",\"03-10-2016 18:57:03\",\"03-10-2016 18:57:19\",\"03-10-2016 18:57:26\",\"03-10-2016 18:59:11\",\"03-10-2016 18:59:16\",\"03-10-2016 18:59:19\",\"03-10-2016 18:59:38\",\"03-10-2016 18:59:43\",\"03-10-2016 19:01:08\",\"03-10-2016 19:06:20\",\"03-10-2016 19:06:49\",\"03-10-2016 19:08:40\",\"03-10-2016 19:09:23\",\"03-10-2016 19:13:05\",\"03-10-2016 19:13:41\",\"03-10-2016 19:15:45\",\"03-10-2016 19:15:55\",\"03-10-2016 19:18:52\",\"03-10-2016 19:18:56\",\"03-10-2016 19:20:20\",\"03-10-2016 19:20:25\",\"03-10-2016 19:20:36\",\"03-10-2016 19:20:40\",\"03-10-2016 19:21:59\",\"03-10-2016 19:22:05\",\"03-10-2016 19:22:12\",\"03-10-2016 19:33:25\",\"03-10-2016 19:33:32\",\"03-10-2016 19:33:50\",\"03-10-2016 19:34:05\",\"03-10-2016 19:35:26\",\"03-10-2016 19:35:30\",\"03-10-2016 19:35:34\",\"03-10-2016 19:35:39\",\"03-10-2016 19:35:50\",\"03-10-2016 19:35:54\",\"03-10-2016 19:37:36\",\"03-10-2016 19:37:40\",\"03-10-2016 19:37:44\",\"03-10-2016 19:37:48\",\"03-10-2016 19:37:53\",\"03-10-2016 19:37:57\",\"03-10-2016 19:39:14\",\"03-10-2016 19:39:22\",\"03-10-2016 19:39:50\",\"03-10-2016 19:40:01\",\"03-10-2016 19:41:29\",\"03-10-2016 19:41:35\",\"03-10-2016 19:41:39\",\"03-10-2016 19:41:49\",\"03-10-2016 19:42:13\",\"03-10-2016 19:42:57\",\"03-10-2016 19:43:11\",\"03-10-2016 19:43:36\",\"03-10-2016 19:44:42\",\"03-10-2016 19:44:46\",\"03-10-2016 19:44:49\",\"03-10-2016 19:44:53\",\"03-10-2016 19:44:56\",\"03-10-2016 19:45:01\",\"03-10-2016 19:47:57\",\"03-10-2016 19:48:01\",\"03-10-2016 19:50:13\",\"03-10-2016 19:50:18\",\"03-10-2016 19:50:22\",\"03-10-2016 19:50:26\",\"03-10-2016 19:50:30\",\"03-10-2016 19:50:34\",\"03-10-2016 19:50:39\",\"03-10-2016 19:50:45\",\"03-10-2016 19:50:48\",\"03-10-2016 19:50:52\",\"03-10-2016 19:50:56\",\"03-10-2016 19:51:01\",\"03-10-2016 19:51:06\",\"03-10-2016 19:51:09\",\"03-10-2016 19:51:12\",\"03-10-2016 19:51:18\",\"03-10-2016 19:51:21\",\"03-10-2016 19:51:26\",\"03-10-2016 19:51:29\",\"03-10-2016 19:54:05\",\"03-10-2016 19:56:13\",\"03-10-2016 19:56:18\",\"03-10-2016 19:56:21\",\"03-10-2016 19:56:26\",\"03-10-2016 19:58:28\",\"03-10-2016 19:58:34\",\"03-10-2016 20:02:14\",\"03-10-2016 20:02:19\",\"03-10-2016 20:02:22\",\"03-10-2016 20:02:26\",\"03-10-2016 20:02:30\",\"03-10-2016 20:02:34\",\"03-10-2016 20:02:38\",\"03-10-2016 20:02:41\",\"03-10-2016 20:02:44\",\"03-10-2016 20:02:48\",\"03-10-2016 20:02:52\",\"03-10-2016 20:02:56\",\"03-10-2016 20:02:59\",\"03-10-2016 20:03:04\",\"03-10-2016 20:03:09\",\"03-10-2016 20:03:12\",\"03-10-2016 20:03:16\",\"03-10-2016 20:03:19\",\"03-10-2016 20:16:02\",\"03-10-2016 20:16:06\",\"03-10-2016 20:16:10\",\"03-10-2016 20:16:20\",\"03-10-2016 20:17:01\",\"03-10-2016 20:17:06\",\"03-10-2016 20:17:10\",\"03-10-2016 20:17:13\",\"03-10-2016 22:42:49\",\"03-10-2016 22:42:53\",\"03-10-2016 22:42:57\",\"03-10-2016 22:51:28\",\"03-10-2016 22:51:41\",\"03-10-2016 22:51:49\",\"03-10-2016 22:54:40\",\"03-10-2016 22:54:44\",\"03-10-2016 22:54:48\",\"03-10-2016 22:54:56\",\"03-10-2016 22:55:46\",\"03-10-2016 22:57:37\",\"03-10-2016 22:57:41\",\"03-10-2016 22:57:43\",\"03-10-2016 23:01:49\",\"03-10-2016 23:01:53\",\"03-10-2016 23:01:56\",\"03-10-2016 23:03:50\",\"03-10-2016 23:03:54\",\"03-10-2016 23:03:57\",\"03-10-2016 23:07:26\",\"03-10-2016 23:07:30\",\"03-10-2016 23:07:34\",\"03-10-2016 23:08:51\",\"03-10-2016 23:08:55\",\"03-10-2016 23:08:58\",\"03-10-2016 23:10:18\",\"03-10-2016 23:10:22\",\"03-10-2016 23:10:27\",\"03-10-2016 23:15:50\",\"03-10-2016 23:17:10\",\"03-10-2016 23:17:14\",\"03-10-2016 23:17:17\",\"03-10-2016 23:19:44\",\"03-10-2016 23:19:48\",\"03-10-2016 23:19:51\",\"03-10-2016 23:23:48\",\"03-10-2016 23:23:56\",\"03-10-2016 23:23:59\",\"03-10-2016 23:34:58\",\"03-10-2016 23:35:02\",\"03-10-2016 23:35:06\",\"03-10-2016 23:36:07\",\"03-10-2016 23:36:14\",\"03-10-2016 23:36:19\",\"03-10-2016 23:37:47\",\"03-10-2016 23:37:52\",\"03-10-2016 23:37:56\",\"03-10-2016 23:38:19\",\"03-10-2016 23:38:23\",\"03-10-2016 23:38:28\",\"03-10-2016 23:44:01\",\"03-10-2016 23:44:05\",\"03-10-2016 23:44:11\",\"03-10-2016 23:44:54\",\"03-10-2016 23:44:58\",\"03-10-2016 23:45:02\",\"03-10-2016 23:45:40\",\"03-10-2016 23:47:40\",\"03-10-2016 23:50:54\",\"03-10-2016 23:50:57\",\"03-10-2016 23:51:39\",\"03-10-2016 23:53:15\",\"03-10-2016 23:53:25\",\"03-10-2016 23:54:18\",\"03-10-2016 23:54:22\",\"03-10-2016 23:54:25\",\"03-10-2016 23:54:46\",\"03-10-2016 23:55:01\",\"03-10-2016 23:55:17\",\"03-10-2016 23:55:50\",\"03-10-2016 23:55:55\",\"03-10-2016 23:55:58\",\"03-10-2016 23:56:13\",\"03-10-2016 23:57:00\",\"03-10-2016 23:59:45\",\"03-10-2016 23:59:52\",\"03-10-2016 23:59:56\",\"04-10-2016 00:01:03\",\"04-10-2016 00:02:47\",\"04-10-2016 00:02:52\",\"04-10-2016 00:03:00\",\"04-10-2016 00:03:31\",\"04-10-2016 00:04:06\",\"04-10-2016 00:04:42\",\"04-10-2016 00:04:47\",\"04-10-2016 00:04:51\",\"04-10-2016 00:05:16\",\"04-10-2016 00:05:21\",\"04-10-2016 00:05:25\",\"04-10-2016 00:06:08\",\"04-10-2016 00:07:10\",\"04-10-2016 00:09:55\",\"04-10-2016 00:09:59\",\"04-10-2016 00:10:03\",\"04-10-2016 00:10:19\",\"04-10-2016 00:10:31\",\"04-10-2016 00:10:43\",\"04-10-2016 00:11:04\",\"04-10-2016 00:11:11\",\"04-10-2016 00:11:15\",\"04-10-2016 00:11:44\",\"04-10-2016 00:13:16\",\"04-10-2016 00:13:21\",\"04-10-2016 00:13:24\",\"04-10-2016 00:13:36\",\"04-10-2016 00:13:46\",\"04-10-2016 00:13:54\",\"04-10-2016 00:14:20\",\"04-10-2016 00:14:26\",\"04-10-2016 00:14:30\",\"04-10-2016 00:14:43\"]',''),(124,1,NULL,NULL,'2016-10-04 13:30:56','2016-10-04 13:37:21',NULL,'::1','[0,11]','[\"04-10-2016 13:30:59\",\"04-10-2016 13:37:21\"]',''),(125,1,NULL,NULL,'2016-10-04 14:48:17','2016-10-04 14:59:09','2016-10-04 14:59:09','::1','[0,3]','[\"04-10-2016 14:48:20\",\"04-10-2016 14:59:09\"]',''),(126,1,NULL,NULL,'2016-10-04 21:02:16','2016-10-06 00:33:01','2016-10-06 00:33:01','::1','[0,19,20,20,20,19,20,20,20,19,20,20,20,19,20,20,20,20,19,20,20,20,20,19,20,20,20,20,19,20,20,20,19,20,20,20,19,20,20,20,19,20,20,19,20,20,20,20,19,20,20,20,19,20,20,20,19,20,20,20,19,20,20,20,19,20,20,20,8,9,8,21,21,21,21,21,19,20,20,19,20,20,19,20,20,19,20,20,19,19,19,20,20,19,20,20,20,19,20,20,20,19,20,20,19,20,20,20,19,20,20,19,20,20,19,20,20,19,19,20,20,19,19,20,20,19,19,20,20,19,20,20,19,20,20,19,20,20,20,19,20,20,19,20,20,19,20,20,19,20,20,19,20,20,20,19,20,20,20,19,19,20,20,20,19,20,20,21,19,20,20,19,19,20,20,19,22,22,8,9,8,22,22,22,22,22,22,22,22,22,22,22,4,22,22,22,22,22,22,22,22,22]','[\"04-10-2016 21:02:21\",\"04-10-2016 21:05:05\",\"04-10-2016 21:05:10\",\"04-10-2016 21:05:15\",\"04-10-2016 21:05:25\",\"04-10-2016 21:09:17\",\"04-10-2016 21:09:21\",\"04-10-2016 21:09:25\",\"04-10-2016 21:09:32\",\"04-10-2016 21:14:27\",\"04-10-2016 21:14:31\",\"04-10-2016 21:14:34\",\"04-10-2016 21:14:38\",\"04-10-2016 21:15:36\",\"04-10-2016 21:15:41\",\"04-10-2016 21:15:44\",\"04-10-2016 21:15:52\",\"04-10-2016 21:15:55\",\"04-10-2016 21:16:23\",\"04-10-2016 21:16:27\",\"04-10-2016 21:16:37\",\"04-10-2016 21:16:46\",\"04-10-2016 21:16:52\",\"04-10-2016 21:18:10\",\"04-10-2016 21:18:14\",\"04-10-2016 21:18:17\",\"04-10-2016 21:18:21\",\"04-10-2016 21:18:23\",\"04-10-2016 21:25:13\",\"04-10-2016 21:25:17\",\"04-10-2016 21:25:20\",\"04-10-2016 21:25:25\",\"04-10-2016 21:27:23\",\"04-10-2016 21:27:27\",\"04-10-2016 21:27:30\",\"04-10-2016 21:27:40\",\"04-10-2016 21:30:02\",\"04-10-2016 21:30:07\",\"04-10-2016 21:30:10\",\"04-10-2016 21:30:14\",\"04-10-2016 21:31:57\",\"04-10-2016 21:32:02\",\"04-10-2016 21:32:06\",\"04-10-2016 21:33:25\",\"04-10-2016 21:33:29\",\"04-10-2016 21:33:32\",\"04-10-2016 21:33:35\",\"04-10-2016 21:33:38\",\"04-10-2016 21:34:16\",\"04-10-2016 21:34:20\",\"04-10-2016 21:34:23\",\"04-10-2016 21:34:54\",\"04-10-2016 21:36:03\",\"04-10-2016 21:36:07\",\"04-10-2016 21:36:10\",\"04-10-2016 21:36:19\",\"04-10-2016 21:38:55\",\"04-10-2016 21:38:58\",\"04-10-2016 21:39:03\",\"04-10-2016 21:39:22\",\"04-10-2016 21:40:20\",\"04-10-2016 21:40:26\",\"04-10-2016 21:40:28\",\"04-10-2016 21:40:38\",\"04-10-2016 23:39:01\",\"04-10-2016 23:39:05\",\"04-10-2016 23:39:08\",\"04-10-2016 23:39:23\",\"04-10-2016 23:41:22\",\"04-10-2016 23:41:30\",\"04-10-2016 23:41:32\",\"04-10-2016 23:41:43\",\"04-10-2016 23:42:09\",\"04-10-2016 23:42:55\",\"04-10-2016 23:43:25\",\"04-10-2016 23:44:21\",\"04-10-2016 23:49:16\",\"04-10-2016 23:49:20\",\"04-10-2016 23:49:23\",\"04-10-2016 23:50:30\",\"04-10-2016 23:50:34\",\"04-10-2016 23:50:37\",\"04-10-2016 23:51:31\",\"04-10-2016 23:51:34\",\"04-10-2016 23:51:37\",\"05-10-2016 00:04:30\",\"05-10-2016 00:04:57\",\"05-10-2016 00:05:22\",\"05-10-2016 00:14:54\",\"05-10-2016 00:15:13\",\"05-10-2016 00:19:39\",\"05-10-2016 00:19:44\",\"05-10-2016 00:19:48\",\"05-10-2016 00:20:11\",\"05-10-2016 00:20:16\",\"05-10-2016 00:20:20\",\"05-10-2016 00:20:49\",\"05-10-2016 00:21:30\",\"05-10-2016 00:21:33\",\"05-10-2016 00:21:37\",\"05-10-2016 00:21:48\",\"05-10-2016 00:22:26\",\"05-10-2016 00:22:43\",\"05-10-2016 00:22:47\",\"05-10-2016 00:24:35\",\"05-10-2016 00:24:39\",\"05-10-2016 00:24:42\",\"05-10-2016 00:24:51\",\"05-10-2016 00:25:00\",\"05-10-2016 00:25:04\",\"05-10-2016 00:25:14\",\"05-10-2016 01:04:29\",\"05-10-2016 01:04:33\",\"05-10-2016 01:04:36\",\"05-10-2016 01:04:44\",\"05-10-2016 01:04:48\",\"05-10-2016 01:04:51\",\"05-10-2016 01:12:15\",\"05-10-2016 01:13:20\",\"05-10-2016 01:13:24\",\"05-10-2016 01:13:32\",\"05-10-2016 01:13:48\",\"05-10-2016 01:14:25\",\"05-10-2016 01:14:29\",\"05-10-2016 01:14:49\",\"05-10-2016 01:16:13\",\"05-10-2016 01:17:29\",\"05-10-2016 01:17:33\",\"05-10-2016 01:17:37\",\"05-10-2016 01:17:51\",\"05-10-2016 01:17:55\",\"05-10-2016 01:18:00\",\"05-10-2016 01:19:53\",\"05-10-2016 01:19:56\",\"05-10-2016 01:19:59\",\"05-10-2016 01:20:24\",\"05-10-2016 01:20:29\",\"05-10-2016 01:20:32\",\"05-10-2016 01:20:45\",\"05-10-2016 01:20:56\",\"05-10-2016 01:21:01\",\"05-10-2016 01:21:04\",\"05-10-2016 01:26:26\",\"05-10-2016 01:26:30\",\"05-10-2016 01:26:33\",\"05-10-2016 01:26:55\",\"05-10-2016 01:26:59\",\"05-10-2016 01:27:02\",\"05-10-2016 01:27:12\",\"05-10-2016 01:27:31\",\"05-10-2016 01:28:44\",\"05-10-2016 01:31:54\",\"05-10-2016 01:31:58\",\"05-10-2016 01:32:01\",\"05-10-2016 01:32:12\",\"05-10-2016 01:32:28\",\"05-10-2016 01:32:32\",\"05-10-2016 01:32:36\",\"05-10-2016 01:32:46\",\"05-10-2016 01:32:58\",\"05-10-2016 01:33:31\",\"05-10-2016 01:33:36\",\"05-10-2016 01:33:39\",\"05-10-2016 01:33:57\",\"05-10-2016 01:34:07\",\"05-10-2016 01:34:11\",\"05-10-2016 01:34:15\",\"05-10-2016 01:34:53\",\"05-10-2016 02:01:39\",\"05-10-2016 02:01:43\",\"05-10-2016 02:01:45\",\"05-10-2016 02:02:05\",\"05-10-2016 02:08:34\",\"05-10-2016 02:08:40\",\"05-10-2016 02:08:43\",\"05-10-2016 02:08:54\",\"05-10-2016 23:30:45\",\"05-10-2016 23:31:28\",\"05-10-2016 23:31:38\",\"05-10-2016 23:31:45\",\"05-10-2016 23:31:46\",\"05-10-2016 23:31:56\",\"05-10-2016 23:32:45\",\"05-10-2016 23:34:04\",\"05-10-2016 23:36:22\",\"05-10-2016 23:36:25\",\"05-10-2016 23:37:42\",\"05-10-2016 23:38:27\",\"05-10-2016 23:38:36\",\"05-10-2016 23:38:48\",\"05-10-2016 23:38:58\",\"06-10-2016 00:14:23\",\"06-10-2016 00:14:31\",\"06-10-2016 00:15:44\",\"06-10-2016 00:19:17\",\"06-10-2016 00:24:43\",\"06-10-2016 00:26:00\",\"06-10-2016 00:32:26\",\"06-10-2016 00:32:32\",\"06-10-2016 00:32:43\",\"06-10-2016 00:32:51\",\"06-10-2016 00:33:01\"]',''),(127,1,NULL,NULL,'2016-10-06 12:12:47','2016-10-06 16:45:04',NULL,'::1','[0,0,22,22,22,22,22,3,3,8,9,8,3,23,3,3,23,3,23,3,23,3]','[\"06-10-2016 12:12:49\",\"06-10-2016 12:14:10\",\"06-10-2016 12:53:21\",\"06-10-2016 13:06:49\",\"06-10-2016 13:06:51\",\"06-10-2016 13:07:20\",\"06-10-2016 13:07:21\",\"06-10-2016 16:36:22\",\"06-10-2016 16:39:50\",\"06-10-2016 16:40:31\",\"06-10-2016 16:40:40\",\"06-10-2016 16:40:43\",\"06-10-2016 16:40:51\",\"06-10-2016 16:41:02\",\"06-10-2016 16:41:42\",\"06-10-2016 16:42:15\",\"06-10-2016 16:42:25\",\"06-10-2016 16:42:56\",\"06-10-2016 16:43:29\",\"06-10-2016 16:44:18\",\"06-10-2016 16:44:34\",\"06-10-2016 16:45:04\"]',''),(128,1,NULL,NULL,'2016-10-06 17:34:45','2016-10-06 18:35:34',NULL,'::1','[0,3,23,3,23,3,3,23,23,23,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,3,23,3,23,3,3,23,3,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,3]','[\"06-10-2016 17:34:47\",\"06-10-2016 17:37:06\",\"06-10-2016 17:37:16\",\"06-10-2016 17:44:29\",\"06-10-2016 17:44:38\",\"06-10-2016 17:44:44\",\"06-10-2016 17:45:05\",\"06-10-2016 17:45:14\",\"06-10-2016 17:45:53\",\"06-10-2016 17:46:00\",\"06-10-2016 17:47:36\",\"06-10-2016 17:47:40\",\"06-10-2016 17:47:49\",\"06-10-2016 17:47:56\",\"06-10-2016 17:48:05\",\"06-10-2016 17:52:24\",\"06-10-2016 17:52:33\",\"06-10-2016 17:53:45\",\"06-10-2016 17:53:54\",\"06-10-2016 17:54:34\",\"06-10-2016 17:54:43\",\"06-10-2016 17:55:38\",\"06-10-2016 17:55:47\",\"06-10-2016 17:56:25\",\"06-10-2016 17:56:34\",\"06-10-2016 17:57:32\",\"06-10-2016 17:57:42\",\"06-10-2016 17:58:16\",\"06-10-2016 17:58:28\",\"06-10-2016 17:58:50\",\"06-10-2016 17:59:00\",\"06-10-2016 17:59:18\",\"06-10-2016 17:59:26\",\"06-10-2016 17:59:59\",\"06-10-2016 18:00:08\",\"06-10-2016 18:01:01\",\"06-10-2016 18:01:10\",\"06-10-2016 18:01:47\",\"06-10-2016 18:01:56\",\"06-10-2016 18:02:20\",\"06-10-2016 18:02:30\",\"06-10-2016 18:03:09\",\"06-10-2016 18:04:14\",\"06-10-2016 18:05:10\",\"06-10-2016 18:05:19\",\"06-10-2016 18:08:42\",\"06-10-2016 18:08:53\",\"06-10-2016 18:10:07\",\"06-10-2016 18:10:17\",\"06-10-2016 18:10:40\",\"06-10-2016 18:11:16\",\"06-10-2016 18:13:28\",\"06-10-2016 18:13:37\",\"06-10-2016 18:13:59\",\"06-10-2016 18:14:10\",\"06-10-2016 18:15:19\",\"06-10-2016 18:15:28\",\"06-10-2016 18:16:24\",\"06-10-2016 18:16:36\",\"06-10-2016 18:17:00\",\"06-10-2016 18:17:35\",\"06-10-2016 18:17:42\",\"06-10-2016 18:18:29\",\"06-10-2016 18:18:46\",\"06-10-2016 18:19:17\",\"06-10-2016 18:19:41\",\"06-10-2016 18:20:16\",\"06-10-2016 18:21:43\",\"06-10-2016 18:22:00\",\"06-10-2016 18:22:09\",\"06-10-2016 18:22:26\",\"06-10-2016 18:22:35\",\"06-10-2016 18:23:04\",\"06-10-2016 18:23:17\",\"06-10-2016 18:24:20\",\"06-10-2016 18:24:30\",\"06-10-2016 18:24:44\",\"06-10-2016 18:24:55\",\"06-10-2016 18:25:10\",\"06-10-2016 18:25:17\",\"06-10-2016 18:26:54\",\"06-10-2016 18:27:03\",\"06-10-2016 18:28:04\",\"06-10-2016 18:28:14\",\"06-10-2016 18:30:20\",\"06-10-2016 18:30:27\",\"06-10-2016 18:31:34\",\"06-10-2016 18:31:42\",\"06-10-2016 18:35:12\",\"06-10-2016 18:35:21\",\"06-10-2016 18:35:27\",\"06-10-2016 18:35:34\"]',''),(129,1,NULL,NULL,'2016-10-06 18:39:58','2016-10-07 12:02:43','2016-10-07 12:02:43','::1','[0,3,3,23,3,3,23,3,23,3,23,3,23,3,23,3,23,3,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,23,3,3,23,3,23,3,23,3,23,3,23,3]','[\"06-10-2016 18:40:00\",\"06-10-2016 18:40:19\",\"06-10-2016 18:41:11\",\"06-10-2016 18:42:22\",\"06-10-2016 18:44:08\",\"06-10-2016 18:44:34\",\"06-10-2016 18:44:41\",\"06-10-2016 18:45:33\",\"06-10-2016 18:46:54\",\"06-10-2016 18:47:26\",\"06-10-2016 18:47:42\",\"06-10-2016 18:48:11\",\"06-10-2016 18:48:20\",\"06-10-2016 18:50:18\",\"06-10-2016 18:50:28\",\"06-10-2016 18:51:12\",\"06-10-2016 18:51:20\",\"06-10-2016 18:52:24\",\"06-10-2016 18:52:43\",\"06-10-2016 18:52:51\",\"06-10-2016 18:53:35\",\"06-10-2016 18:53:43\",\"06-10-2016 18:54:14\",\"06-10-2016 18:54:23\",\"06-10-2016 18:56:09\",\"06-10-2016 18:57:52\",\"06-10-2016 18:59:19\",\"06-10-2016 18:59:27\",\"06-10-2016 19:00:47\",\"06-10-2016 19:00:57\",\"06-10-2016 19:01:39\",\"06-10-2016 19:01:47\",\"06-10-2016 19:02:57\",\"06-10-2016 19:03:04\",\"06-10-2016 19:04:08\",\"06-10-2016 19:04:16\",\"06-10-2016 19:06:20\",\"06-10-2016 19:06:28\",\"06-10-2016 19:07:07\",\"06-10-2016 19:07:14\",\"06-10-2016 19:07:55\",\"06-10-2016 19:08:03\",\"06-10-2016 19:09:18\",\"06-10-2016 19:09:31\",\"06-10-2016 19:13:35\",\"06-10-2016 19:13:42\",\"06-10-2016 19:14:01\",\"06-10-2016 19:14:09\",\"06-10-2016 19:14:28\",\"06-10-2016 19:14:37\",\"06-10-2016 19:14:54\",\"06-10-2016 19:15:02\",\"06-10-2016 19:15:55\",\"06-10-2016 19:16:24\",\"06-10-2016 19:18:31\",\"06-10-2016 19:18:39\",\"06-10-2016 19:19:15\",\"06-10-2016 19:19:22\",\"06-10-2016 19:19:40\",\"06-10-2016 19:19:47\",\"06-10-2016 19:21:05\",\"06-10-2016 19:21:12\",\"06-10-2016 19:21:41\",\"06-10-2016 19:21:51\",\"06-10-2016 19:24:08\",\"06-10-2016 19:24:16\",\"06-10-2016 19:24:39\",\"06-10-2016 19:24:47\",\"06-10-2016 19:25:14\",\"06-10-2016 19:26:12\",\"06-10-2016 19:26:35\",\"06-10-2016 19:26:42\",\"06-10-2016 19:27:02\",\"06-10-2016 19:27:09\",\"06-10-2016 19:27:51\",\"06-10-2016 19:27:58\",\"06-10-2016 19:28:26\",\"06-10-2016 19:29:29\",\"06-10-2016 19:30:07\",\"06-10-2016 19:30:14\",\"06-10-2016 19:30:37\",\"06-10-2016 19:30:44\",\"06-10-2016 19:31:13\",\"06-10-2016 19:31:19\",\"06-10-2016 19:33:09\",\"06-10-2016 19:33:23\",\"06-10-2016 19:33:46\",\"06-10-2016 19:33:55\",\"06-10-2016 19:34:41\",\"06-10-2016 19:34:48\",\"06-10-2016 19:35:13\",\"06-10-2016 19:35:20\",\"06-10-2016 19:35:50\",\"06-10-2016 19:35:59\",\"06-10-2016 19:36:31\",\"06-10-2016 19:36:38\",\"06-10-2016 19:37:10\",\"06-10-2016 19:37:29\",\"06-10-2016 19:37:39\",\"06-10-2016 19:41:14\",\"06-10-2016 19:41:21\",\"06-10-2016 19:41:49\",\"06-10-2016 19:41:57\",\"06-10-2016 19:42:28\",\"06-10-2016 19:42:36\",\"06-10-2016 19:47:06\",\"06-10-2016 19:47:13\",\"06-10-2016 19:48:45\",\"06-10-2016 19:48:52\",\"06-10-2016 19:49:32\",\"06-10-2016 19:49:40\",\"06-10-2016 19:50:02\",\"06-10-2016 19:50:09\",\"06-10-2016 19:51:25\",\"06-10-2016 19:51:33\",\"06-10-2016 19:52:23\",\"06-10-2016 19:52:32\",\"06-10-2016 19:53:00\",\"06-10-2016 19:53:08\",\"06-10-2016 19:55:22\",\"06-10-2016 19:55:30\",\"06-10-2016 19:56:09\",\"06-10-2016 19:56:17\",\"06-10-2016 19:56:58\",\"06-10-2016 19:57:24\",\"06-10-2016 19:57:31\",\"06-10-2016 19:59:06\",\"06-10-2016 19:59:13\",\"06-10-2016 20:00:21\",\"06-10-2016 20:00:29\",\"06-10-2016 20:01:18\",\"06-10-2016 20:01:25\",\"06-10-2016 20:05:41\",\"06-10-2016 20:05:51\",\"07-10-2016 12:02:43\"]',''),(130,1,NULL,NULL,'2016-10-07 15:21:56','2016-10-08 21:05:09','2016-10-08 21:05:09','::1','[0,3,23,8,9,8,24,24,24,24,24,24,24,24,24,24,24,24,24,8,9,8,25,25,25,25,25,25,25,25,25,25,25,25,25,25,25,25,25,25,24,24,24,9,8,24,24,24,26,9,8,24,26,26,24,25,25,24,8,9,8,27,27,27,27,27,28,8,9,8,27,28,28,28,28,28,27,28,27,28,27,28,27,28,27,28,27,28,27,28,27,28,27,28,27,28,27,28,27,27,28,27,28,27,28,28,8,9,8,27,28,28,27,28,28,29,29,29,29,29,29,29,29,29,29,29,29,29,8,9,8,29,29,30,30,30,30,30,30,30,30,30,8,9,8,30,31,31,31,31,31,31,31,31,30,30,3,3,8,9,8,3,32,3,32,3,32,3,32,3,3,32,3,32,3,32,3,32,3,3,32,33,8,9,8,3,32,33,3,32,33,3,3,32,3,3,32,3,3,3,32,33,3,32,33,3,32,33,3,8,9,8,8,3,32,33,34,3,32,33,3,32,33,3,32,33,3,32,33,3,32,33,34,3,32,33,34,3,32,33,3,32,33,34,3,32,33,34,3,32,33,34,34,34,34,34,34,3,3,32,33,34,34,3,32,33,34,34,34,34,34,34,34,34,34,34,34,3,32,33,3,32,33,34,34,34,3,32,33,34,34,34,3,3,3,3,32,33,3,32,33,3,32,33,3,32,33,3,32,33,3,32,33,3,32,33,3,32,33,3,32,33,3,3,32,33,33,33,33,3,32,33,33,33,3,32,33,3,32,33,3,32,33,33,3,32,33,33,3,32,33,3,32,33,3,32,33,3,32,33,3,32,33,3,32,33,3,32,33,3,8,3,8,9,8,35,35,35,35,35,35,35,35,35,24,8,9,8,36,36,36,36,35,2,3,23,3,32,33,34,34,3,3,8,8,0,19,20,20]','[\"07-10-2016 15:21:59\",\"07-10-2016 15:22:20\",\"07-10-2016 15:22:34\",\"07-10-2016 15:29:42\",\"07-10-2016 15:29:52\",\"07-10-2016 15:29:54\",\"07-10-2016 15:30:07\",\"07-10-2016 15:30:58\",\"07-10-2016 15:31:25\",\"07-10-2016 15:33:02\",\"07-10-2016 15:33:20\",\"07-10-2016 15:33:34\",\"07-10-2016 15:34:10\",\"07-10-2016 15:34:23\",\"07-10-2016 15:36:28\",\"07-10-2016 15:39:21\",\"07-10-2016 15:41:57\",\"07-10-2016 15:42:11\",\"07-10-2016 15:42:21\",\"07-10-2016 15:43:43\",\"07-10-2016 15:43:51\",\"07-10-2016 15:43:53\",\"07-10-2016 15:44:04\",\"07-10-2016 15:54:59\",\"07-10-2016 15:55:43\",\"07-10-2016 16:02:06\",\"07-10-2016 16:03:22\",\"07-10-2016 16:04:15\",\"07-10-2016 16:05:52\",\"07-10-2016 16:07:03\",\"07-10-2016 16:07:28\",\"07-10-2016 16:07:42\",\"07-10-2016 16:08:02\",\"07-10-2016 16:10:06\",\"07-10-2016 16:10:23\",\"07-10-2016 16:13:35\",\"07-10-2016 16:13:53\",\"07-10-2016 16:15:33\",\"07-10-2016 16:16:47\",\"07-10-2016 16:22:00\",\"07-10-2016 16:22:04\",\"07-10-2016 16:25:04\",\"07-10-2016 16:25:40\",\"07-10-2016 16:27:05\",\"07-10-2016 16:27:06\",\"07-10-2016 16:27:14\",\"07-10-2016 16:28:19\",\"07-10-2016 16:28:37\",\"07-10-2016 16:28:42\",\"07-10-2016 16:28:50\",\"07-10-2016 16:28:52\",\"07-10-2016 16:28:59\",\"07-10-2016 16:29:05\",\"07-10-2016 16:29:45\",\"07-10-2016 16:30:49\",\"07-10-2016 16:34:46\",\"07-10-2016 16:35:15\",\"07-10-2016 16:35:17\",\"07-10-2016 16:38:02\",\"07-10-2016 16:38:15\",\"07-10-2016 16:38:16\",\"07-10-2016 16:38:31\",\"07-10-2016 16:39:29\",\"07-10-2016 16:40:14\",\"07-10-2016 16:41:09\",\"07-10-2016 16:41:39\",\"07-10-2016 16:42:29\",\"07-10-2016 16:42:44\",\"07-10-2016 16:42:51\",\"07-10-2016 16:42:53\",\"07-10-2016 16:43:02\",\"07-10-2016 16:43:02\",\"07-10-2016 16:43:31\",\"07-10-2016 16:49:16\",\"07-10-2016 16:49:41\",\"07-10-2016 16:52:12\",\"07-10-2016 16:52:29\",\"07-10-2016 16:52:30\",\"07-10-2016 16:54:18\",\"07-10-2016 16:54:18\",\"07-10-2016 16:55:42\",\"07-10-2016 16:55:43\",\"07-10-2016 16:57:05\",\"07-10-2016 16:57:06\",\"07-10-2016 16:58:19\",\"07-10-2016 16:58:20\",\"07-10-2016 16:59:17\",\"07-10-2016 16:59:17\",\"07-10-2016 17:00:02\",\"07-10-2016 17:00:02\",\"07-10-2016 17:08:05\",\"07-10-2016 17:08:05\",\"07-10-2016 17:08:37\",\"07-10-2016 17:08:38\",\"07-10-2016 17:09:26\",\"07-10-2016 17:09:27\",\"07-10-2016 17:10:32\",\"07-10-2016 17:10:33\",\"07-10-2016 17:11:05\",\"07-10-2016 17:11:44\",\"07-10-2016 17:11:44\",\"07-10-2016 17:12:59\",\"07-10-2016 17:12:59\",\"07-10-2016 17:14:01\",\"07-10-2016 17:14:02\",\"07-10-2016 17:14:15\",\"07-10-2016 17:14:34\",\"07-10-2016 17:15:03\",\"07-10-2016 17:15:05\",\"07-10-2016 17:15:12\",\"07-10-2016 17:15:12\",\"07-10-2016 17:15:20\",\"07-10-2016 17:15:22\",\"07-10-2016 17:15:22\",\"07-10-2016 17:16:28\",\"07-10-2016 17:16:30\",\"07-10-2016 17:16:58\",\"07-10-2016 17:18:48\",\"07-10-2016 17:22:32\",\"07-10-2016 17:22:59\",\"07-10-2016 17:24:30\",\"07-10-2016 17:25:22\",\"07-10-2016 17:25:48\",\"07-10-2016 17:27:22\",\"07-10-2016 17:28:00\",\"07-10-2016 19:02:54\",\"07-10-2016 19:03:42\",\"07-10-2016 19:04:13\",\"07-10-2016 19:04:26\",\"07-10-2016 19:04:34\",\"07-10-2016 19:04:36\",\"07-10-2016 19:04:43\",\"07-10-2016 19:05:08\",\"07-10-2016 19:05:10\",\"07-10-2016 19:06:41\",\"07-10-2016 19:07:51\",\"07-10-2016 19:10:17\",\"07-10-2016 19:11:56\",\"07-10-2016 19:14:08\",\"07-10-2016 19:15:30\",\"07-10-2016 19:15:46\",\"07-10-2016 19:16:09\",\"07-10-2016 19:16:29\",\"07-10-2016 19:16:37\",\"07-10-2016 19:16:39\",\"07-10-2016 19:16:46\",\"07-10-2016 19:16:54\",\"07-10-2016 19:17:23\",\"07-10-2016 19:17:56\",\"07-10-2016 19:18:01\",\"07-10-2016 19:18:03\",\"07-10-2016 19:22:13\",\"07-10-2016 19:23:22\",\"07-10-2016 19:24:35\",\"07-10-2016 19:29:58\",\"07-10-2016 19:32:49\",\"07-10-2016 19:50:23\",\"07-10-2016 19:51:05\",\"07-10-2016 19:51:20\",\"07-10-2016 19:51:28\",\"07-10-2016 19:51:30\",\"07-10-2016 19:51:57\",\"07-10-2016 19:52:06\",\"07-10-2016 19:52:40\",\"07-10-2016 19:52:48\",\"07-10-2016 19:53:24\",\"07-10-2016 19:53:32\",\"07-10-2016 19:54:15\",\"07-10-2016 19:54:24\",\"07-10-2016 23:15:01\",\"07-10-2016 23:15:30\",\"07-10-2016 23:15:45\",\"07-10-2016 23:17:25\",\"07-10-2016 23:17:34\",\"07-10-2016 23:18:17\",\"07-10-2016 23:18:25\",\"07-10-2016 23:19:16\",\"07-10-2016 23:19:41\",\"07-10-2016 23:22:00\",\"07-10-2016 23:22:08\",\"07-10-2016 23:22:27\",\"07-10-2016 23:22:30\",\"07-10-2016 23:22:35\",\"07-10-2016 23:22:44\",\"07-10-2016 23:22:45\",\"07-10-2016 23:23:09\",\"07-10-2016 23:23:19\",\"07-10-2016 23:23:22\",\"07-10-2016 23:25:41\",\"07-10-2016 23:25:49\",\"07-10-2016 23:25:53\",\"07-10-2016 23:44:27\",\"07-10-2016 23:45:12\",\"07-10-2016 23:45:20\",\"07-10-2016 23:45:52\",\"07-10-2016 23:46:24\",\"07-10-2016 23:46:33\",\"07-10-2016 23:47:03\",\"07-10-2016 23:47:36\",\"07-10-2016 23:48:16\",\"07-10-2016 23:48:21\",\"07-10-2016 23:48:27\",\"07-10-2016 23:48:35\",\"07-10-2016 23:48:58\",\"07-10-2016 23:49:02\",\"07-10-2016 23:55:15\",\"07-10-2016 23:55:27\",\"07-10-2016 23:55:32\",\"07-10-2016 23:56:54\",\"07-10-2016 23:57:08\",\"07-10-2016 23:57:15\",\"07-10-2016 23:57:17\",\"07-10-2016 23:58:27\",\"07-10-2016 23:58:31\",\"07-10-2016 23:58:39\",\"07-10-2016 23:58:42\",\"08-10-2016 00:00:00\",\"08-10-2016 00:01:45\",\"08-10-2016 00:01:53\",\"08-10-2016 00:02:03\",\"08-10-2016 00:08:57\",\"08-10-2016 00:09:09\",\"08-10-2016 00:09:13\",\"08-10-2016 00:10:18\",\"08-10-2016 00:10:25\",\"08-10-2016 00:10:30\",\"08-10-2016 00:12:08\",\"08-10-2016 00:12:15\",\"08-10-2016 00:12:19\",\"08-10-2016 00:12:32\",\"08-10-2016 00:12:48\",\"08-10-2016 00:12:51\",\"08-10-2016 00:13:13\",\"08-10-2016 00:14:06\",\"08-10-2016 00:14:15\",\"08-10-2016 00:14:18\",\"08-10-2016 00:14:42\",\"08-10-2016 00:16:02\",\"08-10-2016 00:16:10\",\"08-10-2016 00:16:13\",\"08-10-2016 00:16:49\",\"08-10-2016 00:16:58\",\"08-10-2016 00:17:02\",\"08-10-2016 00:17:51\",\"08-10-2016 00:23:43\",\"08-10-2016 00:23:51\",\"08-10-2016 00:23:55\",\"08-10-2016 00:24:15\",\"08-10-2016 00:24:44\",\"08-10-2016 00:24:51\",\"08-10-2016 00:24:57\",\"08-10-2016 00:25:13\",\"08-10-2016 00:33:55\",\"08-10-2016 00:34:53\",\"08-10-2016 00:41:31\",\"08-10-2016 00:43:11\",\"08-10-2016 00:44:41\",\"08-10-2016 11:33:39\",\"08-10-2016 11:33:48\",\"08-10-2016 11:33:58\",\"08-10-2016 11:34:06\",\"08-10-2016 11:34:30\",\"08-10-2016 11:35:21\",\"08-10-2016 11:36:26\",\"08-10-2016 11:36:50\",\"08-10-2016 11:36:53\",\"08-10-2016 11:37:14\",\"08-10-2016 11:37:33\",\"08-10-2016 11:39:46\",\"08-10-2016 11:43:04\",\"08-10-2016 11:43:16\",\"08-10-2016 11:44:26\",\"08-10-2016 11:45:05\",\"08-10-2016 11:45:28\",\"08-10-2016 11:46:23\",\"08-10-2016 11:47:54\",\"08-10-2016 11:49:16\",\"08-10-2016 11:50:13\",\"08-10-2016 11:50:59\",\"08-10-2016 11:51:13\",\"08-10-2016 11:52:23\",\"08-10-2016 11:52:30\",\"08-10-2016 11:52:33\",\"08-10-2016 11:52:52\",\"08-10-2016 11:53:12\",\"08-10-2016 12:00:32\",\"08-10-2016 12:28:20\",\"08-10-2016 12:28:35\",\"08-10-2016 12:28:39\",\"08-10-2016 12:28:56\",\"08-10-2016 12:30:35\",\"08-10-2016 12:31:26\",\"08-10-2016 13:51:15\",\"08-10-2016 13:55:54\",\"08-10-2016 15:00:57\",\"08-10-2016 15:06:21\",\"08-10-2016 15:06:36\",\"08-10-2016 15:06:40\",\"08-10-2016 15:07:25\",\"08-10-2016 15:07:33\",\"08-10-2016 15:07:37\",\"08-10-2016 15:09:02\",\"08-10-2016 15:09:09\",\"08-10-2016 15:09:12\",\"08-10-2016 15:11:31\",\"08-10-2016 15:11:39\",\"08-10-2016 15:11:42\",\"08-10-2016 15:12:02\",\"08-10-2016 15:12:09\",\"08-10-2016 15:12:13\",\"08-10-2016 15:12:45\",\"08-10-2016 15:12:53\",\"08-10-2016 15:12:57\",\"08-10-2016 15:13:25\",\"08-10-2016 15:13:35\",\"08-10-2016 15:13:38\",\"08-10-2016 15:14:46\",\"08-10-2016 15:14:52\",\"08-10-2016 15:14:56\",\"08-10-2016 15:16:06\",\"08-10-2016 15:16:13\",\"08-10-2016 15:16:16\",\"08-10-2016 15:16:49\",\"08-10-2016 15:18:12\",\"08-10-2016 15:18:24\",\"08-10-2016 15:18:27\",\"08-10-2016 15:18:36\",\"08-10-2016 15:18:45\",\"08-10-2016 15:20:03\",\"08-10-2016 15:20:56\",\"08-10-2016 15:21:06\",\"08-10-2016 15:21:10\",\"08-10-2016 15:21:22\",\"08-10-2016 15:21:27\",\"08-10-2016 15:22:05\",\"08-10-2016 15:22:12\",\"08-10-2016 15:22:16\",\"08-10-2016 15:23:07\",\"08-10-2016 15:23:28\",\"08-10-2016 15:23:33\",\"08-10-2016 15:24:17\",\"08-10-2016 15:24:24\",\"08-10-2016 15:24:27\",\"08-10-2016 15:24:54\",\"08-10-2016 15:25:00\",\"08-10-2016 15:25:08\",\"08-10-2016 15:25:12\",\"08-10-2016 15:25:28\",\"08-10-2016 15:27:23\",\"08-10-2016 15:27:30\",\"08-10-2016 15:27:33\",\"08-10-2016 15:28:26\",\"08-10-2016 15:28:32\",\"08-10-2016 15:28:35\",\"08-10-2016 15:29:25\",\"08-10-2016 15:29:32\",\"08-10-2016 15:29:35\",\"08-10-2016 15:30:12\",\"08-10-2016 15:30:46\",\"08-10-2016 15:30:50\",\"08-10-2016 15:31:15\",\"08-10-2016 15:31:22\",\"08-10-2016 15:31:25\",\"08-10-2016 15:33:20\",\"08-10-2016 15:33:29\",\"08-10-2016 15:33:32\",\"08-10-2016 15:34:31\",\"08-10-2016 15:34:36\",\"08-10-2016 15:34:40\",\"08-10-2016 15:38:52\",\"08-10-2016 16:02:05\",\"08-10-2016 16:02:39\",\"08-10-2016 16:25:26\",\"08-10-2016 16:25:33\",\"08-10-2016 16:25:35\",\"08-10-2016 16:25:54\",\"08-10-2016 16:26:53\",\"08-10-2016 16:29:03\",\"08-10-2016 16:29:58\",\"08-10-2016 16:32:47\",\"08-10-2016 16:34:53\",\"08-10-2016 16:36:26\",\"08-10-2016 16:37:01\",\"08-10-2016 16:38:55\",\"08-10-2016 17:08:41\",\"08-10-2016 17:27:41\",\"08-10-2016 17:27:48\",\"08-10-2016 17:27:50\",\"08-10-2016 17:28:06\",\"08-10-2016 17:28:42\",\"08-10-2016 17:32:04\",\"08-10-2016 17:32:33\",\"08-10-2016 20:51:10\",\"08-10-2016 20:52:31\",\"08-10-2016 20:53:15\",\"08-10-2016 20:53:53\",\"08-10-2016 20:54:46\",\"08-10-2016 20:55:00\",\"08-10-2016 20:55:07\",\"08-10-2016 20:56:04\",\"08-10-2016 20:56:08\",\"08-10-2016 20:56:16\",\"08-10-2016 20:59:50\",\"08-10-2016 21:02:19\",\"08-10-2016 21:02:34\",\"08-10-2016 21:03:24\",\"08-10-2016 21:04:02\",\"08-10-2016 21:04:11\",\"08-10-2016 21:04:16\"]','5 heures 43 minutes 13 secondes'),(131,1,NULL,NULL,'2016-10-08 14:04:08','2016-10-10 16:29:51','2016-10-10 16:29:51','::1','[0,3,3,3,3,3,3,3,3,3,3,3,3,3,32,33,3]','[\"08-10-2016 14:04:12\",\"08-10-2016 14:05:58\",\"08-10-2016 14:10:07\",\"08-10-2016 14:11:00\",\"08-10-2016 14:19:11\",\"08-10-2016 14:19:34\",\"08-10-2016 14:20:33\",\"08-10-2016 14:21:22\",\"08-10-2016 14:21:32\",\"08-10-2016 14:21:55\",\"08-10-2016 14:25:51\",\"08-10-2016 14:26:21\",\"08-10-2016 14:27:59\",\"08-10-2016 14:50:55\",\"08-10-2016 14:52:03\",\"08-10-2016 14:52:11\",\"10-10-2016 16:29:49\"]','2 heures 25 minutes 43 secondes'),(132,1,NULL,NULL,'2016-10-10 11:06:16','2016-10-10 18:46:28',NULL,'::1','[0,11,3,32,33,34,33,3,32,33,34,3,23,3,36,3,23,3,23,3,3,23,3,23,23,3,3,23,8,9,8,37,37,37,37,3,23,3,3,3,23,11,4,4,4,4,4,4,4,4,17,17,4,4,4,4,4,11,11,11,11,3,4,4,4,4,11,3,3,3,3,3,3,4,4,11,4,3,4,4,11,3]','[\"10-10-2016 11:06:20\",\"10-10-2016 11:06:32\",\"10-10-2016 11:26:36\",\"10-10-2016 11:27:57\",\"10-10-2016 11:28:02\",\"10-10-2016 11:28:25\",\"10-10-2016 11:31:37\",\"10-10-2016 11:31:50\",\"10-10-2016 11:32:45\",\"10-10-2016 11:32:56\",\"10-10-2016 11:33:22\",\"10-10-2016 11:35:28\",\"10-10-2016 11:35:51\",\"10-10-2016 11:36:20\",\"10-10-2016 11:41:05\",\"10-10-2016 15:52:39\",\"10-10-2016 15:52:52\",\"10-10-2016 15:53:16\",\"10-10-2016 15:53:25\",\"10-10-2016 15:53:32\",\"10-10-2016 15:54:03\",\"10-10-2016 15:54:12\",\"10-10-2016 16:01:31\",\"10-10-2016 16:01:41\",\"10-10-2016 16:01:53\",\"10-10-2016 16:02:03\",\"10-10-2016 16:02:12\",\"10-10-2016 16:02:21\",\"10-10-2016 16:22:38\",\"10-10-2016 16:22:49\",\"10-10-2016 16:22:51\",\"10-10-2016 16:23:14\",\"10-10-2016 16:23:30\",\"10-10-2016 16:25:24\",\"10-10-2016 16:26:00\",\"10-10-2016 16:28:55\",\"10-10-2016 16:29:08\",\"10-10-2016 16:29:12\",\"10-10-2016 16:52:49\",\"10-10-2016 16:53:07\",\"10-10-2016 16:53:28\",\"10-10-2016 16:55:28\",\"10-10-2016 16:55:44\",\"10-10-2016 16:57:05\",\"10-10-2016 16:57:23\",\"10-10-2016 16:59:59\",\"10-10-2016 17:01:06\",\"10-10-2016 17:01:50\",\"10-10-2016 17:03:20\",\"10-10-2016 17:09:43\",\"10-10-2016 17:09:59\",\"10-10-2016 17:10:06\",\"10-10-2016 17:10:14\",\"10-10-2016 17:11:27\",\"10-10-2016 17:11:44\",\"10-10-2016 17:17:47\",\"10-10-2016 17:19:31\",\"10-10-2016 17:19:34\",\"10-10-2016 17:38:40\",\"10-10-2016 17:39:29\",\"10-10-2016 17:40:07\",\"10-10-2016 17:40:46\",\"10-10-2016 17:41:18\",\"10-10-2016 17:43:12\",\"10-10-2016 17:44:32\",\"10-10-2016 17:45:25\",\"10-10-2016 17:45:28\",\"10-10-2016 17:46:09\",\"10-10-2016 18:29:07\",\"10-10-2016 18:29:57\",\"10-10-2016 18:32:05\",\"10-10-2016 18:34:07\",\"10-10-2016 18:38:15\",\"10-10-2016 18:42:46\",\"10-10-2016 18:43:51\",\"10-10-2016 18:43:53\",\"10-10-2016 18:44:06\",\"10-10-2016 18:44:23\",\"10-10-2016 18:44:43\",\"10-10-2016 18:46:03\",\"10-10-2016 18:46:06\",\"10-10-2016 18:46:28\"]',''),(133,1,NULL,NULL,'2016-10-10 16:30:38','2016-10-16 09:28:23','2016-10-16 09:28:23','::1','[0,3,23,3,3,23,3,23,3,3,37,37,37,37,37,0,0,8,9,8,0,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38]','[\"10-10-2016 16:30:43\",\"10-10-2016 16:31:21\",\"10-10-2016 16:31:34\",\"10-10-2016 16:32:19\",\"10-10-2016 16:33:50\",\"10-10-2016 16:33:59\",\"10-10-2016 16:34:07\",\"10-10-2016 16:34:17\",\"10-10-2016 16:36:51\",\"10-10-2016 16:37:25\",\"10-10-2016 16:37:44\",\"10-10-2016 16:44:18\",\"10-10-2016 16:47:32\",\"10-10-2016 16:48:31\",\"10-10-2016 16:49:29\",\"10-10-2016 23:41:25\",\"10-10-2016 23:42:09\",\"10-10-2016 23:42:33\",\"10-10-2016 23:42:46\",\"10-10-2016 23:42:48\",\"10-10-2016 23:42:56\",\"10-10-2016 23:43:18\",\"10-10-2016 23:45:52\",\"10-10-2016 23:47:34\",\"10-10-2016 23:48:54\",\"10-10-2016 23:49:50\",\"10-10-2016 23:52:12\",\"10-10-2016 23:54:17\",\"10-10-2016 23:54:47\",\"10-10-2016 23:56:23\",\"10-10-2016 23:56:59\",\"10-10-2016 23:57:35\",\"10-10-2016 23:59:02\",\"10-10-2016 23:59:53\",\"11-10-2016 00:00:20\",\"11-10-2016 00:00:59\",\"11-10-2016 00:01:24\",\"11-10-2016 00:02:11\",\"11-10-2016 00:02:40\",\"11-10-2016 00:04:14\",\"11-10-2016 00:05:40\",\"11-10-2016 00:06:34\",\"11-10-2016 00:07:54\",\"11-10-2016 00:08:08\",\"11-10-2016 00:09:00\",\"11-10-2016 00:11:04\",\"11-10-2016 00:11:22\",\"11-10-2016 00:13:22\",\"11-10-2016 00:13:30\",\"11-10-2016 00:14:12\",\"11-10-2016 00:17:12\",\"11-10-2016 00:17:35\",\"11-10-2016 00:17:51\",\"11-10-2016 00:18:21\",\"11-10-2016 00:18:44\",\"11-10-2016 00:20:09\",\"11-10-2016 00:20:23\",\"11-10-2016 00:20:34\",\"11-10-2016 00:21:35\",\"11-10-2016 00:21:39\",\"11-10-2016 00:21:52\",\"11-10-2016 00:28:02\",\"11-10-2016 00:29:45\",\"11-10-2016 00:29:54\",\"11-10-2016 00:30:10\",\"11-10-2016 00:33:00\",\"11-10-2016 00:33:30\",\"11-10-2016 00:34:23\",\"11-10-2016 00:35:11\",\"11-10-2016 00:36:30\",\"11-10-2016 00:37:36\",\"11-10-2016 00:38:20\",\"11-10-2016 00:38:56\",\"11-10-2016 00:39:24\",\"11-10-2016 00:39:45\",\"11-10-2016 00:40:28\",\"11-10-2016 00:40:57\",\"11-10-2016 00:41:26\",\"11-10-2016 00:41:53\",\"11-10-2016 00:42:26\",\"11-10-2016 00:42:37\",\"11-10-2016 00:43:15\",\"11-10-2016 00:43:32\",\"11-10-2016 00:44:59\",\"11-10-2016 00:46:25\",\"11-10-2016 00:47:16\",\"11-10-2016 00:48:07\",\"11-10-2016 00:49:40\",\"11-10-2016 00:49:51\",\"11-10-2016 00:49:58\",\"11-10-2016 00:51:09\",\"11-10-2016 00:51:38\",\"11-10-2016 00:53:45\",\"11-10-2016 00:53:59\",\"11-10-2016 00:54:14\",\"11-10-2016 00:56:11\",\"11-10-2016 00:56:30\",\"11-10-2016 00:56:45\",\"11-10-2016 00:58:20\",\"11-10-2016 00:58:26\",\"11-10-2016 00:58:39\"]','16 heures 57 minutes 45 secondes'),(134,1,NULL,NULL,'2016-10-11 00:59:45','2016-10-11 10:00:33','2016-10-11 10:00:33','::1','[0,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38]','[\"11-10-2016 00:59:48\",\"11-10-2016 01:00:00\",\"11-10-2016 01:00:15\",\"11-10-2016 01:01:50\",\"11-10-2016 01:02:29\",\"11-10-2016 01:02:49\",\"11-10-2016 01:03:03\",\"11-10-2016 01:03:24\",\"11-10-2016 01:03:57\",\"11-10-2016 01:04:15\",\"11-10-2016 01:07:01\",\"11-10-2016 01:07:59\",\"11-10-2016 01:08:26\",\"11-10-2016 01:09:01\",\"11-10-2016 01:11:09\",\"11-10-2016 01:11:57\",\"11-10-2016 01:12:10\",\"11-10-2016 01:12:29\",\"11-10-2016 01:12:57\",\"11-10-2016 08:29:56\",\"11-10-2016 08:30:10\",\"11-10-2016 08:30:24\",\"11-10-2016 08:30:32\",\"11-10-2016 08:30:44\",\"11-10-2016 08:31:30\",\"11-10-2016 08:31:59\",\"11-10-2016 08:32:41\",\"11-10-2016 08:32:58\",\"11-10-2016 08:33:10\",\"11-10-2016 08:33:33\",\"11-10-2016 08:34:38\",\"11-10-2016 08:34:57\",\"11-10-2016 08:35:07\",\"11-10-2016 08:35:39\",\"11-10-2016 08:35:51\",\"11-10-2016 08:36:21\",\"11-10-2016 08:36:32\",\"11-10-2016 08:36:47\",\"11-10-2016 08:36:58\",\"11-10-2016 08:37:09\",\"11-10-2016 08:37:18\",\"11-10-2016 08:37:27\",\"11-10-2016 08:37:35\",\"11-10-2016 08:42:05\",\"11-10-2016 08:42:20\",\"11-10-2016 08:42:28\",\"11-10-2016 08:51:19\",\"11-10-2016 08:51:56\",\"11-10-2016 08:52:06\",\"11-10-2016 08:53:00\",\"11-10-2016 08:53:45\",\"11-10-2016 08:54:43\",\"11-10-2016 08:55:07\",\"11-10-2016 08:55:21\",\"11-10-2016 08:55:34\",\"11-10-2016 08:55:50\",\"11-10-2016 08:56:16\",\"11-10-2016 09:07:50\",\"11-10-2016 09:25:51\",\"11-10-2016 09:27:08\",\"11-10-2016 09:32:17\",\"11-10-2016 09:33:11\",\"11-10-2016 10:00:33\"]',''),(135,1,NULL,NULL,'2016-10-11 16:39:08','2016-10-12 21:29:51','2016-10-12 21:29:51','::1','[0,38,38,38,35,35,36,37,37,37,37,35,35,35,35,35,35,35,35,35,35,38,38,8,9,8,39,39,39,39,39,39,39,39,39,38,3,32,3,3,3,32,32,32,32,32,32,33,32,33,33,32,32,32,32,32,37,32,32,32,32,32,32,32,32,32,32,32,32,32,32,32,38,3,32,32,32,32,32,32,32,39,39,39,39,39,39,38,3,32,32,32,32,32,32,32,32,32,32,32,32,33,32,32,32,32,32,33,32,32,32,32,32,33,32,32,32,32,32,33,32,32,32,32,32,33,32,32,32,32,32,32,32,32,32,32,39,39,39,39,39,39,39,39,39,39]','[\"11-10-2016 16:39:10\",\"11-10-2016 16:39:25\",\"11-10-2016 16:39:46\",\"11-10-2016 16:39:59\",\"11-10-2016 16:55:43\",\"11-10-2016 17:52:04\",\"11-10-2016 17:52:13\",\"11-10-2016 17:52:20\",\"11-10-2016 17:53:59\",\"11-10-2016 18:03:07\",\"11-10-2016 18:11:37\",\"11-10-2016 18:12:25\",\"11-10-2016 18:12:39\",\"11-10-2016 18:12:42\",\"11-10-2016 18:12:45\",\"11-10-2016 18:13:21\",\"11-10-2016 18:13:54\",\"11-10-2016 18:14:18\",\"11-10-2016 18:15:10\",\"11-10-2016 18:15:42\",\"11-10-2016 18:16:51\",\"11-10-2016 19:07:16\",\"11-10-2016 19:09:56\",\"12-10-2016 17:15:17\",\"12-10-2016 17:15:29\",\"12-10-2016 17:15:31\",\"12-10-2016 17:15:44\",\"12-10-2016 17:16:30\",\"12-10-2016 17:20:49\",\"12-10-2016 17:21:11\",\"12-10-2016 17:26:46\",\"12-10-2016 17:27:44\",\"12-10-2016 17:28:22\",\"12-10-2016 17:28:34\",\"12-10-2016 17:34:20\",\"12-10-2016 18:24:25\",\"12-10-2016 18:24:36\",\"12-10-2016 18:24:56\",\"12-10-2016 18:25:01\",\"12-10-2016 18:28:55\",\"12-10-2016 18:29:39\",\"12-10-2016 18:30:14\",\"12-10-2016 18:30:17\",\"12-10-2016 18:30:18\",\"12-10-2016 18:30:20\",\"12-10-2016 18:30:21\",\"12-10-2016 18:30:22\",\"12-10-2016 18:30:24\",\"12-10-2016 18:30:25\",\"12-10-2016 18:30:26\",\"12-10-2016 18:30:28\",\"12-10-2016 18:30:31\",\"12-10-2016 18:30:33\",\"12-10-2016 18:30:35\",\"12-10-2016 18:30:36\",\"12-10-2016 18:30:37\",\"12-10-2016 18:31:17\",\"12-10-2016 18:32:17\",\"12-10-2016 18:32:19\",\"12-10-2016 18:32:21\",\"12-10-2016 18:32:22\",\"12-10-2016 18:32:24\",\"12-10-2016 18:33:08\",\"12-10-2016 18:33:11\",\"12-10-2016 18:33:12\",\"12-10-2016 18:33:14\",\"12-10-2016 18:33:15\",\"12-10-2016 18:34:31\",\"12-10-2016 18:34:33\",\"12-10-2016 18:34:35\",\"12-10-2016 18:34:36\",\"12-10-2016 18:34:37\",\"12-10-2016 18:34:49\",\"12-10-2016 18:34:57\",\"12-10-2016 18:50:47\",\"12-10-2016 18:51:20\",\"12-10-2016 18:51:37\",\"12-10-2016 18:51:39\",\"12-10-2016 18:51:41\",\"12-10-2016 18:51:43\",\"12-10-2016 18:51:45\",\"12-10-2016 19:10:50\",\"12-10-2016 19:12:15\",\"12-10-2016 19:13:29\",\"12-10-2016 19:13:48\",\"12-10-2016 19:15:30\",\"12-10-2016 19:15:50\",\"12-10-2016 19:16:07\",\"12-10-2016 19:16:21\",\"12-10-2016 19:16:33\",\"12-10-2016 19:18:40\",\"12-10-2016 19:19:07\",\"12-10-2016 19:19:10\",\"12-10-2016 19:19:12\",\"12-10-2016 19:19:14\",\"12-10-2016 19:19:15\",\"12-10-2016 19:36:42\",\"12-10-2016 19:36:44\",\"12-10-2016 19:36:47\",\"12-10-2016 19:36:48\",\"12-10-2016 19:36:50\",\"12-10-2016 19:36:56\",\"12-10-2016 19:37:39\",\"12-10-2016 19:37:41\",\"12-10-2016 19:37:44\",\"12-10-2016 19:37:46\",\"12-10-2016 19:37:48\",\"12-10-2016 19:37:53\",\"12-10-2016 19:39:58\",\"12-10-2016 19:40:00\",\"12-10-2016 19:40:02\",\"12-10-2016 19:40:04\",\"12-10-2016 19:40:06\",\"12-10-2016 19:40:10\",\"12-10-2016 19:41:19\",\"12-10-2016 19:41:21\",\"12-10-2016 19:41:23\",\"12-10-2016 19:41:25\",\"12-10-2016 19:41:27\",\"12-10-2016 19:41:32\",\"12-10-2016 19:43:07\",\"12-10-2016 19:43:09\",\"12-10-2016 19:43:11\",\"12-10-2016 19:43:13\",\"12-10-2016 19:43:15\",\"12-10-2016 19:43:21\",\"12-10-2016 19:43:44\",\"12-10-2016 19:43:46\",\"12-10-2016 19:43:48\",\"12-10-2016 19:43:50\",\"12-10-2016 19:43:52\",\"12-10-2016 19:44:25\",\"12-10-2016 19:44:27\",\"12-10-2016 19:44:30\",\"12-10-2016 19:44:31\",\"12-10-2016 19:44:33\",\"12-10-2016 20:51:54\",\"12-10-2016 20:55:20\",\"12-10-2016 20:58:17\",\"12-10-2016 20:58:56\",\"12-10-2016 20:59:39\",\"12-10-2016 20:59:53\",\"12-10-2016 21:07:12\",\"12-10-2016 21:08:35\",\"12-10-2016 21:29:33\",\"12-10-2016 21:29:51\"]',''),(136,1,NULL,NULL,'2016-10-14 09:27:00','2016-10-14 10:26:06','2016-10-14 10:26:06','::1','[0,10,8,9,8,40]','[\"14-10-2016 09:27:03\",\"14-10-2016 09:29:53\",\"14-10-2016 10:25:16\",\"14-10-2016 10:25:29\",\"14-10-2016 10:25:31\",\"14-10-2016 10:25:51\"]','0 heures 59 minutes 6 secondes'),(137,1,NULL,NULL,'2016-10-14 10:26:18','2016-10-16 08:58:08','2016-10-16 08:58:08','::1','[0,40,40,40,40,40,40,40,40,22,22,40,40,8,9,8,40,41,41,40,41,40,41,41,40,41,41,40,40,41,40,41,40,41,40,40,40,40,40,41,40,40,40,41,40,40,40,40,40,40,40,40,8]','[\"14-10-2016 10:26:20\",\"14-10-2016 10:26:38\",\"14-10-2016 10:27:43\",\"14-10-2016 10:28:16\",\"14-10-2016 10:29:33\",\"14-10-2016 10:30:10\",\"14-10-2016 10:31:22\",\"14-10-2016 10:32:06\",\"14-10-2016 10:47:00\",\"14-10-2016 10:47:07\",\"14-10-2016 10:47:20\",\"14-10-2016 10:48:47\",\"14-10-2016 10:49:11\",\"14-10-2016 10:50:27\",\"14-10-2016 10:50:38\",\"14-10-2016 10:50:40\",\"14-10-2016 10:50:48\",\"14-10-2016 10:50:56\",\"14-10-2016 10:51:53\",\"14-10-2016 10:52:47\",\"14-10-2016 10:52:54\",\"14-10-2016 10:53:27\",\"14-10-2016 10:53:34\",\"14-10-2016 10:53:48\",\"14-10-2016 10:55:05\",\"14-10-2016 10:55:13\",\"14-10-2016 10:55:21\",\"14-10-2016 11:18:37\",\"14-10-2016 11:20:58\",\"14-10-2016 11:21:09\",\"14-10-2016 11:22:14\",\"14-10-2016 11:22:23\",\"14-10-2016 11:32:15\",\"14-10-2016 11:32:24\",\"14-10-2016 12:44:27\",\"14-10-2016 12:45:44\",\"14-10-2016 12:46:11\",\"14-10-2016 12:46:45\",\"14-10-2016 12:47:17\",\"14-10-2016 12:47:26\",\"14-10-2016 12:47:55\",\"14-10-2016 12:49:23\",\"14-10-2016 12:50:28\",\"14-10-2016 12:50:36\",\"14-10-2016 12:50:57\",\"14-10-2016 12:51:28\",\"14-10-2016 12:52:07\",\"14-10-2016 12:53:42\",\"14-10-2016 12:55:02\",\"14-10-2016 12:56:30\",\"14-10-2016 16:54:13\",\"14-10-2016 16:55:13\",\"16-10-2016 08:58:06\"]','22 heures 31 minutes 50 secondes'),(138,1,NULL,NULL,'2016-10-16 08:58:23','2016-10-16 09:11:05',NULL,'::1','[8,40,41,40,40,40,40,40,40,40,40,40]','[\"16-10-2016 08:58:25\",\"16-10-2016 08:59:43\",\"16-10-2016 08:59:53\",\"16-10-2016 09:00:23\",\"16-10-2016 09:01:05\",\"16-10-2016 09:04:13\",\"16-10-2016 09:05:23\",\"16-10-2016 09:07:34\",\"16-10-2016 09:09:03\",\"16-10-2016 09:09:46\",\"16-10-2016 09:10:13\",\"16-10-2016 09:11:05\"]',''),(139,1,NULL,NULL,'2016-10-16 09:28:49','2016-10-31 23:27:29','2016-10-31 23:27:29','::1','[0,40,41,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,41,40,40,41,40,40,40,40,40,40,40,40,40,40,41,40,40,41,40,41,40,40,41,40]','[\"16-10-2016 09:28:52\",\"16-10-2016 09:30:31\",\"16-10-2016 09:30:39\",\"16-10-2016 09:31:02\",\"16-10-2016 09:33:06\",\"16-10-2016 09:35:40\",\"16-10-2016 09:38:54\",\"16-10-2016 09:42:45\",\"16-10-2016 09:49:41\",\"16-10-2016 09:49:57\",\"16-10-2016 09:50:34\",\"16-10-2016 09:51:59\",\"16-10-2016 09:52:30\",\"16-10-2016 09:52:55\",\"16-10-2016 09:55:16\",\"16-10-2016 09:56:15\",\"16-10-2016 09:56:47\",\"16-10-2016 09:57:24\",\"16-10-2016 10:06:21\",\"16-10-2016 10:07:53\",\"16-10-2016 10:13:11\",\"16-10-2016 10:14:02\",\"16-10-2016 10:15:10\",\"16-10-2016 10:16:00\",\"16-10-2016 10:16:33\",\"16-10-2016 10:17:50\",\"16-10-2016 10:20:53\",\"16-10-2016 10:22:55\",\"16-10-2016 10:24:11\",\"16-10-2016 10:24:26\",\"16-10-2016 10:27:20\",\"16-10-2016 10:27:45\",\"16-10-2016 18:13:30\",\"16-10-2016 18:13:43\",\"16-10-2016 18:14:09\",\"16-10-2016 18:15:43\",\"16-10-2016 18:17:07\",\"16-10-2016 18:17:29\",\"16-10-2016 18:19:23\",\"16-10-2016 18:20:09\",\"16-10-2016 18:23:49\",\"16-10-2016 18:24:34\",\"16-10-2016 18:26:24\",\"16-10-2016 18:27:14\",\"16-10-2016 18:32:04\",\"16-10-2016 18:32:25\",\"16-10-2016 21:22:48\",\"16-10-2016 21:22:56\",\"16-10-2016 21:23:21\",\"16-10-2016 21:38:09\",\"16-10-2016 21:38:32\",\"16-10-2016 21:43:55\",\"16-10-2016 21:44:01\",\"16-10-2016 21:44:21\"]','13 heures 58 minutes 40 secondes'),(140,1,NULL,NULL,'2016-10-31 12:26:10','2016-10-31 23:39:27',NULL,'::1','[0,38,38,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3]','[\"31-10-2016 12:26:13\",\"31-10-2016 12:26:46\",\"31-10-2016 12:42:06\",\"31-10-2016 12:46:38\",\"31-10-2016 22:40:25\",\"31-10-2016 22:40:52\",\"31-10-2016 22:41:43\",\"31-10-2016 22:42:34\",\"31-10-2016 22:44:50\",\"31-10-2016 22:46:55\",\"31-10-2016 22:47:04\",\"31-10-2016 22:49:34\",\"31-10-2016 22:52:09\",\"31-10-2016 22:54:45\",\"31-10-2016 22:58:05\",\"31-10-2016 22:59:04\",\"31-10-2016 23:06:49\",\"31-10-2016 23:07:13\",\"31-10-2016 23:07:48\",\"31-10-2016 23:08:49\",\"31-10-2016 23:09:19\",\"31-10-2016 23:10:53\",\"31-10-2016 23:11:43\",\"31-10-2016 23:12:41\",\"31-10-2016 23:14:08\",\"31-10-2016 23:16:33\",\"31-10-2016 23:16:42\",\"31-10-2016 23:16:49\",\"31-10-2016 23:17:34\",\"31-10-2016 23:18:39\",\"31-10-2016 23:19:05\",\"31-10-2016 23:20:00\",\"31-10-2016 23:20:09\",\"31-10-2016 23:25:12\",\"31-10-2016 23:26:08\",\"31-10-2016 23:26:49\",\"31-10-2016 23:29:40\",\"31-10-2016 23:38:41\",\"31-10-2016 23:39:27\"]',''),(141,1,NULL,NULL,'2016-10-31 23:27:52','2016-11-03 19:47:22','2016-11-03 19:47:22','::1','[0,38,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,7,3,3,7,3,3,7,3,0,38,3,3,3,7,3,38,3,38,3,3,23,3,35,42,8,9,8,42,40,41,40,40,40,41,40,41,40,40,41,40,40,41,40,40,41,40,40,41,40,40,41,40,40,41,40,40,41,40,41,40,41,40]','[\"31-10-2016 23:27:55\",\"31-10-2016 23:28:07\",\"31-10-2016 23:28:16\",\"31-10-2016 23:29:50\",\"31-10-2016 23:32:02\",\"31-10-2016 23:32:49\",\"31-10-2016 23:33:06\",\"31-10-2016 23:33:34\",\"31-10-2016 23:34:40\",\"31-10-2016 23:34:52\",\"31-10-2016 23:35:03\",\"31-10-2016 23:35:20\",\"31-10-2016 23:36:23\",\"31-10-2016 23:36:49\",\"31-10-2016 23:37:05\",\"31-10-2016 23:37:19\",\"31-10-2016 23:38:51\",\"31-10-2016 23:39:36\",\"31-10-2016 23:39:57\",\"31-10-2016 23:40:09\",\"31-10-2016 23:51:42\",\"31-10-2016 23:52:01\",\"31-10-2016 23:52:13\",\"31-10-2016 23:56:04\",\"31-10-2016 23:56:15\",\"31-10-2016 23:56:21\",\"01-11-2016 00:18:06\",\"01-11-2016 00:18:18\",\"01-11-2016 00:18:29\",\"01-11-2016 00:22:14\",\"01-11-2016 00:22:57\",\"01-11-2016 00:23:37\",\"01-11-2016 00:24:00\",\"01-11-2016 00:25:16\",\"01-11-2016 00:25:27\",\"01-11-2016 00:26:17\",\"01-11-2016 00:26:42\",\"02-11-2016 09:16:31\",\"02-11-2016 09:16:44\",\"02-11-2016 09:16:58\",\"03-11-2016 14:18:47\",\"03-11-2016 14:42:29\",\"03-11-2016 14:42:54\",\"03-11-2016 14:43:13\",\"03-11-2016 14:43:16\",\"03-11-2016 14:44:33\",\"03-11-2016 14:48:34\",\"03-11-2016 14:49:25\",\"03-11-2016 14:50:36\",\"03-11-2016 18:33:42\",\"03-11-2016 18:44:32\",\"03-11-2016 18:44:50\",\"03-11-2016 18:46:06\",\"03-11-2016 18:46:18\",\"03-11-2016 18:46:44\",\"03-11-2016 18:48:38\",\"03-11-2016 18:48:54\",\"03-11-2016 18:49:14\",\"03-11-2016 18:56:34\",\"03-11-2016 18:56:50\",\"03-11-2016 18:57:25\",\"03-11-2016 19:15:33\",\"03-11-2016 19:15:48\",\"03-11-2016 19:16:06\",\"03-11-2016 19:21:02\",\"03-11-2016 19:21:16\",\"03-11-2016 19:21:37\",\"03-11-2016 19:34:57\",\"03-11-2016 19:35:16\",\"03-11-2016 19:35:38\",\"03-11-2016 19:37:03\",\"03-11-2016 19:38:34\",\"03-11-2016 19:39:07\",\"03-11-2016 19:39:38\",\"03-11-2016 19:41:14\",\"03-11-2016 19:44:45\",\"03-11-2016 19:44:58\",\"03-11-2016 19:45:50\",\"03-11-2016 19:47:01\",\"03-11-2016 19:47:22\"]',''),(142,1,NULL,NULL,'2016-11-04 17:15:13','2016-11-04 19:04:08',NULL,'::1','[0,0,8,9,8,0,43,43,43,43,43,43,43,43,43,43,43]','[\"04-11-2016 17:15:16\",\"04-11-2016 18:48:10\",\"04-11-2016 18:49:07\",\"04-11-2016 18:49:17\",\"04-11-2016 18:49:19\",\"04-11-2016 18:49:27\",\"04-11-2016 18:49:41\",\"04-11-2016 18:51:04\",\"04-11-2016 18:51:08\",\"04-11-2016 18:59:47\",\"04-11-2016 18:59:53\",\"04-11-2016 19:01:44\",\"04-11-2016 19:01:49\",\"04-11-2016 19:03:36\",\"04-11-2016 19:03:40\",\"04-11-2016 19:04:04\",\"04-11-2016 19:04:08\"]',''),(143,1,NULL,NULL,'2016-11-04 19:08:35','2016-11-04 19:57:14',NULL,'::1','[0,43,43,43,43,43,43,43,43,43,43,43,43,43,43]','[\"04-11-2016 19:08:37\",\"04-11-2016 19:08:57\",\"04-11-2016 19:09:01\",\"04-11-2016 19:09:04\",\"04-11-2016 19:09:52\",\"04-11-2016 19:10:07\",\"04-11-2016 19:10:34\",\"04-11-2016 19:10:51\",\"04-11-2016 19:11:09\",\"04-11-2016 19:11:26\",\"04-11-2016 19:19:11\",\"04-11-2016 19:45:45\",\"04-11-2016 19:50:34\",\"04-11-2016 19:56:38\",\"04-11-2016 19:57:14\"]','');

/*Table structure for table `controleur` */

DROP TABLE IF EXISTS `controleur`;

CREATE TABLE `controleur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `controleur` */

insert  into `controleur`(`id`,`nom`,`description`) values (1,'UserController','Controlleur qui gère les utilisateurs'),(2,'ProfilController','Controlleur qui gère les utilisateurs'),(3,'EleveController','Controlleur qui gère les abonnés'),(4,'AbonneController','Controlleur qui gère les abonnés'),(5,'DroitController','Controlleur qui gère les  droits d\'accès aux fonctionnalités'),(6,'MatiereController','Controlleur qui gère les utilisateurs'),(7,'NiveauController','Controlleur qui gère les utilisateurs'),(8,'ConfigurationController','Controlleur qui gère les utilisateurs'),(9,'PlanComptableController','Contrôleur qui gère lajout des Caisses'),(10,'TypeOperationController','Contrôleur qui gère lajout des Caisses'),(11,'SchemaController','Contrôleur qui gère lajout des Caisses'),(12,'OperationController','Controlleur qui gère les opérations'),(13,'ClasseController','Controlleur qui gère les utilisateurs');

/*Table structure for table `decoupage` */

DROP TABLE IF EXISTS `decoupage`;

CREATE TABLE `decoupage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typedecoupage_id` int(11) DEFAULT NULL,
  `libelle_decoupage` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description_decoupage` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `etat_decoupage` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_380D87ADA8361C65` (`typedecoupage_id`),
  CONSTRAINT `FK_380D87ADA8361C65` FOREIGN KEY (`typedecoupage_id`) REFERENCES `type_decoupage` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `decoupage` */

insert  into `decoupage`(`id`,`typedecoupage_id`,`libelle_decoupage`,`description_decoupage`,`etat_decoupage`) values (1,1,'Trimestre 1','Trimestre 1',2),(2,1,'Trimestre 2','Trimestre 2',2),(3,1,'Trimestre 3','Trimestre 3',1),(4,2,'Semestre 1','Semestre 1',2),(5,2,'Semestre 2','Semestre 2',1);

/*Table structure for table `degre` */

DROP TABLE IF EXISTS `degre`;

CREATE TABLE `degre` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle_degre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description_degre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `etat_degre` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `degre` */

insert  into `degre`(`id`,`libelle_degre`,`description_degre`,`etat_degre`) values (1,'Premier Dégré','Classe de CP1 au CM2',1),(2,'Deuxieme Dégre','Classe de 6 eme en 3eme',1),(3,'Troisième degré','Description  Troisième degré',1);

/*Table structure for table `ecolage` */

DROP TABLE IF EXISTS `ecolage`;

CREATE TABLE `ecolage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `anneescolaire_id` int(11) DEFAULT NULL,
  `degre_id` int(11) DEFAULT NULL,
  `enseignement_id` int(11) DEFAULT NULL,
  `etablissement_id` int(11) DEFAULT NULL,
  `libelle_ecolage` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `montant_ecolage` int(11) NOT NULL,
  `etat_ecolage` int(11) NOT NULL,
  `niveau_id` int(11) DEFAULT NULL,
  `tranche_ecolage` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_FE61A04F7DEA6356` (`anneescolaire_id`),
  KEY `IDX_FE61A04FEB168534` (`degre_id`),
  KEY `IDX_FE61A04FABEC3B20` (`enseignement_id`),
  KEY `IDX_FE61A04FFF631228` (`etablissement_id`),
  KEY `IDX_FE61A04FB3E9C81` (`niveau_id`),
  CONSTRAINT `FK_FE61A04F7DEA6356` FOREIGN KEY (`anneescolaire_id`) REFERENCES `annee_scolaire` (`id`),
  CONSTRAINT `FK_FE61A04FABEC3B20` FOREIGN KEY (`enseignement_id`) REFERENCES `enseignement` (`id`),
  CONSTRAINT `FK_FE61A04FB3E9C81` FOREIGN KEY (`niveau_id`) REFERENCES `niveau` (`id`),
  CONSTRAINT `FK_FE61A04FEB168534` FOREIGN KEY (`degre_id`) REFERENCES `degre` (`id`),
  CONSTRAINT `FK_FE61A04FFF631228` FOREIGN KEY (`etablissement_id`) REFERENCES `etablissement` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `ecolage` */

insert  into `ecolage`(`id`,`anneescolaire_id`,`degre_id`,`enseignement_id`,`etablissement_id`,`libelle_ecolage`,`montant_ecolage`,`etat_ecolage`,`niveau_id`,`tranche_ecolage`) values (1,1,NULL,NULL,NULL,'55000',55000,1,1,3),(2,1,NULL,NULL,NULL,'65000',65000,1,2,3);

/*Table structure for table `eleve` */

DROP TABLE IF EXISTS `eleve`;

CREATE TABLE `eleve` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `etablissement_id` int(11) DEFAULT NULL,
  `username_eleve` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nom_eleve` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `prenoms_eleve` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `code_base_eleve` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_eleve` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tel1_eleve` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `adresse_eleve` longtext COLLATE utf8_unicode_ci,
  `etat_eleve` smallint(6) NOT NULL,
  `attempt_eleve` smallint(6) DEFAULT NULL,
  `password_eleve` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `c_password_eleve` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `salt_eleve` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `etat_connecte` tinyint(1) DEFAULT NULL,
  `adresse_ip_eleve` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sexe_eleve` smallint(6) DEFAULT NULL,
  `date_inscription` datetime NOT NULL,
  `date_edit_eleve` datetime DEFAULT NULL,
  `loss_password_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_loss_password` datetime DEFAULT NULL,
  `nom_contact` varchar(70) COLLATE utf8_unicode_ci DEFAULT NULL,
  `etablissement_origine` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nationalite` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `numero_piece_naissance` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `numero_piece_nationnalite` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lieu_naissance` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `groupe_sanguin` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bp` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_naissance` datetime NOT NULL,
  `titre_image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_ECA105F771EC4478` (`email_eleve`),
  KEY `IDX_ECA105F7FF631228` (`etablissement_id`),
  CONSTRAINT `FK_ECA105F7FF631228` FOREIGN KEY (`etablissement_id`) REFERENCES `etablissement` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `eleve` */

insert  into `eleve`(`id`,`etablissement_id`,`username_eleve`,`nom_eleve`,`prenoms_eleve`,`code_base_eleve`,`email_eleve`,`tel1_eleve`,`adresse_eleve`,`etat_eleve`,`attempt_eleve`,`password_eleve`,`c_password_eleve`,`salt_eleve`,`etat_connecte`,`adresse_ip_eleve`,`sexe_eleve`,`date_inscription`,`date_edit_eleve`,`loss_password_url`,`date_loss_password`,`nom_contact`,`etablissement_origine`,`nationalite`,`numero_piece_naissance`,`numero_piece_nationnalite`,`lieu_naissance`,`groupe_sanguin`,`bp`,`date_naissance`,`titre_image`) values (1,1,NULL,'TEVI','Fessou Atassé Armand','TDJZV','fev.atasse@yahoo.fr','90860831','Maison Face CEG Adamavo',1,0,'52605a118d3123252d8ede57844b89e5','52605a118d3123252d8ede57844b89e5','80b6bdb0d712ccc0699a8c0f0435f925',0,NULL,0,'2016-08-08 16:35:24',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0000-00-00 00:00:00',''),(2,1,NULL,'TEVID','Fessou Atassé','75BD6','fev.atasssqse@yahoo.fr','90860781','Maison TEVI',2,0,'9d4a4c3aecc4ac2521371fc66492129f','testMotPasse','04624ffbce72487df754613f93c9b982',0,NULL,0,'2016-08-10 14:56:46',NULL,NULL,NULL,NULL,'CEG Nouvelle','TG','ODJDZ','KKDKDK','Lomé',NULL,'3966','2016-08-29 00:00:00',''),(3,1,NULL,'TIKOU','Ahinou','ZG51B','fev.atasssqsse@yahoo.fr','92586120','Maison cise à Lomé',1,0,'9d4a4c3aecc4ac2521371fc66492129f','testMotPasse','8b6065015d798f6e1cb74a590291b96a',0,NULL,0,'2016-08-11 01:09:17',NULL,NULL,NULL,NULL,'CEH You','AF','UEYD874541','TEDS784554','Tabligbo',NULL,'9871','2016-08-02 00:00:00',''),(4,1,NULL,'YOUBFOR','Fessou','IZFJW','fev.atasssqsse122@yahoo.fr','98745511','Maison face CEG Adamavo',1,0,'9d4a4c3aecc4ac2521371fc66492129f','testMotPasse','b8fcdc5130607ab63318f10b2c1ba7a8',0,NULL,0,'2016-08-11 19:13:54',NULL,NULL,NULL,NULL,'CEG Nouvelle','AS','7810952123','TEOD784522','Lomé',NULL,'3855','2016-08-11 00:00:00',''),(5,1,NULL,'YOUBFOR12','Fessou','PDRJF','fev.atasssqsse1221@yahoo.fr','98745587','Maison face CEG Adamavo',1,0,'9d4a4c3aecc4ac2521371fc66492129f','testMotPasse','f0af9528a0442d202bb6a6541e91ddeb',0,NULL,0,'2016-08-11 19:22:25',NULL,NULL,NULL,NULL,'CEG Nouvelle','AS','7810952123','TEOD784522','Lomé',NULL,'3855','2016-08-11 00:00:00',''),(6,1,NULL,'LILI','Ameguan','E5KWR','email@tevi.com','96860831','Adresse de Lomé',1,0,'9d4a4c3aecc4ac2521371fc66492129f','testMotPasse','1e31671193da0a156f682e19c0790446',0,NULL,0,'2016-09-22 18:01:25',NULL,NULL,NULL,NULL,'CEG Nouvelle','TG','452368','445588','Lomé',NULL,'9871','2016-09-14 00:00:00',''),(7,1,NULL,'LILI','Ameguan','Z82WS','email@tevio.com','96860841','Adresse de Lomé',1,0,'9d4a4c3aecc4ac2521371fc66492129f','testMotPasse','4fc011f926abac1ddb28c385a5ad7092',0,NULL,0,'2016-09-22 18:03:44',NULL,NULL,NULL,NULL,'CEG Nouvelle','TG','452368','445588','Lomé',NULL,'9871','2016-09-14 00:00:00',''),(10,1,NULL,'NomEleve1','PrenomEleve1','Y9L67','eleve1@adresse1.com','90000001','Adresse Eleve 1',1,0,'9d4a4c3aecc4ac2521371fc66492129f','testMotPasse','b1d893c64b169c1a31ab3cfdba08dc62',0,NULL,2,'2016-09-22 18:43:30',NULL,NULL,NULL,NULL,'Etablissement Orignine Eleve 1','TG','Piece01','Nation01','LieuEleve1',NULL,'3901','2016-08-10 00:00:00',''),(12,1,NULL,'aNonEleve2','aPrenomEleve2','CGPJ0','anoneleve1@yahoo.fr','90000002','Lome',1,0,'9d4a4c3aecc4ac2521371fc66492129f','testMotPasse','01bd90508c05beeb2cb3a83bea9b628d',0,NULL,1,'2016-09-30 18:36:40',NULL,NULL,NULL,NULL,'Etablissement Orignine Eleve 2','TG','Piece02','Nation02','LieuEleve2',NULL,'3902','2016-09-28 00:00:00',''),(13,1,NULL,'aNonEleve3','aPrenomEleve3','1XWRA','anoneleve3@yahoo.fr','90000010','Lome',1,0,'9d4a4c3aecc4ac2521371fc66492129f','testMotPasse','f5bb2b8913519785912ed7099edfe511',0,NULL,1,'2016-09-30 18:44:17',NULL,NULL,NULL,NULL,NULL,'TG','Piece03','Nation03','LieuEleve3',NULL,'3967','2016-09-28 00:00:00',''),(14,1,NULL,'aNonEleve4','aPrenomEleve4','9G75K','anoneleve4@yahoo.fr','90000011','4477',1,0,'9d4a4c3aecc4ac2521371fc66492129f','testMotPasse','27bd0a4f6935c17d1a707ec66dfe0604',0,NULL,1,'2016-09-30 18:54:14',NULL,NULL,NULL,NULL,'Etablissement Orignine Eleve 2','DZ','Piece03','Nation04','LieuEleve4',NULL,'3968','2016-09-26 00:00:00',''),(15,1,NULL,'aNonEleve5','aPrenomEleve5','5NFQ3','anoneleve5@yahoo.fr','90000013','lome',1,0,'9d4a4c3aecc4ac2521371fc66492129f','testMotPasse','0efd5b08a55d7476e8a6591a9b7a2e63',0,NULL,2,'2016-10-01 01:10:33',NULL,NULL,NULL,NULL,'Etablissement Orignine Eleve 5','TG','Piece05','Nation05','LieuEleve5',NULL,'3902','2016-09-06 00:00:00','96977941157eef0e9a8d5e3.74436142.png');

/*Table structure for table `enseignement` */

DROP TABLE IF EXISTS `enseignement`;

CREATE TABLE `enseignement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `degre_id` int(11) DEFAULT NULL,
  `libelle_enseignement` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description_enseignement` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `etat_enseignement` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_BD310CCEB168534` (`degre_id`),
  CONSTRAINT `FK_BD310CCEB168534` FOREIGN KEY (`degre_id`) REFERENCES `degre` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `enseignement` */

insert  into `enseignement`(`id`,`degre_id`,`libelle_enseignement`,`description_enseignement`,`etat_enseignement`) values (1,2,'Collège Enseignement Général','Description Collège en',1),(2,1,'Primaire','Cours primaire',1),(3,3,'Lycée enseignement général','Description Lycée enseignement général',1),(4,3,'Lycée enseignement technique','Description Lycée enseignement technique',1);

/*Table structure for table `entrepot` */

DROP TABLE IF EXISTS `entrepot`;

CREATE TABLE `entrepot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ville_id` int(11) DEFAULT NULL,
  `code_entrepot` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `nom_entrepot` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `contact_entrepot` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `adresse_entrepot` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `seuil_entrepot` int(11) NOT NULL,
  `date_publication` date NOT NULL,
  `date_modification` date NOT NULL,
  `id_auteur` int(11) NOT NULL,
  `etat_entrepot` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2173750CA73F0036` (`ville_id`),
  CONSTRAINT `FK_2173750CA73F0036` FOREIGN KEY (`ville_id`) REFERENCES `ville` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `entrepot` */

/*Table structure for table `envoi` */

DROP TABLE IF EXISTS `envoi`;

CREATE TABLE `envoi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message_id` int(11) DEFAULT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `abonne_id` int(11) DEFAULT NULL,
  `statut` int(11) NOT NULL,
  `affichable` tinyint(1) NOT NULL,
  `date_envoi` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_CA7E3566537A1329` (`message_id`),
  KEY `IDX_CA7E3566FB88E14F` (`utilisateur_id`),
  KEY `IDX_CA7E3566C325A696` (`abonne_id`),
  CONSTRAINT `FK_CA7E3566537A1329` FOREIGN KEY (`message_id`) REFERENCES `message` (`id`),
  CONSTRAINT `FK_CA7E3566C325A696` FOREIGN KEY (`abonne_id`) REFERENCES `abonne` (`id`),
  CONSTRAINT `FK_CA7E3566FB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `envoi` */

/*Table structure for table `est_enseigne` */

DROP TABLE IF EXISTS `est_enseigne`;

CREATE TABLE `est_enseigne` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typematiere_id` int(11) DEFAULT NULL,
  `matiere_id` int(11) DEFAULT NULL,
  `niveau_id` int(11) DEFAULT NULL,
  `etat_est_enseigne` int(11) NOT NULL,
  `filiere_id` int(11) DEFAULT NULL,
  `coefficient` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_AC5BEA27D363255` (`typematiere_id`),
  KEY `IDX_AC5BEA27F46CD258` (`matiere_id`),
  KEY `IDX_AC5BEA27B3E9C81` (`niveau_id`),
  KEY `IDX_AC5BEA27180AA129` (`filiere_id`),
  CONSTRAINT `FK_AC5BEA27180AA129` FOREIGN KEY (`filiere_id`) REFERENCES `filiere` (`id`),
  CONSTRAINT `FK_AC5BEA27B3E9C81` FOREIGN KEY (`niveau_id`) REFERENCES `niveau` (`id`),
  CONSTRAINT `FK_AC5BEA27D363255` FOREIGN KEY (`typematiere_id`) REFERENCES `type_matiere` (`id`),
  CONSTRAINT `FK_AC5BEA27F46CD258` FOREIGN KEY (`matiere_id`) REFERENCES `matiere` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `est_enseigne` */

insert  into `est_enseigne`(`id`,`typematiere_id`,`matiere_id`,`niveau_id`,`etat_est_enseigne`,`filiere_id`,`coefficient`) values (1,3,4,2,1,1,4),(2,3,3,1,1,1,5),(3,3,4,1,1,2,4),(4,3,3,1,1,2,3),(5,3,1,2,1,1,2);

/*Table structure for table `est_parent` */

DROP TABLE IF EXISTS `est_parent`;

CREATE TABLE `est_parent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eleve_id` int(11) DEFAULT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `etat_est_parent` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6CA19F8AA6CC7B2` (`eleve_id`),
  KEY `IDX_6CA19F8AFB88E14F` (`utilisateur_id`),
  CONSTRAINT `FK_6CA19F8AA6CC7B2` FOREIGN KEY (`eleve_id`) REFERENCES `eleve` (`id`),
  CONSTRAINT `FK_6CA19F8AFB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `est_parent` */

insert  into `est_parent`(`id`,`eleve_id`,`utilisateur_id`,`etat_est_parent`) values (1,2,2,1),(2,2,3,1),(3,2,4,1),(4,3,5,1),(5,3,6,2),(6,4,7,1),(7,4,8,2),(8,5,9,1),(9,5,10,2),(10,5,10,3),(11,6,11,1),(12,6,12,2),(13,6,11,3),(14,7,13,1),(15,7,14,2),(16,7,13,3),(23,10,19,1),(24,10,20,2),(25,10,19,3),(27,12,24,1),(28,12,25,2),(29,12,24,3),(30,13,26,1),(31,13,27,2),(32,13,26,3),(33,14,28,1),(34,14,29,2),(35,14,28,3),(36,15,31,1),(37,15,32,2),(38,15,31,3),(39,1,37,1),(40,1,38,2),(41,1,39,3);

/*Table structure for table `etablissement` */

DROP TABLE IF EXISTS `etablissement`;

CREATE TABLE `etablissement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle_etablissement` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contact_etablissement` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `adresse_etablissement` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `devise_etablissement` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_initial_anneescolaire` datetime NOT NULL,
  `etat_etablissement` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `etablissement` */

insert  into `etablissement`(`id`,`libelle_etablissement`,`contact_etablissement`,`adresse_etablissement`,`devise_etablissement`,`date_initial_anneescolaire`,`etat_etablissement`) values (1,'Etablissement de test','90860831','Adresse de l\'établissement de test 1','Travail Liberté Patrie','2016-08-08 13:43:48',1);

/*Table structure for table `facture` */

DROP TABLE IF EXISTS `facture`;

CREATE TABLE `facture` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code_facture` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `date_publication` date NOT NULL,
  `etat_ville` smallint(6) NOT NULL,
  `type_ville` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `facture` */

insert  into `facture`(`id`,`code_facture`,`date_publication`,`etat_ville`,`type_ville`) values (1,'REC-1610-1','2016-10-08',1,0),(2,'REC-1610-2','2016-10-08',1,0),(3,'REC-1610-3','2016-10-08',1,0),(4,'REC-1610-4','2016-10-08',1,0),(5,'REC-1610-5','2016-10-08',1,0),(6,'REC-1610-6','2016-10-08',1,0),(7,'REC-1610-7','2016-10-08',1,0),(8,'REC-1610-8','2016-10-08',1,0),(9,'REC-1610-9','2016-10-08',1,0),(10,'REC-1610-10','2016-10-08',1,0),(11,'REC-1610-11','2016-10-08',1,0),(12,'REC-1610-13','2016-10-08',1,0),(13,'REC-1610-15','2016-10-08',1,0),(14,'REC-1610-17','2016-10-08',1,0),(15,'REC-1610-19','2016-10-08',1,0),(16,'REC-1610-20','2016-10-08',1,0),(17,'REC-1610-21','2016-10-08',1,0),(18,'REC-1610-23','2016-10-08',1,0),(19,'REC-1610-26','2016-10-08',1,0),(20,'REC-1610-27','2016-10-08',1,0),(21,'REC-1610-28','2016-10-08',1,0),(22,'REC-1610-31','2016-10-08',1,0),(23,'REC-1610-34','2016-10-08',1,0),(24,'REC-1610-37','2016-10-10',1,0);

/*Table structure for table `faire_cours` */

DROP TABLE IF EXISTS `faire_cours`;

CREATE TABLE `faire_cours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `classe_id` int(11) DEFAULT NULL,
  `jour_id` int(11) DEFAULT NULL,
  `anneescolaire_id` int(11) DEFAULT NULL,
  `estenseigne_id` int(11) DEFAULT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `etat_faire_cours` int(11) NOT NULL,
  `heurejournee_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_ECC8812A8F5EA509` (`classe_id`),
  KEY `IDX_ECC8812A220C6AD0` (`jour_id`),
  KEY `IDX_ECC8812A7DEA6356` (`anneescolaire_id`),
  KEY `IDX_ECC8812AFE1A89DF` (`estenseigne_id`),
  KEY `IDX_ECC8812AFB88E14F` (`utilisateur_id`),
  KEY `IDX_ECC8812A30BF887F` (`heurejournee_id`),
  CONSTRAINT `FK_ECC8812A220C6AD0` FOREIGN KEY (`jour_id`) REFERENCES `jour` (`id`),
  CONSTRAINT `FK_ECC8812A30BF887F` FOREIGN KEY (`heurejournee_id`) REFERENCES `heure_journee` (`id`),
  CONSTRAINT `FK_ECC8812A7DEA6356` FOREIGN KEY (`anneescolaire_id`) REFERENCES `annee_scolaire` (`id`),
  CONSTRAINT `FK_ECC8812A8F5EA509` FOREIGN KEY (`classe_id`) REFERENCES `classe` (`id`),
  CONSTRAINT `FK_ECC8812AFB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`),
  CONSTRAINT `FK_ECC8812AFE1A89DF` FOREIGN KEY (`estenseigne_id`) REFERENCES `est_enseigne` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `faire_cours` */

insert  into `faire_cours`(`id`,`classe_id`,`jour_id`,`anneescolaire_id`,`estenseigne_id`,`utilisateur_id`,`etat_faire_cours`,`heurejournee_id`) values (1,1,1,1,5,34,1,1),(3,1,3,1,5,21,1,2),(4,1,5,1,5,34,1,3),(5,1,1,1,5,21,1,5),(6,1,5,1,5,21,1,8);

/*Table structure for table `fichier` */

DROP TABLE IF EXISTS `fichier`;

CREATE TABLE `fichier` (
  `idfile` int(11) NOT NULL AUTO_INCREMENT,
  `nomfile` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `path` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  `type_file` int(11) DEFAULT NULL,
  `etat_file` int(11) DEFAULT NULL,
  `type_charge` int(11) DEFAULT NULL,
  `add_date` datetime DEFAULT NULL,
  PRIMARY KEY (`idfile`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `fichier` */

/*Table structure for table `filiere` */

DROP TABLE IF EXISTS `filiere`;

CREATE TABLE `filiere` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enseignement_id` int(11) DEFAULT NULL,
  `libelle_filiere` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `descriptionlibelle_filiere` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `etat_filiere` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2ED05D9EABEC3B20` (`enseignement_id`),
  CONSTRAINT `FK_2ED05D9EABEC3B20` FOREIGN KEY (`enseignement_id`) REFERENCES `enseignement` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `filiere` */

insert  into `filiere`(`id`,`enseignement_id`,`libelle_filiere`,`descriptionlibelle_filiere`,`etat_filiere`) values (1,1,'-','Filiere pour college',1),(2,4,'G1','description G1',1),(3,4,'G2','Description G2',1),(4,4,'G3','Description G3',1),(5,3,'CD','Description CD',1),(6,3,'A4','Description A4',1),(7,3,'C','Description C',1),(8,3,'D','Description D',1);

/*Table structure for table `fournisseur` */

DROP TABLE IF EXISTS `fournisseur`;

CREATE TABLE `fournisseur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code_fournisseur` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `nom_fournisseur` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `contact_fournisseur` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `adresse_fournisseur` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ressource_fournisseur` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_publication` date NOT NULL,
  `date_modification` date NOT NULL,
  `id_auteur` int(11) NOT NULL,
  `etat_fournisseur` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `fournisseur` */

/*Table structure for table `heure_cours` */

DROP TABLE IF EXISTS `heure_cours`;

CREATE TABLE `heure_cours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `heurecours_id` int(11) DEFAULT NULL,
  `fairecours_id` int(11) DEFAULT NULL,
  `etat_heure_cours` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_FDA1BB339BA59C8F` (`heurecours_id`),
  KEY `IDX_FDA1BB33C6A9FD4C` (`fairecours_id`),
  CONSTRAINT `FK_FDA1BB339BA59C8F` FOREIGN KEY (`heurecours_id`) REFERENCES `heure_cours` (`id`),
  CONSTRAINT `FK_FDA1BB33C6A9FD4C` FOREIGN KEY (`fairecours_id`) REFERENCES `faire_cours` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `heure_cours` */

/*Table structure for table `heure_journee` */

DROP TABLE IF EXISTS `heure_journee`;

CREATE TABLE `heure_journee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle_heure_journee` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `etat_heure_journee` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `heure_journee` */

insert  into `heure_journee`(`id`,`libelle_heure_journee`,`etat_heure_journee`) values (1,'1ere Heure',1),(2,'2eme Heure',1),(3,'3ème Heure',1),(4,'4ème Heure',1),(5,'5ème Heure',1),(6,'6ème Heure',1),(7,'7ème Heure',1),(8,'8ème Heure',1);

/*Table structure for table `histodateouverture` */

DROP TABLE IF EXISTS `histodateouverture`;

CREATE TABLE `histodateouverture` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_jour` date NOT NULL,
  `date_ouverte` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `histodateouverture` */

/*Table structure for table `image` */

DROP TABLE IF EXISTS `image`;

CREATE TABLE `image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fournisseur_id` int(11) DEFAULT NULL,
  `titre_image` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `date_publication` date NOT NULL,
  `etat_image` int(11) NOT NULL,
  `type_image` int(11) NOT NULL,
  `url_image` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C53D045F670C757F` (`fournisseur_id`),
  CONSTRAINT `FK_C53D045F670C757F` FOREIGN KEY (`fournisseur_id`) REFERENCES `fournisseur` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `image` */

/*Table structure for table `imagepersonne` */

DROP TABLE IF EXISTS `imagepersonne`;

CREATE TABLE `imagepersonne` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `abonne_id` int(11) DEFAULT NULL,
  `titre_image` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `date_publication` date NOT NULL,
  `etat_image` int(11) NOT NULL,
  `type_image` int(11) NOT NULL,
  `url_image` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_EE967F4EC325A696` (`abonne_id`),
  CONSTRAINT `FK_EE967F4EC325A696` FOREIGN KEY (`abonne_id`) REFERENCES `abonne` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `imagepersonne` */

/*Table structure for table `imageproduit` */

DROP TABLE IF EXISTS `imageproduit`;

CREATE TABLE `imageproduit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `produit_id` int(11) DEFAULT NULL,
  `titre_image` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `date_publication` date NOT NULL,
  `etat_image` int(11) NOT NULL,
  `type_image` int(11) NOT NULL,
  `url_image` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_7F8B556CF347EFB` (`produit_id`),
  CONSTRAINT `FK_7F8B556CF347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `imageproduit` */

/*Table structure for table `indice` */

DROP TABLE IF EXISTS `indice`;

CREATE TABLE `indice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle_indice` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description_indice` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `etat_indice` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `indice` */

insert  into `indice`(`id`,`libelle_indice`,`description_indice`,`etat_indice`) values (1,'1','Indice numéro 1',1),(2,'2','Indice numéro 2',1);

/*Table structure for table `jour` */

DROP TABLE IF EXISTS `jour`;

CREATE TABLE `jour` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle_jour` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description_jour` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `etat_jour` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `jour` */

insert  into `jour`(`id`,`libelle_jour`,`description_jour`,`etat_jour`) values (1,'Lundi','Lundi',1),(2,'Mardi','Mardi',1),(3,'Mercredi','Mercredi',1),(4,'Jeudi','Jeudi',1),(5,'Vendredi','Vendredi',1),(6,'Samedi','Samedi',1);

/*Table structure for table `lignecommande` */

DROP TABLE IF EXISTS `lignecommande`;

CREATE TABLE `lignecommande` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `produit_id` int(11) DEFAULT NULL,
  `commande_id` int(11) DEFAULT NULL,
  `quantite` int(11) DEFAULT NULL,
  `quantite_livre` int(11) DEFAULT NULL,
  `quantite_reste` int(11) DEFAULT NULL,
  `etat_ligne_commande` smallint(6) NOT NULL,
  `annule` tinyint(1) NOT NULL,
  `tauxtva` double DEFAULT NULL,
  `montantht` int(11) DEFAULT NULL,
  `montantautretaxe` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_364532CCF347EFB` (`produit_id`),
  KEY `IDX_364532CC82EA2E54` (`commande_id`),
  CONSTRAINT `FK_364532CC82EA2E54` FOREIGN KEY (`commande_id`) REFERENCES `commande` (`id`),
  CONSTRAINT `FK_364532CCF347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `lignecommande` */

/*Table structure for table `lignecommandetmp` */

DROP TABLE IF EXISTS `lignecommandetmp`;

CREATE TABLE `lignecommandetmp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `produit_id` int(11) DEFAULT NULL,
  `commande_tmp_id` int(11) DEFAULT NULL,
  `quantite` int(11) NOT NULL,
  `annule` tinyint(1) NOT NULL,
  `tauxtva` double DEFAULT NULL,
  `montantht` int(11) DEFAULT NULL,
  `montantautretaxe` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_48A38849F347EFB` (`produit_id`),
  KEY `IDX_48A38849B0B81AE3` (`commande_tmp_id`),
  CONSTRAINT `FK_48A38849B0B81AE3` FOREIGN KEY (`commande_tmp_id`) REFERENCES `commandetmp` (`id`),
  CONSTRAINT `FK_48A38849F347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `lignecommandetmp` */

/*Table structure for table `livrer` */

DROP TABLE IF EXISTS `livrer`;

CREATE TABLE `livrer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lignecommande_id` int(11) DEFAULT NULL,
  `entrepot_id` int(11) DEFAULT NULL,
  `nbre_libre` int(11) NOT NULL,
  `nbre_reste` int(11) NOT NULL,
  `date_livraison` date NOT NULL,
  `ref_bon_livraison` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `ref_bon_reception` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `etatLivraison` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E576B73220D3031` (`lignecommande_id`),
  KEY `IDX_E576B73272831E97` (`entrepot_id`),
  CONSTRAINT `FK_E576B73220D3031` FOREIGN KEY (`lignecommande_id`) REFERENCES `lignecommande` (`id`),
  CONSTRAINT `FK_E576B73272831E97` FOREIGN KEY (`entrepot_id`) REFERENCES `entrepot` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `livrer` */

/*Table structure for table `load_process` */

DROP TABLE IF EXISTS `load_process`;

CREATE TABLE `load_process` (
  `idprocessload` int(11) NOT NULL AUTO_INCREMENT,
  `iduser` int(11) NOT NULL,
  `dateprocess` datetime DEFAULT NULL,
  PRIMARY KEY (`idprocessload`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `load_process` */

/*Table structure for table `matiere` */

DROP TABLE IF EXISTS `matiere`;

CREATE TABLE `matiere` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle_matiere` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description_matiere` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `etat_matiere` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `matiere` */

insert  into `matiere`(`id`,`libelle_matiere`,`description_matiere`,`etat_matiere`) values (1,'Français','Description francais',1),(2,'Anglais','Description anglais',1),(3,'Mathématique','Description mathématique',1),(4,'Physique','Description Physique',1);

/*Table structure for table `matiere_utilisateur` */

DROP TABLE IF EXISTS `matiere_utilisateur`;

CREATE TABLE `matiere_utilisateur` (
  `matiere_id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  PRIMARY KEY (`matiere_id`,`utilisateur_id`),
  KEY `IDX_2CA025D7F46CD258` (`matiere_id`),
  KEY `IDX_2CA025D7FB88E14F` (`utilisateur_id`),
  CONSTRAINT `FK_2CA025D7F46CD258` FOREIGN KEY (`matiere_id`) REFERENCES `matiere` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_2CA025D7FB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `matiere_utilisateur` */

insert  into `matiere_utilisateur`(`matiere_id`,`utilisateur_id`) values (1,21),(1,34),(4,1),(4,21),(4,33);

/*Table structure for table `media` */

DROP TABLE IF EXISTS `media`;

CREATE TABLE `media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eleve_id` int(11) DEFAULT NULL,
  `titre_image` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `date_publication` date NOT NULL,
  `etat_image` int(11) NOT NULL,
  `type_image` int(11) NOT NULL,
  `url_image` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6A2CA10CA6CC7B2` (`eleve_id`),
  KEY `IDX_6A2CA10CFB88E14F` (`utilisateur_id`),
  CONSTRAINT `FK_6A2CA10CA6CC7B2` FOREIGN KEY (`eleve_id`) REFERENCES `eleve` (`id`),
  CONSTRAINT `FK_6A2CA10CFB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `media` */

/*Table structure for table `message` */

DROP TABLE IF EXISTS `message`;

CREATE TABLE `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int(11) DEFAULT NULL,
  `abonne_id` int(11) DEFAULT NULL,
  `message_transfere_id` int(11) DEFAULT NULL,
  `message_repondu_id` int(11) DEFAULT NULL,
  `objet` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `contenu` longtext COLLATE utf8_unicode_ci,
  `contenu_claire` longtext COLLATE utf8_unicode_ci,
  `etat` smallint(6) NOT NULL,
  `date_envoi` datetime NOT NULL,
  `cloturer` tinyint(1) NOT NULL,
  `type_envoi` int(11) NOT NULL,
  `code_fil` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B6BD307FFB88E14F` (`utilisateur_id`),
  KEY `IDX_B6BD307FC325A696` (`abonne_id`),
  KEY `IDX_B6BD307F10384EB1` (`message_transfere_id`),
  KEY `IDX_B6BD307F82118B10` (`message_repondu_id`),
  CONSTRAINT `FK_B6BD307F10384EB1` FOREIGN KEY (`message_transfere_id`) REFERENCES `message` (`id`),
  CONSTRAINT `FK_B6BD307F82118B10` FOREIGN KEY (`message_repondu_id`) REFERENCES `message` (`id`),
  CONSTRAINT `FK_B6BD307FC325A696` FOREIGN KEY (`abonne_id`) REFERENCES `abonne` (`id`),
  CONSTRAINT `FK_B6BD307FFB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `message` */

/*Table structure for table `module` */

DROP TABLE IF EXISTS `module`;

CREATE TABLE `module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `etat_module` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `module` */

insert  into `module`(`id`,`nom`,`description`,`etat_module`) values (1,'MODULE ADMINISTRATEUR','Module de gestion des administrateurs',1),(2,'MODULE UTILISATEUR','Module de gestion des utilisateurs',1),(3,'MODULE DROIT','Module de gestion des droits d\'accès aux fonctionnalités',1),(4,'MODULE PLAN COMPTABLE','Module de gestion des plans comptable ',1),(5,'MODULE TYPE OPERATION','Module de gestion des types operations ',1),(6,'MODULE SCHEMA','Module de gestion des schemas comptable ',1),(7,'MODULE PRODUIT','Module de gestion des produits ',1),(8,'MODULE FOURNISSUER','Module de gestion des fournisseurs ',1);

/*Table structure for table `niveau` */

DROP TABLE IF EXISTS `niveau`;

CREATE TABLE `niveau` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle_niveau` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description_niveau` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `etat_niveau` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `niveau` */

insert  into `niveau`(`id`,`libelle_niveau`,`description_niveau`,`etat_niveau`) values (1,'T','La classe de Terminal',1),(2,'S','Classe de Seconde',1);

/*Table structure for table `note` */

DROP TABLE IF EXISTS `note`;

CREATE TABLE `note` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setrouver_id` int(11) DEFAULT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `eleve_id` int(11) DEFAULT NULL,
  `decoupage_id` int(11) DEFAULT NULL,
  `etat_note` int(11) NOT NULL,
  `note` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_enregistre_note` datetime NOT NULL,
  `typeexamen_id` int(11) DEFAULT NULL,
  `matiere_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_CFBDFA144B714EEF` (`setrouver_id`),
  KEY `IDX_CFBDFA14A6CC7B2` (`eleve_id`),
  KEY `IDX_CFBDFA14BD92790A` (`decoupage_id`),
  KEY `IDX_CFBDFA14E6F70706` (`typeexamen_id`),
  KEY `IDX_CFBDFA14FB88E14F` (`utilisateur_id`),
  KEY `IDX_CFBDFA14F46CD258` (`matiere_id`),
  CONSTRAINT `FK_CFBDFA144B714EEF` FOREIGN KEY (`setrouver_id`) REFERENCES `se_trouver` (`id`),
  CONSTRAINT `FK_CFBDFA14A6CC7B2` FOREIGN KEY (`eleve_id`) REFERENCES `eleve` (`id`),
  CONSTRAINT `FK_CFBDFA14BD92790A` FOREIGN KEY (`decoupage_id`) REFERENCES `decoupage` (`id`),
  CONSTRAINT `FK_CFBDFA14E6F70706` FOREIGN KEY (`typeexamen_id`) REFERENCES `type_examen` (`id`),
  CONSTRAINT `FK_CFBDFA14F46CD258` FOREIGN KEY (`matiere_id`) REFERENCES `matiere` (`id`),
  CONSTRAINT `FK_CFBDFA14FB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=136 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `note` */

insert  into `note`(`id`,`setrouver_id`,`utilisateur_id`,`eleve_id`,`decoupage_id`,`etat_note`,`note`,`date_enregistre_note`,`typeexamen_id`,`matiere_id`) values (126,2,34,10,3,1,'12','2016-11-03 19:45:51',1,1),(127,3,34,12,3,1,'13','2016-11-03 19:45:52',1,1),(128,4,34,13,3,1,'14','2016-11-03 19:45:52',1,1),(129,5,34,14,3,1,'15','2016-11-03 19:45:53',1,1),(130,6,34,15,3,1,'16','2016-11-03 19:45:53',1,1),(131,2,34,10,3,1,'10','2016-11-03 19:47:23',1,1),(132,3,34,12,3,1,'10','2016-11-03 19:47:24',1,1),(133,4,34,13,3,1,'10','2016-11-03 19:47:24',1,1),(134,5,34,14,3,1,'10','2016-11-03 19:47:25',1,1),(135,6,34,15,3,1,'10','2016-11-03 19:47:25',1,1);

/*Table structure for table `operation` */

DROP TABLE IF EXISTS `operation`;

CREATE TABLE `operation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `commande_id` int(11) DEFAULT NULL,
  `produit_id` int(11) DEFAULT NULL,
  `caisse_id` int(11) DEFAULT NULL,
  `eleve_id` int(11) DEFAULT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `idtypeoperation` int(11) DEFAULT NULL,
  `compte` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `etablissement_id` int(11) DEFAULT NULL,
  `code_operation` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_operation` date NOT NULL,
  `date_valeur` date NOT NULL,
  `piece_operation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `etat_operation` int(11) DEFAULT NULL,
  `montant` int(11) NOT NULL,
  `comptabilise` int(11) NOT NULL,
  `id_abonne` int(11) DEFAULT NULL,
  `type_traite_operation` int(11) DEFAULT NULL,
  `num_mvt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `compte_auxiliaire` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nom_deposant` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lib_operation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tel_deposant` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `compte_client` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cheque` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ref_facture` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sensoperation` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `classe_id` int(11) DEFAULT NULL,
  `anneescolaire_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1981A66D82EA2E54` (`commande_id`),
  KEY `IDX_1981A66DF347EFB` (`produit_id`),
  KEY `IDX_1981A66D27B4FEBF` (`caisse_id`),
  KEY `IDX_1981A66DA6CC7B2` (`eleve_id`),
  KEY `IDX_1981A66DFB88E14F` (`utilisateur_id`),
  KEY `IDX_1981A66D8324D32A` (`idtypeoperation`),
  KEY `IDX_1981A66DCFF65260` (`compte`),
  KEY `IDX_1981A66DFF631228` (`etablissement_id`),
  KEY `IDX_1981A66D8F5EA509` (`classe_id`),
  KEY `IDX_1981A66D7DEA6356` (`anneescolaire_id`),
  CONSTRAINT `FK_1981A66D27B4FEBF` FOREIGN KEY (`caisse_id`) REFERENCES `caisse` (`id`),
  CONSTRAINT `FK_1981A66D7DEA6356` FOREIGN KEY (`anneescolaire_id`) REFERENCES `annee_scolaire` (`id`),
  CONSTRAINT `FK_1981A66D82EA2E54` FOREIGN KEY (`commande_id`) REFERENCES `commande` (`id`),
  CONSTRAINT `FK_1981A66D8324D32A` FOREIGN KEY (`idtypeoperation`) REFERENCES `typeoperation` (`idtypeoperation`),
  CONSTRAINT `FK_1981A66D8F5EA509` FOREIGN KEY (`classe_id`) REFERENCES `classe` (`id`),
  CONSTRAINT `FK_1981A66DA6CC7B2` FOREIGN KEY (`eleve_id`) REFERENCES `eleve` (`id`),
  CONSTRAINT `FK_1981A66DCFF65260` FOREIGN KEY (`compte`) REFERENCES `plancomptable` (`compte`),
  CONSTRAINT `FK_1981A66DF347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`),
  CONSTRAINT `FK_1981A66DFB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`),
  CONSTRAINT `FK_1981A66DFF631228` FOREIGN KEY (`etablissement_id`) REFERENCES `etablissement` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `operation` */

insert  into `operation`(`id`,`commande_id`,`produit_id`,`caisse_id`,`eleve_id`,`utilisateur_id`,`idtypeoperation`,`compte`,`etablissement_id`,`code_operation`,`date_operation`,`date_valeur`,`piece_operation`,`etat_operation`,`montant`,`comptabilise`,`id_abonne`,`type_traite_operation`,`num_mvt`,`compte_auxiliaire`,`nom_deposant`,`lib_operation`,`tel_deposant`,`compte_client`,`cheque`,`ref_facture`,`sensoperation`,`classe_id`,`anneescolaire_id`) values (4,NULL,NULL,1,15,NULL,2,'CPTE00001',NULL,NULL,'2016-10-08','2016-10-08','0',1,10000,0,0,1,'MVT161024','','TEC','TEC','','','','REC-1610-23','D',1,1),(5,NULL,NULL,1,15,NULL,2,'CPTE00002',NULL,NULL,'2016-10-08','2016-10-08','0',1,10000,0,0,1,'MVT161025','','TEC','TEC','','','','REC-1610-23','C',1,1),(6,NULL,NULL,1,15,NULL,2,'CPTE00001',1,NULL,'2016-10-08','2016-10-08','0',1,30000,0,NULL,1,'MVT161029','','TEVI E','TEVI E','','','','REC-1610-28','D',1,1),(7,NULL,NULL,1,15,NULL,2,'CPTE00002',1,NULL,'2016-10-08','2016-10-08','0',1,30000,0,NULL,1,'MVT161030','','TEVI E','TEVI E','','','','REC-1610-28','C',1,1),(8,NULL,NULL,1,15,NULL,2,'CPTE00001',1,NULL,'2016-10-08','2016-10-08','0',1,251232,0,NULL,1,'MVT161032','','TEV','TEV','','','','REC-1610-31','D',1,1),(9,NULL,NULL,1,15,NULL,2,'CPTE00002',1,NULL,'2016-10-08','2016-10-08','0',1,251232,0,NULL,1,'MVT161033','','TEV','TEV','','','','REC-1610-31','C',1,1),(10,NULL,NULL,1,15,NULL,2,'CPTE00001',1,NULL,'2016-10-08','2016-10-08','0',1,251232,0,NULL,1,'MVT161035','','TEV','TEV','','','','REC-1610-34','D',1,1),(11,NULL,NULL,1,15,NULL,2,'CPTE00002',1,NULL,'2016-10-08','2016-10-08','0',1,251232,0,NULL,1,'MVT161036','','TEV','TEV','','','','REC-1610-34','C',1,1);

/*Table structure for table `param` */

DROP TABLE IF EXISTS `param`;

CREATE TABLE `param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` int(11) NOT NULL,
  `valeur` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type_donnee` int(11) NOT NULL,
  `libelle` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type_param` int(11) NOT NULL,
  `affiche` int(11) NOT NULL,
  `date_choix` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_A4FA7C896C6E55B5` (`nom`),
  UNIQUE KEY `UNIQ_A4FA7C89A4D60759` (`libelle`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `param` */

insert  into `param`(`id`,`nom`,`valeur`,`type_donnee`,`libelle`,`description`,`type_param`,`affiche`,`date_choix`) values (1,1,'2000',1,'Durée max d\'inactivité','Durée d\'inactivité après laquelle l\'utilisateur est déconnecté automatiquement',0,1,NULL),(2,7,'@ETR_ACE@',4,'Clé SMS','La clé de validation des SMS reçus. Il s’agit de clé de KANNEL ',0,1,NULL),(3,6,'5',1,'Longueur code base','Longueur des codes de base attribués automatiquement aux abonnés à leur création',0,1,NULL),(4,10,'10',1,'Longueur code confirmation','Longueur des codes de confirmation des ordres de virement effectués et acquittés par la banque',0,1,NULL),(5,9,'10',1,'Longueur code sms','Longueur du code SMS envoyé à un abonné pour finaliser un ordre de virement ',0,1,NULL),(6,5,'14',1,'Longueur compte','Longueur des comptes bancaires ',0,1,NULL),(7,4,'5',1,'Longueur min password','Longueur minimale des mots de passe  ',0,1,NULL),(8,8,'8',1,'Longueur tel','Longueur des numéros de téléphone sans l\'indicatif téléphonique',0,1,NULL),(9,3,'3',1,'Max attempt','Nombre maximal de tentatives de connexions échouées ',0,1,NULL),(10,11,'10',1,'Nombre de messages tableau de bord','Nombre maximal de messages récents à afficher sur le tableau de bord',0,1,NULL),(11,2,'1',6,'Déconnexion automatique','Active/désactive la déconnexion automatique',0,1,NULL),(12,12,'2',1,'Durée de vie des urls de réinitialisation de mot de passe','Nombre maximal d\'heures après lesquelles une route de réinitialisation de mot de passe expire ',0,1,NULL),(13,13,'1',1,'Choix des destinaires d\'un msg par les abonnes','Indique si les abonné peuvent choisir le destinaire d\'un message ou pas ',0,1,NULL);

/*Table structure for table `pays` */

DROP TABLE IF EXISTS `pays`;

CREATE TABLE `pays` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_pays` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `code_pays` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `etat_pays` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `pays` */

/*Table structure for table `periode` */

DROP TABLE IF EXISTS `periode`;

CREATE TABLE `periode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `anneescolaire_id` int(11) DEFAULT NULL,
  `decoupage_id` int(11) DEFAULT NULL,
  `niveau_id` int(11) DEFAULT NULL,
  `libelle_periode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `etat_periode` int(11) NOT NULL,
  `date_debut_periode` datetime NOT NULL,
  `date_fin_periode` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_93C32DF37DEA6356` (`anneescolaire_id`),
  KEY `IDX_93C32DF3BD92790A` (`decoupage_id`),
  KEY `IDX_93C32DF3B3E9C81` (`niveau_id`),
  CONSTRAINT `FK_93C32DF37DEA6356` FOREIGN KEY (`anneescolaire_id`) REFERENCES `annee_scolaire` (`id`),
  CONSTRAINT `FK_93C32DF3B3E9C81` FOREIGN KEY (`niveau_id`) REFERENCES `niveau` (`id`),
  CONSTRAINT `FK_93C32DF3BD92790A` FOREIGN KEY (`decoupage_id`) REFERENCES `decoupage` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `periode` */

/*Table structure for table `plancomptable` */

DROP TABLE IF EXISTS `plancomptable`;

CREATE TABLE `plancomptable` (
  `compte` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `etablissement_id` int(11) DEFAULT NULL,
  `libelle` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `liea` int(11) NOT NULL,
  `type` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `datecreation` datetime NOT NULL,
  `suppr` int(11) NOT NULL,
  PRIMARY KEY (`compte`),
  KEY `IDX_7BC1317DFF631228` (`etablissement_id`),
  CONSTRAINT `FK_7BC1317DFF631228` FOREIGN KEY (`etablissement_id`) REFERENCES `etablissement` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `plancomptable` */

insert  into `plancomptable`(`compte`,`etablissement_id`,`libelle`,`liea`,`type`,`datecreation`,`suppr`) values ('CPTE00001',NULL,'Compte caisse',1,'M','2016-10-07 16:22:00',0),('CPTE00002',NULL,'Compte élève',3,'M','2016-10-07 16:35:15',0);

/*Table structure for table `presence` */

DROP TABLE IF EXISTS `presence`;

CREATE TABLE `presence` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setrouve_id` int(11) DEFAULT NULL,
  `fairecours_id` int(11) DEFAULT NULL,
  `etat_presence` int(11) NOT NULL,
  `si_present` int(11) NOT NULL,
  `date_presence` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6977C7A5BDC8E01C` (`setrouve_id`),
  KEY `IDX_6977C7A5C6A9FD4C` (`fairecours_id`),
  CONSTRAINT `FK_6977C7A5BDC8E01C` FOREIGN KEY (`setrouve_id`) REFERENCES `se_trouver` (`id`),
  CONSTRAINT `FK_6977C7A5C6A9FD4C` FOREIGN KEY (`fairecours_id`) REFERENCES `faire_cours` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `presence` */

/*Table structure for table `produit` */

DROP TABLE IF EXISTS `produit`;

CREATE TABLE `produit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categorie_id` int(11) DEFAULT NULL,
  `compte` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fournisseur_id` int(11) DEFAULT NULL,
  `code_produit` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `nom_produit` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description_produit` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pourcentage_produit` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `poids_produit` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `seuil_produit` int(11) NOT NULL,
  `fabricat_produit` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_publication` date NOT NULL,
  `date_commer` date NOT NULL,
  `date_modification` date NOT NULL,
  `id_auteur` int(11) NOT NULL,
  `etat_produit` int(11) NOT NULL,
  `montanthtAchat` int(11) DEFAULT NULL,
  `montanthtvente` int(11) DEFAULT NULL,
  `tauxTva` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `montantttcvente` int(11) DEFAULT NULL,
  `autretaxe` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E618D5BBBCF5E72D` (`categorie_id`),
  KEY `IDX_E618D5BBCFF65260` (`compte`),
  KEY `IDX_E618D5BB670C757F` (`fournisseur_id`),
  CONSTRAINT `FK_E618D5BB670C757F` FOREIGN KEY (`fournisseur_id`) REFERENCES `fournisseur` (`id`),
  CONSTRAINT `FK_E618D5BBBCF5E72D` FOREIGN KEY (`categorie_id`) REFERENCES `categorieproduit` (`id`),
  CONSTRAINT `FK_E618D5BBCFF65260` FOREIGN KEY (`compte`) REFERENCES `plancomptable` (`compte`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `produit` */

/*Table structure for table `profil` */

DROP TABLE IF EXISTS `profil`;

CREATE TABLE `profil` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `etat` smallint(6) NOT NULL,
  `type_profil` smallint(6) NOT NULL,
  `date_ajout_profil` datetime NOT NULL,
  `date_edit_profil` datetime DEFAULT NULL,
  `tab_id_actions` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `profil` */

insert  into `profil`(`id`,`nom`,`code`,`description`,`etat`,`type_profil`,`date_ajout_profil`,`date_edit_profil`,`tab_id_actions`) values (1,'Administrateur','MAINTE','Profil mainteance',1,1,'2016-08-08 11:58:11',NULL,'a:43:{i:0;i:1;i:1;i:2;i:2;i:4;i:3;i:10;i:4;i:11;i:5;i:12;i:6;i:13;i:7;i:14;i:8;i:15;i:9;i:16;i:10;i:17;i:11;i:18;i:12;i:19;i:13;i:20;i:14;i:21;i:15;i:22;i:16;i:39;i:17;i:40;i:18;i:41;i:19;i:43;i:20;i:3;i:21;i:5;i:22;i:6;i:23;i:7;i:24;i:23;i:25;i:35;i:26;i:36;i:27;i:37;i:28;i:38;i:29;i:42;i:30;i:8;i:31;i:9;i:32;i:24;i:33;i:25;i:34;i:26;i:35;i:27;i:36;i:28;i:37;i:29;i:38;i:30;i:39;i:31;i:40;i:32;i:41;i:33;i:42;i:34;}'),(2,'ABONNE','ABONNE','Profil Abonné',1,2,'2016-08-08 11:58:11',NULL,'a:0:{}'),(3,'Enseignant','LW6TG5K9E','Profils de l\'enseignant',1,1,'2016-09-30 13:13:26',NULL,'a:0:{}'),(4,'Parent d\'élève','3WNIE410B','Profil qui traite les relations parents d\'élève',1,2,'2016-09-30 13:17:12',NULL,'a:0:{}');

/*Table structure for table `recap_moyenne_generale` */

DROP TABLE IF EXISTS `recap_moyenne_generale`;

CREATE TABLE `recap_moyenne_generale` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setrouver_id` int(11) DEFAULT NULL,
  `decoupage_id` int(11) DEFAULT NULL,
  `etat_recap_moyenne_generale` int(11) NOT NULL,
  `date_enregistre_recap_moyenne_generale` datetime NOT NULL,
  `total_coef` int(11) DEFAULT NULL,
  `type_moyenne` int(11) DEFAULT NULL,
  `moyenne_classe` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rang_moyenne_classe` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `moyenne_compo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rang_moyenne_compo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `moyenne_generale` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rang_moyenne_generale` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_CEE89B564B714EEF` (`setrouver_id`),
  KEY `IDX_CEE89B56BD92790A` (`decoupage_id`),
  CONSTRAINT `FK_CEE89B564B714EEF` FOREIGN KEY (`setrouver_id`) REFERENCES `se_trouver` (`id`),
  CONSTRAINT `FK_CEE89B56BD92790A` FOREIGN KEY (`decoupage_id`) REFERENCES `decoupage` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `recap_moyenne_generale` */

insert  into `recap_moyenne_generale`(`id`,`setrouver_id`,`decoupage_id`,`etat_recap_moyenne_generale`,`date_enregistre_recap_moyenne_generale`,`total_coef`,`type_moyenne`,`moyenne_classe`,`rang_moyenne_classe`,`moyenne_compo`,`rang_moyenne_compo`,`moyenne_generale`,`rang_moyenne_generale`) values (11,2,3,1,'2016-11-03 19:45:52',NULL,NULL,'11','5','11','5','11','5'),(12,3,3,1,'2016-11-03 19:45:52',NULL,NULL,'11.5','4','11.5','4','11.5','4'),(13,4,3,1,'2016-11-03 19:45:53',NULL,NULL,'12','3','12','3','12','3'),(14,5,3,1,'2016-11-03 19:45:53',NULL,NULL,'12.5','2','12.5','2','12.5','2'),(15,6,3,1,'2016-11-03 19:45:53',NULL,NULL,'13','1','13','1','13','1');

/*Table structure for table `recap_note` */

DROP TABLE IF EXISTS `recap_note`;

CREATE TABLE `recap_note` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setrouver_id` int(11) DEFAULT NULL,
  `decoupage_id` int(11) DEFAULT NULL,
  `matiere_id` int(11) DEFAULT NULL,
  `etat_recap_note` int(11) NOT NULL,
  `date_enregistre_recap_note` datetime NOT NULL,
  `moyenne_interro` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `moyenne_devoir` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `note_classe` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rang_note_classe` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `moyenne_compo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `moyenne_generale` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rang_moyenne_generale` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2BFF69E24B714EEF` (`setrouver_id`),
  KEY `IDX_2BFF69E2BD92790A` (`decoupage_id`),
  KEY `IDX_2BFF69E2F46CD258` (`matiere_id`),
  CONSTRAINT `FK_2BFF69E24B714EEF` FOREIGN KEY (`setrouver_id`) REFERENCES `se_trouver` (`id`),
  CONSTRAINT `FK_2BFF69E2BD92790A` FOREIGN KEY (`decoupage_id`) REFERENCES `decoupage` (`id`),
  CONSTRAINT `FK_2BFF69E2F46CD258` FOREIGN KEY (`matiere_id`) REFERENCES `matiere` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `recap_note` */

insert  into `recap_note`(`id`,`setrouver_id`,`decoupage_id`,`matiere_id`,`etat_recap_note`,`date_enregistre_recap_note`,`moyenne_interro`,`moyenne_devoir`,`note_classe`,`rang_note_classe`,`moyenne_compo`,`moyenne_generale`,`rang_moyenne_generale`) values (13,2,3,1,1,'2016-11-03 19:45:52','11','11','11',NULL,'11','11',NULL),(14,3,3,1,1,'2016-11-03 19:45:52','11.5','11.5','11.5',NULL,'11.5','11.5',NULL),(15,4,3,1,1,'2016-11-03 19:45:53','12','12','12',NULL,'12','12',NULL),(16,5,3,1,1,'2016-11-03 19:45:53','12.5','12.5','12.5',NULL,'12.5','12.5',NULL),(17,6,3,1,1,'2016-11-03 19:45:53','13','13','13',NULL,'13','13',NULL);

/*Table structure for table `schemacomptable` */

DROP TABLE IF EXISTS `schemacomptable`;

CREATE TABLE `schemacomptable` (
  `idschema` int(11) NOT NULL AUTO_INCREMENT,
  `compte` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idtypeoperation` int(11) DEFAULT NULL,
  `etablissement_id` int(11) DEFAULT NULL,
  `sens` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `coef` int(11) NOT NULL,
  `valeur` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`idschema`),
  KEY `IDX_CC47E853CFF65260` (`compte`),
  KEY `IDX_CC47E8538324D32A` (`idtypeoperation`),
  KEY `IDX_CC47E853FF631228` (`etablissement_id`),
  CONSTRAINT `FK_CC47E8538324D32A` FOREIGN KEY (`idtypeoperation`) REFERENCES `typeoperation` (`idtypeoperation`),
  CONSTRAINT `FK_CC47E853CFF65260` FOREIGN KEY (`compte`) REFERENCES `plancomptable` (`compte`),
  CONSTRAINT `FK_CC47E853FF631228` FOREIGN KEY (`etablissement_id`) REFERENCES `etablissement` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `schemacomptable` */

insert  into `schemacomptable`(`idschema`,`compte`,`idtypeoperation`,`etablissement_id`,`sens`,`coef`,`valeur`) values (3,'CPTE00001',2,NULL,'D',1,'1'),(4,'CPTE00002',2,NULL,'C',1,'2');

/*Table structure for table `se_trouver` */

DROP TABLE IF EXISTS `se_trouver`;

CREATE TABLE `se_trouver` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eleve_id` int(11) DEFAULT NULL,
  `classe_id` int(11) DEFAULT NULL,
  `anneescolaire_id` int(11) DEFAULT NULL,
  `etat_se_trouver` int(11) NOT NULL,
  `si_reussir` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8BB500BCA6CC7B2` (`eleve_id`),
  KEY `IDX_8BB500BC8F5EA509` (`classe_id`),
  KEY `IDX_8BB500BC7DEA6356` (`anneescolaire_id`),
  CONSTRAINT `FK_8BB500BC7DEA6356` FOREIGN KEY (`anneescolaire_id`) REFERENCES `annee_scolaire` (`id`),
  CONSTRAINT `FK_8BB500BC8F5EA509` FOREIGN KEY (`classe_id`) REFERENCES `classe` (`id`),
  CONSTRAINT `FK_8BB500BCA6CC7B2` FOREIGN KEY (`eleve_id`) REFERENCES `eleve` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `se_trouver` */

insert  into `se_trouver`(`id`,`eleve_id`,`classe_id`,`anneescolaire_id`,`etat_se_trouver`,`si_reussir`) values (2,10,1,1,1,0),(3,12,1,1,1,0),(4,13,1,1,1,0),(5,14,1,1,1,0),(6,15,1,1,1,0);

/*Table structure for table `type_abonne` */

DROP TABLE IF EXISTS `type_abonne`;

CREATE TABLE `type_abonne` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_type_abonne` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `desc_type_abonne` longtext COLLATE utf8_unicode_ci,
  `etat_type_abonne` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `type_abonne` */

/*Table structure for table `type_decoupage` */

DROP TABLE IF EXISTS `type_decoupage`;

CREATE TABLE `type_decoupage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle_type_decoupage` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description_type_decoupage` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `etat_type_decoupage` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `type_decoupage` */

insert  into `type_decoupage`(`id`,`libelle_type_decoupage`,`description_type_decoupage`,`etat_type_decoupage`) values (1,'Trimestriel','Trois grand examen au cours de l\'année',1),(2,'Semestriel','Deux grands examens au cours de l\'année',1);

/*Table structure for table `type_examen` */

DROP TABLE IF EXISTS `type_examen`;

CREATE TABLE `type_examen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle_type_examen` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description_type_examen` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `etat_type_examen` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `type_examen` */

insert  into `type_examen`(`id`,`libelle_type_examen`,`description_type_examen`,`etat_type_examen`) values (1,'Interrogation','Interrogation',1),(2,'Devoir','Devoir',2),(3,'Examen','Examen',2);

/*Table structure for table `type_matiere` */

DROP TABLE IF EXISTS `type_matiere`;

CREATE TABLE `type_matiere` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle_type_matiere` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description_type_matiere` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `etat_type_matiere` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `type_matiere` */

insert  into `type_matiere`(`id`,`libelle_type_matiere`,`description_type_matiere`,`etat_type_matiere`) values (1,'Fondamentale','Fondamentale',1),(2,'Falcutative','Falcutative',1),(3,'Scientifiques','Scientifiques',1),(4,'Litteraires','Litteraires',1);

/*Table structure for table `typeoperation` */

DROP TABLE IF EXISTS `typeoperation`;

CREATE TABLE `typeoperation` (
  `idtypeoperation` int(11) NOT NULL AUTO_INCREMENT,
  `etablissement_id` int(11) DEFAULT NULL,
  `libtypeoperation` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `codeoperation` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `etattypeOperation` int(11) NOT NULL,
  `suppr` int(11) NOT NULL,
  `mouvFonds` int(11) NOT NULL,
  `nbreligne` int(11) NOT NULL,
  `codeopint` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`idtypeoperation`),
  KEY `IDX_FABEC4F1FF631228` (`etablissement_id`),
  CONSTRAINT `FK_FABEC4F1FF631228` FOREIGN KEY (`etablissement_id`) REFERENCES `etablissement` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `typeoperation` */

insert  into `typeoperation`(`idtypeoperation`,`etablissement_id`,`libtypeoperation`,`codeoperation`,`etattypeOperation`,`suppr`,`mouvFonds`,`nbreligne`,`codeopint`) values (1,NULL,'Inscription élèves','TYP01',0,0,0,2,NULL),(2,NULL,'paiement scolarite','TYP02',0,0,3,2,NULL);

/*Table structure for table `utilisateur` */

DROP TABLE IF EXISTS `utilisateur`;

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profil_id` int(11) DEFAULT NULL,
  `etablissement_id` int(11) DEFAULT NULL,
  `username_user` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nom_user` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `prenoms_user` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_user` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `tel1_user` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `tel2_user` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `adresse_user` longtext COLLATE utf8_unicode_ci,
  `etat_user` smallint(6) NOT NULL,
  `attempt_user` smallint(6) NOT NULL,
  `password_user` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `c_password_user` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `salt_user` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `etat_connecte` tinyint(1) NOT NULL,
  `adresse_ip_user` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sexe_user` smallint(6) DEFAULT NULL,
  `date_ajout_user` datetime NOT NULL,
  `date_edit_user` datetime DEFAULT NULL,
  `loss_password_url_user` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_loss_password_user` datetime DEFAULT NULL,
  `titre_image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1D1C63B312A5F6CC` (`email_user`),
  KEY `IDX_1D1C63B3275ED078` (`profil_id`),
  KEY `IDX_1D1C63B3FF631228` (`etablissement_id`),
  CONSTRAINT `FK_1D1C63B3275ED078` FOREIGN KEY (`profil_id`) REFERENCES `profil` (`id`),
  CONSTRAINT `FK_1D1C63B3FF631228` FOREIGN KEY (`etablissement_id`) REFERENCES `etablissement` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `utilisateur` */

insert  into `utilisateur`(`id`,`profil_id`,`etablissement_id`,`username_user`,`nom_user`,`prenoms_user`,`email_user`,`tel1_user`,`tel2_user`,`adresse_user`,`etat_user`,`attempt_user`,`password_user`,`c_password_user`,`salt_user`,`etat_connecte`,`adresse_ip_user`,`sexe_user`,`date_ajout_user`,`date_edit_user`,`loss_password_url_user`,`date_loss_password_user`,`titre_image`) values (1,1,NULL,'stockpro','stockprouser','Gautier','user@stock.com','99220453','','Adresse user',1,0,'a9924c4ad875a8166713f5d9180a1f25','a9924c4ad875a8166713f5d9180a1f25','02232ca88ab0a0148d25889bc65ac8aa',1,'::1',1,'2016-08-08 11:58:11',NULL,NULL,NULL,NULL),(2,1,NULL,NULL,'TEVI','Latevi','fev@yahoo.fr','90250654',NULL,'Maison face CEG',1,0,'TEVI','253d2438cd5400fd173f6210b30ce56e','47ca1a56eb47a9a2b6e2176a65efc3df',0,'',1,'2016-08-10 14:56:46',NULL,NULL,NULL,NULL),(3,1,NULL,NULL,'AKOVI','Kayi','fev.atasddse@yahoo.fr','90741245',NULL,'',1,0,'AKOVI','253d2438cd5400fd173f6210b30ce56e','0feca54523d24a8465ab2d7e7ade8428',0,'',1,'2016-08-10 14:56:47',NULL,NULL,NULL,NULL),(4,1,NULL,NULL,'TES','DSAod','fev.atasse@yahodo.fr','9075',NULL,'Lome',1,0,'TES','253d2438cd5400fd173f6210b30ce56e','df7232d3a831d5570475882d42871e33',0,'',1,'2016-08-10 14:56:47',NULL,NULL,NULL,NULL),(5,1,NULL,NULL,'HINOU','uoivi','hevs@yaj.com','98741562',NULL,'Kagome',1,0,'HINOU','253d2438cd5400fd173f6210b30ce56e','7c2618697a6290bcf4f9302ce37fd389',0,'',1,'2016-08-11 01:09:17',NULL,NULL,NULL,NULL),(6,1,NULL,NULL,'tevdo','jiddjs','gedi@yahoo.fr','2364722',NULL,'9874 dds',1,0,'tevdo','253d2438cd5400fd173f6210b30ce56e','d376aad5e10e10b76b464b3d1a34cd79',0,'',1,'2016-08-11 01:09:18',NULL,NULL,NULL,NULL),(7,1,NULL,NULL,'HOUNGBE','Anini','feds@him.com','98741562',NULL,'874ds',1,0,'HOUNGBE','253d2438cd5400fd173f6210b30ce56e','596620754864a3ed07f34339efd25f59',0,'',1,'2016-08-11 19:13:55',NULL,NULL,NULL,NULL),(8,1,NULL,NULL,'TITINOU','djinou','feved@gmail.com','98741125',NULL,'',1,0,'TITINOU','253d2438cd5400fd173f6210b30ce56e','a78df16f43fbc871f3ecae24d78c57dd',0,'',1,'2016-08-11 19:13:55',NULL,NULL,NULL,NULL),(9,1,NULL,NULL,'HINOU','Anini','','98741562',NULL,'',1,0,'HINOU','253d2438cd5400fd173f6210b30ce56e','1f475824f9ad944bef174d0f47e50474',0,'',1,'2016-08-11 19:22:25',NULL,NULL,NULL,NULL),(10,1,NULL,NULL,'tevdo','djinou','fev.fatasse788@yahoo.fr','98741125',NULL,'',1,0,'tevdo','253d2438cd5400fd173f6210b30ce56e','b70b91b09d2d659e6b4259eaff4ae659',0,'',1,'2016-08-11 19:22:25',NULL,NULL,NULL,NULL),(11,1,NULL,NULL,'Lili','granf','liligranf@yahoo.fr','97686802',NULL,'Lome Togo',1,0,'Lili','253d2438cd5400fd173f6210b30ce56e','70f8857ce478f7e53964b1399d3f93e7',0,'',1,'2016-09-22 18:01:25',NULL,NULL,NULL,NULL),(12,1,NULL,NULL,'NOUNOU','Merisal','liligrand78@gmail.com','98756412',NULL,'',1,0,'NOUNOU','253d2438cd5400fd173f6210b30ce56e','ff140acddb9fffcd8a3d60053edae717',0,'',1,'2016-09-22 18:01:26',NULL,NULL,NULL,NULL),(13,1,NULL,NULL,'Lili','granf','liligranfo@yahoo.fr','97686802',NULL,'Lome Togo',1,0,'Lili','253d2438cd5400fd173f6210b30ce56e','b4f54a0a2c1170028de69d6cc64656b1',0,'',1,'2016-09-22 18:03:44',NULL,NULL,NULL,NULL),(14,1,NULL,NULL,'NOUNOU','Merisal','liligrando@gmail.com','98756412',NULL,'',1,0,'NOUNOU','253d2438cd5400fd173f6210b30ce56e','a3df923c19190d86122863091b49c137',0,'',1,'2016-09-22 18:03:44',NULL,NULL,NULL,NULL),(19,1,NULL,NULL,'NomPEleve1','PrenomPEleve1','PEleve1@adresse.com','90000002',NULL,'Adresse PEleve 1',1,0,'NomPEleve1','253d2438cd5400fd173f6210b30ce56e','ee2334d3b62aad3b3f733aa3d3941749',0,'',1,'2016-09-22 18:43:30',NULL,NULL,NULL,NULL),(20,1,NULL,NULL,'NomMEleve1','PrenomMeleve1','MEleve1@adresse.com','90000003',NULL,'Adresse MEleve 1',1,0,'NomMEleve1','253d2438cd5400fd173f6210b30ce56e','55170594ac303423bc6278433cc879fd',0,'',1,'2016-09-22 18:43:30',NULL,NULL,NULL,NULL),(21,1,NULL,'util4','aNomUtilisateur','aPrenomUtilisateur','fev.utilisateur@yahoo.fr','90860831',NULL,'Adresse de Lomé',1,0,'8781eba6b2631a210de5e363c2834d83','8781eba6b2631a210de5e363c2834d83','5c3e36aa1bcfdf146018ef2058985087',0,'',0,'2016-09-30 14:10:46',NULL,NULL,NULL,NULL),(24,1,NULL,NULL,'NomPEleve2','PrenomPEleve2','liligranfper2@yahoo.fr','90000004',NULL,'Adresse PEleve 2',1,0,'NomPEleve2','253d2438cd5400fd173f6210b30ce56e','a67298195082fb3aee5266c6985eee2a',0,'',1,'2016-09-30 18:36:40',NULL,NULL,NULL,NULL),(25,1,NULL,NULL,'NomMEleve2','PrenomMeleve2','liligrand@gmail.com','90000006',NULL,'Adresse MEleve 2',1,0,'NomMEleve2','253d2438cd5400fd173f6210b30ce56e','fc3a2a26a5b367fa0e58474220dafb95',0,'',2,'2016-09-30 18:36:40',NULL,NULL,NULL,NULL),(26,1,NULL,NULL,'NomPEleve3','PrenomPEleve3','tevilatevi1000@yahoo.fr','90000020',NULL,'Lome Togo',1,0,'NomPEleve3','253d2438cd5400fd173f6210b30ce56e','73cd5a7e695968cfc22ad07e651565b5',0,'',1,'2016-09-30 18:44:17',NULL,NULL,NULL,NULL),(27,1,NULL,NULL,'NomMEleve3','PrenomMeleve3','liligrand356@gmail.com','90000030',NULL,'Adresse MEleve 2',1,0,'NomMEleve3','253d2438cd5400fd173f6210b30ce56e','01b55025681cce632998b4e16f46deac',0,'',2,'2016-09-30 18:44:17',NULL,NULL,NULL,NULL),(28,1,NULL,NULL,'NomPEleve4','PrenomPEleve4','tevilatevi21@yahoo.fr','90000021',NULL,'Lome Togo',1,0,'NomPEleve4','253d2438cd5400fd173f6210b30ce56e','a06da9b752d048fdc0c3b486ce993e4e',0,'',1,'2016-09-30 18:54:14',NULL,NULL,NULL,NULL),(29,1,NULL,NULL,'NomMEleve4','PrenomMeleve4','fev.atasddse32@yahoo.fr','90000031',NULL,'Maison face CEG',1,0,'NomMEleve4','253d2438cd5400fd173f6210b30ce56e','21a02768aaafb95d96879f42d3d2c314',0,'',2,'2016-09-30 18:54:14',NULL,NULL,NULL,NULL),(31,1,NULL,NULL,'NomPEleve5','PrenomPEleve5','liligranfper5@yahoo.fr','90000022',NULL,'Lome Togo',1,0,'NomPEleve5','253d2438cd5400fd173f6210b30ce56e','5a5c0ef6e7fe6e909620f5cc72bac48d',0,'',1,'2016-10-01 01:10:33',NULL,NULL,NULL,NULL),(32,1,NULL,NULL,'NomMEleve5','PrenomMeleve3','liligrandmer5@gmail.com','90000035',NULL,'Adresse MEleve 5',1,0,'NomMEleve5','253d2438cd5400fd173f6210b30ce56e','cb17b51f522bf60f19f2c8c68c0197f2',0,'',2,'2016-10-01 01:10:33',NULL,NULL,NULL,NULL),(33,1,NULL,'aUtil27','aNomUtilisateur1','aPrenomUtilisateur1','fev.utilisateur1@yahoo.fr','25252527',NULL,'Adresse de Lomé',1,0,'8781eba6b2631a210de5e363c2834d83','8781eba6b2631a210de5e363c2834d83','e95c98a584ea9461e69a66a18c512ed9',0,'',0,'2016-10-02 10:53:42',NULL,NULL,NULL,NULL),(34,1,NULL,'utilisateur2','aNomUtilisateur2','aPrenomUtilisateur2','fev.utilisateur2@yahoo.fr','25252528',NULL,'Adresse de Lomé',1,0,'8781eba6b2631a210de5e363c2834d83','8781eba6b2631a210de5e363c2834d83','f99649c4faf5e75dea54fc3e2ea47c50',0,'',0,'2016-10-02 10:56:20',NULL,NULL,NULL,'129538833257f0cbb4a73367.49871862.png'),(35,1,NULL,'TestUsername','aNomUtilisateur3','aPrenomUtilisateur3','autil3@yahoo.fr','90000060',NULL,'Test adresse',1,0,'8781eba6b2631a210de5e363c2834d83','8781eba6b2631a210de5e363c2834d83','2c6bbbd96a94f2c06d2f93adbaac7c02',0,'',1,'2016-10-10 17:19:31',NULL,NULL,NULL,NULL),(36,1,NULL,'tester','aNomUtilisateur4','aPrenomUtilisateur4','ers@gmail.com','98000002',NULL,'Lomé',1,0,'8781eba6b2631a210de5e363c2834d83','8781eba6b2631a210de5e363c2834d83','ed1c819c38a7fae79f9de7ccdd9fc76b',0,'',1,'2016-10-10 17:43:13',NULL,NULL,NULL,NULL),(37,1,NULL,'tester','aNomUtilisateur5','aPrenomUtilisateur5','ers5@gmail.com','98000003',NULL,'Lomé5',1,0,'8781eba6b2631a210de5e363c2834d83','8781eba6b2631a210de5e363c2834d83','717a9ff76d6baf825df875f9a49e2dce',0,'',1,'2016-10-10 17:45:26',NULL,NULL,NULL,NULL),(38,1,NULL,'yedsdd','aNomUtilisateur6','aPrenomUtilisateur6','hhhjkkd@yahoo.fr','90000061',NULL,'Adresse de Lomé',1,0,'8781eba6b2631a210de5e363c2834d83','8781eba6b2631a210de5e363c2834d83','0499ec8ca5877474f813505d382f00b3',0,'',2,'2016-10-10 18:43:51',NULL,NULL,NULL,NULL),(39,1,NULL,'admin521','aNomUtilisateur7','aPrenomUtilisateur7','jjdjdjd@yahoo.de','90000064',NULL,'hdjdk,',1,0,'8781eba6b2631a210de5e363c2834d83','8781eba6b2631a210de5e363c2834d83','a788340a3a76e8769462958202718f61',0,'',2,'2016-10-10 18:46:03',NULL,NULL,NULL,NULL);

/*Table structure for table `utilisateur_matiere` */

DROP TABLE IF EXISTS `utilisateur_matiere`;

CREATE TABLE `utilisateur_matiere` (
  `utilisateur_id` int(11) NOT NULL,
  `matiere_id` int(11) NOT NULL,
  PRIMARY KEY (`utilisateur_id`,`matiere_id`),
  KEY `IDX_EA1FA0D8FB88E14F` (`utilisateur_id`),
  KEY `IDX_EA1FA0D8F46CD258` (`matiere_id`),
  CONSTRAINT `FK_EA1FA0D8F46CD258` FOREIGN KEY (`matiere_id`) REFERENCES `matiere` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_EA1FA0D8FB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `utilisateur_matiere` */

insert  into `utilisateur_matiere`(`utilisateur_id`,`matiere_id`) values (1,4),(21,1),(21,4),(33,4),(34,1);

/*Table structure for table `ville` */

DROP TABLE IF EXISTS `ville`;

CREATE TABLE `ville` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pays_id` int(11) DEFAULT NULL,
  `nom_ville` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `etat_ville` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_43C3D9C3A6E44244` (`pays_id`),
  CONSTRAINT `FK_43C3D9C3A6E44244` FOREIGN KEY (`pays_id`) REFERENCES `pays` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `ville` */

/* Function  structure for function  `getCompteur` */

/*!50003 DROP FUNCTION IF EXISTS `getCompteur` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` FUNCTION `getCompteur`(p_type int, p_an int, p_mois int, p_entite varchar(40), p_taille int) RETURNS varchar(50) CHARSET latin1
BEGIN
        DECLARE  var_existe int  ;
        DECLARE var_res varchar(100) ;
       
        SELECT 	count(*) into var_existe
        FROM   	compteur
        WHERE 	entite = p_entite AND mois = p_mois AND annee = p_an and type_compt= p_type;
        if var_existe = 0 then		
		INSERT INTO compteur(annee, mois, type_compt, compteur, entite)
		VALUES(p_an, p_mois, p_type, 0, p_entite);
			
        end if;
        UPDATE 	compteur
        SET	compteur = (compteur+1)
        WHERE 	entite = p_entite AND mois = p_mois AND annee = p_an and type_compt= p_type;
        
        SELECT  compteur into var_res
        FROM   	compteur
        WHERE 	entite = p_entite AND mois = p_mois AND annee = p_an and type_compt= p_type;
	RETURN var_res ;
    END */$$
DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
