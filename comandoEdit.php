<?php
    /* CLEAR CACHE */
    
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    //header("Content-Type: application/xml; charset=utf-8");

    require_once 'appConfig.php';
    include_once 'api/config/database.php';
    include_once 'api/objects/comando.php';
    include_once 'api/objects/sistema.php';
    include_once 'api/objects/arquivo.php';

    // functionals functions

    function checkImageExtension ($ext) {
        switch ($ext) {
            case 'jpg': return true; break;
            case 'jpeg': return true; break;
            case 'png': return true; break;
            case 'gif': return true; break;
            case 'tiff': return true; break;
            case 'webp': return true; break;
            case 'bmp': return true; break;
            case 'svg': return true; break;
        }
    }

    function checkCompactExtension ($ext) {
        switch ($ext) {
            case 'zip': return true; break;
            case '7z': return true; break;
            case 'rar': return true; break;
            case '001': return true; break;
            case 'arj': return true; break;
            case 'bin': return true; break;
            case 'bzip': return true; break;
            case 'cab': return true; break;
            case 'jar': return true; break;
            case 'deb': return true; break;
            case 'rpm': return true; break;
            case 'gz': return true; break;
            case 'tar': return true; break;
            case 'war': return true; break;
        }
    }

    function checkFileExtension ($ext) {
        switch ($ext) {
            case 'doc': return '<i class="fas fa-3x fa-file-word text-muted"></i>'; break;
            case 'docx': return '<i class="fas fa-3x fa-file-word text-muted"></i>'; break;
            case 'xls': return '<i class="fas fa-3x fa-file-excel text-muted"></i>'; break;
            case 'xlsx': return '<i class="fas fa-3x fa-file-excel text-muted"></i>'; break;
            case 'ppt': return '<i class="fas fa-3x fa-file-powerpoint text-muted"></i>'; break;
            case 'pptx': return '<i class="fas fa-3x fa-file-powerpoint text-muted"></i>'; break;
            case 'pdf': return $ext; /*'<i class="fas fa-3x fa-file-pdf text-muted"></i>';*/ break;
            case 'txt': return '<i class="fas fa-3x fa-file-alt text-muted"></i>'; break;
            case 'text': return '<i class="fas fa-3x fa-file-alt text-muted"></i>'; break;
            case 'md': return true; break;
            case 'wps': return '<i class="fas fa-3x fa-file-word text-muted"></i>'; break;
            case 'eps': return true; break;
            case 'psd': return true; break;
            case 'odt': return '<i class="fas fa-3x fa-file-word text-muted"></i>'; break;
            case 'rft': return '<i class="fas fa-3x fa-file-word text-muted"></i>'; break;
        }
    }

    // get database connection

    $database = new Database();
    $db = $database->getConnection();

    // prepare objects

    $comando = new Comando($db);
    $sistema = new Sistema($db);
    $arquivo = new Arquivo($db);

    // GET variables

    $py_idcomando = md5('idcomando');

    // read all records from secondary request for primary request

    $sql = $comando->readLinkedSystem($_GET[''.$py_idcomando.'']);

        if($sql->rowCount() > 0) {
            $row = $sql->fetchAll(PDO::FETCH_ASSOC);
            $syslinked_array = array_column($row, 'idsistema');
            $syslinked = implode(',', $syslinked_array);
        } else {
            $syslinked = '';
        }

    $sql->closeCursor();

    // read data from primary request

    $sql = $comando->readSingle($_GET[''.$py_idcomando.'']);

        if ($sql->rowCount() > 0) {
            #while($row = $sql->fetch(PDO::FETCH_OBJ)) {}
            $row = $sql->fetch(PDO::FETCH_OBJ);
?>
<form class="form-edit-comando">
    <div class="modal-header">
        <h4 class="modal-title">
            <span>Editar Conhecimento</span>
            <span class="text-muted">
            <small>(<i class="fas fa-bell"></i> Campo obrigat&oacute;rio)</small>
            </span>
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <input type="hidden" name="rand" id="rand_edit" value="<?php echo md5(mt_rand()); ?>">
        <input type="hidden" name="idcomando" id="idcomando" value="<?php echo $_GET[''.$py_idcomando.'']; ?>">
        <input type="hidden" name="sistema_selected_original" id="sistema_selected_original" value="<?php echo $syslinked; ?>">
        <input type="hidden" name="sistema_selected" id="sistema_selected_edit">

        <div class="form-group">
            <label for="sistema"><i class="fas fa-bell"></i> Sistema</label>
            <select name="sistema" id="sistema_edit" class="form-control"
                data-placeholder="Encontre ou informe o sistema destino" style="width: 100%;" multiple required>
            <?php
                $sql2 = $sistema->readAll();

                    if($sql2->rowCount() > 0) {
                        while ($row2 = $sql2->fetch(PDO::FETCH_OBJ)) {
                            if (strstr($syslinked, $row2->idsistema)) {
                                echo'<option value="'.$row2->idsistema.'" selected>'.$row2->sistema.'</option>';
                            } else {
                                echo'<option value="'.$row2->idsistema.'">'.$row2->sistema.'</option>';
                            }
                        }
                    }
            ?>
            </select>
        </div>
        <div class="form-group">
            <label for="descricao"><i class="fas fa-bell"></i> Descri&ccedil;&atilde;o</label>
            <input type="text" name="descricao" id="descricao_edit" maxlength="100"
                value="<?php echo $row->descricao; ?>" class="form-control" placeholder="Descri&ccedil;&atilde;o" required>
        </div>
        <div class="form-group">
            <label for="instrucao"><i class="fas fa-bell"></i> Comando</label>
            <textarea name="instrucao" id="instrucao_edit" rows="3" class="form-control"
                placeholder="Instru&ccedil;&atilde;o" required><?php echo $row->instrucao; ?></textarea>
        </div>
        <!--<div class="form-group">
            <label for="anexo">Anexo</label>
            <input type="file" name="anexo[]" id="anexo_edit" class="form-control" placeholder="Anexe arquivos ao conhecimento" multiple>
        </div>-->
        <div class="custom-file">
            <label class="custom-file-label" for="anexo">Escolher arquivos</label>
            <input type="file" name="anexo[]" id="anexo_edit" class="custom-file-input">
        </div>
        <div class="anexo">
        <?php
            $sql2 = $arquivo->readForCommand($row->idcomando);

                if ($sql2->rowCount() > 0) {
                    $dir = 'anexo/';

                        while ($row2 = $sql2->fetch(PDO::FETCH_OBJ)) {
                            $extensao = strrchr($row2->link, '.');
                            $extensao = substr($extensao, 1);

                                if (checkImageExtension($extensao)) {
                                    echo'
                                    <div>
                                        <span><a href="' . $dir . $row2->link . '" data-toggle="lightbox"><img src="' . $dir . $row2->link . '" alt="anexo"></a></span>
                                        <p class="anexo-action">
                                            <a class="a-delete-arquivo" id="c87da4b82210d84b55cce6bc0e64e3de-'.$row2->idarquivo.'" href="#" title="Excluir anexo"><i class="fa fa-trash text-danger"></i></a>
                                        </p>
                                    </div>';
                                } elseif (checkCompactExtension($extensao)) {
                                    echo'
                                    <div>
                                        <span><i class="fas fa-3x fa-file-archive text-muted"></i> <em><a href="' . $dir . $row2->link . '">' . $row2->link . '</a></em></span>
                                        <p class="anexo-action">
                                            <a class="a-delete-arquivo" id="c87da4b82210d84b55cce6bc0e64e3de-'.$row2->idarquivo.'" href="#" title="Excluir anexo"><i class="fa fa-trash text-danger"></i></a>
                                        </p>
                                    </div>';
                                } elseif ($fa = checkFileExtension($extensao)) {
                                    if ($fa == 'pdf') {
                                        echo'
                                        <div>
                                            <span><i class="fas fa-3x fa-file-pdf text-muted"></i> <em><a class="a-view-document" href="' . $dir . $row2->link . '">' . $row2->link . '</a></em></span>
                                            <p class="anexo-action">
                                                <a class="a-delete-arquivo" id="c87da4b82210d84b55cce6bc0e64e3de-'.$row2->idarquivo.'" href="#" title="Excluir anexo"><i class="fa fa-trash text-danger"></i></a>
                                            </p>
                                        </div>';
                                    } else {
                                        echo'
                                        <div>
                                            <span>' . $fa . ' <em><a href="' . $dir . $row2->link . '">' . $row2->link . '</a></em></span>
                                            <p class="anexo-action">
                                                <a class="a-delete-arquivo" id="c87da4b82210d84b55cce6bc0e64e3de-'.$row2->idarquivo.'" href="#" title="Excluir anexo"><i class="fa fa-trash text-danger"></i></a>
                                            </p>
                                        </div>';
                                    }
                                } else {
                                    echo'
                                    <div>
                                        <span><i class="fas fa-3x fa-shapes text-muted"></i> <em>' . $row2->link . '</em></span>
                                        <p class="anexo-action">
                                            <a class="a-delete-arquivo" id="c87da4b82210d84b55cce6bc0e64e3de-'.$row2->idarquivo.'" href="#" title="Excluir anexo"><i class="fa fa-trash text-danger"></i></a>
                                        </p>
                                    </div>';
                                }
                        }
                }
        ?>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary btn-edit-comando">Salvar</button>
    </div>
</form>
<script defer>
    $(document).ready(function() {
        const fade = 150,
            delay = 100,
            timeout = 60000,
            filelist = [],
            Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1000
            });
        
        /* TOOLTIP */

        $('div a, td span a, span a, div p a, p a').tooltip({
            boundary: 'window'
        });
        
        /* SELECT MULTIPLE */

        $('#sistema_edit').show(function() {
            $('#sistema_edit').select2({
                tags: true,
                language: {
                    noResults: function () {
                        return 'No campo acima é possível criar um novo sistema.';
                    }
                }
            });

            $('#sistema_edit').change(function (e) {
                let obj = $('#sistema_edit').select2('val');
                $('#sistema_selected_edit').attr('value', obj);
            });
        });

        /* UPLOAD */

        $('#anexo_edit').on('change', function(e) {
            e.preventDefault();

                for (let i = 0; i < this.files.length; i++) {
                    filelist.push(this.files[i]);
                }
        });

        /* MODAL */

        $('.anexo').on('click', '.a-view-document', function(e) {
            e.preventDefault();
            //$('#modal-view-document').modal('show').find('.modal-content').load($(this).attr('href'));
            eModal.iframe($(this).attr('href'), 'Visualizador');
        });

        /* LIGHTBOX */

        $('.anexo').on('click', '[data-toggle="lightbox"]', function(e) {
            e.preventDefault();
            
            $(this).ekkoLightbox({
                alwaysShowClose: true
            });
        });

        /* EDITAR CONHECIMENTO */

        $('.form-edit-comando').submit(function(e) {
            e.preventDefault();

            let formdata = new FormData(this);
            formdata.append('anexo', filelist);

            $.ajax({
                url: 'api/comando/update.php',
                type: 'POST',
                cache: false,
                data: formdata,
                processData: false,
                contentType: false,
                success: function (data) {
                    switch (data) {
                        case 'true':
                            Toast.fire({
                                icon: 'success',
                                title: 'Conhecimento editado.'
                            }).then((result) => {
                                window.setTimeout("location.href='inicio'", delay);
                            });
                            break;

                        default:
                            Toast.fire({
                                icon: 'error',
                                title: data
                            });
                            break;
                    }
                },
                error: function (data) {
                    Toast.fire({
                        icon: 'error',
                        title: data
                    });
                }
            });

            return false;
        });

        /* DELETE ARQUIVO */

        $('.anexo').on('click', '.a-delete-arquivo', function(e) {
            e.preventDefault();

            let target = $('.a-edit-comando').attr('href'),
                click = this.id.split('-'),
                py = click[0],
                id = click[1];

            Swal.fire({
                icon: 'question',
                title: 'Excluir o Arquivo',
                showCancelButton: true,
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não',
            }).then((result) => {
                if (result.value == true) {
                    $.ajax({
                        type: 'GET',
                        url: 'api/arquivo/delete.php?' + py + '=' + id,
                        dataType: 'json',
                        cache: false,
                        beforeSend: function(result) {
                            $('#search-result').empty().html(
                                '<p style="position: relative;top: 15px;" class="lead"><i class="fas fa-cog fa-spin"></i></p>'
                            );
                        },
                        error: function(result) {
                            Swal.fire({
                                icon: 'error',
                                html: result.responseText,
                                showConfirmButton: false
                            });
                        },
                        success: function(data) {
                            if (data == true) {
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Arquivo exclu&iacute;do.'
                                }).then((result) => {
                                    //window.setTimeout("location.href='inicio'", delay);
                                    // load the url and show modal on success

                                    $('#modal-edit-comando .modal-content').load(target);
                                });
                            }
                        }
                    });
                }
            });
        });
    });
<?php
        } else {
            echo'
            <blockquote class="quote-danger">
                <h5>Erro</h5>
                <p>O conhecimento n&atilde;o foi encontrado.</p>
            </blockquote>';
        }
?>