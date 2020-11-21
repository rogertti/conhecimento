<?php
    // include database and object files
    
    include_once '../config/database.php';
    include_once '../objects/comando.php';
    include_once '../objects/arquivo.php';

    // get database connection

    $database = new Database();
    $db = $database->getConnection();

    // prepare object
    
    $comando = new Comando($db);
    $arquivo = new Arquivo($db);
    
    $py_idcomando = md5('idcomando');
    $comando->idcomando = $_GET[''.$py_idcomando.''];

    // identify attachment

    $sql = $arquivo->readForCommand($comando->idcomando);
    $ret = $sql->rowCount();

        if ($ret > 0) {
            $a = 1;
            $dir = '../../anexo/';

                while ($row = $sql->fetch(PDO::FETCH_OBJ)) {
                    $arquivo->idarquivo = $row->idarquivo;

                        if (file_exists($dir . $row->link)) {
                            if (unlink($dir . $row->link)) {
                                if ($arquivo->delete()) {
                                    if ($a == $ret) {
                                        $filesdeleted = true;
                                        
                                            if (!$arquivo->readAll()) {
                                                $arquivo->truncate();
                                            }
                                    }
                                } else {
                                    die(var_dump($db->errorInfo()));
                                }
                            }
                        }

                    $a++;
                }
        } else {
            $filesdeleted = false;

                if ($comando->delete()) {
                    echo'true';
                } else {
                    die(var_dump($db->errorInfo()));
                }
        }

        if ($filesdeleted) {
            if ($comando->delete()) {
                echo'true';
            } else {
                die(var_dump($db->errorInfo()));
            }
        }
   
    unset($database,$db,$comando,$py_idcomando,$arquivo);
