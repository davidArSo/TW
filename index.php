<?php
require_once('Vista/VistasHTML.php');
require_once('Modelo/db.php');
require_once('Modelo/funcionesBD.php');

$db=DB_connection();
crearTablasSiNoExisten($db);

HTMLinicio("ProyectoTW");
HTMLheader();
HTMLnav(0);
$_SESSION['imagen']='';
if (!isset($_SESSION['rol']))
    $_SESSION['rol']='';
$res=getCalendarioBD($db);
DB_disconnection($db);
HTMLcalendario($res, '');

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