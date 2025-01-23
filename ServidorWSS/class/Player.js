export default class Player {
    constructor(socket, username) {
        this._socket = socket;
        this._username = username;
    }

    get socket() {
        return this._socket;
    }

    set socket(value) {
        this._socket = value;
    }

    get username() {
        return this._username;
    }

    set username(value) {
        this._username = value;
    }
}