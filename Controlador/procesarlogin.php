<?php
require_once('../Modelo/funcionesBD.php');

require_once('../Vista/VistasHTML.php');
require_once('../Modelo/db.php');
HTMLinicio("ProyectoTW");
HTMLheader();

    
$db=DB_connection();
$res=loginBD(strip_tags($_POST['login']), strip_tags($_POST['clave']), $db);


if ($res==true){
    $mensaje="El usuario con email ".$_POST['login']." se ha LOGUEADO en el sistema con éxito";
    insertLog($mensaje, $db);
    $r=recuperarDatos($_POST['login'], $db);
    /*$r=obtenerRolBD($_POST['login'], $db);*/
    $rol=$r[0]['rol'];
    $_SESSION['foto_usuario']=base64_decode($r[0]['Fotografia']);
    
    $_SESSION["rol"]=$rol;
    $_SESSION["usuario"]=$_POST['login'];
    $_SESSION["login"]="true";
}
else{
    $mensaje="Un usuario con email ".$_POST['login']." se ha INTENTADO LOGUEAR en el sistema pero no ha tenido éxito";
    insertLog($mensaje, $db);
}

HTMLnav(0);

$res=getCalendarioBD($db);
DB_disconnection($db);
HTMLcalendario($res, '');

if (isset($_SESSION["login"])){
    if (($_SESSION["login"])==true )
        HTMLaside($_SESSION["login"], $_SESSION["usuario"]);
    else 
        HTMLaside('', '');
}
else {
    $_SESSION["login"]=false;
    HTMLaside('', '');
}
    

HTMLfooter();
HTMLfin();
?>