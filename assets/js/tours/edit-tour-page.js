
function validateEditTourForm() {
  const requiredTextIds = ["sku", "title", "location", "description"];
  const requiredNumberIds = ["price_usd", "adults_limit", "children_limit"];

  let valid = true;
  let messages = [];

  for (const id of requiredTextIds) {
    const el = document.getElementById(id);
    if (!el) continue;
    if (!el.value.trim()) {
      valid = false;
      el.classList.add("is-invalid");
      messages.push(`El campo ${id} es obligatorio.`);
    } else {
      el.classList.remove("is-invalid");
    }
  }

  for (const id of requiredNumberIds) {
    const el = document.getElementById(id);
    if (!el) continue;
    const v = el.value.trim();
    if (v === "" || isNaN(v)) {
      valid = false;
      el.classList.add("is-invalid");
      messages.push(`El campo numérico ${id} es obligatorio.`);
    } else {
      el.classList.remove("is-invalid");
    }
  }

  if (!valid) {
    alert(messages.join("\n"));
  }
  return valid;
}

document.addEventListener("DOMContentLoaded", () => {
  console.log("[edit-tour-page] DOMContentLoaded");

  const form = document.getElementById("tour-edit-form");
  if (!form) {
    console.error("[edit-tour-page] No se encontró #tour-edit-form");
    return;
  }

  form.addEventListener("submit", async (event) => {
    console.log("[edit-tour-page] submit capturado");
    event.preventDefault(); // MUY IMPORTANTE

    const idInput = document.getElementById("tour_id");
    if (!idInput || !idInput.value) {
      alert("ID de tour inválido.");
      return;
    }

    // Validar
    const isValid = validateEditTourForm();
    if (!isValid) {
      console.warn("[edit-tour-page] formulario inválido");
      return;
    }

    const formData = new FormData(form);
    formData.set("id", idInput.value);

    try {
      console.log("[edit-tour-page] Enviando fetch a /Buke-Tours/api/tours/update.php");
      const response = await fetch("/Buke-Tours/api/tours/update.php", {
        method: "POST",
        body: formData,
      });

      const rawText = await response.text();
      console.log("[edit-tour-page] Respuesta cruda:", rawText);

      let result;
      try {
        result = JSON.parse(rawText);
      } catch (parseErr) {
        console.error("No es JSON válido:", rawText);
        alert("Error al actualizar tour: la API no devolvió JSON válido.");
        return;
      }

      if (!response.ok || !result?.success) {
        const msg =
          Array.isArray(result?.errors) && result.errors.length
            ? result.errors.join(", ")
            : result?.message || "No se pudo actualizar el tour.";
        console.error("[edit-tour-page] Error en update:", result);
        alert("El tour no pudo ser actualizado: " + msg);
        return;
      }

      alert(result?.message || "Tour actualizado correctamente.");
      // Redirigir a la lista
      window.location.href = "/Buke-Tours/admin/tours/index.php";
    } catch (err) {
      console.error("[edit-tour-page] Error de red:", err);
      alert("Error de red al actualizar tour: " + (err?.message || err));
    }
  });
});
