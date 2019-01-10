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
/************************************************************************/
/* Fonctions du module                                                  */
/************************************************************************/
// les menus
/*******************************************************/

function FabMenu() {
   global $NPDS_Prefix, $ThisFile, $aff_comm, $aff_vote, $ModPath, $user;
   $query = sql_query("SELECT id, nom, acces FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='0' ORDER BY nom");
   if (sql_num_rows($query) != 0) {
      $ibid='';
      while($row = sql_fetch_row($query)) {
         if (autorisation($row[2])) {
            $ibid.='
            <div class="col-md-4 mb-2">
            <a href="'.$ThisFile.'&amp;op=cat&amp;catid='.$row[0].'"><i class="fa fa-folder fa-2x align-middle mr-2"></i>'.stripslashes($row[1]).'</a>
            </div>';
         }
      }
      if ($ibid) {
         echo '<h5 class="card-header"><span class="breadcrumb-item my-1 mr-2"><i class="fa fa-camera fa-2x align-middle mr-2"></i>'.gal_translate("Accueil").'</span><span class="float-right">';
         if ($aff_comm)
            echo '<a class="btn btn-outline-secondary btn-sm  my-1 mr-2" href="modules.php?ModPath='.$ModPath.'&amp;ModStart=gal&amp;op=topcomment">'.gal_translate("Top-Commentaires").'</a>';
         if ($aff_vote)
            echo '<a class="btn btn-outline-secondary btn-sm my-1 mr-2" href="modules.php?ModPath='.$ModPath.'&amp;ModStart=gal&amp;op=topvote">'.gal_translate("Top-Votes").'</a>';
         if (isset($user))
            echo '<a class="btn btn-outline-secondary btn-sm my-1" href="modules.php?ModPath='.$ModPath.'&amp;ModStart=gal&amp;op=formimgs">'.gal_translate("Proposer des images").'</a>';
         echo '</span></h5>
         <div class="card-body">
            <div class="row lead">';
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
         <a class="breadcrumb-item" href="'.$ThisFile.'"><i class="fa fa-camera fa-2x align-middle mr-2"></i>'.gal_translate("Accueil").'</a>
         <span class="breadcrumb-item active">'.stripslashes($cat[0]).'</span>
      </nav>';
      if($nbsc>0) { 
         echo '
      <div class="card-body">
         <h4>'.gal_translate("Sous catégories").'<span class="float-right badge badge-secondary badge-pill">'.$nbsc.'</span></h4>
         <hr />
         <div class="row lead">';
         while ($row = sql_fetch_row($query)) {
            if (autorisation($row[2])) {
               $ngal = sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_gal WHERE cid='".$row[0]."'");
               $nbgal=sql_num_rows($ngal);
               echo '
               <div class="col-md-4 mb-2">';
               if($nbgal>0)
                  echo '
                  <a href="'.$ThisFile.'&amp;op=sscat&amp;catid='.$catid.'&amp;sscid='.$row[0].'"><i class="fa fa-folder fa-2x align-middle mr-2"></i>'.stripslashes($row[1]).'</a> <span class="badge badge-secondary badge-pill">'.$nbgal.'</span>';
               else
                  echo '
                  <a class="text-muted"><i class="fa fa-folder-o fa-2x align-middle mr-2"></i>'.stripslashes($row[1]).'</a>';
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
      <nav class="card-header lead">
         <a class="breadcrumb-item" href="'.$ThisFile.'"><i class="fa fa-camera fa-2x align-middle mr-2"></i>'.gal_translate("Accueil").'</a>
         <a class="breadcrumb-item" href="'.$ThisFile.'&op=cat&amp;catid='.$catid.'">'.stripslashes($cat[0]).'</a>
         <span class="breadcrumb-item active">'.stripslashes($sscat[0]).'</span>
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
         <a class="breadcrumb-item" href="'.$ThisFile.'"><i class="fa fa-camera fa-2x mr-2 align-middle"></i>'.gal_translate("Accueil").'</a>';
      echo GetGalArbo($galid);
      echo '<span class="breadcrumb-item active">'.stripslashes($gal[0]).'</span>
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
         <a class="breadcrumb-item" href="modules.php?ModPath='.$ModPath.'&amp;ModStart=gal"><i class="fa fa-camera fa-2x align-middle mr-2"></i>'.gal_translate("Accueil").'</a>';
      echo GetGalArbo($galid);
      echo '
         <a class="breadcrumb-item" href="'.$ThisFile.'&amp;op=gal&amp;galid='.$galid.'">'.stripslashes($gal[0]).'</a>';
      $img = sql_fetch_row(sql_query("SELECT comment FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='".$galid."' and noaff='0' ORDER BY ordre,id LIMIT $pos,1"));
      if ($img[0]!='')
         echo '<span class="breadcrumb-item active">'.stripslashes($img[0]).'</span>';
      echo '
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
               $ibid.= '<a href="'.$ThisFile.'&amp;op=gal&amp;galid='.$row[0].'"><i class="fa fa-folder fa-2x align-middle mr-2"></i>'.stripslashes($row[1]).'</a> <span class="badge badge-success badge-pill" title="'.gal_translate("Nombre d'images").'" data-toggle="tooltip" data-placement="right">'.$nimg[0].'</span>
               <br /><span class="small">'.gal_translate("Créée le").' '.date(translate("dateinternal"),$row[2]).'</span>';
            else 
               $ibid.= '<span class="text-muted"><i class="fa fa-folder-o fa-2x align-middle mr-2"></i>'.stripslashes($row[1]).'<br /><span class="small">'.gal_translate("Créée le").' '.date(translate("dateinternal"),$row[2]).'</span></span>';
            $ibid.= '
            </div>';
         }
      }
      if ($ibid!='')
         echo '
         <div class="card-body">
            <h4>'.gal_translate("Galeries").'<span class="float-right badge badge-secondary badge-pill">'.$nb_gal.'</span></h4>
            <hr />
            <div class="row lead">'.$ibid.'</div>
         </div>';
   }
}

function ViewGal($galid, $page){
   global $NPDS_Prefix, $ModPath, $imglign, $imgpage, $MaxSizeThumb, $aff_comm, $aff_vote, $galid, $pos,$pid;
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
      <div class="card-columns p-2 mt-2">';
      while ($row = sql_fetch_row($query)) {
        $nbcom = sql_num_rows(sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_com WHERE pic_id='".$row[0]."'"));
        $nbvote = sql_num_rows(sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_vot WHERE pic_id='".$row[0]."'"));
        if (@file_exists("modules/$ModPath/imgs/".$row[2])) {
           list($width, $height, $type, $attr) = @getimagesize("modules/$ModPath/imgs/$row[2]");
           $ibid = '<img class="img-fluid card-img-top" src="modules/'.$ModPath.'/mini/'.$row[2].'" alt="'.stripslashes($row[3]).' '.$attr.'" title="'.$row[2].'<br />'.stripslashes($row[3]).'" data-html="true" data-toggle="tooltip" data-placement="bottom" />';
        } else
           $ibid = ReducePic($row[2],stripslashes($row[3]),$MaxSizeThumb);
        echo '
            <div class="card">
               <a href="'.$ThisFile.'&amp;op=img&amp;galid='.$galid.'&amp;pos='.$pos.'">'.$ibid.'</a>
               <div class="card-body">
               <p class="card-text text-muted"><span class="badge badge-secondary mr-1">'.$row[4].'</span>'.gal_translate("affichage(s)");
        if ($aff_comm and $nbcom>0)
           echo '<br /><span class="badge badge-secondary mr-1">'.$nbcom.'</span>'.gal_translate("commentaire(s)");
        if ($aff_vote and $nbvote>0)
           echo '<br /><span class="badge badge-secondary mr-1">'.$nbvote.'</span>'.gal_translate("vote(s)");
        echo '
               </p>
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
            <button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target=".carou">'.gal_translate("Diaporama").'</button>
         </div>
         <div>';
      echo paginate_single('modules.php?ModPath='.$ModPath.'&amp;ModStart=gal&amp;op=gal&amp;galid='.$galid.'&amp;page=', '', $nbPages, $current, $adj=3,'',$page);
      echo '
         </div>
      </nav>
      <div class="mx-2">';
      echo meta_lang('comment_system(galerie,'.$galid.')');
      echo '</div>';
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
               <a class="nav-link" href="'.$ThisFile.'&amp;op=ecard&amp;galid='.$galid.'&amp;pos='.$pos.'&amp;pid='.$row[0].'" title="'.gal_translate("Envoyer comme e-carte").'" data-toggle="tooltip" data-placement="right"><i class="fa fa-at fa-2x"></i></a>
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
               <h4 class="card-title">'.gal_translate("Noter cette image").'</h4>
               <div class="card-body">
                  <div class="row">';
               $i=0;$star='';
               while ($i<=5) {
                  $star .='<i class="fa fa-star" aria-hidden="true"></i>';
                  echo '
                     <div class="col-xs-2">
                        <a class="btn btn-outline-primary btn-sm mr-1" href="'.$ThisFile.'&amp;op=vote&amp;value='.$i.'&amp;pic_id='.$row[0].'&amp;gal_id='.$galid.'&amp;pos='.$pos.'">'.$star.'</a>
                     </div>';
                  $i++;
               }
            echo '
               </div>
            </div>';
            }
         }
      }

// Infos sur l'image
   $tailleo = @filesize("modules/$ModPath/imgs/$row[2]");
   $taille = $tailleo/1000;
   echo '
   <h4 class="card-title">'.gal_translate("Informations sur l'image").'</h4>
   <ul class="list-group lead">
      <li class="list-group-item d-flex justify-content-between align-items-center">'.gal_translate("Taille du fichier").'<span class="badge badge-secondary">'.$taille.' Ko</span></li>
      <li class="list-group-item d-flex justify-content-between align-items-center">'.gal_translate("Dimensions").'<span class="badge badge-secondary">'.$width.' x '.$height.' Pixels</span></li>';
   if ($aff_vote) {
      $rowV = sql_fetch_row(sql_query("SELECT COUNT(id), AVG(rating) FROM ".$NPDS_Prefix."tdgal_vot WHERE pic_id='".$row[0]."'"));
      $note = round($rowV[1]);$star='';
      $i=0;
      while($i<=$note) {$star.='<i class="fa fa-star mx-1"></i>';$i++;}
      echo '
      <li class="list-group-item d-flex justify-content-between align-items-center">'.gal_translate("Note ").$rowV[0].' '.gal_translate("vote(s)").'<span class="text-success">'.$star.'</span></li>';
      }
   echo '
      <li class="list-group-item d-flex justify-content-between align-items-center">'.gal_translate("Affichées").'<span class="badge badge-secondary">'.($row[4] + 1).' '.gal_translate("fois").'</span></li>
   </ul>';

      if ($interface!="no") {
         if ($aff_comm) {
         //   echo meta_lang('<p>comment_system(galerie,'.$pos.')</p>');
         // Commentaires sur l'image
            $qcomment = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_com WHERE pic_id='".$row[0]."' ORDER BY comtimestamp DESC LIMIT 0,10");
            $num_comm = sql_num_rows($qcomment);
            if (($num_comm > 0) || (isset($user) || $comm_anon)) {
               echo '
      <h4 class="card-title mt-3">'.gal_translate("Commentaire(s)").'</h4>';
               while ($rowC = sql_fetch_row($qcomment)) {
                  echo '
      <div class="card mb-2"><div class="card-header"><strong>'.$rowC[2].'</strong><span class="small float-right">'.gal_translate('Posté le').' '.date(translate("dateinternal"),$rowC[5]).'</span></div>
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
                     <fieldset class="form-group">
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
  max-width:300px;
  width:100%;
}
.carousel-item-next, .carousel-item-prev, .carousel-item.active {
    display: block !important;
}
</style>
      
<div class="wrapper">
      <div id="photosIndicators" class="carousel slide" data-ride="carousel" data-wrap="true" data-interval="3000">
         <ol class="carousel-indicators">';
       $i = 0;
      settype($pos,"integer");
      $pic_query = sql_query("SELECT id, name FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='$galid' AND noaff='0'");
      while($picture = sql_fetch_assoc($pic_query)) {
         if($i==0)
            echo '<li data-target="#photosIndicators" data-slide-to="'.$i.'" class="active"></li>';
         else
            echo '<li data-target="#photosIndicators" data-slide-to="'.$i.'"></li>';
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
         <a class="carousel-control-prev" href="#photosIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
         </a>
         <a class="carousel-control-next" href="#photosIndicators" role="button" data-slide="next">
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
         <div class="card-header lead"><a href="'.$ThisFile.'"><i class="fa fa-camera fa-2x align-middle mr-2"></i>'.gal_translate("Accueil").'</a></div>
         <div class="card-body">
            <h4 class="">'.gal_translate("Envoyer une E-carte de la part de").' <span class="text-muted">'.$username.'</span></h4>
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
                  <textarea class="form-control tin " name="card_msg" id="card_msg" rows="5" required="required"></textarea>
               </div>';
      aff_editeur("card_msg","true");
      echo Q_spambot();
      echo '
               <button class="btn btn-primary" type="submit">'.gal_translate("Envoyer comme e-carte").'</button>
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
      $message = "<!DOCTYPE html>";
      $message.= '<head>';
      $message.= '<title>'.gal_translate("Une e-carte pour vous").'</title>';
      $message.= '<meta http-equiv="content-type" content="text/html" />';
      $message.= '<meta charset="utf-8" />';
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
      <p class="card-text alert alert-danger" role="alert"><i class="fa fa-info-circle mr-2" aria-hidden="true"></i>'.gal_translate("Erreur").'<br />';
   else
      echo '
      <p class="card-text alert alert-success" role="alert"><i class="fa fa-info-circle mr-2" aria-hidden="true"></i>'.gal_translate("Résultat").'<br />';
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
   $qverif = sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_com WHERE pic_id='$pic_id' AND user='$name' AND comhostname='$host'");
   if (sql_num_rows($qverif) == 0) {
      $stamp = time()+((integer)$gmt*3600);
      sql_query("INSERT INTO ".$NPDS_Prefix."tdgal_com VALUES('',$pic_id,'$name','$comment','$host','$stamp')");
      redirect_url($ThisRedo."&op=img&galid=$gal_id&pos=$pos");
   } else {
      echo '
      <div class="card-body">
         <div class="lead alert alert-danger"><i class="fa fa-info-circle mr-2" aria-hidden="true"></i>'.gal_translate("Vous avez déjà commenté cette photo").'</div>
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
   $picverif = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_img WHERE id='$pic_id'");
   $qverif = sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_vot WHERE pic_id='$pic_id' AND user='$name' AND ratinghostname='$host'");
   if ((sql_num_rows($qverif) == 0) and (sql_num_rows($picverif) !='')) {
      $stamp = time()+((integer)$gmt*3600);
      sql_query("INSERT INTO ".$NPDS_Prefix."tdgal_vot VALUES('','$pic_id','$name','$value','$host','$stamp')");
      redirect_url($ThisRedo."&op=img&galid=$gal_id&pos=$pos");
   } else {
      echo '
      <div class="alert alert-danger">
         <i class="fa fa-info-circle mr-2" aria-hidden="true"></i>'.gal_translate("Vous avez déjà noté cette photo").'
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
   while (list($X, $val) = each($tab_groupe)) {
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

   // Affichage
   echo '
   <h4 class="card-header">'.gal_translate("Photos aléatoires").'</h4>
   <div class="card-columns p-2 mt-2">';
   $pos='0';
   while ($row = sql_fetch_row($query)) {
      $nbcom = sql_num_rows(sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_com WHERE pic_id='".$row[0]."'"));
      if (file_exists("modules/$ModPath/imgs/".$row[2])) {
         list($width, $height, $type, $attr) = @getimagesize("modules/$ModPath/imgs/$row[2]");
         $ibid = '<img class="img-fluid card-img-top" src="modules/'.$ModPath.'/mini/'.$row[2].'" alt="'.stripslashes($row[3]).'" '.$attr.' title="'.$row[2].'<br />'.stripslashes($row[3]).'" data-html="true" data-toggle="tooltip" data-placement="bottom"/>';
      } else
         $ibid = ReducePic($row[2],stripslashes($row[3]),$MaxSizeThumb);
      echo '
         <div class="card">
            <a href="'.$ThisFile.'&amp;op=img&amp;galid='.$row[1].'&amp;pos=-'.$row[0].'">'.$ibid.'</a>
            <div class="card-body">
               <p class="card-text text-muted"><span class="badge badge-secondary mr-1">'.$row[4].'</span>'.gal_translate("affichage(s)");
      if ($aff_comm and $nbcom>0)
         echo '<br /><span class="badge badge-secondary mr-1">'.$nbcom.'</span>'.gal_translate("commentaire(s)");
      echo '
               </p>
            </div>
         </div>';
      $pos++;
   }
   echo '
   </div>';
}

function ViewLastAdd() {
   global $NPDS_Prefix, $ModPath, $ThisFile, $imgpage, $MaxSizeThumb, $aff_comm;
   // Fabrication de la requête 1
   $where1='';
   $tab_groupe=autorisation_local();
   $count = count($tab_groupe); $i = 0;
   while (list($X, $val) = each($tab_groupe)) {
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
   <h4 class="card-header">'.gal_translate("Derniers ajouts").'</h4>
   <div class="card-columns p-2 mt-2">';
   while ($row = sql_fetch_row($query)) {
      $nbcom = sql_num_rows(sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_com WHERE pic_id='".$row[0]."'"));
      if (file_exists("modules/$ModPath/imgs/".$row[2])) {
         list($width, $height, $type, $attr) = @getimagesize("modules/$ModPath/imgs/$row[2]");
         $ibid = '<img class="img-fluid card-img-top" src="modules/'.$ModPath.'/imgs/'.$row[2].'" alt="'.stripslashes($row[3]).' '.$attr.'" title="'.$row[2].'<br />'.stripslashes($row[3]).'" data-html="true" data-toggle="tooltip" data-placement="bottom"/>';
      } else
         $ibid = ReducePic($row[2],stripslashes($row[3]),$MaxSizeThumb);
      echo '
      <div class="card">
         <a href="'.$ThisFile.'&amp;op=img&amp;galid='.$row[1].'&amp;pos=-'.$row[0].'">'.$ibid.'</a>
         <div class="card-body">
            <p class="card-text text-muted"><span class="badge badge-secondary mr-1">'.$row[4].'</span>'.gal_translate("affichage(s)");
      if ($aff_comm and $nbcom>0)
         echo '<br /><span class="badge badge-secondary mr-1">'.$nbcom.'</span>'.gal_translate("commentaire(s)");
      echo '
            </p>
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
      <a class="breadcrumb-item" href="'.$ThisFile.'&amp;op=cat&amp;catid='.$temp[0].'">'.stripslashes($row[1]).'</a>';
   else {
      $queryX = sql_query("SELECT nom FROM ".$NPDS_Prefix."tdgal_cat WHERE id='".$row[0]."'");
      $rowX = sql_fetch_row($queryX);
      $retour = '
      <a class="breadcrumb-item" href="'.$ThisFile.'&amp;op=cat&amp;catid='.$row[0].'">'.stripslashes($rowX[0]).'</a>
      <a class="breadcrumb-item" href="'.$ThisFile.'&amp;op=sscat&amp;catid='.$row[0].'&amp;sscid='.$temp[0].'">'.stripslashes($row[1]).'</a>';
   }
   return $retour;
}

/*******************************************************/
//à voir
/*******************************************************/

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
   return '<img class="img-fluid" src="'.$image.'" height="'.$h_i.'" width="'.$w_i.'" alt="'.$comment.'" />';
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
         <a href="modules.php?ModPath='.$ModPath.'&amp;ModStart=gal"><i class="fa fa-camera fa-2x align-middle mr-2"></i>'.gal_translate("Accueil").'</a>
      </div>
      <div class="card-body">
         <h5 class="card-title">';
   if ($typeOP=='comment')
      echo gal_translate("Top").' '.$nbtop.' '.gal_translate("des images les plus commentées").'</h5>';
   else
      echo gal_translate("Top").' '.$nbtop.' '.gal_translate("des images les plus notées").'</h5>';

   $TableRep=sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_img WHERE noaff='0'");
   $NombreEntrees=sql_num_rows($TableRep);
   $TableRep1=sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_com");
   $NombreComs=sql_num_rows($TableRep1);
   $TableRep2=sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_vot");
   $NombreComs1=sql_num_rows($TableRep2);
   echo '
         <hr />
         <ul class="list-group mb-3 lead">
            <li class="list-group-item d-flex justify-content-between align-items-center">'.gal_translate("Nombre d'images").'<span class="badge badge-secondary badge-pill">'.$NombreEntrees.'</span></li>
            <li class="list-group-item d-flex justify-content-between align-items-center">'.gal_translate("Nombre de commentaires").'<span class="badge badge-secondary badge-pill">'.$NombreComs.'</span></li>
            <li class="list-group-item d-flex justify-content-between align-items-center">'.gal_translate("Nombre de notes").'<span class="badge badge-secondary badge-pill">'.$NombreComs1.'</span></li>
         </ul>';

   if ($typeOP=='comment')
      $result1 = sql_query("SELECT pic_id, count(user) AS pic_nbcom FROM ".$NPDS_Prefix."tdgal_com GROUP BY pic_id ORDER BY pic_nbcom DESC LIMIT 0,$nbtop");
   else
      $result1 = sql_query("SELECT pic_id, count(user) AS pic_nbvote FROM ".$NPDS_Prefix."tdgal_vot GROUP BY pic_id ORDER BY pic_nbvote DESC LIMIT 0,$nbtop");
   echo '
         <div class="card-columns p-0 mt-2">';
   $j=1;
   while (list($pic_id, $nb) = sql_fetch_row($result1)) {
      $result2=sql_fetch_assoc(sql_query("SELECT gal_id, name, comment FROM ".$NPDS_Prefix."tdgal_img WHERE id='$pic_id' AND noaff='0'"));

      if ($result2) {
         $comm_vignette=StripSlashes($result2['comment']);
         echo '
            <div class="card" title="'.$result2['name'].'" data-toggle="tooltip" data-placement="bottom" >
               <div class="card-body px-0 pt-1 pb-1 text-center">
                  <div class="h2 text-center">
                     <span class="badge badge-success badge-pill ">'.$j.'</span>
                  </div>
                  <a href="modules.php?ModPath='.$ModPath.'&amp;ModStart=gal&amp;op=img&amp;galid='.($result2['gal_id']).'&amp;pos=-'.$pic_id.'" ><img class="n-irl" src="modules/'.$ModPath.'/mini/'.$result2['name'].'" alt="'.$comm_vignette.'" /></a>
                  <div class="card-text">
                  <span class="badge badge-secondary badge-pill" data-toggle="tooltip" data-placement="left"';
         if ($typeOP=='comment')
            echo ' title="'.gal_translate("Nombre de commentaires").'"';
         else
            echo ' title="'.gal_translate("Nombre de vote(s)").'"';
         echo ' >'.$nb.'</span>
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
   $sql_cat = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='0' ORDER BY nom ASC");
   $num_cat = sql_num_rows($sql_cat);
   if ($num_cat != 0) {
      $sql_sscat = "SELECT * FROM ".$NPDS_Prefix."tdgal_cat WHERE cid!=0";
      $sql_gal = "SELECT * FROM ".$NPDS_Prefix."tdgal_gal";
      // CATEGORIE
      while ($row_cat = sql_fetch_row($sql_cat)) {
         $ibid.='<optgroup label="'.stripslashes($row_cat[2]).'">';
         $queryX = sql_query("SELECT id, nom  FROM ".$NPDS_Prefix."tdgal_gal WHERE cid='".$row_cat[0]."' ORDER BY nom ASC");
         while ($rowX_gal = sql_fetch_row($queryX)) {
            if ($rowX_gal[0] == $sel) { $IsSelected = " selected"; } else { $IsSelected = ""; }
            $ibid.='<option value="'.$rowX_gal[0].'" '.$IsSelected.'>'.stripslashes($rowX_gal[1]).'</option>';
         } // Fin Galerie Catégorie

         // SOUS-CATEGORIE
         $query = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='".$row_cat[0]."' ORDER BY nom ASC");
         while ($row_sscat = sql_fetch_row($query)) {
            $ibid.='<optgroup label="  '.stripslashes($row_sscat[2]).'">';
            $querx = sql_query("SELECT id, nom FROM ".$NPDS_Prefix."tdgal_gal WHERE cid='".$row_sscat[0]."' ORDER BY nom ASC");
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
      <div class="card-header lead"><a href="modules.php?ModPath='.$ModPath.'&ModStart=gal"><i class="fa fa-camera fa-2x align-middle mr-2"></i>'.gal_translate("Accueil").'</a></div>
      <div class="card-body">
         <h5 class="card-title">'.gal_translate("Proposer des images").'</h5>
         <hr />
         <form enctype="multipart/form-data" method="post" action="'.$ThisFile.'" name="FormImgs" lang="'.language_iso(1,'','').'">
            <input type="hidden" name="op" value="addimgs" />
            <input type="hidden" name="user_connecte" value="'.$user_connecte.'" />
            <div class="form-group">
               <label class="col-form-label" for="imggal">'.gal_translate("Galerie").'</label>
               <select class="custom-select" name="imggal" id="imggal" >';
   echo select_arbo('');
   echo '
               </select>
            </div>';
      $i=1;
      do {
         echo '
              <div class="form-group">
               <label class="">'.gal_translate("Image").' '.$i.'</label>
               <div class="input-group mb-2 mr-sm-2">
                  <div class="input-group-prepend" onclick="reset2($(\'#newcard'.$i.'\'),'.$i.');">
                     <div class="input-group-text"><i class="fa fa-refresh"></i></div>
                  </div>
                  <div class="custom-file">
                     <input type="file" class="custom-file-input" name="newcard'.$i.'" id="newcard'.$i.'" />
                     <label id="lab'.$i.'" class="custom-file-label" for="newcard'.$i.'">'.gal_translate("Sélectionner votre image").'</label>
                  </div>
               </div>
            </div>
            <div class="form-group">
               <input type="text" class="form-control" id="newdesc'.$i.'"  name="newdesc'.$i.'" placeholder="'.gal_translate("Description").'">
            </div>';
         $i++;
      }
      while($i<=6);
   echo '
            <button class="btn btn-primary" type="submit">'.gal_translate("Envoyer").'</button>
         </form>
      </div>
   </div>
   <script type="text/javascript">
      //<![CDATA[
         $(".custom-file-input").on("change",function(){
            $(this).next(".custom-file-label").addClass("selected").html($(this).val().split(\'\\\\\').pop());
         });
         window.reset2 = function (e,f) {
            e.wrap("<form>").closest("form").get(0).reset();
            e.unwrap();
            event.preventDefault();
            $("#lab"+f).html("'.gal_translate("Sélectionner votre image").'")
         };
      //]]>
   </script>';
}

// Ajout de photos par les membres
function AddImgs($imgscat,$newcard1,$newdesc1,$newcard2,$newdesc2,$newcard3,$newdesc3,$newcard4,$newdesc4,$newcard5,$newdesc5,$user_connecte) {
   global $language, $MaxSizeImg, $MaxSizeThumb, $ModPath, $ModStart, $NPDS_Prefix, $ThisFile, $adminmail, $nuke_url, $notif_admin;
   include_once("modules/upload/lang/upload.lang-$language.php");
   include_once("modules/upload/clsUpload.php");

   $newdesc1=$newdesc1.gal_translate(" proposé par ").$user_connecte;
   $newdesc2=$newdesc2.gal_translate(" proposé par ").$user_connecte;
   $newdesc3=$newdesc3.gal_translate(" proposé par ").$user_connecte;
   $newdesc4=$newdesc4.gal_translate(" proposé par ").$user_connecte;
   $newdesc5=$newdesc5.gal_translate(" proposé par ").$user_connecte;

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
      $img = 'newcard'.$i;
      $tit = 'newdesc'.$i;
      if (!empty($$img)) {
         $newimg = stripslashes(removeHack($$img));
         if (!empty($$tit))
            $newtit = addslashes(removeHack($$tit));
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
               if (sql_query("INSERT INTO ".$NPDS_Prefix."tdgal_img VALUES ('','$imgscat','$newfilename','$newtit','','0','1')")) {
                  echo '<div class="alert alert-info" role="alert"><i class="fa fa-info-circle mr-2" aria-hidden="true"></i>'.gal_translate("Photo(s) envoyée(s) à la validation du webmaster").' : '.$origin_filename.'</div>';
                  $soumission=true;
               } else {
                  echo '<div class="alert alert-danger" role="alert"><i class="fa fa-info-circle mr-2" aria-hidden="true"></i>'.gal_translate("Impossible d'ajouter l'image à la BDD").' : '.$origin_filename.'</span></div>';
                  @unlink ("modules/$ModPath/imgs/$newfilename");
                  @unlink ("modules/$ModPath/mini/$newfilename");
               }
            } else {
               echo '<div class="alert alert-danger" role="alert"><i class="fa fa-info-circle mr-2" aria-hidden="true"></i>'.$upload->errors.'</span></div>';
            }
         } else {
            if ($filename_ext!="")
               echo '<div class="alert alert-warning" role="alert"><i class="fa fa-info-circle mr-2" aria-hidden="true"></i>'.gal_translate("Ce fichier n'est pas un fichier jpg ou gif").' png : '.$origin_filename.'</span></div>';
         }
      }
      $i++;
   }

   if ($notif_admin and $soumission) {
      $subject=gal_translate("Nouvelle soumission de Photos");
      $message=gal_translate("Des photos viennent d'être proposées dans la galerie photo du site ").$nuke_url.gal_translate(" par ").$user_connecte;
      send_email($adminmail, $subject, $message, '', true, 'html');
   }
   echo '</div>';
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
?>