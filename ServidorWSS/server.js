import { WebSocketServer } from "ws";
import Player from "./class/Player.js";

const PORT = 81;
const wss = new WebSocketServer({ port: PORT });
console.log(`Server started on ws://localhost:${PORT}\n`);


let players = [];


wss.on('connection', (socket) => {
    console.log('A client connected!');
    socket.on('message', (data) => {
        const message = JSON.parse(data);
        console.log('Message summary:');
        handleMessage(socket, message);

        for (let p of players) {
            console.log('Player name: ', p.username, '\n');
        }

        console.log('End of Message\n');
    });

    socket.on('close', () => {
        console.log('Alguien se desconectó.');
    });
});

function handleMessage(socket, message) {
    switch (message.type) {
        case 'message':
            console.log('Received a message');
            break;
        case 'createPlayer':
            console.log('Creating player...', message);
            createPlayer(socket, message);
            break;
        case 'handleDisconnect':
            console.log('Disconecting user...', message);
            handleDisconnect(socket, message);
            break;
        default:
            console.log('Received an unkown message', message);
            break;
    }
}

function handleDisconnect(socket, object) {
    console.log(`El jugador ${object.username} se desconectó.`);

    players = players.filter(player => player.username !== object.username);

    console.log("CURRENT PLAYERS: ", players.map(p => p.username));
}

function createPlayer(socket, object) {
    // Verifica si el jugador ya existe
    let existingPlayer = players.find(p => p.username === object.username && object.username !== "");

    if (existingPlayer) {
        console.log(`El jugador ${object.username} ya está en la lista.`);
        existingPlayer.socket = socket; // Actualiza el socket con el nuevo
    } else {
        let player = new Player(socket, object.username);
        players.push(player);
        console.log(`Se ha añadido ${object.username} a la lista de jugadores.`);
    }

    console.log("[CREATE] - CURRENT PLAYERS: ", players.map(p => p.username));
}