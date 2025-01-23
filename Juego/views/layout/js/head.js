let ws = new WebSocket("ws://localhost:81");

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