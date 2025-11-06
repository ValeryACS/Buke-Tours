

const STORAGE_KEY = 'buke.reviews.v1';

// Sanitiza texto
const esc = (s = '') => s
  .replaceAll('&', '&amp;')
  .replaceAll('<', '&lt;')
  .replaceAll('>', '&gt;')
  .replaceAll('"', '&quot;')
  .replaceAll("'", '&#39;');

// Manejo localStorage
const loadReviews = () => {
  try { return JSON.parse(localStorage.getItem(STORAGE_KEY)) ?? []; }
  catch { return []; }
};
const saveReviews = (list) => {
  localStorage.setItem(STORAGE_KEY, JSON.stringify(list));
};

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

  container.innerHTML = list.map(r => `
    <div class="card mb-3 shadow-sm border-0">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width:40px; height:40px;">
            <span>${esc(r.nombre.charAt(0).toUpperCase())}</span>
          </div>
          <div>
            <h5 class="card-title mb-0">${esc(r.nombre)}</h5>
            <small class="text-muted">${new Date(r.fecha).toLocaleDateString()}</small>
          </div>
        </div>
        <div class="mb-2">${'⭐'.repeat(r.calificacion)}${'☆'.repeat(5 - r.calificacion)}</div>
        <p class="card-text">${esc(r.comentario)}</p>
      </div>
    </div>
  `).join('');
};

// Validación
const validate = ({ nombre, calificacion, comentario }) => {
  if (!nombre || nombre.trim().length < 2) return 'El nombre debe tener al menos 2 caracteres.';
  const score = Number(calificacion);
  if (!Number.isFinite(score) || score < 1 || score > 5) return 'Selecciona una calificación válida (1 a 5).';
  if (!comentario || comentario.trim().length < 10) return 'El comentario debe tener al menos 10 caracteres.';
  return null;
};

// Mostrar alerta Bootstrap
const showAlert = (msg, type = 'danger') => {
  const alert = document.createElement('div');
  alert.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3 shadow`;
  alert.style.zIndex = 2000;
  alert.role = 'alert';
  alert.innerHTML = `
    ${esc(msg)}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  `;
  document.body.appendChild(alert);
  setTimeout(() => alert.classList.remove('show'), 4000);
};

// Inicialización
document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('.formulario-resena form');
  const contenedor = document.querySelector('.resenas-container');
  const nombre = document.querySelector('#nombre');
  const calificacion = document.querySelector('#calificacion');
  const comentario = document.querySelector('#comentario');

  let reviews = loadReviews();
  renderReviews(contenedor, reviews);

  form?.addEventListener('submit', (e) => {
    e.preventDefault();

    const payload = {
      nombre: nombre.value.trim(),
      calificacion: Number(calificacion.value),
      comentario: comentario.value.trim(),
      fecha: new Date().toISOString(),
      id: crypto.randomUUID?.() ?? String(Date.now())
    };

    const error = validate(payload);
    if (error) {
      showAlert(error, 'warning');
      return;
    }

    reviews = [payload, ...reviews];
    saveReviews(reviews);
    renderReviews(contenedor, reviews);

    form.reset();
    showAlert('¡Reseña enviada con éxito!', 'success');
  });
});
