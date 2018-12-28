<div class="main-content-section">
    <ul class="dashboard-menu">
        <li><a class="active" href="<?= HTTP_ROOT . "edit-profile"; ?>">Account Info</a></li>        
        <li><a href="<?= HTTP_ROOT . "update-profile-pic"; ?>">Profile Picture </a></li>
        <li><a href="<?= HTTP_ROOT . "change-password"; ?>">Change Password </a></li>
    </ul>
    <div class="main-content">
        <div class="tab-content">
            <div id="basicinfo" class="tab-pane fade in active">
                <?= $this->Form->create($user, ['id' => 'userContent', 'class' => 'main-form']); ?>
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
                    <?= $this->Form->control('employee.phone', ['placeholder' => 'Phone No.', 'class' => 'text-box', 'label' => FALSE]); ?>  
                </div>               
                <div class="form-div">                    
                    <input type="submit" value="Update" id="cancel-btn" name="cancel-btn" class="button-3 padding-left140">
                    <a href="<?= HTTP_ROOT . "view-profile" ?>" class="button-2">Cancel</a>
                </div>
                <?= $this->Form->end(); ?>
            </div>

        </div>
    </div>
</div>
<script>
    $(function () {
        var userID = <?= $user->id ?>;
        $("#userContent").validate({
            ignore: [],
            rules: {
                name: 'required',
                email: {
                    required: true,
                    email: true,
                    remote: "users/checkEmailAvail?id=" + userID,
                },
                phone: 'required',
            },
            messages: {
                name: "Please enter your name!!",
                email: {
                    required: "Please enter email!",
                    email: "Please enter valid email!",
                    remote: "Email already exits!",
                },
                phone: "Please enter phone!!",
            },
            submitHandler: function (form) {
                return true;
            }
        });
    });
</script>