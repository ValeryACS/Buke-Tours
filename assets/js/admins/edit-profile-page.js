import { setOnChangeEvents } from "../utils.module.js";
import { validateProfileForm } from "../profile-module.js"; 
document.addEventListener("DOMContentLoaded", () => {
  const adminProfileForm = document.getElementById("admin-form");

  adminProfileForm.addEventListener("submit", async (event) => {
    event.preventDefault();

    const adminIdInput = document.getElementById("admin_id"); 
    const adminId = adminIdInput ? adminIdInput.value : null;

    const nombreCompleto = document.getElementById("fullName");
    const email = document.getElementById("email");
    const telefono = document.getElementById("phone");
    const password = document.getElementById("password");
    const fechaDeNacimiento = document.getElementById("birthdate");
    const confirmPassword = document.getElementById("confirm-password");
    const pais = document.getElementById("country");
    const idioma = document.getElementById("idioma");
    const pasaporteOdocumento = document.getElementById("documento");
    const direccion = document.getElementById("direccion");
    const ciudad = document.getElementById("ciudad");
    const provincia = document.getElementById("provincia");
    const codigoPostal = document.getElementById("zip");
    const genres = Array.from(document.querySelectorAll(".genre-radio-button"));

    setOnChangeEvents({
      inputTextStrings: [nombreCompleto, ciudad, provincia],
      inputNumbers: [telefono, pasaporteOdocumento, codigoPostal],
    });

    const passwordValue = password.value.trim();
    const confirmPasswordValue = confirmPassword.value.trim();

    if (passwordValue !== "" && passwordValue.length < 6) {
        Swal.fire({
            icon: "error",
            title: "Error de Validación",
            text: "La nueva Contraseña debe tener al menos 6 caracteres.",
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 6000,
            timerProgressBar: true,
        });
        return;
    }
    
    if (passwordValue !== "" && passwordValue !== confirmPasswordValue) {
        Swal.fire({
            icon: "error",
            title: "Error de Validación",
            text: "Las contraseñas no coinciden.",
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 6000,
            timerProgressBar: true,
        });
        return; 
    }

    const isValidProfileForm = validateProfileForm(
      nombreCompleto,
      email,
      telefono,
      fechaDeNacimiento,
      passwordValue ? password : null, 
      confirmPasswordValue ? confirmPassword : null, 
      pais,
      genres,
    );

    if (isValidProfileForm) {
      const formData = new FormData();
      const generoSeleccionado = genres.find((genre) => genre.checked);

      if (adminId) {
        formData.append("admin_id", adminId);
      } else {
         Swal.fire({
            icon: "error",
            title: "Error de ID",
            text: "El ID del administrador es requerido para la edición.",
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 6000,
            timerProgressBar: true,
        });
        return;
      }
      
      formData.append("nombre", nombreCompleto.value);
      formData.append("email", email.value);
      formData.append("telefono", telefono.value);
      formData.append("pais", pais.value);
      formData.append("idioma", idioma.value);
      formData.append("pasaporteOdocumento", pasaporteOdocumento.value);
      formData.append("direccion", direccion.value);
      formData.append("ciudad", ciudad.value);
      formData.append("provincia", provincia.value);
      formData.append("codigoPostal", codigoPostal.value);
      formData.append("genero", generoSeleccionado ? generoSeleccionado.value : '');
      formData.append("fechaDeNacimiento", fechaDeNacimiento.value);

      if (passwordValue) {
        formData.append("password", passwordValue);
        formData.append("confirmPassword", confirmPasswordValue);
      }

      try {
        const response = await fetch("/Buke-Tours/api/admin/profile/edit_admin_profile.php", {
          method: "POST",
          body: formData,
        });

        
        if (!response.ok) {
          const text = await response.text();
          try {
             const errorJson = JSON.parse(text);
             const msg = Array.isArray(errorJson?.errors) ? errorJson.errors.join(", ") : errorJson.message;
             Swal.fire({
                icon: "error",
                title: "Error al Editar Administrador",
                text: msg || "Ocurrió un error en el servidor.",
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 6000,
                timerProgressBar: true,
             });
          } catch(e) {
             Swal.fire({
                icon: "error",
                title: "Error de Servidor",
                text: text || "Ocurrió un error desconocido en el servidor.",
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 6000,
                timerProgressBar: true,
             });
          }
          return;
        }

        const result = await response.json();

        if (!result?.success) {
          const msg =
            Array.isArray(result?.errors) && result.errors.length
              ? result.errors.join(", ")
              : result?.message || "No se pudo actualizar el administrador.";
          Swal.fire({
            icon: "error",
            title: "Edición Fallida",
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
          title: "Administrador Actualizado Exitosamente",
          text: result?.message || "El administrador ha sido actualizado.",
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 5000,
          timerProgressBar: true,
        });
        
        setTimeout(() => {
          window.location.href = '/Buke-Tours/admin/admins/index.php'; 
        }, 3000);
      } catch (err) {
        Swal.fire({
          icon: "error",
          title: "Error de red",
          text: "No se pudo conectar con el servidor. Intenta nuevamente. Detalle: " + err.message,
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 6000,
          timerProgressBar: true,
        });
        return;
      }
    } else {
         Swal.fire({
            icon: "warning",
            title: "Formulario Incompleto",
            text: "Por favor, revise y complete todos los campos obligatorios.",
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 6000,
            timerProgressBar: true,
        });
    }
  });
});