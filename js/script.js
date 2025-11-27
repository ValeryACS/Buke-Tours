document
  .getElementById("language-switcher")
  .addEventListener("change", function () {
    var newLang = this.value;

    // Usar Fetch API (AJAX) para enviar el nuevo idioma al servidor
    fetch("process_lang.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: "lang=" + newLang,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          // Recargar la página para que PHP cargue los textos correctos
          window.location.reload();
        } else {
          console.error(data.message);
        }
      })
      .catch((error) => console.error("Error:", error));
  });
