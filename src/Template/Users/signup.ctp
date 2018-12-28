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
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>        
        <script src="bootstrap-4/js/bootstrap.min.js" type="text/javascript"></script>   
        <script src="js/common.js" type="text/javascript"></script>   
        <script src="js/jquery.validate.min.js" language="javascript"></script>
       
    </head>
    <body>
        <?= $this->Flash->render(); ?>
        <div class="main register-page">            
            <div class="content-section">
                <div class="wrapper">                   
                    <div class="logon-left-section">
                        <a href="<?= HTTP_ROOT ?>"><img src="images/login-logo.png" alt=""></a>
                        <p>Signup here, it's quick and easy.</p>
                    </div>
                    <div class="logon-form-section">
                        <h1>Create an Account</h1>
                        <?= $this->Form->create($userEntity, ['type' => 'post', 'id' => 'registerForm']); ?>                           
                        <?= $this->Form->control('name', ['label' => FALSE, 'placeholder' => 'Name','id' => 'webfrontTitleInput']); ?>
                        <?= $this->Form->hidden('merchant.profile_url', ['id' => 'webfrontUrlInput']); ?>
                        <?= $this->Form->control('email', ['label' => FALSE, 'placeholder' => 'Email']); ?>
                        <?= $this->Form->control('phone', ['label' => FALSE, 'placeholder' => 'Phone']); ?>
                        <?= $this->Form->control('password', ['label' => FALSE, 'placeholder' => 'Password']); ?>
                        <?= $this->Form->control('conf_password', ['type' => 'password', 'label' => FALSE, 'placeholder' => 'Confirm Password']); ?>                        
                        <?= $this->Form->submit('register', ['class' => 'button-4 btn100']); ?>

                        <p class="login-links"><a href="<?= HTTP_ROOT . 'login' ?>"><i class="fa fa-user" aria-hidden="true"></i> Already registered? Log In</a></p>
                        <p class="login-links">By signing up, you agree to our <a href="javascript:;">Terms & Conditions</a> and <a href="javascript:;">Privacy Policy.</a></p>
                    </div>
                </div>
            </div>           
        </div>

        <script type="text/javascript">
            $(function () {
                $('#registerForm').validate({
                    ignore: [],
                    rules: {
                        name: 'required',
                        password: 'required',
                        phone: {
                            'required' : true,
                            'number' : true,
                            'minlength' : 10,
                        },
                        conf_password: {
                            required: true,
                            equalTo: "#password"
                        },
                        email: {
                            required: true,
                            email: true,
                            remote: "users/ajaxCheckEmailAvail"
                        },
                    },
                    messages: {
                        name: "Enter you name!!",
                        password: "Please enter a new password!!",
                        phone: {
                            required : "Please enter phone!!",
                            number : "Phone No. should be numeric!!",
                            minlength : "Invalid Phone No.!!",
                        },
                        conf_password: {
                            required: "Please enter a confirm password!!",
                            equalTo: "Passsword & confirm password are not matching!!"
                        },
                        email: {
                            required: "Please enter your email id!!",
                            email: "Please enter valid email id!!",
                            remote: "Email already exits!"
                        },
                    },
                    submitHandler: function (form) {
                        return true;
                    }
                });
            });
        </script>
    </body>
</html>