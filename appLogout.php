<?php
    require_once 'appConfig.php';
    session_unset();
    session_destroy();
    header('location: ./');
