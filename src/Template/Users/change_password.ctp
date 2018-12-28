<div class="main-content-section">
    <ul class="dashboard-menu">
        <li><a href="<?= HTTP_ROOT . "edit-profile"; ?>">Account Info</a></li>        
        <li><a href="<?= HTTP_ROOT . "update-profile-pic"; ?>">Profile Picture </a></li>
        <li><a class="active" href="<?= HTTP_ROOT . "change-password"; ?>">Change Password </a></li>
    </ul>
    <div class="main-content">
        <?= $this->Form->create(NULL, ['class' => 'main-form', 'id' => 'changePassword']); ?>
        <div class="form-div">
            <label class="form-label">Old Password <span class="required-fild">*</span>:</label>
            <?= $this->Form->control('old_password', ['type' => 'password', 'placeholder' => 'Old Password', 'class' => 'text-box', 'label' => false]); ?>
        </div>
        <div class="form-div">
            <label class="form-label">New Password <span class="required-fild">*</span>:</label>
            <?= $this->Form->control('password1', ['type' => 'password', 'placeholder' => 'New Password', 'class' => 'text-box', 'label' => false]); ?>
        </div>
        <div class="form-div">
            <label class="form-label">Confirm Password <span class="required-fild">*</span>:</label>
            <?= $this->Form->control('password2', ['type' => 'password', 'placeholder' => 'Confirm Password', 'class' => 'text-box', 'label' => false]); ?>
        </div>       
        <div class="form-div">
            <input type="submit" value="Change Password" id="update-btn" name="update-btn" class="button-2 padding-left140">
            <a class="button-3" href="<?= HTTP_ROOT; ?>view-profile">Cancel </a>
        </div>
        <?= $this->Form->end(); ?>   
    </div>
</div>
<script>
    $(function () {
        $("#changePassword").validate({
            ignore: [],
            rules: {
                old_password: "required",
                password1: "required",
                password2: {
                    required: true,
                    equalTo: "#password1",
                },
            },
            messages: {
                old_password: "Enter Old Password!!",
                password1: "Please enter a new password!!",
                password2: {
                    required: "Please enter a confirm password!!",
                    equalTo: "Passsword & confirm password are not matching!!"
                },
            },
            submitHandler: function (form) {
                return true;
            }

        });
    });
</script>
