<?php
require_once('../Modelo/funcionesBD.php');
require_once('../Modelo/db.php');
require_once('../Vista/VistasHTML.php');
HTMLinicio("ProyectoTW");
HTMLheader();
HTMLnav(0);
$_SESSION['imagen']='';
function getParams($post, $atributo) {
    $error=$atributo.'error';
    $result[$error]='';
    if (isset($post[$atributo])) { /* El formulario ha sido enviado */
    $result['enviado'] = 'true';
    
    /* Comprobar valor de Celsius */
    
      
    if (!isset($post[$atributo]) or empty($post[$atributo])){
      $result[$error] = 'No ha indicado ningún valor';
      $result[$atributo] = '';
    }
        
    else if (!is_string($post[$atributo])){
      $result[$error] = 'El valor debe ser un string';
      $result[$atributo] = '';
    }
        
    $result[$atributo] = strip_tags($post[$atributo]);
    $result[$atributo] =addslashes ($result[$atributo] ); /*Para las consultas SQL*/
    
    } else {  /* El formulario aun no ha sido enviado */
    $result['enviado'] = 'false';
    $result[$atributo] = '';
    }
    
    return $result;
}



$atributo1="acronimo";
$params1 = getParams($_POST, $atributo1);
$atributo2="nombre";
$params2 = getParams($_POST, $atributo2);
$atributo3="descripcion";
$params3 = getParams($_POST, $atributo3);

$atributos= array($atributo1, $atributo2, $atributo3);

$params = array_merge($params1, $params2, $params3);





if (isset($_POST['accion'])){
    if ($_POST['accion']=='Validar'){
        $db=DB_connection();
        $r=insertarVacunaBD($params, $atributos, $db);
        
        

        if ($r){
          mensajeVacunaInsertada($params, $atributos);
          $mensaje="El usuario ".$_SESSION['usuario']." ha INSERTADO la VACUNA con acrónimo ".$params[$atributos[0]]." en el sistema con éxito";
          insertLog($mensaje, $db);
        }
                 
        else{
          mensajeVacunaNoInsertada($r);
          $mensaje="El usuario ".$_SESSION['usuario']." NO ha podido insertar la VACUNA con acrónimo ".$params[$atributos[0]]." en el sistema con éxito";
          insertLog($mensaje, $db);
        }
            

        
            
        
        DB_disconnection($db);
    }
}

if (isset($_POST['accion'])){
    if ($_POST['accion']=='Ver' || $_POST['accion']=='Borrar' || $_POST['accion']=='Editar' ){
      $acronimo=$_POST['acronimo'];
      $db=DB_connection();
      $r=recuperarDatosVacuna($acronimo, $db);

      $params[$atributos[0]]=$r[0]['acronimo'];
      $params[$atributos[1]]=$r[0]['nombre'];
      $params[$atributos[2]]=$r[0]['descripcion'];

      for ($i = 0; $i <= 2; $i++) {
        $error=$atributos[$i].'error';
        $params[$error]='';
      }
      
      DB_disconnection($db);
      //Esto es ya que al ser todos los datos modificables identificar cúal es el usuario que estoy modificando
      if (($_POST['accion']=='Editar') )
        $_SESSION['vacuna_mod']=$params[$atributos[0]];

    }
}

  if (isset($_POST['accion'])){
    if ($_POST['accion']=='Confirmar Borrado'){
      $db=DB_connection();
      $acronimo=$_POST['acronimo'];
      $nombre=$_POST['nombre'];
      $r=eliminarVacuna($acronimo, $db);
      if ($r=="true"){
        mensajeVacunaBorrada($acronimo, $nombre);
        $mensaje="El usuario ".$_SESSION['usuario']." ha BORRADO la VACUNA con acrónimo ".$params[$atributos[0]]." en el sistema con éxito";
        insertLog($mensaje, $db);
      }
        
      else {
        mensajeVacunaNoBorrada($acronimo, $nombre);
        $mensaje="El usuario ".$_SESSION['usuario']." NO ha podido BORRAR la VACUNA con acrónimo ".$params[$atributos[0]]." en el sistema con éxito";
        insertLog($mensaje, $db);
      }
        
      DB_disconnection($db);
      for ($i = 0; $i <= 2; $i++) {
        $error=$atributos[$i].'error';
        $params[$error]='';
      }
    }
    
  }


if (isset($_POST['accion'])){
    if ($_POST['accion']=='Validar datos si son correctos'){
        $db=DB_connection();
        $r=editarVacuna($params, $atributos, $db);
       

        if ($r=="true"){
          mensajeVacunaEditada($params, $atributos);
          $mensaje="El usuario ".$_SESSION['usuario']." ha EDITADO la VACUNA con acrónimo ".$params[$atributos[0]]." en el sistema con éxito";
          insertLog($mensaje, $db);
        }
        
        else{
          mensajeVacunaNoEditada($r);
          $mensaje="El usuario ".$_SESSION['usuario']." NO ha podido EDITAR la VACUNA con acrónimo ".$params[$atributos[0]]." en el sistema con éxito";
          insertLog($mensaje, $db);
        }
        DB_disconnection($db);
    }
}

$error='false';


for ($i = 0; $i <= 2; $i++) {
  $error_p=$atributos[$i].'error';
  if ($params[$error_p]!='' || $params['enviado']=='false'){
    $error='true';
  }
}

if (isset($_POST['accion']))
  if ($_POST['accion']=='Ver' || $_POST['accion']=='Borrar' || $_POST['accion']=='Editar' )
    $error='false';



  if ($error=='false'){
    if (isset($_POST['accion'])){
      if ($_POST['accion']=='Editar')
        $a='';
      else 
        $a="readonly"; 
    }
    else
        $a='';
      
  }
    
  else{
    $a='';
  }
    


  
if (!isset($_POST['accion']))
  showVacunas($params, $atributos, $a);

if (isset($_POST['accion'])){
  if (($_POST['accion']!='Validar') && ($_POST['accion']!='Confirmar Borrado') &&  ($_POST['accion']!='Validar datos si son correctos') )
    showVacunas($params, $atributos, $a);
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