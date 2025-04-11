/**
 * Funzione per reindirizzare alla pagina del menu con il tipo specifico
 * @param {number} type - Tipo di menu (0 = gelato, 1 = granita, ecc., 100 = tutto)
 * @param {string} lang - Codice lingua (it/en)
 */
function menu(type, lang) {
    // Reindirizza alla pagina del menu con i parametri corretti
    window.location.href = '../../front-end/php/menu_digitale/menu.php?type=' + type + '&lang=' + lang;
}

/**
 * Funzione per cambiare lingua mantenendo la visualizzazione corrente
 * @param {string} newLang - Nuova lingua (it/en)
 */
function changeLang(newLang) {
    // Ottiene l'URL attuale
    const url = new URL(window.location.href);
    
    // Aggiorna il parametro della lingua
    url.searchParams.set('lang', newLang);
    
    // Reindirizza alla stessa pagina con la nuova lingua
    window.location.href = url.toString();
}