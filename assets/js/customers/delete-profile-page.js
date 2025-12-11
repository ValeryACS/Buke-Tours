document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.btn-eliminar-customer').forEach(button => {
        button.addEventListener('click', async (event) => {
            event.preventDefault();

            const taskId = event.currentTarget.getAttribute('data-task-id');
            if (!taskId) return;

            const result = await Swal.fire({
                title: "¿Está seguro de eliminar este usuario?",
                text: "¡No podrás revertir esto!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc3545",
                cancelButtonColor: "#6c757d", 
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar"
            });

            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('id', taskId);

                try {
                    const response = await fetch('/Buke-Tours/api/admin/profile_customers/delete_profile_customers.php', { 
                        method: 'POST',
                        body: formData
                    });
                    
                    const data = await response.json();

                    if (data.success) {
                        Swal.fire({
                            title: "¡Eliminado!",
                            text: data.message,
                            icon: "success"
                        }).then(() => {
                             window.location.reload(); 
                        });
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: data.message || "Error desconocido al eliminar.",
                            icon: "error"
                        });
                    }
                } catch (error) {
                    Swal.fire({
                        title: "Error de Conexión",
                        text: "No se pudo conectar al servidor para eliminar el usuario.",
                        icon: "error"
                    });
                }
            }
        });
    });
});