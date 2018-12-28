<!DOCTYPE html>
<html>
    <head>
        <title><?= SITE_NAME ?> : Account Activated</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet"> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="shortcut icon" type="image/x-icon" href="images/fav.png">        
        <link href="bootstrap-4/css/bootstrap.min.css" type="text/css" rel="stylesheet"/>
        <link href="css/style-paytring.css" type="text/css" rel="stylesheet"/>
        <script src="js/jquery-1.10.2.min.js" type="text/javascript"></script>
        <script src="bootstrap-4/js/bootstrap.min.js" type="text/javascript"></script>        
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
                                <h1>Account Activated Successfully!!</h1>
                                <p>
                                    Congratulations!! you account has been activated successfully!!. <br/> 
                                    You will be automatically redirected to login page after 5 seconds. You can also click below button to navigate to the login page as well. 
                                </p>
                                <p><a href="<?= HTTP_ROOT . "login" ?>" class="button-2">Login Your Account</a></p> 
                                <p>&nbsp;</p>                              
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer">
                <div class="wrapper"><p class="copy-rt-text"><?= date('Y') ?> <?= SITE_NAME ?>. All Rights Reserved.</p></div>
            </div>
        </div>
        <script>
          setTimeout(function () {
              window.location = "<?= HTTP_ROOT . "login" ?>";
          }, 5000);
        </script>
    </body>
</html>