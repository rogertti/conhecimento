<?php
    // include database and object files

    include_once '../config/database.php';
    include_once '../objects/comando.php';
    include_once '../objects/sistema.php';

    // get database connection

    $database = new Database();
    $db = $database->getConnection();

    // prepare object

    $comando = new Comando($db);
    $sistema = new Sistema($db);

    // vars to control this script

    $msg = "Campo obrigat&oacute;rio vazio.";

        //filtering the inputs

        if (empty($_POST['rand'])) {
            die('Vari&aacute;vel de controle nula.');
        }

        if (empty($_POST['idcomando'])) {
            die('Vari&aacute;vel de controle nula.');
        } else {
            $comando->idcomando = $_POST['idcomando'];
        }

        if (empty($_POST['sistema'])) {
            die($msg);
        } else {
            $filtro = 1;
            
            if (is_numeric($_POST['sistema'])) {
                $comando->idsistema = $_POST['sistema'];
            } else {
                $sistema->descricao = ucwords($_POST['sistema']);
                $comando->idsistema = $sistema->insertSystemBefore();
                #print_r($comando->idsistema); exit;
            }
        }

        if (empty($_POST['descricao'])) {
            die($msg);
        } else {
            $filtro++;
            $_POST['descricao'] = str_replace("'", "&#39;", $_POST['descricao']);
            $_POST['descricao'] = str_replace('"', '&#34;', $_POST['descricao']);
            $_POST['descricao'] = str_replace('%', '&#37;', $_POST['descricao']);
            $comando->descricao = ucwords($_POST['descricao']);
        }

        if (empty($_POST['instrucao'])) {
            die($msg);
        } else {
            $filtro++;
            $_POST['instrucao'] = str_replace("'", "&#39;", $_POST['instrucao']);
            $_POST['instrucao'] = str_replace('"', '&#34;', $_POST['instrucao']);
            $_POST['instrucao'] = str_replace('%', '&#37;', $_POST['instrucao']);
            $comando->instrucao = $_POST['instrucao'];
        }

        if (!empty($_POST['anexo'])) {
            $comando->anexo = $_FILES['anexo'];
        } else {
            $comando->anexo = false;
        }

        if ($filtro == 3) {
            if ($comando->update()) {
                echo'true';
            } else {
                die(var_dump($db->errorInfo()));
            }
        } else {
            die('Vari&aacute;vel de controle nula.');
        }

    unset($database,$db,$comando,$sistema,$msg);
