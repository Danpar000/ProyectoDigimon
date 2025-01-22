function validarForm(evento) {
    evento.preventDefault();
    let error = false;
    let errores = "";
    let username = document.getElementById("username").value;
    let alerta = document.getElementById("alerta");

    alerta.innerHTML = "";
    alerta.className = "alert alert-danger invisible";

    if (!isValidUsername(username)) {
        error = true;
        errores += "El username solo puede contener letras y números.";
    }

    if (!checkLength(username)) {
        error = true;
        errores += "\nEl campo username no puede exceder 50 caracteres.";
    }

    if (error) {
        alerta.innerHTML = errores;
        alerta.className = "alert alert-danger visible";
    } else {
        let enviar = confirm("¿Quieres crear el usuario con estos datos?");
        if (enviar) {
            document.getElementById("miform").submit();
        }
    }
}

function isValidUsername(username) {
    const pattern = /^[a-zA-Z0-9]+$/;
    return pattern.test(username);
}

function checkLength(username) {
    return username.length <= 50;
}

document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("miform").addEventListener("submit", validarForm);
});
