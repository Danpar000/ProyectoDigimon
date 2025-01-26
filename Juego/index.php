<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
</head>
<body>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
    const overlay = document.getElementById("transitionOverlay");

    // EFECTO AL CARGAR LA PÁGINA (Sólo si está en la vista de inicio)
    const isInicio = document.querySelector(".fondoInicio"); // Si existe, estamos en inicio
    if (isInicio) {
        overlay.classList.add("fade-in"); // Se desvanece el blanco al cargar
        overlay.classList.add("fade-out"); // Se desvanece el blanco al cargar
    }

    // EFECTO AL HACER CLICK (Sólo en botones específicos)
    function attachTransition() {
        // Agregar evento para los botones generales
        document.querySelectorAll("#misDigimon, #miEquipo, #combateOffline, #combateOnline, header a").forEach(button => {
            button.addEventListener("click", (event) => {
                event.preventDefault(); // Evita el cambio inmediato

                const link = button.closest("a").getAttribute("href"); // Obtiene el destino
                if (link && link !== "#") {
                    overlay.classList.remove("fade-in"); // Asegura que no interfiera
                    overlay.classList.add("fade-out"); // Aplica el oscurecimiento

                    setTimeout(() => {
                        window.location.href = link; // Cambia la vista después de la animación
                    }, 500); // Ajusta el tiempo según sea necesario
                }
            });
        });

        // Agregar evento específico para el botón de logout
        const logoutButton = document.getElementById("logout");
        if (logoutButton) {
            logoutButton.addEventListener("click", (event) => {
                event.preventDefault(); // Evita el cambio inmediato

                overlay.classList.remove("fade-in"); // Asegura que no interfiera
                overlay.classList.add("fade-out"); // Aplica el oscurecimiento

                setTimeout(() => {
                    window.location.href = logoutButton.getAttribute("href"); // Cambia la vista a la URL de logout
                }, 500); // Ajusta el tiempo según sea necesario
            });
        }
    }

    // Restablecer el overlay para la siguiente animación cuando se recarga la página
    window.addEventListener("load", () => {
        overlay.classList.remove("fade-out"); // Elimina cualquier clase de desvanecimiento
        overlay.classList.add("fade-in"); // Añade la clase de fundido desde blanco
        overlay.style.opacity = "1"; // Asegura que tenga opacidad completa
    });

    attachTransition(); // Inicializar al cargar
});


    </script>

    <div id="transitionOverlay"></div>
</body>
</html>

<?php
ob_start();
require_once "config/sessionControl.php";
require_once("router/router.php");
require_once("views/layout/head.php");

$vista = router();
?>


<!-- div class="container-fluid" -->
<div>
    <div class="row">
        <?php
        require_once "views/layout/navbar.php";
        if (!file_exists($vista)) echo "Error, REVISA TUS RUTAS";
        else require_once($vista);
        ?>
    </div>
</div>
<?php
require_once("views/layout/footer.php");
ob_end_flush();
?>
