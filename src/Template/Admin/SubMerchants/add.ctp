<style>
    .select-1 { width: 65%; }
</style>
<div class="main-content-section">
    <div class="main-content">
        <?= $this->Form->create($submerchant, ['class' => 'main-form', 'id' => 'newsubmerchant', 'novalidate']); ?>
        <div class="form-div">
            <label class="form-label">Merchant <span class="required-fild">*</span>:</label>
            <?= $this->Form->control('merchant_id', ['type' => 'select', 'options' => $merchantList, 'empty' => 'Select Merchant', 'class' => 'select-1', 'label' => FALSE]); ?>
        </div>
        <div class="form-div">
            <label class="form-label">Name <span class="required-fild">*</span>:</label>
            <?= $this->Form->control('name', ['placeholder' => 'Submerchant Name', 'class' => 'text-box', 'label' => false]); ?>
        </div>
        <div class="form-div">
            <label class="form-label">Email  <span class="required-fild">*</span>:</label>
            <?= $this->Form->control('email', ['placeholder' => 'Submerchant Email', 'class' => 'text-box', 'label' => false]); ?>
        </div>
        <div class="form-div">
            <label class="form-label">PAYUMID  <span class="required-fild">*</span>:</label>
            <?= $this->Form->control('payumid', ['placeholder' => 'Submerchant PAYUMID', 'class' => 'text-box', 'label' => false]); ?>
        </div>
        <div class="form-div">
            <input type="submit" value="Create" id="update-btn" name="create-btn" class="button-2 padding-left140">
            <a class="button-3" href="<?= HTTP_ROOT; ?>admin">Cancel </a>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>
<script>
    $(function () {
        $("#newsubmerchantXX").validate({
            ignore: [],
            rules: {
                merchant_id: "required",
                name: "required",
                email: {
                    required: true,
                    email: true
                },
                payumid: "required"
            },
            messages: {
                merchant_id: 'Please Select A Parent Merchant',
                name: "Enter you name!!",
                email: {
                    required: "Please enter your email id!!",
                    email: "Please enter valid email id!!"
                },
                payumid: "Please Enter Your PAYUMID"
            }
        });
    });
</script>