<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/*                                                                      */
/* NPDS Copyright (c) 2002-2020 by Philippe Brunier                     */
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
$reqalerte est un tableau où chaque requête correspond à un état du module qui nécessite une intervention de l'administrateur.
Ces requêtes généreront une notification dans l'administration et le bloc admin 
*/

global $NPDS_Prefix;
$reqalerte = array("SELECT id FROM ".$NPDS_Prefix."tdgal_img WHERE noaff=1");
?>