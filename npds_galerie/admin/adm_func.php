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
/* MAJ jpb, phr - 2017 renommé npds_galerie pour Rev 16                 */
/* v 3.2                                                                */
/************************************************************************/
include 'modules/geoloc/geoloc_conf.php';

function PrintFormCat() {
   global $ModPath, $ModStart, $NPDS_Prefix, $ThisFile;
   $num = sql_fetch_row(sql_query("SELECT COUNT(id) FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='0'"));
   echo '
      <h3 class="my-3">'.gal_translate("Catégorie").'<span class="badge badge-secondary float-right" title="'.gal_translate("Nombre de catégories").'" data-toggle="tooltip" data-placement="left">'.$num[0].'</span></h3>
      <h4 class="my-4">'.gal_translate("Ajout catégorie").'</h4>
      <hr />
      <form id="creercat" action="'.$ThisFile.'" method="post" name="FormCat">
         <input type="hidden" name="subop" value="addcat" />
         <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="newcat">'.gal_translate("Nom de la catégorie").'</label>
            <div class="col-sm-8">
               <input type="text" class="form-control" name="newcat" id="newcat" required="required" maxlength="150" />
               <span class="help-block text-right" id="countcar_newcat"></span>
            </div>
         </div>
         <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="accescat">'.gal_translate("Accès pour").'</label>
            <div class="col-sm-8">
               <select class="custom-select" id="accescat" name="accescat">';
   echo Fab_Option_Group('','tousdroits');
   echo '
               </select>
            </div>
         </div>
         <div class="form-group row">
            <div class="col-sm-8 ml-auto">
               <button class="btn btn-primary" type="submit">'.gal_translate("Ajouter").'</button>
            </div>
         </div>
      </form>';
   $arg1 = '
   var formulid = ["creercat"]
   inpandfieldlen("newcat",150);';
   adminfoot('fv','',$arg1,'1');
}

function AddACat($newcat,$accescat) {
   global $ModPath, $ModStart, $NPDS_Prefix, $ThisRedo;
   if (!empty($newcat)) {
      $newcat = addslashes(removeHack($newcat));
      if (sql_num_rows(sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='0' AND nom='$newcat'")))
         echo '<p class="lead text-warning"><i class="fa fa-info-circle mr-2"></i>'.gal_translate("Cette catégorie existe déjà").'</p>';
      else {
         if ($add = sql_query("INSERT INTO ".$NPDS_Prefix."tdgal_cat VALUES ('0','0','$newcat','$accescat')"))
            redirect_url($ThisRedo);
         else
            echo '<p class="lead text-danger"><i class="fa fa-info-circle mr-2"></i>'.gal_translate("Erreur lors de l'ajout de la catégorie").'</p>';
      }
   } else
      redirect_url($ThisRedo."&subop=formcat");
}

function PrintFormSSCat() {
   global $ModPath, $ModStart, $NPDS_Prefix, $ThisFile, $ThisRedo;
   $qnum = sql_num_rows(sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='0'"));
   if ($qnum == 0) redirect_url($ThisRedo);
   PrintJavaCodeGal('accesscat');
   $num = sql_fetch_row(sql_query("SELECT COUNT(id) FROM ".$NPDS_Prefix."tdgal_cat WHERE cid!=0"));
   echo '
   <h3 class="my-3">'.gal_translate("Sous-catégorie").'<span class="badge badge-secondary float-right" title="'.gal_translate("Nombre de sous-catégories").'" data-toggle="tooltip" data-placement="left">'.$num[0].'</span></h3>
   <h4 class="my-4">'.gal_translate("Ajout sous-catégorie").'</h4>
   <hr />
   <form id="creerscat" action="'.$ThisFile.'" method="post" name="FormCreer">
      <input type="hidden" name="subop" value="addsscat" />
      <div class="form-group row">
         <label class="col-sm-4 col-form-label" for="catparente">'.gal_translate("Catégorie parente").'</label>
         <div class="col-sm-8">
            <select class="custom-select" name="cat" id="catparente" onChange="remplirAcces(this.selectedIndex,this.options[this.selectedIndex].text);">
               <option value="none" selected="selected">'.gal_translate("Choisissez").'</option>';
   $query = sql_query("SELECT id, nom, acces FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='0' ORDER BY nom ASC");
   while ($row = sql_fetch_row($query)) {
      echo '
               <option value="'.$row[0].'">'.stripslashes($row[1]).' ('.Get_Name_Group('',$row[2]).')</option>';
   }
      echo '
            </select>
         </div>
      </div>
      <div class="form-group row">
         <label class="col-sm-4 col-form-label" for="newsscat">'.gal_translate("Nom de la sous-catégorie").' '.$row[2].'</label>
         <div class="col-sm-8">
            <input type="text" class="form-control" name="newsscat" id="newsscat" placeholder="" required="required" />
            <span class="help-block text-right" id="countcar_newsscat"></span>
         </div>
      </div>
      <div class="form-group row">
         <label class="col-sm-4 col-form-label" for="accesscat">'.gal_translate("Accès pour").'</label>
         <div class="col-sm-8">
            <select class="custom-select" id="accesscat" name="accesscat">
            </select>
         </div>
      </div>
      <div class="form-group row">
         <div class="col-sm-8 ml-auto">
            <input class="btn btn-primary" type="submit" value="'.gal_translate("Ajouter").'" />
         </div>
      </div>
   </form>';
   $arg1 = '
   var formulid = ["creerscat"]
   inpandfieldlen("newsscat",150);';
   adminfoot('fv','',$arg1,'1');

}

function AddSsCat($idparent,$newcat,$accesscat) {
   global $ModPath, $ModStart, $NPDS_Prefix, $ThisRedo;
   if (!empty($newcat)) {
      $newcat = addslashes(removeHack($newcat));
      if (sql_num_rows(sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='$idparent' AND nom='$newcat'")))
         echo '
         <div class="alert alert-danger lead">'.gal_translate("Cette sous-catégorie existe déjà").'</div>';
      else {
         if ($add = sql_query("INSERT INTO ".$NPDS_Prefix."tdgal_cat VALUES ('0','$idparent','$newcat','$accesscat')"))
            redirect_url($ThisRedo);
         else
            echo '
         <div class="alert alert-danger lead ">'.gal_translate("Erreur lors de l'ajout de la sous-catégorie").'</div>';
      }
   }
   else
      redirect_url($ThisRedo."&subop=formsscat");
}

function PrintCreerGalery() {
   global $ModPath, $ModStart, $NPDS_Prefix, $ThisFile, $ThisRedo;
   $qnum = sql_num_rows(sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_cat"));
   if ($qnum == 0)
      redirect_url($ThisRedo);
   PrintJavaCodeGal('droitacces');

   $num = sql_fetch_row(sql_query("SELECT COUNT(id) FROM ".$NPDS_Prefix."tdgal_gal"));
   $num[0] = ($num[0] -1);
   echo '
   <h3 class="my-3">'.gal_translate("Galeries").'<span class="badge badge-secondary float-right" title="'.gal_translate("Nombre de galeries").'" data-toggle="tooltip" data-placement="left">'.$num[0].'</span></h3>
   <h4>'.gal_translate("Ajout galerie").'</h4>
   <hr/>
   <form id="creergalerie" action="'.$ThisFile.'" method="post" name="FormCreer">
      <input type="hidden" name="subop" value="addsscat" />
      <input type="hidden" name="subop" value="creegal" />
      <div class="form-group row">
         <label class="col-sm-4 col-form-label" for="galcat">'.gal_translate("Catégorie").'</label>
         <div class="col-sm-8">
            <select class="custom-select" name="galcat" id="galcat" onChange="remplirAcces(this.selectedIndex,this.options[this.selectedIndex].text);" />
               <option value="none" selected="selected">'.gal_translate("Choisissez").'</option>';
   echo cat_arbo('');
   echo '
            </select>
         </div>
      </div>
      <div class="form-group row">
         <label class="col-sm-4 col-form-label" for="newgal">'.gal_translate("Nom de la galerie").' '.$row[2].'</label>
         <div class="col-sm-8">
            <input type="text" class="form-control" id="newgal" name="newgal" id="newgal" placeholder="" required="required" />
         </div>
      </div>
      <div class="form-group row">
         <label class="col-sm-4 col-form-label" for="droitacces">'.gal_translate("Accès pour").'</label>
         <div class="col-sm-8">
            <select class="custom-select" name="acces" id="droitacces">
            </select>
         </div>
      </div>
      <div class="form-group row">
         <div class="col-sm-8 ml-auto">
            <button class="btn btn-primary" type="submit">'.gal_translate("Ajouter").'</button>
         </div>
      </div>
   </form>';
}

function AddNewGal($galcat,$newgal,$acces) {
   global $ModPath, $ModStart, $gmt, $NPDS_Prefix, $ThisRedo, $acces, $ThisFile;
   if (!empty($newgal)) {
      $newgal = addslashes(removeHack($newgal));
      if (sql_num_rows(sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_gal WHERE cid='$galcat' AND nom='$newgal'")))
         echo '
      <div class="alert alert-danger lead"><i class="fa fa-info-circle mr-2"></i>'.gal_translate("Cette galerie existe déjà").'</div>';
      else {
         $regdate = time()+((integer)$gmt*3600);
         if ($add = sql_query("INSERT INTO ".$NPDS_Prefix."tdgal_gal VALUES ('0','$galcat','$newgal','$regdate','$acces')")) {
            $new_gal_id = sql_last_id();
   echo '
      <h3 class="my-3">'.gal_translate("Images").'</h3>
      <h4>'.gal_translate("Ajouter des photos à cette nouvelle galerie").'</h4>
      <hr />
      <div class="row">
         <div class="col-md-6">
            <form enctype="multipart/form-data" method="post" action="'.$ThisFile.'" id="formimgs" name="FormImgs" lang="'.language_iso(1,'','').'">
            <input type="hidden" name="subop" value="addimgs" />
            <input type="hidden" name="imggal" value="'.$new_gal_id.'" />';
   $i=1;
   do {
      echo '
            <div class="form-group mb-0">
               <label class="font-weight-bolder">'.gal_translate("Image").' '.$i.'</label>
               <div class="input-group mb-2 mr-sm-2">
                  <div class="input-group-prepend" onclick="reset2($(\'#newcard'.$i.'\'),'.$i.');">
                     <div class="input-group-text"><i class="fas fa-redo-alt"></i></div>
                  </div>
               <div class="custom-file">
                  <input type="file" class="custom-file-input" name="newcard'.$i.'" id="newcard'.$i.'" />
                  <label id="lab'.$i.'" class="custom-file-label" for="newcard'.$i.'">'.gal_translate("Sélectionner votre image").'</label>
               </div>
            </div>
         <div class="form-group mb-2">
            <label class="sr-only" for="newdesc'.$i.'">'.gal_translate("Description").'</label>
            <input type="text" class="form-control" id="newdesc'.$i.'" name="newdesc[]" placeholder="'.gal_translate("Description").'">
         </div>
         <div class="form-row">
            <div class="form-group col-md-6 mb-0">
               <label for="imglat'.$i.'" class="sr-only">'.gal_translate("Latitude").'</label>
               <div class="input-group mb-2 mr-sm-2">
                  <div class="input-group-prepend">
                     <div class="input-group-text jsgeo'.$i.' jsgeolat" title="'.gal_translate("Latitude").'" data-toggle="tooltip"><i class="fa fa-globe fa-lg"></i></div>
                  </div>
                  <input type="text" class="form-control js-lat" name="imglat[]" id="imglat'.$i.'" placeholder="'.gal_translate("Latitude").'" />
               </div>
            </div>
             <div class="form-group col-md-6 mb-0">
               <label for="imglong'.$i.'" class="sr-only">'.gal_translate("Longitude").'</label>
               <div class="input-group mb-2 mr-sm-2">
                  <div class="input-group-prepend">
                     <div class="input-group-text jsgeo'.$i.' jsgeolon" title="'.gal_translate("Longitude").'" data-toggle="tooltip"><i class="fa fa-globe fa-lg"></i></div>
                  </div>
                  <input type="text" class="form-control js-long" name="imglong[]" id="imglong'.$i.'" placeholder="'.gal_translate("Longitude").'"/>
               </div>
            </div>
         </div>
      </div>';
      $i++;
   }
   while($i<=5);
   echo '
      <div class="form-group mt-2">
         <button class="btn btn-primary" type="submit">'.gal_translate("Ajouter").'</button>
      </div>
   </form>
   </div>
   <div class="col-md-6 align-self-center">
        '.img_geolocalisation('0','0','1').'
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
   
   
         } else
            echo '<div class="alert alert-danger">'.gal_translate("Erreur lors de l'ajout de la galerie").'</div>';
      }
   } else
      redirect_url($ThisRedo."&subop=formcregal");
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
            if ($rowX_gal[0] == $sel) { $IsSelected = ' selected'; } else { $IsSelected = ''; }
            $ibid.='<option value="'.$rowX_gal[0].'"'.$IsSelected.'>'.stripslashes($rowX_gal[1]).' </option>';
         } // Fin Galerie Catégorie

         // SOUS-CATEGORIE
         $query = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='".$row_cat[0]."' ORDER BY nom ASC");
         while ($row_sscat = sql_fetch_row($query)) {
            $ibid.='<optgroup label="&nbsp;&nbsp;'.stripslashes($row_sscat[2]).'">';
            $querx = sql_query("SELECT id, nom FROM ".$NPDS_Prefix."tdgal_gal WHERE cid='".$row_sscat[0]."' ORDER BY nom ASC");
            while ($row_gal = sql_fetch_row($querx)) {
               if ($row_gal[0] == $sel) $IsSelected = ' selected="selected"'; else $IsSelected = '';
               $ibid.='<option value="'.$row_gal[0].'"'.$IsSelected.'>'.stripslashes($row_gal[1]).' </option>';
            } // Fin Galerie Sous Catégorie
            $ibid.='</optgroup>';
         } // Fin Sous Catégorie
         $ibid.='</optgroup>';
      } // Fin Catégorie
   }
   return ($ibid);
}

function cat_arbo($sel) {
   global $NPDS_Prefix;
   $ibid='';
   $queryX = sql_query("SELECT id,nom,acces FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='0' ORDER BY nom ASC");
   while ($rowX = sql_fetch_row($queryX)) {
      if ($sel==$rowX[0]) $selected='selected="selected"'; else $selected='';
      $ibid.='<option value="'.$rowX[0].'" '.$selected.'>'.stripslashes($rowX[1]).' ('.Get_Name_Group("",$rowX[2]).')</option>';
      $queryY = sql_query("SELECT id,nom,acces FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='".$rowX[0]."' ORDER BY nom ASC");
      while ($rowY = sql_fetch_row($queryY)) {
         if ($sel==$rowY[0]) $selected='selected="selected"'; else $selected='';
         $ibid.='<option value="'.$rowY[0].'" '.$selected.'>&nbsp;&nbsp;'.stripslashes($rowY[1]).' ('.Get_Name_Group("",$rowY[2]).')</option>';
      }
   }
   return ($ibid);
}

function PrintFormImgs() {
   global $ModPath, $ModStart, $NPDS_Prefix, $ThisFile, $ThisRedo;

   $qnum = sql_num_rows(sql_query("SELECT id FROM ".$NPDS_Prefix."tdgal_cat"));
   if ($qnum == 0)
      redirect_url($ThisRedo);
   echo '
   <h3 class="my-3">'.gal_translate("Images").'</h3>
   <h4>'.gal_translate("Ajout images").'</h4>
   <hr />
   <div class="row">
      <div class="col-md-6">
         <form enctype="multipart/form-data" method="post" action="'.$ThisFile.'" id="formimgs" name="FormImgs" lang="'.language_iso(1,'','').'">
            <input type="hidden" name="subop" value="addimgs">
            <div class="form-group">
               <label class="w-100 font-weight-bolder" for="imggal">'.gal_translate("Affectation vers la galerie choisie.").'</label>
               <div class="">
                  <select name="imggal" id="imggal" class="custom-select">';
   echo select_arbo('');
   echo '
                  </select>
                  <span class="help-block">'.gal_translate("Sélectionner une galerie").'</span>
               </div>
            </div>';
   $i=1;
   do {
      echo '
            <div class="form-group mb-0">
               <label class="font-weight-bolder">'.gal_translate("Image").' '.$i.'</label>
               <div class="input-group mb-2 mr-sm-2">
                  <div class="input-group-prepend" onclick="reset2($(\'#newcard'.$i.'\'),'.$i.');">
                     <div class="input-group-text"><i class="fas fa-redo-alt"></i></div>
                  </div>
                  <div class="custom-file">
                     <input type="file" class="custom-file-input" name="newcard'.$i.'" id="newcard'.$i.'" />
                     <label id="lab'.$i.'" class="custom-file-label" for="newcard'.$i.'">'.gal_translate("Sélectionner votre image").'</label>
                  </div>
               </div>
               <div class="form-group mb-2">
                  <label class="sr-only" for="newdesc'.$i.'">'.gal_translate("Description").'</label>
                  <input type="text" class="form-control" id="newdesc'.$i.'" name="newdesc[]" placeholder="'.gal_translate("Description").'">
               </div>
               <div class="form-row">
                  <div class="form-group col-md-6 mb-0">
                     <label for="imglat'.$i.'" class="sr-only">'.gal_translate("Latitude").'</label>
                     <div class="input-group mb-2 mr-sm-2">
                        <div class="input-group-prepend">
                           <div class="input-group-text jsgeo'.$i.' jsgeolat" title="'.gal_translate("Latitude").'" data-toggle="tooltip"><i class="fa fa-globe fa-lg"></i></div>
                        </div>
                        <input type="text" class="form-control js-lat" name="imglat[]" id="imglat'.$i.'" placeholder="'.gal_translate("Latitude").'" />
                     </div>
                  </div>
                   <div class="form-group col-md-6 mb-0">
                     <label for="imglong'.$i.'" class="sr-only">'.gal_translate("Longitude").'</label>
                     <div class="input-group mb-2 mr-sm-2">
                        <div class="input-group-prepend">
                           <div class="input-group-text jsgeo'.$i.' jsgeolon" title="'.gal_translate("Longitude").'" data-toggle="tooltip"><i class="fa fa-globe fa-lg"></i></div>
                        </div>
                        <input type="text" class="form-control js-long" name="imglong[]" id="imglong'.$i.'" placeholder="'.gal_translate("Longitude").'"/>
                     </div>
                  </div>
               </div>
            </div>';
      $i++;
   }
   while($i<=5);
   echo '
            <div class="form-group mt-2">
               <button class="btn btn-primary" type="submit">'.gal_translate("Ajouter").'</button>
            </div>
         </form>
      </div>
      <div class="col-md-6 align-self-center">
        '.img_geolocalisation('0','0','1').'
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

/*******************************************************/
function AddImgs($imggal,$newcard1,$newdesc,$imglat,$imglong,$newcard2,$newcard3,$newcard4,$newcard5) {
   global $language, $MaxSizeImg, $MaxSizeThumb, $ModPath, $ModStart, $NPDS_Prefix;
   include_once("modules/upload/lang/upload.lang-$language.php");
   include_once("modules/upload/clsUpload.php");

   $year = date("Y"); $month = date("m"); $day = date("d");
   $hour = date("H"); $min = date("i"); $sec = date("s");

   $i=1;
   while($i <= 5) {
      $img = "newcard$i";
      $tit = $newdesc[$i-1];
      $lat = $imglat[$i-1];
      $long = $imglong[$i-1];
      
      if (!empty($$img)) {
         $newimg = stripslashes(removeHack($$img));
         $newtit = !empty($newdesc[$i-1]) ? addslashes(removeHack($newdesc[$i-1])) : '';
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
                  echo '<ul class="list-group">';
               if (sql_query("INSERT INTO ".$NPDS_Prefix."tdgal_img VALUES ('0','$imggal','$newfilename','$newtit','0','0','0','$lat','$long')")) {
                  echo '<li class="list-group-item list-group-item-success"><i class="fa fa-info-circle mr-2"></i>'.gal_translate("Image ajoutée avec succès").'</li>';
               } else {
                  echo '<li class="list-group-item list-group-item-danger"><i class="fa fa-info-circle mr-2"></i>'.gal_translate("Impossible d'ajouter l'image en BDD").'</li>';
                  @unlink ("modules/$ModPath/imgs/$newfilename");
                  @unlink ("modules/$ModPath/mini/$newfilename");
               }
            } else {
               echo '<li class="list-group-item list-group-item-danger"><i class="fa fa-info-circle mr-2"></i>'.$upload->errors.'</li>';
            }
         } else {
            if ($filename_ext!="")
               echo '<li class="list-group-item list-group-item-danger"><i class="fa fa-info-circle mr-2"></i>'.gal_translate("Ce fichier n'est pas un fichier jpg, gif ou png").'</li>';
         }
         echo '</ul>';
      }
      $i++;
   }
}

function PrintFormConfig() {
   global $ModPath, $ModStart, $ThisFile, $MaxSizeImg, $MaxSizeThumb, $imgpage, $nbtopcomment, $nbtopvote, $view_alea, $view_last, $vote_anon, $comm_anon, $post_anon, $aff_vote, $aff_comm, $notif_admin;

   echo '
   <h3 class="mt-3"><i class="fa fa-cogs mr-2" aria-hidden="true"></i>'.gal_translate("Configuration").'</h3>
   <hr />
   <form id="formconfig" action="'.$ThisFile.'" method="post" name="FormConfig">
      <input type="hidden" name="subop" value="wrtconfig" />
      <fieldset disabled>
      <div class="form-group row">
         <label class="col-sm-8 col-form-label" for="maxszimg">'.gal_translate("Dimension maximale de l'image en pixels").'&nbsp;(1024px Max)</label>
         <div class="col-sm-4">
            <input type="text" class="form-control" id="maxszimg" name="maxszimg"  value="'.$MaxSizeImg.'" placeholder="" />
         </div>
      </div>
      <div class="form-group row">
         <label class="col-sm-8 col-form-label" for="maxszthb">'.gal_translate("Dimension maximale de la miniature en pixels").'&nbsp;(240px Max)</label>
         <div class="col-sm-4">
            <input type="text" class="form-control" id="maxszthb" name="maxszthb"  value="'.$MaxSizeThumb.'" placeholder="" />
         </div>
      </div>
      </fieldset>
      <div class="form-group row">
         <label class="col-sm-8 col-form-label" for="nbimpg">'.gal_translate("Nombre d'images par page").'</label>
         <div class="col-sm-4">
            <input type="text" class="form-control" id="nbimpg" name="nbimpg" value="'.$imgpage.'" placeholder="8" maxlength="2" required="required" />
            <span class="help-block text-right" id="countcar_nbimpg"></span>
         </div>
      </div>
      <div class="form-group row">
         <label class="col-sm-8 col-form-label" for="nbimcomment">'.gal_translate("Nombre d'images à afficher dans le top commentaires").'</label>
         <div class="col-sm-4">
            <input type="text" class="form-control" id="nbimcomment" name="nbimcomment"  value="'.$nbtopcomment.'" placeholder="10" maxlength="2" required="required" />
            <span class="help-block text-right" id="countcar_nbimcomment"></span>
         </div>
      </div>
      <div class="form-group row">
         <label class="col-sm-8 col-form-label" for="nbimvote">'.gal_translate("Nombre d'images à afficher dans le top votes").'</label>
         <div class="col-sm-4">
            <input type="text" class="form-control" id="nbimvote" name="nbimvote" value="'.$nbtopvote.'" placeholder="10" maxlength="2" required="required" />
            <span class="help-block text-right" id="countcar_nbimvote"></span>
         </div>
      </div>';

   if ($view_alea) { $rad1 = ' checked="checked"'; $rad2 = ''; } else { $rad1 = ''; $rad2 = ' checked="checked"'; }
   echo '
      <div class="form-group row">
         <label class="col-sm-8 col-form-label">'.gal_translate("Afficher des photos aléatoires ?").'</label>
         <div class="col-sm-4 my-2">
            <div class="custom-control custom-radio custom-control-inline">
               <input class="custom-control-input" type="radio" id="viewalea_y" name="viewalea" value="true"'.$rad1.' />
               <label class="custom-control-label" for="viewalea_y">'.adm_translate("Oui").'</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
               <input class="custom-control-input" type="radio" id="viewalea_n" name="viewalea" value="false"'.$rad2.' />
               <label class="custom-control-label" for="viewalea_n">'.adm_translate("Non").'</label>
            </div>
         </div>
      </div>';
   if ($view_last) { $rad1 = ' checked="checked"'; $rad2 = ''; } else { $rad1 = ''; ' checked="checked"'; }
   echo '
      <div class="form-group row">
         <label class="col-sm-8 col-form-label">'.gal_translate("Afficher les derniers ajouts ?").'</label>
         <div class="col-sm-4 my-2">
            <div class="custom-control custom-radio custom-control-inline">
               <input class="custom-control-input" type="radio" id="viewlast_y" name="viewlast" value="true"'.$rad1.' />
               <label class="custom-control-label" for="viewlast_y">'.adm_translate("Oui").'</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
               <input class="custom-control-input" type="radio" id="viewlast_n" name="viewlast" value="false"'.$rad2.' />
               <label class="custom-control-label" for="viewlast_n">'.adm_translate("Non").'</label>
            </div>
         </div>
      </div>';
   if ($aff_vote) { $rad1 = ' checked="checked"'; $rad2 = ''; } else { $rad1 = ''; $rad2 = ' checked="checked"'; }
   echo '
      <div class="form-group row">
         <label class="col-sm-8 col-form-label">'.gal_translate("Afficher les votes ?").'</label>
         <div class="col-sm-4 my-2">
            <div class="custom-control custom-radio custom-control-inline">
               <input class="custom-control-input" type="radio" id="votegal_y" name="votegal" value="true"'.$rad1.' />
               <label class="custom-control-label" for="votegal_y">'.adm_translate("Oui").'</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
               <input class="custom-control-input" type="radio" id="votegal_n" name="votegal" value="false"'.$rad2.' />
               <label class="custom-control-label" for="votegal_n">'.adm_translate("Non").'</label>
            </div>
         </div>
      </div>';
   if ($aff_comm) { $rad1 = ' checked="checked"'; $rad2 = ''; } else { $rad1 = ''; $rad2 = ' checked="checked"'; }
   echo '
      <div class="form-group row">
         <label class="col-sm-8 col-form-label">'.gal_translate("Afficher les commentaires ?").'</label>
         <div class="col-sm-4 my-2">
            <div class="custom-control custom-radio custom-control-inline">
               <input class="custom-control-input" type="radio" id="commgal_y" name="commgal" value="true"'.$rad1.' />
               <label class="custom-control-label" for="commgal_y">'.adm_translate("Oui").'</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
               <input class="custom-control-input" type="radio" id="commgal_n" name="commgal" value="false"'.$rad2.' />
               <label class="custom-control-label" for="commgal_n">'.adm_translate("Non").'</label>
            </div>
         </div>
      </div>';
   if ($vote_anon) { $rad1 = ' checked="checked"'; $rad2 = ''; } else { $rad1 = ''; $rad2 = ' checked="checked"'; }
   echo '
      <div class="form-group row">
         <label class="col-sm-8 col-form-label">'.gal_translate("Les anonymes peuvent voter ?").'</label>
         <div class="col-sm-4 my-2">
            <div class="custom-control custom-radio custom-control-inline">
               <input class="custom-control-input" type="radio" id="votano_y" name="votano" value="true"'.$rad1.' />
               <label class="custom-control-label" for="votano_y">'.adm_translate("Oui").'</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
               <input class="custom-control-input" type="radio" id="votano_n" name="votano" value="false"'.$rad2.' />
               <label class="custom-control-label" for="votano_n">'.adm_translate("Non").'</label>
            </div>
         </div>
      </div>';
   if ($comm_anon) { $rad1 = ' checked="checked"'; $rad2 = ''; } else { $rad1 = ''; $rad2 = ' checked="checked"'; }
   echo '
      <div class="form-group row">
         <label class="col-sm-8 col-form-label">'.gal_translate("Les anonymes peuvent poster un commentaire ?").'</label>
         <div class="col-sm-4 my-2">
            <div class="custom-control custom-radio custom-control-inline">
               <input class="custom-control-input" type="radio" id="comano_y" name="comano" value="true"'.$rad1.' />
               <label class="custom-control-label" for="comano_y">'.adm_translate("Oui").'</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
               <input class="custom-control-input" type="radio" id="comano_n" name="comano" value="false"'.$rad2.'>
               <label class="custom-control-label" for="comano_n">'.adm_translate("Non").'</label>
            </div>
         </div>
      </div>';
   if ($post_anon) { $rad1 = ' checked="checked"'; $rad2 = ''; } else { $rad1 = ''; $rad2 = ' checked="checked"'; }
   echo '
      <div class="form-group row">
         <label class="col-sm-8 col-form-label">'.gal_translate("Les anonymes peuvent envoyer des E-Cartes ?").'</label>
         <div class="col-sm-4 my-2">
            <div class="custom-control custom-radio custom-control-inline">
               <input class="custom-control-input" type="radio" id="postano_y" name="postano" value="true"'.$rad1.' />
               <label class="custom-control-label" for="postano_y">'.adm_translate("Oui").'</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
               <input class="custom-control-input" type="radio" id="postano_n" name="postano" value="false"'.$rad2.' />
               <label class="custom-control-label" for="postano_n">'.adm_translate("Non").'</label>
            </div>
         </div>
      </div>';
   if ($notif_admin) { $rad1 = ' checked="checked"'; $rad2 = ''; } else { $rad1 = ''; $rad2 = ' checked="checked"'; }
   echo '
      <div class="form-group row">
         <label class="col-sm-8 col-form-label">'.gal_translate("Notifier par email l'administrateur de la proposition de photos ?").'</label>
         <div class="col-sm-4 my-2">
            <div class="custom-control custom-radio custom-control-inline">
               <input class="custom-control-input" type="radio" id="notifadmin_y" name="notifadmin" value="true"'.$rad1.' />
               <label class="custom-control-label" for="notifadmin_y">'.adm_translate("Oui").'</span>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
               <input class="custom-control-input" type="radio" id="notifadmin_n" name="notifadmin" value="false"'.$rad2.' />
               <label class="custom-control-label" for="notifadmin_n">'.adm_translate("Non").'</span>
            </div>
         </div>
      </div>
      <button class="btn btn-primary" type="submit">'.gal_translate("Valider").'</button>
      </form>';
   $fv_parametres = '
      nbimpg: {
         validators: {
            regexp: {
               regexp:/^\d{1,2}$/,
               message: "0-9"
            },
            between: {
               min: 1,
               max: 50,
               message: "1 ... 50"
            }
         }
      },
      nbimvote: {
         validators: {
            regexp: {
               regexp:/^\d{1,2}$/,
               message: "0-9"
            },
            between: {
               min: 1,
               max: 50,
               message: "1 ... 50"
            }
         }
      },
      nbimcomment: {
         validators: {
            regexp: {
               regexp:/^\d{1,2}$/,
               message: "0-9"
            },
            between: {
               min: 1,
               max: 50,
               message: "1 ... 50"
            }
         }
      },';
   $arg1='
   var formulid = ["formconfig"];
   inpandfieldlen("nbimpg",2);
   inpandfieldlen("nbimvote",2);
   inpandfieldlen("nbimcomment",2);';
   adminfoot('fv',$fv_parametres,$arg1,'no');
}

function WriteConfig($maxszimg,$maxszthb,$nbimpg,$nbimcomment,$nbimvote,$viewalea,$viewlast,$vote,$comm,$votano,$comano,$postano,$notifadmin) {
   global $ModPath, $ModStart, $ThisRedo;

   if (!is_integer($maxszimg) && ($maxszimg > 1024)) {
      $msg_erreur = gal_translate("Dimension maximale de l'image incorrecte");
      $erreur=true;
   }
   if (!is_integer($maxszthb) && ($maxszthb > 300) && !isset($erreur)) {
      $msg_erreur = gal_translate("Dimension maximale de la miniature incorrecte");
      $erreur=true;
   }
   if (isset($erreur)) {
      echo '<p class="lead text-danger">'.$msg_erreur.'</p>';
      exit;
   }
   
   $filename = "modules/".$ModPath."/gal_conf.php";
   $content = "<?php\n";
   $content.= "/************************************************************************/\n";
   $content.= "/* DUNE by NPDS                                                         */\n";
   $content.= "/*                                                                      */\n";
   $content.= "/* NPDS Copyright (c) 2002-".date('Y')." by Philippe Brunier                     */\n";
   $content.= "/*                                                                      */\n";
   $content.= "/* This program is free software. You can redistribute it and/or modify */\n";
   $content.= "/* it under the terms of the GNU General Public License as published by */\n";
   $content.= "/* the Free Software Foundation; either version 2 of the License.       */\n";
   $content.= "/* Module de gestion de galeries pour NPDS                              */\n";
   $content.= "/*                                                                      */\n";
   $content.= "/* (c) 2004-2005 Tribal-Dolphin - http://www.tribal-dolphin.net         */\n";
   $content.= "/* (c) 2007 Xgonin, Lopez - http://modules.npds.org                     */\n";
   $content.= "/* MAJ conformité XHTML pour REvolution 10.02 par jpb/phr en mars 2010  */\n";
   $content.= "/* MAJ Dev - 2011                                                       */\n";
   $content.= "/* MAJ jpb, phr - 2017 renommé npds_galerie pour Rev 16                 */\n";
   $content.= "/* v 3.2                                                                */\n";
   $content.= "/************************************************************************/\n\n";
   $content.= "// Dimension max des images\n";
//   $content.= "\$MaxSizeImg = ".$maxszimg.";\n\n";
   $content.= "\$MaxSizeImg = 1000;\n\n";
   $content.= "// Dimension max des images miniatures\n";
//   $content.= "\$MaxSizeThumb = ".$maxszthb.";\n\n";
   $content.= "\$MaxSizeThumb = 300;\n\n";
   $content.= "// Nombre de photos par page\n";
   $content.= "\$imgpage = ".$nbimpg.";\n\n";
   $content.= "// Nombre d'images à afficher dans le top commentaires\n";
   if (!$nbimcomment) $nbimcomment=5;
   $content.= "\$nbtopcomment = ".$nbimcomment.";\n\n";
   $content.= "// Nombre d'images à afficher dans le top votes\n";
   if (!$nbimvote) $nbimvote=5;
   $content.= "\$nbtopvote = ".$nbimvote.";\n\n";
   $content.= "// Personnalisation de l'affichage\n";
   $content.= "\$view_alea = ".$viewalea.";\n";
   $content.= "\$view_last = ".$viewlast.";\n";
   $content.= "\$aff_vote = ".$vote.";\n";
   $content.= "\$aff_comm = ".$comm.";\n\n";
   $content.= "// Autorisations pour les anonymes\n";
   $content.= "\$vote_anon = ".$votano.";\n";
   $content.= "\$comm_anon = ".$comano.";\n";
   $content.= "\$post_anon = ".$postano.";\n\n";
   $content.= "// Notification admin par email de la proposition\n";
   $content.= "\$notif_admin = ".$notifadmin.";\n\n";
   $content.= "// Version du module\n";
   $content.= "\$npds_gal_version = \"V 3.2\";\n";
   $content.= "?>";
     
   if ($myfile = fopen("$filename", "wb")) {
      fwrite($myfile, "$content");
      fclose($myfile);
      unset($content);
      redirect_url($ThisRedo);
   } else
      redirect_url($ThisRedo."&subop=config");
}

function PrintArbo() {
   global $ModPath, $ModStart, $ThisFile, $NPDS_Prefix;
   echo '
   <script type="text/javascript">
   //<![CDATA[
      function aff_image(img_id, img_src) {
         var image_open = new Image();
         image_open.src = img_src;
         var image_closed = new Image();
         image_closed.src = "modules/'.$ModPath.'/data/img.png";
         if (document.all) {
            if (document.all[img_id].src == image_closed.src) {
               document.all[img_id].src = image_open.src;
            } else {
               document.all[img_id].src = image_closed.src;
            }
        } else {
           if (document.getElementById(img_id).src == image_closed.src) {
              document.getElementById(img_id).src = image_open.src;
           } else {
               document.getElementById(img_id).src = image_closed.src;
            }
         }
      }
      $(document).ready(function(){ 
         $("#ckballd").change(function(){
            $(".ckd").prop("checked", $(this).prop("checked"));
         });
         $("#ckballv").change(function(){
            $(".ckv").prop("checked", $(this).prop("checked"));
         });
      });
   //]]>
   </script>';

   $queryZ = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='-1' ORDER BY id");
   $nb_img = sql_num_rows($queryZ);
   $j=0;$i=0; $affgaltemp=''; $img_geotag='';
   if ($nb_img == 0)
      $affgaltemp.= '<p class="card-text"><i class="fa fa-info-circle mr-2"></i>'.gal_translate("Vide").'</p>';
   else {
      $affgaltemp.= '
      <div class="custom-control custom-checkbox d-inline">
         <input type="checkbox" class="custom-control-input is-invalid" id="ckballd" />
         <label class="custom-control-label" for="ckballd"><i class="fas fa-trash fa-lg text-danger align-middle"></i></label>
      </div>';
//      if($j!=0)
         $affgaltemp.= '
      <div class="custom-control custom-checkbox d-inline ml-3">
         <input type="checkbox" class="custom-control-input" id="ckballv" />
         <label class="custom-control-label" for="ckballv"><i class="fas fa-check fa-lg text-primary align-middle"></i></label>
      </div>';
      $affgaltemp.= '
      <hr class="my-2" />
      <div class="row px-3">';
      while ($rowZ_img = sql_fetch_row($queryZ)) {
         if ($rowZ_img[6]==1)  {$cla=' alert-danger '; $j++;} else $cla='alert-secondary';
         if (($rowZ_img[7] != '') and ($rowZ_img[8] != ''))
            $img_geotag = '<img class="geotag tooltipbyclass float-left mt-1" src="modules/'.$ModPath.'/data/geotag_16.png" title="'.gal_translate("Image géoréférencée").'" alt="'.gal_translate("Image géoréférencée").'" loading="lazy" />';
         else
            $img_geotag ='';
         $affgaltemp.= '
            <div class="col-lg-3 col-sm-4 border rounded p-1 my-2 '.$cla.'">
               <div class="custom-control custom-checkbox d-inline">
                  <input form="delbatch-1" type="checkbox" class="custom-control-input is-invalid ckd" id="del_'.$i.'" name="imgids[]" value="'.$rowZ_img[0].'" />
                  <label class="custom-control-label" for="del_'.$i.'"></label>
               </div>';
         if ($rowZ_img[6]==1)
            $affgaltemp.= '
               <div class="custom-control custom-checkbox d-inline">
                  <input form="valbatch-1" type="checkbox" class="custom-control-input ckv" id="val_'.$i.'" name="imgidsv[]" value="'.$rowZ_img[0].'" />
                  <label class="custom-control-label" for="val_'.$i.'"><i class="fas fa-check text-primary align-middle"></i></label>
               </div>';
         $affgaltemp.= '
               <button class="btn" type="button" data-toggle="modal" data-target="#modal_'.$i.'">
                  <div class="text-center">
                     <img class="img-fluid rounded mb-1 tooltipbyclass" src="modules/'.$ModPath.'/mini/'.$rowZ_img[2].'" alt="'.$rowZ_img[3].'" data-placement="top" title="'.$rowZ_img[3].'" loading="lazy" /><br />
                     '.$img_geotag.'<small class="small">'.$rowZ_img[2].'</small>
                  </div>
               </button>
               <div class="mt-2">
                  '.stripslashes($rowZ_img[3]).'
               </div>
               <div class="text-center mt-3">';
         if ($rowZ_img[6]==1)
            $affgaltemp.= '
                  <a class="btn btn-sm btn-link" href="'.$ThisFile.'&amp;subop=validimg&amp;imgid='.$rowZ_img[0].'"><i class="fa fa-check fa-2x align-middle" title="'.gal_translate("Valider").'" data-toggle="tooltip"></i></a>';
         else
            $affgaltemp.= '
                  <a class="btn btn-sm btn-link" href="'.$ThisFile.'&amp;subop=editimg&amp;imgid='.$rowZ_img[0].'"><i class="fa fa-edit fa-2x align-middle" title="'.gal_translate("Editer").'" data-toggle="tooltip"></i></a>';
         $affgaltemp.= '
                  <a class="btn btn-sm" href="'.$ThisFile.'&amp;subop=delimg&amp;imgid='.$rowZ_img[0].'"><i class="fas fa-trash fa-2x text-danger align-middle" title="'.gal_translate("Effacer").'" data-toggle="tooltip"></i></a>
               </div>
            </div>
            <div class="modal fade" id="modal_'.$i.'" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="modal_'.$i.'">
               <div class="modal-dialog" role="document">
                  <div class="modal-content">
                     <img class="img-fluid card-img-top" src="modules/'.$ModPath.'/imgs/'.$rowZ_img[2].'" alt="'.$rowZ_img[3].'" loading="lazy" />
                  </div>
               </div>
            </div>';
         $i++;
     }
     $affgaltemp.= '
         </div>
         <hr class="my-2" />
         <form class="d-inline" action="'.$ThisFile.'&amp;subop=delimgbatch" method="post" id="delbatch-1">
            <button class="btn btn-danger form-check btn-sm mt-2" type="submit"><i class="fa fa-check-square mr-1"></i><i class="fas fa-trash mr-2"></i>'.gal_translate("Effacer").'</button>
         </form>';
      if($j>0) 
         $affgaltemp.= '
         <form class="d-inline ml-2" action="'.$ThisFile.'&amp;subop=valimgbatch" method="post" id="valbatch-1">
            <button class="btn btn-primary form-check btn-sm mt-2" type="submit"><i class="fa fa-check-square mr-1"></i><i class="fa fa-check mr-2"></i>'.gal_translate("Valider").'</button>
         </form>';
   }
   echo '
   <div class="blockquote lead">
      <span class="badge badge-pill badge-dark mr-2">&nbsp;</span>'.gal_translate("Nombre de sous-catégories").'<br />
      <span class="badge badge-pill badge-secondary mr-2">&nbsp;</span>'.gal_translate("Nombre de galeries").'<br />
      <span class="badge badge-pill badge-success mr-2">&nbsp;</span>'.gal_translate("Nombre d'images").'<br />
      <span class="badge badge-pill badge-danger mr-2">&nbsp;</span>'.gal_translate("Nombre d'images à valider").'
   </div>
   <div class="card mb-3">
      <div class="card-body">
         <h5 class="mb-0">
            <a data-toggle="collapse" href="#gt" aria-expanded="false" aria-checks="gt">
            <i class="toggle-icon fa fa-caret-down fa-lg mr-2" data-toggle="tooltip" data-placement="top" title="'.gal_translate("Cliquer pour déplier").'"></i></a>Galerie temporaire';
   if(($nb_img-$j)>0)
      echo '
            <span class="float-right"><span class="badge badge-success badge-pill" title="'.gal_translate("Nombre d'images").'" data-toggle="tooltip" data-placement="left">'.($nb_img-$j).'</span>';
   if($j>0)
      echo '
            <a href="#gt" data-toggle="collapse" class="badge badge-danger badge-pill ml-2 tooltipbyclass" title="'.gal_translate("Nombre d'images à valider").'" data-placement="left">'.$j.'</a>';
   echo '
         </h5>
      </div>
      <div class="card-body pt-0 collapse" id="gt">
         '.$affgaltemp.'
      </div>
   </div>';
   // <== galerie temporaire

   $sql_cat = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='0' ORDER BY nom ASC");
   $num_cat = sql_num_rows($sql_cat);
   if ($num_cat == 0)
      echo '<div class="alert alert-danger lead">'.gal_translate("Aucune catégorie trouvée").'</div>';
   else {
      //==> CATEGORIE
      $icondroits=array(
      '<i class="ml-2 fa fa-user-cog fa-lg tooltipbyclass" data-html="true" title="'.gal_translate("Accès pour").'<br />'.gal_translate("Administrateurs").'"></i>',
      '<i class="ml-2 fa fa-user-check fa-lg tooltipbyclass" data-html="true" title="'.gal_translate("Accès pour").'<br />'.adm_translate("Utilisateur enregistré").'"></i>',
      '<i class="ml-2 fa fa-user fa-lg tooltipbyclass" data-html="true" title="'.gal_translate("Accès pour").'<br />'.adm_translate("Public").'"></i>',
      '<i class="ml-2 fa fa-users fa-lg tooltipbyclass" data-html="true" title="'.gal_translate("Accès pour").'<br />'.adm_translate("Groupe").'"></i>');
      while ($row_cat = sql_fetch_row($sql_cat)) {
         $queryX = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_gal WHERE cid='".$row_cat[0]."' ORDER BY nom ASC");
         $n_gc = sql_num_rows($queryX);
         $queryS = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='".$row_cat[0]."' ORDER BY nom ASC");
         $n_sc = sql_num_rows($queryS);
         $affcatgal=''; $affsoucat='';
         $tn_ig=0; $tn_ivgc=0; $tn_igscs=0;
         while ($rowX_gal = sql_fetch_row($queryX)) {
            $queryZ = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='".$rowX_gal[0]."' ORDER BY ordre,id,noaff");
            $n_ivgc= sql_num_rows(sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='".$rowX_gal[0]."' AND noaff=1"));
            $tn_ivgc += $n_ivgc;
            $n_ig = sql_num_rows($queryZ);
            $tn_ig += ($n_ig-$n_ivgc);
            $icondroit='';
            switch ($rowX_gal[4]) {
               case -127: $icondroit=$icondroits[0]; break;
               case 1: $icondroit=$icondroits[1]; break;
               case 0: $icondroit=$icondroits[2]; break;
               case $rowX_gal[4]>1: $icondroit=$icondroits[3]; break;
            }
            $affcatgal .= '
            <hr class="mt-0" />
            <h5 class="mx-3 mb-3 lead">';
            if($n_ig>0)
               $affcatgal .= '
               <a class="ml-3" data-toggle="collapse" href="#galcat'.$rowX_gal[0].'" aria-expanded="false" aria-checks="galcat'.$rowX_gal[0].'">
               <i class="toggle-icon fa fa-caret-down fa-lg mr-2" data-toggle="tooltip" data-placement="top" title="'.gal_translate("Cliquer pour déplier").'"></i></a>';
            else
               $affcatgal .= '
               <a class="ml-2"><i class=" mr-2 "></i></a>';
            $affcatgal .= stripslashes($rowX_gal[2]).' <small>( '.gal_translate("Galerie").' '.$icondroit.' )</small>';
            if(($n_ig-$n_ivgc)>0)
               $affcatgal .= '<span class="badge badge-success badge-pill ml-2" title="'.gal_translate("Nombre d'images").'" data-toggle="tooltip" data-placement="right">'.($n_ig-$n_ivgc).'</span>';
            if($n_ivgc)
               $affcatgal .='
               <a href="#galcat'.$rowX_gal[0].'" data-toggle="collapse" class="badge badge-danger badge-pill ml-2 tooltipbyclass" title="'.gal_translate("Nombre d'images à valider").'" data-toggle="tooltip" data-placement="right">'.$n_ivgc.'</a>';
            $affcatgal .='
               <span class="float-right mr-3">
                  <a href="'.$ThisFile.'&amp;subop=editgal&amp;galid='.$rowX_gal[0].'"><i class="fa fa-edit align-middle" title="'.gal_translate("Editer").' '.gal_translate("Galerie").'" data-toggle="tooltip"></i></a>
                  <a class="" href="'.$ThisFile.'&amp;subop=delgal&amp;galid='.$rowX_gal[0].'"><i class="fas fa-trash text-danger ml-3 align-middle" title="'.gal_translate("Effacer").' '.gal_translate("Galerie").'" data-toggle="tooltip"></i></a>
               </span>
            </h5>
            <div class="card-body collapse" id="galcat'.$rowX_gal[0].'">
               <form action="'.$ThisFile.'&amp;subop=ordre" method="post" id="FormArbo'.$rowX_gal[0].'" name="FormArbo'.$rowX_gal[0].'">
                  <input type="hidden" name="subop" value="ordre" />';
            $i=1;
            $affcatgal.= '
                  <script type="text/javascript">
                  //<![CDATA[
                     $(document).ready(function() { 
                        $("#ckballg_'.$rowX_gal[0].'").change(function(){
                           $(".ckcgid_'.$rowX_gal[0].'").prop("checked", $(this).prop("checked"));
                           if(!$("#delbatch'.$rowX_gal[0].' button").hasClass("show")){
                              $("#delbatch'.$rowX_gal[0].' button").collapse("toggle")
                           }
                           else if($("#delbatch'.$rowX_gal[0].' button").hasClass("show")){
                              $("#delbatch'.$rowX_gal[0].' button").collapse("toggle")
                           }
                        });
                        $("#ckballgv_'.$rowX_gal[0].'").change(function(){
                           $(".ckcgiv_'.$rowX_gal[0].'").prop("checked", $(this).prop("checked"));
                        });
                        
                        //==> not finish
                        $(".ckcgid_'.$rowX_gal[0].'").each(function(id,v){
                        if($(this).prop("checked") == false) console.log(v)});
                        $(".ckcgid_'.$rowX_gal[0].'").click(function(){
                           if($(this).prop("checked") == true && !$("#delbatch'.$rowX_gal[0].' button").hasClass("show")) {
                              $("#delbatch'.$rowX_gal[0].' button").collapse("show")
                           }
                           else if ($(this).prop("checked") == false && $("#delbatch'.$rowX_gal[0].' button").hasClass("show")) {
                              $("#delbatch'.$rowX_gal[0].' button").collapse("hide")
                           }
                        });
                        //<== not finish
                     });
                  //]]>
                  </script>';
            if($n_ivgc>1)
               $affcatgal.= '
                  <div class="custom-control custom-checkbox d-inline mr-2">
                     <input type="checkbox" class="custom-control-input is-valid" id="ckballgv_'.$rowX_gal[0].'" />
                     <label class="custom-control-label" for="ckballgv_'.$rowX_gal[0].'"><i class="fas fa-check fa-lg text-success align-middle"></i></label>
                  </div>';
            if($n_ig>1)
               $affcatgal.= '
                  <div class="custom-control custom-checkbox d-inline">
                     <input type="checkbox" class="custom-control-input is-invalid" id="ckballg_'.$rowX_gal[0].'" />
                     <label class="custom-control-label" for="ckballg_'.$rowX_gal[0].'"><i class="fas fa-trash fa-lg text-danger align-middle"></i></label>
                  </div>';
            $affcatgal .= '
                  <hr class="my-2" />
                  <div class="row px-3">';
            $affcatgalimg='';
            while ($rowZ_img = sql_fetch_row($queryZ)) {
               $cla = $rowZ_img[6]==1 ? ' alert-danger ' : 'alert-secondary';
               if (($rowZ_img[7] != '') and ($rowZ_img[8] != ''))
                  $img_geotag = '<img class="geotag tooltipbyclass float-right mt-1" src="modules/'.$ModPath.'/data/geotag_16.png" title="'.gal_translate("Image géoréférencée").'" alt="'.gal_translate("Image géoréférencée").'" loading="lazy" />';
               else
                  $img_geotag ='';
               $affcatgalimg .= '
                  <div class="col-md-3 col-sm-4 border rounded p-1 my-2 '.$cla.'">';
               if($n_ivgc>1)
                  if ($rowZ_img[6]==1)
                     $affcatgalimg .= '
                     <div class="custom-control custom-checkbox d-inline mr-2">
                        <input form="valbatch'.$rowX_gal[0].'" type="checkbox" class="custom-control-input ckcgiv_'.$rowX_gal[0].' is-valid" id="valigc_'.$rowZ_img[0].'" name="imgidsv[]" value="'.$rowZ_img[0].'" />
                        <label class="custom-control-label" for="valigc_'.$rowZ_img[0].'"><i class="fas fa-check text-success align-middle"></i></label>
                     </div>';
               if($n_ig>1)
                  $affcatgalimg .= '
                     <div class="custom-control custom-checkbox d-inline">
                        <input form="delbatch'.$rowX_gal[0].'" type="checkbox" class="custom-control-input is-invalid ckcgid_'.$rowX_gal[0].'" id="deligc_'.$rowZ_img[0].'" name="imgids[]" value="'.$rowZ_img[0].'" />
                        <label class="custom-control-label" for="deligc_'.$rowZ_img[0].'"><i class="fas fa-trash text-danger"></i></label>
                     </div>';
               $affcatgalimg .=$img_geotag;
               if ($rowZ_img[6]==1)
                  $affcatgalimg .= '
                     <div class="text-center form-group mt-2 mb-1">
                        <a href="modules.php?ModPath='.$ModPath.'&amp;ModStart=gal&amp;op=one-img&amp;galid='.$rowX_gal[0].'&amp;pos='.$rowZ_img[0].'" target="_blank"><img class="img-fluid mb-1" src="modules/'.$ModPath.'/mini/'.$rowZ_img[2].'"  alt="mini/'.$rowZ_img[2].'" data-toggle="tooltip" data-placement="top"  title="mini/'.$rowZ_img[2].'" loading="lazy" /></a>
                     </div>';
               else
                  $affcatgalimg .= '
                     <div class="text-center form-group mt-2 mb-1">
                        <a href="javascript: void(0);" onMouseDown="aff_image(\'image'.$rowX_gal[0].'_'.$i.'\',\'modules/'.$ModPath.'/mini/'.$rowZ_img[2].'\');">
                           <img class="img-fluid mb-1" src="modules/'.$ModPath.'/data/img.png" id="image'.$rowX_gal[0].'_'.$i.'" alt="mini/'.$rowZ_img[2].'" data-toggle="tooltip" data-placement="right" title="mini/'.$rowZ_img[2].'" loading="lazy" />
                        </a>
                     </div>';
               $affcatgalimg .= '
                     <div class="form-group mb-1">
                        <textarea class="form-control" name="desc['.$i.']" rows="2" >'.stripslashes($rowZ_img[3]).'</textarea>
                     </div>
                     <div class="form-group mb-1">
                        <input class="form-control" type="number" name="ordre['.$i.']" value="'.$rowZ_img[5].'" min="0" />
                     </div>
                     <input type="hidden" name="img_id['.$i.']" value="'.$rowZ_img[0].'" />
                     <div class="d-flex justify-content-center">';
               if ($rowZ_img[6]==1)
                  $affcatgalimg .= '
                        <a class="btn btn-sm btn-link" href="'.$ThisFile.'&amp;subop=validimg&amp;imgid='.$rowZ_img[0].'"><i class="fa fa-check fa-2x align-middle text-success" title="'.gal_translate("Valider").'" data-toggle="tooltip"></i></a>';
               else
                  $affcatgalimg .= '
                        <a class="btn btn-sm btn-link" href="'.$ThisFile.'&amp;subop=editimg&amp;imgid='.$rowZ_img[0].'"><i class="fa fa-edit fa-2x align-middle" title="'.gal_translate("Editer").'" data-toggle="tooltip"></i></a>';
               $affcatgalimg .= '
                        <a class="btn btn-sm btn-link" href="'.$ThisFile.'&amp;subop=delimg&amp;imgid='.$rowZ_img[0].'"><i class="fas fa-trash fa-2x text-danger" title="'.gal_translate("Effacer").'" data-toggle="tooltip"></i></a>';
               $i++;
               $affcatgalimg .= '
                     </div>
                  </div>';
            }
            $affcatgal .= $affcatgalimg;
            $affcatgal .= '
                  </div>
               </form>
               <hr class="my-2" />';
            if ($i!=1)
               $affcatgal .= '
                  <div class="form-group d-inline mr-2">
                     <button class="btn btn-primary form-check btn-sm mt-2" type="submit" form="FormArbo'.$rowX_gal[0].'"><i class="fa fa-edit mr-2"></i>'.gal_translate("Valider").'</button>
                  </div>';
            if($n_ivgc>1)
               $affcatgal .= '
               <form class="d-inline mr-2" action="'.$ThisFile.'&amp;subop=valimgbatch" method="post" id="valbatch'.$rowX_gal[0].'">
                  <button class="btn btn-success form-check btn-sm mt-2" type="submit"><i class="fa fa-check-square mr-1"></i><i class="fa fa-check mr-2"></i>'.gal_translate("Valider").'</button>
               </form>';
            if ($i!=1) 
               $affcatgal .= '
               <form class="d-inline " action="'.$ThisFile.'&amp;subop=delimgbatch" method="post" id="delbatch'.$rowX_gal[0].'">
                  <button class="collapse btn btn-danger form-check btn-sm mt-2" type="submit"><i class="fa fa-check-square mr-1"></i><i class="fas fa-trash mr-2"></i>'.gal_translate("Effacer").'</button>
               </form>';
            $affcatgal .= '
            </div>';
         }
        //==> SOUS-CATEGORIE
        $tn_gsc=0;
         while ($row_sscat = sql_fetch_row($queryS)) {
            $querx = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_gal WHERE cid='".$row_sscat[0]."' ORDER BY nom ASC");
            $n_gsc = sql_num_rows($querx);
            $affsoucatgal='';
             
            $tn_ivgsc=0; $n_igsc=0; $tn_igsc=0;
            while ($row_gal = sql_fetch_row($querx)) {
               $querz = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='".$row_gal[0]."' ORDER BY ordre,id,noaff");
               $n_igsc = sql_num_rows($querz);
               $n_ivgsc = sql_num_rows(sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='".$row_gal[0]."' AND noaff=1"));
               $tn_ivgsc += $n_ivgsc;
               $tn_igsc += $n_igsc;
               $icondroit='';
               switch ($row_gal[4]) {
                  case -127: $icondroit=$icondroits[0]; break;
                  case 1: $icondroit=$icondroits[1]; break;
                  case 0: $icondroit=$icondroits[2]; break;
                  case $row_gal[4]>1: $icondroit=$icondroits[3]; break;
               }
               $affsoucatgal .= '
               <div class="mx-3">
                  <h5 class="ml-3 lead">';
               if($n_igsc>0)
                  $affsoucatgal .= '
                     <a class="" data-toggle="collapse" href="#galscat'.$row_gal[0].'" aria-expanded="false" aria-checks="galscat'.$row_sscat[0].'">
                     <i class="toggle-icon fa fa-caret-down fa-lg mr-2" data-toggle="tooltip" data-placement="top" title="'.gal_translate("Cliquer pour déplier").'"></i></a>';
               else
                  $affsoucatgal .= '
                     <a class="ml-3"><i class=" mr-2 "></i></a>';
               $affsoucatgal .= stripslashes($row_gal[2]).' <small>( '.gal_translate("Galerie").' '.$icondroit.' )</small>';
               if(($n_igsc-$n_ivgsc)>0)
                  $affsoucatgal .= '<span class="badge badge-success badge-pill ml-2" title="'.gal_translate("Nombre d'images").'" data-toggle="tooltip" data-placement="right">'.$n_igsc.'</span>';
               if($n_ivgsc)
                  $affsoucatgal .= '
                     <a href="#galscat'.$row_gal[0].'" data-toggle="collapse" class="badge badge-danger badge-pill ml-2 tooltipbyclass" title="'.gal_translate("Nombre d'images à valider").'" data-placement="right">'.$n_ivgsc.'</a>';
               $affsoucatgal .= '
                     <span class="float-right mr-3">
                        <a class="" href="'.$ThisFile.'&amp;subop=editgal&amp;galid='.$row_gal[0].'"><i class="fa fa-edit" title="'.gal_translate("Editer").' '.gal_translate("Galerie").'" data-toggle="tooltip"></i></a>
                        <a class="" href="'.$ThisFile.'&amp;subop=delgal&amp;galid='.$row_gal[0].'"><i class="fas fa-trash text-danger ml-2" title="'.gal_translate("Effacer").' '.gal_translate("Galerie").'" data-toggle="tooltip"></i></a>
                     </span>
                  </h5>
               </div>
               <div class="card-body collapse" id="galscat'.$row_gal[0].'">
                  <form action="'.$ThisFile.'&amp;subop=ordre" method="post" name="FormArbo'.$row_gal[0].'">
                     <input type="hidden" name="subop" value="ordre" />';
               $i=1;
               $affsoucatgal .= '
                     <div class="row px-3">';
               $affsoucatgalimg='';
               while($row_img = sql_fetch_row($querz)) {
                  $cla = $row_img[6]==1 ? ' alert-danger ' : 'alert-secondary';
                  if (($row_img[7] != '') and ($row_img[8] != ''))
                     $img_geotag = '<img class="geotag tooltipbyclass float-right mt-1" src="modules/'.$ModPath.'/data/geotag_16.png" title="'.gal_translate("Image géoréférencée").'" alt="'.gal_translate("Image géoréférencée").'" loading="lazy" />';
                  else
                  $img_geotag ='';
                  $affsoucatgalimg .= '
                        <div class="col-lg-3 col-sm-4 border rounded p-1 my-2 '.$cla.'">
                           <label class="custom-check custom-checkbox">
                              <span class="custom-check-description"></span>
                              <input form="delbatch'.$row_gal[0].'" type="checkbox" class="custom-check-input" name="imgids[]" value="'.$row_img[0].'" />
                              <span class="custom-check-indicator bg-danger"></span>
                           </label>';
                                          $affsoucatgalimg .= $img_geotag;

                  if ($row_img[6]==1)
                     $affsoucatgalimg .= '
                           <div class="text-center form-group mb-1">
                              <a href="modules.php?ModPath='.$ModPath.'&amp;ModStart=gal&amp;op=one-img&amp;galid='.$row_gal[0].'&amp;pos='.$row_img[0].'" target="_blank"><img class="img-fluid mb-1" src="modules/'.$ModPath.'/mini/'.$row_img[2].'" alt="mini/'.$row_img[2].'" data-toggle="tooltip" data-placement="top"  title="mini/'.$row_img[2].'" loading="lazy" /></a>
                           </div>';
                  else
                     $affsoucatgalimg .= '
                           <div class="text-center form-group mb-1">
                              <a href="javascript: void(0);" onMouseDown="aff_image(\'image'.$row_gal[0].'_'.$i.'\',\'modules/'.$ModPath.'/mini/'.$row_img[2].'\');">
                                 <img class="img-fluid mb-1" src="modules/'.$ModPath.'/data/img.png" id="image'.$row_gal[0].'_'.$i.'" alt="mini/'.$row_img[2].'" data-toggle="tooltip" data-placement="right" title="mini/'.$row_img[2].'" loading="lazy" />
                              </a>
                           </div>';
                  $affsoucatgalimg .= '
                           <div class="form-group mb-1">
                              <textarea class="form-control" name="desc['.$i.']" rows="2" >'.stripslashes($row_img[3]).'</textarea>
                           </div>
                           <div class="form-group mb-1">
                              <input class="form-control" type="number" name="ordre['.$i.']" value="'.$row_img[5].'" min="0" />
                           </div>
                           <input type="hidden" name="img_id['.$i.']" value="'.$row_img[0].'" />
                           <div class="text-center mt-3">';
                  $i++;
                  if ($row_img[6]==1)
                     $affsoucatgalimg .= '
                              <a class="btn btn-sm btn-link" href="'.$ThisFile.'&amp;subop=validimg&amp;imgid='.$row_img[0].'"><i class="fa fa-check fa-2x align-middle" title="'.gal_translate("Valider").'" data-toggle="tooltip"></i></a>';
                  else
                     $affsoucatgalimg .= '<a class="btn btn-sm btn-link" href="'.$ThisFile.'&amp;subop=editimg&amp;imgid='.$row_img[0].'"><i class="fa fa-edit fa-2x align-middle" title="'.gal_translate("Editer").'" data-toggle="tooltip"></i></a>';
                  $affsoucatgalimg .= '<a class="btn btn-sm" href="'.$ThisFile.'&amp;subop=delimg&amp;imgid='.$row_img[0].'"><i class="fa fa-trash fa-2x text-danger" title="'.gal_translate("Effacer").'" data-toggle="tooltip"></i></a>
                           </div>
                        </div>';
               }
               $affsoucatgal .= $affsoucatgalimg;
               if ($i!=1)
                  $affsoucatgal .='
                     <div class="form-group w-100">
                        <button class="btn btn-outline-primary form-check btn-sm mt-2" type="submit">'.gal_translate("Valider").'</button>
                     </div>';
               $affsoucatgal .='
                     </div>
                  </form>
                  <form action="'.$ThisFile.'&amp;subop=delimgbatch" method="post" id="delbatch'.$row_gal[0].'">
                     <button class="btn btn-outline-danger form-check btn-sm mt-2" type="submit"><i class="fa fa-check-square fa-lg mr-1"></i>'.gal_translate("Effacer").'</button>
                  </form>
               </div>';
            }
            $icondroit='';
            switch ($row_sscat[3]) {
               case -127: $icondroit=$icondroits[0]; break;
               case 1: $icondroit=$icondroits[1]; break;
               case 0: $icondroit=$icondroits[2]; break;
               case $row_cat[3]>1: $icondroit=$icondroits[3]; break;
            }
            $affsoucat .= '
            <hr class="mt-0" />
               <h5 class="mx-3 mb-3">';
            if($n_gsc>0)
               $affsoucat .='
                  <a class="ml-3" data-toggle="collapse" href="#scat'.$row_sscat[0].'" aria-expanded="false" aria-checks="scat'.$row_sscat[0].'"><i class="toggle-icon fa fa-caret-down fa-lg mr-2" data-toggle="tooltip" data-placement="top" title="'.gal_translate("Cliquer pour déplier").'"></i></a>';
            else
               $affsoucat .= '
                  <a class="ml-2"><i class=" mr-2 "></i></a>';
            $affsoucat .= stripslashes($row_sscat[2]).' <small>( '.gal_translate("Sous-catégorie").' '.$icondroit.' )</small>';
            if($n_gsc>0)
               $affsoucat .= '<span class="badge badge-secondary badge-pill ml-2" title="'.gal_translate("Nombre de galeries").'" data-toggle="tooltip" data-placement="right">'.$n_gsc.'</span>';
            if($n_igsc>0)
               $affsoucat .= '<span class="badge badge-success badge-pill ml-2" title="'.gal_translate("Nombre d'images").'" data-toggle="tooltip" data-placement="right">'.($tn_igsc).'</span>';
            if($tn_ivgsc)
               $affsoucat .='
                  <a href="#scat'.$row_sscat[0].'" class="badge badge-danger badge-pill ml-2 tooltipbyclass" data-toggle="collapse" title="'.gal_translate("Nombre d'images à valider").'" data-placement="right">'.$tn_ivgsc.'</a>'; 
            $affsoucat .= '
                  <span class="float-right mr-3"><a href="'.$ThisFile.'&amp;subop=editcat&amp;catid='.$row_sscat[0].'"><i class="fa fa-edit" title="'.gal_translate("Editer").' '.gal_translate("Sous-catégorie").'" data-toggle="tooltip"></i></a><a class="" href="'.$ThisFile.'&amp;subop=delsscat&amp;sscatid='.$row_sscat[0].'"><i class="fas fa-trash text-danger ml-3" data-original-title="'.gal_translate("Effacer").' '.gal_translate("Sous-catégorie").'" data-toggle="tooltip"></i></a></span>
               </h5>
            <div class="collapse" id="scat'.$row_sscat[0].'">';
           // SOUS-CATEGORIE

            $affsoucat .= $affsoucatgal;
            $affsoucat .= '
            </div>';
            $tn_gsc += $n_gsc;
            $tn_igscs += $tn_igsc;
         }
         $icondroit='';
         switch ($row_cat[3]) {
            case -127: $icondroit=$icondroits[0]; break;
            case 1: $icondroit=$icondroits[1]; break;
            case 0: $icondroit=$icondroits[2]; break;
            case $row_cat[3]>1: $icondroit=$icondroits[3]; break;
         }
         echo '
   <div class="card mb-3">
      <div class="p-3 bg-light">
         <h5 class="mb-0">';
         if($n_sc > 0 or $n_gc > 0)
            echo '
            <a data-toggle="collapse" href="#cat'.$row_cat[0].'" aria-expanded="false" aria-checks="cat'.$row_cat[0].'"><i class="toggle-icon fa fa-caret-down fa-lg mr-2" data-toggle="tooltip" data-placement="top" title="'.gal_translate("Cliquer pour déplier").'"></i></a>';
         echo stripslashes($row_cat[2]).' <small>( '.gal_translate("Catégorie").' '.$icondroit.' )</small>';
         if($n_sc>0)
            echo '<span class="badge badge-dark badge-pill ml-2" title="'.gal_translate("Nombre de sous-catégories").'" data-toggle="tooltip" data-placement="right">'.$n_sc.'</span>';
         if(($n_gc+$tn_gsc)>0)
            echo '<span class="badge badge-secondary badge-pill ml-2" title="'.gal_translate("Nombre de galeries").'" data-toggle="tooltip" data-placement="right">'.($n_gc+$tn_gsc).'</span>';
         if(($tn_ig+$tn_igscs)>0)
            echo '<span class="badge badge-success badge-pill ml-2" title="'.gal_translate("Nombre d'images").'" data-toggle="tooltip" data-placement="right">'.($tn_ig+$tn_igscs).'</span>';
         if($tn_ivgc>0 or $tn_ivgsc>0)
            echo '
            <a href="#cat'.$row_cat[0].'" data-toggle="collapse" class="badge badge-danger badge-pill ml-2 tooltipbyclass" title="'.gal_translate("Nombre d'images à valider").'" data-placement="right">'.($tn_ivgc+$tn_ivgsc).'</a>';
         echo '
            <span class="float-right">
               <a href="'.$ThisFile.'&amp;subop=editcat&amp;catid='.$row_cat[0].'"><i class="fa fa-edit align-middle" title="'.gal_translate("Editer").' '.gal_translate("Catégorie").'" data-toggle="tooltip"></i>
               </a><a href="'.$ThisFile.'&amp;subop=delcat&amp;catid='.$row_cat[0].'"><i class="fas fa-trash text-danger align-middle ml-3" title="'.gal_translate("Effacer").' '.gal_translate("Catégorie").'" data-toggle="tooltip"></i></a>
            </span>
         </h5>
      </div>
      <div class="collapse " id="cat'.$row_cat[0].'">
      '.$affcatgal.$affsoucat.'
      </div>
   </div>';
      }
   }
}

function DelCat($id,$go) {
   global $ThisFile, $NPDS_Prefix, $ModPath;
   if (empty($go)) {
      $q_cat = sql_query("SELECT nom FROM ".$NPDS_Prefix."tdgal_cat WHERE id='$id'");
      $r_cat = sql_fetch_row($q_cat);
      echo '
      <div class="alert alert-danger lead">'.gal_translate("Vous allez supprimer la catégorie").' : '.$r_cat[0].'</div>
      <a href="'.$ThisFile.'&amp;subop=delcat&amp;catid='.$id.'&amp;go=true" class=" btn btn-outline-danger btn-sm">'.gal_translate("Confirmer").'</a> <a class="btn btn-outline-secondary btn-sm" href="'.$ThisFile.'">'.gal_translate("Annuler").'</a>';
   } else {
      $q_cat = sql_query("SELECT nom FROM ".$NPDS_Prefix."tdgal_cat WHERE id='$id'");
      $r_cat = sql_fetch_row($q_cat);
      $q_sscat = sql_query("SELECT nom,id FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='$id'");
      echo '
      <h5 class="font-weight-bold">'.gal_translate("Catégorie").' '.$r_cat[0].'</h5>';
      // Il peut ne pas y avoir de sous-catégories
      $r_sscat = sql_fetch_row($q_sscat);
      do {
         echo ''.$r_sscat[0].'';
         $q_gal = sql_query("SELECT nom,id,cid FROM ".$NPDS_Prefix."tdgal_gal WHERE cid='$r_sscat[1]' OR cid='$id'");
         while ($r_gal = sql_fetch_row($q_gal)) {
            if ($r_gal[2]==$r_sscat[1])
               $remp='';
            else
               $remp='';
            echo ''.$remp.''.$r_gal[0].'';
            $q_img = sql_query("SELECT name,id FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='$r_gal[1]'");
            while ($r_img = sql_fetch_row($q_img)) {
               $m_img = "modules/$ModPath/mini/$r_img[0]";
               $g_img = "modules/$ModPath/imgs/$r_img[0]";
               echo '<ul class="list-group">';
               echo '<li class="list-group-item">'.$r_img[0].'</li>';
               if (@unlink($m_img))
                  echo '<li class="list-group-item list-group-item-success">'.gal_translate("Miniature supprimée").'</li>';
               else
                  echo '<li class="list-group-item list-group-item-danger">'.gal_translate("Miniature non supprimée").'</li>';
               if (@unlink($g_img))
                  echo '<li class="list-group-item list-group-item-success">'.gal_translate("Image supprimée").'</li>';
               else
                  echo '<li class="list-group-item list-group-item-danger">'.gal_translate("Image non supprimée").'</li>';
               if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_vot WHERE pic_id='$r_img[1]'"))
                  echo '<li class="list-group-item list-group-item-success">'.gal_translate("Votes supprimés").'</li>';
               else
                  echo '<li class="list-group-item list-group-item-danger">'.gal_translate("Votes non supprimés").'</li>';
               if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_com WHERE pic_id='$r_img[1]'"))
                  echo '<li class="list-group-item list-group-item-success">'.gal_translate("Commentaires supprimés").'</li>';
               else
                  echo '<li class="list-group-item list-group-item-danger">'.gal_translate("Commentaires non supprimés").'</li>';
               if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_img WHERE id='$r_img[1]'"))
                  echo '<li class="list-group-item list-group-item-success">'.gal_translate("Enregistrement supprimé").'</li>';
               else
                  echo '<li class="list-group-item list-group-item-danger">'.gal_translate("Enregistrement non supprimé").'</li>';
            } // Fin du while img
            if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_gal WHERE id='$r_gal[1]'")) {
               echo '<li class="list-group-item list-group-item-success">'.$remp.'&nbsp;&nbsp;&nbsp; '.gal_translate("Galerie supprimée").'</li>';
            } else {
               echo '<li class="list-group-item list-group-item-danger">'.$remp.' '.gal_translate("Galerie non supprimée").'</li>';
            }
         } // Fin du while galerie
         if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='$id'")) {
            echo '<li class="list-group-item list-group-item-success">'.gal_translate("Sous-catégorie supprimée").'</li>';
         } else {
            echo '<li class="list-group-item list-group-item-danger">'.gal_translate("Sous-catégorie non supprimée").'</li>';
         }
      } while ($r_sscat = sql_fetch_row($q_sscat));
       // SousCat
      if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_cat WHERE id='$id'")) {
         echo '<li class="list-group-item list-group-item-success">'.gal_translate("Catégorie supprimée").'</li>';
      } else {
         echo '<li class="list-group-item list-group-item-danger">'.gal_translate("Catégorie non supprimée").'</li>';
      }
      echo '</ul>';
   }
}

function DelSsCat($id,$go) {
   global $ThisFile, $NPDS_Prefix, $ModPath;
   if (empty($go)) {
      $q_sscat = sql_query("SELECT nom FROM ".$NPDS_Prefix."tdgal_cat WHERE id='$id'");
      $r_sscat = sql_fetch_row($q_sscat);
      echo '
      <div class="alert alert-danger lead">'.gal_translate("Vous allez supprimer la sous-catégorie").' : '.$r_sscat[0].'</div>
      <a class="btn btn-outline-danger btn-sm mr-2" href="'.$ThisFile.'&amp;subop=delsscat&amp;sscatid='.$id.'&amp;go=true">
      '.gal_translate("Confirmer").'</a><a class="btn btn-outline-secondary btn-sm" href="'.$ThisFile.'">'.gal_translate("Annuler").'</a>'; 
   } else {
      $q_sscat = sql_query("SELECT nom FROM ".$NPDS_Prefix."tdgal_cat WHERE id='$id'");
      $r_sscat = sql_fetch_row($q_sscat);
      $q_gal = sql_query("SELECT nom,id FROM ".$NPDS_Prefix."tdgal_gal WHERE cid='$id'");

      echo "<table class=\"table\" width=\"100%\" cellspacing=\"2\" cellpadding=\"0\" border=\"0\">";
      echo "<tr><td colspan=\"2\" class=\"header\"><strong>&nbsp;".$r_sscat[0]."</strong></td></tr>";
      while ($r_gal = sql_fetch_row($q_gal)) {
         
         echo "<tr><td colspan=\"2\">&nbsp;&nbsp;&nbsp;".$r_gal[0]."</td></tr>";
         $q_img = sql_query("SELECT name,id FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='$r_gal[1]'");
         while ($r_img = sql_fetch_row($q_img)) {
            $m_img = "modules/$ModPath/mini/$r_img[0]";
            $g_img = "modules/$ModPath/imgs/$r_img[0]";
            
            echo "<tr><td colspan=\"2\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$r_img[0]."</td></tr>";
            if (@unlink($m_img)) {
               echo "<tr><td width=\"40%\"></td><td>".gal_translate("Miniature supprimée")."</td></tr>";
            } else {
               echo "<tr><td width=\"40%\"></td><td class=\"text-danger\">".gal_translate("Miniature non supprimée")."</td></tr>";
            }
            if (@unlink($g_img)) {
               echo "<tr><td></td><td>".gal_translate("Image supprimée")."</td></tr>";
            } else {
               echo "<tr><td></td><td class=\"text-danger\">".gal_translate("Image non supprimée")."</td></tr>";
            }
            if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_vot WHERE pic_id='$r_img[1]'")) {
               echo "<tr><td></td><td>".gal_translate("Votes supprimés")."</td></tr>";
            } else {
               echo "<tr><td></td><td class=\"text-danger\">".gal_translate("Votes non supprimés")."</td></tr>";
            }
            if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_com WHERE pic_id='$r_img[1]'")) {
               echo "<tr><td></td><td>".gal_translate("Commentaires supprimés")."</td></tr>";
            } else {
               echo "<tr><td></td><td class=\"text-danger\">".gal_translate("Commentaires non supprimés")."</td></tr>";
            }
            if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_img WHERE id='$r_img[1]'")) {
               echo "<tr><td></td><td>".gal_translate("Enregistrement supprimé")."</td></tr>";
            } else {
               echo "<tr><td></td><td class=\"text-danger\">".gal_translate("Enregistrement non supprimé")."</td></tr>";
            }
         } // Fin du while img
         if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_gal WHERE id='$r_gal[1]'")) {
            echo "<tr><td colspan=\"2\">&nbsp;&nbsp;&nbsp;".gal_translate("Galerie supprimée")."</td></tr>";
         } else {
            echo "<tr><td colspan=\"2\" class=\"text-danger\">&nbsp;&nbsp;&nbsp;".gal_translate("Galerie non supprimée")."</td></tr>";
         }
      } // Fin du while galerie
      
      if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_cat WHERE id='$id'")) {
         echo "<tr><td colspan=\"2\"><strong>".gal_translate("Sous-catégorie supprimée")."</strong></td></tr>";
      } else {
         echo "<tr><td colspan=\"2\" class=\"text-danger\">".gal_translate("Sous-catégorie non supprimée")."</td></tr>";
      }
      echo "</table>";
   }
}

function DelGal($id,$go) {
   global $ThisFile, $NPDS_Prefix, $ModPath;
   if (empty($go)) {
      $q_gal = sql_query("SELECT nom FROM ".$NPDS_Prefix."tdgal_gal WHERE id='$id'");
      $r_gal = sql_fetch_row($q_gal);
      echo '
      <div class="alert alert-danger lead">
         <i class="fa fa-info-circle mr-2"></i>'.gal_translate("Vous allez supprimer").' : <strong>'.$r_gal[0].'</strong>
      </div>
      <a class="btn btn-outline-danger btn-sm mr-2" href="'.$ThisFile.'&amp;subop=delgal&amp;galid='.$id.'&amp;go=true">'.gal_translate("Confirmer").'</a><a class="btn btn-outline-secondary btn-sm" href="'.$ThisFile.'">'.gal_translate("Annuler").'</a>';
   } else {
      $q_gal = sql_query("SELECT nom FROM ".$NPDS_Prefix."tdgal_gal WHERE id='$id'");
      $r_gal = sql_fetch_row($q_gal);
      $q_img = sql_query("SELECT name,id FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='$id'");

       if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_gal WHERE id='$id'"))
         echo '<div class="alert alert-success">'.gal_translate("Galerie").' '.$r_gal[0].' '.gal_translate("supprimée").'</div>';
      else
         echo '<div class="alert alert-danger">'.gal_translate("Galerie").' '.$r_gal[0].' '.gal_translate(" non supprimée").'</div>';

      while ($r_img = sql_fetch_row($q_img)) {
         $m_img = "modules/$ModPath/mini/$r_img[0]";
         $g_img = "modules/$ModPath/imgs/$r_img[0]";
         echo '
         <ul class="list-group">
            <li class="list-group-item lead font-weight-bold">'.$r_img[0].'</li>';
         if (@unlink($m_img))
            echo '
            <li class="list-group-item list-group-item-success">'.gal_translate("Miniature supprimée").'</li>';
         else
            echo '
            <li class="list-group-item list-group-item-danger">'.gal_translate("Miniature non supprimée").'</li>';
         if (@unlink($g_img))
            echo '
            <li class="list-group-item list-group-item-success">'.gal_translate("Image supprimée").'</li>';
         else
            echo '
            <li class="list-group-item list-group-item-danger">'.gal_translate("Image non supprimée").'</li>';
         if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_vot WHERE pic_id='$r_img[1]'"))
            echo '
            <li class="list-group-item list-group-item-success">'.gal_translate("Votes supprimés").'</li>';
         else
            echo '
            <li class="list-group-item list-group-item-danger">'.gal_translate("Votes non supprimés").'</li>';
         if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_com WHERE pic_id='$r_img[1]'"))
            echo '
            <li class="list-group-item list-group-item-success">'.gal_translate("Commentaires supprimés").'</li>';
         else
            echo '
            <li class="list-group-item list-group-item-danger">'.gal_translate("Commentaires non supprimés").'</li>';
         if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_img WHERE id='$r_img[1]'"))
            echo '
            <li class="list-group-item list-group-item-success">'.gal_translate("Enregistrement supprimé").'</li>';
         else
            echo '
            <li class="list-group-item list-group-item-danger">'.gal_translate("Enregistrement non supprimé").'</li>';
      }
      echo '
         </ul>';
   }
}

function EditImg($id) {
   global $ThisFile, $NPDS_Prefix, $ModPath;
   $queryA = sql_query("SELECT name,comment,gal_id,img_lat,img_long FROM ".$NPDS_Prefix."tdgal_img WHERE id='$id'");
   $rowA = sql_fetch_row($queryA);
   echo '
   <h4>'.gal_translate("Editer").' '.gal_translate("Image").'</h4>
   <hr />
   <div class="row">
      <div class="col-sm-5 d-flex align-items-center py-0 mx-auto">
         <img class="img-fluid img-thumbnail tooltipbyclass" src="modules/'.$ModPath.'/mini/'.$rowA[0].'" alt="'.$rowA[0].'" data-toggle="modal" data-target="#modaleditphoto" data-placement="bottom" title="'.$rowA[0].'" />
      </div>
      <div class="col-sm-7">
         <form id="formmodifimg" action="'.$ThisFile.'" method="post" name="FormModifImg">
            <input type="hidden" name="subop" value="doeditimg" />
            <input type="hidden" name="imgid" value="'.$id.'" />
            <div class="form-group">
               <label class="col-form-label" for="imggal">'.gal_translate("Galeries").'</label>
               <select id="imggal" name="imggal" class="custom-select">';
   echo select_arbo($rowA[2]);
   echo '
               </select>
            </div>
            <div class="form-group">
               <label class="col-form-label" for="newdesc">'.gal_translate("Description").'</label>
               <textarea class="form-control" type="text" id="newdesc" name="newdesc" rows="3" maxlength="255">'.stripslashes($rowA[1]).'</textarea>
               <span class="help-block text-right" id="countcar_newdesc"></span>
            </div>
            <div class="form-row">
               <div class="form-group col-md-6">
                  <label for="imglat" class="">'.gal_translate("Latitude").'</label>
                  <div class="input-group mb-2 mr-sm-2">
                     <div class="input-group-prepend">
                        <div class="input-group-text jsgeo"><i class="fa fa-globe fa-lg"></i></div>
                     </div>
                     <input type="text" class="form-control" name="imglat" id="imglat" placeholder="'.gal_translate("Latitude").'" value="'.$rowA[3].'"/>
                  </div>
               </div>
               <div class="form-group col-md-6">
                  <label for="imglong" class="">'.gal_translate("Longitude").'</label>
                  <div class="input-group mb-2 mr-sm-2">
                     <div class="input-group-prepend">
                        <div class="input-group-text jsgeo"><i class="fa fa-globe fa-lg"></i></div>
                     </div>
                     <input type="text" class="form-control" name="imglong" id="imglong" placeholder="'.gal_translate("Longitude").'" value="'.$rowA[4].'"/>
                  </div>
                </div>
            </div>
            <input class="btn btn-primary" type="submit" value="'.gal_translate("Modifier").'">
         </form>
      </div>
   </div>
   <div class="modal fade" id="modaleditphoto" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">
         <div class="modal-content">
            <img class="img-fluid" src="modules/'.$ModPath.'/imgs/'.$rowA[0].'" alt="'.$rowA[0].'" />
         </div>
      </div>
   </div>';
   echo img_geolocalisation($rowA[3],$rowA[4],'');
   $fv_parametres ='
      imglat : {
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
      imglong: {
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
      },';
   $arg1 = '
   var formulid = ["formmodifimg"]
   inpandfieldlen("newdesc",255);';
   adminfoot('fv',$fv_parametres,$arg1,'1');

   $qcomment = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_com WHERE pic_id='$id' ORDER BY comtimestamp DESC");
   $num_comm = sql_num_rows($qcomment);
   echo '
      <ul class="list-group mt-4">';
   while ($rowC = sql_fetch_row($qcomment)) {
      echo '
         <li class="d-flex list-group-item list-group-item-light justify-content-between align-items-left">'.userpopover($rowC[2],40).' '.$rowC[2].'<br />'.gal_translate("Posté le").' '.date(translate("dateinternal"),$rowC[5]).'<span class="ml-auto"><a href="'.$ThisFile.'&amp;subop=delcomimg&amp;id='.$rowC[0].'&amp;picid='.$rowC[1].'"><i class="fas fa-trash fa-lg text-danger" title="'.gal_translate("Effacer").'" data-toggle="tooltip"></i></a></span></li>
         <li class="list-group-item">'.stripslashes($rowC[3]).'</li>';
   }
   echo '
      </ul>';
}

function DoEditImg($id,$imggal,$newdesc,$imglat,$imglong) {
   global $ThisRedo, $NPDS_Prefix;
   $newtit = addslashes(removeHack($newdesc));
   if ($imggal=='') $imggal="-1";
   if (sql_query("UPDATE ".$NPDS_Prefix."tdgal_img SET gal_id='$imggal', comment='$newtit', img_lat='$imglat', img_long='$imglong' WHERE id='$id'"))
      redirect_url($ThisRedo."&subop=viewarbo");
   else {
      echo '
      <script type="text/javascript">
         //<![CDATA[
            alert("Erreur lors de la modification de l\'image");
         //]]>
      </script>';
      redirect_url($ThisRedo."&subop=editimg&imgid=$id");
   }
}

function DelImg($id,$go) {
   global $ThisFile, $NPDS_Prefix, $ModPath, $ThisRedo;
   if (empty($go)) {
      $q_img = sql_query("SELECT name FROM ".$NPDS_Prefix."tdgal_img WHERE id='$id'");
      $r_img = sql_fetch_row($q_img);
      echo '
      <div class="alert alert-danger lead">'.gal_translate("Vous allez supprimer une image").' : '.$r_img[0].'</div>
      <a class="btn btn-outline-danger btn-sm mr-2" href="'.$ThisFile.'&amp;subop=delimg&amp;imgid='.$id.'&amp;go=true">'.gal_translate("Confirmer").'</a>
      <a class="btn btn-outline-secondary btn-sm" href="'.$ThisFile.'">'.gal_translate("Annuler").'</a>';
   } else {
      $q_img = sql_query("SELECT name FROM ".$NPDS_Prefix."tdgal_img WHERE id='$id'");
      $r_img = sql_fetch_row($q_img);
      $m_img = "modules/$ModPath/mini/$r_img[0]";
      $g_img = "modules/$ModPath/imgs/$r_img[0]";
      echo '
      <ul class="list-group">
         <li class="list-group-item lead font-weight-bold">'.$r_img[0].'</li>';
      if (@unlink($m_img))
         echo '
         <li class="list-group-item list-group-item-success">'.gal_translate("Miniature supprimée").'</li>';
      else
         echo '
         <li class="list-group-item list-group-item-danger">'.gal_translate("Miniature non supprimée").'</li>';
      if (@unlink($g_img))
         echo '
         <li class="list-group-item list-group-item-success">'.gal_translate("Image supprimée").'</li>';
      else
         echo '
         <li class="list-group-item list-group-item-danger">'.gal_translate("Image non supprimée").'</li>';
      if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_vot WHERE pic_id='$id'"))
         echo '
         <li class="list-group-item list-group-item-success">'.gal_translate("Votes supprimés").'</li>';
      else
         echo '
         <li class="list-group-item list-group-item-danger">'.gal_translate("Votes non supprimés").'</li>';
      if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_com WHERE pic_id='$id'"))
         echo '
         <li class="list-group-item list-group-item-success">'.gal_translate("Commentaires supprimés").'</li>';
      else
         echo '
         <li class="list-group-item list-group-item-danger">'.gal_translate("Commentaires non supprimés").'</li>';
      if (sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_img WHERE id='$id'"))
         echo '
         <li class="list-group-item list-group-item-success"><strong>'.gal_translate("Enregistrement supprimé").'</strong></li>';
      else
         echo '
         <li class="list-group-item list-group-item-danger">'.gal_translate("Enregistrement non supprimé").'</li>';
      echo '
      </ul>';
//      redirect_url($ThisRedo);
   }
}

function DelImgBatch($imgids,$go) {
   global $ThisFile, $NPDS_Prefix, $ModPath, $ThisRedo, $imgids;
   $nbdel=count($imgids);
   $imgtodel='';
   foreach ($imgids as $v_id) {
   $imgtodel .= 'imgids[]='.$v_id.'&';
   }
   if (empty($go)) {
      echo '
      <div class="alert alert-danger lead">'.gal_translate("Vous allez supprimer").' '.$nbdel.' '.gal_translate("image(s)").'</div>
      <a class="btn btn-outline-danger btn-sm mr-2" href="'.$ThisFile.'&amp;subop=delimgbatch&amp;'.$imgtodel.'go=true">'.gal_translate("Confirmer").'</a>
      <a class="btn btn-outline-secondary btn-sm" href="'.$ThisFile.'">'.gal_translate("Annuler").'</a>';
   } 
   else {
      foreach ($imgids as $v_id) {
         $q_img = sql_query("SELECT name FROM ".$NPDS_Prefix."tdgal_img WHERE id='$v_id'");
         $r_img = sql_fetch_row($q_img);
         $m_img = "modules/$ModPath/mini/$r_img[0]";
         $g_img = "modules/$ModPath/imgs/$r_img[0]";
         @unlink($m_img);
         @unlink($g_img);
         sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_vot WHERE pic_id='$v_id'");
         sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_com WHERE pic_id='$v_id'");
         sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_img WHERE id='$v_id'");
      }
   }
}

function DelComImg($id, $picid) {
   global $ThisRedo, $NPDS_Prefix;
   sql_query("DELETE FROM ".$NPDS_Prefix."tdgal_com WHERE pic_id='$picid' AND id='$id'");
   redirect_url($ThisRedo."&subop=editimg&imgid=$picid");
}

function DoValidImg($id) {
   global $ThisRedo, $NPDS_Prefix;
   if (sql_query("UPDATE ".$NPDS_Prefix."tdgal_img SET noaff='0' WHERE id='$id'"))
      redirect_url($ThisRedo."&subop=viewarbo");
}

function ValidImgBatch($imgidsv,$go) {
   global $ThisFile, $NPDS_Prefix, $ModPath, $ThisRedo, $imgidsv;
   $nbval=count($imgidsv);
   $imgtoval='';
   foreach ($imgidsv as $v_id) {
      $imgtoval .= 'imgidsv[]='.$v_id.'&';
   }
   if (empty($go))
      echo '
      <div class="alert alert-success lead">'.gal_translate("Vous allez valider").' '.$nbval.' '.gal_translate("image(s)").'</div>
      <a class="btn btn-outline-success btn-sm mr-2" href="'.$ThisFile.'&amp;subop=valimgbatch&amp;'.$imgtoval.'go=true">'.gal_translate("Confirmer").'</a>
      <a class="btn btn-outline-secondary btn-sm" href="'.$ThisFile.'">'.gal_translate("Annuler").'</a>';
   else
      foreach ($imgidsv as $v_id) {
         sql_query("UPDATE ".$NPDS_Prefix."tdgal_img SET noaff='0' WHERE id='$v_id'");
      }
}

function Edit($type,$id) {
   global $ThisFile, $NPDS_Prefix, $ThisRedo;
   if ($type=="Cat") {
      $query = "SELECT * FROM ".$NPDS_Prefix."tdgal_cat WHERE id='$id'";
      $row = sql_fetch_row(sql_query($query));
      $notice='';
      if($row[1] == 0)
         $notice = '<small class="text-danger">'.gal_translate("La modification du droit d'accès à cette catégorie entraine de facto la modification des droits d'accès à TOUTES les sous catégories et galeries qui en dépendent.").'</small>';
      if($row[1] != 0) {
         $queryp = "SELECT acces FROM ".$NPDS_Prefix."tdgal_cat WHERE id='$row[1]'";
         $rowp = sql_fetch_row(sql_query($queryp));
      } else $rowp[]='tousdroits';
   }
   if ($type=="Gal") {
      $query = "SELECT * FROM ".$NPDS_Prefix."tdgal_gal WHERE id='$id'";
      $row = sql_fetch_row(sql_query($query));
      $queryp = "SELECT acces FROM ".$NPDS_Prefix."tdgal_cat WHERE id='$row[1]'";
      $rowp = sql_fetch_row(sql_query($queryp));
   }
   $result = sql_query($query);
   if (!$row=sql_fetch_row($result))
      redirect_url($ThisRedo);
   else
      $actualname = stripslashes($row[2]);
   echo '
   <form class="mt-3" id="formrename" action="'.$ThisFile.'" method="post" name="FormRename">
      <input type="hidden" name="subop" value="rename" />
      <input type="hidden" name="type" value="'.$type.'" />
      <input type="hidden" name="gcid" value="'.$id.'" />
      <h5 class="my-3">'.gal_translate("Editer").' ';
      if ($type=="Gal")
         echo strtolower(gal_translate("Galerie"));
      else
         echo strtolower(gal_translate("Catégorie"));
      echo ' : <span class="text-muted">'.$actualname.'</span></h5>
      <hr />';
   //déplacement d'une galerie
   if ($type=="Gal") {
      echo '
      <div class="form-group row">
         <label class="col-sm-4 form-control-label" for="newgalcat">'.gal_translate("Catégorie").'</label>
         <div class="col-sm-8">
            <select class="custom-select" name="newgalcat" id="newgalcat" >';
      echo cat_arbo($row[1]);
      echo '
            </select>
         </div>
      </div>';
   }
   echo '
      <div class="form-group row">
         <label class="col-sm-4 col-form-label" for="newacces">'.gal_translate("Accès pour").'</label>
         <div class="col-sm-8">';
   if ($type=="Cat")
      echo '
            <select class="custom-select" type="select" name="newacces" id="newacces" >'.Fab_Option_Group($row[3],$rowp[0]).'</select>'.$notice;
   if ($type=="Gal")
      echo '
            <select class="custom-select" type="select" name="newacces" id="newacces" >'.Fab_Option_Group($row[4],$rowp[0]).'</select>';
   echo '
         </div>
      </div>
      <div class="form-group row">
         <label class="col-sm-4 col-form-label" for="newname">'.gal_translate("Nouveau nom").'</label>
         <div class="col-sm-8">
            <input class="form-control" type="text" name="newname" id="newname" maxlength="150" required="required" value="'.$actualname.'" />
            <span class="help-block text-right" id="countcar_newname"></span>
         </div>
      </div>
      <div class="form-group row">
         <div class="col-sm-8 ml-auto">
            <button class="btn btn-primary" type="submit">'.gal_translate("Modifier").'</button>
         </div>
      </div>
   </form>';
   $arg1 = '
   var formulid = ["formrename"]
   inpandfieldlen("newname",150);';
   adminfoot('fv','',$arg1,'1');
}

function ChangeName($type,$id,$valeur,$galcat,$acces) {
   global $NPDS_Prefix, $ThisRedo;
   if ($type=="Cat") {
      $query="UPDATE ".$NPDS_Prefix."tdgal_cat SET nom=\"$valeur\", acces=\"$acces\" WHERE id=$id";
   }
   if ($type=="Gal")
      $query = "UPDATE ".$NPDS_Prefix."tdgal_gal SET cid=\"$galcat\", nom=\"$valeur\", acces=\"$acces\" WHERE id=$id";
   $update = sql_query($query);

    if ($type=="Cat") {
      $query = "UPDATE ".$NPDS_Prefix."tdgal_gal SET acces=$acces WHERE cid=$id";
      sql_query($query);
      $scquery ="SELECT * FROM ".$NPDS_Prefix."tdgal_cat WHERE cid=$id";
      $rowsc = sql_query($scquery);
      while($d = sql_fetch_row($rowsc)) {
         $query = "UPDATE ".$NPDS_Prefix."tdgal_cat SET acces=$acces WHERE id=$d[0]";
         sql_query($query);

         $queryx = "UPDATE ".$NPDS_Prefix."tdgal_gal SET acces=$acces WHERE cid=$d[0]";
         sql_query($queryx);
      }
   }
   redirect_url($ThisRedo.'&subop=viewarbo');
}

function PrintJavaCodeGal($selid) {
   global $NPDS_Prefix;
   $query = sql_query("SELECT groupe_id, groupe_name FROM ".$NPDS_Prefix."groupes ORDER BY groupe_name");
   $nbgrp = sql_num_rows($query);
   while ($mX = sql_fetch_row($query)) {
      $tmp_groupe[$mX[0]]=$mX[1];
   }
   echo '
   <script type="text/javascript">
   //<![CDATA[
      var cde_all = ["0","1","-127"];
      var txt_all = ["'.adm_translate("Public").'","'.adm_translate("Utilisateur enregistré").'","'.gal_translate("Administrateurs").'"];';
   if (count($tmp_groupe) != 0)
      foreach($tmp_groupe as $val => $nom) {
         echo '
      cde_all.push("'.$val.'"); txt_all.push("'.$nom.'");';
      }
   echo '
   function decodeHTMLEntities(text) {
     var textArea = document.createElement("textarea");
     textArea.innerHTML = text;
     return textArea.value;
   }
   function remplirAcces(index,code) {
      var x=document.getElementById("'.$selid.'");
      if(index==0) {x.options.length = 0;}
      else if(code.indexOf("('.adm_translate("Public").')") !== -1) {
         x.options.length = 0;
         function quelDroits(ac,ind) {
            var option = document.createElement("option");
            option.text = decodeHTMLEntities(ac);
            option.value = cde_all[ind];
            x.add(option);
         }
         txt_all.forEach(quelDroits);
      }
      else if(code.indexOf("('.adm_translate("Administrateurs").')") !== -1) {
         x.options.length = 0;
         function quelDroits(ac,ind) {
            if(ind==2) {
               var option = document.createElement("option");
               option.text = decodeHTMLEntities(ac);
               option.value = cde_all[ind];
               x.add(option);
            };
         }
         txt_all.forEach(quelDroits);
      }
      else if(code.indexOf("('.html_entity_decode(adm_translate("Utilisateur enregistré"),ENT_COMPAT | ENT_HTML401,cur_charset).')") !== -1) {
         x.options.length = 0;
         function quelDroits(ac,ind) {
            if(ind != 0) { 
               var option = document.createElement("option");
               option.text = decodeHTMLEntities(ac);
               option.value = cde_all[ind];
               x.add(option);
            };
         }
         txt_all.forEach(quelDroits);
      }
      else {
         x.options.length = 0;
         var cod = code.substring(code.lastIndexOf("(")+1).slice(0, -1);
         const val = txt_all.findIndex(el => el==cod);
         var option = document.createElement("option");
         option.text = cod;
         option.value = cde_all[val];
         x.add(option);

         function quelDroits(ac, ind) {
            if(ind==2) {
               var option = document.createElement("option");
               option.text = decodeHTMLEntities(ac);
               option.value = cde_all[ind];
               x.add(option);
            }
         }
         txt_all.forEach(quelDroits);
      };
   }
   //]]>
   </script>';
}

function Fab_Option_Group($GrpActu='0',$dp) {
   settype($txt,'string');
   switch($dp) {
      case -127: $priodroit = array("-127"); break;
      case 1: $priodroit = array("-127","1"); break;
      case 0: $priodroit = array("-127","0","1",$dp); break;
      case $dp>1: $priodroit = array($dp); break;
   }
   $tmp_group = Get_Name_Group('list', $GrpActu);
   foreach($tmp_group as $val => $nom) {
      if(in_array($val, $priodroit) or $dp=='tousdroits') {
         if ($val == $GrpActu)
            $txt.= '<option value="'.$val.'" selected="selected">'.$nom.'&nbsp;</option>';
         else
            $txt.= '<option value="'.$val.'">'.$nom.'&nbsp;</option>';
      }
   }
   return $txt;
}

function Get_Name_Group($ordre, $GrpActu) {
   $tmp_groupe = liste_group('');
   $tmp_groupe[-127] = gal_translate("Administrateurs");
   $tmp_groupe[0] = adm_translate("Public");
   $tmp_groupe[1] = adm_translate("Utilisateur enregistré");
   if ($ordre=='list') {
      asort($tmp_groupe);
      return ($tmp_groupe);
   } else
      return ($tmp_groupe[$GrpActu]);
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
      return stripslashes($rowX[0])." - ".stripslashes($row[0]);
   }
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

function import() {
   global $ModPath, $ModStart, $NPDS_Prefix, $ThisFile, $ThisRedo;
   $handle=opendir("modules/$ModPath/import");
   while ($file = readdir($handle)) $filelist[] = $file;
   closedir($handle);
   $j=0;
   foreach($filelist as $v) {
      if(preg_match('#\.gif|\.jpg|\.jpeg|\.png$#i', $v)) $j++;
   }
   echo '
   <h3 class="my-3">'.gal_translate("Images").'</h3>
   <hr />';
   if($j!=0) {
      echo '
   <h4>'.gal_translate("Import images").'<span class="badge badge-success float-right">'.$j.'</span></h4>
   <blockquote class="blockquote my-3">
      '.gal_translate("Images du dossier").' <code>/modules/npds_galerie/import</code><br />
      '.gal_translate("Création des images et imagettes dans").' <code>/modules/npds_galerie/imgs</code> &amp; <code>/modules/npds_galerie/mini</code><br />
      '.gal_translate("Affectation vers la galerie choisie.").'<br />
      '.gal_translate("Les images importées seront supprimées du dossier").' <code>/modules/npds_galerie/import</code>.
   </blockquote>
   <form id="massimport" method="post" action="'.$ThisFile.'" name="MassImport">
      <input type="hidden" name="subop" value="massimport" />
      <div class="form-group">
         <label class="col-form-label" for="imggal">'.gal_translate("Affectation").'</label>
         <select class="custom-select" name="imggal" id="imggal">';
      echo select_arbo('');
      echo '
         </select>
      </div>
      <div class="form-group">
         <label class="col-form-label" for="descri">'.gal_translate("Description").'</label>
         <textarea class="form-control" name="descri" id="descri" maxlength="255" rows="2"></textarea>
         <span class="help-block">'.gal_translate("Pour toutes les images de cet import.").'<span class="float-right" id="countcar_descri"></span></span>
      </div>
      <button class="btn btn-primary" type="submit">'.gal_translate("Importer").'</button>
   </form>';
      $arg1 = '
   var formulid = ["massimport"]
   inpandfieldlen("descri",255);';
      adminfoot('fv','',$arg1,'1');
   }
   else {
      echo '
      <h4 class="my-3">'.gal_translate("Import images").'<span class="badge badge-danger float-right">'.$j.'</span></h4>
      <div class="alert alert-danger">
            '.gal_translate("Aucune image dans le dossier").' <code>/modules/npds_galerie/import</code> !! <br />
      </div>';
   }
}

function massimport($imggal, $descri) {
   global $MaxSizeImg, $MaxSizeThumb, $ModPath, $ModStart, $NPDS_Prefix;

   $year = date("Y"); $month = date("m"); $day = date("d");
   $hour = date("H"); $min = date("i"); $sec = date("s");

   $handle=opendir("modules/$ModPath/import");
   while ($file = readdir($handle)) $filelist[] = $file;
   closedir($handle);
   asort($filelist);

   $i=1;
   foreach($filelist as $key => $file) {
      if (preg_match('#\.gif|\.jpg|\.jpeg|\.png$#i', strtolower($file))) {
         $filename_ext = strtolower(substr(strrchr($file, "."),1));
         $newfilename = $year.$month.$day.$hour.$min.$sec."-".$i.".".$filename_ext;
         rename("modules/$ModPath/import/$file","modules/$ModPath/import/$newfilename");
         if ((function_exists('gd_info')) or extension_loaded('gd')) {
            @CreateThumb($newfilename, "modules/$ModPath/import/", "modules/$ModPath/imgs/", $MaxSizeImg, $filename_ext);
            @CreateThumb($newfilename, "modules/$ModPath/import/", "modules/$ModPath/mini/", $MaxSizeThumb, $filename_ext);
         }
      echo '<ul class="list-group">';
         if (sql_query("INSERT INTO ".$NPDS_Prefix."tdgal_img VALUES ('0','$imggal','$newfilename','$descri','0','0','0','','')")) {
            echo '<li class="list-group-item list-group-item-success mb-1">'.gal_translate("Image ajoutée avec succès").' : '.$file.'</li>';
            $i++;
         } else {
            echo '<li class="list-group-item list-group-item-danger mb-1">'.gal_translate("Impossible d'ajouter l'image en BDD").'</li>';
            @unlink ("modules/$ModPath/imgs/$newfilename");
            @unlink ("modules/$ModPath/mini/$newfilename");
         }
         echo '</ul>';
         @unlink ("modules/$ModPath/import/$newfilename");
      }
   }
}

function ordre($ximg, $xordre, $xdesc) {
   global $ThisRedo, $NPDS_Prefix;
   while(list($ibid,$img_id)=each($ximg)) {
      echo $img_id, $xordre[$ibid].'<br />';
      sql_query("UPDATE ".$NPDS_Prefix."tdgal_img SET ordre='$xordre[$ibid]', comment='$xdesc[$ibid]' WHERE id='$img_id'");
   }
   redirect_url($ThisRedo."&subop=viewarbo");
}

function PrintExportCat() {
   global $NPDS_Prefix, $ThisFile;
   echo '
   <h5 class="mt-3">'.gal_translate("Export catégorie").'</h5>
   <hr />
   <form action="'.$ThisFile.'" method="post" name="FormCat">
      <input type="hidden" name="subop" value="massexport" />
      <div class="form-group">
         <label class= "col-form-label" for="cat">'.gal_translate("Nom de la catégorie").'</label>
         <select class="custom-select" name="cat" id="cat">
            <option value="none" selected="selected">'.gal_translate("Choisissez").'</option>';
   $query = sql_query("SELECT id,nom,acces FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='0' ORDER BY nom ASC");
   while ($row = sql_fetch_row($query)) {
      echo '
            <option value='.$row[0].'>'.stripslashes($row[1]).'</option>';
   }
   echo '
         </select>
      </div>
      <button class="btn btn-primary" type="submit">'.gal_translate("Exporter").'</button>
   </form>';
}

function MassExportCat($cat) {
   global $NPDS_Prefix, $ThisRedo, $ModPath;

   $sql_cat = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_cat WHERE id='$cat'");
   $num_cat = sql_num_rows($sql_cat);
   if ($num_cat != 0) {
      $sql_sscat = "SELECT * FROM ".$NPDS_Prefix."tdgal_cat WHERE cid=$cat";
      $sql_gal = "SELECT * FROM ".$NPDS_Prefix."tdgal_gal";
      // CATEGORIE
      $nb_gal=0;
      $nb_img=0;
      while ($row_cat = sql_fetch_row($sql_cat)) {
         $ibid.="INSERT INTO tdgal_cat VALUES ($row_cat[0], $row_cat[1], '".htmlentities($row_cat[2])."',$row_cat[3]);\n";
         $queryX = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_gal WHERE cid='".$row_cat[0]."' ORDER BY nom ASC");
         while ($rowX_gal = sql_fetch_row($queryX)) {
            $ibid.="INSERT INTO tdgal_gal VALUES ($rowX_gal[0], $rowX_gal[1], '".htmlentities($rowX_gal[2])."', $rowX_gal[3], $rowX_gal[4]);\n";
            $nb_gal++;
            // trouver les images
            $queryZ = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='".$rowX_gal[0]."' ORDER BY ordre,id");
            while ($rowZ_img = sql_fetch_row($queryZ)) {
               copy("modules/$ModPath/mini/$rowZ_img[2]","modules/$ModPath/export/mini/$rowZ_img[2]");
               copy("modules/$ModPath/imgs/$rowZ_img[2]","modules/$ModPath/export/imgs/$rowZ_img[2]");
               $ibid.="INSERT INTO ".$NPDS_Prefix."tdgal_img VALUES (NULL, $rowX_gal[0], '".htmlentities($rowZ_img[2])."', '".htmlentities($rowZ_img[3])."', 0, $rowZ_img[5], 0,$rowZ_img[7],$rowZ_img[8]);\n";
               $nb_img++;
            }
         }
         $ibid.="\n";
         // SOUS-CATEGORIE
         $query = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_cat WHERE cid='".$row_cat[0]."' ORDER BY nom ASC");
         while ($row_sscat = sql_fetch_row($query)) {
            $ibid.="INSERT INTO tdgal_cat VALUES ($row_sscat[0], $row_sscat[1], '".htmlentities($row_sscat[2])."',$row_sscat[3]);\n";
            $querx = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_gal WHERE cid='".$row_sscat[0]."' ORDER BY nom ASC");
            while ($row_gal = sql_fetch_row($querx)) {
               $ibid.="INSERT INTO tdgal_gal VALUES ($row_gal[0], $row_gal[1], '".htmlentities($row_gal[2])."', $row_gal[3], $row_gal[4]);\n";
               $nb_gal++;
               // trouver les images
               $queryZ = sql_query("SELECT * FROM ".$NPDS_Prefix."tdgal_img WHERE gal_id='".$row_gal[0]."' ORDER BY ordre,id");
               while ($rowZ_img = sql_fetch_row($queryZ)) {
                  copy("modules/$ModPath/mini/$rowZ_img[2]","modules/$ModPath/export/mini/$rowZ_img[2]");
                  copy("modules/$ModPath/imgs/$rowZ_img[2]","modules/$ModPath/export/imgs/$rowZ_img[2]");
                  $ibid.="INSERT INTO ".$NPDS_Prefix."tdgal_img VALUES (NULL, $row_gal[0], '".htmlentities($rowZ_img[2])."', '".htmlentities($rowZ_img[3])."', 0, $rowZ_img[5], 0,$rowZ_img[7],$rowZ_img[8]);\n";
                  $nb_img++;
               }
            }
         }
      }
   }
   $ibid.="\n";
   $ibid.="# ----------------------------------------\n";
   $ibid.="# Nombre de galeries exportées $nb_gal\n";
   $ibid.="# Nombre d'images exportées : $nb_img\n";
   $ibid.="# ----------------------------------------\n";
   $ibid.="# Attention les numeros de catégories et  \n";
   $ibid.="# de galeries peuvent être en conflit avec\n";
   $ibid.="# ceux de votre Galerie. \n";
   $ibid.="# ----------------------------------------\n";
   
   if ($myfile = fopen("modules/$ModPath/export/sql/export.sql", "wb")) {
      fwrite($myfile, "$ibid");
      fclose($myfile);
      unset($content);
      redirect_url($ThisRedo);
   } else
      redirect_url($ThisRedo);
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
         $source_fond='
         new ol.source.Stamen({
            layer:"'.$cartyp.'"
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
                     <div class="form-group row mb-0">
                        <label class="col-form-label col-sm-12 font-weight-bolder" for="cartyp">Type de carte</label>
                        <div class="col-sm-12">
                           <select class="custom-select form-control-sm" name="cartyp" id="cartyp">';
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
                           <input type="range" value="1" class="custom-range mt-1" min="0" max="1" step="0.1" id="baselayeropacity" />
                           <label class="mt-0 float-right small" for="baselayeropacity">Opacity</label>
                           <div id="dayslider" class="collapse">
                              <input type="range" value="1" class="custom-range mt-1" min="-6" max="0" value="0" id="nasaday" />
                              <label id="dateimages" class="mt-0 float-right small" for="nasaday">'.$date_jour.'</label>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>';
   $affi .= '
<script type="text/javascript">
   //<![CDATA[
   if (!$("link[href=\'/lib/ol/ol.css\']").length)
      $("head link[rel=\'stylesheet\']").last().after("<link rel=\'stylesheet\' href=\''.$nuke_url.'/lib/ol/ol.css\' type=\'text/css\' media=\'screen\'>");
   $("head link[rel=\'stylesheet\']").last().after("<link rel=\'stylesheet\' href=\''.$nuke_url.'/modules/npds_galerie/css/galerie.css\' type=\'text/css\' media=\'screen\'>");
   $("head link[rel=\'stylesheet\']").last().after("<link rel=\'stylesheet\' href=\''.$nuke_url.'/modules/geoloc/include/ol-geocoder.css\' type=\'text/css\' media=\'screen\'>");
   if (typeof ol=="undefined")
      $("head").append($("<script />").attr({"type":"text/javascript","src":"'.$nuke_url.'/lib/ol/ol.js"}));
   $("head").append($("<script />").attr({"type":"text/javascript","src":"'.$nuke_url.'/modules/geoloc/include/ol-geocoder.js"}));
   $(function () {
      //==>  affichage des coordonnées...
         var mousePositionControl = new ol.control.MousePosition({
          coordinateFormat: function(coord) {return ol.coordinate.format(coord, "Lat. {y}, Long. {x}", 4);},
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
      src_georef.getFeatures().forEach(feat => {feat.setStyle(
         new ol.style.Style({
            text: new ol.style.Text({
               text: "\uf030 "+feat.W.substr(2),
               font: "900 18px \'Font Awesome 5 Free\'",
               bottom: "Bottom",
               scale: [1.5, 1.5],
               fill: new ol.style.Fill({color: "rgba(0, 0, 0, 0.4)"}),
               stroke: new ol.style.Stroke({color: "rgba(255, 255, 255, 1)", width: 1})
            })
         })
      )})';
   $affi .= '
      var
         georef_marker = new ol.layer.Vector({
            id:"georef",
            source: src_georef,
            style: nolocatedstyle
         }),
         source = new ol.source.Stamen({layer:"toner"}),
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
      translate.on("translateend", function(evt) {
         var idim = (evt.features.R[0].W).substr(2),
             coordinate = evt.coordinate,
             coordWgs = ol.proj.toLonLat(coordinate);
         $("#imglat"+idim).val(coordWgs[1].toFixed(6));
         $("#imglong"+idim).val(coordWgs[0].toFixed(6));
         if((evt.features.R[0].W).substr(0, 2) ==="pg") {
            evt.features.R[0].setStyle(new ol.style.Style({
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
         var coordinate = evt.coordinate,
         coordWgs = ol.proj.toLonLat(coordinate);
         $("#imglat").val(coordWgs[1].toFixed(6));
         $("#imglong").val(coordWgs[0].toFixed(6));
         if((evt.features.R[0].W).substr(0, 2) ==="pg") {
            evt.features.R[0].setStyle(new ol.style.Style({
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
            var idf = feat.W.substr(2);
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
         $(dic.olfullscreentrue.cla).attr("data-original-title", dic["olfullscreentrue"][lang]);
      })
      fullscreen.on("leavefullscreen",function(){
         $(dic.olfullscreenfalse.cla).attr("data-original-title", dic["olfullscreenfalse"][lang]);
      })

   $("#cartyp").on("change", function() {
      cartyp = $( "#cartyp option:selected" ).val();
      $("#dayslider").removeClass("show");
      switch (cartyp) {
         case "OSM":
            fond_carte.setSource(new ol.source.OSM());
            map.getLayers().R[0].setProperties({"id":cartyp});
            fond_carte.setMinResolution(1);
         break;
         case "sat-google":
            fond_carte.setSource(new ol.source.XYZ({
               url: "https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}",
               crossOrigin: "Anonymous",
               attributions: " &middot; <a href=\"https://www.google.at/permissions/geoguidelines/attr-guide.html\">Map data ©2015 Google</a>"
            }));
            map.getLayers().R[0].setProperties({"id":cartyp});
         break;
         case "Road":case "Aerial":case "AerialWithLabels":
            fond_carte.setSource(new ol.source.BingMaps({
               key: "'.$api_key_bing.'",
               imagerySet: cartyp 
            }));
            map.getLayers().R[0].setProperties({"id":cartyp});
            fond_carte.setMinResolution(1);
         break;
         case "natural-earth-hypso-bathy": case "geography-class":
            fond_carte.setSource(new ol.source.TileJSON({
               url: "https://api.tiles.mapbox.com/v4/mapbox."+cartyp+".json?access_token='.$api_key_mapbox.'",
               attributions: "© <a href=\"https://www.mapbox.com/about/maps/\">Mapbox</a> © <a href=\"http://www.openstreetmap.org/copyright\">OpenStreetMap</a> <strong><a href=\"https://www.mapbox.com/map-feedback/\" target=\"_blank\">Improve this map</a></strong>"
            }));
            fond_carte.setMinResolution(2000);
            fond_carte.setMaxResolution(40000);
            map.getLayers().R[0].setProperties({"id":cartyp});
         break;
         case "terrain": case "toner": case "watercolor":
            fond_carte.setSource(new ol.source.Stamen({layer:cartyp}));
            fond_carte.setMinResolution(0);
            fond_carte.setMaxResolution(40000);
            map.getLayers().R[0].setProperties({"id":cartyp});
         break;
         case "modisterra":
            $("#dayslider").addClass("show");
            var datejour="'.$date_jour.'";
            var today = new Date();
            fond_carte.setSource(new ol.source.XYZ({
               url: "https://gibs-{a-c}.earthdata.nasa.gov/wmts/epsg3857/best/VIIRS_SNPP_CorrectedReflectance_TrueColor/default/"+datejour+"/GoogleMapsCompatible_Level9/{z}/{y}/{x}.jpg"
            }));
            $("#nasaday").on("input change", function(event) {
               var newDay = new Date(today.getTime());
               newDay.setUTCDate(today.getUTCDate() + Number.parseInt(event.target.value));
               datejour = newDay.toISOString().split("T")[0];
               var datejourFr = datejour.split("-");
               $("#dateimages").html(datejourFr[2]+"/"+datejourFr[1]+"/"+datejourFr[0]);
               fond_carte.setSource(new ol.source.XYZ({
                  url: "https://gibs-{a-c}.earthdata.nasa.gov/wmts/epsg3857/best/VIIRS_SNPP_CorrectedReflectance_TrueColor/default/"+datejour+"/GoogleMapsCompatible_Level9/{z}/{y}/{x}.jpg"
               }));
            });
            fond_carte.setMinResolution(2);
            fond_carte.setMaxResolution(40000);
            map.getLayers().array_[0].setProperties({"id":cartyp});
         break;
      }
   });

   // ==> opacité sur couche de base
      $("#baselayeropacity").on("input change", function() {
         map.getLayers().R[0].setOpacity(parseFloat(this.value));
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