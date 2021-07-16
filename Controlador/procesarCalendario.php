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
      $result[$error] = 'No ha indicado ningun valor';
      $result['enviado'] = 'false';
      $result[$atributo] = '';
    }
    
    return $result;
}

function getParamsSexo($post, $atributo) {
    $error=$atributo.'error';
    $result[$error]='';
    if (isset($post[$atributo])) { /* El formulario ha sido enviado */
    $result['enviado'] = 'true';
    
    if (!isset($post[$atributo]) or empty($post[$atributo]))
        $result[$error] = 'No ha indicado ningún valor';
    else if (!preg_match('/Masculino|Femenino|Todos/', $post[$atributo])){
      $result[$error] = 'El valor debe ser uno de los indicados';
      $result[$atributo]='';
    }
        
    else{
      $result[$atributo] = strip_tags($post[$atributo]);
    }
    
    } else {  /* El formulario aun no ha sido enviado */
    $result[$error] = 'false';
    $result[$atributo] = '';
    }
    
    return $result;
    }

function getParamsMeses($post, $atri1, $atri2) {
    $error1=$atri1.'error';
    $error2=$atri2.'error';
    $result[$error1]='';
    $result[$error2]='';
    if (isset($post[$atri1]) && isset($post[$atri2])) { /* El formulario ha sido enviado */
    $result['enviado'] = 'true';
    
    if ((!isset($post[$atri1])) or (!isset($post[$atri2]))){
      $result[$atri1] ='';
      $result[$atri2]='';
      $result[$error1] = 'No ha indicado ningún valor para alguno de los meses';
      
    }

    else if (  ((intval($post[$atri1]))==0) &&  ($post[$atri1]!="0")){
        $result[$error1] = 'Valores de los meses incorrectos ';
        $result[$atri1] ='';
        $result[$atri2]='';

      }
    else if (  ((intval($post[$atri2]))==0) &&  ($post[$atri2]!="0")){
        $result[$error1] = 'Valores de los meses incorrectos';
        $result[$atri1] ='';
        $result[$atri2]='';
    }

    
        
    else if (  (! ((intval($post[$atri1]))<=(intval($post[$atri2])))) && ((intval($post[$atri2]))!=0)){
      $result[$error1] = 'El número de meses de inicio debe de ser menor que el de fin, o el de fin ser igual a 0';
      $result[$atri1] ='';
      $result[$atri2]='';
    }
        
    else{

      $result[$atri1] = strip_tags($post[$atri1]);
      $result[$atri2]= strip_tags($post[$atri2]);
    }

    } else {  /* El formulario aun no ha sido enviado */
    $result['enviado'] = 'false';
    $result[$atri1] = '';
    $result[$atri2] = '';
    }
    
    return $result;
  }

  function getParamsTipo($post, $atributo) {
    $error=$atributo.'error';
    $result[$error]='';
    if (isset($post[$atributo])) { /* El formulario ha sido enviado */
    $result['enviado'] = 'true';
    
    if (!isset($post[$atributo]) or empty($post[$atributo]))
        $result[$error] = 'No ha indicado ningún valor';
    else if (!preg_match('/Susceptible|Sistemica/', $post[$atributo])){
      $result[$error] = 'El valor debe ser uno de los indicados';
      $result[$atributo]='';
    }
        
    else{
      $result[$atributo] = strip_tags($post[$atributo]);
    }
    
    } else {  /* El formulario aun no ha sido enviado */
    $result['enviado'] = 'false';
    $result[$atributo] = '';
    }
    
    return $result;
    }

$atributo1="acronimo";
$params1 = getParams($_POST, $atributo1);
$atributo2="sexo";
$params2 = getParams($_POST, $atributo2);
$atributo3="meses_ini";
$atributo4="meses_fin";
$params3 = getParamsMeses($_POST, $atributo3, $atributo4);
$atributo5="tipo";
$params4 = getParams($_POST, $atributo5);
$atributo6="comentarios";
$params5 = getParams($_POST, $atributo6);

$atributos= array($atributo1, $atributo2, $atributo3, $atributo4, $atributo5, $atributo6);

$params = array_merge($params1, $params2, $params3, $params4, $params5);





if (isset($_POST['accion'])){
    if ($_POST['accion']=='Validar'){
        $db=DB_connection();
        $r=insertarCalendarioBD($params, $atributos, $db);
        

        if ($r=="true"){
          mensajeCalendarioInsertado($params, $atributos);  
          $mensaje="El usuario ".$_SESSION['usuario']." ha INSERTADO en el CALENDARIO la vacuna con acrónimo ".$params[$atributos[0]]." en el sistema con éxito";
          insertLog($mensaje, $db);
        }
               
        else{
          mensajeCalendarioNoInsertado($r);
          $mensaje="El usuario ".$_SESSION['usuario']." NO ha podido INSERTAR en el CALENDARIO la vacuna con acrónimo ".$params[$atributos[0]]." en el sistema con éxito";
          insertLog($mensaje, $db);
        }
            
        
            
        
        DB_disconnection($db);
    }
}


if (isset($_POST['accion'])){
    if ($_POST['accion']=='Ver' || $_POST['accion']=='Borrar' || $_POST['accion']=='Editar' ){
      $acronimo=$_POST['acronimo'];
      $db=DB_connection();
      $r=recuperarDatosCalendario($acronimo, $db);

      $params[$atributos[0]]=$r[0]['acronimo'];
      if ($r[0]['sexo']=='M')
        $sexo="Masculino";
      else if ($r[0]['sexo']=='F')
        $sexo="Femenino";
      else 
        $sexo="Todos";
     
      $params[$atributos[1]]=$sexo;
      

      
      $params[$atributos[2]]=strval($r[0]['meses_ini']);
      $params[$atributos[3]]=strval($r[0]['meses_fin']);
      
      if ($r[0]['tipo']=='I')
        $tipo="Sistemica";
      else 
        $tipo="Susceptible";
   
      $params[$atributos[4]]=$tipo;


      $params[$atributos[5]]=$r[0]['comentarios'];

      $params['error']='';

      for ($i = 0; $i <= 5; $i++) {
        $error=$atributos[$i].'error';
        $params[$error]='';
      }
      
      DB_disconnection($db);
      //Esto es ya que al ser todos los datos modificables identificar cúal es el usuario que estoy modificando
      if (($_POST['accion']=='Editar') )
        $_SESSION['acronimo_mod']=$params[$atributos[0]];

    }
  }


  if (isset($_POST['accion'])){
    if ($_POST['accion']=='Confirmar Borrado'){
      $db=DB_connection();
      $acronimo=$_POST['acronimo'];
      
      $r=eliminarCalendario($acronimo, $db);
      if ($r=="true"){
        mensajeCalendarioBorrado($acronimo);
        $mensaje="El usuario ".$_SESSION['usuario']." ha BORRADO del CALENDARIO la vacuna con acrónimo ".$params[$atributos[0]]." en el sistema con éxito";
        insertLog($mensaje, $db);
      }
        
      else {
        mensajeCalendarioNoBorrado($r);
        $mensaje="El usuario ".$_SESSION['usuario']." NO ha podido BORRAR del CALENDARIO la vacuna con acrónimo ".$params[$atributos[0]]." en el sistema con éxito";
        insertLog($mensaje, $db);
      }
        
      DB_disconnection($db);
      for ($i = 0; $i <= 5; $i++) {
        $error=$atributos[$i].'error';
        $params[$error]='';
      }
    }
    
  }


  if (isset($_POST['accion'])){
    if ($_POST['accion']=='Validar datos si son correctos'){
        $db=DB_connection();
        $r=editarCalendario($params, $atributos, $db);
        
        if ($r=="true"){
          mensajeCalendarioEditado($params, $atributos);
          $mensaje="El usuario ".$_SESSION['usuario']." ha EDITADO el CALENDARIO de la vacuna con acrónimo ".$params[$atributos[0]]." en el sistema con éxito";
          insertLog($mensaje, $db);
        }
            
        else{
          mensajeCalendarioNoEditado($r);
          $mensaje="El usuario ".$_SESSION['usuario']." NO ha podido EDITAR el CALENDARIO de la vacuna con acrónimo ".$params[$atributos[0]]." en el sistema con éxito";
          insertLog($mensaje, $db);
        }
        DB_disconnection($db);
    }
}

$error='false';


for ($i = 0; $i <= 5; $i++) {
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
  showCalendario($params, $atributos, $a);

if (isset($_POST['accion'])){
  if (($_POST['accion']!='Validar') && ($_POST['accion']!='Confirmar Borrado') &&  ($_POST['accion']!='Validar datos si son correctos') )
    showCalendario($params, $atributos, $a);
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
