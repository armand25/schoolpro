<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ApplicationManager
 *
 * @author Edmond
 */

namespace admin\ParamBundle\Services;

class DateManager {
    /*
     * 
     *  Retourne la date d'hier
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @return \DateTime
     */

    public function getYesterdayDate() {
        $today = time();
        $yesterday = $today - (3600 * 24);
        $format = 'Y-m-d H:i:s';
        $tempDate = date($format, $yesterday);

        return \DateTime::createFromFormat($format, $tempDate);
    }

    /*
     * 
     * Retourn le nom du jour à partir d'une date au format string d/m/Y  (11/05/2015) retournera Mai
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param string $dateString
     * @param string $separateur
     * @return string
     */

    public function getNomMoisFr($dateString, $separateur = '/') {
        $date_explode = explode($separateur, $dateString);
        $jour = $date_explode[0];
        $mois = $date_explode[1];
        $annee = $date_explode[2];

        $newTimestamp = mktime(12, 0, 0, $mois, $jour, $annee); // Créé le timestamp pour ta date (a midi)
        // Pareil pour les mois en FR
        $Mois = array("", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");

        // Et le libellé du mois
        return $Mois[date("n", $newTimestamp)];
    }

    /*
     * 
     *  Retourn le nom du jour à partir d'une date au format string d/m/Y  (11/05/2015) retournera Lundi
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param string $dateString
     * @param string $separateur
     * @return string
     */

    public function getNomJourFr($dateString, $separateur = '/') {
        $dateExplode = explode($separateur, $dateString);
        $jour = $dateExplode[0];
        $mois = $dateExplode[1];
        $annee = $dateExplode[2];

        $newTimestamp = mktime(12, 0, 0, $mois, $jour, $annee); // Créé le timestamp pour ta date (a midi)
        /*
         *  Ensuite tu fais un tableau avec les jours en Français
         */
        $Jour = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");

        return $Jour[date("w", $newTimestamp)];
    }

    /*
     * 
     *  Retourne un objet DateTime à partir d'une date au format dd/mm/yyyy (13/01/2015) 
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param string $dateUi
     * @param string $isDebut
     * @return \DateTime
     */

    public function getDateTimeByDateUI($dateUi = '01-01-2015', $separateurDate = '-', $isDebut = FALSE) {
        $d = $this->getDateHoroStatutSearchBfu($dateUi, $separateurDate, $isDebut, 1);

        return \DateTime::createFromFormat('Y-m-d H:i:s', $d);
    }

 
    /*
     * Retourne une date string au format yyyy-mm-dd H:i:s (2015-01-17 08:45:12) à partir d'une date
     *  au format dd/mm/yyyy (13/01/2015) pour la recherche des dfus sur efacture
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param string $dateUi
     * @param string $separateurDate
     * @param boolean $isDebut
     * @param int $avancementDate
     * @return string
     */
    public function getDateHoroStatutSearchBfu($dateUi = '01-01-2015', $separateurDate = '-', $isDebut = FALSE, $avancementDate = 1) {
        $yearActuelle = (int) date('Y');
        $yearFinDefault = $yearActuelle + $avancementDate;
        $defaultDate = ($isDebut) ? $yearActuelle . '-01-01 00:00:01' : $yearFinDefault . '-01-01 23:59:59';
        if ($dateUi == NULL) {
            return $defaultDate;
        } else {
            if (!$this->isDateUI($dateUi, $separateurDate)) {
                return $defaultDate;
            }
            $tab = explode($separateurDate, $dateUi);
            if (count($tab) == 3) {
                $searchDate = $tab[2] . '-' . $tab[1] . '-' . $tab[0];

                return ($isDebut) ? $searchDate . ' 00:00:00' : $searchDate . ' 23:59:00';
            }
        }

        return $defaultDate;
    }

    /*
     * vérifie si $date est une date (chaine de caratecteres ) au format jj/mm/aa H:i:s (12/12/2014 12:02:11)
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param string $dateTime
     * @return boolean
     */

    function isDateTimeUI($dateTime = "", $separateurDate = '/') {

        $tab = explode(' ', $dateTime);
        if ((!is_array($tab)) || (count($tab) != 2)) {
            return FALSE;
        }
        if ($this->isDateUI($tab[0], $separateurDate)) {
            $regexDateTime1 = "#^0[0-9]:0[0-9]:0[0-9]$|^0[0-9]:0[0-9]:[1-5][0-9]$|^0[0-9]:[1-5][0-9]:0[0-9]$|^0[0-9]:[1-5][0-9]:[1-5][0-9]$#";
            $regexDateTime2 = "#^1[0-9]:0[0-9]:0[0-9]$|^1[0-9]:0[0-9]:[1-5][0-9]$|^1[0-9]:[1-5][0-9]:0[0-9]$|^1[0-9]:[1-5][0-9]:[1-5][0-9]$#";
            $regexDateTime3 = "#^2[0-3]:0[0-9]:0[0-9]$|^2[0-3]:0[0-9]:[1-5][0-9]$|^2[0-3]:[1-5][0-9]:0[0-9]$|^2[0-3]:[1-5][0-9]:[1-5][0-9]$#";
            return (preg_match($regexDateTime1, $tab[1]) || preg_match($regexDateTime2, $tab[1]) || preg_match($regexDateTime3, $tab[1]));
        }
        return FALSE;
    }

    /*
     * vérifie si $date est une date (chaine de caratecteres ) au format jj/mm/aa (12/12/2014)
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param string $date
     * @param string $separateurDate
     * @return boolean
     */

    function isDateUI($date = "", $separateurDate = '/') {
        $regexDate = ($separateurDate == '/') ? "#^[0-2][0-9]/0[0-9]/[1-2][0-9]{3}$|^3[0-1]/0[0-9]/[1-2][0-9]{3}$|^[0-2][0-9]/1[0-2]/[1-2][0-9]{3}$|^3[0-1]/1[0-2]/[1-2][0-9]{3}$#" : "#^[0-2][0-9]-0[0-9]-[1-2][0-9]{3}$|^3[0-1]-0[0-9]-[1-2][0-9]{3}$|^[0-2][0-9]-1[0-2]-[1-2][0-9]{3}$|^3[0-1]-1[0-2]-[1-2][0-9]{3}$#";

        return preg_match($regexDate, $date);
    }

    /*
     * Convertie une duree de connexion en lettre affichable au connecte
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param \DateTime $debut
     * @param \DateTime $fin
     * @return string
     */

    public function convertDureeConnexionToString(\DateTime $debut, \DateTime $fin) {
        $laDuree = $fin->diff($debut);
        return $laDuree->format('%h heures %i minutes %s secondes');
    }

    /*
     * Crée une DateTime à partir date en chaine de caracteres
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param string $format
     * @param string $dateString
     * 
     * @return \DateTime
     */

    public function createDateTimeWithDateString($format, $dateString) {
        $dateTemp = new \DateTime();
        return $dateTemp->createFromFormat($format, $dateString);
    }

    /*
     * Crée un objet DateTime à partir d'une date (chaine de caractere )au format jj/mm/aaaa
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param string $dateUI
     * @param boolean $isDateTimeUI
     * 
     * @return \DateTime
     */
    public function getDateTimeObjetWithDateUI($dateUI, $isDateTimeUI = FALSE) {
        $tempDate = new \DateTime();
        if ($isDateTimeUI) {
            $tempTab = explode(' ', $dateUI);
            $tab = explode('/', $tempTab[0]);
            return $tempDate->createFromFormat('Y-m-d H:i:s', $tab[2] . '-' . $tab[1] . '-' . $tab[0] . ' ' . date($tempTab[1]));
        } else {
            $tab = explode('/', $dateUI);
            return $tempDate->createFromFormat('Y-m-d H:i:s', $tab[2] . '-' . $tab[1] . '-' . $tab[0] . ' ' . date('H:i:s'));
        }
    }

   
    /*
     * Retourne le numero de la semaine de l'année
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param \DateTime $date
     * 
     * @return int
     */
    public function getNumeroSemaine(\DateTime $date) {
        return (int) date('W', strtotime($date->format('Y-m-d H:i:s')));
    }

    /*
     * Retourne la date du dernier jour du mois au format string
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param int  $annee
     * @param int $numeroMois : compris entre 1 et 12
     * 
     * @return string. exple :  2015-04-14
     */
    public function getLastDateOfMonth($annee, $numeroMois) {
        $dateCollee = strftime("%Y%m%d", mktime(0, 0, 0, $numeroMois + 1, 0, $annee));
        return substr($dateCollee, 0, 4) . '-' . substr($dateCollee, 4, 2) . '-' . substr($dateCollee, 6, 2);
    }

    
    /*
     * Retourne la date du dernier jour du mois au format string
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param int  $annee
     * @param int $numeroMois : compris entre 1 et 12
     * 
     * @return string. exple :  2015-04-14
     */
    public function getFirstDateOfMonth($annee, $numeroMois) {
        $dateCollee = strftime("%Y%m%d", mktime(0, 0, 0, $numeroMois, 1, $annee));
        return substr($dateCollee, 0, 4) . '-' . substr($dateCollee, 4, 2) . '-' . substr($dateCollee, 6, 2);
    }

    
    /*
     *Retourne une date au format string à partir dun numéro de semaine 
     * Les numéros des semaine commentce avec 0. ( 0 à 6)
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param int $numeroSemaine 1 -53
     * @param int $annee
     * @param int $jour : Lundi = 0, Mardi = 1, ..., Dimanche = 6. 
     * @param string $forma : format de date
     * 
     * @return string : date au format $format 
     */
    function getDateByNumeroSemaine($numeroSemaine, $annee, $jour, $format = "d/m/Y") {
        $numeroSemaine--;
        if (strftime("%W", mktime(0, 0, 0, 01, 01, $annee)) == 1) {
            $mktime = mktime(0, 0, 0, 01, (01 + (($numeroSemaine - 1) * 7)), $annee);
        } else {
            $mktime = mktime(0, 0, 0, 01, (01 + (($numeroSemaine) * 7)), $annee);
        }
        if (date("w", $mktime) > 1) {
            $decalage = ((date("w", $mktime) - 1) * 60 * 60 * 24);
        }
        $lundi = $mktime - $decalage;
        $mardi = $lundi + (1 * 60 * 60 * 24);
        $mercredi = $lundi + (2 * 60 * 60 * 24);
        $jeudi = $lundi + (3 * 60 * 60 * 24);
        $vendredi = $lundi + (4 * 60 * 60 * 24);
        $samedi = $lundi + (5 * 60 * 60 * 24);
        $dimanche = $lundi + (6 * 60 * 60 * 24);

        $resultat = array(date($format, $lundi),
            date($format, $mardi),
            date($format, $mercredi),
            date($format, $jeudi),
            date($format, $vendredi),
            date($format, $samedi),
            date($format, $dimanche));

        $jours = array(0, 1, 2, 3, 4, 5, 6);
        if (in_array($jour, $jours)) {
            return $resultat[$jour];
        } else {
            return NULL;
        }
    }

    
    /*
     *retourne le nombre de jours écoulés entre deux dates
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param \DateTime $dateDeDebut
     * @param \DateTime $dateDeFin
     * 
     * @return int
     */
    public function getNombreJoursEcolles(\DateTime $dateDeDebut, \DateTime $dateDeFin) {
        $heures = $this->getNombreHeuresEcollees($dateDeDebut, $dateDeFin);

        return (int) ceil($heures / 24);
    }

    
    /*
     *retourne le nombre d'heures écoulées entre deux dates
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param \DateTime $dateDeDebut
     * @param \DateTime $dateDeFin
     * 
     * @return int
     */
    public function getNombreHeuresEcollees(\DateTime $dateDeDebut, \DateTime $dateDeFin) {
        $minutes = $this->getMinutesEcoulees($dateDeDebut, $dateDeFin);

        return (int) ($minutes / 60);
    }

   
    /*
     *retourne le nombre de minutes écoulées entre deux dates
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param \DateTime $dateDeDebut
     * @param \DateTime $dateDeFin
     * 
     * @return int
     */
    public function getMinutesEcoulees(\DateTime $dateDeDebut, \DateTime $dateDeFin) {
        return (int) ($this->getSecondeesEcoulees($dateDeDebut, $dateDeFin) / 60);
    }

    
    /*
     * retourne le nombre de secondes écoulées entre deux dates
     * format de vos dates date("Y-m-d H:i:s");
     * 
     * @author armand.tevi@gmail.com
     * @copyright Master Internationnal  - UTBM/ IAI/CIC/
     * @version 1
     * @access   public
     * @param \DateTime $dateDeDebut
     * @param \DateTime $dateDeFin
     * 
     * @return int
     */

    public function getSecondeesEcoulees(\DateTime $dateDeDebut, \DateTime $dateDeFin) {
        $datedebut = $dateDeDebut->format('Y-m-d H:i:s');
        $datefin = $dateDeFin->format('Y-m-d H:i:s');

        list($de, $td) = explode(' ', $datedebut); // Séparation date et heure début
        list($df, $tf) = explode(' ', $datefin); // Séparation date et heure fin

        $dd = explode("-", $de);
        $ddannee = $dd[0];
        $ddmois = $dd[1];
        $ddjour = $dd[2]; /// date 1
        $hd = explode(":", $td);
        $hdheure = $hd[0];
        $hdmin = $hd[1];
        $hdsec = $hd[2];   /// heure 1

        $df = explode("-", $df);
        $dfannee = $df[0];
        $dfmois = $df[1];
        $dfjour = $df[2]; /// date 2
        $hf = explode(":", $tf);
        $hfheure = $hf[0];
        $hfmin = $hf[1];
        $hfsec = $hf[2];   /// heure 2

        $time1 = time() - mktime($hdheure, $hdmin, $hdsec, $ddmois, $ddjour, $ddannee);
/// difference de seconde entre 1-1-1970 et la date 1
        $time2 = time() - mktime($hfheure, $hfmin, $hfsec, $dfmois, $dfjour, $dfannee);
/// difference de seconde entre 1-1-1970 et la date 2

        return ($time1 - $time2);  /// time1 - time2  donne le nombre en secondes
    }

    
    
    /**
     * Retourne un objet DateTime à partir d'une date au format dd/mm/yyyy (13/01/2015)
     * 
     * @param type $dateUi
     * @param type $isDebut
     *
     * @return type
     */
    public function getDateObject($dateUi = '01-01-2015', $separateurDate = '-', $isDebut = false)
    {
        $d = $this->getDateHoroStatutSearchBfu($dateUi, $separateurDate, $isDebut, 1);

        return \DateTime::createFromFormat('Y-m-d H:i:s', $d);
    }

    
    
}
