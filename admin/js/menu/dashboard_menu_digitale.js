/**
 * Funzione per navigare nel menu digitale
 * @param {number} tipo - Il tipo di menu da visualizzare
 * @param {string} lang - La lingua del menu (it/en)
 */
function menu(tipo, lang) {
    // Reindirizzamento alla pagina del menu con i parametri tipo e lingua
    location.href = "../front-end/php/menu_digitale/menu.php?type=" + tipo + "&lang=" + lang;
}