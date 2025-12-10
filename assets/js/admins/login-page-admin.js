import { validateLoginForm } from "../login-module.js";

document.addEventListener("DOMContentLoaded", () => {
  const btnLoginForm = document.getElementById("btn-login");
  const email = document.getElementById("email");
  const password = document.getElementById("password");

  btnLoginForm.addEventListener("click", async (event) => {
    event.preventDefault();

    const isValidForm = validateLoginForm({
      stringsRequeridos: [email, password],
    });

    try {
      if (isValidForm) {
        const formData = new FormData();
        formData.append("email", email.value);
        formData.append("password", password.value);

        const respuesta = await fetch("/Buke-Tours/api/admin/login/login.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            email: email.value,
            password: password.value,
          }),
        });

        const data = await respuesta.json();
        console.log("data", data);
        if (respuesta.ok) {
          if (data.status === "error") {
            Swal.fire({
              icon: "error",
              title: "Error",
              text: data.mensaje ?? "Administrador no encontrado.",
              toast: true,
              position: "top-end",
              showConfirmButton: false,
              timer: 5000,
              timerProgressBar: true,
            });
          } else {
            Swal.fire({
              icon: "success",
              title: "Éxito",
              text:
                "Inicio de sesión exitoso. Bienvenido: " + (data.name || ""),
              toast: true,
              position: "top-end",
              showConfirmButton: false,
              timer: 5000,
              timerProgressBar: true,
            });
            setTimeout(() => {
              window.location.href = "/Buke-Tours/admin/invoices/";
            }, 3000);
          }
        }
      }
    } catch (error) {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "Hubo un error inesperado",
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
      });
      return;
    }
  });
});
