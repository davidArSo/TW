<?php
/*require('../Controlador/comprobaciones.php');*/
/*require_once('../Controlador/comprobaciones.php');*/
require_once(__DIR__ . "/../Controlador/comprobaciones.php");
function HTMLinicio($titulo) {
echo <<< HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<base href="https://void.ugr.es/~prji2021nakidavid/proyecto/">
<link type="text/css" rel="stylesheet" href="Vista/estilo.css">
<title>$titulo</title>
<meta name="author" content="Inaki Melguizo Marcos">

</head>
<body>
HTML;
}
/*<base href="https://void.ugr.es/~prji2021nakidavid/proyecto/">*/

function HTMLheader(){
session_start();
echo <<< HTML
<header>
<img src="images/vacuna.png" alt="vacuna" id="img_vacuna" >
<h1 class="centrado">Subsistema de Gestion de Vacunación</h1>

</header>
HTML;
}

function HTMLfin() {
echo <<< HTML
</body>
</html>
HTML;
    }
    
function HTMLnav($activo) {
echo <<< HTML
<nav>
<ul class="en_linea sin_puntos centro">
HTML;
if (!isset($_SESSION['login'])) {
    $items = ["Calendario de Vacunación", "Solicitar Ingreso"];
    /* index.php */
    $links = ["index.php", "Controlador/procesar.php"];
}
else if (  ($_SESSION['login']=='false') || ($_SESSION['login']=='')     ){
    $items = ["Calendario de Vacunación", "Solicitar Ingreso"];
    $links = ["index.php", "Controlador/procesar.php"];
}


if  (isset($_SESSION['rol'])){
    if ($_SESSION["rol"]=="P"){
        $items = ["Calendario de Vacunación", "Información Personal", "Cartilla de Vacunación Personal", "Información Adicional Vacunas Paciente"];
        $links = ["index.php", "Controlador/infoPersonal.php", "Controlador/cartillaVacunacion.php", "Controlador/infoAdicional.php"];
    }
}

if  (isset($_SESSION['rol'])){
    if ($_SESSION["rol"]=="S"){
        $items = ["Calendario de Vacunación", "Información Personal", "ListadoPacientes", "Cartilla de Vacunación Personal", "Información Adicional Vacunas Paciente"];
        $links = ["index.php", "Controlador/infoPersonal.php", "Controlador/listadoPacientes2.php", "Controlador/cartillaVacunacion.php", "Controlador/infoAdicional.php"];
    }
}

if  (isset($_SESSION['rol'])) {
    if ($_SESSION["rol"]=="A") {
        $items = ["Añadir a calendario","Calendario de Vacunación" ,  "Añadir Paciente", "Listado Usuarios", "Añadir Vacunas", "Listado Vacunas","Cartilla de Vacunación Personal", "Información del Log", "Gestión de Base de Datos"];
        $links = ["Controlador/procesarCalendario.php","index.php", "Controlador/procesar.php", "Controlador/listadoPacientes.php", "Controlador/procesarVacuna.php", "Controlador/listadoVacunas.php", "Controlador/cartillaVacunacion.php", "Controlador/listado_paginado_logs.php", "Controlador/pestaniabackup.php" ];
    }
}
    

foreach ($items as $k => $v)
    echo "<li".($k==$activo?" class='activo'":"").">"."<a href='".$links[$k]."'>".$v."</a></li>";
echo <<< HTML
</ul>
</nav>
HTML;
}



function HTMLaside($log, $usuario){
if ($usuario=='' )
echo "
<aside>
<section id='color_login'>
<img src='images/user_login.png' alt='user_login' id='img_login' >
<form action='Controlador/procesarlogin.php' method='POST' enctype='multipart/form-data'>
    <div class='caja_login en_linea'><img src='images/sobre.png' alt='sobre' class='imagenes_login' > <input class='cajas_login' type='text' name='login'/></div>
    <div class='caja_login en_linea'><img src='images/candado.png' alt='candado' class='imagenes_login' > <input  class='cajas_login' type='password' name='clave'/></div>
<p><input type='submit' value='Login' name='submit' class='boton'/></p>
</section>";
else{

echo "
<aside>
<section id='color_login'>";
echo "<img src='data:image/png;base64,".base64_encode($_SESSION['foto_usuario'])."' class='imagen_usuario'>"; /*style="display: block;
margin-top: 2%;
margin-left: auto;
margin-right: auto;
width: 30%;"  />*/


echo "<p> El usuario activo es ".$usuario."</p>
<p>Pulse el boton para deslogearse:</p>
<form action='Controlador/logout.php'>
    <input type='submit' value='Logout' class='boton' />
</form>
</section>
";
}
echo <<< HTML
<section>
<h2 class='texto_centro'>Estadísticas del sistema </h2>
HTML;
$r=estadisticasVacunacion();

echo "<p>En estos últimos 30 días han sido vacunadas ".$r." personas</p>";

$p=numeroUsuariosSistema();


echo <<<HTML
<div id='listado_estadisticas'><table><tr>
<th colspan='3'>Roles de Usuarios del Sistema<th></tr>
<tr><th>Rol</th><th>Nº usuarios</th> <th>%</th></tr>
HTML;
    foreach ($p as $v){
     
    echo "  <tr>  <td class='vac_acro'>{$v[0]}</td>";
    echo"     <td class='vac_nombre'>{$v[1]}</td>";
    echo"     <td class='vac_comentarios'>{$v[2]}%</td>";
    echo "</tr>";
    }
    
    echo "</table></div>";
/*
echo "<ul><li>".$p[0]." pacientes</li>";
echo "<li>".$p[1]." sanitarios</li>";
echo "<li>".$p[2]." administradores</li>";*/


echo <<< HTML
</section>
</aside>
</div>	
HTML;

}


function informacionAdicional($params){
    echo "<div class='en_linea'><main>
    
    <h2 class='titulo_pagina'>Describa la acción que desea ralizar</h2>
    <div class='en_linea centro'>
    <form action='Controlador/procesarInfoAdicional.php' method='POST' class='margen_formulario'>
    <p>Mostrar las siguientes N vacunas: 
    <input type='text' name='num_vacunas' />";
   
    
    echo "<input type='submit' name='accion' value='Mostrar N vacunas'/></p>";
    if (isset($params['num_vacunaserror']))
        if ($params['num_vacunaserror']!='')
            echo "<p><span>Error</span>: ".$params['num_vacunaserror']."</p>";
    echo "<p> Mostrar las vacunas que debe ponerse en los próximos N meses: 
    <input type='text' name='num_meses' /> 
    <input type='submit' name='accion' value='Mostrar N meses'/> </p>";

    if (isset($params['num_meseserror']))
        if ($params['num_meseserror']!='')
            echo "<p><span>Error</span>: ".$params['num_meseserror']."</p>";

    echo "<p> Mostrar todas las vacunas que debe ponerse en el futuro:
    <input type='submit' name='accion' value='Mostrar vacunas futuras'/> </p>
    <p> Mostrar las vacunas que debía haberse puesto: 
    <input type='submit' name='accion' value='Mostrar vacunas pasadas'/> </p>
    </form>
    <img src='images/lupita.png' alt='medico' id='img_lupa' >
    </div>
    </main>";
}


function HTMLcalendario($datos, $accion){


echo <<< HTML
<div class="en_linea">
<main>
<div class='listado2'><table>
HTML;
if ($_SESSION['rol']!='A')
echo "<tr><th colspan='16'>Calendario de Vacunaciones para todas las edades Andalucía 2021</th></tr>
<tr><th rowspan='2'>Vacuna</th>  <th colspan='7'> Edad en meses </th> <th colspan='8'> Edad en años </th></tr>
<tr> <th>Prenatal</th> <th>0</th> <th>2</th> <th>4</th> <th>11</th> <th>12</th> <th>15</th> <th>3</th> <th>6</th> <th>12</th> <th>14</th> <th>18</th> <th>50</th> <th>65</th> <th>>65</th> </tr>";
else
echo "<tr><th colspan='17'>Calendario de Vacunaciones para todas las edades Andalucía 2021</th></tr>
<tr><th rowspan='2'>Vacuna</th>  <th colspan='7'> Edad en meses </th> <th colspan='8'> Edad en años </th><th rowspan='2'>Acciones</th></tr>
<tr> <th>Prenatal</th> <th>0</th> <th>2</th> <th>4</th> <th>11</th> <th>12</th> <th>15</th> <th>3</th> <th>6</th> <th>12</th> <th>14</th> <th>18</th> <th>50</th> <th>65</th> <th>>65</th> </tr>";

foreach ($datos as $v){
 
echo "  <tr>  <td class='vac_nombre'>{$v['acronimo']}</td>";
if (comprobarRangoEdad(-30, 0, $v))
    echo"     <td class='vac_tiempo'>{$v['acronimo']}</td>";
else 
    echo"     <td class='vac_tiempo fondo_blanco'></td>";
if (comprobarRangoEdad(0, 2, $v))
    echo"     <td class='vac_tiempo'>{$v['acronimo']}</td>";
else 
    echo"     <td class='vac_tiempo fondo_blanco'></td>";
if (comprobarRangoEdad(2, 4, $v))
    echo"     <td class='vac_tiempo'>{$v['acronimo']}</td>";
else 
    echo"     <td class='vac_tiempo fondo_blanco'></td>";
if (comprobarRangoEdad(4, 11, $v))
    echo"     <td class='vac_tiempo'>{$v['acronimo']}</td>";
else 
    echo"     <td class='vac_tiempo fondo_blanco'></td>";

if (comprobarRangoEdad(11, 12, $v))
    echo"     <td class='vac_tiempo'>{$v['acronimo']}</td>";
else 
    echo"     <td class='vac_tiempo fondo_blanco'></td>";

if (comprobarRangoEdad(12, 15, $v))
    echo"     <td class='vac_tiempo'>{$v['acronimo']}</td>";
else 
    echo"     <td class='vac_tiempo fondo_blanco'></td>";

if (comprobarRangoEdad(15, 36, $v))
    echo"     <td class='vac_tiempo'>{$v['acronimo']}</td>";
else 
    echo"     <td class='vac_tiempo fondo_blanco'></td>";


if (comprobarRangoEdad(36, 72, $v))
    echo"     <td class='vac_tiempo'>{$v['acronimo']}</td>";
else 
    echo"     <td class='vac_tiempo fondo_blanco'></td>";  
    
if (comprobarRangoEdad(72, 144, $v))
    echo"     <td class='vac_tiempo'>{$v['acronimo']}</td>";
else 
    echo"     <td class='vac_tiempo fondo_blanco'></td>";

if (comprobarRangoEdad(144, 168, $v))
    echo"     <td class='vac_tiempo'>{$v['acronimo']}</td>";
else 
    echo"     <td class='vac_tiempo fondo_blanco'></td>";

if (comprobarRangoEdad(168, 192, $v))
    echo"     <td class='vac_tiempo'>{$v['acronimo']}</td>";
else 
    echo"     <td class='vac_tiempo fondo_blanco'></td>";

if (comprobarRangoEdad(192, 216, $v))
    echo"     <td class='vac_tiempo'>{$v['acronimo']}</td>";
else 
    echo"     <td class='vac_tiempo fondo_blanco'></td>";

if (comprobarRangoEdad(216, 600, $v))
    echo"     <td class='vac_tiempo'>{$v['acronimo']}</td>";
else 
    echo"     <td class='vac_tiempo fondo_blanco'></td>";

if (comprobarRangoEdad(600, 780, $v))
    echo"     <td class='vac_tiempo'>{$v['acronimo']}</td>";
else 
    echo"     <td class='vac_tiempo fondo_blanco'></td>";

if (comprobarRangoEdad(780, 10000, $v))
    echo"     <td class='vac_tiempo'>{$v['acronimo']}</td>";
else 
    echo"     <td class='vac_tiempo fondo_blanco'></td>";

if ($_SESSION['rol']=='A'){
    echo "<td class=' fondo_blanco'> <form action='Controlador/procesarCalendario.php' method='POST'>
    <input type='hidden' name='acronimo' value='{$v['acronimo']}' />
    <input type='submit'  name='accion' value='Ver' />
    <input type='submit'   name='accion' value='Editar' />
    <input type='submit'  name='accion' value='Borrar' /> ";

    echo "</form></td>";
}
echo "</tr>";
}
echo "</table></div>";

echo "</main>";

}









function listadoCartilla($datos, $accion){


echo <<< HTML
<div class="en_linea">
<main>
<div class='listado2'><table>
HTML;
    
    
    echo "<tr>"; if($accion=='Modificar') echo  '<th colspan="17">'; else echo  '<th colspan="16">'; echo " Historial de Vacunas del paciente por edad</th></tr>
    <tr><th rowspan='2'>Vacuna</th>  <th colspan='7'> Edad en meses </th> <th colspan='8'> Edad en años </th>"; if($accion=='Modificar') echo '<th rowspan="2">Acción</th>'; echo "</tr>
    <tr> <th>Prenatal</th> <th>0</th> <th>2</th> <th>4</th> <th>11</th> <th>12</th> <th>15</th> <th>3</th> <th>6</th> <th>12</th> <th>14</th> <th>18</th> <th>50</th> <th>65</th> <th>>65</th> </tr>";
    if ($accion=='personal')
        $_SESSION['correo_mod']=$_SESSION['usuario'];
    $fecha=obtenerFechaNacimiento();
    
    
    $fecha_nac=strtotime($fecha[0]['fnac']);
    

    foreach ($datos as $v){
    $fecha_vacuna=strtotime($v['fecha']);
    $diferencia=$fecha_vacuna-$fecha_nac;
    $diferencia=$diferencia/2592000;
    $dif_meses=intval($diferencia);
    $dif_anios=intval($diferencia/12);
    echo " <tr>   <td class='vac_nombre'>{$v['acronimo']}</td>";
    if (comprobarRangoEdadC(-30, 0, $diferencia))
        echo"     <td class='vac_tiempo'>{$dif_meses} meses</td>";
    else 
        echo"     <td class='vac_tiempo fondo_blanco'></td>";
    if (comprobarRangoEdadC(0, 2,$diferencia))
        echo"     <td class='vac_tiempo'>{$dif_meses} meses</td>";
    else 
        echo"     <td class='vac_tiempo fondo_blanco'></td>";
    if (comprobarRangoEdadC(2, 4, $diferencia))
        echo"     <td class='vac_tiempo'>{$dif_meses} meses</td>";
    else 
        echo"     <td class='vac_tiempo fondo_blanco'></td>";
    if (comprobarRangoEdadC(4, 11, $diferencia))
        echo"     <td class='vac_tiempo'>{$dif_meses} meses</td>";
    else 
        echo"     <td class='vac_tiempo fondo_blanco'></td>";
    
    if (comprobarRangoEdadC(11, 12, $diferencia))
        echo"     <td class='vac_tiempo'>{$dif_meses} meses</td>";
    else 
        echo"     <td class='vac_tiempo fondo_blanco'></td>";
    
    if (comprobarRangoEdadC(12, 15, $diferencia))
        echo"     <td class='vac_tiempo'>{$dif_meses} meses</td>";
    else 
        echo"     <td class='vac_tiempo fondo_blanco'></td>";
    
    if (comprobarRangoEdadC(15, 36, $diferencia))
        echo"     <td class='vac_tiempo'>{$dif_anios} años</td>";
    else 
        echo"     <td class='vac_tiempo fondo_blanco'></td>";
    
    
    if (comprobarRangoEdadC(36, 72,$diferencia))
        echo"     <td class='vac_tiempo'>{$dif_anios} años</td>";
    else 
        echo"     <td class='vac_tiempo fondo_blanco'></td>";  
        
    if (comprobarRangoEdadC(72, 144, $diferencia))
        echo"     <td class='vac_tiempo'>{$dif_anios} años</td>";
    else 
        echo"     <td class='vac_tiempo fondo_blanco'></td>";
    
    if (comprobarRangoEdadC(144, 168, $diferencia))
        echo"     <td class='vac_tiempo'>{$dif_anios} años</td>";
    else 
        echo"     <td class='vac_tiempo fondo_blanco'></td>";
    
    if (comprobarRangoEdadC(168, 192,$diferencia))
        echo"     <td class='vac_tiempo'>{$dif_anios} años</td>";
    else 
        echo"     <td class='vac_tiempo fondo_blanco'></td>";
    
    if (comprobarRangoEdadC(192, 216, $diferencia))
        echo"     <td class='vac_tiempo'>{$dif_anios} años</td>";
    else 
        echo"     <td class='vac_tiempo fondo_blanco'></td>";
    
    if (comprobarRangoEdadC(216, 600, $diferencia))
        echo"     <td class='vac_tiempo'>{$dif_anios} años</td>";
    else 
        echo"     <td class='vac_tiempo fondo_blanco'></td>";
    
    if (comprobarRangoEdadC(600, 800, $diferencia))
        echo"     <td class='vac_tiempo'>{$dif_anios} años</td>";
    else 
        echo"     <td class='vac_tiempo fondo_blanco'></td>";
    
    if (comprobarRangoEdadC(800, 10000, $diferencia))
        echo"     <td class='vac_tiempo'>{$dif_anios} años</td>";
    else 
        echo"     <td class='vac_tiempo fondo_blanco'></td>";
    if ($accion=='Modificar'){
        echo "<td class='fondo_claro'> <form action='Controlador/cartillaVacunacion.php' method='POST'>
            <input type='hidden' name='acronimo' value='{$v['acronimo']}' />
            <input type='submit'  name='accion' value='Modificar Vacuna' />
            </form></td>";
    }

    echo "</tr>";
    }
    echo "</table></div>";
    
    echo "</main>";
    
    }

function listadoInfoVacunas($datos,$params, $atributo, $accion){
echo <<< HTML
<div class="en_linea">
<main>
<div class='listado'><table><tr>
<th>Acrónimo</th><th>Meses inicio</th> <th>Meses fin</th></tr>
HTML;

    $fecha=obtenerFechaNacimiento();
    
    $fecha_nac=strtotime($fecha[0]['fnac']);

    $date_ = date('Y-m-d');
    $date_now=strtotime($date_);
    $meses=($date_now-$fecha_nac)/2592000;
    if ($accion=='Mostrar N vacunas' || $accion=='Mostrar vacunas futuras'){
        $contador=0;
        foreach ($datos as $v){
            if ((intval($v['meses_fin'])>= intval($meses)) && (intval($contador)<intval($params[$atributo]))){
                $contador=$contador+1;
                echo "  <tr>  <td class='vac_acro'>{$v['acronimo']}</td>";
                echo"     <td class='vac_nombre'>{$v['meses_ini']}</td>";
                echo"     <td class='vac_comentarios'>{$v['meses_fin']}</td>";
                echo "</tr>";
            }
        }
    }
    else if ($accion=='Mostrar N meses'){
        
        $date_future=$meses+intval($params[$atributo]);
        foreach ($datos as $v){
            if ($v['meses_ini']<$date_future  && $v['meses_fin']>=$meses){
                echo "  <tr>  <td class='vac_acro'>{$v['acronimo']}</td>";
                echo"     <td class='vac_nombre'>{$v['meses_ini']}</td>";
                echo"     <td class='vac_comentarios'>{$v['meses_fin']}</td>";
                echo "</tr>";
            }
        }

    }

    else {
        foreach ($datos as $v){
            if ($v['meses_fin']<$meses){
                echo "  <tr>  <td class='vac_acro'>{$v['acronimo']}</td>";
                echo"     <td class='vac_nombre'>{$v['meses_ini']}</td>";
                echo"     <td class='vac_comentarios'>{$v['meses_fin']}</td>";
                echo "</tr>";
            }
        }
    }

    
    echo "</table></div>";
    echo "</main>";
}





function showVacunas($params, $atributos, $a){
    echo "<div class='en_linea centro'>";
    echo "<main>";
    echo "<h2 class='titulo_pagina'>FORMULARIO DE INSERCIÓN DE VACUNAS EN EL SISTEMA</h2>";
    
    
    echo "<div class='en_linea centro'>";
    echo "<form action='Controlador/procesarVacuna.php' method='POST' enctype='multipart/form-data' class='margen_formulario'>";
    echo "<p>Acrónimo: <input type='text' name='acronimo' "; if ($a=='readonly') echo 'readonly'; echo " value='".$params[$atributos[0]]."'/></p>";
    if ($params[$atributos[0].'error']!='' && $params['enviado']=='true')
        echo "<p><span>Error</span>: ".$params[$atributos[0].'error']."</p>";

    echo "<p>Nombre: <input type='text' name='nombre' ";  if ($a=='readonly') echo 'readonly'; echo " value='".$params[$atributos[1]]."'/></p>";
    if ($params[$atributos[1].'error']!='' && $params['enviado']=='true')
        echo "<p><span>Error</span>: ".$params[$atributos[1].'error']."</p>";
    
    echo "<p>Descripción: <input type='text' name='descripcion' ";  if ($a=='readonly') echo 'readonly'; echo " value='".$params[$atributos[2]]."'/></p>";
    if ($params[$atributos[2].'error']!='' && $params['enviado']=='true')
        echo "<p><span>Error</span>: ".$params[$atributos[2].'error']."</p>";
    if (isset($_POST['accion'])){
        if ($a=='readonly' && $_POST['accion']=='Enviar')
            echo "<input type='submit' name='accion' value='Validar'/>";
        else if ($_POST['accion']=='Enviar')
            echo  "<input type='submit' name='accion'  value='Enviar'/>";
        else if ($_POST['accion']=='Borrar')
            echo  "<input type='submit' name='accion'  value='Confirmar Borrado'/>";
        else if ($_POST['accion']=='Editar')
            echo  "<input type='submit' name='accion'  value='Modificar'/>";
        else if ($a=='readonly' && $_POST['accion']=='Modificar')
            echo "<input type='submit' name='accion'  value='Validar datos si son correctos'/>";
        else if ($_POST['accion']=='Modificar')
            echo  "<input type='submit' name='accion'  value='Modificar'/>";
        }
        
        else 
        echo  "<input type='submit' name='accion'  value='Enviar'/>";
    echo "</form>";
    echo "<img src='images/medico.png' alt='medico' id='img_medico' >";
    echo "</main>";
}


function showAniadirCartilla($params, $atributos, $a){
    echo "<div class='en_linea centro'>";
    echo "<main>";
    echo "<h2 >FORMULARIO DE GESTIÓN DE VACUNACIÓN</h2>";
    echo "<div class='en_linea centro'>";
    echo "<form action='Controlador/cartillaVacunacion.php' method='POST' enctype='multipart/form-data'>

    <p>Email: <input type='text' name='email' readonly value='".$params[$atributos[0]]."'/></p>";
    if (isset($params[$atributos[0].'error'])){
        if ($params[$atributos[0].'error']!='' && $params['enviado']=='true')
        echo "<p><span>Error</span>: ".$params[$atributos[0].'error']."</p>";
    }
    

    echo "<p>Acrónimo: <input type='text' name='acronimo' "; if ($a=='readonly') echo 'readonly'; echo " value='".$params[$atributos[1]]."'/></p>";
    
    if (isset($params[$atributos[1].'error'])){
        if ($params[$atributos[1].'error']!='' && $params['enviado']=='true')
            echo "<p><span>Error</span>: ".$params[$atributos[1].'error']."</p>";
    }

    
    echo "<p>fecha: <input type='text' name='fecha' ";  if ($a=='readonly') echo 'readonly'; echo " value='".$params[$atributos[2]]."'/></p>";
    
    if (isset($params[$atributos[2].'error'])){
        if ($params[$atributos[2].'error']!='' && $params['enviado']=='true')
            echo "<p><span>Error</span>: ".$params[$atributos[2].'error']."</p>";
    }

    echo "<p>Fabricante: <input type='text' name='fabricante' ";  if ($a=='readonly') echo 'readonly'; echo " value='".$params[$atributos[3]]."'/></p>";

    if (isset($params[$atributos[3].'error'])){
        if ($params[$atributos[3].'error']!='' && $params['enviado']=='true')
            echo "<p><span>Error</span>: ".$params[$atributos[3].'error']."</p>";
    }
    echo "<p>Comentarios: <input type='text' name='comentarios' ";  if ($a=='readonly') echo 'readonly'; echo " value='".$params[$atributos[4]]."'/></p>";

    if (isset($params[$atributos[4].'error'])){
        if ($params[$atributos[4].'error']!='' && $params['enviado']=='true')
            echo "<p><span>Error</span>: ".$params[$atributos[4].'error']."</p>";
    }
    if (isset($_POST['accion'])){
        if ($_POST['accion']=='Modificar Vacuna')
            echo  "<input type='submit' name='accion'  value='Enviar'/>";
        else if ($a=='readonly' && $_POST['accion']=='Enviar')
            echo "<input type='submit' name='accion' value='Validar'/>";
        else if ($_POST['accion']=='Enviar')
            echo  "<input type='submit' name='accion'  value='Enviar'/>";
        else if ($a=='readonly' && $_POST['accion']=='Añadir Vacuna')
            echo "<input type='submit' name='accion' value='Validar si son correctos'/>";
        else if ($_POST['accion']=='Añadir Vacuna')
            echo "<input type='submit' name='accion' value='Añadir Vacuna'/>";
    }
        
    echo "</form>";
    echo "<img src='images/medico.png' alt='medico' id='img_medico' >";
    echo "</div>";
    echo "</main>";
}


function showCalendario ($params, $atributos, $a){
    echo "<div class='en_linea centro'>";
    echo "<main>";
    echo "<h2 class='titulo_pagina'>FORMULARIO DE INSERCIÓN DE VACUNA EN CALENDARIO</h2>";
    echo "<div class='en_linea centro'>";
    echo "<form action='Controlador/procesarCalendario.php' method='POST' enctype='multipart/form-data'>";
    echo "<p>Acrónimo: <input type='text' name='acronimo' "; if ($a=='readonly') echo 'readonly'; echo " value='".$params[$atributos[0]]."'/></p>";
    if ($params[$atributos[0].'error']!='' && $params['enviado']=='true')
        echo "<p><span>Error</span>: ".$params[$atributos[0].'error']."</p>";

    echo "<p>Sexo: <label><input type='radio' name='sexo'  value='Masculino' ";if ($params[$atributos[1]]=='Masculino') echo ' checked';  if ( (($params[$atributos[1]]=='Femenino') || ($params[$atributos[1]]=='Todos')) && ($a=='readonly') ) echo 'disabled';
    echo ">Masculino</label>";

    echo "<label><input type='radio' name='sexo' value='Femenino' ";if ($params[$atributos[1]]=='Femenino') echo ' checked'; if ((($params[$atributos[1]]=='Masculino')|| ($params[$atributos[1]]=='Todos')) && ($a=='readonly') ) echo 'disabled';
    echo ">Femenino</label>
    <label><input type='radio' name='sexo'  value='Todos' ";if ($params[$atributos[1]]=='Todos') echo ' checked';  if ((($params[$atributos[1]]=='Femenino') || ($params[$atributos[1]]=='Masculino')) && ($a=='readonly') ) echo 'disabled';
    echo ">Todos</label>
    </p>";
    if ($params[$atributos[1].'error']!='' && $params['enviado']=='true')
        echo "<p><span>Error</span>: ".$params[$atributos[1].'error']."</p>";

    echo "<p>Meses de inicio de Vacunación: <input type='text' name='meses_ini' ";  if ($a=='readonly') echo 'readonly'; echo " value='".$params[$atributos[2]]."'/></p>
    <p>Meses de fin de Vacunación: <input type='text' name='meses_fin' ";  if ($a=='readonly') echo 'readonly'; echo " value='".$params[$atributos[3]]."'/></p>";
    if ($params[$atributos[2].'error']!='' && $params['enviado']=='true')
        echo "<p><span>Error</span>: ".$params[$atributos[2].'error']."</p>";
    
    echo "<p>Tipo de vacuna: <label><input type='radio' name='tipo'  value='Sistemica' ";if ($params[$atributos[4]]=='Sistemica') echo ' checked';  if (($params[$atributos[4]]=='Susceptible')  && ($a=='readonly') ) echo 'disabled';
    echo ">Sistemica</label>
    <label><input type='radio' name='tipo' value='Susceptible' ";if ($params[$atributos[4]]=='Susceptible') echo ' checked'; if (($params[$atributos[4]]=='Sistemica') && ($a=='readonly') ) echo 'disabled';
    echo ">Susceptible</label></p>";
    if ($params[$atributos[4].'error']!='' && $params['enviado']=='true')
        echo "<p><span>Error</span>: ".$params[$atributos[4].'error']."</p>";
    
    echo "<p>Comentarios: <input type='search' name='comentarios' class='placeholder_grande' ";  if ($a=='readonly') echo 'readonly'; echo " value='".$params[$atributos[5]]."'/></p>";
    if ($params[$atributos[5].'error']!='' && $params['enviado']=='true')
        echo "<p><span>Error</span>: ".$params[$atributos[5].'error']."</p>";
    
    if (isset($_POST['accion'])){
        if ($a=='readonly' && $_POST['accion']=='Enviar')
            echo "<input type='submit' name='accion' value='Validar'/>";
        else if ($_POST['accion']=='Enviar')
            echo  "<input type='submit' name='accion'  value='Enviar'/>";
        else if ($_POST['accion']=='Borrar')
            echo  "<input type='submit' name='accion'  value='Confirmar Borrado'/>";
        else if ($_POST['accion']=='Editar')
            echo  "<input type='submit' name='accion'  value='Modificar'/>";
        else if ($a=='readonly' && $_POST['accion']=='Modificar')
            echo "<input type='submit' name='accion'  value='Validar datos si son correctos'/>";
        else if ($_POST['accion']=='Modificar')
            echo  "<input type='submit' name='accion'  value='Modificar'/>";
        }
        
        else 
        echo  "<input type='submit' name='accion'  value='Enviar'/>";
    echo "</form>";
    echo "<img src='images/calendario.png' alt='calendario' id='img_medico' >";
    echo "</div>";
    echo "</main>";
}


function showForm($params, $atributos, $a) {
    echo "<div class='en_linea centro'>";
    echo "<main>";
    echo "<h2 class='titulo_pagina'>FORMULARIO DE GESTIÓN DE USUARIOS EN EL SISTEMA</h2>";
    echo "<form action='Controlador/procesar.php' method='POST' enctype='multipart/form-data'>";
        echo "<div class='en_linea'>";
        
        if (isset($_SESSION['imagen'])){
          if($_SESSION['imagen']!='') 
          echo "<img src='data:image/png;base64,".base64_encode($_SESSION['imagen'])."' class='imagen_usuario_formulario'>";
          else {
            if (isset($_POST['accion'])){
              if ($_POST['accion']=='Ver' || $_POST['accion']=='Editar' || $_POST['accion']=='Borrar')
              echo "<img src='data:image/png;base64,".base64_encode($_SESSION['imagen'])."' class='imagen_usuario_formulario'>";
              else 
                echo "<img src='images/cara.png' alt='cara' id='img_cara' >";
            }
            else 
              echo "<img src='images/cara.png' alt='cara' id='img_cara' >";
          }
        }
              
        else echo "<img src='images/cara.png' alt='cara' id='img_cara' >";
        echo "<div class='margen_izquierdo'><p>Fotografia: </p><label for='fich' > </label> <input type='file'    ";if ($a=='readonly') echo 'disabled' ; echo  " name='fichero' id='fich'></div></div>";
        if ($params[$atributos[11].'error']!='' && $params['enviado']=='true')
        echo "<p><span>Error</span>: ".$params[$atributos[11].'error']."</p>";

        echo "<p>Nombre: <input type='text' name='nombre' ";if ($a=='readonly'|| $a=='readeditar') echo 'readonly'; echo " value='".$params[$atributos[0]]."'/></p>";
        if ($params[$atributos[0].'error']!='' && $params['enviado']=='true')
        echo "<p><span>Error</span>: ".$params[$atributos[0].'error']."</p>";
        
        echo "<p>Apellidos: <input type='text' name='apellidos' "; if ($a=='readonly'|| $a=='readeditar') echo 'readonly'; echo " value='".$params[$atributos[1]]."'/></p>";
        if ($params[$atributos[1].'error']!='' && $params['enviado']=='true')
        echo "<p><span>Error</span>: ".$params[$atributos[1].'error']."</p>";
        
        echo "<p>DNI (NNNNNNNNL) : <input type='text' name='dni' ";  if ($a=='readonly'|| $a=='readeditar') echo 'readonly'; echo " value='".$params[$atributos[2]]."'/></p>";
        if ($params[$atributos[2].'error']!='' && $params['enviado']=='true')
        echo "<p><span>Error</span>: ".$params[$atributos[2].'error']."</p>";
        
        echo "<p>Email: <input type='text' name='email' "; if ($a=='readonly') echo 'readonly'; echo "  value='".$params[$atributos[3]]."'/></p>";
        if ($params[$atributos[3].'error']!='' && $params['enviado']=='true')
        echo "<p><span>Error</span>: ".$params[$atributos[3].'error']."</p>";
        
        echo" <p>Telefono: <input type='text' name='telefono' ";if ($a=='readonly') echo 'readonly'; echo "   value='".$params[$atributos[4]]."'/></p>";
        if ($params[$atributos[4].'error']!='' && $params['enviado']=='true')
        echo "<p><span>Error</span>: ".$params[$atributos[4].'error']."</p>";

        echo "<p>Fecha nac (YYYY-MM-DD): <input type='text' name='fecha_nac' ";if ($a=='readonly'|| $a=='readeditar') echo 'readonly'; echo "   value='".$params[$atributos[5]]."'/></p>";
        if (($params[$atributos[5].'error']!='')  && ($params['enviado']=='true'))
        echo "<p><span>Error</span>: ".$params[$atributos[5].'error']."</p>";
        
        echo "<p>Sexo: <label><input type='radio' name='sexo'  value='Masculino' ";if ($params[$atributos[6]]=='Masculino') echo ' checked';  if ($params[$atributos[6]]=='Femenino' && ($a=='readonly'|| $a=='readeditar'))  echo 'disabled';
        echo ">Masculino</label>
        <label><input type='radio' name='sexo' value='Femenino' ";if ($params[$atributos[6]]=='Femenino') echo ' checked'; if ($params[$atributos[6]]=='Masculino' && ($a=='readonly'|| $a=='readeditar'))  echo 'disabled';
        echo ">Femenino</label></p>";
        if ($params[$atributos[6].'error']!='' && $params['enviado']=='true')
        echo "<p><span>Error</span>: ".$params[$atributos[6].'error']."</p>"; 

        echo "<p> Clave <input type='password' "; if ($a=='readonly') echo 'readonly'; echo " name='contrasenia1' value='".$params[$atributos[7]]."'/> <input type='password' "; if ($a=='readonly') echo 'readonly'; echo " name='contrasenia2' value='".$params[$atributos[8]]."'/></p>";
        if ($params[$atributos[7].'error']!=''  && $params['enviado']=='true')
        echo "<p><span>Error</span>: ".$params[$atributos[7].'error']."</p>"; 


        if (isset($_SESSION['rol'])){
            if ($_SESSION['rol']=='A'){
                echo "<p>
                <label for='rol'>Rol: </label>
                <select name='rol' id='rol'>
                <option   "; if (($a=='readonly' ) && ($params[$atributos[9]]=='Paciente' || $params[$atributos[9]]=='Sanitario')) echo 'disabled'; echo " value='Administrador' ";if ($params[$atributos[9]]=='Administrador') echo ' selected';
                echo ">Administrador</option>
                <option   "; if ($a=='readonly' && ($params[$atributos[9]]=='Administrador' || $params[$atributos[9]]=='Sanitario')) echo 'disabled'; echo " value='Paciente' ";if ($params[$atributos[9]]=='Paciente') echo ' selected';
                echo ">Paciente</option>
                <option   "; if ($a=='readonly' && ($params[$atributos[9]]=='Administrador' || $params[$atributos[9]]=='Paciente')) echo 'disabled'; echo " value='Sanitario' ";if ($params[$atributos[9]]=='Sanitario') echo ' selected';
                echo ">Sanitario</option>
                </select>
                </p>";
                if ($params[$atributos[9].'error']!='')
                echo "<p><span>Error</span>: ".$params[$atributos[9].'error']."</p>"; 
                echo "<p>
                <label for='estado'>Estado: </label>
                <select name='estado' id='estado'>
                <option   "; if ($a=='readonly' && $params[$atributos[10]]=='Inactivo') echo 'disabled';echo " value='Activo' ";if ($params[$atributos[10]]=='Activo') echo ' selected';
                echo ">Activo</option>
                <option   "; if ($a=='readonly' && $params[$atributos[10]]=='Activo') echo 'disabled';echo " value='Inactivo' ";if ($params[$atributos[10]]=='Inactivo') echo ' selected';
                echo ">Inactivo</option>
                </select>
                </p>";
                if ($params[$atributos[10].'error']!='' && $params['enviado']=='true')
                echo "<p><span>Error</span>: ".$params[$atributos[10].'error']."</p>"; 
            }
        }
    
    
        
    if (isset($_POST['accion'])){
      if ($_POST['accion']=='Activar'){
        echo "<input type='submit' name='accion' value='Activar e Informar'/>";
        echo "<input type='submit' name='accion' value='Informar de error'/>";
        echo "<input type='submit' name='accion' value='Confirmar Borrado'/>";
      }
         
      else if ($a=='readonly' && $_POST['accion']=='Enviar')
        echo "<input type='submit' name='accion' value='Validar'/>";
      else if ($_POST['accion']=='Enviar')
        echo  "<input type='submit' name='accion'  value='Enviar'/>";
      else if ($_POST['accion']=='Borrar')
        echo  "<input type='submit' name='accion'  value='Confirmar Borrado'/>";
      else if ($_POST['accion']=='Editar')
        echo  "<input type='submit' name='accion'  value='Modificar'/>";
      else if ($a=='readonly' && $_POST['accion']=='Modificar')
        echo "<input type='submit' name='accion'  value='Validar datos si son correctos'/>";
      else if ($_POST['accion']=='Modificar')
        echo  "<input type='submit' name='accion'  value='Modificar'/>";
    }
      
    else 
      echo  "<input type='submit' name='accion'  value='Enviar'/>";
    echo "</form>";
    echo "</main>";
  }



function mensajeUsuarioBorrado($nombre, $apellidos){
    echo "<div class='en_linea'><main>
    <p>El usuario ".$nombre." ".$apellidos. " ha sido borrado del sistema correctamente </p>
    </main>";
    $_SESSION['imagen']='';
}

function mensajeUsuarioNoBorrado($nombre, $apellidos){
    echo "<div class='en_linea'><main>
    <p>El usuario ".$nombre." ".$apellidos. " no ha podido ser borrado del sistema correctamente </p>
    </main>";
}

function mensajeUsuarioInsertado($params, $atributos){
    echo "<div class='en_linea'><main>
    <p>El usuario ".$params[$atributos[0]]." ".$params[$atributos[1]]. " ha sido insertado en el sistema correctamente </p>
    </main>";
    $_SESSION['imagen']='';
}

function mensajeUsuarioNoInsertado($info){
    echo "<div class='en_linea'><main>
    <p>".$info[0]." </p>
    </main>";
}



function mensajeUsuarioSolicitar($params, $atributos){
    echo "<div class='en_linea'><main>
    <p>El usuario ".$params[$atributos[0]]." ".$params[$atributos[1]]. " ha solicitado activación a los administradores del sistema correctamente </p>
    </main>";
    $_SESSION['imagen']='';
}

function mensajeUsuarioNoSolicitar($info){
    echo "<div class='en_linea'><main>
    <p>".$info[0]." </p>
    </main>";
}

function mensajeUsuarioEditado($params, $atributos){
    echo "<div class='en_linea'><main>
    <p>El usuario ".$params[$atributos[0]]." ".$params[$atributos[1]]. " ha sido editado en el sistema correctamente </p>
    </main>";
    $_SESSION['imagen']='';
}
  
function mensajeUsuarioNoEditado($info){
    echo "<div class='en_linea'><main>
    <p>".$info[0]." </p>
    </main>";
}

function mensajeUsuarioActivado($params, $atributos){
    echo "<div class='en_linea'><main>
    <p>El usuario ".$params[$atributos[0]]." ".$params[$atributos[1]]. " ha sido activado en el sistema correctamente </p>
    </main>";
    $_SESSION['imagen']='';
}

function mensajeUsuarioNoActivado($info){
    echo "<div class='en_linea'><main>
    <p>".$info[0]." </p>
    </main>";
}

function mensajeVacunaInsertada($params, $atributos){
    echo "<div class='en_linea'><main>
    <p>La vacuna con acrónimo ".$params[$atributos[0]]." y nombre ".$params[$atributos[1]]. " ha sido insertado en el sistema correctamente </p>
    </main>";
}

function mensajeVacunaNoInsertada($info){
    echo "<div class='en_linea'><main>
    <p>".$info[0]." </p>
    </main>";
}

function mensajeVacunaBorrada($acronimo, $nombre){
    echo "<div class='en_linea'><main>
    <p>La vacuna cuyo acrónimo es ".$acronimo." y nombre es ".$nombre. " ha sido borrada del sistema correctamente </p>
    </main>";
}

function mensajeVacunaNoBorrada($nombre, $apellidos){
    echo "<div class='en_linea'><main>
    <p>La vacuna cuyo acrónimo es ".$acronimo." y nombre es ".$nombre. " NO ha podido ser borrada del sistema correctamente </p>
    </main>";
}

function mensajeVacunaEditada($params, $atributos){
    echo "<div class='en_linea'><main>
    <p>La vacuna con acrónimo ".$params[$atributos[0]]." y nombre ".$params[$atributos[1]]. " ha sido editada en el sistema correctamente </p>
    </main>";
    $_SESSION['imagen']='';
}
  
function mensajeVacunaNoEditada($info){
    echo "<div class='en_linea'><main>
    <p>".$info[0]." </p>
    </main>";
}

function mensajeCalendarioInsertado($params, $atributos){
    echo "<div class='en_linea'><main>
    <p>La vacuna con acrónimo ".$params[$atributos[0]]." se ha sido insertado en el sistema correctamente en el calendario de vacunación</p>
    </main>";
}

function mensajeCalendarioNoInsertado($info){
    echo "<div class='en_linea'><main>
    <p>".$info[0]." </p>
    </main>";
}

function mensajeCalendarioBorrado($acronimo){
    echo "<div class='en_linea'><main>
    <p>La vacuna cuyo acrónimo es ".$acronimo." ha podido ser borrada del calendario de vacunación correctamente </p>
    </main>";
}

function mensajeCalendarioNoBorrado($info){
    echo "<div class='en_linea'><main>
    <p>".$info[0]." </p>
    </main>";
}

function mensajeCalendarioEditado($params, $atributos){
    echo "<div class='en_linea'><main>
    <p>La vacuna con acrónimo ".$params[$atributos[0]]."  ha sido editada en el calendario de vacunación correctamente </p>
    </main>";
}

function mensajeCalendarioNoEditado($info){
    echo "<div class='en_linea'><main>
    <p>".$info[0]." </p>
    </main>";
}

function mensajeCartillaModificada($params, $atributos){
    echo "<div class='en_linea'><main>
    <p>La vacuna con acrónimo ".$params[$atributos[1]]." del usuario ".$_SESSION['correo_mod']." ha sido modificada en la cartilla de vacunación correctamente </p>
    </main>";
}

function mensajeCartillaNoModificada($info){
    echo "<div class='en_linea'><main>
    <p>".$info[0]." </p>
    </main>";
}

function mensajeCartillaAniadida($params, $atributos){
    echo "<div class='en_linea'><main>
    <p>La vacuna con acrónimo ".$params[$atributos[1]]."  ha sido insertada en la cartilla de vacunación del usuario ".$_SESSION['correo_mod']." correctamente </p>
    </main>";
}


function mensajeCartillaNoAniadida($info){
    echo "<div class='en_linea'><main>
    <p>".$info[0]." </p>
    </main>";
}

function borradoTablasCorrecto(){
    echo "<div class='en_linea'><main>
    <p>El borrado de todas las tablas de la base de datos (excepto el usuario logueado que ha ejecutado la orden) ha sido realizado con éxito</p>
    </main>";
}

function mensajeRestoreCorrecto(){
    echo "<div class='en_linea'><main>
    <p>Se ha realizado el Restore de las tablas del sistema correctamente</p>
    </main>";
}

function mensajeUsuarioInformarError($email){
    
    echo "<div class='en_linea'><main>
    <p>El usuario con email ".$email." no ha sido activado por el usuario ".$_SESSION['usuario']." en el sistema debido a un error y se le ha notificado de este</p>
    </main>";
}

function mensajeNoPacientes(){
    echo "<div class='anchura'>
    <p>No se han encontrado pacientes con esos criterios de búsqueda</p>
    </main>";
}




function listadoUsuarios($datos, $accion){

if ($accion=='listadoPacientes2')
echo "<div>";
else {
    echo "<div class='en_linea'>
    <main>";
}
echo <<<HTML
<div class='listado'><table><tr>
<th>Fotografia</th><th>Nombre</th> <th>Apellidos</th> <th>Email</th><th>Rol</th><th>Estado</th> <th>Acción</th></tr>
HTML;
foreach ($datos as $v){
    if ($_SESSION['rol']!='P' || $v['email']==$_SESSION['usuario']){
        if ($_SESSION['rol']!='S' || $v['rol']=='P'){

            /*echo "<img src='data:image/png;base64,".base64_encode($_SESSION['foto_usuario'])."' class='imagen_usuario'>";*/
            echo'   <tr> <td>' .'<img src="data:image/png;base64,'.$v['Fotografia'].'" class="imagen_usuario_lista">'.'</td>';
            echo " <td >{$v['nombre']}</td>";
            echo"     <td>{$v['apellidos']}</td>";
            echo"     <td>{$v['email']}</td>";
            if ($v['rol']=='P')
                echo"     <td class='vac_rol'>Paciente</td>";
            else if ($v['rol']=='S')
                echo"     <td class='vac_rol'>Sanitario</td>";
            else 
                echo"     <td class='vac_rol'>Administrador</td>";
        
            if ($v['estado']=='A')
                echo"     <td class='vac_estado'>Activo</td>";
            else 
                echo"     <td class='vac_estado'>Inactivo</td>";
        
            echo "<td> <form action='Controlador/procesar.php' method='POST'>
            <input type='hidden' name='Email' value='{$v['email']}' />
            <input type='submit'  name='accion' value='Ver' />
            <input type='submit'   name='accion' value='Editar' />";
            if ($v['email']!=$_SESSION['usuario'])
                echo "<input type='submit'  name='accion' value='Borrar' /> ";
            if ($v['estado']=='I')
                echo "<input type='submit' name='accion' value='Activar'/>";
            echo "</form></td>";
            echo "</tr>";
        }
    }
}
echo "</table></div>";
if ($accion!='listadoPacientes2')
    echo "</main>";

}

function listadoVacunas($datos, $accion){
echo <<< HTML
<div class="en_linea">
<main>
<div class='listado'><table><tr>
<th>Acrónimo</th><th>Nombre</th> <th>Descripción</th> <th>Acción</th> </tr>
HTML;
    foreach ($datos as $v){
     
    echo "  <tr>  <td class='vac_acro'>{$v['acronimo']}</td>";
    echo"     <td class='vac_nombre'>{$v['nombre']}</td>";
    echo"     <td class='vac_comentarios'>{$v['descripcion']}</td>";

    echo "<td> <form action='Controlador/procesarVacuna.php' method='POST'>
    <input type='hidden' name='acronimo' value='{$v['acronimo']}' />
    <input type='submit' name='accion' value='Ver' />
    <input type='submit' name='accion' value='Editar' />
    <input type='submit' name='accion' value='Borrar' /> 
    </form></td>";
    echo "</tr>";
    }
    
    echo "</table></div>";
    echo "</main>";
    
}


function listadoLogs($datos){
echo <<< HTML
<div class="en_linea">

<main>
<div class='listado'><table><tr>
<th>Fecha</th><th>Descripción</th>  </tr>
HTML;
        foreach ($datos as $v){
         
        echo " <tr><td>{$v['fecha']}</td>";
        echo" <td>{$v['descripcion']}</td>";
        echo "</tr>";
        }
        
        echo "</table></div>";
        echo "</main>";
        
}

function listado_pag_logs($datos) {
echo <<< HTML
<div class="en_linea2">
<div class="no_margen">
<main class="mainl">
<div class='listado no_margen'><table><tr>
<th>Fecha</th>
<th>Descripcion</th>

</tr>
HTML;
    
    foreach ($datos as $v) {
      echo '<tr>';
      echo '<td>'.htmlentities($v['fecha']).'</td>';
      echo '<td>'.htmlentities($v['descripcion']).'</td>';
      echo '</tr>';
    }
    
    echo "</table></div>";
    echo "</main>";
}

function listado_pag_vacunas($datos){
echo <<< HTML
<div class="en_linea2">
<div class="no_margen">
<main class="mainl">
<div class='listado no_margen'><table><tr>
<th>Acrónimo</th><th>Nombre</th> <th>Descripción</th> <th>Acción</th> </tr>
HTML;
    foreach ($datos as $v){
     
    echo "  <tr>  <td class='vac_acro'>{$v['acronimo']}</td>";
    echo"     <td class='vac_nombre'>{$v['nombre']}</td>";
    echo"     <td class='vac_comentarios'>{$v['descripcion']}</td>";

    echo "<td> <form action='Controlador/procesarVacuna.php' method='POST'>
    <input type='hidden' name='acronimo' value='{$v['acronimo']}' />
    <input type='submit' name='accion' value='Ver' />
    <input type='submit' name='accion' value='Editar' />
    <input type='submit' name='accion' value='Borrar' /> 
    </form></td>";
    echo "</tr>";
    }
    
    echo "</table></div>";
    echo "</main>";
}

function listado_pag_usuarios($datos, $accion){

if ($accion!='listadoPacientes2'){
    echo "<div class='en_linea'>";
    echo "<main>";
}

else 
echo "<div class='anchura'>";
echo <<< HTML

<div class='listado no_margen'><table><tr>
<th>Fotografia</th><th>Nombre</th> <th>Apellidos</th> <th>Email</th><th>Rol</th><th>Estado</th> <th>Acción</th></tr>
HTML;
foreach ($datos as $v){
    
        
            echo'   <tr> <td>' .'<img src="data:image/png;base64, '.$v['Fotografia']. '"width="50px" height="50px"/>'.'</td>';
            echo " <td>{$v['nombre']}</td>";
            echo"     <td>{$v['apellidos']}</td>";
            echo"     <td>{$v['email']}</td>";
            if ($v['rol']=='P')
                echo"     <td class='vac_rol'>Paciente</td>";
            else if ($v['rol']=='S')
                echo"     <td class='vac_rol'>Sanitario</td>";
            else 
                echo"     <td class='vac_rol'>Administrador</td>";
        
            if ($v['estado']=='A')
                echo"     <td class='vac_estado'>Activo</td>";
            else 
                echo"     <td class='vac_estado'>Inactivo</td>";
            if ($_SESSION['rol']=='A' && $v['estado']=='A'){
                echo "<td> <form action='Controlador/procesar.php' method='POST'>
                <input type='hidden' name='Email' value='{$v['email']}' />
                <input type='submit'  name='accion' value='Ver' />
                <input type='submit'   name='accion' value='Editar' />";
                if ($v['email']!=$_SESSION['usuario'])
                    echo "<input type='submit'  name='accion' value='Borrar' /> ";
                
                    
                echo "</form>";
            }
            else if ($_SESSION['rol']=='A' && $v['estado']=='I'){
                echo "<td> <form action='Controlador/procesar.php' method='POST'>";
                echo "<input type='hidden' name='Email' value='{$v['email']}' />";
                echo "<input type='submit' name='accion' value='Activar'/>";
                echo "</form>";
            }
            else if ($_SESSION['rol']=='S' && $v['estado']=='A'){
                echo "<td> <form action='Controlador/cartillaVacunacion.php' method='POST'>
                <input type='hidden' name='Email' value='{$v['email']}' />
                <input type='submit'  name='accion' value='Ver Cartilla' />
                <input type='submit'   name='accion' value='Modificar Cartilla' />
                <input type='submit'   name='accion' value='Añadir Vacuna' />";
                echo "</form>";
            }
            else if ($_SESSION['rol']=='S' && $v['estado']=='I'){
                echo " <td><form action='Controlador/procesar.php' method='POST'>
                <input type='hidden' name='Email' value='{$v['email']}' />";
                echo "<input type='submit' name='accion' value='Activar'/>";
                echo "</form>";
            }
            echo "</td>";
            
            echo "</tr>";
        
    
}
echo "</table></div>";

}

function informacionUsuario($datos){
echo <<< HTML
<div class="en_linea">
<main>
<div class='listado'><table><tr>
<th>Fotografia</th><th>Nombre</th> <th>Apellidos</th> <th>Email</th><th>Rol</th><th>Estado</th> <th>Acción</th></tr>
HTML;
    foreach ($datos as $v){
     
        echo'   <tr> <td>' .'<img src="data:image/png;base64, '.$v['Fotografia']. '"width="50px" height="50px"/>'.'</td>';
        echo " <td>{$v['nombre']}</td>";
        echo"     <td>{$v['apellidos']}</td>";
        echo"     <td>{$v['email']}</td>";
        if ($v['rol']=='P')
            echo"     <td class='vac_rol'>Paciente</td>";
        else if ($v['rol']=='S')
            echo"     <td class='vac_rol'>Sanitario</td>";
        else 
            echo"     <td class='vac_rol'>Administrador</td>";
    
        if ($v['estado']=='A')
            echo"     <td class='vac_estado'>Activo</td>";
        else 
            echo"     <td class='vac_estado'>Inactivo</td>";
    
        echo "<td> <form action='Controlador/procesar.php' method='POST'>
        <input type='hidden' name='Email' value='{$v['email']}' />
        <input type='submit'  name='accion' value='Ver' />
        <input type='submit'   name='accion' value='Editar' />";
        if ($v['email']!=$_SESSION['usuario'])
            echo "<input type='submit'  name='accion' value='Borrar' /> ";
        if ($v['estado']=='I')
            echo "<input type='submit' name='accion' value='Activar'/>";
        echo "</form></td>";
        echo "</tr>";
    }
    
    echo "</table></div>";
    echo "</main>";
}


function gestionBD(){
echo <<< HTML
<div class="en_linea">
<main>
<h2 class='titulo_pagina'>ACCIONES PARA LA GESTIÓN DE LA BASE DE DATOS</h2>
<div class="en_linea centro">
    <div>
<form action='Controlador/restore.php' method='POST' enctype='multipart/form-data'>
<div >
<p>Restaurar la base de datos del sistema</p>
<label for='fichero'>Seleccione el fichero .sql que desea importar: </label>
<input type='file' name='fichero' />
</div>
<div class='margen_formulario'>
<input type='submit' name='submit' value='Subir' />
</div>
</form>

<form action='Controlador/backup.php' method='POST' class="margen_formulario2">
<p>Copia de seguridad: 
<input type='submit' name='accion' value='Backup'/> </p>
</form>

<form action='Controlador/borrar.php' method='POST' class="margen_formulario2">
<p>Borrado de todas las tablas (menos el usuario administrador que ejecuta la orden): 
<input type='submit' name='accion' value='Borrar'/> </p>
</form>
</div>
<img src="images/database.png" alt="database" id="img_vacuna" >
</div>
</main>
HTML;
    
}

function htmlNavpaginado($clase,$menu,$accion, $activo='' ) {
    echo "<nav id='nav2'>";
    foreach ($menu as $elem){
        echo "<a class='negro' ".($activo==$elem['texto']?"class='activo' ":'')."href='{$elem['url']}'>{$elem['texto']}</a>";
        if (end($menu)!=$elem)
            echo "|";
    }
      
    echo '</nav>';
    if ($accion=='listadoUsuarios')
        echo '</main>';
    
        
    else 
        echo '</div>';
  }

function htmlNavpaginado2($clase,$menu,$activo='') {
    
    echo "<nav id='nav2'>";
    foreach ($menu as $elem){
        echo "<a class='negro' ".($activo==$elem['texto']?"class='activo' ":'')."href='{$elem['url']}'>{$elem['texto']}</a>";
        if (end($menu)!=$elem)
            echo "|";
    }
        
    echo '</nav>';
    echo '</div>';
    echo '</main>';
}

function HTMLfooter() {
echo <<< HTML
<footer>
<small>
<ul class="sin_puntos en_linea centro ">
<li>2020 Tecnologías Web</li>
<li>|</li>
<li>David Armenteros Soto e Iñaki Melguizo Marcos</li>
<li>|</li>
<li>daarso98@correo.ugr.es, imm98@correo.ugr.es</li>
<li>|</li>
<li><a class='negro' href="documentacion.pdf">Ver documentación </a> </li>
</ul></small>
</footer>
HTML;
}




function buscarPacientes($datos=false) {
    $nombre = isset($datos['nombre']) ? " value='{$datos['nombre']}' " : '';
    $apellidos = isset($datos['apellidos']) ? " value='{$datos['apellidos']}' " : '';
    $dni = isset($datos['dni']) ? " value='{$datos['dni']}' " : '';
    $fechamin = isset($datos['fechamin']) ? " value='{$datos['fechamin']}' " : '';
    $fechamax = isset($datos['fechamax']) ? " value='{$datos['fechamax']}' " : '';
    
    $numSemanas= isset($datos['numSemanas']) ? " value='{$datos['numSemanas']}' " : '';
echo <<< HTML
<div class="en_linea">
<main class="mainl en_linea">
<div class='div_param_busqueda'>

<form action='Controlador/listadoPacientes2.php' method='POST' enctype='multipart/form-data'>
HTML;
/*
<p>
<label for='nombre'>Nombre:</label>
<input type='text' name='nombre' $nombre/>
</p>
HTML;

value='.$_COOKIE['nombre'].'*/
$nombre='nombre';
echo "
<p>
<label for='nombre'>Nombre:</label>
<input type='text' name='nombre' "; if (isset($_COOKIE['nombre'])) echo "value='$_COOKIE[$nombre]'"; echo " />
</p>";
$apellidos='apellidos';

echo "<p>
<label for='apellidos'>Apellidos:</label>
<input type='text' name='apellidos' "; if (isset($_COOKIE['apellidos'])) echo "value='$_COOKIE[$apellidos]'"; echo "/>
</p>";
$dni='dni';
echo "<p>
<label for='dni'>DNI (NNNNNNNL):</label>
<input type='text' name='dni' "; if (isset($_COOKIE['dni'])) echo "value='$_COOKIE[$dni]'"; echo "/>
</p>";
$fechamin='fechamin';
echo "<p>
<label for='fechamin'>Fecha mínima (YYYY-MM-DD):</label>
<input type='text' name='fechamin'  "; if (isset($_COOKIE['fechamin'])) echo "value='$_COOKIE[$fechamin]'"; echo "/>
</p>";
$fechamax='fechamax';
echo "<p>
<label for='fechamax'>Fecha tope (YYYY-MM-DD):</label>
<input type='text' name='fechamax'  "; if (isset($_COOKIE['fechamax'])) echo "value='$_COOKIE[$fechamax]'"; echo "/>
</p>
<div>
";

$estado='estado';

echo "
<p>Estado:
<label><input type='radio' name='estado' "; if (isset($_COOKIE['estado']))if ($_COOKIE['estado']=='Activo') echo 'checked'  ;echo " value='Activo' >Activo</label>
<label><input type='radio' name='estado' value='Inactivo'  ";if (isset($_COOKIE['estado']))if ($_COOKIE['estado']=='Inactivo') echo 'checked' ; echo "  >Inactivo</label>
<label><input type='radio' name='estado' value='todos'  "; if ($datos['estado']!='Activo' && $datos['estado']!='Inactivo') echo 'checked'; echo "  >Todos</label>
</p></div>";
$numSemanas='numSemanas';
echo "<p>Pacientes con vacunas pendientes: <div></p>
<p><label><input type='radio' name='pendientes' "; if (isset($_COOKIE['pendientes']))if ($_COOKIE['pendientes']=='VacPendientes') echo 'checked'  ; echo " value='VacPendientes' >Vacunas pendientes</label></p>
<p><label><input type='radio' name='pendientes' "; if ($datos['pendientes']!='VacPendientes') echo 'checked'; echo " value='todos' >Todos los pacientes</label></p>
</p>
</p></div>
<p>
<label for='numSemanas'>numSemanas:</label>
<input type='text' name='numSemanas' "; if (isset($_COOKIE[$numSemanas])) echo "value='$_COOKIE[$numSemanas]'"; echo "/>
</p>


<p>Ordenacion por: <div></p>
<p><label><input type='radio' name='ordenacion' ";if (isset($_COOKIE['ordenacion']))if ($_COOKIE['ordenacion']=='nombre') echo 'checked'  ; echo " value='nombre' >Nombre</label></p>
<p><label><input type='radio' name='ordenacion' "; if (isset($_COOKIE['ordenacion']))if ($_COOKIE['ordenacion']=='apellido') echo 'checked' ; echo " value='apellido' >Apellido</label></p>
<p><label><input type='radio' name='ordenacion' ";if (isset($_COOKIE['ordenacion']))if ($_COOKIE['ordenacion']=='mayoramenor') echo 'checked' ; echo " value='mayoramenor' >Edad mayor a menor</label></p>
<p><label><input type='radio' name='ordenacion' ";if (isset($_COOKIE['ordenacion']))if ($_COOKIE['ordenacion']=='menoramayor') echo 'checked' ; echo " value='menoramayor' >Edad menor a mayor</label></p>
</div>


<p>
<input type='submit' name='accion' value='Buscar' />
</p>
</form>
</div>
";
}

function HTMLdivmain(){
    echo "<div class='en_linea'><main>";
    
}

function HTMLcierremain(){
    echo "</main>";
}

?>