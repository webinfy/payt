<!-- Update Customer Fields Section Start -->
<div id="customerfields" class="tab-pane fade <?= ($tab == 'customerfields') ? "active show" : "" ?>">

    <?= $this->Form->create($webfront, ['id' => 'customerfields-form']) ?>
    <input type="hidden" name="step" value="customer_fields" />

    <div class="form-div">
        <label class="form-label">Name <span class="required-fild">*</span>:</label>
        <?= $this->Form->control('customer_name_alias', ['placeholder' => 'Name', 'class' => 'text-box', 'label' => false]); ?>
    </div>
    <div class="form-div">
        <label class="form-label">Email:</label>
        <?= $this->Form->control('customer_email_alias', ['placeholder' => 'Email', 'class' => 'text-box', 'label' => false]); ?>
    </div>
    <div class="form-div">
        <label class="form-label">Phone No.</label>
        <?= $this->Form->control('customer_phone_alias', ['placeholder' => 'Phone No.', 'class' => 'text-box', 'label' => false]); ?>
    </div>

    <div class="form-div" >
        <label class="form-label">CA1:</label>
        <?= $this->Form->control('customer_fields.ca1.value', ['placeholder' => 'Default Label', 'class' => 'text-box adv-web-text-box', 'label' => false, 'id' => 'ca0']); ?>
        <?= $this->Form->checkbox('customer_fields.ca1.is_mandatory', ['class' => 'check-box adv-web-check-box', 'id' => 'ca0-checkbox']); ?>
        <?= $this->Form->select('customer_fields.ca1.input_type', $inputTypeList, ['empty' => 'Select Type', 'class' => 'select-1 adv-web-select-type', 'id' => 'adv-select-1', 'data-id' => 1]); ?>
        <?= $this->Form->select('customer_fields.ca1.validation_id', $validationList, ['empty' => 'Select Validation', 'class' => 'select-1 adv-web-select-validation']); ?>

        <?php if (!empty($webfront['customer_fields']['ca1']['webfront_field_values'])) { ?>
            <div class="form-div choices-container">
                <div class="choices">
                    <?php foreach ($webfront['customer_fields']['ca1']['webfront_field_values'] as $option) { ?>
                        <div class="form-div dynamictext">
                            <label class="form-label"></label>
                            <div class="input text"><input value="<?= $option['value'] ?>" type="text" name="customer_fields[ca1][options][]" placeholder="Default Label" class="text-box adv-web-text-box"></div>
                            <span class="dynamic-text-del-btn" id=""><i class="fa fa-remove" style="font-size: 30px; color: #E08374;"></i></i></span>
                        </div>
                    <?php } ?>
                </div>
                <div class="mybutton btn-blue addMoreOption"><i class="fa fa-plus" aria-hidden="true"></i>Add</div>
            </div>
        <?php } ?>

    </div>

    <div class="form-div">
        <label class="form-label">CA2:</label>
        <?= $this->Form->control('customer_fields.ca2.value', ['placeholder' => 'Default Label', 'class' => 'text-box adv-web-text-box', 'label' => false, 'id' => 'ca1']); ?>
        <?= $this->Form->checkbox('customer_fields.ca2.is_mandatory', ['class' => 'check-box adv-web-check-box', 'id' => 'ca1-checkbox']); ?>
        <?= $this->Form->select('customer_fields.ca2.input_type', $inputTypeList, ['empty' => 'Select Type', 'class' => 'select-1 adv-web-select-type', 'id' => 'adv-select-2', 'data-id' => 2]); ?>
        <?= $this->Form->select('customer_fields.ca2.validation_id', $validationList, ['empty' => 'Select Validation', 'class' => 'select-1 adv-web-select-validation']); ?>

        <?php if (!empty($webfront['customer_fields']['ca2']['webfront_field_values'])) { ?>
            <div class="form-div choices-container">
                <div class="choices">
                    <?php foreach ($webfront['customer_fields']['ca2']['webfront_field_values'] as $option) { ?>
                        <div class="form-div dynamictext">
                            <label class="form-label"></label>
                            <div class="input text"><input value="<?= $option['value'] ?>" type="text" name="customer_fields[ca2][options][]" placeholder="Default Label" class="text-box adv-web-text-box"></div>
                            <span class="dynamic-text-del-btn" id=""><i class="fa fa-remove" style="font-size: 30px; color: #E08374;"></i></i></span>
                        </div>
                    <?php } ?>
                </div>
                <div class="mybutton btn-blue addMoreOption"><i class="fa fa-plus" aria-hidden="true"></i>Add</div>
            </div>
        <?php } ?>

    </div>

    <div class="form-div">
        <label class="form-label">CA3:</label>
        <?= $this->Form->control('customer_fields.ca3.value', ['placeholder' => 'Default Label', 'class' => 'text-box adv-web-text-box', 'label' => false, 'id' => 'ca2']); ?>
        <?= $this->Form->checkbox('customer_fields.ca3.is_mandatory', ['class' => 'check-box adv-web-check-box', 'id' => 'ca2-checkbox']); ?>
        <?= $this->Form->select('customer_fields.ca3.input_type', $inputTypeList, ['empty' => 'Select Type', 'class' => 'select-1 adv-web-select-type', 'id' => 'adv-select-3', 'data-id' => 3]); ?>
        <?= $this->Form->select('customer_fields.ca3.validation_id', $validationList, ['empty' => 'Select Validation', 'class' => 'select-1 adv-web-select-validation']); ?>

        <?php if (!empty($webfront['customer_fields']['ca3']['webfront_field_values'])) { ?>
            <div class="form-div choices-container">
                <div class="choices">
                    <?php foreach ($webfront['customer_fields']['ca3']['webfront_field_values'] as $option) { ?>
                        <div class="form-div dynamictext">
                            <label class="form-label"></label>
                            <div class="input text"><input value="<?= $option['value'] ?>" type="text" name="customer_fields[ca3][options][]" placeholder="Default Label" class="text-box adv-web-text-box"></div>
                            <span class="dynamic-text-del-btn" id=""><i class="fa fa-remove" style="font-size: 30px; color: #E08374;"></i></i></span>
                        </div>
                    <?php } ?>
                </div>
                <div class="mybutton btn-blue addMoreOption"><i class="fa fa-plus" aria-hidden="true"></i>Add</div>
            </div>
        <?php } ?>

    </div>

    <div class="form-div">
        <label class="form-label">CA4:</label>
        <?= $this->Form->control('customer_fields.ca4.value', ['placeholder' => 'Default Label', 'class' => 'text-box adv-web-text-box', 'label' => false, 'id' => 'ca3']); ?>
        <?= $this->Form->checkbox('customer_fields.ca4.is_mandatory', ['class' => 'check-box adv-web-check-box', 'id' => 'ca3-checkbox']); ?>
        <?= $this->Form->select('customer_fields.ca4.input_type', $inputTypeList, ['empty' => 'Select Type', 'class' => 'select-1 adv-web-select-type', 'id' => 'adv-select-4', 'data-id' => 4]); ?>
        <?= $this->Form->select('customer_fields.ca4.validation_id', $validationList, ['empty' => 'Select Validation', 'class' => 'select-1 adv-web-select-validation']); ?>

        <?php if (!empty($webfront['customer_fields']['ca4']['webfront_field_values'])) { ?>
            <div class="form-div choices-container">
                <div class="choices">
                    <?php foreach ($webfront['customer_fields']['ca4']['webfront_field_values'] as $option) { ?>
                        <div class="form-div dynamictext">
                            <label class="form-label"></label>
                            <div class="input text"><input value="<?= $option['value'] ?>" type="text" name="customer_fields[ca4][options][]" placeholder="Default Label" class="text-box adv-web-text-box"></div>
                            <span class="dynamic-text-del-btn" id=""><i class="fa fa-remove" style="font-size: 30px; color: #E08374;"></i></i></span>
                        </div>
                    <?php } ?>
                </div>
                <div class="mybutton btn-blue addMoreOption"><i class="fa fa-plus" aria-hidden="true"></i>Add</div>
            </div>
        <?php } ?>

    </div>

    <div class="form-div">
        <label class="form-label">CA5:</label>
        <?= $this->Form->control('customer_fields.ca5.value', ['placeholder' => 'Default Label', 'class' => 'text-box adv-web-text-box', 'label' => false, 'id' => 'ca5']); ?>
        <?= $this->Form->checkbox('customer_fields.ca5.is_mandatory', ['class' => 'check-box adv-web-check-box', 'id' => 'ca4-checkbox']); ?>
        <?= $this->Form->select('customer_fields.ca5.input_type', $inputTypeList, ['empty' => 'Select Type', 'class' => 'select-1 adv-web-select-type', 'id' => 'adv-select-5', 'data-id' => 5]); ?>
        <?= $this->Form->select('customer_fields.ca5.validation_id', $validationList, ['empty' => 'Select Validation', 'class' => 'select-1 adv-web-select-validation']); ?>

        <?php if (!empty($webfront['customer_fields']['ca5']['webfront_field_values'])) { ?>
            <div class="form-div choices-container">
                <div class="choices">
                    <?php foreach ($webfront['customer_fields']['ca5']['webfront_field_values'] as $option) { ?>
                        <div class="form-div dynamictext">
                            <label class="form-label"></label>
                            <div class="input text"><input value="<?= $option['value'] ?>" type="text" name="customer_fields[ca5][options][]" placeholder="Default Label" class="text-box adv-web-text-box"></div>
                            <span class="dynamic-text-del-btn" id=""><i class="fa fa-remove" style="font-size: 30px; color: #E08374;"></i></i></span>
                        </div>
                    <?php } ?>
                </div>
                <div class="mybutton btn-blue addMoreOption"><i class="fa fa-plus" aria-hidden="true"></i>Add</div>
            </div>
        <?php } ?>

    </div>

    <div class="form-div">
        <label class="form-label">CA6:</label>
        <?= $this->Form->control('customer_fields.ca6.value', ['placeholder' => 'Default Label', 'class' => 'text-box adv-web-text-box', 'label' => false, 'id' => 'ca6']); ?>
        <?= $this->Form->checkbox('customer_fields.ca6.is_mandatory', ['class' => 'check-box adv-web-check-box', 'id' => 'ca5-checkbox']); ?>
        <?= $this->Form->select('customer_fields.ca6.input_type', $inputTypeList, ['empty' => 'Select Type', 'class' => 'select-1 adv-web-select-type', 'id' => 'adv-select-6', 'data-id' => 6]); ?>
        <?= $this->Form->select('customer_fields.ca6.validation_id', $validationList, ['empty' => 'Select Validation', 'class' => 'select-1 adv-web-select-validation']); ?>

        <?php if (!empty($webfront['customer_fields']['ca6']['webfront_field_values'])) { ?>
            <div class="form-div choices-container">
                <div class="choices">
                    <?php foreach ($webfront['customer_fields']['ca6']['webfront_field_values'] as $option) { ?>
                        <div class="form-div dynamictext">
                            <label class="form-label"></label>
                            <div class="input text"><input value="<?= $option['value'] ?>" type="text" name="customer_fields[ca6][options][]" placeholder="Default Label" class="text-box adv-web-text-box"></div>
                            <span class="dynamic-text-del-btn" id=""><i class="fa fa-remove" style="font-size: 30px; color: #E08374;"></i></i></span>
                        </div>
                    <?php } ?>
                </div>
                <div class="mybutton btn-blue addMoreOption"><i class="fa fa-plus" aria-hidden="true"></i>Add</div>
            </div>
        <?php } ?>

    </div>


    <div class="form-div">
        <span class="button-3 prev-btn padding-left140">Back</span>
        <input type="submit" value="Update" id="create-btn" name="create-btn" class="button-2">
    </div>
    <?= $this->Form->end() ?>

</div><!-- Update Customer Fields Section End -->


<script>

    $(document).ready(function () {

        // Webfront Customer Fields Code 
        $("#customerfields-form").validate({
            rules: {
                customer_name_alias: "required"
            },
            messages: {
                customer_name_alias: "Enter Name Alias!!"
            }
        });

        $('.adv-web-text-box').on('keyup keypress blur change', function (e) {
            var checkBox = $(this).closest('div.form-div').find('.adv-web-check-box');
            if ($(this).val()) {
                checkBox.prop('checked', true);
            } else {
                checkBox.prop('checked', false);
            }
        });

        $('.adv-web-check-box').on('click', function (e) {
            var inputBox = $(this).closest('div.form-div').find('input.adv-web-text-box');
            if (!$(this).is(':checked')) {
                inputBox.val('');
            }
        });

        $('.adv-web-select-type').on('change', function () {

            var inputType = parseInt($(this).val());

            if (!inputType || inputType == 1 || inputType == 2) {

                $(this).closest('.form-div').find('.choices-container').remove();

            } else if (inputType == 3 || inputType == 4) {

                if ($(this).closest('.form-div').find('div.choices-container').length) {
                    return; // If Choices container already exist 
                }

                var caIndex = $(this).attr('data-id');
                var choiceBox = '<div class="form-div choices-container">';
                choiceBox += '<div class="choices">';
                choiceBox += '<div class="form-div dynamictext">';
                choiceBox += '<label class="form-label"></label>';
                choiceBox += '<div class="input text"><input type="text" name="customer_fields[ca' + caIndex + '][options][]" placeholder="Default Label" class="text-box adv-web-text-box"></div>';
                choiceBox += '<span class="dynamic-text-del-btn"><i class="fa fa-remove" style="font-size: 30px; color: #E08374;"></i></i></span>';
                choiceBox += '</div>';
                choiceBox += '</div>';
                choiceBox += '<div class="mybutton btn-blue addMoreOption"><i class="fa fa-plus" aria-hidden="true"></i>Add</div>';
                choiceBox += '</div>';

                $(this).closest('.form-div').append(choiceBox);
            }
        });

        $(document).on('click', '.addMoreOption', function (e) {
            var newOption = $(this).closest('.choices-container').find(".dynamictext").last().clone();
            $(this).siblings('div.choices').append(newOption);
        });

        $('.adv-web-text-box').each(function (e) {
            if ($(this).val()) {
                $(this).closest('.form-div').find('.adv-web-check-box').prop('checked', true);
            }
        });
        
        $(document).on('click', '.dynamic-text-del-btn', function (e) {
            if ($(this).closest('div.dynamictext').siblings().length < 1) {
                e.preventDefault();
            } else {
                $(this).closest('div.dynamictext').remove();
            }
        });
    });
</script>