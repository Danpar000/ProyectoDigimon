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
});

