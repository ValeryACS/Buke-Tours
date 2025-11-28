<?php 
/**
 * Contine los scripts del Home Page
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'common-scripts.php';
?>
<script type="module" src="/Buke-Tours/assets/js/home.page.js" defer></script>
<!-- Initialize Swiper -->
<script>
  (() => {
    let slidesPerView = window.innerWidth > 768 ? 3 : 1;
    var swiper = new Swiper(".slider-tours", {
      lazy: true,
      slidesPerView,
      spaceBetween: 30,
      loop: true,
      breakpoints: {
        "@0.00": {
          slidesPerView: 1,
          spaceBetween: 5,
        },
        "@0.75": {
          slidesPerView: 2,
          spaceBetween: 20,
        },
        "@1.00": {
          slidesPerView: 3,
          spaceBetween: 30,
        },
        "@1.50": {
          slidesPerView: 4,
          spaceBetween: 40,
        },
      },
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
    });
  })();
</script>
