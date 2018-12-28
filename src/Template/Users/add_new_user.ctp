<style>
    label.error {margin-left: 23%;}
</style>
<div class="main-content-section">
    <div class="main-content">
        <div class="tab-content">
            <div id="basicinfo" class="tab-pane fade in active">
                <?= $this->Form->create(NULL, ['id' => 'userContent', 'class' => 'main-form']); ?>
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
                    <label class="form-label">Password <span class="required-fild">*</span>:</label>
                    <?= $this->Form->control('password', ['placeholder' => 'Password', 'class' => 'text-box', 'label' => FALSE]); ?>  
                </div>
                <div class="form-div">
                    <label class="form-label">Confirm Password <span class="required-fild">*</span>:</label>
                    <?= $this->Form->control('conf_password', ['type' => 'password', 'placeholder' => 'Confirm Password', 'class' => 'text-box', 'label' => FALSE]); ?>  
                </div>
                <div class="form-div">
                    <label class="form-label">  </label>                              
                    <input type="radio" name="access" value="2" /> <span style="margin-right: 10px;font-size: 15px;">Full Access</span> 
                    <input type="radio" name="access" value="3" /> <span style="margin-right: 10px;font-size: 15px;">View Only</span> 
                </div>
                <div class="form-div">                    
                    <input type="submit" value="Save" id="cancel-btn" name="cancel-btn" class="button-3 padding-left140">
                    <a href="<?= HTTP_ROOT ?>" class="button-2">Back</a>
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
                name: 'required',
                password: 'required',
                phone: 'required',
                access: 'required',
                conf_password: {
                    required: true,
                    equalTo: "#password"
                },
                email: {
                    required: true,
                    remote: "users/checkEmailAvail",
                    email: true
                },
            },
            messages: {               
                name: "Please enter a name!!",
                password: "Please enter password!!",
                phone: "Please enter phone!!",
                access: "Please chose access type!!",
                conf_password: {
                    required: "Please enter a confirm password!!",
                    equalTo: "Passsword & confirm password are not matching!!"
                },
                email: {
                    required: "Please enter email!",
                    remote: "Email already exits!",
                    email: "Please enter valid email!"
                },
            },
            submitHandler: function (form) {
                return true;
            }
        });
    });
</script>