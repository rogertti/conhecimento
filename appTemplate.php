<?php
    require_once 'appConfig.php';

        if (empty($_SESSION['key'])) {
            header('location:./');
        }

    $menu = 0;
    #echo md5('');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $cfg['head_title']; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="dist/img/favicon.png">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700">
        <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
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

                <!-- Content Header (Page header) -->

                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-12">
                                <h1>
                                    <span></span>
                                    <span class="float-right">
                                        <a href="#" class="btn btn-primary" title="Clique para cadastrar um novo" data-toggle="modal" data-target="#modal-new-">
                                            <i class="fas fa-user"></i> Novo
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
                    <?php include_once 'appSearch.php'; ?>

                    <!-- Default box -->

                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered table-hover table-data d-none">
                                <thead>
                                    <th></th>
                                    <th></th>
                                    <th style="max-width: 100px;width: 90px;"></th>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <th></th>
                                    <th></th>
                                    <th style="max-width: 100px;width: 90px;"></th>
                                </tfoot>
                            </table>

                            <blockquote class="blockquote-data d-none">
                                <h5>Nada encontrado</h5>
                                <p>Nenhum  cadastrado.</p>
                            </blockquote>
                        </div>
                    </div>

                    <!-- /.card -->

                </section>

            <!-- /.content -->

            </div>

            <!-- /.content-wrapper -->

            <div class="modal fade" id="modal-new-">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form class="form-new-">
                            <div class="modal-header">
                                <h4 class="modal-title">
                                    <span>Novo </span>
                                    <span class="text-muted">
                                        <small>(<i class="fas fa-bell"></i> Campo obrigat&oacute;rio)</small>
                                    </span>
                                </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="">

                                <div class="form-group">
                                    <label for=""><i class="fas fa-bell"></i> </label>
                                    <input type="text" name="" id="" maxlength="" class="form-control" placeholder="" required>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                <button type="submit" class="btn btn-primary btn-new-">Salvar</button>
                            </div>
                        </form>
                    </div>

                    <!-- /.modal-content -->

                </div>

                <!-- /.modal-dialog -->

            </div>

            <!-- /.modal -->

            <div class="modal fade" id="modal-edit-">
                <div class="modal-dialog">
                    <div class="modal-content"></div>
                </div>
            </div>

            <?php include_once 'appFootbar.php'; ?>
        </div>

        <!-- ./wrapper -->

        <script src="plugins/jquery/jquery.min.js"></script>
        <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
        <script src="dist/js/adminlte.min.js"></script>
        <script defer src="dist/js/main.js"></script>
        <script defer></script>
    </body>
</html>
