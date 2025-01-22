// Mostrar errores en el contenedor
function mostrarErrores(mensajes) {
    const errores = document.getElementById('errores');
    errores.innerHTML = mensajes.join('<br>');
    errores.style.display = 'block';
}

// Validar NOMBRE y APELLIDOS
function validarTexto(campo, mensajes, nombreCampo) {
    if (campo.value.trim() === '') {
        mensajes.push(`${nombreCampo} es obligatorio.`);
        if (!campo.classList.contains('error')) {
            campo.classList.add('error');
        }
    } else {
        campo.classList.remove('error');
    }
}

// Validar EDAD
function validarEdad(campo, mensajes) {
    const edad = parseInt(campo.value);
    if (isNaN(edad) || edad < 0 || edad > 105) {
        mensajes.push('La edad debe ser un número entre 0 y 105.');
        if (!campo.classList.contains('error')) {
            campo.classList.add('error');
        }
    } else {
        campo.classList.remove('error');
    }
}

// Validar NIF
function validarNIF(campo, mensajes) {
    const regex = /^\d{8}-[A-Za-z]$/; // 8 números, un guion y una letra
    if (!regex.test(campo.value)) {
        mensajes.push('El NIF debe tener el formato 8 dígitos, un guion y una letra.');
        if (!campo.classList.contains('error')) {
            campo.classList.add('error');
        }
    } else {
        campo.classList.remove('error');
    }
}

// Validar EMAIL
function validarEmail(campo, mensajes) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Estructura básica de email
    if (!regex.test(campo.value)) {
        mensajes.push('El email no tiene un formato válido.');
        if (!campo.classList.contains('error')) {
            campo.classList.add('error');
        }
    } else {
        campo.classList.remove('error');
    }
}

// Validar PROVINCIA
function validarProvincia(campo, mensajes) {
    if (campo.value === '0') {
        mensajes.push('Debe seleccionar una provincia.');
        if (!campo.classList.contains('error')) {
            campo.classList.add('error');
        }
    } else {
        campo.classList.remove('error');
    }
}

// Validar FECHA
function validarFecha(campo, mensajes) {
    const regex = /^\d{2}[-/]\d{2}[-/]\d{4}$/; // Formato dd/mm/aaaa o dd-mm-aaaa
    if (!regex.test(campo.value)) {
        mensajes.push('La fecha debe tener el formato dd/mm/aaaa o dd-mm-aaaa.');
        if (!campo.classList.contains('error')) {
            campo.classList.add('error');
        }
    } else {
        campo.classList.remove('error');
    }
}

// Validar TELEFONO
function validarTelefono(campo, mensajes) {
    const regex = /^\d{9}$/; // 9 dígitos
    if (!regex.test(campo.value)) {
        mensajes.push('El teléfono debe tener 9 dígitos.');
        if (!campo.classList.contains('error')) {
            campo.classList.add('error');
        }
    } else {
        campo.classList.remove('error');
    }
}

// Validar HORA
function validarHora(campo, mensajes) {
    const regex = /^\d{2}:\d{2}$/; // Formato hh:mm
    if (!regex.test(campo.value)) {
        mensajes.push('La hora debe tener el formato hh:mm.');
        if (!campo.classList.contains('error')) {
            campo.classList.add('error');
        }
    } else {
        campo.classList.remove('error');
    }
}

// Validar el formulario completo
function validarFormulario(event) {
    event.preventDefault();

    const mensajes = [];
    const campos = [
        { campo: document.getElementById('nombre'), nombre: 'Nombre', validador: validarTexto },
        { campo: document.getElementById('apellidos'), nombre: 'Apellidos', validador: validarTexto },
        { campo: document.getElementById('edad'), nombre: 'Edad', validador: validarEdad },
        { campo: document.getElementById('nif'), nombre: 'NIF', validador: validarNIF },
        { campo: document.getElementById('email'), nombre: 'Email', validador: validarEmail },
        { campo: document.getElementById('provincia'), nombre: 'Provincia', validador: validarProvincia },
        { campo: document.getElementById('fecha'), nombre: 'Fecha', validador: validarFecha },
        { campo: document.getElementById('telefono'), nombre: 'Teléfono', validador: validarTelefono },
        { campo: document.getElementById('hora'), nombre: 'Hora', validador: validarHora },
    ];

    campos.forEach(({ campo, nombre, validador }) => {
        validador(campo, mensajes, nombre);
    });

    if (mensajes.length > 0) {
        mostrarErrores(mensajes);
        return false;
    }

    if (confirm('¿Está seguro de que desea enviar el formulario?')) {
        document.getElementById('formulario').submit();
    }
}

// Asignar eventos al cargar la página
window.onload = function () {
    document.getElementById('formulario').onsubmit = validarFormulario;
};


/*
    Explicaciones de expresiones regulares:
    NIF (/^\d{8}-[A-Za-z]$/)
        ^ indica el inicio de la cadena.
        \d{8} valida exactamente 8 dígitos numéricos.
        - obliga a incluir un guion literal.
        [A-Za-z] asegura una única letra (mayúscula o minúscula).
        $ indica el final de la cadena.

    EMAIL (/^[^\s@]+@[^\s@]+\.[^\s@]+$/)
        ^ y $ aseguran que la validación cubre toda la cadena.
        [^\s@]+ valida una secuencia sin espacios ni caracteres @.
        @ requiere la presencia del carácter arroba.
        \. valida un punto literal entre el dominio y la extensión.
    
    FECHA (/^\d{2}[-/]\d{2}[-/]\d{4}$/)
        \d{2} valida dos dígitos para el día y el mes.
        [-/] permite usar guion (-) o barra (/) como separadores.
        \d{4} asegura un año de 4 dígitos.

    TELÉFONO (/^\d{9}$/)
        \d{9} valida exactamente 9 dígitos consecutivos.
        No permite espacios ni caracteres adicionales.

    HORA (/^\d{2}:\d{2}$/)
    \d{2} valida dos dígitos para las horas y los minutos.
    : obliga a un carácter de dos puntos entre horas y minutos.
*/