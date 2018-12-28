<?php
use Cake\Core\Configure;
use Cake\Error\Debugger;

$this->layout = '';

if (Configure::read('debug')):
    $this->layout = 'dev_error';

    $this->assign('title', $message);
    $this->assign('templateName', 'error500.ctp');

    $this->start('file');
?>
<?php if (!empty($error->queryString)) : ?>
    <p class="notice">
        <strong>SQL Query: </strong>
        <?= h($error->queryString) ?>
    </p>
<?php endif; ?>
<?php if (!empty($error->params)) : ?>
        <strong>SQL Query Params: </strong>
        <?php Debugger::dump($error->params) ?>
<?php endif; ?>
<?php if ($error instanceof Error) : ?>
        <strong>Error in: </strong>
        <?= sprintf('%s, line %s', str_replace(ROOT, 'ROOT', $error->getFile()), $error->getLine()) ?>
<?php endif; ?>
<?php
    echo $this->element('auto_table_warning');

    if (extension_loaded('xdebug')):
        xdebug_print_function_stack();
    endif;

    $this->end();
endif;
?>
        
<!DOCTYPE html>
<html>
    <head>
        <title>Internal Server Error : <?= SITE_NAME ?></title>
        <base href="<?= HTTP_ROOT ?>" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet"> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="shortcut icon" type="image/x-icon" href="images/fav.png">        
        <link href="bootstrap-4/css/bootstrap.min.css" type="text/css" rel="stylesheet"/>
        <link href="css/style-paytring.css" type="text/css" rel="stylesheet"/>
        <script src="js/jquery-1.10.2.min.js" type="text/javascript"></script> 
    </head>
    <body>
        <div class="main">
            <div class="header success-page-header">                                 
                <div class="logo-header"><a href="#"><img src="images/logo.png" alt=""></a></div>
            </div>
            <div class="content-section">
                <div class="wrapper">                    
                    <div class="main-content-section success-page">                        
                        <div class="main-content ">
                            <div class="profile-data">
                                <h1>Internal Server Error Occurred!!</h1>
                                <p>&nbsp;</p>    
                                <p><a href="<?= HTTP_ROOT ?>" class="button-2">Go Back To Home</a></p>                                                          
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer">
                <div class="wrapper"><p class="copy-rt-text"><?= date('Y') ?> <?= SITE_NAME ?>. All Rights Reserved.</p></div>
            </div>
        </div>
    </body>
</html>


