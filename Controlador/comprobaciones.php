<?php
/*require_once('/var/www/html/proyecto/Modelo/funcionesBD.php');
require_once('/var/www/html/proyecto/Modelo/db.php');*/
require_once(__DIR__ . "/../Modelo/funcionesBD.php");
require_once(__DIR__ . "/../Modelo/db.php");
function comprobarRangoEdad($ini, $fin, $v){
    $rango=false;
    if (($v['meses_ini']>=$ini) && ($v['meses_ini']<$fin))
        $rango=true;
    else if (($v['meses_fin']>=$ini) && ($v['meses_fin']<=$fin))
        $rango=true;
    else if (($v['meses_ini']<=$ini) && ($v['meses_fin']>=$fin))
        $rango=true;

    return $rango;
}

function estadisticasVacunacion(){
    
    $db=DB_connection();
        $r=obtenerVacunacionBD($db);

        $date_now = date('Y-m-d');
        $date_past = strtotime('-30 day', strtotime($date_now));
        $date_past = date('Y-m-d', $date_past);
        
        $contador=0;
        foreach ($r as $v){
            
            if ($v['fecha']<= $date_now)
                if ($v['fecha']>=$date_past)
                    $contador=$contador+1;
        }

       
        
        DB_disconnection($db);

        return $contador;
}
function comprobarRangoEdadC($ini, $fin, $dif){
    $rango=false;
    if ($dif>=$ini && $dif<=$fin)
        $rango=true;
    return $rango;
}

function numeroUsuariosSistema(){
    $db=DB_connection();
    $r=obtenerNumUsuariosBD($db);

    DB_disconnection($db);
    $pacientes=0;
    $sanitarios=0;
    $administradores=0;
    $contador=0;
    foreach ($r as $v){
            
        if ($v['rol']== 'P')
            $pacientes=$pacientes+1;
        else if ($v['rol']== 'S')
            $sanitarios=$sanitarios+1;
        else 
            $administradores=$administradores+1;
        $contador++;   
    }
    $array_pacientes=array("Pacientes", $pacientes, round ( ($pacientes/$contador)*100, 2 ,  PHP_ROUND_HALF_UP ) );
    $array_sanitarios=array("Sanitarios", $sanitarios, round ( ($sanitarios/$contador)*100, 2 ,  PHP_ROUND_HALF_UP ) );
    $array_administradores=array("Adiministradores", $administradores,    round ( ($administradores/$contador)*100, 2 ,  PHP_ROUND_HALF_UP )   );
    $r=array($array_pacientes,$array_sanitarios, $array_administradores);
    /*$r=array($pacientes, $sanitarios, $administradores);*/
    return $r;
}



function obtenerFechaNacimiento(){
    $db=DB_connection();
    
    $r=obtenerFechaNacimientoBD($db);

    DB_disconnection($db);
    return $r;
}

function generarGrafico($array){
    $DataSet = new pData;  
 $DataSet->AddPoint(array(10,2,3,5,3),"Serie1");  
 $DataSet->AddPoint(array("Jan","Feb","Mar","Apr","May"),"Serie2");  
 $DataSet->AddAllSeries();  
 $DataSet->SetAbsciseLabelSerie("Serie2");  
  
 // Initialise the graph  
 $Test = new pChart(300,200);  
 $Test->loadColorPalette("Sample/softtones.txt");  
 $Test->drawFilledRoundedRectangle(7,7,293,193,5,240,240,240);  
 $Test->drawRoundedRectangle(5,5,295,195,5,230,230,230);  
  
 // This will draw a shadow under the pie chart  
 $Test->drawFilledCircle(122,102,70,200,200,200);  
  
 // Draw the pie chart  
 $Test->setFontProperties("Fonts/tahoma.ttf",8);  
 $Test->drawBasicPieGraph($DataSet->GetData(),$DataSet->GetDataDescription(),120,100,70,PIE_PERCENTAGE,255,255,218);  
 $Test->drawPieLegend(230,15,$DataSet->GetData(),$DataSet->GetDataDescription(),250,250,250);  
  
 $Test->Render("example14.png"); 
}
?>








