import {setOnChangeEvents} from "./utils.module.js";
import {validateProfileForm} from "./profile-module.js";

document.addEventListener("DOMContentLoaded", ()=> {
    const profileForm = document.getElementById("profile-form");

    profileForm.addEventListener("submit", (event)=> {
        event.preventDefault();
        const nombreCompleto = document.getElementById("fullName");
        const email = document.getElementById("email");
        const telefono = document.getElementById("phone");
        const password = document.getElementById("password");
        const sexo = document.getElementById("sexo");
        const fechaDeNacimiento = document.getElementById("birthdate");
        const confirmPassword = document.getElementById("confirm-password");
        const pais = document.getElementById("country");
        const idioma = document.getElementById("idioma");
        const pasaporteOdocumento = document.getElementById("documento");
        const direccion = document.getElementById("direccion");
        const ciudad = document.getElementById("ciudad");
        const provincia = document.getElementById("provincia");
        const codigoPostal = document.getElementById("zip");

        setOnChangeEvents({
            inputTextStrings: [nombreCompleto, ciudad, provincia],
            inputNumbers: [
                telefono,
                pasaporteOdocumento,
                codigoPostal,
            ],
        });

        const isValidProfileForm = validateProfileForm({
                stringsRequeridos: [
                  nombreCompleto,
                  email,
                  telefono,
                  pais,
                  password,
                  confirmPassword,
                  idioma,
                  pasaporteOdocumento,
                  direccion,
                  ciudad,
                  provincia,
                  sexo,
                ],
                numerosRequeridos: [codigoPostal],
                fechasRequeridas: [fechaDeNacimiento],
        });
    })
})