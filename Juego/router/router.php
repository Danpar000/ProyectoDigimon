<?php

function GenerarRutaJs(string $route):string{
    $ultimo=strrpos($route,"/");
    $ruta=substr($route,0, $ultimo);
    $partes= (explode("/",$route));
    $fichero= end($partes);
    $partes2=explode(".",$fichero);
    $nombreFichero= $partes2[0].".js";
    $path=$ruta."/js/".$nombreFichero;
    return $path;
    }

function GenerarRutaCSS(string $route):string{
    $ultimo=strrpos($route,"/");
    $ruta=substr($route,0, $ultimo);
    $partes= (explode("/",$route));
    $fichero= end($partes);
    $partes2=explode(".",$fichero);
    $nombreFichero= $partes2[0].".css";
    $path=$ruta."/css/".$nombreFichero;
    return $path;
    }

function router (){
    $url = $_SERVER["REQUEST_URI"];

// si pongo sólo la barra asumo que es ruta por defecto
    if (substr ($url,-1)=="/") return "views/inicio.php";
    if (!strpos ($url,"index.php"))return"views/404.php";

// si hay index y no hay tabla Vista por defecto
    if (!isset ($_REQUEST["tabla"])) return "views/inicio.php";

    $tablas=[
        "user"=>[//defino las acciones permitidas para esa tabla
                "ver"=> "show.php"
        ],
        "digimons"=>[
                "ver"=> "show.php",
                "listar"=>"list.php",
		        "buscar"=>"search.php",
        ],
        "digimons_users"=>[
              "evento"=>"event.php",
              "ver"=>"show.php",
              "listar"=>"list.php",
              "digievolucionar"=>"evolutions.php",
        ],
        "team_users"=>[
            "guardar"=>"store.php",
            "ver"=> "show.php",
            "editar"=>"edit.php"
        ],
        "offline"=>[
            "buscar"=>"search.php",
            "partida"=>"game.php",
            "premio"=>"prize.php",
        ],
        "rooms"=>[
            "buscar"=>"search.php",
            "join"=>"room.php"
        ]
    ];
    $tabla= $_REQUEST["tabla"];
    if (!isset($tablas[$tabla])) return"views/404.php"; 

    // si no hay accion definimos por defecto la accion listar
    if (!isset ($_REQUEST["accion"])) return "views/{$tabla}/list.php";
    // Si la acción no está permitda
    $accion=$_REQUEST["accion"];
    if (!isset($tablas[$tabla][$accion]) ) return"views/404.php"; 
   
    // Por ejemplo si recibo la tabla=user y accion= listar
    // esto llamará a la vista
    // views/user/list.php dentro del requir
    return "views/{$tabla}/{$tablas[$tabla][$accion]}";

}

?>
