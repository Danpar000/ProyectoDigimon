// ASADSADSADSADSA
// document.addEventListener("DOMContentLoaded", function() {
//     document.getElementById("miform").addEventListener("submit", validarForm);
// });

// function validarForm(evento) {
//     evento.preventDefault();

//     let errores = false;
//     let dni = document.getElementById("idFiscal").value;
//     let cif = document.getElementById("idFiscal").value;
//     let contact_phone_number = document.getElementById("contact_phone_number").value;
//     let company_phone_number = document.getElementById("company_phone_number").value;
//     let contact_email = document.getElementById("contact_email").value;

//     document.getElementById("alerta").innerHTML = "";
//     let error = "";

//     if (!isValidDni(dni) && !isValidCif(cif)) {
//         error += " DNI o CIF incorrecto<br> ";
//         document.getElementById("alerta").className = "alert alert-danger visible";
//         errores = true;
//     }

//     if (!isValidEmail(contact_email)) {
//         error += " Formato email incorrecto<br> ";
//         errores = true;
//     }

//     if (!isValidPhone(contact_phone_number)) {
//         error += " Teléfono de contacto incorrecto<br> ";
//         errores = true;
//     }

//     if (!isValidPhone(company_phone_number)) {
//         error += " Teléfono de compañía incorrecto<br> ";
//         errores = true;
//     }

//     document.getElementById("alerta").innerHTML = error;

//     if (!errores) {
//         let enviar = confirm("¿Quieres enviar estos datos al servidor?");
//         if (enviar) {
//             document.getElementById("miform").submit();
//         }
//     }
// }

// function isValidEmail(email) {
//     const pattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+.[a-zA-Z]{2,}$/;
//     if (!pattern.test(email)) {
//         document.getElementById("alerta").className = "alert alert-danger visible";
//         return false;
//     }
//     return true;
// }

// function isValidDni(dni) {
//     const letter = dni.slice(-1);
//     const numbers = dni.slice(0, -1);
//     const pattern = /^[0-9]{7,8}[A-Z]$/;
//     const validLetter = "TRWAGMYFPDXBNJZSQVHLCKE";
//     const letterFromNumber = validLetter.charAt(numbers % 23);

//     if (!pattern.test(dni) || letter !== letterFromNumber || letter.length !== 1) {
//         return false;
//     }
//     return true;
// }

// function isValidCif(cif) {
//     const pattern = /^([ABCDEFGHJKLMNPQRSUVW])(\d{7})([0-9A-J])$/;
//     if (!pattern.test(cif)) {
//         return false;
//     }
//     return true;
// }

// function isValidPhone(number) {
//     const pattern = /^[0-9]{9}$/;
//     if (!pattern.test(number)) {
//         document.getElementById("alerta").className = "alert alert-danger visible";
//         return false;
//     }
//     return true;
// }
