document.addEventListener("DOMContentLoaded", function () {
  // Add click event listeners to all delete buttons
  document.querySelectorAll(".producto-delete").forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      const productId = this.getAttribute("href").split("=")[1];

      // Show confirmation modal using SweetAlert2
      Swal.fire({
        title: "¿Estás seguro?",
        text: "Esta acción no se puede deshacer",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dc3545",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          // If confirmed, redirect to delete URL
          window.location.href = this.getAttribute("href");
        }
      });
    });
  });
});
