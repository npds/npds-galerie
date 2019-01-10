<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/*                                                                      */
/* NPDS Copyright (c) 2002-2019 by Philippe Brunier                     */
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
/* MAJ jpb, phr - 2017 renommé npds_galerie pour Rev 16                 */
/* v 3.0                                                                */
/************************************************************************/

// For More security
if (!stristr($_SERVER['PHP_SELF'],"modules.php")) die();
if (strstr($ModPath,'..') || strstr($ModStart,'..') || stristr($ModPath, 'script') || stristr($ModPath, 'cookie') || stristr($ModPath, 'iframe') || stristr($ModPath, 'applet') || stristr($ModPath, 'object') || stristr($ModPath, 'meta') || stristr($ModStart, 'script') || stristr($ModStart, 'cookie') || stristr($ModStart, 'iframe') || stristr($ModStart, 'applet') || stristr($ModStart, 'object') || stristr($ModStart, 'meta'))
   die();
global $language, $NPDS_Prefix;
// For More security

include_once('functions.php');
include_once("modules/$ModPath/gal_conf.php");
include_once("modules/$ModPath/gal_func.php");
include_once("modules/$ModPath/lang/galerie-$language.php");
include ("modules/$ModPath/admin/pages.php");

// Paramètres utilisés par le script
$ThisFile = "modules.php?ModPath=$ModPath&amp;ModStart=gal";
$ThisRedo = "modules.php?ModPath=$ModPath&ModStart=$ModStart";

include("header.php");
settype($op,'string');
switch($op) {
   // Affichage des catégories et ses galeries
   case 'cat':
      echo '<div class="card">';
      FabMenuCat($catid);
      ListGalCat($catid);
      echo '</div>';
   break;
   // Affichage des sous-catégories et ses galeries
   case 'sscat':
      echo '<div class="card">';
      FabMenuSsCat($catid, $sscid);
      ListGalCat($sscid);
      echo '</div>';
   break;
   // Affichage d'une galerie
   case 'gal':
      echo '<div class="card">';
      FabMenuGal($galid);
      settype($page, "integer");
      if (empty($page)) { $page = 1; }
      ViewGal($galid, $page);
      echo '</div>';
   break;
   // Affichage d'une image
   case 'img':
      if ($pos < 0) $pos = GetPos($galid, $pos);
      echo '<div class="card">';
      FabMenuImg($galid, $pos);
      ViewImg($galid, $pos, '');
      echo '</div>';
   break;
   // Diaporama sur un album
   case 'diapo':
      echo '<div class="card">';
      ViewDiapo($galid, $pos, $pid);
      echo '</div>';
   break;
   // Ecard sur une image
   case 'ecard':
      PrintFormEcard($galid, $pos, $pid);
   break;
   // Post d'un commentaire
   case 'postcomment':
      echo '<div class="card">';
      PostComment($gal_id, $pos, $pic_id, $comm);
      echo '</div>';
   break;
   // Top des commentaires
   case 'topcomment':
      TopCV("comment",$nbtopcomment);
   break;
   // Top des votes
   case 'topvote':
      TopCV("vote",$nbtopvote);
   break;
   // Vote pour une image
   case 'vote':
      PostVote($gal_id, $pos, $pic_id, $value);
   break;

   case 'sendcard':
      PostEcard($galid, $pos, $pid, $from_name, $from_mail, $to_name, $to_mail, $card_sujet, $card_msg);
   break;
   // Affichage d'une seule image sans sa galerie
   case 'one-img':
      echo '<div class="card">';
      ViewImg($galid, $pos, "no");
      echo '</div>';
   break;
   // Proposition d'images par les membres
   case 'formimgs' :
      if(autorisation(1))
         PrintFormImgs();
      else
         redirect_url($nuke_url);
      break;

   case 'addimgs' :
      echo '<div class="card">';
      AddImgs($imggal,$newcard1,$newdesc1,$newcard2,$newdesc2,$newcard3,$newdesc3,$newcard4,$newdesc4,$newcard5,$newdesc5,$user_connecte);
      echo '</div>';
   break;

   default :
      echo '<div class="card">';
      FabMenu();
      echo '</div>';
      if ($view_alea) {
      echo '<div class="card my-3">';
      ViewAlea();
      echo '</div>';
      }
      if ($view_last) {
      echo '<div class="card my-3">';
      ViewLastAdd();
      echo '</div>';
     }
   break;
}

include("footer.php");
?>