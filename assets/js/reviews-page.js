
const validate = ({ nombre, calificacion, comentario, idDelTour }) => {
  if (idDelTour === "no-seleccionado") {
    return "Debes seleccionar un Tour.";
  }
  if (isNaN(idDelTour) || Number(idDelTour) <= 0) {
    return "El Id del Tour tiene que ser mayor a cero.";
  }

  if (!nombre || nombre.trim().length < 3)
    return "El nombre debe tener al menos 3 caracteres.";
  const score = Number(calificacion);
  if (!Number.isFinite(score) || score < 1 || score > 5)
    return "Selecciona una calificación válida (1 a 5).";
  if (!comentario || comentario.trim().length < 10)
    return "El comentario debe tener al menos 10 caracteres.";
  return null;
};


const showAlert = (msg, type = "danger") => {
  const alert = document.createElement("div");
  alert.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3 shadow`;
  alert.style.zIndex = 2000;
  alert.role = "alert";
  alert.innerHTML = `
    ${msg}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  `;
  document.body.appendChild(alert);
  setTimeout(() => alert.classList.remove("show"), 4000);
};

document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("#formulario-resena");
  const nombre = document.getElementById("full_name");
  const idDelTour = document.getElementById("tour-id");
  const customerId = document.getElementById("customerId");
  const calificacion = document.getElementById("calificacion");
  const comentario = document.getElementById("comentario");

  form?.addEventListener("submit", async (e) => {
    e.preventDefault();

    const payload = {
      nombre: nombre.value.trim(),
      calificacion: Number(calificacion.value ?? 1),
      comentario: comentario.value.trim(),
      idDelTour: idDelTour.value,
    };

    const error = validate(payload);
    if (error) {
      showAlert(error, "warning");
      return;
    }

    try {
      const formData = new FormData();
      formData.append("nombre", nombre.value.trim());
      formData.append("tourId", idDelTour.value);
      formData.append("customerId", customerId.value);
      formData.append("calificacion", calificacion.value.trim());
      formData.append("comentario", comentario.value.trim());

      const response = await fetch("/Buke-Tours/api/reviews/create.php", {
        method: "POST",
        body: formData,
      });

      if (!response.ok) {
        const text = await response.text();
        Swal.fire({
          icon: "error",
          title: "Error al Crear Reseña",
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
            : result?.message || "No se pudo crear la Reseña.";
        Swal.fire({
          icon: "error",
          title: "La Reseña no pudo ser creada",
          text: msg,
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 6000,
          timerProgressBar: true,
        });
        return;
      }

      Swal.fire({
        icon: "success",
        title: "Reseña Creada Exitosamente",
        text: result?.message || "La reseña ha sido creado exitosamente.",
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
      });
      form.reset();
      showAlert("¡Tu reseña será revisada antes de ser publicada!", "success");
      setTimeout(() => {
        window.location.href = "/Buke-Tours/";
      }, 3000);
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
      return;
    }
  });
});
