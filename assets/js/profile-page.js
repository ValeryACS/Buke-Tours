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
        const genres = Array.from(document.querySelectorAll(".genre-radio-button"));

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

        if(isValidProfileForm){
            const formData = new FormData();

            const generoSeleccionado = genres.find((genre)=> genre.checked);

            formData.append("nombre", nombreCompleto.value);
            formData.append("email", email.value);
            formData.append("telefono", telefono.value);
            formData.append("pais", pais.value);
            formData.append("password", password.value);
            formData.append("confirmPassword", confirmPassword.value);
            formData.append("idioma", idioma.value);
            formData.append("pasaporteOdocumento", pasaporteOdocumento.value);
            formData.append("direccion", direccion.value);
            formData.append("ciudad", ciudad.value);
            formData.append("provincia", provincia.value);
            formData.append("codigoPostal", codigoPostal.value);
            formData.append("genero", generoSeleccionado.value);

            //TODO enviar al api la data
        }
    })
})