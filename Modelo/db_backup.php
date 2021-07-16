
<?php
/* Backup de la BBDD completa */
function DB_backup($db) {
  // Obtener listado de tablas
  $tablas = array();
  $result = mysqli_query($db,'SHOW TABLES');
  while ($row = mysqli_fetch_row($result))
    $tablas[] = $row[0];
  
  // Salvar cada tabla
  $salida = '';
  foreach ($tablas as $tab) {
    $result = mysqli_query($db,'SELECT * FROM '.$tab);
    $num = mysqli_num_fields($result);
    
    $salida .= 'DROP TABLE IF EXISTS '.$tab.';';
    $row2 = mysqli_fetch_row(mysqli_query($db,'SHOW CREATE TABLE '.$tab));
    $salida .= "\n\n".$row2[1].";\n\n";
    
    while ($row = mysqli_fetch_row($result)) {
      $salida .= 'INSERT INTO '.$tab.' VALUES(';
      for ($j=0; $j < $num; $j++) {
        if (!is_null($row[$j])) {
          $row[$j] = addslashes($row[$j]);
          $row[$j] = preg_replace("/\n/","\\n",$row[$j]);
          if (isset($row[$j]))
            $salida .= '"'.$row[$j].'"';
          else
            $salida .= '""';
        } else
          $salida .= 'NULL';
        if ($j < ($num-1))
          $salida .= ',';
      }
      $salida .= ");\n";
    }
    $salida .= "\n\n\n";
  }
  return $salida;
}

/* Restauración de la BBDD completa */
function DB_restore($db,$f) {
    mysqli_query($db,'SET FOREIGN_KEY_CHECKS=0');
    DB_delete($db);
    $error = [];
    $sql = file_get_contents($f);
    $queries = explode(';',$sql);
    foreach ($queries as $q) {
      $q = trim($q);
      if ($q!='' and !mysqli_query($db,$q)){
        print_r(mysqli_error($db));
        $error .= mysqli_error($db);
      }
        
    }
    mysqli_commit($db);
    mysqli_query($db,'SET FOREIGN_KEY_CHECKS=1');
    return $error;
  }
  
  /* Borrar el contenido de las tablas de la BBDD */
  function DB_delete($db) {
    $result = mysqli_query($db,'SHOW TABLES');
    while ($row = mysqli_fetch_row($result)){
        if ($row[0]=='Usuarios'){
            mysqli_query($db,"DELETE FROM Usuarios where email!='{$_SESSION['usuario']}'");
        }
        else{
            mysqli_query($db,'DELETE FROM '.$row[0]);
        }
        
    }   
    mysqli_commit($db);  
  }



?>