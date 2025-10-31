document.addEventListener("DOMContentLoaded", () => {
  const hamburger = document.querySelector("#nav-icon3");

  hamburger.addEventListener("click", () => {
    hamburger.classList.toggle("open"); // Agrega la clase que le da la animacion a la hamburguesa
  });
});
