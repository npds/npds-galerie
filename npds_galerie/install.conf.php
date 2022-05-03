<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/*                                                                      */
/* NPDS Copyright (c) 2002-2022 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/*                                                                      */
/* NMIG : NPDS Module Installer Generator                               */
/* ---------------------------------------------------------------------*/
/* Version 2.0 - 2015                                                   */
/* Générateur de fichier de configuration pour Module-Install 1.1       */
/* Développé par Boris - http://www.lordi-depanneur.com                 */
/* Module-Install est un installeur inspiré du programme d'installation */
/* d'origine du module Hot-Projet développé par Hotfirenet              */
/* ---------------------------------------------------------------------*/
/* Version 2.1 for NPDS 16 jpb 2017                                     */
/************************************************************************/

global $ModInstall;
#autodoc $name_module: Nom du module #required (no space and exotism)
$name_module = 'npds_galerie';

#autodoc $path_adm_module: chemin depuis $ModInstall #required SI admin avec interface
$path_adm_module = 'admin/adm';

#autodoc $affich: pour l'affichage du nom du module dans l'admin
$affich='npds_galerie';

#autodoc $icon: icon pour l'admin : c'est un nom de fichier(sans extension) !! #required SI admin avec interface
$icon='npds_galerie';

#autodoc $list_fich : Modifications de fichiers: Dans le premier tableau, tapez le nom du fichier
#autodoc et dans le deuxème, A LA MEME POSITION D'INDEX QUE LE PREMIER, tapez le code à insérer dans le fichier.
#autodoc Si le fichier doit être créé, n'oubliez pas les < ? php et ? > !!! (sans espace!).
#autodoc Synopsis: $list_fich = array(array("nom_fichier1","nom_fichier2"), array("contenu_fichier1","contenu_fichier2"));
$list_fich = array(array(''), array(''));

#autodoc $sql = array(""): Si votre module doit exécuter une ou plusieurs requêtes SQL, tapez vos requêtes ici.
#autodoc Attention! UNE requête par élément de tableau! SVP respecter la syntaxe suivante PAS d'espace avant l'instruction
#autodoc Synopsis: $sql = array("requête_sql_1","requête_sql_2");
global $NPDS_Prefix;
$sql = array("CREATE TABLE ".$NPDS_Prefix."tdgal_cat (
  id int(11) NOT NULL auto_increment,
  cid int(11) NOT NULL default '0',
  nom varchar(600) COLLATE utf8mb4_unicode_ci NOT NULL default '',
  acces tinyint(4) NOT NULL default '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",
"CREATE TABLE ".$NPDS_Prefix."tdgal_com (
  id int(11) NOT NULL auto_increment,
  pic_id int(11) NOT NULL default '0',
  user varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL default '',
  comment text COLLATE utf8mb4_unicode_ci NOT NULL,
  comhostname varchar(320) COLLATE utf8mb4_unicode_ci NOT NULL default '',
  comtimestamp varchar(14) COLLATE utf8mb4_unicode_ci default NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",
"CREATE TABLE ".$NPDS_Prefix."tdgal_gal (
  id int(11) NOT NULL auto_increment,
  cid int(11) NOT NULL default '0',
  nom varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL default '',
  date varchar(14) COLLATE utf8mb4_unicode_ci default NULL,
  acces tinyint(4) NOT NULL default '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",
"INSERT INTO ".$NPDS_Prefix."tdgal_gal VALUES (1, -1, 'Import', NULL, -127);",
"CREATE TABLE ".$NPDS_Prefix."tdgal_img (
  id int(11) NOT NULL auto_increment,
  gal_id int(11) NOT NULL default '0',
  name varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL default '',
  comment varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL default '',
  view int(11) NOT NULL default '0',
  ordre int(11) NOT NULL default '0',
  noaff int(1) unsigned default '0',
  img_lat varchar(11) DEFAULT NULL,
  img_long varchar(11) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",
"CREATE TABLE ".$NPDS_Prefix."tdgal_vot (
  id int(11) NOT NULL auto_increment,
  pic_id int(11) NOT NULL default '0',
  user varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL default '',
  rating tinyint(4) NOT NULL,
  ratinghostname varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL default '',
  ratingtimestamp varchar(14) COLLATE utf8mb4_unicode_ci default NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

#autodoc $blocs = array(array(""), array(""), array(""), array(""), array(""), array(""), array(""), array(""), array(""))
#autodoc                titre      contenu    membre     groupe     index      rétention  actif      aide       description
#autodoc Configuration des blocs
$blocs = array(array("Galeries Photo"), array("include#modules/".$name_module."/imgalea.php"), array("0"), array(""), array("1"), array("0"), array("1"), array(""), array("Galeries Photo"));

#autodoc $txtdeb : Vous pouvez mettre ici un texte de votre choix avec du html qui s'affichera au début de l'install
#autodoc Si rien n'est mis, le texte par défaut sera automatiquement affiché
$txtdeb = '<br /><strong>Attention:</strong> si le module '.$ModInstall.' est déjà installé sur votre site, veuillez sauter l\'étape de création de la base de données<br /><br />Sinon les tables tdgal_* de votre base de données seront écrasées</b><br /><br />';

#autodoc $txtfin : Vous pouvez mettre ici un texte de votre choix avec du html qui s'affichera à la fin de l'install
$txtfin = 'Nous vous recommandons de lire le tutorial situé dans le répertoire install du module.<br />Pour les questions et le support rendez vous sur <a href="http://www.npds.org" target="_blank">npds.org</a>';

#autodoc $end_link: Lien sur lequel sera redirigé l'utilisateur à la fin de l'install (si laissé vide, redirigé sur index.php)
#autodoc N'oubliez pas les '\' si vous utilisez des guillemets !!!
$end_link = 'admin.php?op=Extend-Admin-SubModule&ModPath='.$ModInstall.'&ModStart=admin/adm';
?>