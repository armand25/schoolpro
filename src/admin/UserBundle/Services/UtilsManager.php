<?php

/**
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ApplicationManager
 *
 * @author Edmond
 */

namespace admin\UserBundle\Services;

class UtilsManager {

    /*
     * Supprime les espaces blanc, les tabulation, les retours à la ligne, les retours chariot dans une chaine de caractères
     * 
     * @param type $chaine
     * @return type
     */
    function trimUltime($chaine) {
        $chaineTrim = trim($chaine);
        $chaine1 = str_replace("t", " ", $chaineTrim);
        $chaine2 = str_replace("r", " ", $chaine1);
        $chaine3 = str_replace("n", " ", $chaine2);
        return  preg_replace("( +)", " ", $chaine3);
    }

    /*
     * Vérifie si une chaine de caracteres est une adresse email
     * @param type $email
     * @return type
     */
    function isEmail($email = "") {
        $regexEmail = "#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#";

        return (preg_match($regexEmail, $email)) ? TRUE : FALSE;
    }

    /*
     * Création d'image à partir d'une autre
     * 
     * @param string $pathFolderOfFile : le repertoir contenant le fichier image source 
     * @param string $nomOldFile : nom du fichier image source (sans l'extension)
     * @param string $extensionFile : extension de l'image source
     * @param string $pathRepertoireNewFile : le repertoir destination de l'image générée
     * @param string $nomNewFile : nom de la nouvelle image à générer
     * @param int $heigthNewFile : la hauteur souhaité pour la nouvelle image
     * @param int $widthNewFile : le longeur souhaité pour la nouvelle image
     * @param boolean $deleteOldFile : renseigne si après génération de la nouvelle image, s'il faut supprimer l'image source
     * @return array()
     */
    function genererImage($pathFolderOfFile, $nomOldFile, $extensionFile, $pathRepertoireNewFile, $nomNewFile, $heigthNewFile = 130, $widthNewFile = 130, $deleteOldFile = FALSE) {
        $rep = array('etat' => FALSE, 'msg' => '');

        // on vérifie si l'image source existe
        if (!file_exists($pathFolderOfFile) || !file_exists($pathFolderOfFile . '/' . $nomOldFile . '.' . $extensionFile) || !file_exists($pathRepertoireNewFile)) {
            $rep['msg'] = 'Les repertoires specifiés n\'existent pas';
            return $rep;
        }
        // conversion de l'extention de l'image source en minuscule
        $extensionFile = strtolower($extensionFile);
        // cas d'une image jpeg ou jpg
        if ($extensionFile == 'jpg' || $extensionFile == 'jpeg') {
            // création d'une image source
            $source = imagecreatefromjpeg($pathFolderOfFile . '/' . $nomOldFile . "." . $extensionFile);
            // Les fonctions imagesx et imagesy renvoient la largeur et la hauteur d'une image
            $largeur_source = imagesx($source);
            $hauteur_source = imagesy($source);
            // détermination des dimmensions de la nouvelle image 
            $widthNewFile = ($widthNewFile < $largeur_source) ? $widthNewFile : $largeur_source;
            $heigthNewFile = ($heigthNewFile < $hauteur_source) ? $heigthNewFile : $hauteur_source;
            // ajustement pour conserver le rapport afin de ne pas déformer la nouvelle image
            if ($widthNewFile > $heigthNewFile) {
                $widthNewFile = $heigthNewFile * ($largeur_source / $hauteur_source);
            } else {
                $heigthNewFile = $widthNewFile * ($hauteur_source / $largeur_source);
            }
            // création de la nouvelle image à partir descouleurs
            $destination = imagecreatetruecolor($widthNewFile, $heigthNewFile); // On crée la  miniature vide
            // On crée la miniature
            imagecopyresampled($destination, $source, 0, 0, 0, 0, $widthNewFile, $heigthNewFile, $largeur_source, $hauteur_source);
            // génération de la nouvelle image dans le repertoir vslu
            imagejpeg($destination, $pathRepertoireNewFile . '/' . $nomNewFile . "." . $extensionFile);
            $rep['etat'] = TRUE;
        } else if ($extensionFile == 'gif') {
            $source = imagecreatefromgif($pathFolderOfFile . '/' . $nomOldFile . "." . $extensionFile);
            $largeur_source = imagesx($source);
            $hauteur_source = imagesy($source);

            $widthNewFile = ($widthNewFile < $largeur_source) ? $widthNewFile : $largeur_source;
            $heigthNewFile = ($heigthNewFile < $hauteur_source) ? $heigthNewFile : $hauteur_source;

            if ($widthNewFile > $heigthNewFile) {
                $widthNewFile = $heigthNewFile * ($largeur_source / $hauteur_source);
            } else {
                $heigthNewFile = $widthNewFile * ($hauteur_source / $largeur_source);
            }


            $destination = imagecreatetruecolor($widthNewFile, $heigthNewFile); // On crée la  miniature vide
            // On crée la miniature
            imagecopyresampled($destination, $source, 0, 0, 0, 0, $widthNewFile, $heigthNewFile, $largeur_source, $hauteur_source);
            // On enregistre la miniature sous le nom "mini_couchersoleil.jpg"
            imagegif($destination, $pathRepertoireNewFile . '/' . $nomNewFile . "." . $extensionFile);
            $rep['etat'] = TRUE;
        } else if ($extensionFile == 'png') {
            $source = imagecreatefrompng($pathFolderOfFile . '/' . $nomOldFile . "." . $extensionFile);
            $largeur_source = imagesx($source) - 1;
            $hauteur_source = imagesy($source) - 1;

            $widthNewFile = ($widthNewFile < $largeur_source) ? $widthNewFile : $largeur_source;
            $heigthNewFile = ($heigthNewFile < $hauteur_source) ? $heigthNewFile : $hauteur_source;

            if ($widthNewFile > $heigthNewFile) {
                $widthNewFile = $heigthNewFile * ($largeur_source / $hauteur_source);
            } else {
                $heigthNewFile = $widthNewFile * ($hauteur_source / $largeur_source);
            }

            $destination = imagecreatetruecolor($widthNewFile, $heigthNewFile); // On crée la  miniature vide
            // On crée la miniature
            imagecopyresampled($destination, $source, 0, 0, 0, 0, $widthNewFile, $heigthNewFile, $largeur_source, $hauteur_source);
            imagepng($destination, $pathRepertoireNewFile . '/' . $nomNewFile . "." . $extensionFile);
            $rep['etat'] = TRUE;
        }
        if ($deleteOldFile) {
            if (file_exists($pathFolderOfFile . '/' . $nomOldFile . '.' . $extensionFile)) {
                $this->deleteFile($pathFolderOfFile . '/' . $nomOldFile . '.' . $extensionFile);
            }
        }
        return $rep;
    }

    /*
     * Supprime un fichier
     * @param type $pathToFile
     * @return boolean
     */
    function deleteFile($pathToFile = '') {
        if (file_exists($pathToFile)) {
            unlink($pathToFile);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /*
     * Retourne les dimentions d'une image
     * @param type $pathToImage
     * @param type $extensionImage
     * @return type
     */
    function getDimensionImage($pathToImage) {
        $tab = explode('.', $pathToImage);
        // récuperation de l'extension de l'image
        $extensionImage = strtolower($tab[count($tab) - 1]);
        $rep = array('x' => 0, 'y' => 0);
        if (file_exists($pathToImage)) {
            switch ($extensionImage) {
                case 'png':
                    $img_create = imagecreatefrompng($pathToImage);
                    $rep['x'] = imagesx($img_create);
                    $rep['y'] = imagesy($img_create);
                    break;
                case 'jpg':
                    $img_create = imagecreatefromjpeg($pathToImage);
                    $rep['x'] = imagesx($img_create);
                    $rep['y'] = imagesy($img_create);
                    break;
                case 'jpeg':
                    $img_create = imagecreatefromjpeg($pathToImage);
                    $rep['x'] = imagesx($img_create);
                    $rep['y'] = imagesy($img_create);
                    break;
                case 'gif':
                    $img_create = imagecreatefromgif($pathToImage);
                    $rep['x'] = imagesx($img_create);
                    $rep['y'] = imagesy($img_create);
                    break;
            }
        }
        return $rep;
    }

}
