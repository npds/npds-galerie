<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* npds_galerie 3.3                                                     */
/*                                                                      */
/* NPDS Copyright (c) 2002-2025 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
/* Attention Ce fichier doit contenir du javascript compatible tiny_mce */
/* qui doit obligatoirement se trouver concaténer dans la variable $tmp */
/************************************************************************/

$tmp.= "
setup: function (editor) {
   editor.on('keyup', function() {
      // Revalidate the comment field
      fv.revalidateField('card_msg');
    });
  },";

?>