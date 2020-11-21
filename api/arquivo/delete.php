<?php
    // include database and object files
    
    include_once '../config/database.php';
    include_once '../objects/arquivo.php';

    // get database connection

    $database = new Database();
    $db = $database->getConnection();

    // prepare object
    
    $arquivo = new Arquivo($db);
    
    $py_idarquivo = md5('idarquivo');
    $arquivo->idarquivo = $_GET[''.$py_idarquivo.''];

    // identify attachment

    $sql = $arquivo->readSingle($arquivo->idarquivo);
    $ret = $sql->rowCount();

        if ($ret > 0) {
            $dir = '../../anexo/';
            $row = $sql->fetch(PDO::FETCH_OBJ);

                if (file_exists($dir . $row->link)) {
                    if (unlink($dir . $row->link)) {
                        if ($arquivo->delete()) {
                            #if ($a == $ret) {
                                #$filesdeleted = true;
                                if (!$arquivo->readAll()) {
                                    $arquivo->truncate();
                                    echo'true';
                                } else {
                                    echo'true';
                                }
                            #}
                        } else {
                            die(var_dump($db->errorInfo()));
                        }
                    }
                }
        }

        /*if ($arquivo->delete()) {
            echo'true';
        } else {
            die(var_dump($db->errorInfo()));
        }*/
   
    unset($database,$db,$arquivo,$py_idarquivo);
