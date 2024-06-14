<?php
$data = $data ?? [];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Page title -->
    <title><?php echo SITE_NAME ?></title>
    
    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <link rel="shortcut icon" type="image/ico" href="<?php echo ASSETS_ROOT ?>/images/favicon.png" />
    
    <!-- Font & Icon  -->
    <link href="<?php echo ASSETS_ROOT ?>/plugins/material-design-icons-iconfont/material-design-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet"/>
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/solid.min.css" rel="stylesheet"/> -->

    <!-- Plugins -->
    <link rel="stylesheet" href="<?php echo ASSETS_ROOT ?>/plugins/jqvmap/jqvmap.min.css">
    <link rel="stylesheet" href="<?php echo ASSETS_ROOT ?>/plugins/noty/noty.min.css">
    <link rel="stylesheet" href="<?php echo ASSETS_ROOT ?>/plugins/datatables/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo ASSETS_ROOT ?>/plugins/datatables/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo ASSETS_ROOT ?>/plugins/select2-3.5.2/select2.css" />
    <link rel="stylesheet" href="<?php echo ASSETS_ROOT ?>/plugins/select2-bootstrap/select2-bootstrap.css" />
    <link rel="stylesheet" href="<?php echo ASSETS_ROOT ?>/plugins/flatpickr/flatpickr.min.css" />
    <link rel="stylesheet" href="<?php echo ASSETS_ROOT ?>/plugins/perfect-scrollbar/perfect-scrollbar.css" />
    <!-- CSS plugins goes here -->
    
    <!-- Main Style -->
    <!-- <link rel="stylesheet" href="<?php echo ASSETS_ROOT ?>/css/custom.css" id="main-css"> -->
    <link rel="stylesheet" href="<?php echo ASSETS_ROOT ?>/css/style.min.css" id="main-css">
    <link rel="stylesheet" href="<?php echo ASSETS_ROOT ?>/css/style2.css">
    <link rel="stylesheet" href="<?php echo ASSETS_ROOT ?>/css/sidebar-std.css" id="sidebar-css">
    
    <!-- cdn fullcalendar -->
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css" rel="stylesheet" /> -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <style>
        .ui-timepicker-container{
            z-index: 1151 !important;
        }
        .select2-container--open{
            z-index: 99999
        }
    </style>
    <script>
        
        // window.history.pushState(null, "", window.location.href);
        // window.onpopstate = function () {
        //     window.history.pushState(null, "", window.location.href);
        // }
    
    </script>

</head>

<body <?php echo $data['body'] ?>>