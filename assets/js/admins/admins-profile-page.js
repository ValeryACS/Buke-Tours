import { setOnChangeEvents } from "../utils.module.js";
import { validateProfileForm } from "../profile-module.js";

document.addEventListener("DOMContentLoaded", () => {
  const adminProfileForm = document.getElementById("admin-form");

  adminProfileForm.addEventListener("submit", async (event) => {
    event.preventDefault();
    const nombreCompleto = document.getElementById("fullName");
    const email = document.getElementById("email");
    const telefono = document.getElementById("phone");
    const password = document.getElementById("password");
    const sexo = document.getElementById("sexo");
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

    const isValidProfileForm = validateProfileForm({
      stringsRequeridos: [
        nombreCompleto,
        email,
        telefono,
        pais,
        password,
        confirmPassword,
        idioma,
        pasaporteOdocumento,
        direccion,
        ciudad,
        provincia,
        sexo,
      ],
      numerosRequeridos: [codigoPostal],
      fechasRequeridas: [fechaDeNacimiento],
    });

    if (isValidProfileForm) {
      const formData = new FormData();

      const generoSeleccionado = genres.find((genre) => genre.checked);

      formData.append("nombre", nombreCompleto.value);
      formData.append("email", email.value);
      formData.append("telefono", telefono.value);
      formData.append("pais", pais.value);
      formData.append("password", password.value);
      formData.append("confirmPassword", confirmPassword.value);
      formData.append("idioma", idioma.value);
      formData.append("pasaporteOdocumento", pasaporteOdocumento.value);
      formData.append("direccion", direccion.value);
      formData.append("ciudad", ciudad.value);
      formData.append("provincia", provincia.value);
      formData.append("codigoPostal", codigoPostal.value);
      formData.append("genero", generoSeleccionado.value);
      formData.append("fechaDeNacimiento", fechaDeNacimiento.value);

      try {
        const response = await fetch("/Buke-Tours/api/admin/profile/admin_profile.php", {
          method: "POST",
          body: formData,
        });

        if (!response.ok) {
          const text = await response.text();
          Swal.fire({
            icon: "error",
            title: "Error al Crear Perfil",
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
              : result?.message || "No se pudo crear el usuario.";
          Swal.fire({
            icon: "error",
            title: "El Usuario no pudo ser creado",
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
          title: "Usuario Creado Exitosamente",
          text: result?.message || "El usuario ha sido creado exitosamente.",
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
          text: "No se pudo conectar con el servidor. Intenta nuevamente.",
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 6000,
          timerProgressBar: true,
        });
        return;
      }
    }
  });
});
