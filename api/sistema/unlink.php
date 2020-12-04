<?php
    // include database and object files
    
    include_once '../config/database.php';
    include_once '../objects/sistema.php';

    // get database connection

    $database = new Database();
    $db = $database->getConnection();

    // prepare object
    
    $sistema = new Sistema($db);
    
    $py_idsistema = md5('idsistema');
    $ids = explode(',', $_GET[''.$py_idsistema.'']);
    #echo $ids[0] . ' - ' . $ids[1]; exit;

    $sistema->idsistema = $ids[0];
    $sistema->idcomando = $ids[1];

        if ($sistema->unlink()) {
            echo'true';
        } else {
            die(var_dump($db->errorInfo()));
        }
   
    unset($database,$db,$sistema,$py_idsistema);
