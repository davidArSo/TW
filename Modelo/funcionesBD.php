<?php

function getCalendarioBD($db){
    $res=mysqli_query($db, "select acronimo, sexo, meses_ini, meses_fin, tipo, comentarios from Calendario order by acronimo");
    if ($res){
        if (mysqli_num_rows($res)>0){
            $tabla=mysqli_fetch_all($res, MYSQLI_ASSOC);
        }
        else
            $tabla=[];
        mysqli_free_result($res);
    }
    else{
        $tabla=false;
    }
    
    return $tabla;
    
}


function loginBD($email, $contrasenia, $db){
    
    $con=addslashes($contrasenia);

    $res = mysqli_query($db, "SELECT password FROM Usuarios WHERE email='{$email}' AND estado='A' ");
    if ($res){
        if (mysqli_num_rows($res)>0){
            $tabla=mysqli_fetch_all($res, MYSQLI_ASSOC);
        }
        else
            $tabla=[];
        mysqli_free_result($res);
    }
    else{
        $tabla=false;
        $salida=false;
    }
    
    if ($tabla!=false){
        if (password_verify($con, $tabla[0]['password']))
            $salida=true;
        else 
            $salida=false;
    }
    else 
        $salida=false;
    
    
    
    return $salida;
}

function obtenerRolBD($atributo1, $db){
    $rol=mysqli_query($db, "select rol from Usuarios where email='{$atributo1}'");
    
    if ($rol){
        if (mysqli_num_rows($rol)>0){
            $tabla=mysqli_fetch_all($rol, MYSQLI_ASSOC);
        }
        else
            $tabla=[];
        mysqli_free_result($rol);
    }
    else{
        $tabla=false;
    }
    
    return $tabla;
}

function insertarBD($params, $atributos, $db){
    
    
    $res = mysqli_query($db, "SELECT COUNT(*) FROM Usuarios WHERE email='{$params[$atributos[3]]}'");
    $num = mysqli_fetch_row($res)[0];
    mysqli_free_result($res);
    
    
    $imagen_completa=base64_encode($_SESSION['imagen']);
    
    if ($num>0)
    $info[] = 'Ya existe un usuario con ese DNI en la Base de Datos';
    else{

       $nombre=addslashes($params[$atributos[0]]);
       $apellidos=addslashes($params[$atributos[1]]);
       $contrasenia=password_hash(addslashes($params[$atributos[7]]), PASSWORD_DEFAULT);
       
       if ($_SESSION['rol']=='A'){
        $res=mysqli_query($db, "INSERT INTO Usuarios (nombre, apellidos, dni, Email, telefono, fnac, sexo,
        password, rol, estado, Fotografia) VALUES ('{$nombre}', '{$apellidos}', '{$params[$atributos[2]]}'
                          , '{$params[$atributos[3]]}', '{$params[$atributos[4]]}' , '{$params[$atributos[5]]}'  , '{$params[$atributos[6]][0]}'
                         , '{$contrasenia}'  , '{$params[$atributos[9]][0]}', '{$params[$atributos[10]][0]}', '{$imagen_completa}'
                         )");
       }
       else{
        $rol='P';
        $estado='I';
        $res=mysqli_query($db, "INSERT INTO Usuarios (nombre, apellidos, dni, Email, telefono, fnac, sexo,
        password, rol, estado, Fotografia) VALUES ('{$nombre}', '{$apellidos}', '{$params[$atributos[2]]}'
                          , '{$params[$atributos[3]]}', '{$params[$atributos[4]]}' , '{$params[$atributos[5]]}'  , '{$params[$atributos[6]][0]}'
                         , '{$contrasenia}'  , '{$rol}', '{$estado}', '{$imagen_completa}'
                         )");
       }
       
    
    if (!$res){
        $info[] = 'Error en la consulta '.__FUNCTION__;
        $info[] = mysqli_error($db);
    }   

    }
    
    if (isset($info))
    return $info;
    else
    return true;
}


function getUsuariosBD($db){
    $res=mysqli_query($db, "select Fotografia, nombre, apellidos, email, estado, rol from Usuarios order by rol");
    if ($res){
        if (mysqli_num_rows($res)>0){
            $tabla=mysqli_fetch_all($res, MYSQLI_ASSOC);
        }
        else
            $tabla=[];
        mysqli_free_result($res);
    }
    else{
        $tabla=false;
    }
    
    return $tabla;
    
}

function activarBD($email, $db){
    $res=mysqli_query($db, "update Usuarios set estado='A'  where email='{$email}'");
    if (!$res){
        $info[] = 'Error en la consulta '.__FUNCTION__;
        $info[] = mysqli_error($db);
    }   

    if (isset($info))
    return $info;
    else
    return true;

}

function getVacunasBD($db){
    $res=mysqli_query($db, "select acronimo, nombre, descripcion from Vacunas order by acronimo");
    if ($res){
        if (mysqli_num_rows($res)>0){
            $tabla=mysqli_fetch_all($res, MYSQLI_ASSOC);
        }
        else
            $tabla=[];
        mysqli_free_result($res);
    }
    else{
        $tabla=false;
    }
    
    return $tabla;
    
}

function recuperarDatos($email, $db){
    $res=mysqli_query($db, "select nombre, apellidos, dni, email, telefono, fnac, sexo, password, rol, estado, Fotografia  from Usuarios where email='{$email}'");
    if ($res){
        if (mysqli_num_rows($res)>0){
            $tabla=mysqli_fetch_all($res, MYSQLI_ASSOC);
        }
        else
            $tabla=[];
        mysqli_free_result($res);
    }
    else{
        $tabla=false;
    }
    
    return $tabla;
}


function recuperarDatosVacuna($acronimo, $db){
    $res=mysqli_query($db, "select acronimo, nombre, descripcion  from Vacunas where acronimo='{$acronimo}'");
    if ($res){
        if (mysqli_num_rows($res)>0){
            $tabla=mysqli_fetch_all($res, MYSQLI_ASSOC);
        }
        else
            $tabla=[];
        mysqli_free_result($res);
    }
    else{
        $tabla=false;
    }
    
    return $tabla;
}

function eliminarUsuario($email, $db){
    $res=mysqli_query($db, "delete from Usuarios where email='{$email}'");
    
    if (!$res){
        $info[] = 'Error en la consulta '.__FUNCTION__;
        $info[] = mysqli_error($db);
    }   

    if (isset($info))
    return $info;
    else
    return true;
}





function editarUsuarioCBD($params, $atributos, $db){

    $imagen_completa=base64_encode($_SESSION['imagen']);

    $contrasenia=password_hash(addslashes($params[$atributos[7]]), PASSWORD_DEFAULT);
    
   /* telefono= '{$params[$atributos[4]]}', password='{$contrasenia}', Fotografia='{$imagen_completa}' ,*/
    $res=mysqli_query($db, "update Usuarios set telefono= '{$params[$atributos[4]]}', password='{$contrasenia}', Fotografia='{$imagen_completa}' ,email='{$params[$atributos[3]]}' where email='{$_SESSION['correo_mod']}'");

    if (!$res){
        $info[] = 'Error en la consulta '.__FUNCTION__;
        $info[] = mysqli_error($db);
    }   

    if (isset($info))
    return $info;
    else
    return true;
}

function editarUsuarioABD($params, $atributos, $db){
    $imagen_completa=base64_encode($_SESSION['imagen']);

    $nombre=addslashes($params[$atributos[0]]);
    $apellidos=addslashes($params[$atributos[1]]);
    $contrasenia=password_hash(addslashes($params[$atributos[7]]), PASSWORD_DEFAULT);

   $res=mysqli_query($db, "update Usuarios set nombre='{$nombre}' ,apellidos='{$apellidos}' ,dni='{$params[$atributos[2]]}', email='{$params[$atributos[3]]}',
     telefono= '{$params[$atributos[4]]}', fnac='{$params[$atributos[5]]}', sexo='{$params[$atributos[6]][0]}',  password='{$contrasenia}', rol='{$params[$atributos[9]][0]}', estado= '{$params[$atributos[10]][0]}', Fotografia='{$imagen_completa}' where email='{$_SESSION['correo_mod']}'");
   
     if (!$res){
        $info[] = 'Error en la consulta '.__FUNCTION__;
        $info[] = mysqli_error($db);
    }   

    if (isset($info))
    return $info;
    else
    return true;
}

function insertarVacunaBD($params, $atributos, $db){
    $acronimo=addslashes($params[$atributos[0]]);
    $nombre=addslashes($params[$atributos[1]]);
    $descripcion=addslashes($params[$atributos[2]]);

    $res=mysqli_query($db, "INSERT INTO Vacunas (acronimo, nombre, descripcion) VALUES ('{$acronimo}', '{$nombre}', '{$descripcion}')");

    if (!$res){
        $info[] = 'Error en la consulta '.__FUNCTION__;
        $info[] = mysqli_error($db);
    }   

    
    if (isset($info))
    return $info;
    else
    return true;
}


function eliminarVacuna($acronimo, $db){
    $res=mysqli_query($db, "delete from Vacunas where acronimo='{$acronimo}'");
    
    if (!$res){
        $info[] = 'Error en la consulta '.__FUNCTION__;
        $info[] = mysqli_error($db);
    }   

    if (isset($info))
    return $info;
    else
    return true;
}

function editarVacuna($params, $atributos, $db){

    $acronimo=addslashes($params[$atributos[0]]);
    $nombre=addslashes($params[$atributos[1]]);
    $descripcion=addslashes($params[$atributos[2]]);


    
    $res=mysqli_query($db, "update Vacunas set acronimo='{$acronimo}', nombre= '{$nombre}', descripcion='{$descripcion}' where acronimo='{$_SESSION['vacuna_mod']}'");

    if (!$res){
        $info[] = 'Error en la consulta '.__FUNCTION__;
        $info[] = mysqli_error($db);
    }   

    if (isset($info))
    return $info;
    else
    return true;
}




function insertarCalendarioBD($params, $atributos, $db){
    $acronimo=addslashes($params[$atributos[0]]);
    $comentarios=addslashes($params[$atributos[5]]);
    $meses_ini=intval($params[$atributos[2]]);
    $meses_fin=intval($params[$atributos[3]]);
    
    if ((intval($meses_fin)==0) && (intval($meses_ini)>0))
        $meses_fin=intval($meses_ini);

    
    
    $res=mysqli_query($db, "INSERT INTO Calendario (acronimo, sexo, meses_ini, meses_fin, tipo, comentarios) VALUES ('{$acronimo}', '{$params[$atributos[1]][0]}', '{$meses_ini}', '{$meses_fin}', '{$params[$atributos[4]][1]}',  '{$comentarios}')");

    if (!$res){
        $info[] = 'Error en la consulta '.__FUNCTION__;
        $info[] = mysqli_error($db);
    }   

    
    if (isset($info))
    return $info;
    else
    return true;
}

function recuperarDatosCalendario($acronimo, $db){
    $res=mysqli_query($db, "select acronimo, sexo, meses_ini, meses_fin, tipo, comentarios  from Calendario where acronimo='{$acronimo}'");
    if ($res){
        if (mysqli_num_rows($res)>0){
            $tabla=mysqli_fetch_all($res, MYSQLI_ASSOC);
        }
        else
            $tabla=[];
        mysqli_free_result($res);
    }
    else{
        $tabla=false;
    }
    
    return $tabla;
}

function eliminarCalendario($acronimo, $db){
    $res=mysqli_query($db, "delete from Calendario where acronimo='{$acronimo}'");
    
    if (!$res){
        $info[] = 'Error en la consulta '.__FUNCTION__;
        $info[] = mysqli_error($db);
    }   

    if (isset($info))
    return $info;
    else
    return true;
}

function editarCalendario($params, $atributos, $db){
    $comentarios=addslashes($params[$atributos[5]]);
    $meses_ini=intval($params[$atributos[2]]);
    $meses_fin=intval($params[$atributos[3]]);
    if ((intval($meses_fin)==0) && (intval($meses_ini)>0))
        $meses_fin=intval($meses_ini);
    $res=mysqli_query($db, "update Calendario set acronimo='{$params[$atributos[0]]}', sexo= '{$params[$atributos[1]][0]}', meses_ini='{$meses_ini}', meses_fin='{$meses_fin}', tipo='{$params[$atributos[4]][1]}', comentarios='{$comentarios}' where acronimo='{$_SESSION['acronimo_mod']}'");

    if (!$res){
        $info[] = 'Error en la consulta '.__FUNCTION__;
        $info[] = mysqli_error($db);
    }   

    if (isset($info))
    return $info;
    else
    return true;
}

function obtenerVacunacionBD($db){
    $fechas=mysqli_query($db, "select fecha from Vacunacion");
    
    if ($fechas){
        if (mysqli_num_rows($fechas)>0){
            $tabla=mysqli_fetch_all($fechas, MYSQLI_ASSOC);
        }
        else
            $tabla=[];
        mysqli_free_result($fechas);
    }
    else{
        $tabla=false;
    }
    
    return $tabla;
}

function obtenerNumUsuariosBD($db){
    $res = mysqli_query($db, "SELECT rol FROM Usuarios");
    if ($res){
        if (mysqli_num_rows($res)>0){
            $tabla=mysqli_fetch_all($res, MYSQLI_ASSOC);
        }
        else
            $tabla=[];
        mysqli_free_result($res);
    }
    else{
        $tabla=false;
    }
    
    return $tabla;
    return $num;
}

function getCartillaBD($db){
    $res=mysqli_query($db, "select email, acronimo, fecha from Vacunacion where email='{$_SESSION['usuario']}'");
    if ($res){
        if (mysqli_num_rows($res)>0){
            $tabla=mysqli_fetch_all($res, MYSQLI_ASSOC);
        }
        else
            $tabla=[];
        mysqli_free_result($res);
    }
    else{
        $tabla=false;
    }
    
    return $tabla;
}

function getCartillaPacienteBD($email, $db){
    $res=mysqli_query($db, "select email, acronimo, fecha from Vacunacion where email='{$email}'");
    if ($res){
        if (mysqli_num_rows($res)>0){
            $tabla=mysqli_fetch_all($res, MYSQLI_ASSOC);
        }
        else
            $tabla=[];
        mysqli_free_result($res);
    }
    else{
        $tabla=false;
    }
    
    return $tabla;
}

function obtenerFechaNacimientoBD($db){
    $res=mysqli_query($db, "select fnac from Usuarios where email='{$_SESSION['correo_mod']}'");
    
    if ($res){
        if (mysqli_num_rows($res)>0){
            $tabla=mysqli_fetch_all($res, MYSQLI_ASSOC);
        }
        else
            $tabla=[];
        mysqli_free_result($res);
    }
    else{
        $tabla=false;
    }
    
    return $tabla;
}

function obtenerVacunasBD( $db){


    $res=mysqli_query($db, "select acronimo, meses_ini, meses_fin from Calendario where acronimo not in (Select acronimo from Vacunacion where email='{$_SESSION['usuario']}') order by meses_ini");
    if ($res){
        if (mysqli_num_rows($res)>0){
            $tabla=mysqli_fetch_all($res, MYSQLI_ASSOC);
        }
        else
            $tabla=[];
        mysqli_free_result($res);
    }
    else{
        $tabla=false;
    }
    
    return $tabla;

}


function insertLog($mensaje, $db){
    $date = date('Y-m-d H:i:s');
    $res=mysqli_query($db, "INSERT INTO log (fecha, descripcion) VALUES ('{$date}', '{$mensaje}')");

    if (!$res){
        $info[] = 'Error en la consulta '.__FUNCTION__;
        $info[] = mysqli_error($db);
    }   

    
    if (isset($info))
    return $info;
    else
    return true;
}


function getNumLogsBD($db,$cadenab='') {
    if ($cadenab!='')
      $cadenab .= ' AND ';
    $res = mysqli_query($db, "SELECT COUNT(*) FROM log");
    $num = mysqli_fetch_row($res)[0];
    mysqli_free_result($res);
    return $num;
}

function get_LogsBD($db,$primero=0,$numitems=0,$cadenab='') {
    
    if ($numitems<=0) {  // Listarlos todos
      $rango='';
    } else {
        $rango = 'LIMIT '.(int)($numitems).' OFFSET '.abs($primero);
    }
    
    // Consulta a la BBDD
    if (strlen($cadenab)!=0)
      $cadenab.=' AND ';
        
      $res = mysqli_query($db,
      "SELECT fecha, descripcion FROM log ORDER BY fecha desc $rango");
      
    if ($res) {
        
      // Si no hay error
      if (mysqli_num_rows($res)>0) {
        // Si hay alguna tupla de respuesta
        $tabla = mysqli_fetch_all($res,MYSQLI_ASSOC);
      } else {
        $tabla = [];
      }
      // Liberar memoria de la consulta
      mysqli_free_result($res);
    } else {
      $tabla = false;
    }
    
    return $tabla;
  }

function getNumVacunasBD($db,$cadenab='') {
    if ($cadenab!='')
      $cadenab .= ' AND ';
    $res = mysqli_query($db, "SELECT COUNT(*) FROM Vacunas");
    $num = mysqli_fetch_row($res)[0];
    mysqli_free_result($res);
    return $num;
}

function get_VacunasBD($db,$primero=0,$numitems=0,$cadenab='') {
    
    if ($numitems<=0) {  // Listarlos todos
      $rango='';
    } else {
        $rango = 'LIMIT '.(int)($numitems).' OFFSET '.abs($primero);
    }
    
    // Consulta a la BBDD
    if (strlen($cadenab)!=0)
      $cadenab.=' AND ';
        
      $res = mysqli_query($db,
      "SELECT acronimo, nombre, descripcion FROM Vacunas ORDER BY acronimo $rango");
      
    if ($res) {
        
      // Si no hay error
      if (mysqli_num_rows($res)>0) {
        // Si hay alguna tupla de respuesta
        $tabla = mysqli_fetch_all($res,MYSQLI_ASSOC);
      } else {
        $tabla = [];
      }
      // Liberar memoria de la consulta
      mysqli_free_result($res);
    } else {
      $tabla = false;
    }
    
    return $tabla;
  }

function getNumUsuariosBD($db,$cadenab='') {
    if ($cadenab!='')
      $cadenab .= ' AND ';
    $res = mysqli_query($db, "SELECT COUNT(*) FROM Usuarios");
    $num = mysqli_fetch_row($res)[0];
    mysqli_free_result($res);
    return $num;
}

function get_UsuariosBD($db,$primero=0,$numitems=0,$cadenab='') {
    
    if ($numitems<=0) {  // Listarlos todos
      $rango='';
    } else {
        $rango = 'LIMIT '.(int)($numitems).' OFFSET '.abs($primero);
    }
    
    // Consulta a la BBDD
    if (strlen($cadenab)!=0)
      $cadenab.=' AND ';
        
      $res = mysqli_query($db,
      "SELECT Fotografia, nombre, apellidos, email, rol, estado FROM Usuarios ORDER BY rol $rango");
      
    if ($res) {
        
      // Si no hay error
      if (mysqli_num_rows($res)>0) {
        // Si hay alguna tupla de respuesta
        $tabla = mysqli_fetch_all($res,MYSQLI_ASSOC);
      } else {
        $tabla = [];
      }
      // Liberar memoria de la consulta
      mysqli_free_result($res);
    } else {
      $tabla = false;
    }
    
    return $tabla;
  }


  function getNumPacientesBD($db,$cadenab='') {
    if ($cadenab!='')
      $cadenab .= ' AND ';
    $res = mysqli_query($db, "SELECT COUNT(*) FROM Usuarios where rol='P'");
    $num = mysqli_fetch_row($res)[0];
    mysqli_free_result($res);
    return $num;
}

function get_PacientesBD($db,$primero=0,$numitems=0,$cadenab='') {
    
    if ($numitems<=0) {  // Listarlos todos
      $rango='';
    } else {
        $rango = 'LIMIT '.(int)($numitems).' OFFSET '.abs($primero);
    }
    
    // Consulta a la BBDD
    if (strlen($cadenab)!=0)
      $cadenab.=' AND ';
        
      $res = mysqli_query($db,
      "SELECT Fotografia, nombre, apellidos, email, rol, estado FROM Usuarios where rol='P' ORDER BY nombre $rango");
      
    if ($res) {
        
      // Si no hay error
      if (mysqli_num_rows($res)>0) {
        // Si hay alguna tupla de respuesta
        $tabla = mysqli_fetch_all($res,MYSQLI_ASSOC);
      } else {
        $tabla = [];
      }
      // Liberar memoria de la consulta
      mysqli_free_result($res);
    } else {
      $tabla = false;
    }
    
    return $tabla;
  }

  function obtenerVacunacionPacienteBD($acronimo, $db){
    $res=mysqli_query($db, "select email, acronimo, fecha, fabricante, comentarios from Vacunacion where email='{$_SESSION['correo_mod']}' AND acronimo='{$acronimo}' ");
    if ($res){
        if (mysqli_num_rows($res)>0){
            $tabla=mysqli_fetch_all($res, MYSQLI_ASSOC);
        }
        else
            $tabla=[];
        mysqli_free_result($res);
    }
    else{
        $tabla=false;
    }
    
    return $tabla;
  }

  function editarCartillaBD($params, $atributos, $db){


    $acronimo=addslashes($params[$atributos[1]]);
    $fabricante=addslashes($params[$atributos[3]]);
    $comentarios=addslashes($params[$atributos[4]]);
    $res=mysqli_query($db, "update Vacunacion set acronimo= '{$acronimo}', fabricante='{$fabricante}', comentarios='{$comentarios}' ,fecha='{$params[$atributos[2]]}' where email='{$_SESSION['correo_mod']}' AND acronimo='{$_SESSION['acronimo_mod']}'");

    if (!$res){
        $info[] = 'Error en la consulta '.__FUNCTION__;
        $info[] = mysqli_error($db);
    }   

    if (isset($info))
    return $info;
    else
    return true;
}

function aniadirCartillaBD($params, $atributos, $db){
    $acronimo=addslashes($params[$atributos[1]]);
    $fabricante=addslashes($params[$atributos[3]]);
    $comentarios=addslashes($params[$atributos[4]]);
    echo $_SESSION['correo_mod'];
    $res=mysqli_query($db, "INSERT INTO Vacunacion (email, acronimo, fecha, fabricante,comentarios) VALUES ('{$_SESSION['correo_mod']}', '{$acronimo}', '{$params[$atributos[2]]}', '{$fabricante}','{$comentarios}')");

    if (!$res){
        $info[] = 'Error en la consulta '.__FUNCTION__;
        $info[] = mysqli_error($db);
    }   

    
    if (isset($info))
    return $info;
    else
    return true;
}

function Pacientes_buildSearch($db,$query) {

    $cadenab='';

    if (isset ($query['numSemanas']) && isset ($query['pendientes'])){
        
        $date_ = date('Y-m-d');
        $cadenab.="INNER JOIN Calendario ON ((select datediff(  '{$date_}', Usuarios.fnac) as date_diff)/30> Calendario.meses_fin) where (Usuarios.email, Calendario.acronimo) not in (Select email, acronimo from Vacunacion) and Usuarios.email in   (SELECT distinct email FROM Usuarios INNER JOIN Calendario ON ( ((select datediff(  '{$date_}', Usuarios.fnac) as date_diff)/30< Calendario.meses_fin) && ((select datediff(  '{$date_}', Usuarios.fnac) as date_diff)/30+ ".$query['numSemanas']."/4.34 > Calendario.meses_ini)) where (Usuarios.email, Calendario.acronimo) not in (Select email, acronimo from Vacunacion) AND";
         }
    else if (isset ($query['pendientes'])){
        
        $date_ = date('Y-m-d');
        $cadenab.="INNER JOIN Calendario ON ((select datediff(  '{$date_}', Usuarios.fnac) as date_diff)/30> Calendario.meses_fin) where (Usuarios.email, Calendario.acronimo) not in (Select email, acronimo from Vacunacion) AND";
    }
     else if (isset ($query['numSemanas'])){
        $date_ = date('Y-m-d');
        $cadenab.="INNER JOIN Calendario ON ( ((select datediff(  '{$date_}', Usuarios.fnac) as date_diff)/30< Calendario.meses_fin) && ((select datediff(  '{$date_}', Usuarios.fnac) as date_diff)/30+ ".$query['numSemanas']."/4.34 > Calendario.meses_ini)) where (Usuarios.email, Calendario.acronimo) not in (Select email, acronimo from Vacunacion) AND";
    }

    if (isset($query['nombre']))
      $cadenab .= " nombre LIKE '%".mysqli_real_escape_string($db,$query['nombre'])."%' AND";
    if (isset($query['apellidos']))
      $cadenab .= " apellidos LIKE '%".mysqli_real_escape_string($db,$query['apellidos'])."%' AND";
    if (isset($query['dni']))
      $cadenab .= " dni='".mysqli_real_escape_string($db,$query['dni'])."' AND";
    if (isset($query['fechamax']))
      $cadenab .= " fnac<='".mysqli_real_escape_string($db,$query['fechamax'])."' AND";
    if (isset($query['fechamin']))
      $cadenab .= " fnac>='".mysqli_real_escape_string($db,$query['fechamin'])."' AND";
    if (isset($query['estado']))
      $cadenab .= " estado='".mysqli_real_escape_string($db,$query['estado'][0])."' AND";
    if (strlen($cadenab)>0)
      $cadenab = substr_replace($cadenab, '', strlen($cadenab)-4, 4);
   /* if (isset($query['pendientes']))*/
 
  /*$cadenab.="INNER JOIN Calendario ON ( ((select datediff(  '{$date_}', Usuarios.fnac) as date_diff)/30< Calendario.meses_fin) && ((select datediff(  '{$date_}', Usuarios.fnac) as date_diff)/30+ ".$query['numSemanas']."/4.34 > Calendario.meses_ini)) where (Usuarios.email, Calendario.acronimo) not in (Select email, acronimo from Vacunacion) AND ";*/
    
  /*  select * FROM Usuarios INNER JOIN Calendario ON ((select datediff( '2021-05-22', Usuarios.fnac) as date_diff)/30> Calendario.meses_fin) where (Usuarios.email, Calendario.acronimo) not in (Select email, acronimo from Vacunacion);
  */


    return $cadenab;
}

function Pacientes_getNumPacientes($db,$cadenab='', $query) {
    
      
    if (isset ($query['pendientes']) && isset($query['numSemanas'])){
        $res = mysqli_query($db, "SELECT COUNT(*) FROM (select distinct email from Usuarios $cadenab))a");
    }
    else if (isset ($query['pendientes']) || isset($query['numSemanas']))
        $res = mysqli_query($db, "SELECT COUNT(distinct email) FROM Usuarios $cadenab ");
    else {
        if ($cadenab!='')
            $cadenab .= ' AND ';
        $res = mysqli_query($db, "SELECT COUNT(*) FROM Usuarios WHERE $cadenab (sexo='M' or sexo='F')");
    }
        
    $num = mysqli_fetch_row($res)[0];
    mysqli_free_result($res);
    return $num;
  }

  function Pacientes_getPacientes($db,$primero=0,$numitems=0,$cadenab='', $query) {
    
    
    if ($numitems<=0) {  // Listarlos todos
      $rango='';
    } else {
      $rango = 'LIMIT '.(int)($numitems).' OFFSET '.abs($primero);
    }

    if (isset($query['ordenacion'])){
        if ($query['ordenacion']=='mayoramenor')
            $orden='fnac';
        else if ($query['ordenacion']=='menoramayor')
            $orden='fnac desc';
        else if ($query['ordenacion']=='nombre')
            $orden='nombre';
        else 
            $orden='apellidos';
    }
    else
        $orden='email';

   
    // Consulta a la BBDD
    /*if (strlen($cadenab)!=0)
      $cadenab.=' AND ';*/
      
      if (isset ($query['pendientes']) && isset($query['numSemanas'])){
        $res = mysqli_query($db, "(SELECT distinct Fotografia,nombre,apellidos, email, rol, estado, fnac FROM Usuarios $cadenab  ) ORDER BY $orden $rango)");//Aquí es donde hay que pasarle por que los vamos a ordenar
    
    }
    else if (isset ($query['pendientes'])|| isset($query['numSemanas'])){
        
     
        $res = mysqli_query($db, "SELECT distinct Fotografia,nombre,apellidos, email, rol, estado, fnac FROM Usuarios $cadenab  ORDER BY $orden $rango");//Aquí es donde hay que pasarle por que los vamos a ordenar
    }
    else {
        if (strlen($cadenab)!=0)
        $cadenab.=' AND ';
        $res = mysqli_query($db,"SELECT Fotografia,nombre,apellidos,email, rol, estado, fnac  FROM Usuarios WHERE $cadenab (sexo='M' or sexo='F') ORDER BY $orden $rango");//Aquí es donde hay que pasarle por que los vamos a ordenar
        
    }
    
        
    
    if ($res) {
      // Si no hay error
      if (mysqli_num_rows($res)>0) {
        // Si hay alguna tupla de respuesta
        $tabla = mysqli_fetch_all($res,MYSQLI_ASSOC);
        
      } else {
        $tabla = [];
      }
      // Liberar memoria de la consulta
      mysqli_free_result($res);
    } else {
      $tabla = false;
    }
    
    return $tabla;
  }

  function crearTablasSiNoExisten($db){
    $res=mysqli_query($db, "select * from Usuarios");
    if ($res){
       $aniadir='false';
    }
    else{
        $aniadir='true';
    }
    

    /*$res=mysqli_query($db, "CREATE TABLE if not exists Usuarios (
        nombre varchar(50),
        apellidos varchar(100),
        dni varchar(15),
        telefono varchar(20) ,
        email varchar(200),
        fnac date ,
        sexo char(1),
        password varchar(200) ,
        rol char(1) ,
        estado char(1) ,
        Fotografia longblob,
        PRIMARY KEY (email)
      )");*/

$res=mysqli_query($db, "CREATE TABLE if not exists Usuarios (
    nombre varchar(50) DEFAULT NULL,
    apellidos varchar(100) DEFAULT NULL,
    dni varchar(15) DEFAULT NULL,
    telefono varchar(20) DEFAULT NULL,
    email varchar(200) NOT NULL,
    fnac date DEFAULT NULL,
    sexo char(1) DEFAULT NULL,
    password varchar(200) DEFAULT NULL,
    rol char(1) DEFAULT NULL,
    estado char(1) DEFAULT NULL,
    Fotografia longblob,
    PRIMARY KEY (`email`)
  )ENGINE=InnoDB DEFAULT CHARSET=latin1");
/*
$res=mysqli_query($db, "create table if not exists Vacunas (
    acronimo varchar(15) NOT NULL,
    nombre varchar(100),
    descripcion Blob, 
    PRIMARY KEY (acronimo)
)");*/


$res=mysqli_query($db, "CREATE TABLE if not exists Vacunas (
    acronimo varchar(15) NOT NULL,
    nombre varchar(100) DEFAULT NULL,
    descripcion blob,
    PRIMARY KEY (acronimo)) ENGINE=InnoDB DEFAULT CHARSET=latin1");
  
    /*$res=mysqli_query($db, "create table if not exists Calendario(
        acronimo varchar(15) NOT NULL, 
        sexo char(1), 
        meses_ini SMALLINT(5), 
        meses_fin SMALLINT(5), 
        tipo char(1), 
        comentarios varchar(255), 
        FOREIGN KEY (acronimo) REFERENCES Vacunas(acronimo), 
        PRIMARY KEY (acronimo)
    
    )");*/

    $res=mysqli_query($db, "
    CREATE TABLE if not exists Calendario (
        acronimo varchar(15) NOT NULL,
        sexo char(1) DEFAULT NULL,
        meses_ini smallint(5) DEFAULT NULL,
        meses_fin smallint(5) DEFAULT NULL,
        tipo char(1) DEFAULT NULL,
        comentarios varchar(255) DEFAULT NULL,
        PRIMARY KEY (acronimo),
        CONSTRAINT Calendario_ibfk_1 FOREIGN KEY (acronimo) REFERENCES Vacunas (acronimo)
      )ENGINE=InnoDB DEFAULT CHARSET=latin1");

    

    /*$res=mysqli_query($db, "create table if not exists Vacunacion (
        email varchar(200), 
        acronimo varchar(15), 
        fecha DATE,
        fabricante varchar(100), 
        comentarios varchar (255),
        FOREIGN KEY (email) REFERENCES Usuarios(email) ON UPDATE CASCADE ON DELETE CASCADE, 
        FOREIGN KEY (acronimo) REFERENCES Calendario(acronimo) ON UPDATE CASCADE ON DELETE CASCADE, 
        PRIMARY KEY (email, acronimo)
    )");*/

    $res=mysqli_query($db, "CREATE TABLE if not exists Vacunacion (
        email varchar(200) NOT NULL,
        acronimo varchar(15) NOT NULL,
        fecha date DEFAULT NULL,
        fabricante varchar(100) DEFAULT NULL,
        comentarios varchar(255) DEFAULT NULL,
        PRIMARY KEY (email,acronimo),
        KEY acronimo (acronimo),
        CONSTRAINT Vacunacion_ibfk_1 FOREIGN KEY (email) REFERENCES Usuarios (email) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT Vacunacion_ibfk_2 FOREIGN KEY (acronimo) REFERENCES Calendario (acronimo) ON DELETE CASCADE ON UPDATE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=latin1");

    $res=mysqli_query($db, "create table if not exists log (
        fecha datetime NOT NULL,
        descripcion varchar(200),
        PRIMARY KEY (fecha)
    )ENGINE=InnoDB DEFAULT CHARSET=latin1 ");
    
    
    $fecha_ac = date('Y-m-d H:i:s');
    
    
    
    if ($aniadir=='true'){
        mysqli_query($db, "insert into log (fecha, descripcion) values ('{$fecha_ac}', 'Se ha CREADO la tabla Usuarios en el sistema')");
        $contrasenia=password_hash('a', PASSWORD_DEFAULT);
        $fotografia='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';
        $res=mysqli_query($db, "insert into Usuarios (nombre, apellidos, dni, telefono, email, fnac, sexo, password, rol, estado, Fotografia) values ('Primer', 'Usuario', '75927670R', '636874394', 'primerusuario@correo.ugr.es', '1998-01-01', 'M', '{$contrasenia}', 'A', 'A', '{$fotografia}')");
        $_SESSION['correo_mod']='primerusuario@correo.ugr.es';
    }
    

    if (!$res){
        $info[] = 'Error en la consulta '.__FUNCTION__;
        $info[] = mysqli_error($db);
    }   

    if (isset($info))
    return $info;
    else
    return true;
  }


  function insertUsuarioPrueba($db){
    $contrasenia=password_hash('a', PASSWORD_DEFAULT);
    $fotografia='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';
    $res=mysqli_query($db, "insert into Usuarios (nombre, apellidos, dni, telefono, email, fnac, sexo, password, rol, estado, Fotografia) values ('Primer', 'Usuario', '75927670R', '636874394', 'primerusuario@correo.ugr.es', '1998-01-01', 'M', '{$contrasenia}', 'A', 'A', '{$fotografia}')");
    $_SESSION['correo_mod']='primerusuario@correo.ugr.es';
  }

?>