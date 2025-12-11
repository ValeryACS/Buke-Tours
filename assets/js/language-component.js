document.addEventListener("DOMContentLoaded", () => {
  const languageSwitcher = document.getElementById("language-switcher");
  if (!languageSwitcher) return;

  languageSwitcher.addEventListener("change", async (event) => {
    const newLang = event.target.value;
    const formData = new FormData();
    formData.append("lang", newLang);

    try {
      const response = await fetch("/Buke-Tours/api/translations/update-lang.php", {
        method: "POST",
        body: formData,
      });
      const data = await response.json();
      if (data.lang) {
        localStorage.setItem('lang', data.lang)
        window.location.reload(); // Recargar la página para que PHP cargue los textos correctos
      } else {
        console.error(data.message);
      }
    } catch (error) {
      console.error("Error:", error);
    }
  });
});
