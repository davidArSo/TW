<?php
require_once('../Vista/VistasHTML.php');
require_once('../Modelo/db.php');
require_once('../Modelo/funcionesBD.php');

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

function getParamsFecha($post, $atributo) {
    $error=$atributo.'error';
    $result[$error]='';
    if (isset($post[$atributo])) { /* El formulario ha sido enviado */
    $result['enviado'] = true;
    
    if (!isset($post[$atributo]) or empty($post[$atributo])){
        $result[$error] = 'No ha indicado ningún valor';
        $result[$atributo]='';
    }
        
    else if (!preg_match('/^((((19|[2-9]\d)\d{2})\-(0[13578]|1[02])\-(0[1-9]|[12]\d|3[01]))|(((19|[2-9]\d)\d{2})\-(0[13456789]|1[012])\-(0[1-9]|[12]\d|30))|(((19|[2-9]\d)\d{2})\-02\-(0[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))\-02\-29))$/', $post[$atributo])){
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



$db=DB_connection();

if (isset($_POST['Email'])){
    $_SESSION['correo_mod']=$_POST['Email'];
}


if (!isset($_POST['accion'])){
    
    $res=getCartillaBD($db);
    listadoCartilla($res, 'personal');
}

else if ($_POST['accion']=='Ver Cartilla'){
    $res=getCartillaPacienteBD($_SESSION['correo_mod'],$db);
    listadoCartilla($res, '');
}

else if ($_POST['accion']=='Modificar Cartilla'){
    $res=getCartillaPacienteBD($_SESSION['correo_mod'],$db);
    listadoCartilla($res, 'Modificar');
}

else if ($_POST['accion']=='Modificar Vacuna'){
    
    $atributo1="email";
    $params1 ['email']=$_SESSION['correo_mod'];
    $atributo2="acronimo";
    $params2 ['acronimo']= $_POST['acronimo'];
    $_SESSION['acronimo_mod']=$_POST['acronimo'];
    $atributo3="fecha";
    $atributo4="fabricante";
    $atributo5="comentarios";
    $r=obtenerVacunacionPacienteBD($_POST['acronimo'], $db);

    $params3[$atributo3]=$r[0]['fecha'];
    $params4[$atributo4]=$r[0]['fabricante'];
    $params5[$atributo5]=$r[0]['comentarios'];
    


        


    $atributos= array($atributo1, $atributo2, $atributo3, $atributo4, $atributo5);

    $params = array_merge($params1, $params2, $params3, $params4, $params5);
    

    showAniadirCartilla($params,$atributos,'');
}

else if ($_POST['accion']=='Enviar' || $_POST['accion']=='Validar' ){
    
    $atributo1="email";
    $params1 ['email']=$_SESSION['correo_mod'];
    $atributo2="acronimo";
    $atributo3="fecha";
    $atributo4="fabricante";
    $atributo5="comentarios";

    $params2 = getParams($_POST, $atributo2);
    $params3 = getParamsFecha($_POST, $atributo3);
    $params4 = getParams($_POST, $atributo4);
    $params5 = getParams($_POST, $atributo5);

    


        


    $atributos= array($atributo1, $atributo2, $atributo3, $atributo4, $atributo5);

    $params = array_merge($params1, $params2, $params3, $params4, $params5);

    $error='false';


    for ($i = 1; $i <= 4; $i++) {
      $error_p=$atributos[$i].'error';
      if (isset($params[$error_p])){
        if ($params[$error_p]!='' || $params['enviado']=='false'){
            $error='true';
        }
      }
        
       else 
        $error='true';

       
            
    }

    if ($error=='false')
        $a='readonly';
    else 
        $a='';
    
    if ($_POST['accion']=='Enviar')
        showAniadirCartilla($params,$atributos,$a);
    else {
        $r=editarCartillaBD($params, $atributos, $db);
        if ($r=="true"){
            $mensaje="Un sanitario con email ".$_SESSION['usuario']." HA EDITADO la cartilla de vacunación de ".$_SESSION['correo_mod']." con éxito";
            insertLog($mensaje, $db);
            mensajeCartillaModificada($params, $atributos);
        }
            
        else {
            $mensaje="Un sanitario con email ".$_SESSION['usuario']." NO ha podido EDITAR la cartilla de vacunación de ".$_SESSION['correo_mod']." con éxito";
            insertLog($mensaje, $db);
            mensajeCartillaNoModificada($r);
        }
            
    }
        
}
else if ($_POST['accion']=='Añadir Vacuna' || $_POST['accion']=='Validar si son correctos' ){
    
    $atributo1="email";
    $params1 ['email']=$_SESSION['correo_mod'];
    $atributo2="acronimo";
    $atributo3="fecha";
    $atributo4="fabricante";
    $atributo5="comentarios";

    $params2 = getParams($_POST, $atributo2);
    $params3 = getParamsFecha($_POST, $atributo3);
    $params4 = getParams($_POST, $atributo4);
    $params5 = getParams($_POST, $atributo5);

    


        


    $atributos= array($atributo1, $atributo2, $atributo3, $atributo4, $atributo5);

    $params = array_merge($params1, $params2, $params3, $params4, $params5);

    $error='false';

    for ($i = 0; $i <= 4; $i++) {
    if (isset($_POST['accion'])){
        if (($_POST['accion']=='Añadir Vacuna' || $_POST['accion']=='Validar si son correctos') && $i==0){

        }else{
            $error_p=$atributos[$i].'error';
            if ($params[$error_p]!='' || $params['enviado']=='false'){
                $error='true';
            }
        }
    }
    else{
        $error_p=$atributos[$i].'error';
            if ($params[$error_p]!='' || $params['enviado']=='false'){
                $error='true';
            }
    }
        
    
    }

    if ($error=='false')
        $a='readonly';
    else 
        $a='';
    
    if ($_POST['accion']=='Añadir Vacuna' )
        showAniadirCartilla($params,$atributos,$a);
    else{
        $r=aniadirCartillaBD($params, $atributos, $db);
        if ($r=="true"){
            $mensaje="Un sanitario con email ".$_SESSION['usuario']." HA AÑADIDO la vacuna ".$params[$atributo2]." a la cartilla de vacunación de ".$_SESSION['correo_mod']." con éxito";
            insertLog($mensaje, $db);
            mensajeCartillaAniadida($params, $atributos);
        }
            
        else {
            mensajeCartillaNoAniadida($r);
            $mensaje="Un sanitario con email ".$_SESSION['usuario']." NO ha podido AÑADIR a la cartilla de vacunación de ".$_SESSION['correo_mod']." con éxito";
            insertLog($mensaje, $db);
        }
            
    }

    
        
}




  
    



DB_disconnection($db);


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