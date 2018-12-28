<!DOCTYPE html>
<html>
    <head>
        <title><?= isset($pageTitle) ? $pageTitle : SITE_NAME; ?></title>
        <base href="<?= HTTP_ROOT ?>"/>
        <meta name="viewport" content="width=device-width">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <link rel="shortcut icon" type="image/x-icon" href="<?= HTTP_ROOT ?>images/fav.png"/>   
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet"/> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
        <!--<link href="<?= HTTP_ROOT ?>bootstrap-4/css/bootstrap.min.css" type="text/css" rel="stylesheet"/>-->
        <link href="<?= HTTP_ROOT ?>css/style-paytring.css" type="text/css" rel="stylesheet"/>          
        <style>
            .razorpay-payment-button { margin-bottom: 8px !important;cursor: pointer;border: 0;text-align: center;display: inline-block;background: #3f51b5;color: #fff;font-size: 14px;text-transform: uppercase;padding: 10px 30px;border-radius: 3px; }
            .payment-div { text-align: center; }
            #razorpay-form { display: inline; }
            .customer-table tr th, .customer-table tr td, .payment-table tr th, .payment-table tr td {border-bottom: 1px solid #d7e0f1; border-right: 1px solid #d7e0f1; padding: 12px 10px;}
            .customer-table tr:last-child th, .customer-table tr:last-child td, .payment-table tr:last-child th, .payment-table tr:last-child td {border-bottom: none;}
            .full-page{width: 100%;float: left;background: #edf0f5;min-height: 751px;}
        </style>
        <style type="text/css" media="print">
            @page {size: auto; margin: 0mm;}
            @media print {                
                .important-links {display: none;}
            }
        </style>  
        <script>
            function printdiv(e) {
                window.print(e);
            }
        </script>
    </head>
    <body>
        <div class="main">            
            <div class="content-section">
                <div class="wrapper">                    
                    <div class="full-page publicpages full-page" id='div_print'>
                        <div class="box-wrapper" style="margin: 30px auto;"> 
                            <?= $this->Flash->render(); ?>
                            <?php if ($advance) { ?>
                                <?= $this->element('PreviewInvoice/advance') ?>
                            <?php } else { ?>
                                <?= $this->element('PreviewInvoice/basic') ?>
                            <?php } ?>
                            <p class="p-by text-center">Powered By&nbsp;<a href="<?= HTTP_ROOT ?>"><img src="<?= HTTP_ROOT ?>images/small-logo.png" style="vertical-align: middle;" alt="PayTring"></a></p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </body>
</html>