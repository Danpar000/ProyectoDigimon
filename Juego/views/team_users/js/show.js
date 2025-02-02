let select = document.getElementById("baseContainer__formSwapDigimons");
let label = document.getElementById("baseContainer__label");
let option = document.createElement("option");
let oldDigimon_id = document.getElementById("oldDigimon_id");
option.setAttribute("value", "");
option.innerText = "-- Elige un Digimon --";
option.selected = true;
select.appendChild(option);

async function actualizarOptions() {
    let id = await getSessionID();
    console.log(id);
    let currentTeam = await getTeam(id);
    console.log(currentTeam);
    let notUsed = await getDigimonsNotInUse(id, currentTeam);
    console.log(notUsed);

    select.innerHTML = "";
    select.disabled = false;

    notUsed.forEach(digimon => {
        let option = document.createElement("option");
        option.setAttribute("value", digimon.id);
        option.innerText = ` ${digimon.name} | #${digimon.id} | ${digimon.type} | Lvl. ${digimon.level}`;
        select.appendChild(option);
    });

    select.appendChild(option);
}


async function getSessionID() {
    try {
        let response = await fetch("public/api.php?funcion=getSessionID");
        return await response.json();
    } catch (error) {
        console.log(error);
        return null;
    }
}

async function getTeam(id) {
    console.log(`public/api.php?funcion=verEquipo&id=${id}`);
    try {
        let response = await fetch(`public/api.php?funcion=verEquipoJson&id=${id}`);
        return await response.json();
    } catch (error) {
        console.log(error);
        return null;
    }
}

async function getDigimonsNotInUse(id, currentTeam) {
    let array = [];
    currentTeam.forEach(element => {
        array.push(element.digimon_id);
    });
    try {
        let response = await fetch(`public/api.php?funcion=listarNoUsadosJson&id=${id}&digimon_id1=${array[0]}&digimon_id2=${array[1]}&digimon_id3=${array[2]}`);
        return await response.json();
    } catch (error) {
        console.log(error);
        return null;
    }
}

async function changeDigimon(newDigimon_id, oldDigimon_id) {
    let id = await getSessionID();
    let currentTeam = await getTeam(id);
    let notUsed = await getDigimonsNotInUse(id, currentTeam);
    let digimon = notUsed.find(digimon => digimon.id == newDigimon_id);
    let equipado = currentTeam.find(equipped => equipped.digimon_id == newDigimon_id);
    if (digimon && !equipado) {
        console.log("Es valido");
        try {
            let response = await fetch(`public/api.php?funcion=editarEquipo&newDigimon_id=${newDigimon_id}&oldDigimon_id=${oldDigimon_id}`);
            if (response.ok) {
                console.log("Se han hecho los cambios correctamente");
                let cards = document.querySelectorAll(".card__container");
                cards.forEach(card => card.classList.remove("card__container--selected"));
                updateCard(newDigimon_id, oldDigimon_id);
            } else {
                console.log("No se ha podido cambiar el digimon");
            }
        } catch (error) {
            console.log(error);
        }
    } else {
        alert("No puedes cambiar a un digimon que no tienes");
    }
}

function selectCard() {
    let cards = document.querySelectorAll(".card__container");
    cards.forEach(card => {
        card.addEventListener("click", () => {
            cards.forEach(c => c.classList.remove("card__container--selected"));
            card.classList.add("card__container--selected");
            oldDigimon_id.value = card.id;
            let name = card.firstElementChild.firstElementChild.innerText;
            label.firstChild.innerText = `Cambiar a ${name} por:`;
            actualizarOptions();
        });
    });
}

async function updateCard(newDigimon_id, oldDigimon_id) {
    let response = await fetch(`public/api.php?funcion=verDigimonJson&id=${newDigimon_id}`);
    let digimon = await response.json();
    let card = document.getElementById(oldDigimon_id);
    card.classList = `card__container card__container--level${digimon.level}`
    card.id = digimon.id;
    card.innerHTML = `
        <div id="card__topArea" class="card__topArea">
            <h4 class="card__title">${digimon.name} | #${digimon.id}</h4>
            <img class="card__image" src="../Administrador/assets/img/digimons/${digimon.name}/base.png"><br>
        </div>
        <div id="card__backwardsArea" class='card__backwardsArea card__backwardsArea--level${digimon.level}' hidden>
            <div class="card__type">
                <img class="card__statImg" src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">
                <p>Tipo: ${digimon.type}</p>
                <img class="card__statImg card__statImg--reverse" src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">
            </div>
            <div class="card__type card__type-health">
                <img class="card__statImg" src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">
                <p>Vida: ${digimon.health}</p>
                <img class="card__statImg card__statImg--reverse" src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">
            </div>
            <div class="card__type card__type-attack">
                <img class="card__statImg" src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">
                <p>Ataque: ${digimon.attack}</p>
                <img class="card__statImg card__statImg--reverse" src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">
            </div>
            <div class="card__type card__type-attack">
                <img class="card__statImg" src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">
                <p>Defensa: ${digimon.defense}</p>
                <img class="card__statImg card__statImg--reverse" src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">
            </div>
            <div class="card__type card__type-speed">
                <img class="card__statImg" src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">
                <p>Velocidad: ${digimon.speed}</p>
                <img class="card__statImg card__statImg--reverse" src="https://www.svgrepo.com/show/335228/heart-solid.svg" width="15px">
            </div>
        </div>
        <div class="card__bottomArea">
            <button id="mirar">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"></path>
                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"></path>
                </svg>
            </button>
        </div>
    `;

    let mirarButton = card.querySelector("#mirar");
    mirarButton.addEventListener("click", function() {
        let topArea = card.querySelector("#card__topArea");
        let backwardsArea = card.querySelector("#card__backwardsArea");
        if (topArea.hidden) {
            topArea.hidden = false;
            backwardsArea.hidden = true;
        } else {
            topArea.hidden = true;
            backwardsArea.hidden = false;
        }
    });
    label.firstChild.innerHTML = "Cambios realizados con éxito";
    oldDigimon_id.value = "";
    select.innerHTML = "";
    select.append(option);
    select.disabled = true;
}

select.addEventListener("change", () => {
    changeDigimon(select.options[select.selectedIndex].value, oldDigimon_id.value);
})

document.addEventListener("DOMContentLoaded", () => {
    console.log("CARGADO");
    selectCard();
});

