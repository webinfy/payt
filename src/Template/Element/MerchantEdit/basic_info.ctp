<!-- BasicInfo Tab Start-->
<div id="BasicInfo" class="tab-pane fade in active">
    <div style="color: #FF0000; margin-bottom: 10px;">(All * fields are mandatory.)</div>
    <?= $this->Form->create($merchant, ['class' => 'main-form', 'id' => 'accountSetup']); ?>
    <?= $this->Form->control('action', ['type' => 'hidden', 'value' => 'BasicInfo']); ?>
    <div class="form-div">
        <label class="form-label">Name <span class="required-fild">*</span>:</label>
        <?= $this->Form->control('name', ['placeholder' => 'Name', 'class' => 'text-box', 'label' => false, 'id' => 'webfrontTitleInput']); ?>
    </div>
    <div class="form-div">
        <label class="form-label">Profile URL <span class="required-fild">*</span>:</label>
        <?= $this->Form->control('merchant.profile_url', ['placeholder' => 'Profile URL', 'class' => 'text-box', 'label' => false, 'id' => 'webfrontUrlInput']); ?>
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
        <label class="form-label">Address <span class="required-fild">*</span>:</label>
        <?= $this->Form->textarea('merchant.address', ['placeholder' => 'Address', 'label' => false, 'class' => 'textarea-box']); ?>
    </div>
    <div class="form-div">
        <label class="form-label">City <span class="required-fild">*</span>:</label>
        <?= $this->Form->control('merchant.city', ['placeholder' => 'City', 'type' => 'text', 'class' => 'text-box', 'label' => false]); ?>
    </div>
    <div class="form-div">
        <label class="form-label">State <span class="required-fild">*</span>:</label>
        <?= $this->Form->control('merchant.state', ['placeholder' => 'State', 'type' => 'text', 'class' => 'text-box', 'label' => false]); ?>
    </div>
    <div class="form-div">
        <label class="form-label">Country <span class="required-fild">*</span>:</label>
        <?= $this->Form->control('merchant.country', ['placeholder' => 'Country', 'type' => 'text', 'class' => 'text-box', 'label' => false]); ?>
    </div>
    <div class="form-div">
        <label class="form-label">Description <span class="required-fild">*</span>:</label>
        <?= $this->Form->textarea('merchant.description', ['placeholder' => 'Description', 'label' => false, 'class' => 'textarea-box', 'style' => 'height: 100px;']); ?>
    </div>
    <div class="form-div">
        <label class="form-label">Convenience Fee </label>
        <?= $this->Form->control('merchant.convenience_fee_amount', ['placeholder' => 'Convenience Fee', 'type' => 'text', 'class' => 'text-box decimalonly', 'label' => false]); ?>
    </div>
    <div class="form-div">
        <input type="submit" value="Update" id="update-btn" class="button-2 padding-left140">
        <a class="button-3" href="<?= HTTP_ROOT; ?>merchants">Cancel </a>
    </div>
    <?= $this->Form->end(); ?>   
</div><!-- BasicInfo Tab End-->

<script>
    $(function () {
        
        var merchantID = <?= $merchant->id; ?>;
        
        $("#accountSetup").validate({
            ignore: [],
            rules: {
                name: "required",
                'merchant[profile_url]': {
                    required: true,
                    remote: 'merchants/checkProfileUrlAvail?merchant_id=' + merchantID
                },
                'merchant[phone]': "required",
                'merchant[address]': "required",
                'merchant[city]': "required",
                'merchant[state]': "required",
                'merchant[country]': "required",
                'merchant[description]': "required",
                email: {
                    required: true,
                    email: true
                },
            },
            messages: {
                name: "Enter you name!!",
                'merchant[profile_url]': {
                    required: "Enter Profile URL!",
                    remote: "Profile URL already exits!"
                },
                'merchant[phone]': "Please enter phone!!",
                'merchant[address]': "Please enter your address!!",
                'merchant[city]': "Please enter your city!!",
                'merchant[state]': "Please enter your state!!",
                'merchant[country]': "Please enter your country!!",
                'merchant[description]': "Please enter description!!",
                email: {
                    required: "Please enter your email id!!",
                    email: "Please enter valid email id!!"
                },
            },
            submitHandler: function (form) {
                return true;
            }
        });       

    });
</script>
