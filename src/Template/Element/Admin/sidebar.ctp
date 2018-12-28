<?php
$controller = $this->request->getParam('controller');
$action = $this->request->getParam('action');
?>
<style>
    .sub-menu > li.active {background: #edf0f8;}
    .left-menu > li.active {background: #edf0f8;}
</style>
<div class="slider-left">
    <div class="profile-data-section">       
        <div class="profile-name" style="margin-top: 0;">Hi, <?= $loginDetails->name ?></div>
    </div>
    <ul class="left-menu">
        <li <?php if ($action == 'dashboard') { ?> class="active" <?php } ?>><a href="<?= HTTP_ROOT ?>admin"><i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard</a></li>
        <li <?php if ($action == 'accountSetup') { ?> class="active" <?php } ?>><a href="<?= HTTP_ROOT . "admin/account-setup"; ?>"><i class="fa fa-desktop"></i> Account Setup</a></li> 
        <li <?php if ($action == 'merchantListing') { ?> class="active" <?php } ?>><a href="<?= HTTP_ROOT . "admin/merchant-listing"; ?>"><i class="fa fa-users"></i> Merchants</a></li>       
        <li <?php if ($action == 'index') { ?> class="active" <?php } ?>><a href="<?= HTTP_ROOT . "admin/webfronts"; ?>"><i class="fa fa-money"></i> Webfronts</a></li>       
        <li <?php if ($action == 'viewPayments') { ?> class="active" <?php } ?>><a href="<?= HTTP_ROOT . "admin/payments"; ?>"><i class="fa fa-money"></i> Webfront Invoices</a></li>       
        <!--<li>
            <a href="javascript:;" data-toggle="collapse" data-target="#sub-merchants"><i class="fa fa-sitemap" aria-hidden="true"></i> Sub-Merchants</a>
            <ul class="sub-menu collapse <?php if (strpos($action, 'Submerchant')) { ?> show <?php } ?>" id="sub-merchants">
                <li class="<?php if (in_array($action, ['addSubmerchant'])) { ?>active<?php } ?>"><a href="<?= HTTP_ROOT . "admin/add-submerchant" ?>"><i class="fa fa-angle-right" aria-hidden="true"></i> Add New Sub-Merchant </a></li>
                <li class="<?php if (in_array($action, ['viewSubmerchants'])) { ?>active<?php } ?>"><a href="<?= HTTP_ROOT . "admin/view-submerchants" ?>"><i class="fa fa-angle-right" aria-hidden="true"></i> View All Sub-Merchants </a></li>
            </ul>
        </li>-->       
        <li <?php if ($action == 'viewEmailTemplates') { ?> class="active" <?php } ?>><a href="<?= HTTP_ROOT . "admin/view-email-templates"; ?>"><i class="fa fa-envelope" aria-hidden="true"></i> Email Templates</a></li>        
        <li><a href="<?= HTTP_ROOT . "logout" ?>"><i class="fa fa-power-off" aria-hidden="true"></i> Logout</a></li>
    </ul>
</div>