<div class="slider-left">
    <div class="profile-data-section">
        <div class="profile-pic">
            <img src="<?= HTTP_ROOT; ?>img/profile-pic.jpg" alt=""></div>
        <div class="profile-name">Hi, <?= $loginDetails['name'] ?></div>
        <div class="profile-name">Welcome to Merchant Panel <br/>Last Login : <?= date('M d, Y H:i A', strtotime($loginDetails['last_login_date'])) ?></div>
    </div>
    <div class="creat-wf-section">
        <a href="merchants/create-basic-webfront" class="button-1">Create Webfront</a>
    </div>
    <ul class="left-menu">
        <li><a href="<?= HTTP_ROOT ?>merchants"><i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard</a></li>
        <li><a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-desktop" aria-hidden="true"></i> Account Setup</a>
            <ul class="sub-menu" id="demo" class="collapse">
                <li><a href="<?= HTTP_ROOT ;?>merchants/account-setup"><i class="fa fa-angle-right" aria-hidden="true"></i> Profile Setting</a></li>
                <li><a href="<?= HTTP_ROOT ;?>merchants/payu-info"><i class="fa fa-angle-right" aria-hidden="true"></i> Pay Info</a></li>
                <li><a href="<?= HTTP_ROOT ;?>merchants/website_and_social"><i class="fa fa-angle-right" aria-hidden="true"></i> Website & Social</a></li>
                <li><a href="<?= HTTP_ROOT ;?>merchants/merchant-logo"><i class="fa fa-angle-right" aria-hidden="true"></i> Merchant Logo</a></li>
                <li><a href="<?= HTTP_ROOT ;?>merchants/change-password"><i class="fa fa-angle-right" aria-hidden="true"></i> Change Password</a></li>
            </ul>
        </li>
        <li><a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-credit-card-alt" aria-hidden="true"></i> Webfronts</a>
            <ul class="sub-menu" id="demo" class="collapse">
                <li><a href="<?= HTTP_ROOT ;?>merchants/basic-webfronts"><i class="fa fa-angle-right" aria-hidden="true"></i> Basic Webfronts</a></li>
                <li><a href="<?= HTTP_ROOT ;?>merchants/advance-webfronts"><i class="fa fa-angle-right" aria-hidden="true"></i> Advance Webfronts </a></li>
            </ul>
        </li>
        <li><a href="merchants/reports"><i class="fa fa-clipboard" aria-hidden="true"></i> Reports</a></li>
        <?php if ($this->request->getSession()->read('Auth.User.access') == 1) { ?>
            <li><a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-user" aria-hidden="true"></i> Manage User</a>
                <ul class="sub-menu" id="demo" class="collapse">
                    <li><a href="<?= HTTP_ROOT . 'merchants/add-new-user'; ?>"><i class="fa fa-angle-right" aria-hidden="true"></i> Add New User</a></li>
                    <li><a href="<?= HTTP_ROOT . 'merchants/view-all-user'; ?>"><i class="fa fa-angle-right" aria-hidden="true"></i> View All User </a></li>
                </ul>
            </li>
        <?php } ?>
        <li><a href="#"><i class="fa fa-power-off" aria-hidden="true"></i> Logout</a></li>
    </ul>
</div>
<script>
    $(document).ready(function () {
        $(".account-menu").click(function () {
            $(".toplinks-ul").slideToggle();
        });
    });
</script>