const IP = "192.168.42.253";
const PORT = 81;
const URL = `ws://${IP}:${PORT}`;
let ws = new WebSocket(URL);

// Al abrir
ws.onopen = async () => {
    console.log("Conectado al websocket");
    let datos = {type: "", username: ""}
    let message = "createPlayer";
    let username = await getSessionUsername();
    datos.type = message;
    datos.username = username;
    ws.send(JSON.stringify(datos))

    document.getElementById("logout").addEventListener("click", () => {
        ws.send(JSON.stringify({type: "handleDisconnect", username: username}));
        ws.close();
    })
}

// Recibir mensaje
ws.onmessage = (event) => {
    let li = document.createElement("li");
    li.textContent = event.data;
    document.getElementById("mensajes").appendChild("li");
}

// Cerrar conexión
ws.onclose = () => {
    console.log("Conexión cerrada");
}

async function getSessionUsername() {
    try {
        let response = await fetch("public/api.php?funcion=getSessionUsername");
        return await response.json();
    } catch (error) {
        console.log(error);
        return null;
    }
}

document.querySelectorAll(".card").forEach(card => {
    card.addEventListener("mousemove", (event) => {
        const { clientX, clientY } = event;
        const rect = card.getBoundingClientRect();
        const centerX = rect.left + rect.width / 2;
        const centerY = rect.top + rect.height / 2;
        const offsetX = (clientX - centerX) / 10;
        const offsetY = (clientY - centerY) / 10;
        card.style.transform = `rotateY(${offsetX}deg) rotateX(${offsetY}deg)`;
    });

    card.addEventListener("mouseleave", () => {
        card.style.transform = "rotateY(0deg) rotateX(0deg)";
    });

    const button = card.querySelector("#mirar");
    if (button) {
        button.addEventListener("click", function () {
            // Buscar el div siguiente con id que tenga "hidden"
            const bottomCard = card.querySelector(".bottom-card");

            // Verificar si tiene el atributo "hidden"
            if (bottomCard.hasAttribute("hidden")) {
                bottomCard.removeAttribute("hidden");  // Quitar el hidden
            } else {
                bottomCard.setAttribute("hidden", "true");  // Poner el hidden
            }
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const transitionOverlay = document.getElementById("transitionOverlay");

    // Al cargar la página, aplica el efecto de entrada
    transitionOverlay.classList.add("fade-in");

    // Agrega el efecto de salida a los enlaces
    document.querySelectorAll("a").forEach(link => {
        link.addEventListener("click", function (event) {
            if (link.id !== "logout") { // Evita que el logout tenga animación de salida
                event.preventDefault(); // Previene la navegación inmediata
                transitionOverlay.classList.remove("fade-in");
                transitionOverlay.classList.add("fade-out");

                // Espera 0.5s antes de ir a la nueva página
                setTimeout(() => {
                    window.location.href = link.href;
                }, 500);
            }
        });
    });
});