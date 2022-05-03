<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/*                                                                      */
/* NPDS Copyright (c) 2002-2022 by Philippe Brunier                     */
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
/* v 3.3 jpb-2022                                                       */
/************************************************************************/
if (stristr($_SERVER['PHP_SELF'],'imgalea.php')) die();
/**************************************************************************************************/
/* Page du block                                                                                  */
/**************************************************************************************************/
/* appel du bloc gauche ou droite: include#modules/npds_galerie/imgalea.php                       */
/**************************************************************************************************/
global $language, $NPDS_Prefix;
$ModPath="npds_galerie";
include_once("modules/$ModPath/lang/galerie-$language.php");

if (isset($user) and $user !='') {
   $tab_groupe = valid_group($user);
   $tab_groupe[] = 1;
}
if (isset($admin) && $admin!='')
   $tab_groupe[] = -127;
$tab_groupe[] = 0;

// Fabrication de la requête 1
$where1='';
$count = count($tab_groupe); $i = 0;
foreach($tab_groupe as $X => $val) {
   $where1.= "(acces='$val')";
   $i++;
   if ($i < $count) $where1.= " OR ";
}
$query = sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_gal WHERE $where1");
// Fabrication de la requête 2
$where2='';
$count = sql_num_rows($query); $i = 0;
while ($row = sql_fetch_row($query)) {
   $where2.= "(gal_id='$row[0]')";
   $i++;
   if ($i < $count) $where2.= ' OR ';
}
$query = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_img WHERE $where2 ORDER BY RAND() LIMIT 0,1");
$row = sql_fetch_row($query);

// Affichage
$image=$row[2];
$comment=$row[3];
list($gallery)=sql_fetch_row(sql_query("SELECT nom FROM ".$NPDS_Prefix."tdgal_gal WHERE id='$row[1]'"));

$ibid ='<img class="img-thumbnail n-irl" src="modules/'.$ModPath.'/imgs/'.$image.'" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.gal_translate("Cliquer sur image").'" loading="lazy" />';
$ibidg ='<img class="img-fluid card-img-top" src="modules/'.$ModPath.'/imgs/'.$image.'" loading="lazy" />';
$content ='';
if ($image!='') {
   $content .= '
      <span data-bs-toggle="modal" data-bs-target="#photomodal">'.$ibid.'</span>
      <div class="modal fade" id="photomodal" tabindex="-1" role="dialog" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
            '.$ibidg.'
            </div>
         </div>
      </div>
      <p class="card-text d-flex justify-content-left mt-2"><a class="" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.gal_translate("Accès à la galerie").'" href="modules.php?ModPath='.$ModPath.'&amp;ModStart=gal&amp;op=gal&amp;galid='.$row[1].'">
         '.stripslashes($gallery).'</a>
      </p>';
   }
else
   $content .= '<p class="card-text"><i class="fa fa-info-circle me-2"></i>'.gal_translate("Aucune galerie").'</p>';
if($admin)
   $content .= '<div class="text-end"><a class="tooltipbyclass" href="admin.php?op=Extend-Admin-SubModule&amp;ModPath=npds_galerie&amp;ModStart=admin/adm" title="[french]Administration[/french][english]Administration[/english][chinese]&#34892;&#25919;[/chinese][spanish]Administraci&oacute;n[/spanish][german]Verwaltung[/german]" data-bs-placement="left"><i class="fa fa-cogs fa-lg ms-1"></i></a></div>';
$content = aff_langue($content);
?>