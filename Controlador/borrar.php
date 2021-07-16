<?php
require_once('../Modelo/funcionesBD.php');
require_once('../Modelo/db_backup.php');
require_once('../Modelo/db.php');
require_once('../Vista/VistasHTML.php');

HTMLinicio("ProyectoTW");
HTMLheader();
$db=DB_connection();
$mensaje="Un administrador con email ".$_SESSION['usuario']." ha BORRADO todas las TABLAS de la base de datos correctamente";
insertLog($mensaje, $db);
HTMLnav(0);

echo DB_delete($db);
DB_disconnection($db);
borradoTablasCorrecto();
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
