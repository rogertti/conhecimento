<?php
    // include database and object files

    include_once '../config/database.php';
    include_once '../objects/appinit.php';

    // get database connection

    $database = new Database();
    $db = $database->getConnection();

    // prepare object

    $appinit = new Appinit($db);

    // vars to control this script

    $msg = "Campo obrigat&oacute;rio vazio.";

        //filtering the inputs

        if (empty($_POST['rand'])) {
            die('Vari&aacute;vel de controle nula.');
        }
        if (empty($_POST['idappinit'])) {
            die('Vari&aacute;vel de controle nula.');
        } else {
            $appinit->idappinit = $_POST['idappinit'];
        }
        if (empty($_POST['nome'])) {
            die($msg);
        } else {
            $filtro = 1;
            $_POST['nome'] = str_replace("'", "&#39;", $_POST['nome']);
            $_POST['nome'] = str_replace('"', '&#34;', $_POST['nome']);
            $_POST['nome'] = str_replace('%', '&#37;', $_POST['nome']);
            $appinit->nome = $_POST['nome'];
        }
        if (empty($_POST['template'])) {
            die($msg);
        } else {
            $filtro++;
            $appinit->template = $_POST['template'];
        }

        if ($filtro == 4) {
            if ($appinit->update()) {
                echo'true';
            } else {
                die(var_dump($db->errorInfo()));
            }
        } else {
            die('Vari&aacute;vel de controle nula.');
        }

    unset($database,$db,$appinit,$msg);
