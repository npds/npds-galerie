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
/* Page de visualisation d'une e-carte                                  */
/************************************************************************/

// For More security
if (!stristr($_SERVER['PHP_SELF'],"modules.php")) die();
if (strstr($ModPath,'..') || strstr($ModStart,'..') || stristr($ModPath, 'script') || stristr($ModPath, 'cookie') || stristr($ModPath, 'iframe') || stristr($ModPath, 'applet') || stristr($ModPath, 'object') || stristr($ModPath, 'meta') || stristr($ModStart, 'script') || stristr($ModStart, 'cookie') || stristr($ModStart, 'iframe') || stristr($ModStart, 'applet') || stristr($ModStart, 'object') || stristr($ModStart, 'meta'))
   die();
// For More security

   global $language;
   include_once("modules/$ModPath/gal_conf.php");
   include_once("modules/$ModPath/gal_func.php");
   include_once("modules/$ModPath/lang/galerie-$language.php");
   if (!isset($data)) redirect_url("modules.php?ModPath=$ModPath&ModStart=gal");

   $card_data = array();
   $card_data = @unserialize(@base64_decode($data));
   list($width, $height, $type, $attr) = getimagesize($card_data['pf']);

   echo '
<!DOCTYPE html>
   <head>
      <title>'.gal_translate("Une E-carte pour vous").'</title>
      <meta charset="utf-8" />
      <meta http-equiv="content-type" content="text/html" />
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta http-equiv="content-script-type" content="text/javascript" />
      <meta http-equiv="content-style-type" content="text/css" />
      <meta http-equiv="expires" content="0" />
      <meta http-equiv="cache-control" content="no-cache" />
      <meta http-equiv="identifier-url" content="" />
      <link href="lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
      <link rel="stylesheet" href="lib/bootstrap/dist/css/bootstrap.min.css" />
   </head>
   <body>
      <div class="col-md-8 my-3 mx-auto">
         <div class="card">
            <div class="card-header">E-card de <a href="mailto:'.$card_data['se'].'">'.$card_data['sn'].'</a></div>
            <img class="img-fluid" src="'.$card_data['pf'].'" '.$attr.' />
            <div class="card-body">
            <h4 class="card-title">'.$card_data['su'].'</h4>
            '.$card_data['ms'].'
            </div>
         </div>
      </div>
   </body>
</html>';

?>