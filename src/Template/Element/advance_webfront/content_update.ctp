<!-- Update Webfront Content Section Start -->
<div id="websitecontent" class="tab-pane fade <?= ($tab == 'websitecontent') ? "active show" : "" ?>">
    <?= $this->Form->create($webfront, ['class' => 'main-form', 'id' => 'websiteContent']) ?>
    <input type="hidden" name="step" value="content" />    
    <div class="form-div">
        <label class="form-label">Title <span class="required-fild">*</span>:</label>
        <?= $this->Form->control('title', ['placeholder' => 'Title', 'class' => 'text-box', 'label' => false, 'id' => 'webfrontTitleInput']); ?>
    </div>
    <div class="form-div">
        <label class="form-label">Webfront URL <span class="required-fild">*</span>:</label>
        <?= $this->Form->control('url', ['placeholder' => 'Url', 'class' => 'text-box alphanumericonly', 'label' => false, 'id' => 'webfrontUrlInput']); ?>
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
        <label class="form-label">Payment Cycle Date <span class="required-fild">*</span>:</label>
        <?= $this->Form->text('payment_cycle_date', ['placeholder' => 'Payment Cycle Date', 'class' => 'text-box', 'label' => false, 'id' => 'datepicker']); ?>
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
        <label class="form-label">Late Fee Amount :</label>
        <?= $this->Form->control('late_fee_amount', ['type' => 'text', 'placeholder' => 'Late Fee Amount', 'class' => 'text-box ', 'label' => false, 'required' => FALSE]); ?>
    </div>
    <div class="form-div">
        <a href="merchants/advance-webfronts" id="cancel-btn" class="button-3 padding-left160">Back</a>
        <input type="submit" value="Update" id="update-btn" name="update-btn" class="button-2 next-btn">
    </div>
    <?= $this->Form->end() ?>
</div><!-- Update Webfront Content Section End -->

<script src="https://cdn.ckeditor.com/4.10.1/standard/ckeditor.js"></script>
<script>

    CKEDITOR.replace('description');
    $(document).ready(function () {

        $("#datepicker").datepicker({dateFormat: 'yy-mm-dd'});

        // Webfront Content/Step-1 valodation Code
        var webfrontID = <?= $webfront->id ?>;

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