<!DOCTYPE html>
<html>
    <head>
        <title><?= isset($pageTitle) ? $pageTitle : SITE_NAME; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet"> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="shortcut icon" type="image/x-icon" href="favicon.png">       
        <link href="bootstrap-4/css/bootstrap.min.css" type="text/css" rel="stylesheet"/>
        <link href="css/style-paytring.css" type="text/css" rel="stylesheet"/>
        <style>
            #flashError {cursor: pointer;font-size: 15px;font-weight: bold;overflow: hidden;padding: 10px 25px;position: fixed;text-align: left;width: auto;z-index: 999999;margin-left: -1%;top: 6%;background-color: #E6250E;color: #FFF;border-radius: 7px;}
            #flashSuccess {cursor: pointer;font-size: 15px; font-weight: bold;overflow: hidden;padding: 10px 25px;position: fixed;text-align: left;width:auto;z-index: 999999;margin-left: -1%; top: 6%; background-color: #0E9F52; color: #FFF;border-radius: 15px;}
        </style>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="bootstrap-4/js/bootstrap.min.js" type="text/javascript"></script>         
    </head>
    <body>
        <div class="main register-page">            
            <div class="content-section">
                <div class="wrapper">                   
                    <div class="logon-left-section">
                        <a href="<?= HTTP_ROOT ?>"><img src="images/login-logo.png" alt=""></a>
                        <p>Retrieve Password</p>
                    </div>
                    <div class="logon-form-section">
                        <h1>Retrieve Password</h1>
                        <?= $this->Flash->render(); ?>
                        <?= $this->Form->create(NULL, ['type' => 'post']); ?>
                        <?= $this->Form->control('phone', ['label' => FALSE, 'placeholder' => 'Enter Email or Phone', 'required' => TRUE]); ?>
                        <?= $this->Form->submit('Send OTP', ['class' => 'button-4 btn100']); ?>
                        <?= $this->Form->end(); ?> 
                        <p class="login-links"><a href="<?= HTTP_ROOT . 'login' ?>"><i class="fa fa-user" aria-hidden="true"></i> Back to Login</a></p>
                    </div>
                </div>
            </div>           
        </div>
    </body>
</html>