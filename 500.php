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
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="stylesheet" href="dist/css/main.css">
</head>
<body class="hold-transition sidebar-mini sidebar-collapse text-sm">

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
                                <span>500 Error Page</span>
                            </h1>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Main content -->

            <section class="content">
                <div class="error-page">
                    <h2 class="headline text-danger"> 500</h2>

                    <div class="error-content">
                        <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! Something went wrong.</h3>

                        <p>
                            We will work on fixing that right away.<br>
                            Meanwhile, you may <a href="inicio">return to dashboard</a>.
                        </p>
                    </div>

                    <!-- /.error-content -->

                </div>

                <!-- /.error-page -->

            </section>

            <!-- /.content -->

        </div>

        <!-- /.content-wrapper -->

        <?php include_once 'appFootbar.php'; ?>
    </div>

    <!-- ./wrapper -->

    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>
    <script src="dist/js/main.js"></script>
</body>
</html>