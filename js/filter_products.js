document.addEventListener("DOMContentLoaded", function () {
  const dateFromFilter = document.getElementById("fecha_vencimiento_desde");
  const dateToFilter = document.getElementById("fecha_vencimiento_hasta");
  const filterBtn = document.getElementById("btn-filtrar");
  const clearFilterBtn = document.getElementById("btn-limpiar-filtro");
  const tableRows = document.querySelectorAll("#tbody-rows tr");

  function filterRows() {
    const dateFrom = dateFromFilter.value;
    const dateTo = dateToFilter.value;

    if (!dateFrom && !dateTo) {
      // Si no hay fechas, no hacer nada o podrías alertar al usuario
      return;
    }

    tableRows.forEach(function (row) {
      const expirationDateCell = row.cells[7];
      const expirationDate = expirationDateCell
        ? expirationDateCell.dataset.date
        : null;

      if (!expirationDate) {
        row.style.display = "none";
        return;
      }

      const isAfterFrom = !dateFrom || expirationDate >= dateFrom;
      const isBeforeTo = !dateTo || expirationDate <= dateTo;

      if (isAfterFrom && isBeforeTo) {
        row.style.display = ""; // Mostrar fila
      } else {
        row.style.display = "none"; // Ocultar fila
      }
    });

    clearFilterBtn.style.display = "inline-block";
  }

  function clearFilter() {
    dateFromFilter.value = "";
    dateToFilter.value = "";

    tableRows.forEach(function (row) {
      row.style.display = "";
    });

    clearFilterBtn.style.display = "none";
  }

  filterBtn.addEventListener("click", filterRows);
  clearFilterBtn.addEventListener("click", clearFilter);
});
