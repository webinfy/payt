<?php
$controller = $this->request->getParam('controller');
$action = $this->request->getParam('action');
?>
<div class="main-content-section">   
    <ul class="dashboard-menu nav nav-tabs">
        <li><a class="active" data-toggle="tab" href="#BasicInfo" >Basic Info</a></li>
        <li><a data-toggle="tab" href="#PaymentGateways">Payment Gateways </a></li>
        <li><a data-toggle="tab" href="#WebsiteAndSocial">Website & Social </a></li>
        <li><a data-toggle="tab" href="#ProfilePicture">Profile Picture </a></li>
        <li><a data-toggle="tab" href="#ChangePassword">Change Password </a></li>

    </ul>
    <div class="main-content">
        <div class="tab-content">            
            <?= $this->element('MerchantEdit/basic_info'); ?>
            <?= $this->element('MerchantEdit/payment_gateways'); ?>
            <?= $this->element('MerchantEdit/website_and_social'); ?>
            <?= $this->element('MerchantEdit/profile_picture'); ?>
            <?= $this->element('MerchantEdit/change_password'); ?>

        </div>
    </div>
</div>
<script>
    $(function () {
        if (location.href.indexOf("#") != -1) {
            var hash = window.location.hash;
            $('a[data-toggle="tab"][href="' + hash + '"]').trigger('click');
        }
        $('a[data-toggle="tab"]').click(function () {
            var hash = $(this).attr('href');
            if (hash) {
                window.location.hash = hash;
            }
        });
    });
</script>
