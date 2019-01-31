<!-- Update Webfront Content Section Start -->
<?php $this->Form->setTemplates(['inputContainer' => '{{content}}']); ?>
<?= $this->Form->create($webfront, ['class' => 'main-form', 'id' => 'websiteContent', 'novalidate']) ?>
<input type="hidden" name="step" value="content" />    
<div class="form-div">
    <label class="form-label">Title <span class="required-fild">*</span>:</label>
    <?= $this->Form->control('title', ['placeholder' => 'Title', 'class' => 'text-box', 'label' => false, 'id' => 'webfrontTitleInput']); ?>
</div>
<div class="form-div">
    <label class="form-label">Webfront URL <span class="required-fild">*</span>:</label>
    <?= $this->Form->control('url', ['placeholder' => 'Url', 'class' => 'text-box webfront-url', 'label' => false, 'id' => 'webfrontUrlInput']); ?>
</div>
<div class="form-div">
    <label class="form-label">Email <span class="required-fild">*</span>:</label>
    <?= $this->Form->control('email', ['placeholder' => 'Email', 'class' => 'text-box', 'label' => false]); ?>
</div>
<div class="form-div">
    <label class="form-label">Phone No. <span class="required-fild">*</span>:</label>
    <?= $this->Form->control('phone', ['placeholder' => 'Phone No.', 'class' => 'text-box', 'label' => false]); ?>
</div>
<div class="form-div">
    <label class="form-label">Address <span class="required-fild">*</span>:</label>
    <?= $this->Form->textarea('address', ['placeholder' => 'Address', 'class' => 'textarea-box', 'label' => false]); ?>
</div>
<div class="form-div">
    <label class="form-label">Description <span class="required-fild">*</span>:</label>
    <div class="form-div" style="width: 65%;">
        <?= $this->Form->control('description', ['placeholder' => 'Description', 'class' => 'textarea-box', 'label' => false]); ?>
    </div>
</div>
<div class="form-div">
    <label class="form-label">Show Recent Payments</label>
    <?= $this->Form->control('show_recent_payments', ['type' => 'checkbox', 'class' => '', 'label' => false]); ?>
</div>
<div class="form-div">
    <label class="form-label">Show In Profile</label>
    <?= $this->Form->control('is_public', ['type' => 'checkbox', 'class' => '', 'label' => false]); ?>
</div>
<div class="form-div">
    <label class="form-label">Payment Cycle Date <span class="required-fild">*</span>:</label>
    <?= $this->Form->text('payment_cycle_date', ['placeholder' => 'Payment Cycle Date', 'class' => 'text-box', 'label' => false, 'autocomplete' => 'off', 'id' => 'datepicker']); ?>
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
    <?= $this->Form->control('late_fee_amount', ['type' => 'text', 'placeholder' => 'Late Fee Amount', 'class' => 'text-box ', 'label' => false, 'required' => FALSE]); ?>
</div>
<div class="form-div">
    <a href="merchants/advance-webfronts" id="cancel-btn" class="button-3 padding-left140">Cancel</a>
    <input type="submit" value="Next" id="update-btn" name="update-btn" class="button-2 next-btn">
</div>
<?= $this->Form->end(); ?>
<!-- Update Webfront Content Section End -->

<script src="https://cdn.ckeditor.com/4.10.1/standard/ckeditor.js"></script>
<script>

    CKEDITOR.replace('description');

    $(document).ready(function () {

        toggleLateFeeFields(<?= $webfront->late_fee_type ?>);

        $('input[type="radio"][name="late_fee_type"]').on('click', function (e) {
            var type = $(this).val();
            toggleLateFeeFields(type);
        });

        $("#datepicker").datepicker({dateFormat: 'yy-mm-dd'});

        // Webfront Content/Step-1 valodation Code
        var webfrontID = '<?= !empty($webfront->id) ? $webfront->id : '' ?>';

        $.validator.setDefaults({ignore: [".ignoreValidation"]});

        $("#websiteContent").validate({
            ignore: [],
            rules: {
                url: {
                    required: true,
                    remote: "merchants/checkWebfrontUrlAvail?webfront_id=" + webfrontID
                },
                phone: "required",
                address: "required",
                title: "required",
                //description: "required",
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
                //description: "Please enter Webfront description!",
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