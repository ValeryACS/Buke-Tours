document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('review-edit-form');
    
    const reviewIdInput = document.querySelector('input[name="review_id"]'); 
    const statusInput = document.getElementById('status');

    if (form) {
        form.addEventListener('submit', async (event) => {
            event.preventDefault(); 

            const reviewId = reviewIdInput ? parseInt(reviewIdInput.value) : 0;
            const status = statusInput ? statusInput.value.trim() : '';

            const errors = [];
            
            if (isNaN(reviewId) || reviewId <= 0) {
                errors.push("ID de reseña inválido.");
            }
            
            const validStatuses = ['Aprobada', 'Denegada', 'Pendiente'];
            if (!validStatuses.includes(status)) {
                errors.push("Estado no válido.");
            }

            if (errors.length > 0) {
                Swal.fire('Error de Validación', errors.join('<br>'), 'error');
                return;
            }

            const result = await Swal.fire({
                title: "¿Confirmar cambio de estado?",
                text: `El estado de esta reseña será cambiado a "${status}".`,
                icon: "info",
                showCancelButton: true,
                confirmButtonColor: "#198754", 
                cancelButtonColor: "#6c757d", 
                confirmButtonText: "Sí, actualizar",
                cancelButtonText: "Cancelar"
            });

            if (result.isConfirmed) {
                try {
                    const formData = new FormData();
                    formData.append('review_id', reviewId); 
                    formData.append('status', status);
                    
                    const url = '../../api/admin/reviews/edit_reviews_status.php';
                    
                    const response = await fetch(url, {
                        method: 'POST',
                        body: formData
                    });
                    
                    const data = await response.json();

                    if (data.success) {
                        Swal.fire({
                            title: "¡Éxito!",
                            text: data.message,
                            icon: "success"
                        }).then(() => {
                            window.location.href = './index.php';
                        });
                    } else {
                        const errorMessage = Array.isArray(data.errors) ? data.errors.join('<br>') : (data.message || "Error desconocido al actualizar.");
                        Swal.fire({
                            title: "Error",
                            html: errorMessage,
                            icon: "error"
                        });
                    }
                } catch (error) {
                    Swal.fire({
                        title: "Error de Conexión",
                        text: "No se pudo conectar al servidor para actualizar el estado.",
                        icon: "error"
                    });
                }
            }
        });
    } else {
        console.error("El formulario de edición no fue encontrado.");
    }
});