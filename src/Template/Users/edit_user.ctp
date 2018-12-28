<div class="main-content-section">
    <div class="main-content">
        <div class="tab-content">
            <div id="basicinfo" class="tab-pane fade in active">
                <?= $this->Form->create($userEntity, ['id' => 'userContent', 'class' => 'main-form']); ?>
                <div class="form-div">
                    <p style="color: red;">All (*) fields are mandatory</p> 
                </div>
                <div class="form-div">
                    <label class="form-label">Name<span class="required-fild">*</span>:</label>
                    <?= $this->Form->control('name', ['placeholder' => 'Name', 'class' => 'text-box', 'label' => FALSE]); ?>
                </div>
                <div class="form-div">
                    <label class="form-label">Email <span class="required-fild">*</span>:</label>
                    <?= $this->Form->control('email', ['placeholder' => 'Email', 'class' => 'text-box', 'label' => FALSE]); ?>  
                </div>
                <div class="form-div">
                    <label class="form-label">Phone <span class="required-fild">*</span>:</label>
                    <?= $this->Form->control('phone', ['placeholder' => 'Phone No.', 'class' => 'text-box', 'label' => FALSE]); ?>  
                </div>
                <div class="form-div">
                    <label class="form-label">  </label>                              
                    <input type="radio" name="access" value="2" <?php if ($userEntity->access == 2) { ?> checked <?php } ?> /> <span style="margin-right: 10px;font-size: 15px;">Full Access</span> 
                    <input type="radio" name="access" value="3" <?php if ($userEntity->access == 3) { ?> checked <?php } ?> /> <span style="margin-right: 10px;font-size: 15px;">View Only</span> 
                </div>
                <div class="form-div">
                    <input type="submit" value="Update" id="cancel-btn" name="cancel-btn" class="button-3 padding-left140">
                    <a href="<?= HTTP_ROOT . 'users/view-all-user'; ?>" class="button-2">Back</a>                    
                </div>
                <?= $this->Form->end(); ?>
            </div>

        </div>
    </div>
</div>
<script>
    $(function () {
        $("#userContent").validate({
            ignore: [],
            rules: {
                name: {
                    required: true,
                },
                password: 'required',
                phone: 'required',
                access: 'required',
                conf_password: {
                    required: true,
                    equalTo: "#password"
                },
                email: {
                    required: true,
                    email: true
                },
            },
            messages: {
                name: {
                    required: "Enter name!",
                },
                password: "Please enter a new password!!",
                phone: "Please enter phone!!",
                access: "Please chose access type!!",
                conf_password: {
                    required: "Please enter a confirm password!!",
                    equalTo: "Passsword & confirm password are not matching!!"
                },
                email: {
                    required: "Please enter email!",
                    email: "Please enter valid email!"
                },
            },
            submitHandler: function (form) {
                return true;
            }
        });
    });
</script>