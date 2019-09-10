/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
    /**
     * Initialisation des datepicker
     */
    initDatePicker();
    });

// affiche de message de debogage
function my_alert(msg) {
    afficher_message("Alert", msg);
}



// affiche un message 
function afficher_message(titre, msg, icon) {
    $('<div id="msg" title="' + titre + '"><p> ' + icon + ' ' + msg + '</p></div>').dialog({
        hide: {
            //  effect: "explode",
            duration: 500
        },
        modal: true,
        autoOpen: false,
        buttons: {
            " OK ": function () {
                $(this).dialog('close');
            }
        }

    }).dialog('open');
}


// affiche un message de succès
function success_message(titre, msg) {
    afficher_message(titre, msg, '<i class="icon_check_alt2"></i> ');
}
// affiche un message de succès
function warning_message(titre, msg) {
    afficher_message(titre, msg, '<i class="icon_close_alt2"></i> ');
}
// affiche un message de succès
function error_message(titre, msg) {
    afficher_message(titre, msg, '<i class="icon_close_alt2"></i> ');
}

/**
 * Pour cocher tous les checkbox ayant la class nomClass
 * 
 * @param {string} nomClass
 * @returns {undefined}
 */
function toutDecocher(nomClass) {
    var query = document.querySelectorAll('.' + nomClass);
    var i = 0;
    for (i = 0; i < query.length; i++) {
        query[i].checked = false;
    }
}

/**
 * Pour décocher tous les checkbox ayant la class nomClass
 * 
 * @param {string} nomClass
 * @returns {undefined}
 */
function toutCocher(nomClass) {
    var query = document.querySelectorAll('.' + nomClass);
    var i = 0;
    for (i = 0; i < query.length; i++) {
        query[i].checked = true;
    }
}

/**
 * Cré un string qui sera envoyé par ajax au serveur, à partir du contenu du tableau tab
 * exple [2, 7, 8] donnera : |2|7|8 
 * @param {type} tab
 * @returns {String}
 */
function prepareAjaxData(tab) {
    var rep = '';
    var max = tab.length;
    for (var i = 0; i < max; i++) {
        rep += '|' + tab[i];
    }

    return rep;
}

/**
 * Retourne un tableau contenant les valeurs des checkboxs cochés ayant la class nomClass
 * 
 * @param {string} nomClass
 * @param {string} valeurAttribut : nom de l'attribut du checkbox contenant la valeur à récupérer
 * @param {boolean} isNombre : indique si la valeur à récuperer est un nombre, si oui une conversion de type a lieu
 * 
 * @returns {Array}
 */
function getCheckedId(nomClass, valeurAttribut, isNombre) {
    var tab = [], i = 0;
    $('.' + nomClass).each(function () {
        if ($(this).is(':checked')) {
            var val = ($(this).attr(valeurAttribut));

            if (isNombre) {
                val = parseInt(val);
            }
            tab[i++] = val;
        }
    });

    return tab;
}

/**
 * Initialisation des dates picker
 * @returns {undefined}
 */
function initDatePicker() {
//    $.datepicker.regional['fr'] = {
//        closeText: 'Fermer',
//        prevText: 'Précédent',
//        nextText: 'Suivant',
//        currentText: 'Aujourd\'hui',
//        monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
//        monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
//        dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
//        dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
//        dayNamesMin: ["Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa"],
//        weekHeader: 'Sem.',
//        dateFormat: 'dd/mm/yy',
//        firstDay: 1,
//        lang: 'fr',
//        isRTL: false,
//        showMonthAfterYear: false,
//        yearSuffix: ''
//    };

}

/*
 * Renseigne si une chaine de caractère est une date au format JJ/MM/AA
 */
function isDate(chaine) {
    var lengthDate = 10;
    if (chaine.length != lengthDate) {
        return false;
    }
    var regexDate = /^[0-2][0-9]\/0[0-9]\/[1-2][0-9]{3}$|^3[0-1]\/0[0-9]\/[1-2][0-9]{3}$|^[0-2][0-9]\/1[0-2]\/[1-2][0-9]{3}$|^3[0-1]\/1[0-2]\/[1-2][0-9]{3}$/;
    return regexDate.test(chaine);
}

/**
 * Création de tooltip  à partir de JBox
 * @param {type} element
 * @returns {undefined}
 */
function tooltipJBoxGobi(element) {
    element.jBox('Tooltip', {
        pointer: 'right:15' // The pointer is moved to the right with a 15 pixel offset
    });
}