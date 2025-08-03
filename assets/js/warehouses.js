/**
 * JavaScript per la pagina Magazzini
 * Gestisce l'espansione/contrazione delle giacenze
 */

// Assicuriamo che la funzione sia globale
window.toggleStocks = function (warehouseId) {
  const stocksDiv = document.getElementById(`stocks-${warehouseId}`);
  const toggleText = document.getElementById(`toggle-text-${warehouseId}`);
  const toggleIcon = document.getElementById(`toggle-icon-${warehouseId}`);

  if (stocksDiv.style.display === "none" || stocksDiv.style.display === "") {
    stocksDiv.style.display = "block";
    if (toggleText) toggleText.textContent = "Nascondi Giacenze";
    if (toggleIcon) toggleIcon.textContent = "▲";
  } else {
    stocksDiv.style.display = "none";
    if (toggleText) toggleText.textContent = "Mostra Giacenze";
    if (toggleIcon) toggleIcon.textContent = "▼";
  }
};

// Eventuale altra logica specifica per i magazzini
document.addEventListener("DOMContentLoaded", function () {
  console.log("Pagina Magazzini caricata");

  // Inizializza tutti i bottoni di toggle
  const toggleButtons = document.querySelectorAll('[onclick*="toggleStocks"]');
  console.log(`Trovati ${toggleButtons.length} bottoni toggle`);

  // Qui puoi aggiungere altre inizializzazioni se necessario
});
