<!DOCTYPE html>
<html>
    <head>
        <title><?= isset($pageTitle) ? $pageTitle : SITE_NAME; ?></title>
        <base href="<?= HTTP_ROOT ?>" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet"> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="shortcut icon" type="image/x-icon" href="images/fav.png">        
        <link href="bootstrap-4/css/bootstrap.min.css" type="text/css" rel="stylesheet"/>
        <link href="css/style-paytring.css" type="text/css" rel="stylesheet"/>
        <script src="js/jquery-1.10.2.min.js" type="text/javascript"></script>
        <script src="bootstrap-4/js/bootstrap.min.js" type="text/javascript"></script>   
        <style>
            #flashError {cursor: pointer;font-size: 15px;font-weight: bold;overflow: hidden;padding: 10px 25px;position: fixed;text-align: left;width: auto;z-index: 999999;margin-left: -1%;top: 6%;background-color: #E6250E;color: #FFF;border-radius: 7px;}
            #flashSuccess {cursor: pointer;font-size: 15px; font-weight: bold;overflow: hidden;padding: 10px 25px;position: fixed;text-align: left;width:auto;z-index: 999999;margin-left: 39%; top: 10%; background-color: #0E9F52; color: #FFF;border-radius: 15px;}
        </style>
    </head>
    <body>
        <?= $this->Flash->render(); ?>
        <div class="main">
            <div class="header success-page-header">                                 
                <div class="logo-header"><a href="<?= HTTP_ROOT ?>"><img src="images/logo.png" alt=""></a></div>
            </div>
            <?= $this->Flash->render(); ?>
            <div class="content-section">
                <div class="wrapper">                    
                    <div class="main-content-section success-page">                        
                        <div class="main-content ">
                            <div class="profile-data">
                                <h1>Registered Successfully!!</h1>
                                <p style="text-align: left;">
                                    Thank you for creating a merchant account with <a href="javascript:;"><?= SITE_NAME ?></a>. Please complete your registration by clicking on the account activation link in the confirmation email we have just sent to your email <b>"<?= $user->email; ?></b>". <br/><br/>
                                    Please note: Your confirmation email may take a few minutes to arrive. If you don’t receive your email after 5-10 minutes, please refresh your inbox & check your spam/junk mail folder.
                                    To resend you activation email please <a href="<?= HTTP_ROOT . "signup-success/" . $user->uniq_id . "?resendemail" ?>">click here</a>.
                                </p>                                
                                <p><a href="<?= HTTP_ROOT . "login" ?>" class="button-2">Login Your Account</a></p> 
                                <p>&nbsp;</p>                              
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="footer">
                    <div class="wrapper"><p class="copy-rt-text"><?= date('Y') ?> <?= SITE_NAME ?>. All Rights Reserved.</p></div>
                </div>
            </div>
        </div>        
    </body>
</html>