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
            $filtro = 1;
            $comando->idcomando = $_POST['idcomando'];
            $sistema->idcomando = $_POST['idcomando'];
        }

        if (empty($_POST['sistema_selected_original'])) {
            die('Vari&aacute;vel de controle nula.');
        }

        if (empty($_POST['sistema_selected'])) {
            $_POST['sistema_selected'] = $_POST['sistema_selected_original'];
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

            // essa rotina apaga o vínculo entre o sistema e o comando, se o usuário assim tiver feito.

            $sistema_selected_original = explode(',', $_POST['sistema_selected_original']);
            $sistema_selected = explode(',', $_POST['sistema_selected']);
            $sistemas_diff = array_diff($sistema_selected_original, $sistema_selected);
            $sistemas_diff_count = count($sistemas_diff);
            #echo $sistemas_diff_count; exit;

                if ($sistemas_diff_count > 0) {
                    foreach ($sistemas_diff as $sistema_diff => $idsistema_diff) {
                        $sistema->idsistema = $idsistema_diff;

                            if (!$sistema->unlink()) {
                                die(var_dump($db->errorInfo()));
                            }
                    }
                }

            // essa rotina cria o vínculo entre o sistema e o comando, se o usuário assim tiver feito.

            $sistemas_diff_reverse = array_diff($sistema_selected, $sistema_selected_original);
            $sistemas_diff_reverse_count = count($sistemas_diff_reverse);
            #echo $sistemas_diff_reverse_count; exit;

                if ($sistemas_diff_reverse_count > 0) {
                    foreach ($sistemas_diff_reverse as $sys) {
                        if (is_numeric($sys)) {
                            $array_sistemas[] = $sys;
                        } else {
                            $sistema->descricao = ucwords($sys);
                            $array_sistemas[] = $sistema->insertSystemBefore();
                        }
                    }

                    $comando->idsistema = $array_sistemas;
                } else {
                    /*if ($comando->update()) {
                        echo'true';
                    } else {
                        die(var_dump($db->errorInfo()));
                    }*/
                }

                if ($comando->update()) {
                    echo'true';
                } else {
                    die(var_dump($db->errorInfo()));
                }
        } else {
            die('Vari&aacute;vel de controle nula.');
        }

    unset($database,$db,$comando,$sistema,$msg);
