<!DOCTYPE html>
<html>
    <head>
        <title><?= SITE_NAME ?> : <?= $webfront->title ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <base href="<?= HTTP_ROOT ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet"> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="shortcut icon" type="image/x-icon" href="favicon.png">  

        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" type="text/css" rel="stylesheet"/>
        <link href="css/style-paytring.css" type="text/css" rel="stylesheet"/>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js" type="text/javascript"></script>         
        <script src="js/jquery.validate.min.js" type="text/javascript"></script>
        <script>
            $(document).ready(function () {
                var showChar = 230;
                var ellipsestext = "...";
                var moretext = "View More";
                var lesstext = "View less";


                $('.more').each(function () {
                    var content = $(this).html();

                    if (content.length > showChar) {

                        var c = content.substr(0, showChar);
                        var h = content.substr(showChar, content.length - showChar);

                        var html = c + '<span class="moreellipses">' + ellipsestext + '&nbsp;</span><span class="morecontent"><span>' + h + '</span><a href="javascript:;" class="morelink">' + moretext + '</a></span>';

                        $(this).html(html);
                    }

                });

                $(".morelink").click(function () {
                    if ($(this).hasClass("less")) {
                        $(this).removeClass("less");
                        $(this).html(moretext);
                    } else {
                        $(this).addClass("less");
                        $(this).html(lesstext);
                    }
                    $(this).parent().prev().toggle();
                    $(this).prev().toggle();
                    return false;
                });
                $(".pay-online-popup-btn,.close-form").click(function () {
                    $(".online-payment-popup-box").slideToggle();
                });
            });

        </script>   
        <style>
            .main-content .more { float: left;padding-right: 0; }
            .main-content .payment-details-p { display: inline-block; }
            .main-content .text-right { float: right;padding-right: 0; }
            .morecontent span { display: none; }
            .morelink { display: inline; }
            .pay-online-form label { font-weight: 600;float: none; }
            .pay-online-form .textarea-box { width: 100%;height: 90px; }
            #making-inline { display: inline;font-weight: 300; }
            .pay-form-select { width: 100%;height: 42px; }
            .online-refno .payment-attr{ width: 100% }
            .recent-payment-list { float: left;width: 100%;word-break: break-all; }
            .payment-list-table { margin-bottom: 10px; }
            .name-row:first-child { padding-left: 20px;text-align: left; }
        </style>
    </head>
    <body>
        <div class="main">            
            <div class="content-section">
                <div class="wrapper">                    
                    <div class="main-content-section publicpages full-page">
                        <div class="box-wrapper">
                            <div class="main-content">
                                <h1><?= $webfront->title; ?></h1>
                                <div class="text-center bank-logo-picXX"><img src="<?= $webfront->logo ? HTTP_ROOT . WEBFRONT_LOGO . $webfront->logo : HTTP_ROOT . 'images/not-available.png' ?>" alt="Logo Not Available" style="margin-bottom: 20px;"></div>
                                <p class="more"><?= $webfront->description ?></p>
                                <?php if ($webfront->type == 0) { ?>
                                    <div class="online-refno">

                                        <h3>Enter your Reference</h3>

                                        <div class="form-div">                                            
                                            <?= $this->Form->create(NULL, ['type' => 'GET']); ?>
                                            <?= $this->Form->control('ref_no', ['type' => 'text', 'class' => 'text-box', 'placeholder' => 'Enter your Reference No.', 'label' => FALSE]); ?>
                                            <input type="submit" class="button-3" value="Get Details">
                                            <?= $this->Form->end() ?>
                                        </div>

                                        <?php if (!empty($_REQUEST['ref_no'])) { ?>
                                            <table width="100%" align="left" cellpadding="0" cellspacing="0" class="payment-list-table">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Reference No</th>
                                                        <th>Bill Amount</th>
                                                        <th>Due Date</th>
                                                        <th>Status</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($recentInvoices as $payment) { ?>
                                                        <tr>
                                                            <td><?= $payment->name; ?></td>
                                                            <td><?= $payment->reference_number; ?></td>
                                                            <td>Rs. <?= formatPrice($payment->fee); ?></td>
                                                            <td><?= date_format($payment->uploaded_payment_file->payment_cycle_date, 'd M, Y'); ?></td>
                                                            <td>
                                                                <?php if ($payment->status == 0) { ?>
                                                                    <a href="javascript:;" class="un-paid">Un Paid</a>
                                                                <?php } else { ?>
                                                                    <a href="javascript:;" class="paid">Paid</a>
                                                                <?php } ?>
                                                            </td>
                                                            <td><a href="<?= HTTP_ROOT . "preview-invoice/" . $payment->uniq_id ?>" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
                                                        </tr>
                                                    <?php } ?>  
                                                    <?php if ($recentInvoices->count() == 0) { ?>   
                                                        <tr><td colspan="6"><h4 style='color:red;font-size:15px;text-align: center;'>No Invoice found with the Reference No. <b><?= $_REQUEST['reference_number'] ?></b></h4></td><tr>                                            
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        <?php } ?>
                                    </div>

                                <?php } else { ?>

                                    <?php if (!empty($webfront->merchant->merchant_payment_gateway)) { ?>
                                        <p class="text-right"><a class="button-3 pay-online-popup-btn" href="javascript:void(0);">Pay Online</a></p>  
                                        <div class="online-refno online-payment-popup-box" style="display: none;">
                                            <br/>
                                            <!-- Pay Online Form Starts -->
                                            <?= $this->element('webfront_url_online_pay_form') ?>
                                            <!-- Pay Online Form Ends -->
                                        </div>
                                    <?php } ?>
                                        
                                    <?php if ($recentPayments && $recentPayments->count() > 0) { ?>                                
                                        <div class="list-of-payment">
                                            <h2 class="text-center">Recent Payments</h2>
                                            <ul>
                                                <?php foreach ($recentPayments as $recentPayment) { ?>
                                                    <li><?= $recentPayment->name ?> : <span>Rs.<?= formatPrice($recentPayment->paid_amount) ?></span></li>    
                                                <?php } ?>    
                                            </ul>
                                        </div>  
                                    <?php } ?>
                                <?php } ?>

                                <div class="merchant-getintuch">
                                    <div class="merchant-info">                               
                                        <img src="<?= HTTP_ROOT . MERCHANT_LOGO ?><?= @$webfront->merchant->logo ? $webfront->merchant->logo : 'noimage.png'; ?>" alt="profile-pic.jpg">
                                        <h3><?= $webfront->user->name; ?></h3>                
                                    </div>
                                    <div class="getintuch-left">
                                        <h2>Get in Touch</h2>
                                        <p><i class="fa fa-phone-square" aria-hidden="true"></i><?= $webfront->phone; ?> </p>
                                        <p><i class="fa fa-envelope" aria-hidden="true"></i> <a class="mail_thrw ng-binding" href="mailto:<?= $webfront->user->email; ?>"><?= $webfront->user->email; ?></a></p>
                                        <p class="address-p"><i class="fa fa-map-marker" aria-hidden="true"></i> <?= $webfront->merchant->address; ?></p>
                                    </div>
                                </div>

                            </div>
                            <p class="p-by text-center">Powered By <a href="<?= HTTP_ROOT ?>"><img src="<?= HTTP_ROOT ?>images/small-logo.png" alt="<?= SITE_NAME ?>"></a></p>
                        </div>
                    </div>
                </div>
            </div>            
        </div>
    </body>
</html>