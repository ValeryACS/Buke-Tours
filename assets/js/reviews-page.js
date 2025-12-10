const STORAGE_KEY = "buke.reviews.v1";

// Sanitiza texto
const esc = (s = "") =>
  s
    .replaceAll("&", "&amp;")
    .replaceAll("<", "&lt;")
    .replaceAll(">", "&gt;")
    .replaceAll('"', "&quot;")
    .replaceAll("'", "&#39;");

// Renderiza lista en Bootstrap
const renderReviews = (container, list) => {
  if (!container) return;
  if (!Array.isArray(list) || list.length === 0) {
    container.innerHTML = `
      <div class="alert alert-secondary text-center mt-3">
        No hay reseñas todavía. ¡Sé la primera persona en opinar! 📝
      </div>`;
    return;
  }

  container.innerHTML = list
    .map(
      (r) => `
    <div class="card mb-3 shadow-sm border-0">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width:40px; height:40px;">
            <span>${esc(r.nombre.charAt(0).toUpperCase())}</span>
          </div>
          <div>
            <h5 class="card-title mb-0">${esc(r.nombre)}</h5>
            <small class="text-muted">${new Date(
              r.fecha
            ).toLocaleDateString()}</small>
          </div>
        </div>
        <div class="mb-2">${"⭐".repeat(r.calificacion)}${"☆".repeat(
        5 - r.calificacion
      )}</div>
        <p class="card-text">${esc(r.comentario)}</p>
      </div>
    </div>
  `
    )
    .join("");
};

// Validación
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

// Mostrar alerta Bootstrap
const showAlert = (msg, type = "danger") => {
  const alert = document.createElement("div");
  alert.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3 shadow`;
  alert.style.zIndex = 2000;
  alert.role = "alert";
  alert.innerHTML = `
    ${esc(msg)}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  `;
  document.body.appendChild(alert);
  setTimeout(() => alert.classList.remove("show"), 4000);
};

// Inicialización
document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("#formulario-resena");
  const contenedor = document.querySelector(".resenas-container");
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
