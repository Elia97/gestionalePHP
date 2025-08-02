/**
 * JavaScript per la pagina Magazzini
 * Gestisce l'espansione/contrazione delle giacenze
 */

function toggleStocks(warehouseId) {
  const stocksDiv = document.getElementById(`stocks-${warehouseId}`);
  const toggleText = document.getElementById(`toggle-text-${warehouseId}`);
  const toggleIcon = document.getElementById(`toggle-icon-${warehouseId}`);

  if (stocksDiv.style.display === "none") {
    stocksDiv.style.display = "block";
    toggleText.textContent = "Nascondi Giacenze";
    toggleIcon.textContent = "▲";
  } else {
    stocksDiv.style.display = "none";
    toggleText.textContent = "Mostra Giacenze";
    toggleIcon.textContent = "▼";
  }
}

// Eventuale altra logica specifica per i magazzini
document.addEventListener("DOMContentLoaded", function () {
  console.log("Pagina Magazzini caricata");
  // Qui puoi aggiungere altre inizializzazioni se necessario
});
