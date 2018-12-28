<!DOCTYPE html>
<html>
    <head>
        <title>PayTring</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet"> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="shortcut icon" type="image/x-icon" href="images/fav.png">        
        <link href="bootstrap-4/css/bootstrap.min.css" type="text/css" rel="stylesheet"/>
        <link href="css/style-paytring.css" type="text/css" rel="stylesheet"/>
        <script src="js/jquery-1.10.2.min.js" type="text/javascript"></script>
        <script src="bootstrap-4/js/bootstrap.min.js" type="text/javascript"></script>        
        <script>
            $(document).ready(function () {

                $(".account-menu").click(function () {
                    $(".toplinks-ul").slideToggle();
                });

                $('html').click(function (accountMenu) {
                    if ($(accountMenu.target).closest('.account-menu, .toplinks-ul').length === 0) {
                        $('.toplinks-ul').fadeOut();
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

                $(window).resize(function () {
                    if ($(this).width() > 480) {
                        $(".slider-left").removeAttr("style");
                    }
                });

                $(".action-btn").click(function () {
                    if ($(this).find(".action-btn-list").is(':visible')) {
                        $(this).find(".action-btn-list").fadeOut();
                    } else {
                        $(".action-btn-list").fadeOut();
                        $(this).find(".action-btn-list").fadeIn();
                    }
                });

                $('html').click(function (actionBtn) {
                    if ($(actionBtn.target).closest('.action-btn, .action-btn-list').length === 0) {
                        $(".action-btn-list").fadeOut();
                    }
                });

            });
        </script>      
    </head>
    <body>
        <div class="main">
            <div class="header success-page-header">                                 
                <div class="logo-header"><a href="<?= HTTP_ROOT ?>"><img src="images/logo.png" alt=""></a></div>
            </div>
            <div class="content-section">
                <div class="wrapper">                    
                    <div class="main-content-section success-page">                        
                        <div class="main-content ">
                            <div class="profile-data">
                                <h1>Thank you for your interst! Check your email for a link to the guide</h1>
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard.Lorem Get more marketing tips in our Marketing Resourse Library. Lorem Ipsum has been the industry's standard.</p>
                                <p><a href="<?php echo HTTP_ROOT . "login/" ?>" class="button-2">Login Your Account</a></p> 
                                <p>&nbsp;</p>                              
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer">
                <div class="wrapper"><p class="copy-rt-text">2018 Paytring. All Rights Reserved.</p></div>
            </div>
        </div>
    </body>
</html>