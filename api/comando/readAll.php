<?php
    // include database and object files

    include_once '../config/database.php';
    include_once '../objects/comando.php';
     
    // get database connection

    $database = new Database();
    $db = $database->getConnection();
     
    // prepare object

    $comando = new Comando($db);
    
    // query instance

    $sql = $comando->readAll();
    
        // check if more than 0 record found

        if ($sql->rowCount() > 0) {
            // instance array

            $comando_arr['comando'] = array();
        
                while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                        
                    $comando_item = array(
                            'status' => true,
                            'idcomando' => $idcomando,
                            'sistema' => $sistema,
                            'descricao' => $descricao,
                            'instrucao' => $instrucao,
                        );

                    array_push($comando_arr['comando'], $comando_item);
                }
        
            echo json_encode($comando_arr['comando']);
        } else {
            $comando_arr = array('status' => false);
            echo json_encode($comando_arr);
        }
