<?php
require_once('../Modelo/funcionesBD.php');
require_once('../Modelo/db.php');
require_once('../Vista/VistasHTML.php');
HTMLinicio("ProyectoTW");
HTMLheader();
HTMLnav(0);
$_SESSION['imagen']='';
function getParamsMesesVacunas($post, $atri1) {
    $error=$atri1.'error';
    $result[$error]='';
    if (isset($post[$atri1]))  { /* El formulario ha sido enviado */
        $result['enviado'] = true;
    if ((!isset($post[$atri1]) or empty($post[$atri1])) ) {
      $result[$atri1] ='';
      $result[$error] = 'No ha indicado ningún valor';
    }

    else if (  ((intval($post[$atri1]))==0) &&  ($post[$atri1]!="0")){
        $result[$error] = 'El tipo de dato que debe introducir debe de ser un entero positivo';
        $result[$atri1] ='';
       
      }
    
        
    else if ((intval($post[$atri1]))<0){
      $result[$error] = 'El número que introduzca debe ser mayor que 0';
      $result[$atri1] ='';
     
    }
        
    else{

      $result[$atri1] = strip_tags($post[$atri1]);
    }
    
    } else {  /* El formulario aun no ha sido enviado */
    $result['enviado'] = false;
    $result[$atri1] = '';
    }
    
    return $result;
  }





if (isset($_POST['accion'])){
    if ($_POST['accion']=='Mostrar N vacunas'){
        $atributo1="num_vacunas";
    
        $params1 = getParamsMesesVacunas($_POST, $atributo1);
    }
        
        
    if ($_POST['accion']=='Mostrar N meses'){
        $atributo1="num_meses";
    
        $params1 = getParamsMesesVacunas($_POST, $atributo1);
    }

    if ($_POST['accion']=='Mostrar vacunas futuras'){
        $atributo1="num_vacunas";
        $params1[$atributo1]="10000";
        $params1['error']='';
    }

    if ($_POST['accion']=='Mostrar vacunas pasadas'){
        $atributo1="";
        $params1['error']='';
    }


        
        
    
        if  (($_POST['accion']=='Mostrar vacunas pasadas' || $_POST['accion']=='Mostrar vacunas futuras')){
            $db=DB_connection();
            $r=obtenerVacunasBD($db);
            
        

            if ($r)
            listadoInfoVacunas($r,$params1, $atributo1, $_POST['accion'] );   
            

            
                
            
            DB_disconnection($db);
        }
        else if (( $_POST['accion']=='Mostrar N meses' || $_POST['accion']=='Mostrar N vacunas') && $params1[$atributo1.'error']==''){
            $db=DB_connection();
            $r=obtenerVacunasBD($db);
            
        

            if ($r)
            listadoInfoVacunas($r,$params1, $atributo1, $_POST['accion'] );   
            

            
                
            
            DB_disconnection($db);
        }
        else{
           
            informacionAdicional($params1);
        }

        


        
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