document.addEventListener("DOMContentLoaded", () => {
  const deleteButtons = document.querySelectorAll(".btn-delete-tour");
  if (!deleteButtons.length) return;

  deleteButtons.forEach((btn) => {
    btn.addEventListener("click", async (event) => {
      event.preventDefault(); 

      const tourId = btn.dataset.tourId;
      if (!tourId) return;

      const confirmed = window.confirm("¿Seguro que deseas eliminar este tour?");
      if (!confirmed) return;

      const formData = new FormData();
      formData.append("id", tourId);

      try {
        const response = await fetch("/Buke-Tours/api/tours/delete.php", {
          method: "POST",
          body: formData,
        });

        const text = await response.text();
        let result;

        try {
          result = JSON.parse(text);
        } catch (e) {
          console.error("Respuesta no JSON del delete.php:", text);
          alert("Respuesta no válida del servidor al eliminar el tour.");
          return;
        }

        console.log("Resultado delete tour:", result);

        if (!result.success) {
          const msg =
            Array.isArray(result.errors) && result.errors.length
              ? result.errors.join(", ")
              : result.message || "No se pudo eliminar el tour.";
          alert("Error al eliminar el tour: " + msg);
          return;
        }

        
        const row = document.querySelector(`tr[data-tour-row="${tourId}"]`);
        if (row) {
          row.remove();
        }

        alert(result.message || "Tour eliminado correctamente.");
      } catch (err) {
        console.error("Error de red al eliminar tour:", err);
        alert(
          "Error de red al eliminar el tour: " +
            (err?.message || String(err))
        );
      }
    });
  });
});
