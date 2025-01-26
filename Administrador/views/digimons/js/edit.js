console.log("Importado");
let next_evolution_id = document.getElementById("next_evolution_id");


async function viewDigimon(id) {
    try {
        let response = await fetch(`public/api.php?funcion=verJson&id=${id}`);
        return await response.json();
    } catch (error) {
        console.log(error);
        return null;
    }
}

async function getEvolutions(responseJson) {
    try {
        let response = await fetch(`public/api.php?funcion=buscarJson&type=${responseJson.type}&level=${responseJson.level}`)
        return response.json();
    } catch (error) {
        console.log(error);
        return null;
    }
}

async function actualizarOptions() {
    let id = document.getElementById("id").value;
    let disabled = false;
    let select = document.getElementById("next_evolution_id");
    select.innerHTML = "";
    select.disabled = disabled;

    // Obtener digimon
    let currentDigimon = await viewDigimon(id);
    console.log(currentDigimon);

    // Obtener evoluciones posibles
    let nextEvolutions = await getEvolutions(currentDigimon);
    console.log(nextEvolutions);

    
    // Si ya tiene una evolución colocada
    if (currentDigimon.next_evolution_id != null) {
        let currentEvolution = await viewDigimon(currentDigimon.next_evolution_id);
        let currentOption = document.createElement("option");
        currentOption.setAttribute("value", currentEvolution.id);
        currentOption.innerText = "(Actual) " + currentEvolution.level + ". " + currentEvolution.name;
        select.appendChild(currentOption);
    }

    nextEvolutions.forEach(element => {
        console.log(element);
        let option = document.createElement("option");
        option.setAttribute("value", element.id);
        option.innerText = element.level + ". " + element.name;
        select.appendChild(option);
    })

    // Nula
    let option = document.createElement("option");
    option.setAttribute("value", "");
    option.innerText = "Ninguna";
    select.appendChild(option);
}

document.addEventListener("DOMContentLoaded", () => {
    actualizarOptions();
    document.getElementById("miform").addEventListener("submit", validarForm);
});

function validarForm(evento) {
    evento.preventDefault();
    let error = false;
    let errores = "";
    
    let health = document.getElementById("health");
    let attack = document.getElementById("attack");
    let defense = document.getElementById("defense");
    let speed = document.getElementById("speed");
    let alerta = document.getElementById("msg");

    let notNulls = [health, attack, defense, speed];

    alerta.innerHTML = "";
    alerta.className = "alert alert-danger invisible";

    notNulls.forEach(element => {
        if (!checkNulls(element)) {
            switch (element.id) {
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
        let enviar = confirm("¿Quieres editar el Digimon con estos datos?");
        if (enviar) {
            document.getElementById("miform").submit();
        }
    }
}

function checkNulls(element) {
    switch (element.id) {
        case "type":
            let tipos = ["Animal", "Elemental", "Vacuna", "Virus"];
            return tipos.includes(element.value);
        default:
            return element.value.trim() !== "";
    }
}