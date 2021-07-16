<?php
require_once('../Modelo/funcionesBD.php');
require_once('../Modelo/db.php');
require_once('../Vista/VistasHTML.php');
HTMLinicio("ProyectoTW");
HTMLheader();
HTMLnav(0);
if (!isset($_SESSION['rol']))
    $_SESSION['rol']='';

if (!isset($_POST['accion']))
  $_SESSION['imagen']='';

$_SESSION['pagina']='procesar';
function getParamsImage($post,$atributo) {
  $error=$atributo.'error';
  $result[$error]='';
    if (isset($_SESSION['imagen'])){

      if ( ($_SESSION['imagen'])=='' ){

      
        if  ((sizeof ($_FILES)==0) || !array_key_exists ('fichero', $_FILES )) {
          $result[$atributo] = '';
          $result[$error] = 'No se ha subido ningún fichero';
          
        }else if ( !is_uploaded_file ($_FILES['fichero']['tmp_name']  ) ){
          $result[$atributo] = '';
          $result[$error] = 'Fichero no subido';
        }
        else{
          
          
          $_SESSION['imagen']= file_get_contents($_FILES['fichero']['tmp_name']);
          
          
        }
    }
    else {

      if  ((sizeof ($_FILES)==0) || !array_key_exists ('fichero', $_FILES )) {
        $result[$atributo] = '';
        
        
      }else if ( !is_uploaded_file ($_FILES['fichero']['tmp_name']  ) ){
        $result[$atributo] = '';
        
      }
      else{
        $_SESSION['imagen']= file_get_contents($_FILES['fichero']['tmp_name']);
        
        
      }
    }
  }
  else {
    if ( ((sizeof ($_FILES)==0) || !array_key_exists ('fichero', $_FILES )) /*&&  ($_SESSION['imagen']=='')*/){
      $result[$atributo] = '';
      $result[$error] = 'No se ha subido ningún fichero';
      
    }else if (( !is_uploaded_file ($_FILES['fichero']['tmp_name']  ) )/* &&  ($_SESSION['imagen']=='')*/){
      $result[$atributo] = '';
      $result[$error] = 'Fichero no subido';
    }
    else{
      $result[$atributo]=$_FILES['fichero']['name'];
      $_SESSION['imagen']=$_FILES['fichero']['name'];
    }


  }
  
  return $result;
}


function getParams($post, $atributo) {
$error=$atributo.'error';

$result[$error] = '';

  
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

function getParamsDNI($post, $atributo) {
  $error=$atributo.'error';
  $result[$error]='';
  if (isset($post[$atributo])) { /* El formulario ha sido enviado */
  $result['enviado'] = 'true';
  
  /* Comprobar valor de Celsius */
  if (!isset($post[$atributo]) or empty($post[$atributo])){
    $result[$error] = 'No ha indicado ningún valor';
    $result[$atributo] = '';
  }
      
  else if  (!preg_match('/^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKEtrwagmyfpdxbnjzsqvhlcke]$/', $post[$atributo])){
    $result[$error] = 'El DNI no es correto, el formato es NNNNNNNNL donde {(N,L)=(Numero, Letra)}';
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

  function getParamsEmail($post, $atributo) {
    $error=$atributo.'error';
    $result[$error]='';
    if (isset($post[$atributo])) { /* El formulario ha sido enviado */
    $result['enviado'] = 'true';
    

    if (!isset($post[$atributo]) or empty($post[$atributo])){
      $result[$error] = 'No ha indicado ningún valor';
      $result[$atributo]='';
    }
        
    else if (!preg_match('/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,4})$/', $post[$atributo])){
        $result[$error] = 'El email introducido no es correcto porque no cumple los requisitos de formato, tiene que que contener como mínimo: nombre@organizacion.tipo';
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


  function getParamsTelefono($post, $atributo) {
    $error=$atributo.'error';
    $result[$error]='';
    if (isset($post[$atributo])) { /* El formulario ha sido enviado */
    $result['enviado'] = 'true';
    
    if (!isset($post[$atributo]) or empty($post[$atributo])){
      $result[$error] = 'No ha indicado ningún valor';
      $result[$atributo]='';
    }
        
    else if (!preg_match('/^(\(\+[0-9]{2}\))?\s*[0-9]{3}\s*[0-9]{6}$/', $post[$atributo])){
      $result[$error] = 'El teléfono indicado no es correcto: Ejemplos válidos: "+34 636767676" "636767676" "636  767676" ';
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


  function getParamsFecha($post, $atributo) {
    $error=$atributo.'error';
    $result[$error]='';
    if (isset($post[$atributo])) { /* El formulario ha sido enviado */
    $result['enviado'] = 'true';
    
    if (!isset($post[$atributo]) or empty($post[$atributo])){
      $result[$error] = 'No ha indicado ningún valor';
      $result[$atributo]='';
    }
        
    else if (!preg_match('/^((((19|[2-9]\d)\d{2})\-(0[13578]|1[02])\-(0[1-9]|[12]\d|3[01]))|(((19|[2-9]\d)\d{2})\-(0[13456789]|1[012])\-(0[1-9]|[12]\d|30))|(((19|[2-9]\d)\d{2})\-02\-(0[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))\-02\-29))$/', $post[$atributo])){
      $result[$error] = 'El formato de la fecha no es el correcto: YYYY-MM-DD donde {(Y,M,D)=(Cifra_Año, Cifra_mes, Cifra_dia)}';
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




function getParamsSexo($post, $atributo) {
  $error=$atributo.'error';
  $result[$error]='';
  if (isset($post[$atributo])) { /* El formulario ha sido enviado */
  $result['enviado'] = 'true';

  if (!isset($post[$atributo]) or empty($post[$atributo]))
      $result[$error] = 'No ha indicado ningún valor';
  else if (!preg_match('/Masculino|Femenino/', $post[$atributo])){
    $result[$error] = 'El valor debe ser uno de los indicados';
    $result[$atributo]='';
  }
      
  else{
    $result[$atributo] = strip_tags($post[$atributo]);
  }
  
  } else {  /* El formulario aun no ha sido enviado */
  $result['enviado'] = 'false';
  $result[$error] = 'No ha indicado ningún valor';
  $result[$atributo] = '';
  }
  
  return $result;
}

function getParamsContrasenias($post, $atri1, $atri2) {
  $error1=$atri1.'error';
  $error2=$atri2.'error';
  $result[$error1]='';
  $result[$error2]='';
    if (isset($post[$atri1]) && isset($post[$atri2])) { /* El formulario ha sido enviado */
    $result['enviado'] = 'true';
    
    if ((!isset($post[$atri1]) or empty($post[$atri1])) or (!isset($post[$atri2]) or empty($post[$atri2]) )){
      $result[$atri1] ='';
      $result[$atri2]='';
      $result[$error1] = 'No ha indicado ningún valor';
      $result[$error2] = 'No ha indicado ningún valor';
    }
        
    else if (!($post[$atri1]==$post[$atri2])){
      $result[$error1] = 'No coinciden las contrasenias insertadas';
      $result[$atri1] ='';
      $result[$atri2]='';
    }
        
    else{

      $result[$atri1] = strip_tags($post[$atri1]);
      $result[$atri2]= strip_tags($post[$atri2]);
    }
    
    } else {  /* El formulario aun no ha sido enviado */
      $enviado='enviado';
    $result[$enviado] = 'false';
    $result[$atri1] = '';
    $result[$atri2] = '';
    }
    
    return $result;
  }


function getParamsRol($post, $atributo) {
  $error=$atributo.'error';
  $result[$error]='';
  if (isset($post[$atributo])) { /* El formulario ha sido enviado */
  $result['enviado'] = 'true';
  
  if (!isset($post[$atributo]) or empty($post[$atributo]))
      $result[$error] = 'No ha indicado ningún valor';
  else if (!preg_match('/Administrador|Paciente|Sanitario/', $post[$atributo])){
    $result[$error] = 'El valor debe ser un string';
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

  function getParamsEstado($post, $atributo) {
    $error=$atributo.'error';
    $result[$error]='';
    if (isset($post[$atributo])) { /* El formulario ha sido enviado */
    $result['enviado'] = 'true';
    
    if (!isset($post[$atributo]) or empty($post[$atributo]))
        $result[$error] = 'No ha indicado ningún valor';
    else if (!preg_match('/Activo|Inactivo/', $post[$atributo])){
      $result[$error] = 'El valor debe ser un string';
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


if (isset($_SESSION['rol'])){
  if ($_SESSION['rol']=='A'){
      $atributo1="nombre";
      $params1 = getParams($_POST, $atributo1);
      $atributo2="apellidos";
      $params2 = getParams($_POST, $atributo2);
      $atributo3="dni";
      $params3 = getParamsDNI($_POST, $atributo3);
      $atributo4="email";
      $params4 = getParamsEmail($_POST, $atributo4);
      $atributo5="telefono";
      $params5 = getParamsTelefono($_POST, $atributo5);
      $atributo6="fecha_nac";
      $params6 = getParamsFecha($_POST, $atributo6);
      $atributo7="sexo";
      $params7 = getParamsSexo($_POST, $atributo7);
      $atributo8="contrasenia1";
      $atributo9="contrasenia2";
      $params8=getParamsContrasenias($_POST, $atributo8, $atributo9);
      $atributo10="rol";
      $params9 = getParamsRol($_POST, $atributo10);
      $atributo11="estado";
      $params10 = getParamsEstado($_POST, $atributo11);
      $atributo12="fichero";
      $params11 = getParamsImage($_POST, $atributo12);

    

          


      $atributos= array($atributo1, $atributo2, $atributo3, $atributo4, $atributo5, $atributo6, $atributo7, $atributo8, $atributo9, $atributo10, $atributo11, $atributo12);

      $params = array_merge($params1, $params2, $params3, $params4, $params5, $params6, $params7, $params8, $params9, $params10, $params11);
     

  }
  else{

  
      $atributo1="nombre";
      $params1 = getParams($_POST, $atributo1);
      $atributo2="apellidos";
      $params2 = getParams($_POST, $atributo2);
      $atributo3="dni";
      $params3 = getParamsDNI($_POST, $atributo3);
      $atributo4="email";
      $params4 = getParamsEmail($_POST, $atributo4);
      $atributo5="telefono";
      $params5 = getParamsTelefono($_POST, $atributo5);
      $atributo6="fecha_nac";
      $params6 = getParamsFecha($_POST, $atributo6);
      $atributo7="sexo";
      $params7 = getParamsSexo($_POST, $atributo7);
      $atributo8="contrasenia1";
      $atributo9="contrasenia2";
      $params8=getParamsContrasenias($_POST, $atributo8, $atributo9);

      



      /*$atributo10="rol";
      $params9='P';
      $atributo11="estado";
      $params10='I';*/
      $atributo12="fichero";
      $params11 = getParamsImage($_POST, $atributo12);

          


      $atributos= array($atributo1, $atributo2, $atributo3, $atributo4, $atributo5, $atributo6, $atributo7, $atributo8, $atributo9,  $atributo12);

      $params = array_merge($params1, $params2, $params3, $params4, $params5, $params6, $params7, $params8,  $params11);


  }

      
}

else {
       
    $atributo1="nombre";
    $params1 = getParams($_POST, $atributo1);
    $atributo2="apellidos";
    $params2 = getParams($_POST, $atributo2);
    $atributo3="dni";
    $params3 = getParamsDNI($_POST, $atributo3);
    $atributo4="email";
    $params4 = getParamsEmail($_POST, $atributo4);
    $atributo5="telefono";
    $params5 = getParamsTelefono($_POST, $atributo5);
    $atributo6="fecha_nac";
    $params6 = getParamsFecha($_POST, $atributo6);
    $atributo7="sexo";
    $params7 = getParamsSexo($_POST, $atributo7);
    $atributo8="contrasenia1";
    $atributo9="contrasenia2";
    $params8=getParamsContrasenias($_POST, $atributo8, $atributo9);
    $atributo12="fichero";
    $params11 = getParamsImage($_POST, $atributo12);


        


    $atributos= array($atributo1, $atributo2, $atributo3, $atributo4, $atributo5, $atributo6, $atributo7, $atributo8, $atributo9, $atributo12);

    $params = array_merge($params1, $params2, $params3, $params4, $params5, $params6, $params7, $params8,  $params11);

    
  }
  
$atributo1="nombre";
$atributo2="apellidos";
$atributo3="dni";
$atributo4="email";
$atributo5="telefono";
$atributo6="fecha_nac";
$atributo7="sexo";
$atributo8="contrasenia1";
$atributo9="contrasenia2";
$atributo10="rol";
$atributo11="estado";
$atributo12="fichero";
$atributos= array($atributo1, $atributo2, $atributo3, $atributo4, $atributo5, $atributo6, $atributo7, $atributo8, $atributo9, $atributo10, $atributo11, $atributo12);
if (isset($_POST['accion'])){
    if (($_POST['accion']=='Validar' || $_POST['accion']=='Enviar' || $_POST['accion']=='Modificar' || $_POST['accion']=='Validar datos si son correctos') && (isset($_SESSION['rol']) && ($_SESSION['rol']=='A'))){

        
        $params1 = getParams($_POST, $atributo1);
        
        $params2 = getParams($_POST, $atributo2);
        
        $params3 = getParamsDNI($_POST, $atributo3);
        
        $params4 = getParamsEmail($_POST, $atributo4);
        
        $params5 = getParamsTelefono($_POST, $atributo5);
        
        $params6 = getParamsFecha($_POST, $atributo6);
        
        $params7 = getParamsSexo($_POST, $atributo7);
        
        $params8=getParamsContrasenias($_POST, $atributo8, $atributo9);
        
        $params9 = getParamsRol($_POST, $atributo10);
        
        $params10 = getParamsEstado($_POST, $atributo11);
        
        $params11 = getParamsImage($_POST, $atributo12);


          


        

        $params = array_merge($params1, $params2, $params3, $params4, $params5, $params6, $params7, $params8, $params9, $params10, $params11);


    }
  }

  if (isset($_POST['accion'])){
    if (($_POST['accion']=='Modificar' || $_POST['accion']=='Validar datos si son correctos') &&  (isset($_SESSION['rol']) && ($_SESSION['rol']=='P' || $_SESSION['rol']=='' ))){
        $params1 = getParams($_POST, $atributo1);
        
        $params2 = getParams($_POST, $atributo2);
        
        $params3 = getParamsDNI($_POST, $atributo3);
        
        $params4 = getParamsEmail($_POST, $atributo4);
        
        $params5 = getParamsTelefono($_POST, $atributo5);
        
        $params6 = getParamsFecha($_POST, $atributo6);
        
        $params7 = getParamsSexo($_POST, $atributo7);
        
        $params8=getParamsContrasenias($_POST, $atributo8, $atributo9);

        $params9=getParamsImage($_POST, $atributo12);
    

        $params = array_merge($params1, $params2, $params3, $params4, $params5, $params6, $params7, $params8, $params9);
    }
  }
  if (isset($_POST['accion'])){
    if ($_POST['accion']=='Ver' || $_POST['accion']=='Borrar' || $_POST['accion']=='Editar' || $_POST['accion']=='Activar'){
      $email=$_POST['Email'];
      $db=DB_connection();
      $r=recuperarDatos($email, $db);

      $params[$atributos[0]]=$r[0]['nombre'];
      $params[$atributos[1]]=$r[0]['apellidos'];
      $params[$atributos[2]]=$r[0]['dni'];
      $params[$atributos[3]]=$r[0]['email'];
      $params[$atributos[4]]=$r[0]['telefono'];
      $params[$atributos[5]]=$r[0]['fnac'];
      if ($r[0]['sexo']=='M')
        $sexo="Masculino";
      else
        $sexo="Femenino";

      $params[$atributos[6]]=$sexo;
      $params[$atributos[7]]='';
      $params[$atributos[8]]='';
      if ($r[0]['rol']=='S')
        $rol="Sanitario";
      else if ($r[0]['rol']=='P')
        $rol="Paciente";
      else 
        $rol="Administrador";
      
      $params[$atributos[9]]=$rol;
      if ($r[0]['estado']=='I')
        $estado="Inactivo";
      else
        $estado="Activo";
      $params[$atributos[10]]=$estado;
      $_SESSION['imagen']=base64_decode($r[0]['Fotografia']);
      $params[$atributos[11]]=$r[0]['Fotografia'];

      
    for ($i = 0; $i <= 11; $i++) {
      $error=$atributos[$i].'error';
      $params[$error]='';
    }
      /*$params['error']='';*/
      
      DB_disconnection($db);
      //Esto es ya que al ser todos los datos modificables identificar cúal es el usuario que estoy modificando
      if (($_POST['accion']=='Editar') )
      $_SESSION['correo_mod']=$params[$atributos[3]];

    }
  }

  if (isset($_POST['accion'])){
    if ($_POST['accion']=='Confirmar Borrado'){
      $db=DB_connection();
      $email=$_POST['email'];
      $nombre=$_POST['nombre'];
      $apellidos=$_POST['apellidos'];
      $r=eliminarUsuario($email, $db);
      if ($r=="true"){
        mensajeUsuarioBorrado($nombre, $apellidos);
        $mensaje="El usuario con email ".$_SESSION['usuario']." ha BORRADO al usuario ".$email." del sistema con éxito";
        insertLog($mensaje, $db);
      }
        
      else {
        mensajeUsuarioNoBorrado($nombre, $apellidos);
        $mensaje="El usuario con email ".$_SESSION['usuario']." NO ha podido BORRAR al usuario ".$email." del sistema con éxito";
        insertLog($mensaje, $db);
      }
        
      DB_disconnection($db);
      $params['error']='';
    }
    
  }


  if (isset($_POST['accion'])){
    if ($_POST['accion']=='Validar datos si son correctos'){
      $db=DB_connection();
      if ((isset($_SESSION['rol']) && ($_SESSION['rol']=='P' || $_SESSION['rol']=='S'))){
        $r=editarUsuarioCBD($params, $atributos, $db);
      }
        
      if ((isset($_SESSION['rol']) && ($_SESSION['rol']=='A')))
        $r=editarUsuarioABD($params, $atributos, $db);
      
  
      if ($r=="true"){
        if ($_SESSION['usuario']==$_SESSION['correo_mod'])
          $_SESSION['usuario']=$params[$atributos[3]];

        mensajeUsuarioEditado($params, $atributos);
        $mensaje="El usuario con email ".$_SESSION['usuario']." ha EDITADO el perfil del usuario ".$params[$atributos[3]]." del sistema con éxito";
        insertLog($mensaje, $db);
      }
        
      else{

        mensajeUsuarioNoEditado($r);
        $mensaje="El usuario con email ".$_SESSION['usuario']." NO ha podido EDITAR el perfil del usuario ".$params[$atributos[3]]." del sistema con éxito";
        insertLog($mensaje, $db);
      }
        
      
      DB_disconnection($db);
    }
  }

  if (isset($_POST['accion'])){
    if ($_POST['accion']=='Informar de error'){
      $db=DB_connection();
      $email=$_POST['email'];
      $r=eliminarUsuario($email, $db);
      mensajeUsuarioInformarError($email);
      $mensaje="El usuario con email ".$email." no ha sido activado por el usuario ".$_SESSION['usuario']." en el sistema y se le ha notificado de un error";
      insertLog($mensaje, $db);
      DB_disconnection($db); 
    }
  }
  
  if (isset($_POST['accion'])){
    if ($_POST['accion']=='Activar e Informar'){
      $db=DB_connection();
      $email=$_POST['email'];
      $r=activarBD($email, $db);
  
      
  
      if ($r=="true"){
        
        mensajeUsuarioActivado($params, $atributos); 
        $mensaje="El usuario con email ".$_SESSION['usuario']." ha ACTIVADO al usuario ".$params[$atributos[3]]." en el sistema con éxito";
        insertLog($mensaje, $db);
      }
        
      else{

        mensajeUsuarioNoActivado($r);
        $mensaje="El usuario con email ".$_SESSION['usuario']."NO ha ACTIVADO al usuario ".$params[$atributos[3]]." en el sistema con éxito";
        insertLog($mensaje, $db);
      }
        
      
      DB_disconnection($db);  
    }
  }


  if (isset($_POST['accion'])){
    if ($_POST['accion']=='Validar'){
      $db=DB_connection();
      $r=insertarBD($params, $atributos, $db);
  
      
      
      if ($r=="true"){
        if ($_SESSION['rol']!=''){
          mensajeUsuarioInsertado($params, $atributos);
          $mensaje="El usuario con email ".$_SESSION['usuario']." ha INSERTADO al usuario ".$params[$atributos[3]]." en el sistema con éxito";
          insertLog($mensaje, $db);
        }
          
        else {
          mensajeUsuarioSolicitar($params, $atributos);
          $mensaje="Un visitante ha SOLICITADO ser miembro del sistema con este email usuario con email ".$params[$atributos[3]]." con éxito";
          insertLog($mensaje, $db);
        }
          
      }
      else{
        if ($_SESSION['rol']!=''){
          $mensaje="El usuario con email ".$_SESSION['usuario']." NO ha podido INSERTAR al usuario ".$params[$atributos[3]]." en el sistema con éxito";
          insertLog($mensaje, $db);
          mensajeUsuarioNoInsertado($r);
        }
          
        else {
          
          $mensaje="Un visitante ha SOLICITADO SIN EXITO ser miembro del sistema con este email usuario con email ".$params[$atributos[3]]." ";
          mensajeUsuarioNoSolicitar($r);
          insertLog($mensaje, $db);
        }
          
      }
      DB_disconnection($db);
    }
  }



$error='false';

if (isset($_SESSION['rol'])){
  if ($_SESSION['rol']=='' ){
    for ($i = 0; $i <= 11; $i++) {
      if ($i!=9 && $i!=10){
        $error_p=$atributos[$i].'error';
        if ($params[$error_p]!='' || $params['enviado']=='false'){
          $error='true';
        }
      }
      
    }
  
  }

  if ($_SESSION['rol']!='A'  ){
    for ($i = 0; $i <= 11; $i++) {
      if ($i!=9 && $i!=10){
        $error_p=$atributos[$i].'error';
        if ($params[$error_p]!='' || $params['enviado']=='false'){
          
          $error='true';
        }
      }
      
    }
  
  }else{
    for ($i = 0; $i <= 11; $i++) {
      $error_p=$atributos[$i].'error';
      if ($params[$error_p]!='' || $params['enviado']=='false'){
        $error='true';
      }
    }
  }
  
}else{
  for ($i = 0; $i <= 11; $i++) {
    if ($i!=9 && $i!=10){
      $error_p=$atributos[$i].'error';
      if ($params[$error_p]!='' || $params['enviado']=='false'){
        $error='true';
      }
    }
    
  }
}

if (isset($_POST['accion'])){
  if ($_POST['accion']=='Ver' || $_POST['accion']=='Editar' || $_POST['accion']=='Borrar' /*|| $_POST['accion']=='Enviar'*/ || $_POST['accion']=='Activar')
  $error='false';
}
  


if ($error=='false'){
  
  if (isset($_POST['accion'])){
    if ($_POST['accion']=='Editar' && (isset($_SESSION['rol']) && (($_SESSION['rol']=='P')|| ($_SESSION['rol']=='S'))))
      $a="readeditar";
    else if ($_POST['accion']=='Editar' && (isset($_SESSION['rol']) && ($_SESSION['rol']=='A')))
      $a='';
    else 
      $a="readonly"; 
  }
    
}
  
else{
  
  if (isset($_POST['accion'])){
    if ($_POST['accion']=='Modificar'  && (isset($_SESSION['rol']) && ($_SESSION['rol']=='P')))
      $a="readeditar";
    else 
      $a= '';
  }
  else 
    $a= '';
}
  







if (!isset($_POST['accion']))
  showForm($params, $atributos, $a);

if (isset($_POST['accion'])){
  if (($_POST['accion']!='Validar') && ($_POST['accion']!='Confirmar Borrado') && ($_POST['accion']!='Validar datos si son correctos') && ($_POST['accion']!='Activar e Informar') && ($_POST['accion']!='Informar de error'))
  showForm($params, $atributos, $a);
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





