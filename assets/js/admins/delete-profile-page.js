document.addEventListener('DOMContentLoaded', () => {
    // Escuchar clics en todos los botones con la clase 'btn-eliminar-tarea'
    document.querySelectorAll('.btn-eliminar-admin').forEach(button => {
        button.addEventListener('click', async (event) => {
            event.preventDefault();

            // 1. Obtener el ID del atributo data-task-id
            const taskId = event.currentTarget.getAttribute('data-task-id');
            if (!taskId) return;

            // 2. Mostrar la confirmación con SweetAlert2 (Swal)
            const result = await Swal.fire({
                title: "¿Está seguro de eliminar este administrador?",
                text: "¡No podrás revertir esto!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc3545",
                cancelButtonColor: "#6c757d", 
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar"
            });

            // 3. Si el usuario confirma la eliminación
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('id', taskId);

                try {
                    // 4. Enviar la petición POST al endpoint PHP
                    const response = await fetch('/Buke-Tours/api/admin/profile/delete_profile_admin.php', {                        method: 'POST',
                        body: formData
                    });
                    
                    const data = await response.json();

                    if (data.success) {
                        // 5. Mostrar éxito y recargar la página
                        Swal.fire({
                            title: "¡Eliminado!",
                            text: data.message,
                            icon: "success"
                        }).then(() => {
                             // Recargar para que el usuario eliminado desaparezca de la lista
                             window.location.reload(); 
                        });
                    } else {
                        // 6. Mostrar error
                        Swal.fire({
                            title: "Error",
                            text: data.message || "Error desconocido al eliminar.",
                            icon: "error"
                        });
                    }
                } catch (error) {
                    Swal.fire({
                        title: "Error de Conexión",
                        text: "No se pudo conectar al servidor para eliminar el administrador.",
                        icon: "error"
                    });
                }
            }
        });
    });
});