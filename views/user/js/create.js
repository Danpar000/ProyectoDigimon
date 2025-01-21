// document.addEventListener("DOMContentLoaded", function() {
//     document.getElementById("miform").addEventListener("submit", validarForm);
// });

// function validarForm(evento) {
//     evento.preventDefault();
//     let errores = false;
//     let error = "";
//     let username = document.getElementById("usuario").value;
//     let password = document.getElementById("password").value;

//     if (!isValidUsername(username)) {
//         error += " Formato usuario incorrecto<br> ";
//         errores = true;
//     }

//     if (!isValidPassword(password)) {
//         error += " Formato Contraseña incorrecta (Tamaño mínimo 6, al menos una letra minuscula, una mayuscula, un número y un símbolo entre: .,-_)<br> ";
//         errores = true;
//     }

//     document.getElementById("alerta").innerHTML = error;

//     if (!errores) {
//         document.getElementById("miform").submit();
//     }
// }

// function isValidPassword(password) {
//     const pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[.,-_])[A-Za-z\d.,-_]{6,}$/;
//     if (!pattern.test(password)) {
//         document.getElementById("alerta").className = "alert alert-danger visible";
//         return false;
//     }
//     return true;
// }

// function isValidUsername(username) {
//     const pattern = /^[0-9A-Za-z]+$/;
//     if (!pattern.test(username)) {
//         document.getElementById("alerta").className = "alert alert-danger visible";
//         return false;
//     }
//     return true;
// }

