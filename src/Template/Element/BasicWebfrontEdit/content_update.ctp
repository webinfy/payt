<?php $this->Form->setTemplates(['inputContainer' => '{{content}}']); ?>
<?= $this->Form->create($webfront, ['id' => 'websiteContent', 'class' => 'main-form', 'novalidate' => TRUE]); ?>
<div class="form-div">
    <p style="color: red;">All (*) fields are mandatory</p> 
</div>
<div class="form-div">
    <label class="form-label">Title <span class="required-fild">*</span>:</label>
    <?= $this->Form->control('title', ['placeholder' => 'Title', 'class' => 'text-box', 'label' => FALSE, 'id' => 'webfrontTitleInput']); ?>                 
</div>
<div class="form-div">
    <label class="form-label">Webfront URL<span class="required-fild">*</span>:</label>
    <?= $this->Form->control('url', ['id' => 'webfrontUrlInput', 'placeholder' => 'Name', 'class' => 'text-box webfront-url', 'label' => FALSE]); ?>
</div>
<div class="form-div">
    <label class="form-label">Email <span class="required-fild">*</span>:</label>
    <?= $this->Form->control('email', ['id' => 'email', 'placeholder' => 'Email', 'class' => 'text-box', 'label' => FALSE]); ?>  
</div>
<div class="form-div">
    <label class="form-label">Phone <span class="required-fild">*</span>:</label>
    <?= $this->Form->control('phone', ['id' => 'phone', 'placeholder' => 'Phone No.', 'class' => 'text-box', 'label' => FALSE]); ?>  
</div>
<div class="form-div">
    <label class="form-label">Address <span class="required-fild">*</span>:</label>
    <?= $this->Form->control('address', ['type' => 'textarea', 'placeholder' => 'Address', 'class' => 'textarea-box', 'label' => false]); ?>
</div>
<div class="form-div">
    <label class="form-label">Description <span class="required-fild">*</span>:</label>
    <div class="form-div editor-box">
        <?= $this->Form->control('description', ['placeholder' => 'Description', 'class' => 'textarea-box', 'label' => false]); ?>
    </div>
</div>
<div class="form-div">
    <label class="form-label">Show In Profile</label>
    <?= $this->Form->control('is_public', ['type' => 'checkbox', 'class' => '', 'label' => false]); ?>
</div>
<div class="form-div">
    <label class="form-label">Late Fee Type : </label>
    <div style="float: left;">
        <span style="display: inline-block">
            <label><input type="radio" name="late_fee_type" value="1" <?php if ($webfront['late_fee_type'] == 1) { ?> checked <?php } ?>> Fixed </label> &nbsp;
            <label><input type="radio" name="late_fee_type" value="2" <?php if ($webfront['late_fee_type'] == 2) { ?> checked <?php } ?>> Recurring </label> &nbsp;
            <label><input type="radio" name="late_fee_type" value="3" <?php if ($webfront['late_fee_type'] == 3) { ?> checked <?php } ?>> Periodic </label> &nbsp;
        </span>
    </div>                    
</div>
<div id="recurring_period" class="form-div" style="display: none;">
    <div class="form-div">
        <label class="form-label">Days :</label>  
        <?= $this->Form->control('recurring_period', ['type' => 'number', 'class' => 'text-box col-sm-2', 'label' => false]); ?>
    </div>
</div>
<div id="late_fee_type_3" class="form-div" style="display: none;">
    <div class="form-div">
        <label class="form-label">&nbsp;</label>                  
        <div style="float: left; color: red; font-size: 12px;">Ex : After 5 days from due date Late fee Rs.100</div>
    </div>
    <div class="form-div">
        <label class="form-label">Period 1 :</label>                  
        <div style="float: left; position: relative;">
            <span class="afterPrefix">After</span><?= $this->Form->control('periodic_days_1', ['type' => 'text', 'placeholder' => 'No Of', 'class' => 'col-sm-6 text-box numericonly', 'style' => "margin-right: 5px;", 'label' => false, 'style' => 'padding-left: 50px;width: 148px;margin-right: 15px;']); ?><span class="daysPrefix">Days</span>
            <span style=" position: relative;display: inline-block;">
                <span class="moneyPrefix">Rs.</span><?= $this->Form->control('periodic_amount_1', ['type' => 'text', 'placeholder' => 'Amount', 'class' => 'col-sm-3 text-box decimalonly', 'label' => false, 'style' => 'padding-left: 35px']); ?>
            </span>
        </div>                  
    </div>
    <div class="form-div">
        <label class="form-label">Period 2 :</label>                   
        <div style="float: left; position: relative;">
            <span class="afterPrefix">After</span><?= $this->Form->control('periodic_days_2', ['type' => 'text', 'placeholder' => 'No Of', 'class' => 'col-sm-6 text-box numericonly', 'style' => "margin-right: 5px;", 'label' => false, 'style' => 'padding-left: 50px;width: 148px;margin-right: 15px;']); ?><span class="daysPrefix">Days</span>
            <span style=" position: relative;display: inline-block;">
                <span class="moneyPrefix">Rs.</span><?= $this->Form->control('periodic_amount_2', ['type' => 'text', 'placeholder' => 'Amount', 'class' => 'col-sm-3 text-box decimalonly', 'label' => false, 'style' => 'padding-left: 35px;']); ?>
            </span>
        </div>
    </div>
</div>
<div class="form-div">
    <label class="form-label">Late Fee Amount :</label>
    <?= $this->Form->control('late_fee_amount', ['type' => 'text', 'placeholder' => 'Late Fee Amount', 'class' => 'text-box decimal', 'label' => FALSE, 'required' => FALSE]); ?>                 
</div>
<div class="form-div">
    <a href="<?= HTTP_ROOT . "merchants/basic-webfronts" ?>" class="button-2 padding-left140">Back</a>
    <input type="submit" value="Next" id="cancel-btn" name="cancel-btn" class="button-3 next-btn">
</div>
<?= $this->Form->end(); ?>

<script src="https://cdn.ckeditor.com/4.10.1/standard/ckeditor.js"></script>
<script>
    $(function () {

        CKEDITOR.replace('description');

        toggleLateFeeFields(<?= $webfront->late_fee_type ?>);

        $('input[type="radio"][name="late_fee_type"]').on('click', function (e) {
            var type = $(this).val();
            toggleLateFeeFields(type);
        });

        var webfrontID = <?= !empty($webfront->id) ? $webfront->id : 0; ?>;

        $("#websiteContent").validate({
            ignore: [],
            rules: {
                url: {
                    required: true,
                    remote: (webfrontID > 0) ? "merchants/checkWebfrontUrlAvail?webfront_id=" + webfrontID : "merchants/checkWebfrontUrlAvail"
                },
                phone: "required",
                address: "required",
                title: "required",
                description: "required",
                email: {
                    required: true,
                    email: true
                },
            },
            messages: {
                url: {
                    required: "Enter Webfront URL!",
                    remote: "URL already exits!"
                },
                phone: "Please enter phone!",
                address: "Please enter address!",
                title: "Please enter Webfront title!",
                description: "Please enter Webfront description!",
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