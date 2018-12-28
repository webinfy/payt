<div class="main-content-section">
    <ul class="dashboard-menu">
        <li><a href="<?= HTTP_ROOT . "admin/account-setup"; ?>">Profile Setting</a></li>
        <li><a class="active" href="<?= HTTP_ROOT . "admin/admin-settings"; ?>">Application Setting</a></li>
        <li><a href="<?= HTTP_ROOT . "admin/change-password"; ?>">Change Password</a></li>
    </ul>
    <div class="main-content">
        
        <?= $this->Form->create($adminSetting, ['class' => 'main-form', 'id' => 'adminSettings']); ?>
        <div class="form-div">
            <label class="form-label">Site Name <span class="required-fild">*</span>:</label>
            <?= $this->Form->control('site_name', ['placeholder' => 'Site Name', 'class' => 'text-box', 'label' => false]); ?>
        </div>
        <div class="form-div">
            <label class="form-label">Admin Email <span class="required-fild">*</span>:</label>
            <?= $this->Form->control('admin_email', ['placeholder' => 'Admin Email', 'class' => 'text-box', 'label' => false]); ?>
        </div>
        <div class="form-div">
            <label class="form-label">From Email <span class="required-fild">*</span>:</label>
            <?= $this->Form->control('from_email', ['placeholder' => 'From Email', 'class' => 'text-box', 'label' => false]); ?>
        </div>
        <div class="form-div">
            <label class="form-label">BCC Email <span class="required-fild">*</span>:</label>
            <?= $this->Form->control('bcc_email', ['placeholder' => 'BCC Email', 'class' => 'text-box', 'label' => false]); ?>
        </div>
        <div class="form-div">
            <label class="form-label">Support Email <span class="required-fild">*</span>:</label>
            <?= $this->Form->control('support_email', ['placeholder' => 'Support Email', 'class' => 'text-box', 'label' => false]); ?>
        </div>
        <div class="form-div">
            <input type="submit" value="Update" id="update-btn" class="button-2 padding-left140">
            <a class="button-3" href="<?= HTTP_ROOT; ?>admin">Cancel </a>
        </div>
        <?= $this->Form->end(); ?>   
        
    </div>
</div>
<script>
    $(function () {
        $("#adminSettings").validate({
            ignore: [],
            rules: {
                site_name: "required",
                admin_email: {
                    required: true,
                    email: true
                },
                from_email: {
                    required: true,
                    email: true
                },
                bcc_email: {
                    required: true,
                    email: true
                },
                support_email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                site_name: "Enter you name!!",
                admin_email: {
                    required: "Please enter your email id!!",
                    email: "Please enter valid email id!!"
                },             
                from_email: {
                    required: "Please enter your email id!!",
                    email: "Please enter valid email id!!"
                },             
                bcc_email: {
                    required: "Please enter your email id!!",
                    email: "Please enter valid email id!!"
                },           
                support_email: {
                    required: "Please enter your email id!!",
                    email: "Please enter valid email id!!"
                }             
            }
        });
    });
</script>