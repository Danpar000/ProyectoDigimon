let nivel = document.getElementById("level");
let tipo = document.getElementById("type");

function actualizarOptions() {
    let disabled = false;
    switch(nivel.value){
        case "1":
        case "2":
        case "3":
            disabled = false;
            break;
        case "4":
            disabled = true;
            break;
    }

    switch(tipo.value) {
        case "Elige un tipo":
            disabled = true;
            break
        case "Animal", "Elemental", "Planta", "Vacuna", "Virus":
            disabled = false;
            break;
    }

    
    let select = document.getElementById("next_evolution_id");
    select.innerHTML = "";
    select.disabled = disabled;

    fetch(`public/api.php?funcion=buscarJson&type=${tipo.value}&level=${nivel.value}`)
    .then(response => response.json())
    .then((data) => {
            data.forEach(element => {
                let option = document.createElement("option");
                option.setAttribute("value", element.id);
                option.innerText = element.level + ". " + element.name;
                select.appendChild(option);
            });
            let option = document.createElement("option");
            option.setAttribute("value", "");
            option.innerText = "Ninguna";
            select.appendChild(option);
        })
    .catch("");
}

document.addEventListener("DOMContentLoaded", () => {
    actualizarOptions();
    nivel.addEventListener("change", actualizarOptions);
    tipo.addEventListener("change", actualizarOptions);
    document.getElementById("miform").addEventListener("submit", validarForm);
});

function validarForm(evento) {
    evento.preventDefault();
    let error = false;
    let errores = "";
    
    let name = document.getElementById("name");
    let attack = document.getElementById("attack");
    let defense = document.getElementById("defense");
    let type = document.getElementById("type");
    let image = document.getElementById("image");
    let imageVictory = document.getElementById("imageVictory");
    let imageDefeat = document.getElementById("imageDefeat");
    let alerta = document.getElementById("alerta");

    let notNulls = [name, attack, defense, type, image, imageVictory, imageDefeat];

    alerta.innerHTML = "";
    alerta.className = "alert alert-danger invisible";

    if (!isValidName(name.value)) {
        error = true;
        errores += "El nombre solo puede contener letras y números.";
    }

    if (!checkLength(name.value)) {
        error = true;
        errores += "\nEl nombre no puede contener más de 50 caracteres.";
    }

    notNulls.forEach(element => {
        if (!checkNulls(element)) {
            switch (element.id) {
                case "image":
                case "imageVictory":
                case "imageDefeat":
                    if (element.files.length === 0) {
                        error = true;
                        errores += "\nTienes que subir una imagen en el campo " + element.id + ".";
                    }
                    break;
                case "type":
                    let tipos = ["Animal", "Elemental", "Vacuna", "Virus"];
                    if (!tipos.includes(element.value)) {
                        error = true;
                        errores += "\nTienes que seleccionar un tipo de Digimon válido.";
                    }
                    break;
                default:
                    if (element.value.trim() === "") {
                        error = true;
                        errores += `\nTienes que rellenar el campo ${element.id}.`;
                    }
                    break;
            }
        }
    });

    if (error) {
        alerta.innerHTML = errores;
        alerta.className = "alert alert-danger visible";
    } else {
        let enviar = confirm("¿Quieres crear el Digimon con estos datos?");
        if (enviar) {
            document.getElementById("miform").submit();
        }
    }
}

function isValidName(name){
    const pattern = /^[a-zA-Z0-9]+$/;
    if (!pattern.test(name)) {
        return false;
    }
    return true;
}

function checkLength(name) {
    return name.length <= 50;
}

function checkNulls(element) {
    switch (element.id) {
        case "image":
        case "imageVictory":
        case "imageDefeat":
            return element.files.length > 0;
        case "type":
            let tipos = ["Animal", "Elemental", "Vacuna", "Virus"];
            return tipos.includes(element.value);
        default:
            return element.value.trim() !== "";
    }
}