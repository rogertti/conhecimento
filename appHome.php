<?php
    require_once 'appConfig.php';
    include_once 'api/config/database.php';
    include_once 'api/objects/comando.php';
    include_once 'api/objects/sistema.php';

    // get database connection

    $database = new Database();
    $db = $database->getConnection();

    // prepare objects

    $comando = new Comando($db);
    $sistema = new Sistema($db);

    $timestamp = time();
    $menu = 1;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $cfg['head_title']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="dist/img/favicon.png">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="plugins/ekko-lightbox/ekko-lightbox.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="stylesheet" href="dist/css/main.css">
</head>

<body class="hold-transition layout-navbar-fixed sidebar-mini sidebar-collapse text-sm">

    <!-- Site wrapper -->

    <div class="wrapper">

        <?php
          include_once 'appNavbar.php';
          include_once 'appSidebar.php';
      ?>

        <!-- Content Wrapper. Contains page content -->

        <div class="content-wrapper">
            <?php include_once 'appSearch.php'; ?>

            <!-- Content Header (Page header) -->

            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <h1>
                                <span>Base de Conhecimento</span>
                                <span class="float-right">
                                    <a href="#" class="btn btn-primary"
                                        title="Clique para cadastrar um novo conhecimento"
                                        data-toggle="modal" data-target="#modal-new-comando">
                                        <i class="fas fa-shapes"></i> Compartilhar Conhecimento
                                    </a>
                                </span>
                                <span></span>
                            </h1>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Main content -->

            <section class="content">

                <!-- Default box -->

                <div class="card">
                    <div class="card-body">
                        <div class="div-load-page d-none"></div>

                        <table class="table table-bordered table-hover table-data d-none">
                            <thead>
                                <tr>
                                    <th>Sistema</th>
                                    <th>Descri&ccedil;&atilde;o</th>
                                    <th>Instru&ccedil;&atilde;o</th>
                                    <th style="max-width: 100px;width: 90px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Sistema</th>
                                    <th>Descri&ccedil;&atilde;o</th>
                                    <th>Instru&ccedil;&atilde;o</th>
                                    <th style="max-width: 100px;width: 90px;"></th>
                                </tr>
                            </tfoot>
                        </table>

                        <blockquote class="blockquote-data d-none">
                            <h5>Nada encontrado</h5>
                            <p>Nenhum conhecimento compartilhado.</p>
                        </blockquote>
                    </div>
                </div>

                <!-- /.card -->

            </section>

            <!-- /.content -->

        </div>

        <!-- /.content-wrapper -->

        <div class="modal fade" id="modal-new-comando">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form class="form-new-comando">
                        <div class="modal-header">
                            <h4 class="modal-title">
                                <span>Novo Conhecimento</span>
                                <span class="text-muted">
                                    <small>(<i class="fas fa-bell"></i> Campo obrigat&oacute;rio)</small>
                                </span>
                            </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="rand" id="rand" value="<?php echo md5(mt_rand()); ?>">
                            <!--<input type="hidden" name="sistema_selected" id="sistema_selected">-->

                            <div class="form-group">
                                <label for="sistema"><i class="fas fa-bell"></i> Sistema</label>
                                <select name="sistema" id="sistema" class="form-control"
                                    data-placeholder="Encontre ou informe o sistema destino" style="width: 100%;" required>
                                <?php
                                    $sql = $sistema->readAll();

                                        if ($sql->rowCount() > 0) {
                                            echo'<option value="" selected>Encontre ou informe o sistema destino</option>';

                                                while ($row = $sql->fetch(PDO::FETCH_OBJ)) {
                                                    echo'<option value="'.$row->idsistema.'">'.$row->sistema.'</option>';
                                                }
                                        }
                                ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="descricao"><i class="fas fa-bell"></i> Descri&ccedil;&atilde;o</label>
                                <input type="text" name="descricao" id="descricao" maxlength="100"
                                    class="form-control" placeholder="Descri&ccedil;&atilde;o" required>
                            </div>
                            <div class="form-group">
                                <label for="instrucao"><i class="fas fa-bell"></i> Comando</label>
                                <textarea name="instrucao" id="instrucao" rows="3" class="form-control"
                                    placeholder="Instru&ccedil;&atilde;o" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="anexo">Anexo</label>
                                <input type="file" name="anexo[]" id="anexo" class="form-control" placeholder="Anexe arquivos ao conhecimento" multiple>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary btn-new-comando">Salvar</button>
                        </div>
                    </form>
                </div>

                <!-- /.modal-content -->

            </div>

            <!-- /.modal-dialog -->

        </div>

        <div class="modal fade" id="modal-view-arquivo">
            <div class="modal-dialog">
                <div class="modal-content"></div>
            </div>
        </div>

        <div class="modal fade" id="modal-edit-comando">
            <div class="modal-dialog">
                <div class="modal-content"></div>
            </div>
        </div>

        <!-- /.modal -->

        <?php include_once 'appFootbar.php'; ?>
    </div>

    <!-- ./wrapper -->

    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="plugins/datatables/jquery.dataTables.js"></script>
    <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="plugins/select2/js/select2.full.min.js"></script>
    <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="plugins/eModal/dist/eModal.min.js"></script>
    <script src="plugins/ekko-lightbox/ekko-lightbox.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>
    <script defer src="dist/js/main.js"></script>
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

            /* PULL DATA */

            (function pullData() {
                $.ajax({
                    type: 'GET',
                    url: 'api/comando/readAll.php',
                    dataType: 'json',
                    cache: false,
                    beforeSend: function(result) {
                        $('.div-load-page').removeClass('d-none').html(
                            '<p class="lead text-center"><i class="fas fa-cog fa-spin"></i></p>'
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
                        if (!data[0]) {
                            $('.div-load-page').addClass('d-none');
                            $('.table-data').addClass('d-none');
                            $('.blockquote-data').removeClass('d-none');
                        } else {
                            if (data[0].status == true) {
                                let response = '';

                                    for (let i in data) {
                                        if (data[i].arquivo == true) {
                                            data[i].arquivo = '<span class="bg bg-info"><a class="fas fa-paperclip a-view-arquivo" href="arquivoView.php?19514500024f9521bc15020a60e6ca5e=' + data[i].idcomando + '" title="Anexo(s)"></a></span>';
                                        } else {
                                            data[i].arquivo = '';
                                        }

                                        response += '<tr><td>' + data[i].sistema + '</td>'
                                        + '<td>' + data[i].descricao + '</td>'
                                        + '<td><code>' + data[i].instrucao + '</code></td>'
                                        + '<td class="td-action">'
                                        + data[i].arquivo
                                        + '<span class="bg bg-warning"><a class="fas fa-pen a-edit-comando" href="comandoEdit.php?19514500024f9521bc15020a60e6ca5e=' + data[i].idcomando + '" title="Editar"></a></span>'
                                        + '<span class="bg bg-danger"><a class="fas fa-trash a-delete-comando" id="19514500024f9521bc15020a60e6ca5e-' + data[i].idcomando + '" href="#" title="Excluir"></a></span>'
                                        + '</td></tr>';
                                    }

                                $('.div-load-page').addClass('d-none');
                                $('.blockquote-data').addClass('d-none');
                                //$('.div-time').removeClass('d-none');
                                $('.table-data').removeClass('d-none');
                                //$(response).appendTo($('.table-data'));
                                $('.table-data tbody').html(response);

                                /* TOOLTIP */

                                $('div a, td span a, span a, div p a, p a').tooltip({
                                    boundary: 'window'
                                });

                                /* DATATABLE */

                                $('.table-data').DataTable({
                                    "paging": true,
                                    "lengthChange": false,
                                    "searching": true,
                                    "ordering": true,
                                    "info": true,
                                    "autoWidth": false,
                                    "responsive": true,
                                    "destroy": true
                                });
                            } else {
                                $('.div-load-page').addClass('d-none');
                                //$('.div-time').addClass('d-none');
                                $('.table-data').addClass('d-none');
                                $('.blockquote-data').removeClass('d-none');
                            }
                        }
                    },
                    complete: setTimeout(function() {
                        pullData();
                    }, timeout),
                    timeout: timeout
                });
            })();

            /* MODAL */

            $('.table-data').on('click', '.a-view-arquivo', function(e) {
                e.preventDefault();
                $('#modal-view-arquivo').modal('show').find('.modal-content').load($(this).attr('href'));
            });

            $('.table-data').on('click', '.a-edit-comando', function(e) {
                e.preventDefault();
                $('#modal-edit-comando').modal('show').find('.modal-content').load($(this).attr('href'));
            });

            /* SELECT MULTIPLE */

            $('#sistema').show(function() {
                $('#sistema').select2({
                    tags: true,
                    language: {
                        noResults: function () {
                            return 'No campo acima é possível criar um novo sistema.';
                        }
                    }
                });

                /*$('#sistema').change(function (e) {
                    let obj = $('#sistema').select2('val');
                    $('#sistema_select').attr('value', obj);
                });*/
            });

            /* UPLOAD */

            $('#anexo').on('change', function(e) {
                e.preventDefault();

                    for (let i = 0; i < this.files.length; i++) {
                        filelist.push(this.files[i]);
                        //console.log(filelist);
                    }
            });

            /* NOVO CONHECIMENTO */

            $('.form-new-comando').submit(function(e) {
                e.preventDefault();

                let formdata = new FormData(this);
                formdata.append('anexo', filelist);

                /*$.post('api/comando/insert.php', formdata, function(data) {
                    $('.btn-new-comando').html('<img src="dist/img/rings.svg" class="loader-svg">').fadeTo(fade, 1);

                    switch (data) {
                        case 'true':
                            Toast.fire({
                                icon: 'success',
                                title: 'Conhecimento compartilhado.'
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

                    $('.btn-new-comando').html('Salvar').fadeTo(fade, 1);
                });*/
                $.ajax({
                    url: 'api/comando/insert.php',
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
                                    title: 'Conhecimento compartilhado.'
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

            /* DELETE CONHECIMENTO */

            $('.table-data').on('click', '.a-delete-comando', function(e) {
                e.preventDefault();

                let click = this.id.split('-'),
                    py = click[0],
                    id = click[1];

                Swal.fire({
                    icon: 'question',
                    title: 'Excluir o Conhecimento',
                    showCancelButton: true,
                    confirmButtonText: 'Sim',
                    cancelButtonText: 'Não',
                }).then((result) => {
                    if (result.value == true) {
                        $.ajax({
                            type: 'GET',
                            url: 'api/comando/delete.php?' + py + '=' + id,
                            dataType: 'json',
                            cache: false,
                            /*beforeSend: function(result) {
                                $('#search-result').empty().html(
                                    '<p style="position: relative;top: 15px;" class="lead"><i class="fas fa-cog fa-spin"></i></p>'
                                );
                            },*/
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
                                        title: 'Conhecimento exclu&iacute;do.'
                                    }).then((result) => {
                                        window.setTimeout("location.href='inicio'", delay);
                                    });
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
<?php unset($cfg,$database,$db,$comando,$sistema,$timestamp,$menu); ?>