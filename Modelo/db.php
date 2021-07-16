<?php
require_once('dbcredenciales.php');
    function DB_connection(){
        $db = mysqli_connect(DB_HOST,DB_USER,DB_PASSWD,DB_DATABASE);
        if(!$db)
            return "Error de conexión a la base de datos (".mysqli_connect_errno().
            ") : ".mysqli_connect_error();

        mysqli_set_charset($db, "utf8");
        return $db;
    }

    function DB_disconnection($db){
        mysqli_close($db);
    }
?>