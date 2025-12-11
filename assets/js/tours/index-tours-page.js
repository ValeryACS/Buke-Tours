
document.addEventListener("DOMContentLoaded", () => {
  const deleteButtons = document.querySelectorAll(".btn-delete-tour");
  if (!deleteButtons.length) return;

  deleteButtons.forEach((btn) => {
    btn.addEventListener("click", async () => {
      const tourId = btn.dataset.tourId;
      if (!tourId) return;

      const confirmResult = await Swal.fire({
        icon: "warning",
        title: "¿Eliminar Tour?",
        text: "Esta acción no se puede deshacer.",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
        toast: false,
      });

      if (!confirmResult.isConfirmed) return;

      const formData = new FormData();
      formData.append("id", tourId);
      formData.append("from_admin", "1");

      try {
        const response = await fetch("/Buke-Tours/api/tours/delete.php", {
          method: "POST",
          body: formData,
        });

        if (!response.ok) {
          const text = await response.text();
          Swal.fire({
            icon: "error",
            title: "Error al eliminar Tour",
            text: text || "Ocurrió un error en el servidor.",
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 6000,
            timerProgressBar: true,
          });
          return;
        }

        const result = await response.json();

        if (!result?.success) {
          const msg =
            Array.isArray(result?.errors) && result.errors.length
              ? result.errors.join(", ")
              : result?.message || "No se pudo eliminar el tour.";
          Swal.fire({
            icon: "error",
            title: "El Tour no pudo ser eliminado",
            text: msg,
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 6000,
            timerProgressBar: true,
          });
          return;
        }

        const row = document.querySelector(`tr[data-tour-row="${tourId}"]`);
        if (row) row.remove();

        Swal.fire({
          icon: "success",
          title: "Tour eliminado",
          text: result?.message || "El tour se eliminó correctamente.",
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 4000,
          timerProgressBar: true,
        });
      } catch (err) {
        Swal.fire({
          icon: "error",
          title: "Error de red",
          text: "No se pudo conectar con el servidor. Intenta nuevamente.",
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 6000,
          timerProgressBar: true,
        });
      }
    });
  });
});

