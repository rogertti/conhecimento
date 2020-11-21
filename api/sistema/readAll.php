<?php
    // include database and object files

    include_once '../config/database.php';
    include_once '../objects/sistema.php';
     
    // get database connection

    $database = new Database();
    $db = $database->getConnection();
     
    // prepare object

    $sistema = new Sistema($db);
    
    // query instance

    $sql = $sistema->readAll();
    
        // check if more than 0 record found

        if ($sql->rowCount() > 0) {
            // instance array

            $sistema_arr['sistema'] = array();
        
                while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                        
                    $sistema_item = array(
                            'status' => true,
                            'idsistema' => $idsistema,
                            'sistema' => $sistema
                        );

                    array_push($sistema_arr['sistema'], $sistema_item);
                }
        
            echo json_encode($sistema_arr['sistema']);
        } else {
            $sistema_arr = array('status' => false);
            echo json_encode($sistema_arr);
        }
