<?php
session_start();

if (!isset ($_SESSION["username"])){
    $_SESSION=[];
    session_destroy();
    header ("location:login.php");
    exit ();
}