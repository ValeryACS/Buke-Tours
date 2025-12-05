import {
  updateBasket,
  updateCartTotal,
  validateCoupon,
} from "./cart.module.js";

import {
  setTourDetailsForm,
  validateCheckoutForm,
  renderFlags,
  calculateExtras,
  calculateAccordionTotal,
} from "./checkout-module.js";

import { todayLocalISO, setOnChangeEvents } from "./utils.module.js";

(() => {
  document.addEventListener("DOMContentLoaded", async () => {
    await updateBasket();
    await setTourDetailsForm();
    renderFlags();
    await updateCartTotal();
    const res = await fetch("/Buke-Tours/api/tours/");
    if (!res.ok) {
      throw new Error("Error al cargar tours.json");
    }
    const { data } = await res.json();

    const btnCoupon = document.getElementById("btn-coupon");
    const inputCoupon = document.getElementById("coupon");
    const btnPayWithCreditCar = document.getElementById(
      "pagar-con-tarjeta-btn"
    );
    const btnPayWithPaypal = document.getElementById("btn-pay-with-paypal");

    const nombreCompleto = document.getElementById("nombre");
    const email = document.getElementById("email");
    const telefono = document.getElementById("telefono");
    const pais = document.getElementById("pais");
    const adultos = Array.from(document.querySelectorAll(".adults-quantity"));
    const ninos = Array.from(document.querySelectorAll(".children-quantity"));
    const idioma = document.getElementById("idioma");
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
    const subtotal = document.getElementById("subtotal");
    const total = document.getElementById("total");
    let totalOfAdults = adultos.reduce(
      (acc, input) => acc + Number(input.value),
      0
    );
    let totalOfChildren = ninos.reduce(
      (acc, input) => acc + Number(input.value),
      0
    );

    setOnChangeEvents({
      inputTextStrings: [nombreCompleto, ciudad, provincia, nombreDelTitular],
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

    calculateExtras({
      children: totalOfChildren,
      adultos: totalOfAdults,
      hasBreakfast: desayuno.checked,
      hasLunch: almuerzo.checked,
      hasDinner: cena.checked,
      hasSecurity: seguro.checked,
      hasPhotos: fotos.checked,
      hasTransport: transporte.checked,
      subtotal: subtotal.value,
      total: total.value,
    });

    const minDate = todayLocalISO();
    document
      .querySelectorAll(".start-date-input, .end-date-input")
      .forEach((el) => {
        el.setAttribute("min", minDate);
      });

    Array.from([seguro, transporte, fotos, desayuno, almuerzo, cena, adultos, ninos, Array.from(fechasDeIngreso), Array.from(fechasDeSalida)]).forEach(
      (element) => {
        if (Array.isArray(element)) {
          element.forEach((elmnt) => {
            elmnt.addEventListener("change", (e) => {
              e.preventDefault();
              totalOfAdults = adultos.reduce(
                (acc, input) => acc + Number(input.value),
                0
              );
              totalOfChildren = ninos.reduce(
                (acc, input) => acc + Number(input.value),
                0
              );
              
              calculateAccordionTotal(data);
              calculateExtras({
                children: totalOfChildren,
                adultos: totalOfAdults,
                hasBreakfast: desayuno.checked,
                hasLunch: almuerzo.checked,
                hasDinner: cena.checked,
                hasSecurity: seguro.checked,
                hasPhotos: fotos.checked,
                hasTransport: transporte.checked,
                subtotal: subtotal.value,
                total: total.value,
              });
            });
          });
        } else {
          element.addEventListener("change", (_) => {
            // Recalcular totales en cada cambio antes de llamar a calculateExtras
            totalOfAdults = adultos.reduce(
              (acc, input) => acc + Number(input.value),
              0
            );
            totalOfChildren = ninos.reduce(
              (acc, input) => acc + Number(input.value),
              0
            );

            calculateExtras({
              children: totalOfChildren,
              adultos: totalOfAdults,
              hasBreakfast: desayuno.checked,
              hasLunch: almuerzo.checked,
              hasDinner: cena.checked,
              hasSecurity: seguro.checked,
              hasPhotos: fotos.checked,
              hasTransport: transporte.checked,
              subtotal: subtotal.value,
              total: total.value,
            });
          });
        }
      }
    );

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

        const ingresos = Array.from(fechasDeIngreso).map((input)=>{
         const rawId = input.dataset.tourId; // viene de data-tour-id="${sku}"
          const hasNumericId = rawId !== undefined && rawId !== null && rawId !== "" && !Number.isNaN(Number(rawId));

          return {
            check_in_date: input.value,
            tour_id: hasNumericId ? parseInt(rawId, 10) : rawId || null,
          };
        });
        const salidas = Array.from(fechasDeSalida).map((input)=>{
          const rawId = input.dataset.tourId; // viene de data-tour-id="${sku}"
          const hasNumericId = rawId !== undefined && rawId !== null && rawId !== "" && !Number.isNaN(Number(rawId));

          return {
            check_out_date: input.value,
            tour_id: hasNumericId ? parseInt(rawId, 10) : rawId || null,
          }
        });

        totalOfAdults = adultos.reduce(
          (acc, input) => acc + Number(input.value),
          0
        );
        totalOfChildren = ninos.reduce(
          (acc, input) => acc + Number(input.value),
          0
        );
        

        formData.append("nombre", nombreCompleto.value);
        formData.append("email", email.value);
        formData.append("telefono", telefono.value);
        formData.append("pais", pais.value);
        formData.append("adultos", totalOfAdults);
        formData.append("ninos", totalOfChildren);
        formData.append("idioma", idioma.value);
        formData.append("pasaporteOdocumento", pasaporteOdocumento.value);
        formData.append("fechasDeingresos", JSON.stringify(ingresos));
        formData.append("fechasDeSalidas", JSON.stringify(salidas));
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
        formData.append("subtotal", subtotal.value);
        formData.append("total", total.value);

        try {
          const response = await fetch("/Buke-Tours/api/checkout/", {
            method: "POST",
            body: formData,
          });

          if (!response.ok) {
            const text = await response.text();
            Swal.fire({
              icon: "error",
              title: "Error al procesar el pago",
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
                : result?.message || "No se pudo procesar el pago.";
            Swal.fire({
              icon: "error",
              title: "Pago rechazado",
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
            title: "Pago Procesado",
            text: result?.message || "Tu pago ha sido procesado exitosamente.",
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
          });
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

        const ingresos = Array.from(fechasDeIngreso).map((input)=>{
         const rawId = input.dataset.tourId; // viene de data-tour-id="${sku}"
          const hasNumericId = rawId !== undefined && rawId !== null && rawId !== "" && !Number.isNaN(Number(rawId));

          return {
            check_in_date: input.value,
            tour_id: hasNumericId ? parseInt(rawId, 10) : rawId || null,
          };
        });
        const salidas = Array.from(fechasDeSalida).map((input)=>{
          const rawId = input.dataset.tourId; // viene de data-tour-id="${sku}"
          const hasNumericId = rawId !== undefined && rawId !== null && rawId !== "" && !Number.isNaN(Number(rawId));

          return {
            check_out_date: input.value,
            tour_id: hasNumericId ? parseInt(rawId, 10) : rawId || null,
          }
        });

        totalOfAdults = adultos.reduce(
          (acc, input) => acc + Number(input.value),
          0
        );
        totalOfChildren = ninos.reduce(
          (acc, input) => acc + Number(input.value),
          0
        );
        

        formData.append("nombre", nombreCompleto.value);
        formData.append("email", email.value);
        formData.append("telefono", telefono.value);
        formData.append("pais", pais.value);
        formData.append("adultos", totalOfAdults);
        formData.append("ninos", totalOfChildren);
        formData.append("idioma", idioma.value);
        formData.append("pasaporteOdocumento", pasaporteOdocumento.value);
        formData.append("fechasDeingresos", JSON.stringify(ingresos));
        formData.append("fechasDeSalidas", JSON.stringify(salidas));
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
        formData.append("subtotal", subtotal.value);
        formData.append("total", total.value);

        try {
          const response = await fetch("/Buke-Tours/api/checkout/", {
            method: "POST",
            body: formData,
          });

          if (!response.ok) {
            const text = await response.text();
            Swal.fire({
              icon: "error",
              title: "Error al procesar el pago",
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
                : result?.message || "No se pudo procesar el pago.";
            Swal.fire({
              icon: "error",
              title: "Pago rechazado",
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
            title: "Pago Procesado",
            text: result?.message || "Tu pago ha sido procesado exitosamente.",
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
          });
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

        
        localStorage.removeItem("cart");
        await updateBasket();
        await updateCartTotal();
        await setTourDetailsForm();
        renderFlags();
      }
    });
  });
})();
