import { setOnChangeEvents } from "./utils.module.js";
import { validateContactForm } from "./contact-module.js";

document.addEventListener("DOMContentLoaded", ()=> {
    const contactForm = document.getElementById("contact-form");

    contactForm.addEventListener("submit", async()=> {
        event.preventDefault();
            const nombre = document.getElementById("nombre");
            const email = document.getElementById("email");
            const telefono = document.getElementById("telefono");
            const mensaje = document.getElementById("mensaje");
            const asunto = document.getElementById("asunto");
        
            setOnChangeEvents({
              inputTextStrings: [nombre, email, mensaje, asunto],
              inputNumbers: [telefono],
            });
        
            const isValidProfileForm = validateContactForm({
              stringsRequeridos: [
                nombre,
                email,
                mensaje,
                asunto,
                telefono
              ],
            });
        
            if (isValidProfileForm) {
              const formData = new FormData();
              formData.append("nombre", nombre.value);
              formData.append("email", email.value);
              formData.append("telefono", telefono.value);
              formData.append("mensaje", mensaje.value);
              formData.append("asunto", asunto.value);
        
              try {
                const response = await fetch("/Buke-Tours/api/contact-form/send-email.php", {
                  method: "POST",
                  body: formData,
                });
        
                if (!response.ok) {
                  const text = await response.text();
                  Swal.fire({
                    icon: "error",
                    title: "Error al enviar el Formulario",
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
                      : result?.message || "No se pudo enviar el mensaje.";
                  Swal.fire({
                    icon: "error",
                    title: "El Mensaje no pudo ser enviado",
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
                  title: "Formulario enviado Exitosamente",
                  text: result?.message || "El Formulario ha sido enviado exitosamente.",
                  toast: true,
                  position: "top-end",
                  showConfirmButton: false,
                  timer: 5000,
                  timerProgressBar: true,
                });
                setTimeout(() => {
                  window.location.href = '/Buke-Tours/';
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
    })
})