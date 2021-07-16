<?php
require_once('pagcode.php');
require_once('../Modelo/db.php');       // Operaciones con BBDD
require_once('../Vista/VistasHTML.php');
require_once('../Modelo/funcionesBD.php');       // Operaciones con BBDD

// Conexión con la BBDD
if (is_string($db=DB_connection())) {
  $msg_err = $db;
  require('error.php');
}

if (isset($_POST['accion'])) {
  $datos = checkRequest($_POST);
  
} else {
  $datos = checkRequestPaginas($_GET);
  $datos = array_merge($datos,checkRequestBusqueda($_GET));
}

HTMLinicio("ProyectoTW");
HTMLheader();
HTMLnav(0);
$_SESSION['imagen']='';

// ************* Contenido
if (isset($datos['busqueda']))
	buscarPacientes($datos['busqueda']);
else
	buscarPacientes();


if ($datos['accion']=='Buscar') {
 
  $busc = Pacientes_buildSearch($db,$datos['busqueda']);
  
  $numpacientes=Pacientes_getNumPacientes($db,$busc, $datos['busqueda']);

  if ($numpacientes>0) {
	  $pacientes=Pacientes_getPacientes($db,$datos['primero'],$datos['numitems'],$busc, $datos['busqueda']);
    
		// Mostrar listado
	  if ($pacientes!==false)
	  	listado_pag_usuarios($pacientes, 'listadoPacientes2');
	  else {
	  	$datos['info'][] = 'Ha habido un error en la consulta a la BBDD';
	  	$datos['info'][] = mysqli_error($db);
	  }
	} else
    mensajeNoPacientes(); 

	// Barra de paginación
	if ($numpacientes>0 && $datos['numitems']>0)
    htmlNavpaginado2('paginador', build_pagLinks('Controlador/listadoPacientes2.php/', $numpacientes, $datos['numitems'], $datos['primero']));
}


if (isset($info) && msgCount($info)>0)
  msgError($info);



// Desconectar de la BBDD (se puede omitir)
DB_disconnection($db);


// ************* Funciones auxiliares
// Verificar argumentos de la petición web
function checkRequestPaginas($param) {
  $datos = [];

  // ************* Argumentos GET de la página
  //  primero: Primer item a visualizar
  //  items : cuantos items incluir (<=0 para ver todos)
  if (!isset($param['items']))
    $datos['numitems'] = 7;   // Valor por defecto
  else if ($param['items']<1 || !is_numeric($param['items']))
    $datos['numitems'] = 0;    // Para mostrar todos los items
  else
    $datos['numitems'] = $param['items'];

  if ($datos['numitems']==0)
    $datos['primero']=0;      // Ver todos los items
  else {
    $datos['primero'] = isset($param['primero']) ? $param['primero'] : 0;
    if ($datos['primero']<0 || !is_numeric($datos['primero']))
      $datos['primero']=0;
  }
  return $datos;
}

function checkRequestBusqueda($param) {
  $datos = [];
  $datos['accion'] = '';

  $aux = [];
  $aux['nombre'] = $param['nombre'] ?? null;
  if (isset($aux['nombre'])){
    setcookie("nombre", $aux['nombre'] );
    
  }
  if (isset($_COOKIE['nombre'])){
    $aux['nombre']=$_COOKIE['nombre'];
  }
  $aux['apellidos'] = $param['apellidos'] ?? null;

  

  if (isset($_COOKIE['apellidos'])){
    $aux['apellidos']=$_COOKIE['apellidos'];
  }

  $aux['dni'] = $param['dni'] ?? null;

  if (isset($_COOKIE['dni'])){
    $aux['dni']=$_COOKIE['dni'];
  }

  $aux['fechamin'] = (isset($param['fechamin']) && preg_match('/^((((19|[2-9]\d)\d{2})\-(0[13578]|1[02])\-(0[1-9]|[12]\d|3[01]))|(((19|[2-9]\d)\d{2})\-(0[13456789]|1[012])\-(0[1-9]|[12]\d|30))|(((19|[2-9]\d)\d{2})\-02\-(0[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))\-02\-29))$/', $param['fechamin'])) ? $param['fechamin'] : null;
  if (isset($_COOKIE['fechamin'])){
    $aux['fechamin']=$_COOKIE['fechamin'];
  }

  $aux['fechamax'] = (isset($param['fechamax']) && preg_match('/^((((19|[2-9]\d)\d{2})\-(0[13578]|1[02])\-(0[1-9]|[12]\d|3[01]))|(((19|[2-9]\d)\d{2})\-(0[13456789]|1[012])\-(0[1-9]|[12]\d|30))|(((19|[2-9]\d)\d{2})\-02\-(0[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))\-02\-29))$/', $param['fechamax'])) ? $param['fechamax'] : null;
  if (isset($_COOKIE['fechamax'])){
    $aux['fechamax']=$_COOKIE['fechamax'];
  }

  $aux['estado']=(isset($param['estado']) && preg_match('/Activo|Inactivo/', $param['estado'])) ? $param['estado'] : null;
  if (isset($_COOKIE['estado'])){
    $aux['estado']=$_COOKIE['estado'];
  }

  $aux['pendientes']=(isset($param['pendientes']) && preg_match('/VacPendientes/', $param['pendientes'])) ? $param['pendientes'] : null;
  if (isset($_COOKIE['pendientes'])){
    $aux['pendientes']=$_COOKIE['pendientes'];
  }

  $aux['numSemanas'] = $param['numSemanas'] ?? null;
  if (isset($_COOKIE['numSemanas'])){
    $aux['numSemanas']=$_COOKIE['numSemanas'];
  }

  $aux['ordenacion']=(isset($param['ordenacion']) && preg_match('/nombre|apellido|menoramayor|mayoramenor/', $param['ordenacion'])) ? $param['ordenacion'] : null;
  if (isset($_COOKIE['ordenacion'])){
    $aux['ordenacion']=$_COOKIE['ordenacion'];
  }

  if (isset($aux['nombre']) || isset($aux['apellidos']) || isset($aux['fechamin']) || isset($aux['fechamax']) || isset($aux['dni']) || isset($aux['estado']) || isset($aux['pendientes']) || isset($aux['numSemanas']) || isset($aux['ordenacion'])) {
    $datos['busqueda'] = $aux;
    $datos['accion'] = "Buscar";
  }
  else{
    $datos['busqueda'] = $aux;
    $datos['accion'] = "Buscar";
  }
  
  return $datos;
}

function checkRequest($param) {
  $datos = [];
  $datos['accion'] = '';

  $aux = [];
  $aux['nombre'] = (isset($param['nombre']) && $param['nombre']!='') ? $param['nombre'] : null;
  setcookie('nombre', $aux['nombre']);
  $_COOKIE['nombre']=$aux['nombre'];
  

    

  $aux['apellidos'] = (isset($param['apellidos']) && $param['apellidos']!='') ? $param['apellidos'] : null;
  if (isset($_COOKIE['apellidos'])){
    setcookie('apellidos', $aux['apellidos']);
    $_COOKIE['apellidos']=$aux['apellidos'];
  }
  else {
   setcookie('apellidos', $aux['apellidos']);
   $_COOKIE['apellidos']=$aux['apellidos'];
  }
    
    
    
  

  
  $aux['dni'] = (isset($param['dni']) && $param['dni']!='') ? $param['dni'] : null;
  setcookie('dni', $aux['dni']);
  $_COOKIE['dni']=$aux['dni'];

  $aux['fechamin'] = (isset($param['fechamin']) && $param['fechamin']!='' &&  preg_match('/^((((19|[2-9]\d)\d{2})\-(0[13578]|1[02])\-(0[1-9]|[12]\d|3[01]))|(((19|[2-9]\d)\d{2})\-(0[13456789]|1[012])\-(0[1-9]|[12]\d|30))|(((19|[2-9]\d)\d{2})\-02\-(0[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))\-02\-29))$/', $param['fechamin'])) ? $param['fechamin'] : null;
  setcookie('fechamin', $aux['fechamin']);
  $_COOKIE['fechamin']=$aux['fechamin'];
  
  $aux['fechamax'] = (isset($param['fechamax']) && $param['fechamax']!='' &&  preg_match('/^((((19|[2-9]\d)\d{2})\-(0[13578]|1[02])\-(0[1-9]|[12]\d|3[01]))|(((19|[2-9]\d)\d{2})\-(0[13456789]|1[012])\-(0[1-9]|[12]\d|30))|(((19|[2-9]\d)\d{2})\-02\-(0[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))\-02\-29))$/', $param['fechamax'])) ? $param['fechamax'] : null;
  setcookie('fechamax', $aux['fechamax']);
  $_COOKIE['fechamax']=$aux['fechamax'];

  $aux['estado']=(isset($param['estado']) &&  preg_match('/Activo|Inactivo/', $param['estado'])) ? $param['estado'] : null;
  setcookie('estado', $aux['estado']);
  $_COOKIE['estado']=$aux['estado'];

  $aux['pendientes']=(isset($param['pendientes']) && preg_match('/VacPendientes/', $param['pendientes'])) ? $param['pendientes'] : null;
  setcookie('pendientes', $aux['pendientes']);
  $_COOKIE['pendientes']=$aux['pendientes'];

  $aux['numSemanas'] = (isset($param['numSemanas']) && $param['numSemanas']!='') ? $param['numSemanas'] : null;
  setcookie('numSemanas', $aux['numSemanas']);
  $_COOKIE['numSemanas']=$aux['numSemanas'];

  $aux['ordenacion']=(isset($param['ordenacion']) && preg_match('/nombre|apellido|menoramayor|mayoramenor/', $param['ordenacion'])) ? $param['ordenacion'] : null;
  setcookie('ordenacion', $aux['ordenacion']);
  $_COOKIE['ordenacion']=$aux['ordenacion'];

  if (isset($aux['nombre']) || isset($aux['apellidos']) || isset($aux['fechamin']) || isset($aux['fechamax'])|| isset($aux['dni']) || isset($aux['estado']) || isset($aux['pendientes'])|| isset($aux['numSemanas']) || isset($aux['ordenacion']) ) {
    $datos['busqueda'] = $aux;
    $datos['accion']='Buscar';
    $datos['primero']=0;
    $datos['numitems']=7;
  } else {
    $datos['busqueda'] = $aux;
    $datos['accion']='Buscar';
    $datos['primero']=0;
    $datos['numitems']=7;
    /*$datos['info'] = [];
    $datos['info'][] = 'No ha indicado ningún campo de búsqueda';*/
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