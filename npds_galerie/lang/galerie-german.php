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
/* German Language File                                                 */
/*                                                                      */
/* (c) 2004-2005 Tribal-Dolphin - http://www.tribal-dolphin.net         */
/* (c) 2007 Xgonin, Lopez - http://modules.npds.org                     */
/* MAJ conformité XHTML pour REvolution 10.02 par jpb/phr en mars 2010  */
/* MAJ Dev - 2011                                                       */
/* MAJ jpb, phr - 2017 renommé npds_galerie pour Rev 16                 */
/* v 3.2                                                                */
/* v 3.3 jpb-2022                                                       */
/************************************************************************/

function gal_translate($phrase) {
   switch ($phrase) {
      case " par ": $tmp = " von "; break;
      case "A": $tmp = "zu"; break;
      case "Accès à la galerie": $tmp = "Zugang zur Galerie"; break;
      case "Accès pour": $tmp = "Zugriff für"; break;
      case "Accueil": $tmp = "Startseite"; break;
      case "Administrateurs": $tmp = "Administratoren"; break;
      case "Administration des galeries": $tmp = "Administration der Galerien"; break;
      case "Adresse e-mail du destinataire": $tmp = "E-Mail-Adresse des Empfängers"; break;
      case "Affectation vers la galerie choisie.": $tmp = "Zuordnung zur ausgewählten Galerie."; break;
      case "affichage(s)": $tmp = "Ansicht (en)"; break;
      case "Affichées": $tmp = "Angezeigt"; break;
      case "Afficher des photos aléatoires ?": $tmp = "Zufällige Bilder anzeigen?"; break;
      case "Afficher les commentaires ?": $tmp = "Kommentare anzeigen?"; break;
      case "Afficher les derniers ajouts ?": $tmp = "Zuletzt hinzugefügte Bilder anzeigen?";break;
      case "Afficher les votes ?": $tmp = "Bewertungen anzeigen?"; break;
      case "Ajout catégorie": $tmp = "Kategorie hinzufügen"; break;
      case "Ajout galerie": $tmp = "Galerie hinzufügen"; break;
      case "Ajout images": $tmp = "Bilder hinzufügen"; break;
      case "Ajout sous-catégorie": $tmp = "Unterkategorie hinzufügen"; break;
      case "Ajouter": $tmp = "Hinzufügen "; break;
      case "Ajoutez votre commentaire": $tmp = "Kommentar hinzufügen"; break;
      case "Annuler": $tmp = "Abbrechen"; break;
      case "Arborescence": $tmp = "Baumstruktur"; break;
      case "Aucune catégorie trouvée": $tmp = "Keine Kategorie gefunden"; break;
      case "Aucune galerie trouvée": $tmp = "Keine Galerie gefunden"; break;
      case "Aucune image dans le dossier": $tmp = "Keine Bilder im Ordner"; break;
      case "Aucune image trouvée": $tmp = "Kein Bild gefunden"; break;
      case "Aucune sous-catégorie trouvée": $tmp = "Keine Unterkategorie gefunden"; break;
      case "Catégorie non supprimée": $tmp = "Kategorie nicht gelöscht"; break;
      case "Catégorie parente": $tmp = "Übergeordnete Kategorie"; break;
      case "Catégorie supprimée": $tmp = "Kategorie gelöscht"; break;
      case "Catégorie": $tmp = "Kategorie"; break;
      case "Catégories": $tmp = "Kategorien"; break;
      case "Ce fichier n'est pas un fichier jpg, gif ou png": $tmp = "Diese Datei ist keine JPG, GIF - oder PNG-Datei"; break;
      case "Cette catégorie existe déjà": $tmp = "Diese Kategorie existiert bereits"; break;
      case "Cette galerie existe déjà": $tmp = "Diese Galerie existiert bereits"; break;
      case "Cette sous-catégorie existe déjà": $tmp = "Diese Unterkategorie existiert bereits"; break;
      case "Choisissez": $tmp = "Wählen"; break;
      case "Cliquer pour déplier": $tmp = "Zum Vergrößern klicken"; break;
      case "Cliquer sur image": $tmp = "Klicken Sie auf das Bild"; break;
      case "Commentaire": $tmp = "Kommentar"; break;
      case "commentaire(s)": $tmp = "Kommentar (e)"; break;
      case "Commentaire(s)": $tmp = "Kommentar (e)"; break;
      case "Commentaires non supprimés": $tmp = "Kommentare nicht gelöscht"; break;
      case "Commentaires supprimés": $tmp = "Kommentare gelöscht"; break;
      case "Configuration": $tmp = "Konfiguration"; break;
      case "Confirmer": $tmp = "Bestätigen"; break;
      case "Création des images et imagettes dans": $tmp = "Erstellen von Bildern und Miniaturansichten in"; break;
      case "Créée le": $tmp = "Erstellt am"; break;
      case "De la part de": $tmp = "Im Namen von"; break;
      case "Derniers ajouts": $tmp = "Letzte Ergänzungen"; break;
      case "des images les plus commentées": $tmp = "am meisten kommentierte Bilder"; break;
      case "des images les plus notées": $tmp = "am meisten bekannte Bilder"; break;
      case "Des photos viennent d'être proposées dans la galerie photo du site ": $tmp = "Fotos wurden in der Fotogalerie der Website eingereicht"; break;
      case "Description": $tmp = "Beschreibung"; break;
      case "Diaporama": $tmp = "Diashow"; break;
      case "Dimension maximale de l'image en pixels": $tmp = "Maximale Dimension des Bildes in Pixel"; break;
      case "Dimension maximale de l'image incorrecte": $tmp = "Maximale falsche Bildgröße"; break;
      case "Dimension maximale de la miniature en pixels": $tmp = "Maximale Abmessung der Miniatur in Pixel"; break;
      case "Dimension maximale de la miniature incorrecte": $tmp = "Maximale falsche Miniaturgröße"; break;
      case "Dimensions": $tmp = "Abmessungen"; break;
      case "Editer": $tmp = "Bearbeiten"; break;
      case "E-carte": $tmp = "E-Card"; break;
      case "Effacer": $tmp = "Löschen"; break;
      case "Enregistrement non supprimé": $tmp = "Datensatz nicht gelöscht"; break;
      case "Enregistrement supprimé": $tmp = "Datensatz gelöscht"; break;
      case "Envoyer une E-carte": $tmp = "Als E-Card senden"; break;
      case "Envoyer": $tmp = "Senden"; break;
      case "envoyée à la validation du webmaster": $tmp = "für Webmaster Validierung gesendet"; break;
      case "Erreur lors de l'ajout de la catégorie": $tmp = "Fehler beim Hinzufügen der Kategorie"; break;
      case "Erreur lors de l'ajout de la galerie": $tmp = "Fehler beim Hinzufügen der Galerie"; break;
      case "Erreur lors de l'ajout de la sous-catégorie": $tmp = "Fehler beim Hinzufügen der Unterkategorie"; break;
      case "Erreur": $tmp = "Fehler"; break;
      case "Export catégorie": $tmp = "Exportkategorie"; break;
      case "Exporter": $tmp = "Exportieren"; break;
      case "Filtrer les images": $tmp = "Bilder filtern"; break;
      case "fois": $tmp = "Mal"; break;
      case "Galerie non supprimée": $tmp = "Galerie nicht gelöscht"; break;
      case "Galerie Privée, connectez vous": $tmp = "Private Galerie, verbinde dich"; break;
      case "Galerie supprimée": $tmp = "Galerie gelöscht"; break;
      case "Galerie temporaire": $tmp = "Temporäre Galerie"; break;
      case "Galerie": $tmp = "Galerie"; break;
      case "Galeries de photos": $tmp = "Fotogalerien"; break;
      case "Galeries": $tmp = "Galerien"; break;
      case "Image ajoutée avec succès": $tmp = "Bild erfolgreich hinzugefügt"; break;
      case "Image non supprimée": $tmp = "Bild nicht gelöscht"; break;
      case "Image supprimée": $tmp = "Bild gelöscht"; break;
      case "Image": $tmp = "Bild"; break;
      case "Images du dossier": $tmp = "Ordnerbilder"; break;
      case "Images géoréférencées": $tmp = "Georeferenzierte Bilder"; break;
      case "Image géoréférencée": $tmp = "Georeferenzierte Bild"; break;
      case "Images vues": $tmp = "Angezeigte Bilder"; break;
      case "Images": $tmp = "Bilder"; break;
      case "image(s)": $tmp = "bild(er)"; break;
      case "Import images": $tmp = "Bilder importieren"; break;
      case "Importer": $tmp = "Importieren"; break;
      case "Impossible d'ajouter l'image en BDD": $tmp = "Das Bild kann nicht in die Datenbank eingefügt werden"; break;
      case "Informations sur l'image": $tmp = "Bildinformationen"; break;
      case "Informations": $tmp = "Information"; break;
      case "L'adresse mail du destinataire est incorrecte.": $tmp = "Die E-Mail-Adresse des Empfängers ist falsch."; break;
      case "La modification du droit d'accès à cette catégorie entraine de facto la modification des droits d'accès à TOUTES les sous catégories et galeries qui en dépendent.": $tmp = "Die Änderung des Zugriffsrechts auf diese Kategorie beinhaltet de facto die Änderung der Zugriffsrechte auf ALLE davon abhängigen Unterkategorien und Galerien."; break;
      case "Latitude": $tmp = "Breitengrad"; break;
      case "Le message ne peut être vide.": $tmp = "Die Nachricht darf nicht leer sein."; break;
      case "Le nom du destinataire ne peut être vide.": $tmp = "Empfängername darf nicht leer sein."; break;
      case "Le sujet ne peut être vide.": $tmp = "Das Thema darf nicht leer sein."; break;
      case "Les anonymes peuvent envoyer des E-Cartes ?": $tmp = "Anonym kann E-Cards senden?"; break;
      case "Les anonymes peuvent poster un commentaire ?": $tmp = "Anonym kann Kommentare abgeben?"; break;
      case "Les anonymes peuvent voter ?": $tmp = "Anonym kann bewerten?"; break;
      case "Les images importées seront supprimées du dossier": $tmp = "Importierte Bilder werden aus dem Ordner gelöscht"; break;
      case "Longitude": $tmp = "Längengrad"; break;
      case "MAJ ordre": $tmp = "Bildreihenfolge aktualisieren"; break;
      case "Message": $tmp = "Nachricht"; break;
      case "Miniature non supprimée": $tmp = "Vorschaubild nicht gelöscht"; break;
      case "Miniature supprimée": $tmp = "Vorschaubild gelöscht"; break;
      case "Modifier": $tmp = "Ändern"; break;
      case "Nom actuel": $tmp = "Aktueller Name"; break;
      case "Nom de la catégorie": $tmp = "Name der Kategorie"; break;
      case "Nom de la galerie": $tmp = "Name der Galerie"; break;
      case "Nom de la sous-catégorie": $tmp = "Name der Unterkategorie"; break;
      case "Nom du destinataire": $tmp = "Empfängername"; break;
      case "Nombre d'images à afficher dans le top commentaires": $tmp = "Anzahl der Bilder, die im Top-Kommentar angezeigt werden sollen"; break;
      case "Nombre d'images à afficher dans le top votes": $tmp = "Anzahl der Bilder, die in der Top-Abstimmung angezeigt werden sollen"; break;
      case "Nombre d'images à valider": $tmp = "Anzahl der zu validierenden Bilder"; break;
      case "Nombre d'images par page": $tmp = "Anzahl der Bilder pro Seite"; break;
      case "Nombre d'images": $tmp = "Anzahl der Bilder"; break;
      case "Nombre de catégories": $tmp = "Anzahl der Kategorien"; break;
      case "Nombre de commentaires": $tmp = "Anzahl der Kommentare"; break;
      case "Nombre de galeries": $tmp = "Anzahl der Galerien"; break;
      case "Nombre de notes": $tmp = "Anzahl der Noten"; break; 
      case "Nombre de sous-catégories": $tmp = "Anzahl der Unterkategorien"; break;
      case "Nombre de vote(s)": $tmp = "Anzahl der Stimmen"; break;
      case "Note ": $tmp = "Note "; break;
      case "Noter cette image": $tmp = "Dieses Bild bewerten."; break;
      case "Notifier par email l'administrateur de la proposition de photos ?": $tmp = "Benachrichtigen Sie den Administrator per E-Mail über die Angebotsfotos?"; break;
      case "Nouveau nom": $tmp = "Neuer Name"; break;
      case "Nouvelle soumission de Photos": $tmp = "Neue Fotoübermittlung"; break;
      case "Photo envoyée avec succés, elle sera traitée par le webmaster": $tmp = "Foto erfolgreich gesendet, es wird vom Webmaster behandelt"; break;
      case "Photos aléatoires": $tmp = "Zufällige Bilder"; break;
      case "Posté le": $tmp = "Gepostet am"; break;
      case "Pour toutes les images de cet import.": $tmp = "Für alle Bilder dieses Imports."; break;
      case "proposé par": $tmp = "vorgeschlagen von"; break;
      case "Proposer des images": $tmp = "Bilder vorschlagen"; break;
      case "Résultat": $tmp = "Ergebnis"; break;
      case "Sélectionner votre image": $tmp = "Wählen Sie Ihr Bild aus"; break;
      case "Si votre e-carte ne s'affiche pas correctement, cliquez ici": $tmp = "Wenn die E-Card nicht richtig angezeigt wird, klicken Sie auf diesen Link"; break;
      case "Sous-catégorie non supprimée": $tmp = "Unterkategorie nicht gelöscht"; break;
      case "Sous-catégorie supprimée": $tmp = "Unterkategorie gelöscht"; break;
      case "Sous-catégorie": $tmp = "Unterkategorie"; break;
      case "Sous-catégories": $tmp = "Unterkategorien"; break;
      case "Sujet": $tmp = "Thema"; break;
      case "Suspendre le Diaporama": $tmp = "Diashow anhalten"; break;
      case "Tableau récapitulatif": $tmp = "Übersichtstabelle"; break;
      case "Taille du fichier": $tmp = "Dateigröße"; break;
      case "Télécharger comme image.png": $tmp = "Karte als image.png herunterladen"; break;
      case "Top-Commentaires": $tmp = "Top-Kommentare"; break;
      case "Top-Votes": $tmp = "Top-Vote"; break;
      case "Top": $tmp = "Top"; break;
      case "Une e-carte pour vous": $tmp = "Eine E-Card für Sie"; break;
      case "Valider": $tmp = "Validieren"; break;
      case "Version du module": $tmp = "Modulversion"; break;
      case "vote(s)": $tmp = "Abstimmung(en)"; break;
      case "Votes non supprimés": $tmp = "Bewertungen nicht gelöscht"; break;
      case "Votes supprimés": $tmp = "Bewertung gelöscht"; break;
      case "Votre adresse e-mail": $tmp = "Ihre E-Mail-Adresse"; break;
      case "Votre adresse mail est incorrecte.": $tmp = "Ihre E-Mail-Adresse ist falsch."; break;
      case "Votre E-carte a été envoyée": $tmp = "Ihre E-Card wurde gesendet"; break;
      case "Votre E-carte n'a pas été envoyée": $tmp = "Ihre E-Card wurde nicht gesendet."; break;
      case "Votre nom": $tmp = "Ihr Name"; break;
      case "Vous allez supprimer": $tmp = "Sie gehen zu löschen"; break;
      case "Vous allez supprimer la catégorie": $tmp = "Sie werden die Kategorie löschen"; break;
      case "Vous allez supprimer la galerie": $tmp = "Sie werden die Galerie löschen"; break;
      case "Vous allez supprimer la sous-catégorie": $tmp = "Sie werden die Unterkategorie löschen"; break;
      case "Vous allez supprimer une image": $tmp = "Sie werden ein Bild löschen"; break;
      case "Vous avez déjà commenté cette photo": $tmp = "Sie haben dieses Foto bereits kommentiert"; break;
      case "Vous avez déjà noté cette photo": $tmp = "Sie haben dieses Foto bereits bewertet"; break;
      case "Vous n'avez accès à aucune galerie": $tmp = "Sie haben keinen Zugriff auf eine Galerie"; break;
      default: $tmp = "Übersetzungsfehler [** $phrase **]"; break;
   }
   return (htmlentities($tmp,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML401,cur_charset));
}
?>