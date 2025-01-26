<?php
function is_valid_username(string $username): bool {
    $patron = "/^[0-9A-Za-z]+$/";
    if (preg_match($patron, $username)) {
        return true;
    }
    return false;
}

function is_valid_name(string $name):bool {
    if (preg_match('/^[a-zA-Z0-9]+$/', $name)){
        return true;
    };
    return false;
}

function HayNulos(array $camposNoNulos, array $arrayDatos): array{
    $nulos = [];
    foreach ($camposNoNulos as $index => $campo) {
        switch ($campo) {
            case "image":
            case "imageVictory":
            case "imageDefeat":
                if ($arrayDatos[$campo]["error"] === 4){
                    $nulos[] = $campo;
                }
                break;
            default:
                if (!isset($arrayDatos[$campo]) || empty($arrayDatos[$campo]) || $arrayDatos[$campo] == null) {
                    $nulos[] = $campo;
                }
                break;
        }
    }
    return $nulos;
}

function existeValor(array $array, string $campo, mixed $valor): bool
{
        return in_array ($array[$campo],$valor);

}

function checkSizeFormat(array $campos, array $arrayDatos) {
    $maxSize = 3 * 1024 * 1024;
    $permitir = ["jpg", "png", "gif", "jpeg"];
    $errores = [];

    foreach ($campos as $campo) {
        if (!isset($arrayDatos[$campo]) || $arrayDatos[$campo]["error"] === 4) {
            continue;
        }

        $extension = strtolower(pathinfo($arrayDatos[$campo]["name"], PATHINFO_EXTENSION));
        if ($arrayDatos[$campo]["size"] > $maxSize || !in_array($extension, $permitir)) {
            $errores[] = $campo;
        }
    }

    return $errores;
}

function DibujarErrores($errores, $campo)
{
    $cadena = "";
    if (isset($errores[$campo])) {
        $last = end($errores);
        foreach ($errores[$campo] as $indice => $msgError) {
            $salto = ($errores[$campo] == $last) ? "" : "<br>";
            $cadena .= "{$msgError}.{$salto}";
        }
    }
    return $cadena;
}