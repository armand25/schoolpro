<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TypeDonnees
 *
 * @author edmond
 */
namespace admin\ParamBundle\Types;

class TypeDonnees {
    
    /**
     * Entier
     */
    const INT = 1;
    
    /*
     * Date string au format Y-m-d
     */
    const DATE = 2;
    
    /*
     * Date time string au format Y-m-d H:i:s
     */
    const DATETIME = 3;
    
    /**
     * Chaine de caractères
     */
    const VARCHAR = 4;
    
    /**
     * Texte
     */
    const TEXT = 5;
    
    /**
     * vrai ou faux
     * 1 = vrai et 0 = faux
     */
    const BOOLEAN = 6;
    
    /**
     * fichier msc
     */
    const FICHIER_MSC = 1; 
    
    /**
     * fichier wct
     */
    const FICHIER_WCT = 2;     
}
