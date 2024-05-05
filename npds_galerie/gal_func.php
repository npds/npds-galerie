<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
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
/* Fonctions du module                                                  */
/************************************************************************/
// les menus
/*******************************************************/
include 'modules/geoloc/geoloc.conf';

function FabMenu() {
   global $NPDS_Prefix, $ThisFile, $aff_comm, $aff_vote, $ModPath, $user;
   $query = sql_query("SELECT id, nom, acces FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='0' ORDER BY nom");
   if (sql_num_rows($query) != 0) {
      $ibid='';
      while($row = sql_fetch_row($query)) {
         if (autorisation($row[2])) {
            $ibid.='
            <div class="col-md-4 mb-2">
            <a href="'.$ThisFile.'&amp;op=cat&amp;catid='.$row[0].'"><i class="fa fa-folder fa-2x align-middle me-2"></i>'.stripslashes($row[1]).'</a>
            </div>';
         }
      }
      if ($ibid) {
         echo '
         <nav class="card-header lead nav flex-column flex-sm-row ps-0 align-items-center" role="navigation">
               <a class="nav-link disabled"><i class="fa fa-camera fa-2x align-middle me-2"></i>'.gal_translate("Accueil").'</a>';
         if ($aff_comm)
            echo '
               <a class="nav-link" href="modules.php?ModPath='.$ModPath.'&amp;ModStart=gal&amp;op=topcomment">'.gal_translate("Top-Commentaires").'</a>';
         if ($aff_vote)
            echo '
               <a class="nav-link" href="modules.php?ModPath='.$ModPath.'&amp;ModStart=gal&amp;op=topvote">'.gal_translate("Top-Votes").'</a>';
         if (isset($user))
            echo '
               <a class="nav-link" href="modules.php?ModPath='.$ModPath.'&amp;ModStart=gal&amp;op=formimgs">'.gal_translate("Proposer des images").'</a>';
         echo '
               <a class="nav-link" href=""><i class="fa fa-question fa-lg align-middle me-2"></i></a>
         </nav>
         <div class="card-body">
            <div class="row">';
         echo $ibid;
         echo '
            </div>
         </div>';
      }
   } else 
      echo '
         <div class="alert alert-info">'.gal_translate("Aucune catégorie trouvée").'</div>';
}

function FabMenuCat($catid) {
 global $NPDS_Prefix, $ModPath;
   $ThisFile = "modules.php?ModPath=$ModPath&amp;ModStart=gal";
   settype($catid,'integer');
   $nbsc='';
   $cat = sql_fetch_row(sql_query("SELECT nom, acces FROM ".$NPDS_Prefix."tdgal_cat WHERE id='".$catid."'"));
   if (autorisation($cat[1])) {
      $query = sql_query("SELECT id, nom, acces FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='".$catid."' ORDER BY nom");
      $nbsc = sql_num_rows($query);
      echo '
      <nav class="card-header lead">
         <ol class="breadcrumb bg-transparent ps-0 mb-0 align-items-center border-0">
            <li class="breadcrumb-item active"><a href="'.$ThisFile.'"><i class="fa fa-camera fa-lg align-middle me-2"></i>'.gal_translate("Accueil").'</a></li>
            <li class="breadcrumb-item active">'.stripslashes($cat[0]).'</li>
         </ol>
      </nav>';
      if($nbsc>0) { 
         echo '
      <div class="card-body">
         <h4>'.gal_translate("Sous-catégories").'<span class="float-end badge bg-secondary rounded-pill">'.$nbsc.'</span></h4>
         <hr />
         <div class="row">';
         while ($row = sql_fetch_row($query)) {
            if (autorisation($row[2])) {
               $ngal = sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_gal WHERE cid='".$row[0]."'");
               $nbgal=sql_num_rows($ngal);
               echo '
               <div class="col-md-4 mb-2">';
               if($nbgal>0)
                  echo '
                  <a href="'.$ThisFile.'&amp;op=sscat&amp;catid='.$catid.'&amp;sscid='.$row[0].'"><i class="fa fa-folder fa-2x align-middle me-2"></i>'.stripslashes($row[1]).'</a> <span class="badge bg-secondary rounded-pill">'.$nbgal.'</span>';
               else
                  echo '
                  <a class="text-muted"><i class="far fa-folder fa-2x align-middle me-2"></i>'.stripslashes($row[1]).'</a>';
               echo '
               </div>';
            }
         }
      echo '
         </div>
      </div>';
      }
   }
   else 
      echo '
      <div class="alert alert-danger">'.gal_translate("Aucune catégorie trouvée").'</div>';
}

function FabMenuSsCat($catid, $sscid) {
   global $NPDS_Prefix, $ModPath;
   $ThisFile = "modules.php?ModPath=$ModPath&amp;ModStart=gal";

   settype($catid,'integer');
   settype($sscid,'integer');
   $cat = sql_fetch_row(sql_query("SELECT nom, acces FROM ".$NPDS_Prefix."tdgal_cat WHERE id='".$catid."'"));
   if (autorisation($cat[1])) {
      $sscat = sql_fetch_row(sql_query("SELECT nom, acces FROM ".$NPDS_Prefix."tdgal_cat WHERE id='".$sscid."'"));
      if (autorisation($sscat[1]))
         echo '
      <nav class="card-header lead" aria-label="breadcrumb" role="navigation">
         <ol class="breadcrumb bg-transparent ps-0 mb-0 align-items-center border-0">
            <li class="breadcrumb-item"><a href="'.$ThisFile.'"><i class="fa fa-camera fa-lg align-middle me-2"></i>'.gal_translate("Accueil").'</a></li>
            <li class="breadcrumb-item"><a href="'.$ThisFile.'&op=cat&amp;catid='.$catid.'">'.stripslashes($cat[0]).'</a></li>
            <li class="breadcrumb-item active">'.stripslashes($sscat[0]).'</li>
         </ol>
      </nav>';
      else 
         echo '
      <div class="alert alert-danger">'.gal_translate("Aucune catégorie trouvée").'</div>';
   }
   else
      echo '<div class="alert alert-danger">'.gal_translate("Aucune catégorie trouvée").'</div>';
}

function FabMenuGal($galid) {
   global $NPDS_Prefix, $ModPath;
   $ThisFile = "modules.php?ModPath=$ModPath&amp;ModStart=gal";

   settype($galid,"integer");
   $gal = sql_fetch_row(sql_query("SELECT nom,acces FROM ".$NPDS_Prefix."tdgal_gal WHERE id='".$galid."'"));
   if (autorisation($gal[1])) {
      echo '
      <nav class="card-header lead" aria-label="breadcrumb" role="navigation">
         <ol class="breadcrumb bg-transparent ps-0 mb-0 align-items-center border-0">
            <li class="breadcrumb-item"><a href="'.$ThisFile.'"><i class="fa fa-camera fa-lg me-2 align-middle"></i>'.gal_translate("Accueil").'</a></li>';
      echo GetGalArbo($galid);
      echo '
            <li class="breadcrumb-item active">'.stripslashes($gal[0]).'</li>
         </ol>
      </nav>';
   }
   else 
      echo '
      <div class="alert alert-danger">'.gal_translate("Aucune galerie trouvée").'</div>';
}

function FabMenuImg($galid, $pos) {
   global $NPDS_Prefix, $ModPath;
   $ThisFile = "modules.php?ModPath=$ModPath&amp;ModStart=gal";
   settype($galid,'integer');
   settype($pos,'integer');
   $gal = sql_fetch_row(sql_query("SELECT nom,acces FROM ".$NPDS_Prefix."tdgal_gal WHERE id='".$galid."'"));
   if (autorisation($gal[1])) {
      echo '
      <nav class="card-header lead" aria-label="breadcrumb" role="navigation">
         <ol class="breadcrumb bg-transparent ps-0 mb-0 align-items-center border-0">
            <li class="breadcrumb-item"><a href="modules.php?ModPath='.$ModPath.'&amp;ModStart=gal"><i class="fa fa-camera fa-2x align-middle me-2"></i>'.gal_translate("Accueil").'</a></li>';
      echo GetGalArbo($galid);
      echo '
            <li class="breadcrumb-item"> <a href="'.$ThisFile.'&amp;op=gal&amp;galid='.$galid.'">'.stripslashes($gal[0]).'</a></li>';
      $img = sql_fetch_row(sql_query("SELECT comment FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='".$galid."' and noaff='0' ORDER BY ordre,id LIMIT $pos,1"));
      if ($img[0]!='')
         echo '
            <li class="breadcrumb-item active">'.stripslashes($img[0]).'</li>';
      echo '
         </ol>
      </nav>';
   }
   else 
      echo '
      <div class="alert alert-danger">'.gal_translate("Aucune galerie trouvée").'</div>';
}
// les menus
function ListGalCat($catid) {
   global $NPDS_Prefix, $ModPath;
   $ThisFile = "modules.php?ModPath=$ModPath&amp;ModStart=gal";
   settype($catid,'integer');
   $gal = sql_query("SELECT id,nom,date,acces FROM ".$NPDS_Prefix."tdgal_gal WHERE cid='".$catid."' ORDER BY nom");
   $nb_gal= sql_num_rows($gal);
   if ($nb_gal != 0) {
      $ibid='';
      while ($row = sql_fetch_row($gal)) {
         if (autorisation($row[3])) {
            $nimg = sql_fetch_row(sql_query("SELECT COUNT(id) FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='".$row[0]."' and noaff='0'"));
            $ibid.= '
            <div class="col-md-4 mb-2">';
            if($nimg[0]!='0')
               $ibid.= '<a href="'.$ThisFile.'&amp;op=gal&amp;galid='.$row[0].'"><i class="fa fa-folder fa-2x align-middle me-2"></i>'.stripslashes($row[1]).'</a> <span class="badge bg-success rounded-pill" title="'.gal_translate("Nombre d'images").'" data-bs-toggle="tooltip" data-bs-placement="right">'.$nimg[0].'</span>
               <br /><span class="small">'.gal_translate("Créée le").' '.date(translate("dateinternal"),$row[2]).'</span>';
            else 
               $ibid.= '<span class="text-muted"><i class="far fa-folder fa-2x align-middle me-2"></i>'.stripslashes($row[1]).'<br /><span class="small">'.gal_translate("Créée le").' '.date(translate("dateinternal"),$row[2]).'</span></span>';
            $ibid.= '
            </div>';
         }
      }
      if ($ibid!='')
         echo '
         <div class="card-body">
            <h4>'.gal_translate("Galeries").'<span class="float-end badge bg-secondary rounded-pill">'.$nb_gal.'</span></h4>
            <hr />
            <div class="row">'.$ibid.'</div>
         </div>';
   }
}

function ViewGal($galid, $page){
   global $NPDS_Prefix, $ModPath, $imgpage, $MaxSizeThumb, $aff_comm, $aff_vote, $galid, $pos,$pid;
   $ThisFile = "modules.php?ModPath=$ModPath&amp;ModStart=gal";
   settype($galid,'integer');
   settype($page,'integer');
   $num=0;
   $gal = sql_fetch_row(sql_query("SELECT acces FROM ".$NPDS_Prefix."tdgal_gal WHERE id='".$galid."'"));
   if (autorisation($gal[0]))
      $num = sql_num_rows(sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='".$galid."' AND noaff='0'"));
   if ($num == 0)
      echo '
      <div class="alert alert-danger">'.gal_translate("Aucune image trouvée").'</div>';
   else {
      $start = ($page - 1) * $imgpage;
      $query = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='".$galid."' AND noaff='0' ORDER BY ordre,id LIMIT ".$start.",".$imgpage."");
      $pos = $start;
      $nbPages = ceil($num/$imgpage);
      $current = 1;
      if ($page >= 1)
         $current=$page;
      else if ($page < 1)
         $current=1;
      else
         $current = $nbPages;
      echo '
      <div class="row g-2 mt-2 gridphot">';
      $img_point='';
      while ($row = sql_fetch_row($query)) {
         $img_geotag='';
         $nbcom = sql_num_rows(sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_com WHERE pic_id='".$row[0]."'"));
         $nbvote = sql_num_rows(sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_vot WHERE pic_id='".$row[0]."'"));
         if (@file_exists("modules/$ModPath/imgs/".$row[2])) {
            list($width, $height, $type, $attr) = @getimagesize("modules/$ModPath/imgs/$row[2]");
            $ibid = '<img class="img-fluid card-img-top tooltipbyclass" src="modules/'.$ModPath.'/mini/'.$row[2].'" alt="'.stripslashes($row[3]).'" '.$attr.' title="'.$row[2].'<br />'.stripslashes($row[3]).'" data-bs-html="true" data-bs-placement="bottom" loading="lazy" />';
         } else
           $ibid = ReducePic($row[2],stripslashes($row[3]),$MaxSizeThumb);
      //==> geoloc
      if (($row[7] != '') or ($row[8] != '')) {
         $desc = trim(preg_replace('/\s\s+/', ' ', $row[3]));
         $img_point .= 'img_features.push([['.str_replace(",",".",$row[8]).','.str_replace(",",".",$row[7]).'], "'.$row[0].'", "'.$row[1].'", "'.addslashes($row[2]).'","'.addslashes($desc).'","'.$row[4].'"]);';
         $img_geotag = '<img class="geotag tooltipbyclass" src="/modules/'.$ModPath.'/data/geotag_16.png" title="'.gal_translate("Image géoréférencée").'" alt="'.gal_translate("Image géoréférencée").'" loading="lazy" />';
      }
      //<== geoloc
        echo '
         <div class="griditem col-12 col-sm-6 col-lg-4 col-xl-3" >
            <div class="card">
               <a href="'.$ThisFile.'&amp;op=img&amp;galid='.$galid.'&amp;pos='.$pos.'">'.$ibid.'</a>
               '.$img_geotag.'
               <div class="card-body">
                  <p class="card-text text-muted"><span class="badge bg-secondary me-1">'.$row[4].'</span>'.gal_translate("affichage(s)");
        if ($aff_comm and $nbcom>0)
           echo '<br /><span class="badge bg-secondary me-1">'.$nbcom.'</span>'.gal_translate("commentaire(s)");
        if ($aff_vote and $nbvote>0)
           echo '<br /><span class="badge bg-secondary me-1">'.$nbvote.'</span>'.gal_translate("vote(s)");
        echo '
                  </p>
               </div>
            </div>
         </div>';
        $pos++;
      }
      echo '
      </div>
      <div class="modal fade carou wrapper mx-auto" tabindex="-1" role="dialog" aria-hidden="true" >
         <div class="modal-dialog modal-lg">
            <div class="modal-content">';
            ViewDiapo($galid, $pos, $pid);
      echo '
            </div>
         </div>
      </div>';
      echo '
      <nav class="d-flex my-2 mx-2 justify-content-between flex-wrap border-top pt-2">
         <div>
            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target=".carou">'.gal_translate("Diaporama").'</button>
         </div>
         <div>';
      echo paginate_single('modules.php?ModPath='.$ModPath.'&amp;ModStart=gal&amp;op=gal&amp;galid='.$galid.'&amp;page=', '', $nbPages, $current, $adj=3,'',$page);
      echo '
         </div>
      </nav>
      <div class="mx-2">';
      echo meta_lang('comment_system(galerie,'.$galid.')');
      echo '</div>';

      if($img_point !='')
         Img_carte($img_point);
   }
}

function watermark() {
   global $nuke_url;
   echo '
   <script type="text/javascript">
   //<![CDATA[
      $(function() {
         $(".img_awesome").watermark({
          text: "'.$nuke_url.'",
          textWidth: 180,
          gravity: "se",
          opacity: 1,
          margin: 12
         });
      })
   //]]>
   </script>';
}

function ViewImg($galid, $pos, $interface) {
   global $NPDS_Prefix, $ModPath, $user, $vote_anon, $comm_anon, $post_anon, $aff_vote, $aff_comm, $admin, $pid;
   $ThisFile = "modules.php?ModPath=$ModPath&amp;ModStart=gal";

   settype($galid,'integer');
   settype($pos,'integer');
   if ($admin) $no_aff=''; else $no_aff="AND noaff='0'";
   $gal = sql_fetch_row(sql_query("SELECT acces FROM ".$NPDS_Prefix."tdgal_gal WHERE id='".$galid."'"));
   if (autorisation($gal[0])) {
      $num = sql_num_rows(sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='".$galid."' $no_aff"));
      if ($interface!="no")
         $row = sql_fetch_row(sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='".$galid."' $no_aff ORDER BY ordre,id LIMIT $pos,1"));
      else
         $row = sql_fetch_row(sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_img WHERE id='".$pos."' AND gal_id='".$galid."' $no_aff"));
      list($width, $height, $type) = @getimagesize("modules/$ModPath/imgs/$row[2]");
      echo watermark().'
   <img class="card-img-top mx-auto img-fluid img_awesome" src="modules/'.$ModPath.'/imgs/'.$row[2].'" alt="'.stripslashes($row[3]).'" />';
      if($row[3]!='')
         echo '
   <p class="border-bottom p-2 mb-0">'.stripslashes($row[3]).'</p>';
      echo '
   <div class="card-body">';
      if ($interface!='no') {
         echo '
         <ul class="nav justify-content-center">';
         if ($pos > 0)
            echo '
            <li class="nav-item">
               <a class="nav-link" href="'.$ThisFile.'&amp;op=img&amp;galid='.$galid.'&amp;pos='.($pos-1).'"><i class="fa fa-chevron-left fa-2x"></i></a>
            </li>';
         if ($pos < ($num-1))
         echo '
            <li class="nav-item">
               <a class="nav-link" href="'.$ThisFile.'&amp;op=img&amp;galid='.$galid.'&amp;pos='.($pos+1).'"><i class="fa fa-chevron-right fa-2x"></i></a>
            </li>';
         if (isset($user) || $post_anon)
         echo '
            <li class="nav-item">
               <a class="nav-link" href="'.$ThisFile.'&amp;op=ecard&amp;galid='.$galid.'&amp;pos='.$pos.'&amp;pid='.$row[0].'" title="'.gal_translate("Envoyer comme e-carte").'" data-bs-toggle="tooltip" data-bs-placement="right"><i class="fa fa-at fa-2x"></i></a>
            </li>';
         echo '
      </ul>';
      }
      $update = sql_query("UPDATE ".$NPDS_Prefix."tdgal_img SET view = view + 1 WHERE id='".$row[0]."'");

      if ($interface!='no') {
         if ($aff_vote) {
            // Notation de l'image
            if (isset($user) || $vote_anon) {
               echo '
      <div class="text-end mb-2">'.gal_translate("Noter cette image").'</div>
      <div class="my-2">
         <span class="h5 rating">';
            $i=0;
            while ($i<6) {
               echo '<a class="" href="'.$ThisFile.'&amp;op=vote&amp;value='.(6-$i).'&amp;pic_id='.$row[0].'&amp;gal_id='.$galid.'&amp;pos='.$pos.'" title="'.(6-$i).'/6" data-bs-toggle="tooltip"><i class="far fa-star fa-lg"></i></a>';
               $i++;
            }
         echo '
         </span>
      <br />
      </div>';
            }
         }
      }

// Infos sur l'image
   $tailleo = @filesize("modules/$ModPath/imgs/$row[2]");
   $taille = $tailleo/1000;
   echo '
   <h4 class="card-title mt-3">'.gal_translate("Informations sur l'image").'</h4>
   <ul class="list-group lead">
      <li class="list-group-item d-flex justify-content-between align-items-center">'.gal_translate("Taille du fichier").'<span class="badge bg-secondary">'.$taille.' Ko</span></li>
      <li class="list-group-item d-flex justify-content-between align-items-center">'.gal_translate("Dimensions").'<span class="badge bg-secondary">'.$width.' x '.$height.' Pixels</span></li>';
   if ($aff_vote) {
      $rowV = sql_fetch_row(sql_query("SELECT COUNT(id), AVG(rating) FROM ".$NPDS_Prefix."tdgal_vot WHERE pic_id='".$row[0]."'"));
      $note = round($rowV[1]); $star='';
      if($note) {
         $i=0;
         while($i<$note) {$star.='<i class="fa fa-star mx-1"></i>';$i++;}
      }
      echo '
      <li class="list-group-item d-flex justify-content-between align-items-center">'.gal_translate("Note ").$rowV[0].' '.gal_translate("vote(s)").'<span class="text-success" title="'.$note.'/6" data-bs-toggle="tooltip" data-bs-placement="left">'.$star.'</span></li>';
      }
   echo '
      <li class="list-group-item d-flex justify-content-between align-items-center">'.gal_translate("Affichées").'<span class="badge bg-secondary">'.($row[4] + 1).' '.gal_translate("fois").'</span></li>
   </ul>';

      if ($interface!="no") {
         if ($aff_comm) {
         // Commentaires sur l'image
            $qcomment = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_com WHERE pic_id='".$row[0]."' ORDER BY comtimestamp DESC LIMIT 0,10");
            $num_comm = sql_num_rows($qcomment);
            if (($num_comm > 0) || (isset($user) || $comm_anon)) {
               echo '
      <h4 class="card-title mt-3">'.gal_translate("Commentaire(s)").'</h4>';
               while ($rowC = sql_fetch_row($qcomment)) {
                  echo '
      <div class="card mb-2"><div class="card-header"><strong>'.$rowC[2].'</strong><span class="small float-end">'.gal_translate('Posté le').' '.date(translate("dateinternal"),$rowC[5]).'</span></div>
      <div class="card-body">'.stripslashes($rowC[3]).'</div></div>';
               }

               // Formulaire de post de commentaire
               if (isset($user) || $comm_anon) {
                  echo '
                  <form action="'.$ThisFile.'" method="post" name="PostComment">
                     <input type="hidden" name="op" value="postcomment" />
                     <input type="hidden" name="gal_id" value="'.$galid.'" />
                     <input type="hidden" name="pos" value="'.$pos.'">
                     <input type="hidden" name="pic_id" value="'.$row[0].'" />
                     <fieldset class="mb-3">
                        <label class="col-form-label" for="com">'.gal_translate("Ajoutez votre commentaire").'</label>
                        <textarea class="form-control tin" id="com" name="comm" rows="5"></textarea>
                     </fieldset>';
                  echo aff_editeur('comm', '');
                  echo Q_spambot();
                  echo '
                  <button class="btn btn-primary" type="submit">OK</button>
                  </form>';
               }
            }
         }
      }
     echo '</div>';
   }
}

function ViewDiapo($galid, $pos, $pid) {
   global $NPDS_Prefix, $ThisRedo, $ModPath;
   settype($galid,"integer");
   $gal = sql_fetch_row(sql_query("SELECT acces FROM ".$NPDS_Prefix."tdgal_gal WHERE id='".$galid."'"));
   if (autorisation($gal[0])) {
      echo '
      <style>
         .wrapper {
           width:100%;
         }
         .carousel-item-next, .carousel-item-prev, .carousel-item.active {
             display: block !important;
         }
      </style>
      
      <div class="wrapper">
      <div id="photosIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-wrap="true" data-bs-interval="3000">
         <ol class="carousel-indicators">';
      $i = 0;
      settype($pos,"integer");
      $pic_query = sql_query("SELECT id, name FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='$galid' AND noaff='0'");
      while($picture = sql_fetch_assoc($pic_query)) {
         if($i==0)
            echo '<li data-bs-target="#photosIndicators" data-bs-slide-to="'.$i.'" class="active"></li>';
         else
            echo '<li data-bs-target="#photosIndicators" data-bs-slide-to="'.$i.'"></li>';
         $i++;
      }
      echo '
         </ol>
         <div class="carousel-inner" role="listbox">';
      $i = 0;
      $j = 0;
      settype($pos,'integer');
      $pic_query = sql_query("SELECT id, name, comment FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='$galid' AND noaff='0' ORDER BY ordre ASC");
      while($picture = sql_fetch_assoc($pic_query)) {
         if($i==0)
            echo '
          <div class="carousel-item active">';
         else 
            echo '
          <div class="carousel-item">';
         echo'
             <img id="'.$i.'" class="d-block w-100" src="modules/'.$ModPath.'/imgs/'.$picture['name'].'" />
             <div class="carousel-caption d-none d-md-block">
               <p class="lead">'.stripslashes($picture['comment']).'</p>
             </div>
          </div>';
         $i++;
      }

   echo '</div>
         <a class="carousel-control-prev" href="#photosIndicators" role="button" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
         </a>
         <a class="carousel-control-next" href="#photosIndicators" role="button" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
         </a>
      </div>
      </div>';
   }
}

function PrintFormEcard($galid, $pos, $pid) {
   global $NPDS_Prefix, $ThisRedo, $ModPath, $MaxSizeThumb, $user, $anonymous;
   $ThisFile ='modules.php?ModPath='.$ModPath.'&amp;ModStart=gal';
   settype($galid,'integer');
   $gal = sql_fetch_row(sql_query("SELECT acces FROM ".$NPDS_Prefix."tdgal_gal WHERE id='".$galid."'"));
   if (autorisation($gal[0])) {
      settype($pos,'integer');
      settype($pid,'integer');
      $query = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_img WHERE id='".$pid."' AND noaff='0'");
      $row = sql_fetch_row($query);
      if (@file_exists("modules/$ModPath/mini/".$row[2])) {
         list($width, $height, $type, $attr) = @getimagesize("modules/$ModPath/mini/$row[2]");
         $ibid = '<img class="img-fluid img-thumbnail" src="modules/'.$ModPath.'/mini/'.$row[2].'" alt="'.stripslashes($row[3]).'" '.$attr.' />';
      } else
         $ibid = ReducePic($row[2],stripslashes($row[3]),$MaxSizeThumb);
      $cookie = cookiedecode($user);
      $username = $cookie[1];
      $data_user_mail='';
      $data_user_name='';
      if(isset($username)) { 
         $data_user=get_userdata($username);
         $data_user_mail = $data_user['email'];
         $data_user_name = $username;
      }
      if ($username == '')
         $username = $anonymous;
      echo '
      <div class="card">
         <div class="card-header lead"><a href="'.$ThisFile.'"><i class="fa fa-camera fa-2x align-middle me-2"></i>'.gal_translate("Accueil").'</a></div>
         <div class="card-body">
            <h4 class="">'.gal_translate("Envoyer une E-carte").'. ' .gal_translate("De la part de").' <span class="text-muted">'.$username.'</span></h4>
            <hr />
            <div class="form-group row">
               <label class="col-form-label col-sm-4" for="from_name">'.gal_translate("Image").'</label>
               <div class="col-sm-8">
                  '.$ibid.'
              </div>
            </div>
            <form id="sendecard" action="'.$ThisFile.'" method="post" name="FormCard" >
               <input type="hidden" name="op" value="sendcard" />
               <input type="hidden" name="galid" value="'.$galid.'" />
               <input type="hidden" name="pos" value="'.$pos.'" />
               <input type="hidden" name="pid" value="'.$pid.'" />
               <div class="form-group row">
                  <label class="col-form-label col-sm-4" for="from_name">'.gal_translate("Votre nom").'</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="from_name" name="from_name" placeholder="'.gal_translate("Votre nom").'" value="'.$data_user_name.'" required="required" />
                 </div>
               </div>
               <div class="form-group row">
                  <label class="col-form-label col-sm-4" for="from_mail">'.gal_translate("Votre adresse e-mail").'</label>
                  <div class="col-sm-8">
                     <input type="email" class="form-control" name="from_mail" id="from_mail" placeholder="'.gal_translate("Votre adresse e-mail").'" value="'.$data_user_mail.'" required="required" />
                  </div>
               </div>
               <div class="form-group row">
                  <label class="col-form-label col-sm-4" for="to_name">'.gal_translate("Nom du destinataire").'</label>
                  <div class="col-sm-8">
                     <input type="text" class="form-control" name="to_name" id="to_name" placeholder="'.gal_translate("Nom du destinataire").'" required="required" />
                  </div>
               </div>
               <div class="form-group row">
                  <label class="col-form-label col-sm-4" for="to_mail">'.gal_translate("Adresse e-mail du destinataire").'</label>
                  <div class="col-sm-8">
                     <input type="email" class="form-control" name="to_mail" id="to_mail" placeholder="'.gal_translate("Adresse e-mail du destinataire").'" required="required" />
                  </div>
               </div>
               <div class="form-group row">
                  <label class="col-form-label col-sm-4" for="card_sujet">'.gal_translate("Sujet").'</label>
                  <div class="col-sm-8">
                     <input type="text" class="form-control" name="card_sujet" id="card_sujet" placeholder="'.gal_translate("Sujet").'" required="required" />
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-form-label" for="card_msg">'.gal_translate("Message").'</label>
                  <textarea class="form-control tin " name="card_msg" id="card_msg" rows="5" ></textarea>
               </div>';
      aff_editeur("card_msg","true");
      echo Q_spambot();
      echo '
               <button class="btn btn-primary" type="submit">'.gal_translate("Envoyer une E-carte").'</button>
            </form>
         </div>
      </div>';
      $arg1 = '
   var formulid = ["sendecard"]';
   adminfoot('fv','',$arg1,'1');
   }
}

function PostEcard($galid, $pos, $pid, $from_name, $from_mail, $to_name, $to_mail, $card_sujet, $card_msg) {
   global $NPDS_Prefix, $ThisRedo, $nuke_url, $sitename, $adminmail, $mail_fonction, $ModPath, $asb_question, $asb_reponse;
      //anti_spambot - begin
   if (!R_spambot($asb_question, $asb_reponse)) {
      Ecr_Log('security', "Module Anti-Spam : module=npds_galerie / url=".$url, '');
      redirect_url($nuke_url."/modules.php?ModPath=npds_galerie&ModStart=gal");
      die();
   }
   //anti_spambot - end  
   $from_name = removehack(stripslashes(FixQuotes($from_name)));
   $from_mail = removehack(stripslashes(FixQuotes($from_mail)));
   
   if (!validate_email($to_mail))
      $error = "01";
   else {
      $to_name = removehack(stripslashes(FixQuotes($to_name)));
      if (empty($to_name))
         $error = "02";
      else {
         $to_mail = removehack(stripslashes(FixQuotes($to_mail)));
         if (!validate_email($to_mail))
            $error = "03";
         else {
            $card_sujet = removehack(stripslashes($card_sujet));
            if (empty($card_sujet))
               $error = "04";
            else {
               $card_msg = removehack(stripslashes($card_msg));
               if (empty($card_msg)) $error = "05";
            }
         }
      }
   }
   if (empty($error)) {
      $query = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_img WHERE id='".$pid."' and noaff='0'");
      $row = sql_fetch_row($query);
      $fichier_img = "modules/$ModPath/imgs/$row[2]";
      $data = array(
        'rn' => $to_name,
        'sn' => $from_name,
        'se' => $from_mail,
        'pf' => $fichier_img,
        'su' => $card_sujet,
        'ms' => $card_msg,
      );
      $coded_data = urlencode(base64_encode(serialize($data)));
      $message = '<!DOCTYPE html>';
      $message.= '<html>';
      $message.= '<head>';
      $message.= '<meta charset="utf-8" />';
      $message.= '<title>'.gal_translate("Une e-carte pour vous").'</title>';
      $message.= '<meta http-equiv="content-type" content="text/html" />';
      $message.= '<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />';
      $message.= '<meta http-equiv="x-ua-compatible" content="ie=edge" />';
      $message.= '<meta http-equiv="content-script-type" content="text/javascript" />';
      $message.= '<meta http-equiv="content-style-type" content="text/css" />';
      $message.= '<meta http-equiv="expires" content="0" />';
      $message.= '<meta http-equiv="pragma" content="no-cache" />';
      $message.= '<meta http-equiv="cache-control" content="no-cache" />';
      $message.= '<meta http-equiv="identifier-url" content="" />';
      $message.= '</head>';
      $message.= '<body>';
      $message.= '<br />';
      $message.= '<p align="center"><a href="'.$nuke_url.'/modules.php?ModPath='.$ModPath.'&amp;ModStart=gal_viewcard&amp;data='.$coded_data.'">';
      $message.= '<b>'.gal_translate("Si votre e-carte ne s'affiche pas correctement, cliquez ici").'</b></a></p>';
      $message.= '<table border="0" cellspacing="0" cellpadding="1" align="center">';
      $message.= '<tr><td bgcolor="#000000">';
      $message.= '<table border="0" cellspacing="0" cellpadding="10" bgcolor="#ffffff">';
      $message.= '<tr><td valign="top">';
      list($width, $height, $type, $attr) = @getimagesize($fichier_img);
      $message.= '<img class="img-fluid" src="'.$nuke_url.'/'.$fichier_img.'" border="1" alt="'.$row[3].'" '.$attr.' /><br />';
      $message.= '</td><td valign="top" width="200" height="250">';
      $message.= '<br />';
      $message.= '<b><font face="arial" color="#000000" size="4">'.$card_sujet.'</font></b>';
      $message.= '<br /><br /><font face="arial" color="#000000" size="2">'.$card_msg.'</font>';
      $message.= '<br /><br /><font face="arial" color="#000000" size="2">'.$from_name.'</font>';
      $message.= '(<a href="mailto:'.$from_mail.'"><font face="arial" color="#000000" size="2">'.$from_mail.'</font></a>)';
      $message.= '</td></tr></table></td></tr></table>';
      $message.= '</body></html>';
      $message = preg_replace("/(?<!\r)\n/si", "\r\n", $message);
      $extra_headers = "Sender: $sitename <$adminmail>\n" . "From: $from_name <$from_mail>\n";
      $extra_headers.= "Reply-To: $from_name <$from_mail>\n" . "MIME-Version: 1.0\n";
      $extra_headers.= "Content-type: text/html; charset=utf-8\n" . "Content-transfer-encoding: 8bit\n";
      $extra_headers.= "Date: " . gmdate('D, d M Y H:i:s', time()) . " UT\n" ."X-Priority: 3 (Normal)\n";
      $extra_headers.= "X-MSMail-Priority: Normal\n" . "X-Mailer: TD-Galerie\n" ."Importance: Normal";
      if (($mail_fonction==1) or ($mail_fonction==''))
         $result = mail($to_mail, $card_sujet, $message, $extra_headers);
      else {
         $pos = strpos($adminmail, "@");
         $tomail=substr($adminmail,0,$pos);
         $result=email($tomail, $to_mail, $card_sujet, $message, $tomail, $extra_headers);
      }
   }
   if (!empty($error) || !$result )
      echo '
      <p class="card-text alert alert-danger" role="alert"><i class="fa fa-info-circle me-2" aria-hidden="true"></i>'.gal_translate("Erreur").'<br />';
   else
      echo '
      <p class="card-text alert alert-success" role="alert"><i class="fa fa-info-circle me-2" aria-hidden="true"></i>'.gal_translate("Résultat").'<br />';
   if (!empty($error)) {
      if ($error == "01") echo gal_translate("Votre adresse mail est incorrecte.").'<br />';
      if ($error == "02") echo gal_translate("Le nom du destinataire ne peut être vide.").'<br />';
      if ($error == "03") echo gal_translate("L'adresse mail du destinataire est incorrecte.").'<br />';
      if ($error == "04") echo gal_translate("Le sujet ne peut être vide.").'<br />';
      if ($error == "05") echo gal_translate("Le message ne peut être vide.").'<br />';
   }
   if ($result) echo gal_translate("Votre E-Carte a été envoyée");
   if (!$result) echo gal_translate("Votre E-carte n'a pas été envoyée");
   echo '
      </p>
      <script  type="text/javascript">
      //<![CDATA[
         function redirect() {
            window.location="'.$ThisRedo.'&op=img&galid='.$galid.'&pos='.$pos.'"
         }
         setTimeout("redirect()",5000);
      //]]>
      </script>';
}

function PostComment($gal_id, $pos, $pic_id, $comm) {
   global $NPDS_Prefix, $ThisRedo, $gmt, $user, $anonymous, $nuke_url, $asb_question, $asb_reponse;
   //anti_spambot - begin
   if (!R_spambot($asb_question, $asb_reponse)) {
      Ecr_Log("security", "Module Anti-Spam : module=npds_galerie / url=".$url, '');
      redirect_url($nuke_url."/modules.php?ModPath=npds_galerie&ModStart=gal");
      die();
   }
   //anti_spambot - end
   $host = getip();
   settype($gal_id,"integer");
   settype($pos,"integer");
   settype($pic_id,"integer");
   $cookie = cookiedecode($user);
   $name = $cookie[1];
   if ($name == '') $name = $anonymous;
   $comment = removeHack($comm);
   $comment = dataimagetofileurl($comment,'modules/upload/upload/gal');
   $qverif = sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_com WHERE pic_id='$pic_id' AND user='$name' AND comhostname='$host'");
   if (sql_num_rows($qverif) == 0) {
      $stamp = time()+((integer)$gmt*3600);
      sql_query("INSERT INTO ".$NPDS_Prefix."tdgal_com VALUES('',$pic_id,'$name','$comment','$host','$stamp')");
      redirect_url($ThisRedo."&op=img&galid=$gal_id&pos=$pos");
   } else {
      echo '
      <div class="card-body">
         <div class="lead alert alert-danger"><i class="fa fa-info-circle me-2" aria-hidden="true"></i>'.gal_translate("Vous avez déjà commenté cette photo").'</div>
      </div>
      <script  type="text/javascript">
         //<![CDATA[
         function redirect() {
            window.location="'.$ThisRedo.'&op=img&galid='.$gal_id.'&pos='.$pos.'"
         }
         setTimeout("redirect()",4000);
         //]]>
      </script>';
   }
}

function PostVote($gal_id, $pos, $pic_id, $value) {
   global $NPDS_Prefix, $ThisRedo, $gmt, $user, $anonymous;
   $cookie = cookiedecode($user);
   $name = $cookie[1];
   if ($name == '') $name = $anonymous;
   $host = getip();
   settype($gal_id,'integer');
   settype($pos,'integer');
   settype($pic_id,'integer');
   settype($value,'integer');
   if($value==0 or $value>6) die('<div class="alert alert-danger">'.$host.' Merci d\'utiliser l\'interface !</div>');
   $picverif = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_img WHERE id='$pic_id'");
   $qverif = sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_vot WHERE pic_id='$pic_id' AND user='$name' AND ratinghostname='$host'");
   if ((sql_num_rows($qverif) == 0) and (sql_num_rows($picverif) !='')) {
      $stamp = time()+((integer)$gmt*3600);
      sql_query("INSERT INTO ".$NPDS_Prefix."tdgal_vot VALUES('','$pic_id','$name','$value','$host','$stamp')");
      redirect_url($ThisRedo."&op=img&galid=$gal_id&pos=$pos");
   } else {
      echo '
      <div class="alert alert-danger">
         <i class="fa fa-info-circle me-2" aria-hidden="true"></i>'.gal_translate("Vous avez déjà noté cette photo").'
      </div>
      <script type="text/javascript">
      //<![CDATA[
         function redirect() {
            window.location="'.$ThisRedo.'&op=img&galid='.$gal_id.'&pos='.$pos.'"
         }
         setTimeout("redirect()",2000);
      //]]>
      </script>';
  }
}

function ViewAlea() {
   global $NPDS_Prefix, $ModPath, $ThisFile, $imgpage, $MaxSizeThumb, $aff_comm;
   $tab_groupe=autorisation_local();
   // Fabrication de la requête 1
   $where1='';
   $count = count($tab_groupe); $i = 0;
   foreach($tab_groupe as $X => $val) {
      $where1.= "(acces='$val')";
      $i++;
      if ($i < $count) $where1.= ' OR ';
   }
   $query = sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_gal WHERE $where1");

   // Fabrication de la requête 2
   $where2='';
   $count = sql_num_rows($query); $i = 0;
   while ($row = sql_fetch_row($query)) {
      $where2.= "gal_id='$row[0]'";
      $i++;
      if ($i < $count) $where2.= ' OR ';
   }
   $query = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_img WHERE noaff='0' AND ($where2) ORDER BY RAND() LIMIT 0,$imgpage");
   $img_point='';
   // Affichage
   echo '
   <h4 class="card-header border-0">'.gal_translate("Photos aléatoires").'</h4>
   <div class="row g-2 mt-2 griditem" data-masonry=\'{"columnWidth": 0, "itemSelector": ".griditem" }\'>';
   $pos='0';
   while ($row = sql_fetch_row($query)) {
      $img_geotag='';
      //==> geoloc
      if (($row[7] != '') or ($row[8] != '')) {
         $desc = trim(preg_replace('/\s\s+/', ' ', $row[3]));
         $img_point .= 'img_features.push([['.str_replace(",",".",$row[8]).','.str_replace(",",".",$row[7]).'], "'.$row[0].'", "'.$row[1].'", "'.addslashes($row[2]).'","'.addslashes($desc).'","'.$row[4].'"]);';
         $img_geotag = '<img class="geotag tooltipbyclass" src="/modules/'.$ModPath.'/data/geotag_16.png" title="'.gal_translate("Image géoréférencée").'" alt="'.gal_translate("Image géoréférencée").'" loading="lazy" />';
      }
      //<== geoloc
      $nbcom = sql_num_rows(sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_com WHERE pic_id='".$row[0]."'"));
      if (file_exists("modules/$ModPath/imgs/".$row[2])) {
         list($width, $height, $type, $attr) = @getimagesize("modules/$ModPath/imgs/$row[2]");
         $ibid = '<img class="img-fluid card-img-top tooltipbyclass" src="modules/'.$ModPath.'/mini/'.$row[2].'" alt="'.stripslashes($row[3]).'" '.$attr.' title="'.$row[2].'<br />'.stripslashes($row[3]).'" data-bs-html="true" data-bs-placement="bottom" loading="lazy" />';
      } else
         $ibid = ReducePic($row[2],stripslashes($row[3]),$MaxSizeThumb);
      echo '
      <div class="griditem col-12 col-md-6 col-lg-4 col-xl-3">
         <div class="card">
            '.$img_geotag.'
            <a href="'.$ThisFile.'&amp;op=img&amp;galid='.$row[1].'&amp;pos=-'.$row[0].'">'.$ibid.'</a>
            <div class="card-body">
               <p class="card-text text-muted"><span class="badge bg-secondary me-1">'.$row[4].'</span>'.gal_translate("affichage(s)");
      if ($aff_comm and $nbcom>0)
         echo '<br /><span class="badge bg-secondary me-1">'.$nbcom.'</span>'.gal_translate("commentaire(s)");
      echo '
               </p>
            </div>
         </div>
      </div>';
      $pos++;
   }
   echo '
   </div>';
   if($img_point !=''){
      echo '<div class=""><i class="fa fa-globe"></i></div>';
      Img_carte($img_point);
   }
}

function ViewLastAdd() {
   global $NPDS_Prefix, $ModPath, $ThisFile, $imgpage, $MaxSizeThumb, $aff_comm;
   // Fabrication de la requête 1
   $where1='';
   $tab_groupe=autorisation_local();
   $count = count($tab_groupe); $i = 0;
   foreach($tab_groupe as $X => $val) {
      $where1.= "(acces='$val')";
      $i++;
      if ($i < $count) $where1.= ' OR ';
   }
   $query = sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_gal WHERE $where1");

   // Fabrication de la requête 2
   $where2='';
   $count = sql_num_rows($query); $i = 0;
   while ($row = sql_fetch_row($query)) {
      $where2.= "gal_id='$row[0]'";
      $i++;
      if ($i < $count) $where2.= ' OR ';
   }
   $query = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_img WHERE noaff='0' AND ($where2) ORDER BY ordre,id DESC LIMIT 0,$imgpage");
   // Affichage
   $pos = 0;

   echo '
   <h4 class="card-header border-0">'.gal_translate("Derniers ajouts").'</h4>
   <div class="row g-2 mt-2 gridphot">';
   while ($row = sql_fetch_row($query)) {
      $nbcom = sql_num_rows(sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_com WHERE pic_id='".$row[0]."'"));
      if (file_exists("modules/$ModPath/imgs/".$row[2])) {
         list($width, $height, $type, $attr) = @getimagesize("modules/$ModPath/imgs/$row[2]");
         $ibid = '<img class="img-fluid card-img-top tooltipbyclass" src="modules/'.$ModPath.'/imgs/'.$row[2].'" alt="'.stripslashes($row[3]).' '.$attr.'" title="'.$row[2].'<br />'.stripslashes($row[3]).'" data-bs-html="true" data-bs-placement="bottom" loading="lazy" />';
      } else
         $ibid = ReducePic($row[2],stripslashes($row[3]),$MaxSizeThumb);
      $img_geotag='';
      //==> geoloc
      if (($row[7] != '') or ($row[8] != '')) {
         $img_geotag = '<img class="geotag tooltipbyclass" src="/modules/'.$ModPath.'/data/geotag_16.png"  title="'.gal_translate("Image géoréférencée").'" alt="'.gal_translate("Image géoréférencée").'" loading="lazy" />';
      }
      //<== geoloc
      echo '
      <div class="griditem col-12 col-md-6 col-lg-4 col-xl-3">
         <div class="card">
            '.$img_geotag.'
            <a href="'.$ThisFile.'&amp;op=img&amp;galid='.$row[1].'&amp;pos=-'.$row[0].'">'.$ibid.'</a>
            <div class="card-body">
               <p class="card-text text-muted"><span class="badge bg-secondary me-1">'.$row[4].'</span>'.gal_translate("affichage(s)");
      if ($aff_comm and $nbcom>0)
         echo '<br /><span class="badge bg-secondary me-1">'.$nbcom.'</span>'.gal_translate("commentaire(s)");
      echo '
               </p>
            </div>
         </div>
      </div>';
      $pos++;
   }
   echo '
   </div>';
}

function autorisation_local() {
   global $user, $admin;
   if ($user) {
      $groupe = valid_group($user);
      $groupe[] = 1;
   }
   if ($admin)
      $groupe[] = -127;
   $groupe[] = 0;
   return ($groupe);
}

function GetPos($galid, $pos) {
   global $NPDS_Prefix;
   settype($galid,'integer');
   settype($pos,'integer');
   // Trouve l'ID
   $id = -$pos;
   $query = sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='$galid' AND noaff='0' ORDER BY ordre,id");
   $i = 0;
   // Boucle déterminant la position de l'image dans son album
   while($row = sql_fetch_row($query)) {
      if ($row[0] == $id) return $i; else $i++;
   }
}

// SOUS-FONCTIONS
function GetGalArbo($galid) {
   global $NPDS_Prefix, $ModPath;
   $ThisFile = "modules.php?ModPath=$ModPath&amp;ModStart=gal";
   settype($galid,'integer');
   $temp = sql_fetch_row(sql_query("SELECT cid FROM ".$NPDS_Prefix."tdgal_gal WHERE id='".$galid."'"));
   $query = sql_query("SELECT cid,nom FROM ".$NPDS_Prefix."tdgal_cat WHERE id='".$temp[0]."'");
   $row = sql_fetch_row($query);
   if ($row[0] == 0)
      $retour = '
      <li class="breadcrumb-item"><a href="'.$ThisFile.'&amp;op=cat&amp;catid='.$temp[0].'">'.stripslashes($row[1]).'</a></li>';
   else {
      $queryX = sql_query("SELECT nom FROM ".$NPDS_Prefix."tdgal_cat WHERE id='".$row[0]."'");
      $rowX = sql_fetch_row($queryX);
      $retour = '
      <li class="breadcrumb-item"><a href="'.$ThisFile.'&amp;op=cat&amp;catid='.$row[0].'">'.stripslashes($rowX[0]).'</a></li>
      <li class="breadcrumb-item"><a href="'.$ThisFile.'&amp;op=sscat&amp;catid='.$row[0].'&amp;sscid='.$temp[0].'">'.stripslashes($row[1]).'</a></li>';
   }
   return $retour;
}

//Fonction de reduction de la taille d'une image
function ReducePic($image, $comment, $Max) {
   global $ModPath;

   $image = "modules/$ModPath/imgs/".$image;
   $taille = @getimagesize("$image");
   $h_i = $taille[1];
   $w_i = $taille[0];

   if (($h_i > $Max) || ($w_i > $Max)) {
      if ($h_i > $w_i) {
         $convert = $Max/$h_i;
         $h_i = $Max;
         $w_i = ceil($w_i*$convert);
      } else {
         $convert = $Max/$w_i;
         $w_i = $Max;
         $h_i = ceil($h_i*$convert);
      }
   }
   return '<img class="img-fluid" src="'.$image.'" height="'.$h_i.'" width="'.$w_i.'" alt="'.$comment.'" loading="lazy" />';
}

function validate_email($email) {
   if (!preg_match('#^[_\.0-9a-z-]+@[0-9a-z-]+\.+[a-z]{2,4}$#i',$email))
      return false;
   return true;
}

function TopCV($typeOP, $nbtop) {
   global $ThisFile, $ModPath, $NPDS_Prefix;
   settype($nbtop,'integer');
   echo '
   <div class="card">
      <div class="card-header lead">
         <a href="modules.php?ModPath='.$ModPath.'&amp;ModStart=gal"><i class="fa fa-camera fa-2x align-middle me-2"></i>'.gal_translate("Accueil").'</a>
      </div>
      <div class="card-body">
         <h5 class="card-title">';
    echo $typeOP=='comment'?
      gal_translate("Top").' '.$nbtop.' '.gal_translate("des images les plus commentées").'</h5>':
      gal_translate("Top").' '.$nbtop.' '.gal_translate("des images les plus notées").'</h5>';

   $TableRep=sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_img WHERE noaff='0'");
   $NombreEntrees=sql_num_rows($TableRep);
   $TableRep1=sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_com");
   $NombreComs=sql_num_rows($TableRep1);
   $TableRep2=sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_vot");
   $NombreComs1=sql_num_rows($TableRep2);
   echo '
         <hr />
         <ul class="list-group mb-3 lead">
            <li class="list-group-item d-flex justify-content-between align-items-center">'.gal_translate("Nombre d'images").'<span class="badge bg-secondary rounded-pill">'.$NombreEntrees.'</span></li>
            <li class="list-group-item d-flex justify-content-between align-items-center">'.gal_translate("Nombre de commentaires").'<span class="badge bg-secondary rounded-pill">'.$NombreComs.'</span></li>
            <li class="list-group-item d-flex justify-content-between align-items-center">'.gal_translate("Nombre de notes").'<span class="badge bg-secondary rounded-pill">'.$NombreComs1.'</span></li>
         </ul>';

   $result1 = $typeOP=='comment' ?
      sql_query("SELECT pic_id, count(user) AS pic_nbcom FROM ".$NPDS_Prefix."tdgal_com GROUP BY pic_id ORDER BY pic_nbcom DESC LIMIT 0,$nbtop"):
      sql_query("SELECT pic_id, count(user) AS pic_nbvote FROM ".$NPDS_Prefix."tdgal_vot GROUP BY pic_id ORDER BY pic_nbvote DESC LIMIT 0,$nbtop");
   echo '
      <div class="row g-2 mt-2 gridphot">';
   $j=1;
   while (list($pic_id, $nb) = sql_fetch_row($result1)) {
      $result2=sql_fetch_assoc(sql_query("SELECT gal_id, name, comment FROM ".$NPDS_Prefix."tdgal_img WHERE id='$pic_id' AND noaff='0'"));

      if ($result2) {
         $comm_vignette=StripSlashes($result2['comment']);
         echo '
         <div class="griditem col-12 col-md-6 col-lg-4 col-xl-3">
            <div class="card" title="'.$result2['name'].'" data-bs-toggle="tooltip" data-bs-placement="bottom" >
               <div class="card-body px-0 pt-1 pb-1 text-center">
                  <div class="h2 text-center">
                     <span class="badge bg-success rounded-pill ">'.$j.'</span>
                  </div>
                  <a href="modules.php?ModPath='.$ModPath.'&amp;ModStart=gal&amp;op=img&amp;galid='.($result2['gal_id']).'&amp;pos=-'.$pic_id.'" ><img class="n-irl" src="modules/'.$ModPath.'/mini/'.$result2['name'].'" alt="'.$comm_vignette.'" loading="lazy" /></a>
                  <div class="card-text">
                  <span class="badge bg-secondary rounded-pill" data-bs-toggle="tooltip" data-bs-placement="left"';
         echo $typeOP=='comment'?
                  ' title="'.gal_translate("Nombre de commentaires").'"':
                  ' title="'.gal_translate("Nombre de vote(s)").'"';
         echo ' >'.$nb.'</span>
                  </div>
               </div>
            </div>
         </div>';
      }
      $j++;
   }
   echo '
         </div>
      </div>
   </div>';
   sql_free_result($result1);
}

function GetGalCat($galcid) {
   global $NPDS_Prefix;
   $query = sql_query("SELECT nom,cid FROM ".$NPDS_Prefix."tdgal_cat WHERE id='".$galcid."'");
   $row = sql_fetch_row($query);
   if ($row[1] == 0)
      return stripslashes($row[0]);
   else {
      $queryX = sql_query("SELECT nom FROM ".$NPDS_Prefix."tdgal_cat WHERE id='".$row[1]."'");
      $rowX = sql_fetch_row($queryX);
      return stripslashes($rowX[0]).' - '.stripslashes($row[0]);
   }
}

function select_arbo($sel) {
   global $NPDS_Prefix;
   $ibid='<option value="-1">'.gal_translate("Galerie temporaire").'</option>';
   $sqlnoadm ='AND acces >= 0';
   if(autorisation(-127)) $sqlnoadm ='';
   $sql_cat = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='0' ".$sqlnoadm." ORDER BY nom ASC");
   $num_cat = sql_num_rows($sql_cat);
   if ($num_cat != 0) {
      $sql_sscat = "SELECT * FROM ".$NPDS_Prefix."tdgal_cat WHERE cid!=0 ".$sqlnoadm."";
      $sql_gal = "SELECT * FROM ".$NPDS_Prefix."tdgal_gal WHERE 1 ".$sqlnoadm."";
      // CATEGORIE
      while ($row_cat = sql_fetch_row($sql_cat)) {
         $ibid.='<optgroup label="'.stripslashes($row_cat[2]).'">';
         $queryX = sql_query("SELECT id, nom, acces FROM ".$NPDS_Prefix."tdgal_gal WHERE cid='".$row_cat[0]."' ORDER BY nom ASC");
         while ($rowX_gal = sql_fetch_row($queryX)) {
            if ($rowX_gal[0] == $sel) $IsSelected = ' selected="selected" '; else $IsSelected = '';
            switch ($rowX_gal[2]) {
               //case -127: $af=true; break;
              // case 1: $af=$icondroits[1]; break;
               //case 0: $af=true; break;
               case $rowX_gal[2]>1: $af=autorisation($rowX_gal[2]); break;
            }
            if(isset($af))
               $ibid.='<option value="'.$rowX_gal[0].'" '.$IsSelected.'>'.stripslashes($rowX_gal[1]).'</option>';
         } // Fin Galerie Catégorie

         // SOUS-CATEGORIE
         $query = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='".$row_cat[0]."' ".$sqlnoadm." ORDER BY nom ASC");
         while ($row_sscat = sql_fetch_row($query)) {
            $ibid.='<optgroup label="  '.stripslashes($row_sscat[2]).'">';
            $querx = sql_query("SELECT id, nom FROM ".$NPDS_Prefix."tdgal_gal WHERE cid='".$row_sscat[0]."' ".$sqlnoadm." ORDER BY nom ASC");
            while ($row_gal = sql_fetch_row($querx)) {
               if ($row_gal[0] == $sel) { $IsSelected = " selected"; } else { $IsSelected = ""; }
               
               $ibid.='<option value="'.$row_gal[0].'"'.$IsSelected.'>'.stripslashes($row_gal[1]).' </option>';
            } // Fin Galerie Sous Catégorie
            $ibid.='</optgroup>';
         } // Fin Sous Catégorie
         $ibid.='</optgroup>';
      } // Fin Catégorie
   }
   return ($ibid);
}

// Propositions de photos par les membres
function PrintFormImgs() {
   global $ModPath, $ModStart, $NPDS_Prefix, $ThisFile, $ThisRedo, $user;

// Récupération de l'utilisateur connecté pour initialisation du champ user_connecte et transmission à AddImgs
   $userinfo=getusrinfo($user);
   $user_connecte=$userinfo['uname'];

   $qnum = sql_num_rows(sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_cat"));
   if ($qnum == 0)
      redirect_url($ThisRedo);
   echo '
   <div class="card">
      <div class="card-header lead">
         <a href="modules.php?ModPath='.$ModPath.'&amp;ModStart=gal"><i class="fa fa-camera fa-2x align-middle me-2"></i>'.gal_translate("Accueil").'</a>
         <a class="float-end" href=""><i class="fa fa-question fa-lg align-middle me-2"></i></a>
      </div>
      <div class="card-body">
         <h5 class="card-title">'.gal_translate("Proposer des images").'</h5>
         <hr />
         <div class="row g-3">
            <div class="col-md-6">
               <form enctype="multipart/form-data" method="post" action="'.$ThisFile.'" id="formimgs" name="FormImgs" lang="'.language_iso(1,'','').'">
                  <input type="hidden" name="op" value="addimgs" />
                  <input type="hidden" name="user_connecte" value="'.$user_connecte.'" />
                  <div class="form-floating mb-3">
                     <select class="form-select" name="imggal" id="imggal" >';
   echo select_arbo('');
   echo '
                     </select>
                     <label for="imggal">'.gal_translate("Galerie").'</label>
                 </div>';
      $i=1;
      do {
         echo '
                  <div class="mb-2">
                     <label class="fw-bolder">'.gal_translate("Image").' '.$i.'</label>
                     <div class="input-group mb-2 me-sm-2">
                        <button class="btn btn-secondary" onclick="reset2($(\'#newcard'.$i.'\'),'.$i.');"><i class="bi bi-arrow-clockwise"></i></button>
                        <label id="lab'.$i.'" class="input-group-text n-ci" for="newcard'.$i.'"></label>
                        <input type="file" class="form-control custom-file-input" name="newcard'.$i.'" id="newcard'.$i.'" />
                     </div>
                     <div class="mb-3">
                        <label class="sr-only" for="newdesc'.$i.'">'.gal_translate("Description").'</label>
                        <input type="text" class="form-control" id="newdesc'.$i.'"  name="newdesc[]" placeholder="'.gal_translate("Description").'" />
                     </div>
                     <div class="row g-2">
                        <div class="col-md-6 mb-3">
                           <div class="input-group mb-2 me-sm-2">
                              <span class="input-group-text jsgeo'.$i.'" title="'.gal_translate("Latitude").'" data-bs-toggle="tooltip"><i class="fa fa-globe fa-lg"></i></span>
                              <label for="imglat'.$i.'" class="sr-only">'.gal_translate("Latitude").'</label>
                              <input type="text" class="form-control js-lat" name="imglat[]" id="imglat'.$i.'" placeholder="'.gal_translate("Latitude").'" />
                           </div>
                        </div>
                        <div class="col-md-6 mb-3">
                           <div class="input-group mb-2 me-sm-2">
                              <span class="input-group-text jsgeo'.$i.'" title="'.gal_translate("Longitude").'" data-bs-toggle="tooltip"><i class="fa fa-globe fa-lg"></i></span>
                              <label for="imglong'.$i.'" class="sr-only">'.gal_translate("Longitude").'</label>
                              <input type="text" class="form-control js-long" name="imglong[]" id="imglong'.$i.'" placeholder="'.gal_translate("Longitude").'"/>
                           </div>
                        </div>
                     </div>
                  </div>';
      $i++;
      }
      while($i<=5);
   echo '
                  <div class="mb-3">
                     <button class="btn btn-primary" type="submit">'.gal_translate("Envoyer").'</button>
                  </div>
               </form>
            </div>
            <div class="col-md-6 align-self-center">
              '.img_geolocalisation('0','0','1').'
            </div>
         </div>
      </div>
   </div>
   <script type="text/javascript">
      //<![CDATA[
         window.reset2 = function (e,f) {
            e.wrap("<form>").closest("form").get(0).reset();
            e.unwrap();
            event.preventDefault();
         };
      //]]>
   </script>';
   $fv_parametres ='
      "imglat[]" : {
         selector: ".js-lat",
         validators: {
            regexp: {
               regexp: /^[-]?([1-8]?\d(\.\d+)?|90(\.0+)?)$/,
               message: "La latitude doit être entre -90.0 et 90.0 avec un point en séparateur numérique"
            },
            numeric: {
                thousandsSeparator: "",
                decimalSeparator: "."
            },
            between: {
               min: -90,
               max: 90,
               message: "La latitude doit être entre -90.0 et 90.0"
            }
         }
      },
      "imglong[]" : {
         selector: ".js-long",
         validators: {
            regexp: {
               regexp: /^[-]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$/,
               message: "La longitude doit être entre -180.0 et 180.0 avec un point en séparateur numérique"
            },
            numeric: {
                thousandsSeparator: "",
                decimalSeparator: "."
            },
            between: {
               min: -180,
               max: 180,
               message: "La longitude doit être entre -180.0 et 180.0"
            }
         }
      },
   ';
   $arg1 = '
   var formulid = ["formimgs"]
//   inpandfieldlen("newdesc",255);';
   adminfoot('fv',$fv_parametres,$arg1,'1');
}

// Ajout de photos par les membres
function AddImgs($imggal,$newcard1,$newcard2,$newcard3,$newcard4,$newcard5,$newdesc,$imglat,$imglong,$user_connecte) {
   global $language, $MaxSizeImg, $MaxSizeThumb, $ModPath, $ModStart, $NPDS_Prefix, $ThisFile, $adminmail, $nuke_url, $notif_admin;
   include_once("modules/upload/lang/upload.lang-$language.php");
   include_once("modules/upload/clsUpload.php");

   $year = date("Y"); $month = date("m"); $day = date("d");
   $hour = date("H"); $min = date("i"); $sec = date("s");
   echo '
   <div class="card">
      <div class="card-header"><a href="'.$ThisFile.'">'.gal_translate("Accueil").'</a></div>
      <div class="card-body">
         <h5 class="card-title">'.gal_translate("Proposer des images").'</h5>';

   $soumission=false;
   $i=1;
   while($i <= 5) {
      $img = "newcard$i";
      $tit = $newdesc[$i-1].' '.gal_translate(" proposé par ").$user_connecte;
      $lat = $imglat[$i-1];
      $long = $imglong[$i-1];
      if (!empty($$img)) {
         $newimg = stripslashes(removeHack($$img));
         if (!empty($newdesc[$i-1]))
            $newtit = addslashes(removeHack($newdesc[$i-1]));
         else
            $newtit = '';
         $upload = new Upload();
         $upload->maxupload_size=200000*100;
         $origin_filename = trim($upload->getFileName("newcard".$i));
         $filename_ext = strtolower(substr(strrchr($origin_filename, "."),1));

         if ( ($filename_ext=="jpg") or ($filename_ext=="jpeg") or ($filename_ext=="gif") or ($filename_ext=="png") ) {
            $newfilename = $year.$month.$day.$hour.$min.$sec."-".$i.".".$filename_ext;
            if ($upload->saveAs($newfilename,"modules/$ModPath/imgs/", "newcard".$i,true)) {
               if ((function_exists('gd_info')) or extension_loaded('gd')) {
                  @CreateThumb($newfilename, "modules/$ModPath/imgs/", "modules/$ModPath/imgs/", $MaxSizeImg, $filename_ext);
                  @CreateThumb($newfilename, "modules/$ModPath/imgs/", "modules/$ModPath/mini/", $MaxSizeThumb, $filename_ext);
               }
               if (sql_query("INSERT INTO ".$NPDS_Prefix."tdgal_img VALUES ('','$imggal','$newfilename','$newtit','','0','1','$lat','$long')")) {
                  echo '<div class="alert alert-info" role="alert"><i class="fa fa-info-circle me-2" aria-hidden="true"></i>'.gal_translate("Image").' '.gal_translate("envoyée à la validation du webmaster").' : '.$origin_filename.'</div>';
                  $soumission=true;
               } else {
                  echo '<div class="alert alert-danger" role="alert"><i class="fa fa-info-circle me-2" aria-hidden="true"></i>'.gal_translate("Impossible d'ajouter l'image à la BDD").' : '.$origin_filename.'</span></div>';
                  @unlink ("modules/$ModPath/imgs/$newfilename");
                  @unlink ("modules/$ModPath/mini/$newfilename");
               }
            } else {
               echo '<div class="alert alert-danger" role="alert"><i class="fa fa-info-circle me-2" aria-hidden="true"></i>'.$upload->errors.'</span></div>';
            }
         } else {
            if ($filename_ext!="")
               echo '<div class="alert alert-warning" role="alert"><i class="fa fa-info-circle me-2" aria-hidden="true"></i>'.gal_translate("Ce fichier n'est pas un fichier jpg ou gif").' png : '.$origin_filename.'</span></div>';
         }
      }
      $i++;
   }

   if ($notif_admin and $soumission) {
      $subject=gal_translate("Nouvelle soumission de Photos");
      $message=gal_translate("Des photos viennent d'être proposées dans la galerie photo du site ").$nuke_url.gal_translate(" par ").$user_connecte;
      send_email($adminmail, $subject, $message, '', true, 'html');
   }
   echo '
      </div>
   </div>';
}

// CreateThumb($newfilename, "modules/$ModPath/imgs/", "modules/$ModPath/mini/", $MaxSizeThumb, $filename_ext);
function CreateThumb($Image, $Source, $Destination, $Max, $ext) {
   switch ($ext) {
      case (preg_match('/jpeg|jpg/i', $ext) ? true : false) :
         $src=@imagecreatefromjpeg($Source.$Image);
      break;
      case (preg_match('/gif/i', $ext) ? true : false) :
         $src=@imagecreatefromgif($Source.$Image);
      break;
      case (preg_match('/png/i', $ext) ? true : false) :
         $src=@imagecreatefrompng($Source.$Image);
      break;
   }
   if ($src) {
      $size = getimagesize($Source.$Image);
      $h_i = $size[1]; //hauteur
      $w_i = $size[0]; //largeur
      if (($h_i > $Max) || ($w_i > $Max)) {
         if ($h_i > $w_i) {
            $convert = $Max/$h_i;
            $h_i = $Max;
            $w_i = ceil($w_i*$convert);
         } else {
            $convert = $Max/$w_i;
            $w_i = $Max;
            $h_i = ceil($h_i*$convert);
         }
      }
      if (function_exists("imagecreatetruecolor"))
         $im = @imagecreatetruecolor($w_i, $h_i);
      else
         $im = @imagecreate($w_i, $h_i);
      @imagecopyresized($im, $src, 0, 0, 0, 0, $w_i, $h_i, $size[0], $size[1]);
      @imageinterlace ($im,1);
      switch ($ext) {
         case (preg_match('/jpeg|jpg/i', $ext) ? true : false) :
            @imagejpeg($im, $Destination.$Image, 100);
         break;
         case (preg_match('/gif/i', $ext) ? true : false) :
            @imagegif($im, $Destination.$Image);
         break;
         case (preg_match('/png/i', $ext) ? true : false) :
            @imagepng($im, $Destination.$Image, 6);
         break;
      }
      @chmod($Dest.$Image,0766);
   }
}
// cette fonction ne peut être utilisé qu'une fois par page ...

#autodoc Img_carte($img_point) : $img_point est une ou plusieurs commande js (push) de construction du tableau "img_features", cette variable $img_point doit donc être remplie dans la boucle récupérant les données dans la bd. Cette fonction ne peut être utilisée qu'une fois par page généré. 
function Img_carte($img_point){
   global $nuke_url;
   include_once('modules/geoloc/geoloc.conf');
   $cartyp='sat-google'; // choix manuel du provider ready4admin interface
$source_fond=''; $max_r=''; $min_r='';$layer_id='';
switch ($cartyp) {
   case 'Road': case 'Aerial': case 'AerialWithLabels':
      $source_fond='
      new ol.source.BingMaps({
         key: "'.$api_key_bing.'",imagerySet: "'.$cartyp.'"
      })';
      $max_r='40000';
      $min_r='0';
      $layer_id= $cartyp;
   break;
   case 'natural-earth-hypso-bathy': case 'geography-class':
      $source_fond='
      new ol.source.TileJSON({
         url: "https://api.tiles.mapbox.com/v4/mapbox.'.$cartyp.'.json?access_token='.$api_key_mapbox.'",
         attributions: "© <a href=\"https://www.mapbox.com/about/maps/\">Mapbox</a> © <a href=\"http://www.openstreetmap.org/copyright\">OpenStreetMap</a> <strong><a href=\"https://www.mapbox.com/map-feedback/\" target=\"_blank\">Improve this map</a></strong>"
      })';
      $max_r='40000';
      $min_r='2000';
      $layer_id= $cartyp;
   break;
   case 'sat-google':
      $source_fond='
      new ol.source.XYZ({
         url: "https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}",
         crossOrigin: "",
         attributions: " &middot; <a href=\"https://www.google.at/permissions/geoguidelines/attr-guide.html\">Map data ©2015 Google</a>"
      })';
      $max_r='40000';
      $min_r='0';
      $layer_id= $cartyp;
   break;
   case 'terrain':case 'toner':case 'watercolor':
      $source_fond='
      new ol.source.Stamen({
         layer:"'.$cartyp.'"
      })';
      $max_r='40000';
      $min_r='0';
      $layer_id= $cartyp;
   break;
   case 'modisterra':
      $source_fond='
      new ol.source.XYZ({
         url: "https://gibs-{a-c}.earthdata.nasa.gov/wmts/epsg3857/best/MODIS_Terra_CorrectedReflectance_TrueColor/default/2013-06-15/GoogleMapsCompatible_Level13/{z}/{y}/{x}.jpg"
      })';
      $max_r='40000';
      $min_r='0';
      $layer_id= $cartyp;
   break;
   default:
      $source_fond='new ol.source.OSM()';
      $max_r='40000';
      $min_r='0';
      $layer_id= 'OSM';
}

   echo '
   <div class="my-3">
      <div id="map-wrapper" class="ol-fullscreen my-3">
         <div id="mapimg" class="map" tabindex="20" lang="'.language_iso(1,0,0).'">
            <div id="ol_popup" class="ol-popup small"></div>
            <div id="ol_tooltip"></div>
         </div>
         <div id="sidebar" class= "sidebar collapse show col-sm-4 col-md-3 col-6 px-0"></div>
      </div>
   </div>
   <a id="image-download" crossorigin="anonymous" download="npds_galerie_map.png"></a>';
   if(!defined('OL')) {
      define('OL','ol');
      echo '<script type="text/javascript" src="/lib/ol/ol.js"></script>';
   }
echo '
<script type="text/javascript">
   //<![CDATA[
   if (!$("link[href=\'/lib/ol/ol.css\']").length)
      $("head link[rel=\'stylesheet\']").last().after("<link rel=\'stylesheet\' href=\'/lib/ol/ol.css\' type=\'text/css\' media=\'screen\'>");
   $("head link[rel=\'stylesheet\']").last().after("<link rel=\'stylesheet\' href=\'/modules/npds_galerie/css/galerie.css\' type=\'text/css\' media=\'screen\'>");
   $(function () {
      //==>  affichage des coordonnées...
         var mousePositionControl = new ol.control.MousePosition({
            coordinateFormat: function(coord) {return ol.coordinate.toStringHDMS(coord,2)},
            projection: "EPSG:4326",
            className: "custom-mouse-position",
            undefinedHTML: "&nbsp;"
         });
      //<==
      var 
      iconimg = new ol.style.Style({
        image: new ol.style.Icon({
          src: "modules/npds_galerie/npds_galerie.png",
          crossOrigin:"anonymous",
        })
      }),
      popup = new ol.Overlay({
        element: document.getElementById("ol_popup"),
         autoPan: true,
         autoPanAnimation: {
            duration: 1000
         },
         offset : [-0,-14],
      }),
      popuptooltip = new ol.Overlay({
        element: document.getElementById("ol_tooltip")
      }),
      img_features=[];
      '.$img_point.'
      var src_img = new ol.source.Vector({});
      var src_img_length = img_features.length;
      for (var i = 0; i < src_img_length; i++){
         var iconFeature = new ol.Feature({
            geometry: new ol.geom.Point(ol.proj.transform(img_features[i][0], "EPSG:4326","EPSG:3857")),
            imgId: img_features[i][1],
            galId: img_features[i][2],
            imgName: img_features[i][3],
            imgCom: img_features[i][4],
            imgView: img_features[i][5],
         });
            iconFeature.setId(("i"+i));
            src_img.addFeature(iconFeature);
      }
      var img_markers = new ol.layer.Vector({
         id: "imag",
         source: src_img,
         style: iconimg
      });

      var
         src_fond = '.$source_fond.',
         minR='.$min_r.',
         maxR='.$max_r.',
         layer_id="'.$layer_id.'",
         fond_carte = new ol.layer.Tile({
            id:layer_id,
            source: src_fond,
            minResolution: minR,
            maxResolution: maxR
         }),
         attribution = new ol.control.Attribution({collapsible: true}),
         zoomslider = new ol.control.ZoomSlider(),
         fullscreen = new ol.control.FullScreen({source: "map-wrapper"}),
         scaleline = new ol.control.ScaleLine,
         view = new ol.View({
            center: ol.proj.fromLonLat([105, 36]),
            zoom: 8,
            minZoom:2
         });

      var map = new ol.Map({
         interactions: new ol.interaction.defaults({
            constrainResolution: true, onFocusOnly: true
         }),
         controls: new ol.control.defaults({attribution: false}).extend([attribution, fullscreen, mousePositionControl, scaleline, zoomslider]),
         target: document.getElementById("mapimg"),
         layers: [
            fond_carte,
            img_markers
         ],
         view: view
      });
      map.addOverlay(popup);
      map.addOverlay(popuptooltip);

      var extimg = img_markers.getSource().getExtent();
      console.log(extimg);
      if (src_img_length==1) {
         view.setCenter(src_img.getFeatureById("i0").getGeometry().getFlatCoordinates());
         view.setZoom(4);
      }
      else
         view.fit(extimg);

      var button = document.createElement("button");
      button.innerHTML = "&#xf0d8";
      button.setAttribute("title", "Masquer")
      var sidebarSwitch = function(e) {
         if($("#sidebar").hasClass("show")) {
            $("#sidebar").collapse("toggle");
            button.innerHTML = "&#xf0d7";
            button.setAttribute("data-bs-original-title", "Voir")
         } else{
            $("#sidebar").collapse("show");
            button.innerHTML = "&#xf0d8";
            button.setAttribute("data-bs-original-title", "Masquer")
         }
      };
      button.addEventListener("click", sidebarSwitch, true);
      var element = document.createElement("div");
      element.className = "ol-sidebar ol-unselectable ol-control fa";
      element.appendChild(button);
      var sidebarControl = new ol.control.Control({
          element: element
      });
      map.addControl(sidebarControl);

      var buttond = document.createElement("button");
      buttond.setAttribute("id","export-png");
      buttond.setAttribute("title","'.html_entity_decode(gal_translate("Télécharger comme image.png"),ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset).'");
      buttond.setAttribute("data-bs-toggle","tooltip");
      buttond.setAttribute("data-bs-placement","left");
      buttond.innerHTML = "&#xf019";
      var element = document.createElement("div");
      element.className = "ol-download ol-unselectable ol-control fa";
      element.appendChild(buttond);
      var downloadControl = new ol.control.Control({
          element: element
      });
      map.addControl(downloadControl);


//==> construction sidebar
      var img_feat = src_img.getFeatures(),
          ima_nb = img_feat.length,
          sbimg=\'<div id="sb_img" class="list-group small"><div class="list-group-item bg-light text-dark fw-light px-1 lead"><img class="me-1" src="modules/npds_galerie/npds_galerie.png" alt="" style="vertical-align:middle;" data-bs-toggle="tooltip" title="'.html_entity_decode(gal_translate("Images géoréférencées"),ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset).'" /><span class="badge bg-secondary float-end">\'+ima_nb+\'</span></div><a class="sb_res list-group-item list-group-item-action py-1 px-1" ><input id="n_filtreimages" placeholder="'.html_entity_decode(gal_translate("Filtrer les images"),ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset).'" class="my-1 form-control form-control-sm" type="text" /></a>\';
      for (var key in img_feat) {
         if (img_feat.hasOwnProperty(key)) {
            sbimg += \'<a id="\'+ img_feat[key].V +\'" href="#" onclick="centeronMe(\\\'\'+ img_feat[key].V +\'\\\');return false;" class="sb_img list-group-item list-group-item-action py-1 px-1" href="#"><img class="img-fluid n-ava-48" src="modules/npds_galerie/mini/\' + img_feat[key].A.imgName + \'" loading="lazy" /><span class="ms-1 nlfilt">\' + img_feat[key].A.imgName + \'</span></a>\';
         }
      }
      sbimg +=\'</div>\';
      $("#sidebar").append(sbimg);

//==> comportement sidebar et layers
   $(document).ready(function () {
      centeronMe = function(u) {
         $(".sb_img").removeClass( "animated faa-horizontal faa-slow" );
         if(u.substr(0,1) == "i") {
         var position = src_img.getFeatureById(u).getGeometry().getFlatCoordinates();
            view.setCenter(position);
            $("#ol_popup").show();
            container.innerHTML = \'<div class="text-center"><img class="" src="modules/npds_galerie/mini/\' + src_img.getFeatureById(u).get("imgName") + \'" loading="lazy" /></div>\';
            popup.setPosition(position);
         }
         $("#"+u).addClass("animated faa-horizontal faa-slow" );
         map.getView().setZoom(16);
      }
   });

//==> les fenetres popup pour les markers
  var container = document.getElementById("ol_popup"),
      OpenPopup = function (evt) {
      $("#ol_popup").show();
      popup.setPosition(undefined);
      $(".sb_img").removeClass("animated faa-horizontal faa-slow" );
      var feature = map.forEachFeatureAtPixel(evt.pixel, function (feature, layer) {
         if (feature) {
            var coord = map.getCoordinateFromPixel(evt.pixel);
            if (typeof feature.get("features") === "undefined") {
               if(feature.getId().substr(0,1) == "i") {
                   container.innerHTML = \'<div class="text-center"><img src="modules/npds_galerie/mini/\' + feature.get("imgName") + \'" loading="lazy" /></div><div class="text-muted small mt-1">\' + feature.get("imgCom") + \'</div></div>\';
               }
            }
            popup.setPosition(coord);
         } else {
            popup.setPosition(undefined);
         }
      });
   };
   map.on("click", OpenPopup);
//<== les fenêtres popup pour les markers

//==> les tooltip des markers
var containertooltip = $("#ol_tooltip");
containertooltip.tooltip({
   animation: false,
   container: "#mapimg",
   trigger: "manual",
   placement:"bottom",
   offset:"40,20",
});
var displayFeatureInfo = function(evt) {
   containertooltip.tooltip("hide");
   popuptooltip.setPosition(undefined);
   var feature = map.forEachFeatureAtPixel(evt.pixel, function (feature, layer) {
      if (feature) {
         var coord = map.getCoordinateFromPixel(evt.pixel);
         if (typeof feature.get("features") === "undefined") {
            if(feature.getId().substr(0,1) == "i") {
                containertooltip.attr("data-bs-original-title", feature.get("imgName")).tooltip("show");
            }
         }
         popuptooltip.setPosition(coord);
      } else {
         popuptooltip.setPosition(undefined);
         containertooltip.tooltip("hide");
      }
   });
}
map.on("pointermove", displayFeatureInfo);
//<== les tooltip des markers

//==> changement etat pointeur sur les markers
   map.on("pointermove", function(e) {
        if (e.dragging) {
          $(".popover").popover("dispose");
          return;
        }
        var pixel = map.getEventPixel(e.originalEvent);
        var hit = map.hasFeatureAtPixel(pixel);
        map.getTarget().style.cursor = hit ? "pointer" : "";
      });
//<== changement etat pointeur sur les markers

//==> filtrage des markers dans sidebar
      $(\'.sb_img span\').each(function(){
         $(this).attr(\'data-search-term\', $(this).text().toLowerCase());
      });
      $(\'#n_filtreimages\').on(\'keyup\', function(){
         var searchTerm = $(this).val().toLowerCase();
         $(\'.nlfilt\').each(function(){
            if ($(this).filter(\'[data-search-term *= \' + searchTerm + \']\').length > 0 || searchTerm.length < 1) {
               var ce = $(this).parents("a");
               ce.show();
               if(ce.attr("id").substr(0,1) == "i")
                  src_img.getFeatureById(ce.attr("id")).setStyle(null);
            }
            else {
               var ce = $(this).parents("a");
               ce.hide();
               if(ce.attr("id").substr(0,1) == "i")
                  src_img.getFeatureById(ce.attr("id")).setStyle(new ol.style.Style({}));
            }
          });
      });
//<== filtrage des markers dans sidebar

//==> download carte.png
document.getElementById("export-png").addEventListener("click", function () {
  map.once("rendercomplete", function () {
    const mapCanvas = document.createElement("canvas");
    const size = map.getSize();
    mapCanvas.width = size[0];
    mapCanvas.height = size[1];
    const mapContext = mapCanvas.getContext("2d");
    Array.prototype.forEach.call(
      document.querySelectorAll(".ol-layer canvas"),
      function (canvas) {
        if (canvas.width > 0) {
          const opacity = canvas.parentNode.style.opacity || canvas.style.opacity;
          mapContext.globalAlpha = opacity === "" ? 1 : Number(opacity);
         const backgroundColor = canvas.parentNode.style.backgroundColor;
          if (backgroundColor) {
            mapContext.fillStyle = backgroundColor;
            mapContext.fillRect(0, 0, canvas.width, canvas.height);
          }

          let matrix;
         const transform = canvas.style.transform;
            if(transform) {
               // Get the transform parameters from the style\'s transform matrix
               matrix = transform
               .match(/^matrix\(([^\(]*)\)$/)[1]
               .split(",")
               .map(Number);
            }
            else {
               matrix = [
                 parseFloat(canvas.style.width) / canvas.width,
                 0,
                 0,
                 parseFloat(canvas.style.height) / canvas.height,
                 0,
                 0,
               ];
            }
          // Apply the transform to the export map context
          CanvasRenderingContext2D.prototype.setTransform.apply(
            mapContext,
            matrix
          );
          mapContext.drawImage(canvas, 0, 0);
        }
      }
    );
    mapContext.globalAlpha = 1;

    if (navigator.msSaveBlob) {
      // link download attribute does not work on MS browsers
      navigator.msSaveBlob(mapCanvas.msToBlob(), "npds_galerie_map.png");
    } else {
      const link = document.getElementById("image-download");
      link.href = mapCanvas.toDataURL();
      link.click();
    }
  });
  map.renderSync();
});
//<== download carte.png
';

echo file_get_contents('modules/geoloc/include/ol-dico.js');
echo '
      const targ = map.getTarget();
      const lang = targ.lang;
      for (var i in dic) {
         if (dic.hasOwnProperty(i)) {
            $("#mapimg "+dic[i].cla).prop("title", dic[i][lang]);
         }
      }

      fullscreen.on("enterfullscreen",function(){
         $(dic.olfullscreentrue.cla).attr("data-bs-original-title", dic["olfullscreentrue"][lang]);
      })
      fullscreen.on("leavefullscreen",function(){
         $(dic.olfullscreenfalse.cla).attr("data-bs-original-title", dic["olfullscreenfalse"][lang]);
      })

   $(\'[data-bs-toggle="tooltip"]\').tooltip({container:"#mapimg"});
   $("#ol_tooltip").tooltip({container:"#mapimg"});
   $("#mapimg .ol-zoom-in, #mapimg .ol-zoom-out").tooltip({placement: "right", container:"#mapimg"});
   $("#mapimg .ol-sidebar button[title], #mapimg .ol-full-screen-false, #mapimg .ol-rotate-reset, #mapimg .ol-attribution button[title]").tooltip({placement: "left", container:"#mapimg"});

   });
   //]]>
</script>';
}

function img_geolocalisation($lat,$long,$multi){
   global $nuke_url, $language, $api_key_bing, $api_key_mapbox;
   include('modules/geoloc/lang/geoloc.lang-'.$language.'.php');

   $affi='';
   if($lat=='') $lat=0;
   if($long=='') $long=0;
   $img_point = 'img_features.push([['.$long.','.$lat.']]);';
   $date_jour = date('Y-m-d');
   $fond_provider=array(
   ['OSM', geoloc_translate("Plan").' (OpenStreetMap)'],
   ['sat-google', geoloc_translate("Satellite").' (Google maps)'],
   ['toner', geoloc_translate("Noir et blanc").' (Stamen)'],
   ['watercolor', geoloc_translate("Dessin").' (Stamen)'],
   ['terrain', geoloc_translate("Relief").' (Stamen)'],
   ['modisterra', geoloc_translate("Satellite").' (NASA)'],
   ['natural-earth-hypso-bathy', geoloc_translate("Relief").' (mapbox)'],
   ['geography-class', geoloc_translate("Carte").' (mapbox)'],
   ['Road', geoloc_translate("Plan").' (Bing maps)'],
   ['Aerial', geoloc_translate("Satellite").' (Bing maps)'],
   ['AerialWithLabels', geoloc_translate("Satellite").' et label (Bing maps)']
   );
   if($api_key_bing=='' and $api_key_mapbox=='') unset($fond_provider[6],$fond_provider[7],$fond_provider[8],$fond_provider[9],$fond_provider[10]);
   elseif($api_key_bing=='') unset($fond_provider[8],$fond_provider[9],$fond_provider[10]);
   elseif($api_key_mapbox=='') unset($fond_provider[6],$fond_provider[7]);

   $cartyp=''; // choix manuel du provider
   $source_fond=''; $max_r=''; $min_r='';$layer_id='';
   switch ($cartyp) {
      case 'Road': case 'Aerial': case 'AerialWithLabels':
         $source_fond='
         new ol.source.BingMaps({
            key: "'.$api_key_bing.'",imagerySet: "'.$cartyp.'"
         })';
         $max_r='40000';
         $min_r='0';
         $layer_id= $cartyp;
      break;
      case 'natural-earth-hypso-bathy': case 'geography-class':
         $source_fond='
         new ol.source.TileJSON({
            url: "https://api.tiles.mapbox.com/v4/mapbox.'.$cartyp.'.json?access_token='.$api_key_mapbox.'",
            attributions: "© <a href=\"https://www.mapbox.com/about/maps/\">Mapbox</a> © <a href=\"http://www.openstreetmap.org/copyright\">OpenStreetMap</a> <strong><a href=\"https://www.mapbox.com/map-feedback/\" target=\"_blank\">Improve this map</a></strong>"
         })';
         $max_r='40000';
         $min_r='2000';
         $layer_id= $cartyp;
      break;
      case 'sat-google':
         $source_fond='
         new ol.source.XYZ({
            url: "https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}",
            crossOrigin: "Anonymous",
            attributions: " &middot; <a href=\"https://www.google.at/permissions/geoguidelines/attr-guide.html\">Map data ©2015 Google</a>"
         })';
         $max_r='40000';
         $min_r='0';
         $layer_id= $cartyp;
      break;
      case 'terrain':case 'toner':case 'watercolor':
         $source_fond='new ol.source.Stamen({layer:"'.$cartyp.'"})';
         $max_r='40000';
         $min_r='0';
         $layer_id= $cartyp;
      break;
      default:
         $source_fond='new ol.source.OSM({})';
         $max_r='40000';
         $min_r='0';
         $layer_id= 'OSM';
   }
   $affi .= '
   <div class="my-3">';
   if($multi!='')
      $affi .= '
      <div id="map-wrapper" style="height:950px;" class=" my-3">';
   else
      $affi .= '
      <div id="map-wrapper" class="ol-fullscreen my-3">';
      $affi .= '
         <div id="mapol" class="map" tabindex="20" lang="'.language_iso(1,0,0).'"></div>
         <div id="sidebar_loca" class= "collapse show col-sm-4 col-md-3 col-6 px-0">
            <div id="sb_tools" class="list-group mb-3">
               <div class="" id="l_sb_tools">
                  <div class="list-group-item list-group-item-action py-1 px-1">
                     <div class="row mb-0">
                        <label class="col-form-label col-sm-12 fw-bolder" for="cartyp">Type de carte</label>
                        <div class="col-sm-12">
                           <select class="form-select" name="cartyp" id="cartyp">';
   $j=0;
   foreach ($fond_provider as $v) {
      if($v[0]==$cartyp) $sel='selected="selected"'; else $sel='';
      switch($j){
         case '0': $affi .= '
                              <optgroup label="OpenStreetMap">';break;
         case '1': $affi .= '
                              <optgroup label="Google">';break;
         case '2': $affi .= '
                              <optgroup label="Stamen">';break;
         case '5': $affi .= '
                              <optgroup label="NASA">';break;
         case '6': if($api_key_mapbox==!'') 
                     $affi .= '
                              <optgroup label="Mapbox">';
                   elseif($api_key_bing==!'')
                     $affi .= '
                              <optgroup label="Bing maps">'; break;
         case '8': if($api_key_bing==!'' and $api_key_mapbox!=='') 
                     $affi .= '
                              <optgroup label="Bing maps">'; break;
      }
      $affi .= '
                                 <option '.$sel.' value="'.$v[0].'">'.$v[1].'</option>';
      switch($j){
         case '0': case '1': case '4': case '10': $affi .= '
                              </optgroup>'; break;
         case '7': if($api_key_mapbox==!'') $affi .= '
                              </optgroup>'; break;
         case '8': if($api_key_mapbox=='' and $api_key_bing==!'') $affi .= '
                              </optgroup>'; break;
      }
      $j++;
   }
$affi .= '
                           </select>
                           <input type="range" value="1" class="form-range mt-1" min="0" max="1" step="0.1" id="baselayeropacity" />
                           <label class="mt-0 float-end small" for="baselayeropacity">Opacity</label>
                           <div id="dayslider" class="collapse">
                              <input type="range" value="1" class="form-range mt-1" min="-6" max="0" value="0" id="nasaday" />
                              <label id="dateimages" class="mt-0 float-end small" for="nasaday">'.$date_jour.'</label>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>';
   if(!defined('OL')) {
      define('OL','ol');
      $affi .= '<script type="text/javascript" src="/lib/ol/ol.js"></script>';
   }
   $affi .= '
<script type="text/javascript">
   //<![CDATA[
   if (!$("link[href=\'/lib/ol/ol.css\']").length)
      $("head link[rel=\'stylesheet\']").last().after("<link rel=\'stylesheet\' href=\'/lib/ol/ol.css\' type=\'text/css\' media=\'screen\'>");
   $("head link[rel=\'stylesheet\']").last().after("<link rel=\'stylesheet\' href=\'/modules/npds_galerie/css/galerie.css\' type=\'text/css\' media=\'screen\'>");
   $("head link[rel=\'stylesheet\']").last().after("<link rel=\'stylesheet\' href=\'/modules/geoloc/include/ol-geocoder.css\' type=\'text/css\' media=\'screen\'>");
   $("head").append($("<script />").attr({"type":"text/javascript","src":"'.$nuke_url.'/modules/geoloc/include/ol-geocoder.js"}));
   $(function () {
      //==>  affichage des coordonnées...
         var mousePositionControl = new ol.control.MousePosition({
            coordinateFormat: function(coord) {return ol.coordinate.toStringHDMS(coord,2)},
            projection: "EPSG:4326",
            className: "custom-mouse-position",
            undefinedHTML: "&nbsp;"
         });
      //<==
      var
         geocodestyle= new ol.style.Style({
            image: new ol.style.Circle({
               radius: 6,
               fill: new ol.style.Fill({color: "rgba(255, 0, 0, 0.4)"}),
               stroke: new ol.style.Stroke({color: "red", width: 1})
            })
         }),
         locatedstyle = new ol.style.Style({
            text: new ol.style.Text({
               text: "\uf030",
               font: "900 18px \'Font Awesome 5 Free\'",
               bottom: "Bottom",
               scale: [1.5, 1.5],
               fill: new ol.style.Fill({color: "rgba(0, 104, 255, 0.4)"}),
               stroke: new ol.style.Stroke({color: "rgba(255, 255, 255, 1)", width: 1})
            })
         }),
         nolocatedstyle = new ol.style.Style({
            text: new ol.style.Text({
               text: "\uf030",
               font: "900 18px \'Font Awesome 5 Free\'",
               bottom: "Bottom",
               scale: [1.5, 1.5],
               fill: new ol.style.Fill({color: "rgba(0, 0, 0, 0.4)"}),
               stroke: new ol.style.Stroke({color: "rgba(255, 255, 255, 1)", width: 1})
            })
         }),
         popuptooltip = new ol.Overlay({
           element: document.getElementById("ol_tooltip")
         }),
      img_features=[];
      '.$img_point.'
      var
         src_img = new ol.source.Vector({}),
         src_img_length = img_features.length;

      for (var i = 0; i < src_img_length; i++){
         var iconFeature = new ol.Feature({
            geometry: new ol.geom.Point(ol.proj.transform(img_features[i][0], "EPSG:4326","EPSG:3857"))
         });
         iconFeature.setId(("pg"+i));
         src_img.addFeature(iconFeature);
      }

      var img_markers = new ol.layer.Vector({
         id: "imag",
         source: src_img,';
         if (($lat == '0') and ($long == '0'))
            $affi .= '
         style: nolocatedstyle';
         else 
            $affi .= '
         style: locatedstyle';
   $affi .= '});
      var src_georef = new ol.source.Vector({});';
   if($multi!='')
      $affi .= '
      var pointGeoref1 = new ol.Feature({
        geometry: new ol.geom.Point(ol.proj.fromLonLat([25, 60])),
      });
      pointGeoref1.setId(("pg1"));
      var pointGeoref2 = new ol.Feature({
         geometry: new ol.geom.Point(ol.proj.fromLonLat([10, 30])),
      });
      pointGeoref2.setId(("pg2"));
      var pointGeoref3 = new ol.Feature({
        geometry: new ol.geom.Point(ol.proj.fromLonLat([0, 0])),
      });
      pointGeoref3.setId(("pg3"));
      var pointGeoref4 = new ol.Feature({
        geometry: new ol.geom.Point(ol.proj.fromLonLat([-10, -30])),
      });
      pointGeoref4.setId(("pg4"));
      var pointGeoref5 = new ol.Feature({
        geometry: new ol.geom.Point(ol.proj.fromLonLat([-25, -60])),
      });
      pointGeoref5.setId(("pg5"));
      
      src_georef.addFeatures([pointGeoref1,pointGeoref2,pointGeoref3,pointGeoref4,pointGeoref5]);
      src_georef.getFeatures().forEach(feat => {console.log(feat), feat.setStyle(
         new ol.style.Style({
            text: new ol.style.Text({
               text: "\uf030 "+feat.getId().substr(2),
               font: "900 18px \'Font Awesome 5 Free\'",
               bottom: "Bottom",
               scale: [1.5, 1.5],
               fill: new ol.style.Fill({color: "rgba(0, 0, 0, 0.4)"}),
               stroke: new ol.style.Stroke({color: "rgba(255, 255, 255, 1)", width: 1})
            })
         })
      )});';
   $affi .= '
      var
         georef_marker = new ol.layer.Vector({
            id:"georef",
            source: src_georef,
            style: nolocatedstyle
         }),
         source = new ol.source.OSM(),
         overviewMapControl = new ol.control.OverviewMap({
            layers: [new ol.layer.Tile({source: source,})],
         }),
         src_fond = '.$source_fond.',
         minR='.$min_r.',
         maxR='.$max_r.',
         layer_id="'.$layer_id.'",
         fond_carte = new ol.layer.Tile({
            id:layer_id,
            source: src_fond,
            minResolution: minR,
            maxResolution: maxR
         }),
         attribution = new ol.control.Attribution({collapsible: true}),
         zoomslider = new ol.control.ZoomSlider(),
         fullscreen = new ol.control.FullScreen({source: "map-wrapper"}),
         scaleline = new ol.control.ScaleLine(),
         view = new ol.View({';
   if($multi=='')
      $affi .= 'center: ol.proj.fromLonLat(['.$long.', '.$lat.']),
            zoom: 5,';
   else
      $affi .= 'center: ol.proj.fromLonLat([0, 0]),
            zoom: 2,';
   $affi .= '
            minZoom:2
         });
         var
            select = new ol.interaction.Select({style:null}),
            translate = new ol.interaction.Translate({
               features: select.getFeatures(),
            });

      var map = new ol.Map({
         interactions: new ol.interaction.defaults({
            constrainResolution: true, onFocusOnly: true
         }).extend([select,translate]),
         controls: new ol.control.defaults({attribution: false}).extend([attribution, fullscreen, mousePositionControl, scaleline, zoomslider, overviewMapControl]),
         target: document.getElementById("mapol"),
         layers: [
            fond_carte,';
   if($multi=='')
      $affi .= '
            img_markers,';
   $affi .= '
            georef_marker
         ],
         view: view
      });';
   if($multi !=='')
      $affi .= '
      map.on("singleclick", function (evt) {
         const Ll= evt.coordinate;
         const hdms = ol.proj.toLonLat(Ll);
      });

      translate.on("translateend", function(evt) {
         var idim = (evt.features.item(0).getId()).substr(2),
             coordinate = evt.coordinate,
             coordWgs = ol.proj.toLonLat(coordinate);
         $("#imglat"+idim).val(coordWgs[1].toFixed(6));
         $("#imglong"+idim).val(coordWgs[0].toFixed(6));
         if((evt.features.item(0).getId()).substr(0, 2) ==="pg") {
            evt.features.item(0).setStyle(new ol.style.Style({
               text: new ol.style.Text({
                  text: "\uf030 "+idim,
                  font: "900 18px \'Font Awesome 5 Free\'",
                  bottom: "Bottom",
                  scale: [1.5, 1.5],
                  fill: new ol.style.Fill({color: "rgba(0, 104, 255, 0.4)"}),
                  stroke: new ol.style.Stroke({color: "rgba(255, 255, 255, 1)", width: 1})
               })
            }));
            $(".jsgeo"+idim).click(function () {
               view.setCenter(coordinate);
            }).addClass("text-primary")
         }
      });';
   if($multi=='')
      $affi .= '
      $("#imglat").val().length ? $(".jsgeo").addClass("text-primary"):"";
      translate.on("translateend", function(evt) {
         coordinate = evt.coordinate,
         coordWgs = ol.proj.transform(evt.coordinate, "EPSG:3857", "EPSG:4326");
         $("#imglat").val(coordWgs[1].toFixed(6));
         $("#imglong").val(coordWgs[0].toFixed(6));
         if((evt.features.item(0).getId()).substr(0, 2) ==="pg") {
            evt.features.item(0).setStyle(new ol.style.Style({
               text: new ol.style.Text({
                  text: "\uf030",
                  font: "900 18px \'Font Awesome 5 Free\'",
                  bottom: "Bottom",
                  scale: [1.5, 1.5],
                  fill: new ol.style.Fill({color: "rgba(0, 104, 255, 0.5)"}),
                  stroke: new ol.style.Stroke({color: "rgba(255, 255, 255, 1)", width: 1})
               })
            }));
            $(".jsgeo").click(function () {
               view.setCenter(coordinate);
            }).addClass("text-primary")
         }
      });';
   $affi .= '
      //==> changement etat pointeur sur les markers
      map.on("pointermove", function(e) {
         var
            pixel = map.getEventPixel(e.originalEvent),
            hit = map.hasFeatureAtPixel(pixel);
         map.getTarget().style.cursor = hit ? "pointer" : "";
      });
      //<== changement etat pointeur sur les markers';
   $source ='';
   if($multi=='') $source ='src_img';
   else if($multi!=='') $source ='src_georef';
   $affi .= '
      var
         geocoder = new Geocoder("nominatim", {
            featureStyle : geocodestyle,
            provider: "osm",
            lang: "'.language_iso(1,0,0).'",
            placeholder: "Chercher un lieu",
            limit: 5,
            debug: false,
            autoComplete: true,
            keepOpen: false
         });
      map.addControl(geocoder);
      geocoder.on("addresschosen", function (evt) {
         var x=500;
         geocoder.getSource().clear();
         geocoder.getSource().addFeature(evt.feature);
         '.$source.'.getFeatures().forEach(feat=>{';
   if($multi=='')
      $affi .='
            var idf = 1;
            if ($("#imglat").val()=="" && $("#imglong").val()=="") {';
   if($multi!=='')
      $affi .='
            var idf = feat.getId().substr(2);
            if ($("#imglat"+idf).val()=="" && $("#imglong"+idf).val()=="") {';
   $affi .='
               window.setTimeout(function () {
                  feat.getGeometry().setCoordinates([(evt.coordinate[0]+(x*idf)),evt.coordinate[1]+(x*idf)]);
               }, 600*idf);
            }
         });
      });';

   $affi .= file_get_contents('modules/geoloc/include/ol-dico.js');
   $affi .='
      const targ = map.getTarget();
      const lang = targ.lang;
      for (var i in dic) {
         if (dic.hasOwnProperty(i)) {
            $("#mapol "+dic[i].cla).prop("title", dic[i][lang]);
         }
      }
      fullscreen.on("enterfullscreen",function(){
         $(dic.olfullscreentrue.cla).attr("data-bs-original-title", dic["olfullscreentrue"][lang]);
      })
      fullscreen.on("leavefullscreen",function(){
         $(dic.olfullscreenfalse.cla).attr("data-bs-original-title", dic["olfullscreenfalse"][lang]);
      })

   $("#cartyp").on("change", function() {
      cartyp = $( "#cartyp option:selected" ).val();
      $("#dayslider").removeClass("show");
      switch (cartyp) {
         case "OSM":
            fond_carte.setSource(new ol.source.OSM());
            map.getLayers().item(0).setProperties({"id":cartyp});
            fond_carte.setMinResolution(1);
         break;
         case "sat-google":
            fond_carte.setSource(new ol.source.XYZ({url: "https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}",crossOrigin: "Anonymous", attributions: " &middot; <a href=\"https://www.google.at/permissions/geoguidelines/attr-guide.html\">Map data ©2015 Google</a>"}));
            map.getLayers().item(0).setProperties({"id":cartyp});
         break;
         case "Road":case "Aerial":case "AerialWithLabels":
            fond_carte.setSource(new ol.source.BingMaps({key: "'.$api_key_bing.'",imagerySet: cartyp }));
            map.getLayers().item(0).setProperties({"id":cartyp});
            fond_carte.setMinResolution(1);
         break;
         case "natural-earth-hypso-bathy": case "geography-class":
            fond_carte.setSource(new ol.source.TileJSON({url: "https://api.tiles.mapbox.com/v4/mapbox."+cartyp+".json?access_token='.$api_key_mapbox.'"}));
            fond_carte.setMinResolution(2000);
            fond_carte.setMaxResolution(40000);
            map.getLayers().item(0).setProperties({"id":cartyp});
         break;
         case "terrain": case "toner": case "watercolor":
            fond_carte.setSource(new ol.source.Stamen({layer:cartyp}));
            fond_carte.setMinResolution(0);
            fond_carte.setMaxResolution(40000);
            map.getLayers().item(0).setProperties({"id":cartyp});
         break;
         case "modisterra":
            $("#dayslider").addClass("show");
            var datejour="'.$date_jour.'";
            var today = new Date();
            fond_carte.setSource(new ol.source.XYZ({url: "https://gibs-{a-c}.earthdata.nasa.gov/wmts/epsg3857/best/VIIRS_SNPP_CorrectedReflectance_TrueColor/default/"+datejour+"/GoogleMapsCompatible_Level9/{z}/{y}/{x}.jpg"}));
            $("#nasaday").on("input change", function(event) {
               var newDay = new Date(today.getTime());
               newDay.setUTCDate(today.getUTCDate() + Number.parseInt(event.target.value));
               datejour = newDay.toISOString().split("T")[0];
               var datejourFr = datejour.split("-");
               $("#dateimages").html(datejourFr[2]+"/"+datejourFr[1]+"/"+datejourFr[0]);
               fond_carte.setSource(new ol.source.XYZ({url: "https://gibs-{a-c}.earthdata.nasa.gov/wmts/epsg3857/best/VIIRS_SNPP_CorrectedReflectance_TrueColor/default/"+datejour+"/GoogleMapsCompatible_Level9/{z}/{y}/{x}.jpg"}));
            });
            fond_carte.setMinResolution(2);
            fond_carte.setMaxResolution(40000);
            map.getLayers().item(0).setProperties({"id":cartyp});
         break;
      }
   });

   // ==> opacité sur couche de base
      $("#baselayeropacity").on("input change", function() {
         map.getLayers().item(0).setOpacity(parseFloat(this.value));
      });
   // <== opacité sur couche de base

      $("#ol_tooltip").tooltip({container:"#mapol"});
      $("#mapol .ol-zoom-in, #mapol .ol-zoom-out, #mapol .ol-overviewmap ").tooltip({placement: "right", container:"#mapol"});
      $("#mapol .ol-full-screen-false, #mapol .ol-rotate-reset, #mapol .ol-attribution button[title]").tooltip({placement: "left", container:"#mapol"});
   });
   //]]>
</script>';
return $affi;
}

?>