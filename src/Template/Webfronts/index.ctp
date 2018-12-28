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
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" type="text/css" rel="stylesheet"/>
        <link href="css/style-paytring.css" type="text/css" rel="stylesheet"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js" type="text/javascript"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" type="text/javascript"></script>        
    </head>
    <body>
        <div class="main webfront-list">
            <div class="header">
                <div class="wrapper">                    
                    <a href="<?= HTTP_ROOT ?>" class="logo"><img src="images/logo.png" alt=""></a>             
                    <ul class="toplinks">
                        <?php if ($is_logged_in) { ?>
                            <?php if ($user_type == 1) { ?>
                                <li><a href="<?= HTTP_ROOT . 'admin' ?>" ><i class="fa fa-dashboard" aria-hidden="true"></i>Dashboard</a></li>
                            <?php } else if ($user_type == 2 || $user_type == 3) { ?>
                                <li><a href="<?= HTTP_ROOT . 'merchants' ?>" ><i class="fa fa-home" aria-hidden="true"></i>Profile</a></li>
                            <?php } ?>
                            <li><a href="<?= HTTP_ROOT ?>logout"><i class="fa fa-power-off" aria-hidden="true"></i>Logout</a></li>
                        <?php } else { ?>
                            <li><a href="<?= HTTP_ROOT . 'login' ?>"><i class="fa fa-lock" aria-hidden="true"></i> Log in</a></li>
                            <li><a href="<?= HTTP_ROOT . 'signup' ?>"><i class="fa fa-user" aria-hidden="true"></i> Sign Up</a></li>
                        <?php } ?>

                    </ul>                   
                </div>
            </div>
            <div class="content-section">
                <div class="wrapper">
                    <div class="search-listingcount">                       
                        
                        <div class="search-div">
                            <i class="fa fa-search" aria-hidden="true"></i>
                            <?= $this->Form->create(NULL, ['type' => 'GET']); ?>
                            <?= $this->Form->control('search', ['type' => 'text', 'class' => 'search-text', 'placeholder' => 'Search By Keyword', 'value' => @$_REQUEST['search'], 'label' => FALSE]) ?>
                            <?= $this->Form->button('Search', ['class' => "search-btn"]); ?>
                            <?= $this->Form->end(); ?>
                        </div>

                        <div class="showing-result"><?= $this->Paginator->counter('Showing {{start}}-{{end}} Webfronts out of {{count}}'); ?></div>
                    </div>
                    <div class="webfront-listing">
                        <ul class="webfront-listing-ul">
                            <?php foreach ($webfronts as $webfront) { ?>
                                <li>
                                    <div class="img-div"><img src="<?= $webfront->logo ? HTTP_ROOT . WEBFRONT_LOGO . $webfront->logo : HTTP_ROOT . 'images/not-available.png' ?>" alt=""></div>
                                    <h3><?= $webfront->title; ?></h3>
                                    <h4>Merchant <?= $webfront->merchant_name; ?></h4>
                                    <p><i class="fa fa-paper-plane" aria-hidden="true"></i><?= $webfront->address; ?></p>
                                    <p><i class="fa fa-mobile" aria-hidden="true"></i> <?= $webfront->phone; ?></p>
                                    <p><i class="fa fa-envelope-open-o" aria-hidden="true"></i> <?= $webfront->email; ?></p>
                                    <div class="pay-now-box">
                                        <a href="<?= HTTP_ROOT . $webfront->url ?>" target="_blank" class="button-3"><i class="fa fa-money" aria-hidden="true"></i> Pay Now</a>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                        <?php if ($this->Paginator->numbers()): ?>
                            <ul class="pagination-style">
                                <?= $this->Paginator->prev(('previous')) ?>
                                <?= $this->Paginator->numbers() ?>
                                <?= $this->Paginator->next(('next')) ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>           
            <div class="footer-div">
                <div class="wrapper"><p><?php echo date('Y'); ?> <?= SITE_NAME ?>. All Rights Reserved.</p></div>
            </div>
        </div>
    </body>
</html>    