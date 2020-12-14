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
/* MAJ jpb, phr - 2017 renommé npds_galerie pour Rev 16                 */
/* v 3.2                                                                */
/* Spanish Language File                                                */
/************************************************************************/

function gal_translate($phrase) {
   switch ($phrase) {
      case " par ": $tmp = " por "; break;
      case "A": $tmp = "A"; break;
      case "Accès à la galerie": $tmp = "Acceso a la galería"; break;
      case "Accès pour": $tmp = "Acceso para"; break; 
      case "Accueil": $tmp = "Inicio"; break;
      case "Administrateurs": $tmp = "Administradores"; break;
      case "Administration des galeries": $tmp = "Administración de las galerías"; break;
      case "Adresse e-mail du destinataire": $tmp = "Email del destinatario"; break;
      case "Affectation vers la galerie choisie.": $tmp = "Asignación a la galería elegida"; break;
      case "affichage(s)": $tmp = "vista(s)"; break;
      case "Affichées": $tmp = "Desplegado"; break;
      case "Afficher des photos aléatoires ?": $tmp = "¿Ver fotos aleatorias?"; break;
      case "Afficher les commentaires ?": $tmp = "¿Mostrar comentarios?"; break;
      case "Afficher les derniers ajouts ?": $tmp = "¿Ver las últimas imágenes agregadas?";break;
      case "Afficher les votes ?": $tmp = "¿Mostrar los votos?"; break;
      case "Ajout catégorie": $tmp = "Agregar categoria"; break;
      case "Ajout galerie": $tmp = "Agregar galería"; break;
      case "Ajout images": $tmp = "Agregar foto"; break;
      case "Ajout sous-catégorie": $tmp = "Agregar Subcategoría"; break;
      case "Ajouter": $tmp = "Agregar"; break;
      case "Ajoutez votre commentaire": $tmp = "Agrega tu comentario"; break;
      case "Annuler": $tmp = "Anular"; break;
      case "Arborescence": $tmp = "Estructura de árbol"; break; 
      case "Aucune catégorie trouvée": $tmp = "Ninguna categoría encontrada"; break;
      case "Aucune galerie trouvée": $tmp = "Ningun album encontrado"; break;
      case "Aucune image dans le dossier": $tmp = "Ninguna imagen en la carpeta"; break;
      case "Aucune image trouvée": $tmp = "Ninguna imagen encontrada"; break;
      case "Aucune sous-catégorie trouvée": $tmp = "Ninguna subcategoría encontrada"; break;
      case "Catégorie non supprimée": $tmp = "Categoría no eliminada"; break;
      case "Catégorie parente": $tmp = "Categoría principal"; break; 
      case "Catégorie supprimée": $tmp = "Categoría eliminada"; break;
      case "Catégorie": $tmp = "Categoría"; break;
      case "Catégories": $tmp = "Categorías"; break;
      case "Ce fichier n'est pas un fichier jpg, gif ou png": $tmp = "Este archivo no es un archivo jpg, gif o png"; break;
      case "Cette catégorie existe déjà": $tmp = "Esta categoría ya existe"; break;
      case "Cette galerie existe déjà": $tmp = "Esta galería ya existe"; break;
      case "Cette sous-catégorie existe déjà": $tmp = "Esta subcategoría ya existe"; break;
      case "Choisissez": $tmp = "Elegir"; break; 
      case "Cliquer pour déplier": $tmp = "Haga clic para expandir"; break;
      case "Cliquer sur image": $tmp = "Haga clic en la foto"; break;
      case "Commentaire": $tmp = "Comentario"; break;
      case "commentaire(s)": $tmp = "comentario(s)"; break;
      case "Commentaire(s)": $tmp = "Comentario(s)"; break;
      case "Commentaires non supprimés": $tmp = "Comentarios no eliminados"; break;
      case "Commentaires supprimés": $tmp = "Comentarios eliminados"; break;
      case "Configuration": $tmp = "configuración"; break;
      case "Confirmer": $tmp = "Confirmar"; break;
      case "Création des images et imagettes dans": $tmp = "Creación de imágenes y miniaturas en"; break;
      case "Créée le": $tmp = "Creado el"; break;
      case "De la part de": $tmp = "De la parte de"; break;
      case "Derniers ajouts": $tmp = "Últimas incorporaciones"; break;
      case "des images les plus commentées": $tmp = "la mayoria de los conocidos"; break;
      case "des images les plus notées": $tmp = "la mayoria de los cuadros"; break;
      case "Des photos viennent d'être proposées dans la galerie photo du site ": $tmp = "Fotos fueron presentados en la galeria de fotos del sitio "; break;
      case "Description": $tmp = "Descripción"; break;
      case "Diaporama": $tmp = "Diapositiva"; break;
      case "Dimension maximale de l'image en pixels": $tmp = "Tamaño máximo de la imagen en píxeles"; break;
      case "Dimension maximale de l'image incorrecte": $tmp = "Tamaño máximo de la imagen incorrecta"; break;
      case "Dimension maximale de la miniature en pixels": $tmp = "Tamaño máximo de la miniatura en píxeles"; break;
      case "Dimension maximale de la miniature incorrecte": $tmp = "Tamaño máximo de la miniatura incorrecta"; break;
      case "Dimensions": $tmp = "Dimensiones"; break;
      case "Editer": $tmp = "Editar"; break;
      case "E-carte": $tmp = "E-tarjeta"; break;
      case "Effacer": $tmp = "Borrar"; break;
      case "Enregistrement non supprimé": $tmp = "Record not deleted"; break;
      case "Enregistrement supprimé": $tmp = "Record deleted"; break;
      case "Envoyer une E-carte": $tmp = "Enviar una E-tarjeta"; break;
      case "Envoyer": $tmp = "Enviar"; break;
      case "envoyée à la validation du webmaster": $tmp = "enviado para validación del webmaster"; break;
      case "Erreur lors de l'ajout de la catégorie": $tmp = "Error durante la adición de la categoría"; break;
      case "Erreur lors de l'ajout de la galerie": $tmp = "Error durante la adición de la galería"; break;
      case "Erreur lors de l'ajout de la sous-catégorie": $tmp = "Error durante la adición de la subcategoría"; break;
      case "Erreur": $tmp = "Error"; break;
      case "Export catégorie": $tmp = "Exportar categoría"; break;
      case "Exporter": $tmp = "Exportar"; break;
      case "Filtrer les images": $tmp = "Filtrar imágenes"; break;
      case "fois": $tmp = "vez(ces)"; break;
      case "Galerie non supprimée": $tmp = "Galería no eliminada"; break;
      case "Galerie Privée, connectez vous": $tmp = " Galeria privado, Conectarle"; break;
      case "Galerie supprimée": $tmp = "Galería eliminada"; break;
      case "Galerie temporaire": $tmp = "Temporal de la galeria"; break; 
      case "Galerie": $tmp = "galería"; break;
      case "Galeries de photos": $tmp = "Galerías de fotos"; break;
      case "Galeries": $tmp = "Albumes"; break;
      case "Image ajoutée avec succès": $tmp = "Imagen añadida correctamente"; break;
      case "Image non supprimée": $tmp = "Imagen no eliminada"; break;
      case "Image supprimée": $tmp = "Imagen eliminada"; break;
      case "Image": $tmp = "Foto"; break;
      case "Images du dossier": $tmp = "Imágenes de carpeta"; break;
      case "Images géoréférencées": $tmp = "Imágenes georreferenciadas"; break;
      case "Image géoréférencée": $tmp = "Imagen georreferenciada"; break;
      case "Images vues": $tmp = "Imágenes vistas"; break;
      case "Images": $tmp = "Fotos"; break;
      case "image(s)": $tmp = "foto(s)"; break;
      case "Import images": $tmp = "Importa fotos"; break;
      case "Importer": $tmp = "Importar"; break;
      case "Impossible d'ajouter l'image en BDD": $tmp = "Imposible agregar la imagen en DB"; break;
      case "Informations sur l'image": $tmp = "Informaciones de la imagen"; break;
      case "Informations": $tmp = "Informaciones"; break;
      case "L'adresse mail du destinataire est incorrecte.": $tmp = "El Email del destinatario no es correcto."; break;
      case "La modification du droit d'accès à cette catégorie entraine de facto la modification des droits d'accès à TOUTES les sous catégories et galeries qui en dépendent.": $tmp = "La modificación del derecho de acceso a esta categoría conlleva de facto la modificación de los derechos de acceso a TODAS las subcategorías y galerías que dependen de ella."; break;
      case "Latitude": $tmp = "Latitud"; break;
      case "Le message ne peut être vide.": $tmp = "El mensaje no puede estar vacio."; break;
      case "Le nom du destinataire ne peut être vide.": $tmp = "El nombre del destinatario no puede estar vacio."; break;
      case "Le sujet ne peut être vide.": $tmp = "El asunto no puede estar vacio."; break;
      case "Les anonymes peuvent envoyer des E-Cartes ?": $tmp = "¿Anónimo puede enviar ECards?"; break;
      case "Les anonymes peuvent poster un commentaire ?": $tmp = "¿Anónimo puede hacer comentarios?"; break;
      case "Les anonymes peuvent voter ?": $tmp = "¿Anónimo puede votar?"; break;
      case "Les images importées seront supprimées du dossier": $tmp = "Imported images will be deleted from the folder"; break;
      case "Longitude": $tmp = "Longitud"; break;
      case "MAJ ordre": $tmp = "Update order"; break;
      case "Message": $tmp = "Mensaje"; break;
      case "Miniature non supprimée": $tmp = "Miniatura no eliminada"; break;
      case "Miniature supprimée": $tmp = "Miniatura eliminada"; break;
      case "Modifier": $tmp = "Modificar"; break;
      case "Nom actuel": $tmp = "Nombre actual"; break;
      case "Nom de la catégorie": $tmp = "Nombre de categoría"; break;
      case "Nom de la galerie": $tmp = "Nombre de galeria"; break;
      case "Nom de la sous-catégorie": $tmp = "Nombre de la subcategoría"; break; 
      case "Nom du destinataire": $tmp = "Nombre del destinatario"; break;
      case "Nombre d'images à afficher dans le top commentaires": $tmp = "Número de imágenes para mostrar en los comentarios principales"; break;
      case "Nombre d'images à afficher dans le top votes": $tmp = "Número de imágenes para mostrar en los votos más altos"; break;
      case "Nombre d'images à valider": $tmp = "Número de fotografías a validar"; break;
      case "Nombre d'images par page": $tmp = "Número de imágenes por página"; break;
      case "Nombre d'images": $tmp = "Número de imágenes"; break;
      case "Nombre de catégories": $tmp = "Número de categorias"; break; 
      case "Nombre de commentaires": $tmp = "Número de comentarios"; break;
      case "Nombre de galeries": $tmp = "Número de galerias"; break;
      case "Nombre de notes": $tmp = "Número de votos"; break; 
      case "Nombre de sous-catégories": $tmp = "Número de subcategorias"; break; 
      case "Nombre de vote(s)": $tmp = "Número de votos"; break; 
      case "Note ": $tmp = "Nota "; break;
      case "Noter cette image": $tmp = "Notar esta imagen"; break;
      case "Notifier par email l'administrateur de la proposition de photos ?": $tmp = "¿Notificar al administrador de la propuesta de fotografía por correo electrónico?"; break;
      case "Nouveau nom": $tmp = "Nuevo nombre"; break;
      case "Nouvelle soumission de Photos": $tmp = "Envío de nuevas fotos"; break;
      case "Photo envoyée avec succés, elle sera traitée par le webmaster": $tmp = "Foto enviada con exito, sera manejado por el webmaster";
      case "Photos aléatoires": $tmp = "Fotos aleatorias"; break;
      case "Posté le": $tmp = "Publicado el"; break;
      case "Pour toutes les images de cet import.": $tmp = "Para todas las imágenes de esta importación."; break;
      case "proposé par": $tmp = "propuesto por"; break;
      case "Proposer des images": $tmp = "Para proponer cuadros"; break;
      case "Résultat": $tmp = "Resultado"; break;
      case "Sélectionner votre image": $tmp = "Seleccione su imagen"; break; 
      case "Si votre e-carte ne s'affiche pas correctement, cliquez ici": $tmp = "Si su E-tarjeta no se muestra correctamente, haga clic aquí"; break;
      case "Sous-catégorie non supprimée": $tmp = "Subcategoría no eliminada"; break;
      case "Sous-catégorie supprimée": $tmp = "Subcategoría eliminada"; break;
      case "Sous-catégorie": $tmp = "Subcategoría"; break;
      case "Sous-catégories": $tmp = "Subcategorías"; break;
      case "Sujet": $tmp = "Asunto"; break;
      case "Suspendre le Diaporama": $tmp = "Suspender la Diapositiva"; break;
      case "Tableau récapitulatif": $tmp = "Tabla de resumen"; break;
      case "Taille du fichier": $tmp = "Tamaño de archivo"; break;
      case "Télécharger comme image.png": $tmp = "Descargar mapa como imagen.png"; break;
      case "Top-Commentaires": $tmp = "Top-Comentario"; break; 
      case "Top-Votes": $tmp = "Top-Votos"; break;
      case "Top": $tmp = "Top"; break;
      case "Une e-carte pour vous": $tmp = "Una E-tarjeta para usted"; break;
      case "Valider": $tmp = "Validar"; break;
      case "Version du module": $tmp = "Versión del módulo"; break;
      case "vote(s)": $tmp = "voto(s)"; break;
      case "Votes non supprimés": $tmp = "Calificaciones no eliminadas"; break;
      case "Votes supprimés": $tmp = "Calificaciones eliminadas"; break;
      case "Votre adresse e-mail": $tmp = "Su Email"; break;
      case "Votre adresse mail est incorrecte.": $tmp = "Su Email no es valida."; break;
      case "Votre E-CARTE a été envoyé.": $tmp = "Su E-CARD ha sido enviada."; break;
      case "Votre E-CARTE n'a pas été envoyé.": $tmp = "Su E-CARD no ha sido enviada."; break;
      case "Votre nom": $tmp = "Su nombre"; break;
      case "Vous allez supprimer": $tmp = "Vas a borrar"; break;
      case "Vous allez supprimer la catégorie": $tmp = "Vas a borrar la categoría"; break;
      case "Vous allez supprimer la galerie": $tmp = "Vas a borrar la galería"; break;
      case "Vous allez supprimer la sous-catégorie": $tmp = "Vas a borrar la subcategoría"; break;
      case "Vous allez supprimer une image": $tmp = "Vas a borrar una imagen"; break;
      case "Vous avez déjà commenté cette photo": $tmp = "Ya has comentado esta foto"; break;
      case "Vous avez déjà noté cette photo": $tmp = "Ya has valorado esta foto"; break;
      case "Vous n'avez accès à aucune galerie": $tmp = "No tienes acceso"; break;
      default: $tmp = "Necesita ser traducido [** $phrase **]"; break;
   }
   return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>