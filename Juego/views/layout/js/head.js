const IP = "192.168.42.253";
//const IP = "172.30.7.251";
const PORT = 83;
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

document.querySelectorAll(".card__container--animated").forEach(card => {
    card.addEventListener("mousemove", (event) => {
        const { clientX, clientY } = event;
        const rect = card.getBoundingClientRect();
        const centerX = rect.left + rect.width / 2;
        const centerY = rect.top + rect.height / 2;

        const offsetX = (clientX - centerX) / 5;
        const offsetY = (clientY - centerY) / 5;

        card.style.transform = `rotateY(${offsetX}deg) rotateX(${offsetY}deg) scale(1.1)`;
        card.style.boxShadow = `${offsetX * 2}px ${offsetY * 2}px 20px rgba(0, 0, 0, 0.3)`;
    });

    card.addEventListener("mouseleave", () => {
        card.style.transform = "rotateY(0deg) rotateX(0deg) scale(1)";
        card.style.boxShadow = "0 4px 6px rgba(0, 0, 0, 0.3)";
    });
});


document.addEventListener("DOMContentLoaded", function () {
    const transitionOverlay = document.getElementById("transitionOverlay");
    transitionOverlay.classList.add("fade-in");

    document.querySelectorAll("a").forEach(link => {
        link.addEventListener("click", function (event) {
            if (link.id !== "logout") {
                event.preventDefault();
                transitionOverlay.classList.remove("fade-in");
                transitionOverlay.classList.add("fade-out");

                setTimeout(() => {
                    window.location.href = link.href;
                }, 500);
            }
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("#mirar").forEach(button => {
        button.addEventListener("click", function () {
            let card = this.parentElement.parentElement;
            Array.from(card.children).forEach(child => {
                if (child.id === "card__topArea") {
                    child.hidden = !child.hidden;
                } else if (child.id === "card__backwardsArea") {
                    child.hidden = !child.hidden;
                }
            });
        });
    });
});