<?php


require_once('pagcode.php');
require_once('../Modelo/db.php');       // Operaciones con BBDD
require_once('../Vista/VistasHTML.php');
require_once('../Modelo/funcionesBD.php');  // Modelo de datos para manipular ciudades

// Conexión con la BBDD
if (is_string($db=DB_connection())) {
  $msg_err = $db;
  require('error.php');
}

// ************* Argumentos GET de la página
$datos = checkRequest($_GET);

// ************* Contenido

// ************* Inicio de la página
HTMLinicio("ProyectoTW");
HTMLheader();
HTMLnav(0);
$_SESSION['imagen']='';

// Obtener listado de ciudades
if ($_SESSION['rol']=='A'){
  $numusuarios=getNumUsuariosBD($db);
  $usuarios=get_UsuariosBD($db,$datos['primero'],$datos['numitems']);
}
if ($_SESSION['rol']=='S'){
  $numusuarios=getNumPacientesBD($db);
  $usuarios=get_PacientesBD($db,$datos['primero'],$datos['numitems']);
}


// Mostrar listado
if ($usuarios!==false)
	listado_pag_usuarios($usuarios, '');
else
	$info[] = 'Ha habido un error en la consulta a la BBDD';

// Barra de paginación
if ($datos['numitems']>0) {
  $accion='listadoUsuarios';
  htmlNavpaginado('paginador', build_pagLinks('Controlador/listadoPacientes.php/', $numusuarios, $datos['numitems'], $datos['primero']), $accion);
}





// Desconectar de la BBDD (se puede omitir)
DB_disconnection($db);

// ************* Funciones auxiliares
// Verificar argumentos de la petición web
function checkRequest($get) {
  $datos = [];

  // ************* Argumentos GET de la página
  //  primero: Primer item a visualizar
  //  items : cuantos items incluir (<=0 para ver todos)
  if (!isset($get['items']))
    $datos['numitems'] = 5;   // Valor por defecto
  else if ($get['items']<1 || !is_numeric($get['items']))
    $datos['numitems'] = 0;    // Para mostrar todos los items
  else
    $datos['numitems'] = $get['items'];

  if ($datos['numitems']==0)
    $datos['primero']=0;      // Ver todos los items
  else {
    $datos['primero'] = isset($get['primero']) ? $get['primero'] : 0;
    if ($datos['primero']<0 || !is_numeric($datos['primero']))
      $datos['primero']=0;
  }
  return $datos;
}


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