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
    const isInicio = document.querySelector(".fondoInicio");
    if (isInicio) {
        overlay.classList.add("fade-in");
        overlay.classList.add("fade-out");
    }

    function attachTransition() {
        document.querySelectorAll("#misDigimon, #miEquipo, #combateOffline, #combateOnline, header a").forEach(button => {
            button.addEventListener("click", (event) => {
                event.preventDefault();

                const link = button.closest("a").getAttribute("href");
                if (link && link !== "#") {
                    overlay.classList.remove("fade-in");
                    overlay.classList.add("fade-out");

                    setTimeout(() => {
                        window.location.href = link;
                    }, 500);
                }
            });
        });

        const logoutButton = document.getElementById("logout");
        if (logoutButton) {
            logoutButton.addEventListener("click", (event) => {
                event.preventDefault();

                overlay.classList.remove("fade-in");
                overlay.classList.add("fade-out");

                setTimeout(() => {
                    window.location.href = logoutButton.getAttribute("href");
                }, 500);
            });
        }
    }

    window.addEventListener("load", () => {
        overlay.classList.remove("fade-out");
        overlay.classList.add("fade-in");
        overlay.style.opacity = "1";
    });

    attachTransition();
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
    <div class="">
        <?php
        if (!file_exists($vista)) echo "Error, REVISA TUS RUTAS";
        else require_once($vista);
        ?>
    </div>
</div>
<?php
require_once("views/layout/footer.php");
ob_end_flush();
?>
