<?php
$action = $this->request->getParam('action');
?>
<style>
    .sub-menu > li.active {background: #edf0f8;}
    .left-menu > li.active {background: #edf0f8;}
</style>
<div class="slider-left">
    <div class="profile-data-section">
        <?php if ($this->request->getSession()->read('Auth.User.type') == 2) { ?>
            <div class="profile-pic"><img src="<?= HTTP_ROOT; ?><?= MERCHANT_LOGO; ?><?= @$loginDetails->merchant->logo ? $loginDetails->merchant->logo : 'noimage.png'; ?>" alt="profile_pic.png"></div>
        <?php } else if ($this->request->getSession()->read('Auth.User.type') == 3) { ?>
            <div class="profile-pic"><img src="<?= HTTP_ROOT; ?><?= MERCHANT_LOGO; ?><?= @$loginDetails->employee->profile_pic ? $loginDetails->employee->profile_pic : 'noimage.png'; ?>" alt="profile_pic.png"></div>
        <?php } ?>
            <div class="profile-name" style="max-width: 150px;">Hi, <?= ucwords($loginDetails->name) ?></div>
    </div>
    <div class="creat-wf-section">
        <a href="<?= HTTP_ROOT . "merchants/create-basic-webfront" ?>" class="button-1">Create Webfront</a>
    </div>
    <ul class="left-menu">
        <li class="<?php if (in_array($action, ['index'])) { ?>active<?php } ?>"><a href="<?= HTTP_ROOT ?>merchants"><i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard</a></li>
        
        <?php if ($this->request->getSession()->read('Auth.User.type') == 2) { ?>
            <li class="<?php if (in_array($action, ['viewProfile'])) { ?>active<?php } ?>">
                <a href="<?= HTTP_ROOT . "merchants/view-profile"; ?>"><i class="fa fa-desktop"></i> View Profile</a>           
            </li>  
        <?php } else if ($this->request->getSession()->read('Auth.User.type') == 3) { ?>
            <li class="<?php if (in_array($action, ['myProfile'])) { ?>active<?php } ?>">
                <a href="<?= HTTP_ROOT . "view-profile"; ?>"><i class="fa fa-desktop"></i> View Profile</a>           
            </li>  
        <?php } ?>
        
        <li>
            <a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-credit-card-alt" aria-hidden="true"></i>Webfronts</a>
            <ul class="sub-menu collapse <?php if (strpos($action, 'Webfronts')) { ?> show <?php } ?>" id="demo">
                <li class="<?php if (in_array($action, ['basicWebfronts'])) { ?>active<?php } ?>"><a href="<?= HTTP_ROOT . "basic-webfronts" ?>"><i class="fa fa-angle-right" aria-hidden="true"></i>Basic Webfronts</a></li>
                <li class="<?php if (in_array($action, ['advanceWebfronts'])) { ?>active<?php } ?>"><a href="<?= HTTP_ROOT . "advance-webfronts" ?>"><i class="fa fa-angle-right" aria-hidden="true"></i>Advance Webfronts </a></li>
            </ul>
        </li>
        <li>
            <a href="<?= HTTP_ROOT . "advance-webfront-reports" ?>" data-toggle="collapse" data-target="#demoReport"><i class="fa fa-clipboard" aria-hidden="true"></i> Reports</a>
            <ul class="sub-menu collapse <?php if (strpos($action, 'Report')) { ?> show <?php } ?>" id="demoReport">
                <li class="<?php if (in_array($action, ['basicWebfrontReports'])) { ?>active<?php } ?>"><a href="<?= HTTP_ROOT . "basic-webfront-reports" ?>"><i class="fa fa-angle-right" aria-hidden="true"></i>Basic Webfront</a></li>
                <li class="<?php if (in_array($action, ['advanceWebfrontReports'])) { ?>active<?php } ?>"><a href="<?= HTTP_ROOT . "advance-webfront-reports" ?>"><i class="fa fa-angle-right" aria-hidden="true"></i>Advance Webfront</a></li>
            </ul>
        </li>
        <?php if ($this->request->getSession()->read('Auth.User.type') == 2) { ?>
            <li>
                <a href="<?= HTTP_ROOT . "add-new-user" ?>" data-toggle="collapse" data-target="#demoUser"><i class="fa fa-user" aria-hidden="true"></i> Manage Users</a>
                <ul class="sub-menu collapse <?php if (strpos($action, 'User')) { ?> show <?php } ?>" id="demoUser">
                    <li class="<?php if (in_array($action, ['addNewUser'])) { ?>active<?php } ?>"><a href="<?= HTTP_ROOT . 'users/add-new-user'; ?>"><i class="fa fa-angle-right" aria-hidden="true"></i>Add New User</a></li>
                    <li class="<?php if (in_array($action, ['viewAllUser'])) { ?>active<?php } ?>"><a href="<?= HTTP_ROOT . 'users/view-all-user'; ?>"><i class="fa fa-angle-right" aria-hidden="true"></i>View All User </a></li>
                </ul>
            </li>
        <?php } ?>

        <li><a href="<?= HTTP_ROOT . "logout" ?>"><i class="fa fa-power-off" aria-hidden="true"></i> Logout</a></li>
    </ul>
</div>