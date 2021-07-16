<?php
require_once('../Modelo/funcionesBD.php');

require_once('../Vista/VistasHTML.php');
require_once('../Modelo/db.php');
HTMLinicio("ProyectoTW");
HTMLheader();
$db=DB_connection();
$mensaje="El usuario con email ".$_SESSION['usuario']." se ha DESLOGUEADO del sistema con éxito";
insertLog($mensaje, $db);
$_SESSION["usuario"]='';
$_SESSION["login"]="false";
$_SESSION["rol"]='';
$_SESSION["correo_mod"]="";
$_SESSION['imagen']='';
$_COOKIE['nombre']='';
setcookie('nombre', '', time()-20000);
$_COOKIE['apellidos']='';
setcookie('apellidos', '', time()-20000);
$_COOKIE['dni']='';
setcookie('dni', '', time()-20000);
$_COOKIE['fechamin']='';
setcookie('fechamin', '', time()-20000);
$_COOKIE['fechamax']='';
setcookie('fechamax', '', time()-20000);
$_COOKIE['ordenacion']='';
setcookie('ordenacion', '', time()-20000);
$_COOKIE['estado']='';
setcookie('estado', '', time()-20000);
$_COOKIE['numSemanas']='';
setcookie('numSemanas', '', time()-20000);
$_COOKIE['pendientes']='';
setcookie('pendientes', '', time()-20000);

HTMLnav(0);

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