const IP = "192.168.42.253";
//const IP = "172.30.7.251";
const PORT = 82;
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

document.querySelectorAll(".card__container").forEach(card => {
    card.addEventListener("mousemove", (event) => {
        const { clientX, clientY } = event;
        const rect = card.getBoundingClientRect();
        const centerX = rect.left + rect.width / 2;
        const centerY = rect.top + rect.height / 2;

        // Aumentar la sensibilidad del movimiento para que sea más notorio
        const offsetX = (clientX - centerX) / 5; // Sensibilidad
        const offsetY = (clientY - centerY) / 5;

        // Aplicar la transformación 3D y la sombra dinámica en tiempo real
        card.style.transform = `rotateY(${offsetX}deg) rotateX(${offsetY}deg) scale(1.1)`; // Rotación en 3D
        card.style.boxShadow = `${offsetX * 2}px ${offsetY * 2}px 20px rgba(0, 0, 0, 0.3)`; // Sombra dinámica en movimiento
    });

    card.addEventListener("mouseleave", () => {
        // Volver al estado inicial
        card.style.transform = "rotateY(0deg) rotateX(0deg) scale(1)";
        card.style.boxShadow = "0 4px 6px rgba(0, 0, 0, 0.3)";  // Sombra normal cuando el ratón se aleja
    });
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