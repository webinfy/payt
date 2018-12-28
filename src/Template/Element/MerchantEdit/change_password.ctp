<?php
$controller = $this->request->getParam('controller');
$action = $this->request->getParam('action');
?>
<!-- ChangePassword Tab Start-->
<div id="ChangePassword" class="tab-pane fade">
    <?= $this->Form->create(NULL, ['class' => 'main-form', 'id' => 'changePassword']); ?>
    <?= $this->Form->control('action', ['type' => 'hidden', 'value' => 'ChangePassword']); ?>
    <?php if ($this->request->getSession()->read('Auth.User.id') == $merchant->id) { ?>
        <div class="form-div">
            <label class="form-label">Old Password <span class="required-fild">*</span>:</label>
            <?= $this->Form->control('old_password', ['type' => 'password', 'placeholder' => 'Old Password', 'class' => 'text-box', 'label' => false]); ?>
        </div>
    <?php } ?>

    <div class="form-div">
        <label class="form-label">New Password <span class="required-fild">*</span>:</label>
        <?= $this->Form->control('password1', ['type' => 'password', 'placeholder' => 'New Password', 'class' => 'text-box', 'label' => false]); ?>
    </div>
    <div class="form-div">
        <label class="form-label">Confirm Password <span class="required-fild">*</span>:</label>
        <?= $this->Form->control('password2', ['type' => 'password', 'placeholder' => 'Confirm Password', 'class' => 'text-box', 'label' => false]); ?>
    </div>       
    <div class="form-div">
        <input type="submit" value="Change Password" class="button-2 padding-left140">
        <a class="button-3" href="<?= HTTP_ROOT; ?>merchants">Cancel </a>
    </div>
    <?= $this->Form->end(); ?>   
</div><!-- ChangePassword Tab End-->

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