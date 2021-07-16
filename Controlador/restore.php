<?php
require_once('../Vista/VistasHTML.php');
require_once('../Modelo/db_backup.php');
require_once('../Modelo/db.php');
require_once('../Modelo/funcionesBD.php');

HTMLinicio("ProyectoTW");
HTMLheader();
HTMLnav(0);

$_SESSION['imagen']='';
if (isset($_POST['submit'])) {
    $db=DB_connection();
	/* Comprobar que se ha subido algún fichero */
	if ((sizeof($_FILES)==0) || !array_key_exists("fichero",$_FILES))
		$error = "No se ha podido subir el fichero";
	else if (!is_uploaded_file($_FILES['fichero']['tmp_name']))
		$error = "Fichero no subido. Código de error: ".$_FILES['fichero']['error'];
	else {
    	$db=DB_connection();
        
		$error = DB_restore($db,$_FILES['fichero']['tmp_name']);
		$mensaje="Un administrador con email ".$_SESSION['usuario']." HA realizado un RESTORE del sistema correctamente";
		insertLog($mensaje, $db);
		mensajeRestoreCorrecto();
		if ($_SESSION['usuario']=='primerusuario@correo.ugr.es')
			insertUsuarioPrueba($db);
    	DB_disconnection($db);
	}
		

} else
	gestionBD();

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