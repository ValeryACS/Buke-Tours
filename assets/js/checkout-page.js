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
  calculateExtras,
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
    const btnPayWithCreditCar = document.getElementById("pagar-con-tarjeta-btn");
    const btnPayWithPaypal = document.getElementById("btn-pay-with-paypal");

    const nombreCompleto = document.getElementById("nombre");
    const email = document.getElementById("email");
    const telefono = document.getElementById("telefono");
    const pais = document.getElementById("pais");
    const adultos = document.getElementById("adultos");
    const ninos = document.getElementById("ninos");
    const idioma = document.getElementById("idioma");
    const viajeroPrincipal = document.getElementById("viajeroPrincipal");
    const pasaporteOdocumento = document.getElementById("documento");
    const fechasDeIngreso = document.querySelectorAll(".start-date-input");
    const fechasDeSalida = document.querySelectorAll(".end-date-input");
    const seguro = document.getElementById("seguro");
    const transporte = document.getElementById("transporte");
    const fotos = document.getElementById("fotos");
    const desayuno = document.getElementById("desayuno");
    const almuerzo = document.getElementById("almuerzo");
    const cena = document.getElementById("cena");
    const direccion = document.getElementById("direccion");
    const ciudad = document.getElementById("ciudad");
    const provincia = document.getElementById("provincia");
    const codigoPostal = document.getElementById("zip");
    const nombreDelTitular = document.getElementById("cardName");
    const numeroDeLaTarjeta = document.getElementById("cardNumber");
    const mes = document.getElementById("cardMonth");
    const year = document.getElementById("cardYear");
    const cvv = document.getElementById("cardCvv");
    const subtotal = document.getElementById("subtotal")
    const total = document.getElementById("total")

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

    Array.from([seguro, transporte, fotos, desayuno, almuerzo,cena, adultos, ninos]).forEach((element)=> {
      element.addEventListener("change", (_)=> {
        calculateExtras({
          children: Number(ninos.value),
          adultos: Number(adultos.value),
          hasBreakfast: desayuno.checked,
          hasLaunch: almuerzo.checked,
          hasDinner: cena.checked,
          hasSecurity: seguro.checked,
          hasPhotos: fotos.checked,
          hasTransport: transporte.checked,
          subtotal: subtotal.value,
          total: total.value
        })
      })
    })

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

        //TODO Calcular Total y Subtotal luego Enviar la data al API de Paypal

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
      }
    });

    btnPayWithCreditCar.addEventListener("click", async (event) => {
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

        //TODO Calcular Total y Subtotal luego Enviar la data al endpoint de PHP

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
      }
    });
  });
})();
