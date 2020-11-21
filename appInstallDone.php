<?php
    require_once 'appConfig.php';
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
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="stylesheet" href="dist/css/main.css">
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <?php echo $cfg['login_title']; ?>
        </div>

        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Cadastre um usu&aacute;rio para acessar</p>

                <form class="form-install">
                    <div class="input-group mb-3">
                        <input type="text" id="nome" class="form-control" maxlength="200" placeholder="Nome" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-smile"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" id="usuario" class="form-control" maxlength="15" placeholder="Usu&aacute;rio"
                            required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" id="senha" class="form-control" maxlength="15" placeholder="Senha"
                            required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="email" id="email" class="form-control" maxlength="100" placeholder="Email"
                            required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8"></div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block btn-install">Salvar</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- /.login-card-body -->

        </div>
    </div>

    <!-- /.login-box -->

    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>
    <script defer>
        $(function () {
            const fade = 150,
                delay = 100,
                Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000
                });

            $('.form-install').submit(function (e) {
                e.preventDefault();
                let usuario = btoa($('#usuario').val()),
                    senha = btoa($('#senha').val());

                $.post('api/usuario/install.php', {
                    nome: $('#nome').val(),
                    usuario: usuario,
                    senha: senha,
                    email: $('#email').val(),
                    rand: Math.random()
                }, function (data) {
                    $('.btn-install').html('<img src="dist/img/rings.svg" class="loader-svg">')
                        .fadeTo(fade, 1);

                    switch (data) {
                        case 'true':
                            Toast.fire({
                                icon: 'success',
                                title: 'Usu&aacute;rio criado.'
                            }).then((result) => {
                                window.setTimeout("location.href='index'", delay);
                            });
                            break;

                        default:
                            Toast.fire({
                                icon: 'error',
                                title: data
                            });
                            break;
                    }

                    $('.btn-install').html('Salvar').fadeTo(fade, 1);
                });

                return false;
            });
        });
    </script>
</body>
</html>