<?php
require_once('../Vista/VistasHTML.php');
require_once('../Modelo/db.php');
require_once('../Modelo/funcionesBD.php');

HTMLinicio("ProyectoTW");
HTMLheader();
HTMLnav(0);

$_SESSION['imagen']='';
informacionAdicional('');
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