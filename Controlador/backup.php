<?php
require_once('../Modelo/funcionesBD.php');
require_once('../Modelo/db_backup.php');
require_once('../Modelo/db.php');
require_once('../Vista/VistasHTML.php');


if (isset($_GET['download'])) {
    if (!is_string($db=DB_connection())) {
      session_start();
      $mensaje="Un administrador con email ".$_SESSION['usuario']." HA realizado un BACKUP con éxito";
      insertLog($mensaje, $db);
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename="db_backup.sql"');
      echo DB_backup($db);
      DB_disconnection($db);
   }
} else {
    HTMLinicio("Ejemplo de PHP");
    HTMLheader();
    HTMLnav(0);
    $_SESSION['imagen']='';
    HTMLdivmain();
    echo "<a href='".$_SERVER['SCRIPT_NAME']."?download' id='linkk'>Pulse aquí</a> para descargar un fichero con los datos de la copia de seguridad";
    HTMLcierremain();
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
  }

  





?>