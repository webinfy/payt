<div class="main-content-section">
    <ul class="dashboard-menu">
        <li><a class="active" href="<?= HTTP_ROOT . "admin/account-setup"; ?>">Profile Setting</a></li>
        <li><a href="<?= HTTP_ROOT . "admin/admin-settings"; ?>">Application Setting</a></li>
        <li><a href="<?= HTTP_ROOT . "admin/change-password"; ?>">Change Password</a></li>
    </ul>
    <div class="main-content">
        <?= $this->Form->create($user, ['class' => 'main-form', 'id' => 'accountSetup']); ?>
        <div class="form-div">
            <label class="form-label">Name <span class="required-fild">*</span>:</label>
            <?= $this->Form->control('name', ['placeholder' => 'Name', 'class' => 'text-box', 'label' => false]); ?>
        </div>
        <div class="form-div">
            <label class="form-label">Email <span class="required-fild">*</span>:</label>
            <?= $this->Form->control('email', ['placeholder' => 'Email', 'class' => 'text-box', 'label' => false]); ?>
        </div>
        <div class="form-div">
            <label class="form-label">Phone <span class="required-fild">*</span>:</label>
            <?= $this->Form->control('phone', ['placeholder' => 'Phone', 'class' => 'text-box', 'label' => false]); ?>
        </div>
        <div class="form-div">
            <input type="submit" value="Update" id="update-btn" name="update-btn" class="button-2 padding-left140">
            <a class="button-3" href="<?= HTTP_ROOT; ?>admin">Cancel </a>
        </div>
        <?= $this->Form->end(); ?>   
    </div>
</div>
<script>
    $(function () {
        $("#accountSetup").validate({
            ignore: [],
            rules: {
                name: "required",
                phone: "required",
                email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                name: "Enter you name!!",
                phone: "Please enter phone!!",
                email: {
                    required: "Please enter your email id!!",
                    email: "Please enter valid email id!!"
                }             
            }
        });
    });
</script>
