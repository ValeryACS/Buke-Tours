import {
  updateBasket,
  updateCartTotal,
  validateCoupon,
} from "./cart.module.js";

import {
  setTourDetailsForm,
  validateCheckoutForm,
  renderFlags,
  setOnChangeCheckoutEvents,
} from "./checkout-module.js";

import { todayLocalISO } from "./utils.module.js";

(() => {
  document.addEventListener("DOMContentLoaded", async () => {
    updateBasket();
    await updateCartTotal();
    await setTourDetailsForm();
    renderFlags();

    const btnCoupon = document.getElementById("btn-coupon");
    const inputCoupon = document.getElementById("coupon");
    const btnCheckout = document.getElementById("pagar-con-tarjeta-btn");
    const btnPayWithPaypal = document.getElementById("btn-pay-with-paypal");

    let nombreCompleto = document.getElementById("nombre");
    let email = document.getElementById("email");
    let telefono = document.getElementById("telefono");
    let pais = document.getElementById("pais");
    let adultos = document.getElementById("adultos");
    let ninos = document.getElementById("ninos");
    let idioma = document.getElementById("idioma");
    let viajeroPrincipal = document.getElementById("viajeroPrincipal");
    let pasaporteOdocumento = document.getElementById("documento");
    let fechasDeIngreso = document.querySelectorAll(".start-date-input");
    let fechasDeSalida = document.querySelectorAll(".end-date-input");
    let seguro = document.getElementById("seguro");
    let transporte = document.getElementById("transporte");
    let fotos = document.getElementById("fotos");
    let desayuno = document.getElementById("desayuno");
    let almuerzo = document.getElementById("almuerzo");
    let cena = document.getElementById("cena");
    let direccion = document.getElementById("direccion");
    let ciudad = document.getElementById("ciudad");
    let provincia = document.getElementById("provincia");
    let codigoPostal = document.getElementById("zip");
    let nombreDelTitular = document.getElementById("cardName");
    let numeroDeLaTarjeta = document.getElementById("cardNumber");
    let mes = document.getElementById("cardMonth");
    let year = document.getElementById("cardYear");
    let cvv = document.getElementById("cardCvv");

    setOnChangeCheckoutEvents({
      inputTextStrings: [
        nombreCompleto,
        viajeroPrincipal,
        ciudad,
        provincia,
        nombreDelTitular,
      ],
      inputNumbers: [
        telefono,
        adultos,
        ninos,
        pasaporteOdocumento,
        codigoPostal,
        numeroDeLaTarjeta,
        mes,
        year,
        cvv,
      ],
    });

    const minDate = todayLocalISO();
    document
      .querySelectorAll(".start-date-input, .end-date-input")
      .forEach((el) => {
        el.setAttribute("min", minDate);
      });

    btnCoupon.addEventListener("click", async () => {
      if (!inputCoupon.value.length) {
        Swal.fire({
          icon: "error",
          title: "Cupon Inválido",
          text: "El Código del Cupon es inválido",
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 6000,
          timerProgressBar: true,
        });
        return;
      }
      await validateCoupon(inputCoupon.value);
    });

    btnPayWithPaypal.addEventListener("click", async (event) => {
      event.preventDefault();
      const terms = document.getElementById("terms");

      if (!terms.checked) {
        Swal.fire({
          icon: "error",
          title: "Revisa la información",
          text: "Debes aceptar los Términos y condiciones antes de hacer el pago.",
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 6000,
          timerProgressBar: true,
        });
        return;
      }

      nombreCompleto = document.getElementById("nombre");
      email = document.getElementById("email");
      telefono = document.getElementById("telefono");
      pais = document.getElementById("pais");
      adultos = document.getElementById("adultos");
      ninos = document.getElementById("ninos");
      idioma = document.getElementById("idioma");
      viajeroPrincipal = document.getElementById("viajeroPrincipal");
      pasaporteOdocumento = document.getElementById("documento");
      fechasDeIngreso = document.querySelectorAll(".start-date-input");
      fechasDeSalida = document.querySelectorAll(".end-date-input");
      seguro = document.getElementById("seguro");
      transporte = document.getElementById("transporte");
      fotos = document.getElementById("fotos");
      desayuno = document.getElementById("desayuno");
      almuerzo = document.getElementById("almuerzo");
      cena = document.getElementById("cena");
      direccion = document.getElementById("direccion");
      ciudad = document.getElementById("ciudad");
      provincia = document.getElementById("provincia");
      codigoPostal = document.getElementById("zip");

      const isValidCheckout = validateCheckoutForm({
        stringsRequeridos: [
          nombreCompleto,
          email,
          telefono,
          pais,
          idioma,
          viajeroPrincipal,
          pasaporteOdocumento,
          direccion,
          ciudad,
          provincia,
          codigoPostal,
        ],
        numerosRequeridos: [adultos, ninos],
        fechasRequeridas: [fechasDeIngreso, fechasDeSalida],
      });
      if (isValidCheckout) {
        const formData = new FormData();

        const ingresosValues = Array.from(fechasDeIngreso, (el) => el.value);
        const salidaValues = Array.from(fechasDeSalida, (el) => el.value);

        formData.append("nombre", nombreCompleto.value);
        formData.append("email", email.value);
        formData.append("telefono", telefono.value);
        formData.append("pais", pais.value);
        formData.append("adultos", adultos.value);
        formData.append("ninos", ninos.value);
        formData.append("idioma", idioma.value);
        formData.append("viajeroPrincipal", viajeroPrincipal.value);
        formData.append("pasaporteOdocumento", pasaporteOdocumento.value);
        formData.append("fechasDeIngreso", JSON.stringify(ingresosValues));
        formData.append("fechasDeSalida", JSON.stringify(salidaValues));
        formData.append("seguro", seguro.checked);
        formData.append("transporte", transporte.checked);
        formData.append("fotos", fotos.checked);
        formData.append("desayuno", desayuno.checked);
        formData.append("almuerzo", almuerzo.checked);
        formData.append("cena", cena.checked);
        formData.append("direccion", direccion.value);
        formData.append("ciudad", ciudad.value);
        formData.append("provincia", provincia.value);
        formData.append("codigoPostal", codigoPostal.value);

        //TODO Enviar la data al API de Paypal

        Swal.fire({
          icon: "success",
          title: "Pago Procesado",
          text: `Gracias por tu compra, tu pago ha sido procesado exitosamente.`,
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 5000,
          timerProgressBar: true,
        });
        localStorage.removeItem("cart");
        await updateBasket();
        await updateCartTotal();
        await setTourDetailsForm();
        renderFlags();
        return;
      }
    });

    btnCheckout.addEventListener("click", async (event) => {
      event.preventDefault();
      const terms = document.getElementById("terms");

      if (!terms.checked) {
        Swal.fire({
          icon: "error",
          title: "Revisa la información",
          text: "Debes aceptar los Términos y condiciones antes de hacer el pago.",
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 6000,
          timerProgressBar: true,
        });
        return;
      }

      nombreCompleto = document.getElementById("nombre");
      email = document.getElementById("email");
      telefono = document.getElementById("telefono");
      pais = document.getElementById("pais");
      adultos = document.getElementById("adultos");
      ninos = document.getElementById("ninos");
      idioma = document.getElementById("idioma");
      viajeroPrincipal = document.getElementById("viajeroPrincipal");
      pasaporteOdocumento = document.getElementById("documento");
      fechasDeIngreso = document.querySelectorAll(".start-date-input");
      fechasDeSalida = document.querySelectorAll(".end-date-input");
      seguro = document.getElementById("seguro");
      transporte = document.getElementById("transporte");
      fotos = document.getElementById("fotos");
      desayuno = document.getElementById("desayuno");
      almuerzo = document.getElementById("almuerzo");
      cena = document.getElementById("cena");
      direccion = document.getElementById("direccion");
      ciudad = document.getElementById("ciudad");
      provincia = document.getElementById("provincia");
      codigoPostal = document.getElementById("zip");
      nombreDelTitular = document.getElementById("cardName");
      numeroDeLaTarjeta = document.getElementById("cardNumber");
      mes = document.getElementById("cardMonth");
      year = document.getElementById("cardYear");
      cvv = document.getElementById("cardCvv");

      const isValidCheckout = validateCheckoutForm({
        stringsRequeridos: [
          nombreCompleto,
          email,
          telefono,
          pais,
          idioma,
          viajeroPrincipal,
          pasaporteOdocumento,
          direccion,
          ciudad,
          provincia,
          codigoPostal,
          nombreDelTitular,
        ],
        numerosRequeridos: [adultos, ninos, numeroDeLaTarjeta, mes, year, cvv],
        fechasRequeridas: [fechasDeIngreso, fechasDeSalida],
      });
      if (isValidCheckout) {
        const formData = new FormData();

        const ingresosValues = Array.from(fechasDeIngreso, (el) => el.value);
        const salidaValues = Array.from(fechasDeSalida, (el) => el.value);

        formData.append("nombre", nombreCompleto.value);
        formData.append("email", email.value);
        formData.append("telefono", telefono.value);
        formData.append("pais", pais.value);
        formData.append("adultos", adultos.value);
        formData.append("ninos", ninos.value);
        formData.append("idioma", idioma.value);
        formData.append("viajeroPrincipal", viajeroPrincipal.value);
        formData.append("pasaporteOdocumento", pasaporteOdocumento.value);
        formData.append("fechasDeIngreso", JSON.stringify(ingresosValues));
        formData.append("fechasDeSalida", JSON.stringify(salidaValues));
        formData.append("seguro", seguro.checked);
        formData.append("transporte", transporte.checked);
        formData.append("fotos", fotos.checked);
        formData.append("desayuno", desayuno.checked);
        formData.append("almuerzo", almuerzo.checked);
        formData.append("cena", cena.checked);
        formData.append("direccion", direccion.value);
        formData.append("ciudad", ciudad.value);
        formData.append("provincia", provincia.value);
        formData.append("codigoPostal", codigoPostal.value);
        formData.append("nombreDelTitular", nombreDelTitular.value);
        formData.append("numeroDeLaTarjeta", numeroDeLaTarjeta.value);
        formData.append("mes", mes.value);
        formData.append("year", year.value);
        formData.append("cvv", cvv.value);

        //TODO Enviar la data al endpoint de PHP

        Swal.fire({
          icon: "success",
          title: "Pago Procesado",
          text: `Gracias por tu compra, tu pago ha sido procesado exitosamente.`,
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 5000,
          timerProgressBar: true,
        });
        localStorage.removeItem("cart");
        await updateBasket();
        await updateCartTotal();
        await setTourDetailsForm();
        renderFlags();
        return;
      }
    });
  });
})();
