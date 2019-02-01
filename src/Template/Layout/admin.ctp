<!DOCTYPE html>
<html>
    <head>
        <title><?= isset($pageTitle) ? $pageTitle : SITE_NAME; ?></title>
        <base href="<?= HTTP_ROOT ?>" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet"> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="shortcut icon" type="image/x-icon" href="favicon.png">          
        <link href="bootstrap-4/css/bootstrap.min.css" type="text/css" rel="stylesheet"/>
        <link href="css/style-paytring.css" type="text/css" rel="stylesheet"/>
        <link href="css/sweetalert.css" type="text/css" rel="stylesheet"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="bootstrap-4/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="js/jquery.validate.min.js" language="javascript"></script>
        <script src="js/sweetalert.min.js" language="javascript"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <style> 
            #flashError {cursor: pointer;font-size: 15px; font-weight: bold;overflow: hidden;padding: 10px 25px;position: fixed;text-align: left;width:auto;z-index: 999999;margin-left: 46%; top: 9%; background-color: #E6250E; color: #FFF;border-radius: 7px;}
            #flashSuccess {cursor: pointer;font-size: 15px; font-weight: bold;overflow: hidden;padding: 10px 25px;position: fixed;text-align: left;width:auto;z-index: 999999;margin-left: 42%; top: 9%; background-color: #0E9F52; color: #FFF;border-radius: 15px;}
            .error-message{color: rgb(255, 2, 2);font-size: 12px;float: left;width: 65%;margin-left: 160px;margin-bottom: 0;margin-top: 4px;}
        </style>
        <script>
            $(document).ready(function () {
                $(".account-menu").click(function () {
                    $(".toplinks-ul").slideToggle();
                });
                $(".action-btn").click(function () {
                    if ($(this).find(".action-btn-list").is(':visible')) {
                        $(this).find(".action-btn-list").fadeOut();
                    } else {
                        $(".action-btn-list").fadeOut();
                        $(this).find(".action-btn-list").fadeIn();
                    }
                });
                $(".left-menu-icon").click(function () {
                    if ($(".slider-left").width() == 200) {
                        $(".slider-left").animate({width: '0px'});
                    } else {
                        $(".slider-left").animate({width: '200px'});
                    }
                });

                $('html').click(function (leftMenu) {
                    if ($(window).width() < 480) {
                        if ($(leftMenu.target).closest('.left-menu-icon, .slider-left').length === 0) {
                            $(".slider-left").animate({width: '0px'});
                        }
                    }
                });
            });
            
            var customConfirm = function(message, link) { 
                var message = message ? message : "You won't be able to revert this!";
                swal({
                    title: 'Are you sure?',
                    text: message,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'rgb(140, 212, 245)',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirm'
                },
                function (isConfirm) {
                    if (isConfirm) {
                        window.location.href = link;
                    }
                });
            };  
        </script>       
    </head>
    <body>
        <div class="main">

            <div class="header">
                <div class="wrapper">
                    <div class="left-menu-icon"><i class="fa fa-bars" aria-hidden="true"></i></div>
                    <div class="logo-header"><a href="<?= HTTP_ROOT ?>"><img src="images/logo.png" alt=""></a></div>
                    <div class="header-right">
                        <h1><?= !empty($pageHeading) ? $pageHeading : "Admin Dashboard"; ?></h1> 
                        <div class="account-menu"><i class="fa fa-th" aria-hidden="true"></i></div>
                        <ul class="toplinks-ul">
                            <li><a href="<?= HTTP_ROOT . "admin/account-setup" ?>"><i class="fa fa-desktop" aria-hidden="true"></i> Account Setup</a></li>
                            <li><a href="javascript:;"><i class="fa fa-eur" aria-hidden="true"></i> Help</a></li>
                            <li><a href="<?= HTTP_ROOT . "logout" ?>"><i class="fa fa-power-off" aria-hidden="true"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <?= $this->Flash->render(); ?>

            <div class="content-section">
                <div class="wrapper">
                    <?= $this->element('Admin/sidebar') ?>                                     
                    <?= $this->fetch('content') ?>
                </div>
            </div>          

            <div class="footer">
                <div class="wrapper"><p class="copy-rt-text"><?= date('Y') ?> <?= SITE_NAME ?>. All Rights Reserved.</p></div>
            </div>

        </div>
    </body>
</html>

