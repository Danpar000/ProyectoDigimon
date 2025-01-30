<?php
require_once "controllers/digimonsUsersController.php";
require_once "controllers/digimonsController.php";

$mensaje = "";
$clase = "alert alert-success";
$visibilidad = "hidden";
$digimonsController = new DigimonsController();
$digiUserController = new DigimonsUsersController();
$userDigimon = $digiUserController->buscar("user_id", "equals", $_SESSION["username"]->id);
?>


<style>
    @keyframes brillo {
        0% {
            box-shadow: 0 0 20px rgba(66, 165, 245, 1); /* Nivel 1 - Azul */
        }
        25% {
            box-shadow: 0 0 20px rgba(76, 175, 80, 0.8); /* Nivel 2 - Verde */
        }
        50% {
            box-shadow: 0 0 20px rgba(255, 87, 34, 0.8); /* Nivel 3 - Naranja */
        }
        75% {
            box-shadow: 0 0 20px rgba(255, 235, 59, 0.8); /* Nivel 4 - Amarillo */
        }
        100% {
            box-shadow: 0 0 20px rgba(66, 165, 245, 1); /* Nivel 1 - Azul (vuelve a empezar) */
        }
    }

    a {
        color: black;
    }

    a:hover {
        color: white;
    }

    body {
        background-color: black;
        background-image: 
            linear-gradient(to right, rgba(255, 255, 255, 0.2) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(255, 255, 255, 0.2) 1px, transparent 1px);
        background-size: 20px 20px;
        height: 200vh;
        width: 100%;
        margin: 0;
    }

    .baseContainer {
        background: rgb(83,83,83);
        background: radial-gradient(circle, rgba(83,83,83,1) 0%, rgba(181,181,181,1) 51%, rgba(46,46,46,1) 100%);
        border: gray 0.2rem solid;
        border-radius: 1rem;
        display: flex;
        justify-content: center;
        margin: 2rem;
    }

    .baseContainer__grid {
        background-color: gray;
        border: 1rem solid darkgray;
        border-radius: 2rem;
        padding: 2rem;
        margin: 4rem 2rem;
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        grid-template-rows: auto;
        gap: 2rem;
        max-height: 70vh;
        overflow-y: auto;
    }

    .baseContainer__grid::-webkit-scrollbar {
        display: none;
    }

    .baseContainer__optionsContainer {
        background-color: gray;
        border: 0.5rem solid darkgray;
        border-radius: 2rem;
        padding: 2rem;
        margin: 4rem 2rem;
        display: flex;
        flex-direction: column;
        justify-content: start;
    }

    .options__type {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .card__container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: white;
        text-align: center;
        padding: 1rem;
        border: 0.2rem solid;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        transition: box-shadow 0.03s ease-in-out;
        transform-style: preserve-3d;
        min-height: 35vh;
        max-height: 40vh;
    }

    .card__image {
        overflow: hidden;
        min-height: 35vh;
        max-height: 40vh;
    }

    /* -- NIVELES --*/
    .card__container--level1 {
        background: rgb(66,165,245);
        background: radial-gradient(circle, rgba(66,165,245,1) 0%, rgba(30,136,229,1) 51%);
    }

    .card__container--level2 {
        background: rgb(102,187,106);
        background: radial-gradient(circle, rgba(102,187,106,1) 11%, rgba(56,142,60,1) 51%);
    }

    .card__container--level3 {
        background: rgb(214,121,119);
        background: radial-gradient(circle, rgba(214,121,119,1) 0%, rgba(211,47,47,1) 87%);
    }

    .card__container--level4 {
        background: rgb(255,243,141);
        background: radial-gradient(circle, rgba(255,243,141,1) 0%, rgba(255,235,59,1) 88%);
    }

    .card_image {
        width: 75%;
        object-fit: cover; /* Asegura que la imagen se ajuste y no se distorsione */
        border: 0.15rem solid;
        border-radius: 8px;
    }

    /* -- ANIMACIONES -- */
    .card__container:hover {
        transform: scale(1.5);
    }

    .card__container--level1:hover {
        box-shadow: 0 0 20px rgba(66, 165, 245, 1);
    }

    .card__container--level2:hover {
        box-shadow: 0 0 20px rgba(76, 175, 80, 0.8);
    }

    .card__container--level3:hover {
        box-shadow: 0 0 20px rgba(255, 87, 34, 0.8);
    }

    .card__container--level4:hover {
        box-shadow: 0 0 20px rgba(255, 235, 59, 0.8);
    }

    /* Diseño responsivo */
    @media (max-width: 768px) {
        .baseContainer__grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    
</style>

<main>
    <div class="baseContainer">
        <div class="baseContainer__grid">
            <?php
                foreach ($userDigimon as $digimonOwned) {
                    $digimon = $digimonsController->ver($digimonOwned->digimon_id);
                    ?>
                    <a href="index.php?tabla=digimons&accion=ver&id=<?=$digimon->id?>">
                        <?php
                        echo ("<div class='card__container card__container--level{$digimon->level}'>")
                        ?>
                            <div class="card__topArea">
                                <h4 class="card_title"><?= $digimon->name?> | #<?=$digimon->id?></h4>
                                <img class="card_image" src="../Administrador/assets/img/digimons/<?= $digimon->name?>/base.png"><br>
                            </div>
                        </div>
                    </a>
                    <?php
                }
            ?>
        </div>
        <div class="baseContainer__optionsContainer">
            <div class="options__type">
                <form action="index.php" method="POST"></form>
                <label for="selectType">Buscar por tipo</label>
                <select name="selectType" id="selectType">
                    <option value="Vacuna" selected>Vacuna</option>
                    <option value="Virus">Virus</option>
                    <option value="Planta">Planta</option>
                    <option value="Animal">Animal</option>
                    <option value="Elemental">Elemental</option>
                </select>
                <label for="selectNivel">Buscar por Nivel</label>
                <select name="selectNivel" id="selectNivel">
                    <option value="1" selected>Nivel 1</option>
                    <option value="2">Nivel 2</option>
                    <option value="3">Nivel 3</option>
                    <option value="4">Nivel 4</option>
                </select>
                <button type="submit">Buscar</button>
                </form>
            </div>
        </div>
    </div>
</main>


<!-- <style>
    main {
        background-image: url("assets/img/evolution.png");
        background-size: cover;
        width: 100%;
    }
</style> -->
<!-- <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4"> -->
<!-- <main class="main-container">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">Digimones</h1>
    </div>
    <div id="contenido">
            <?php
        foreach($userDigimon as $digimonOwned) {
            $next_digimon = null;
            $digimon = $digimonsController->ver($digimonOwned->digimon_id);
            isset($digimon->next_evolution_id) ? $next_digimon = $digimonsController->ver($digimon->next_evolution_id) : "";
            ?>
            <div class="card" style="width: 18rem;">
                <a href="index.php?tabla=digimons&accion=ver&id=<?= $digimon->id ?>">
                    <div>
                        <h5 class="card-title">ID: <?= $digimon->id ?> <br>DIGIMON: <?= $digimon->name ?></h5>
                        <p class="card-text">
                            Nivel: <?= $digimon->level?> <br>
                            Tipo: <?= $digimon->type?><br>
                            Vida: <?= $digimon->health?> <br>
                            Ataque: <?= $digimon->attack?> <br>
                            Defensa: <?= $digimon->defense?><br>
                            Velocidad: <?= $digimon->speed?> <br>
                            Siguiente evolución: <?= isset($next_digimon) ? $next_digimon->name : "Ninguna" ?><br>
                        </p>
                        <img src="../Administrador/assets/img/digimons/<?= $digimon->name?>/base.png" width="150"><br>
                    </div>
                </a>
            </div>
            
            <?php
        }
        ?>
        <a href="index.php" class="btn btn-primary">Volver</a>      
    </div>
</main> -->