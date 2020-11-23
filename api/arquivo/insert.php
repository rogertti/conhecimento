<?php
    // include database and object files
    include_once '../config/database.php';
    include_once '../objects/arquivo.php';

    // get database connection

    $database = new Database();
    $db = $database->getConnection();

    // prepare object

    $arquivo = new Arquivo($db);

    // vars to control this script

    $msg = "Campo obrigat&oacute;rio vazio.";

        //filtering the inputs

        if (empty($_POST['rand'])) {
            die('Vari&aacute;vel de controle nula.');
        }

        if (empty($_POST['idcomando'])) {
            die('Vari&aacute;vel de controle nula.');
        } else {
            $filtro = 1;
            $arquivo->idcomando = $_POST['idcomando'];
        }

        if (empty($_POST['anexo'])) {
            die($msg);
        } else {
            $filtro++;
            $arquivo->link = $_FILES['anexo'];
        }

        if ($filtro == 2) {
            if ($arquivo->upload()) {
                echo'true';
            } else {                    
                die(var_dump($db->errorInfo()));
            }
        } else {
            die('Inconsist&ecirc;ncia na filtragem.');
        }

    unset($database,$db,$arquivo,$msg);
