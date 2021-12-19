<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/*                                                                      */
/* NPDS Copyright (c) 2002-2021 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/* Module de gestion de galeries pour NPDS                              */
/*                                                                      */
/* (c) 2004-2005 Tribal-Dolphin - http://www.tribal-dolphin.net         */
/* (c) 2007 Xgonin, Lopez - http://modules.npds.org                     */
/* MAJ conformité XHTML pour REvolution 10.02 par jpb/phr en mars 2010  */
/* MAJ Dev - 2011                                                       */
/* npds_galerie v 3.2                                                   */
/* Changement de nom du module version Rev16 par jpb/phr janv 2017      */
/************************************************************************/
/*
$reqalertes est un tableau où chaque tableau correspond à un état du module qui nécessite une intervention de l'administrateur.
Ces requêtes généreront une notification/alerte dans l'administration et le bloc admin 
*/
#autodoc $reqalertes = array(array("requête","retour de l'alerte","tooltip de l'alerte"), array("","","")...)
#autodoc  NB : si l'élément [1] du tableau "retour de l'alerte" est à "1" il renverra au final le nombre de ligne trouvé par la requete "requete" de l'élément [0] de son tableau ... tout autre valeur sera interprété telle quelle

global $NPDS_Prefix;
$reqalertes = array(array("SELECT id FROM ".$NPDS_Prefix."tdgal_img WHERE noaff=1","1","Images à valider"));
?>