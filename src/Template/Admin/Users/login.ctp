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
        <style>
            #flashError {color: red;}
        </style>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="bootstrap-4/js/bootstrap.min.js" type="text/javascript"></script>                
    </head>
    <body>
        <div class="main login-page">            
            <div class="content-section">
                <div class="wrapper">                   
                    <div class="logon-left-section">
                        <a href="javascript:;"><img src="images/login-logo.png" alt=""></a>
                        <p>LOGIN TO YOUR ACCOUNT</p>
                    </div>
                    <div class="logon-form-section">
                        <h1>Login</h1>
                        <?= $this->Flash->render(); ?> 
                        <?= $this->Form->create(NULL, ['type' => 'post']); ?>
                        <?= $this->Form->control('email', ['label' => FALSE, 'placeholder' => 'Email', 'required' => TRUE]); ?>
                        <?= $this->Form->control('password', ['label' => FALSE, 'placeholder' => 'Password', 'required' => TRUE]); ?>
                        <?= $this->Form->submit('submit', ['class' => 'button-4']); ?>
                        <?= $this->Form->end(); ?> 
                        <p class="login-links"><a href="<?= HTTP_ROOT . 'forgot-password' ?>"><i class="fa fa-lock" aria-hidden="true"></i> Forgot Password?</a></p>
                        <p class="login-links"><a href="<?= HTTP_ROOT . 'signup' ?>"><i class="fa fa-user" aria-hidden="true"></i> New to PayTring? Sign up now!</a></p>
                    </div>
                </div>
            </div>           
        </div>
    </body>
</html>