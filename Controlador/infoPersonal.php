<?php
require_once('../Vista/VistasHTML.php');
require_once('../Modelo/db.php');
require_once('../Modelo/funcionesBD.php');

HTMLinicio("ProyectoTW");
HTMLheader();
HTMLnav(0);
$_SESSION['imagen']='';
$db=DB_connection();
$res=recuperarDatos( $_SESSION['usuario'], $db);
informacionUsuario($res);
DB_disconnection($db);

if (isset($_SESSION["login"])){
    if (($_SESSION["login"])=="true" )
        HTMLaside($_SESSION["login"], $_SESSION["usuario"]);
    else
        HTMLaside('', '');
}
else 
    HTMLaside('', '');
HTMLfooter();
HTMLfin();
?>