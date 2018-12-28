<div class="pay-online-form">

    <?= $this->Form->create(NULL, ['id' => 'payOnlineForm', 'url' => '/webfronts/payNow/' . $webfront->url]) ?>

    <h2 class="text-center">Pay Online for <span class="blue-color"><?= $webfront->title; ?></span></h2>

    <div class="form-div">
        <label><?= $webfront->customer_name_alias ?><span class="required-fild">*</span></label>
        <input type="text" name="name" id="Name" class="text-box">                                    
    </div>
    <div class="form-div">
        <label><?= $webfront->customer_email_alias ?><span class="required-fild">*</span></label>
        <input type="email" name="email" id="Email" class="text-box">                                    
    </div>
    <div class="form-div">
        <label><?= $webfront->customer_phone_alias ?><span class="required-fild">*</span></label>
        <input type="text" name="phone" id="Phone" class="text-box">                                    
    </div>

    <?php
    if (!empty($webfront->webfront_fields)) {
        foreach ($webfront->webfront_fields as $outerKey => $webfrontField) {
            ?>
            <div class="form-div">
                <label><?= $webfrontField->name ?><span class="required-fild">*</span></label>
                <?php if ($webfrontField->input_type == 1) { ?>
                    <input type="text" name="payee_custom_fields[<?= strtolower($webfrontField->name) ?>]" class="text-box"> 
                <?php } elseif ($webfrontField->input_type == 2) { ?>
                    <textarea class="textarea-box" name="payee_custom_fields[<?= strtolower($webfrontField->name) ?>]"></textarea>
                    <?php
                } else {

                    if ($webfrontField->input_type == 3) {
                        $i = 1;
                        foreach ($webfrontField->webfront_field_values as $innerKey => $webfrontFieldValue) {
                            ?>
                            <label id="making-inline"><input type="radio" name="payee_custom_fields[<?= strtolower($webfrontField->name) ?>]" value="<?= $webfrontFieldValue->value ?>" <?= ($i == 1) ? "checked" : "" ?> ><?= $webfrontFieldValue->value ?></label>&nbsp;&nbsp;
                            <?php
                            $i++;
                        }
                    } elseif ($webfrontField->input_type == 4) {
                        ?>
                        <select class="select-1 pay-form-select" name="payee_custom_fields[<?= strtolower($webfrontField->name) ?>]">
                            <option value="">Select <?= ucfirst($webfrontField->name) ?>...</option>
                            <?php foreach ($webfrontField->webfront_field_values as $innerKey => $webfrontFieldValue) { ?>
                                <option value="<?= $webfrontFieldValue->value ?>"><?= $webfrontFieldValue->value ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <?php
                    }
                }
                ?>
            </div> 

            <?php
        }
    }
    ?>

    <h3>Payment Details</h3>

    <?php
    if (!empty($webfront->webfront_payment_attributes)) {
        foreach ($webfront->webfront_payment_attributes as $webfrontPaymentAttribute) {
            ?>

            <div class="form-div">
                <label><?= $webfrontPaymentAttribute->name ?></label>
                <?php if ($webfrontPaymentAttribute->is_user_entered == 1) { ?>
                    <input type="number" name="payment_custom_fields[<?= strtolower($webfrontPaymentAttribute->name) ?>]" class="text-box payment-attr" readonly value="<?= $webfrontPaymentAttribute->value ?>">
                <?php } else { ?>
                    <input type="number" name="payment_custom_fields[<?= strtolower($webfrontPaymentAttribute->name) ?>]" class="text-box payment-attr">
                <?php } ?>
            </div>

            <?php
        }
    }
    ?>
    <!--<p class="payment-details-p">Payment Cycle :<i id="payment-cycle"><?= $webfront->payment_cycle_date ?></i></p><br>-->
    <!--<p class="payment-details-p">Late Fee :Rs. 0 after Due Date.</p>-->


    <h4>Total Amount Rs. <i id="total">0</i></h4>
    <input type="hidden" name="fee" id="hidden-input">

    <div class="form-div"><label><input type="checkbox" name="term"> Accept Terms & Conditions<span class="required-fild">*</span></label></div>

    <div class="btn-section text-center">
        <a href="javascript:void(0);" class="button-3 close-form"><i class="ace-icon fa fa-close bigger-110"></i> Close</a>
        <button type="submit" class="button-2" style="margin:0 5px;"><i class="ace-icon fa fa-check bigger-110"></i> Pay Now</button>
    </div>

    <?= $this->Form->end() ?>

</div>


<script>
    
    $.validator.addMethod(
        "regex",
        function(value, element, regexp) {
            var re = new RegExp(regexp);
            return this.optional(element) || re.test(value);
        },
        "Please check your input."
    );
    
    function calculateTotalPrice() {
        var sum = 0;
        $('.payment-attr').each(function() {   
            if($(this).val()) {
                sum += parseFloat($(this).val());
            }              
        });
        $('#total').text(sum);
        $('#hidden-input').val(sum);
    }  
    
    $(document).ready(function() {
         
        calculateTotalPrice();        
        
        $('.payment-attr').on('keyup keypress blur change', function(e) {            
            calculateTotalPrice();
        });
        
    }); 
    
    $(document).ready(function() {
        
        $('#payOnlineForm').validate({
            rules: {
                name: "required",
                email: {
                    required: true,
                    email: true,                    
                },
                phone: {
                    required: true,
                    number: true,
                    rangelength: [10, 15]
                },
                term: "required",
                <?php 
                if (!empty($webfront->webfront_fields)) {
                    foreach ($webfront->webfront_fields as $outerKey => $webfrontField) { 
                ?>
                "payee_custom_fields[<?= strtolower($webfrontField->name); ?>]": {
                    required: true,
                    <?php 
                    foreach($validations as $validation) { 
                        if(($webfrontField->validation_id != 1) && ($webfrontField->validation_id == $validation->id)) {
                    ?>
                        regex: /<?= $validation->reg_exp ?>/,
                    <?php                     
                        }                        
                    } 
                    ?>
                },
                <?php  }}?>
            },
            messages: {
                name: "Enter Your Name",
                email: {
                    required: "Please Enter Your Email",
                    email: "Please Enter A Valid Email",                    
                },
                phone: {
                    required: "Enter Your Phone",
                    number: "Enter A Number",
                    rangelength: "Number Should Be Between 10 And 15 Including Both"
                },
                term: "Please Accept The Terms & Conditions",
                <?php 
                if (!empty($webfront->webfront_fields)) {
                    foreach ($webfront->webfront_fields as $outerKey => $webfrontField) { 
                ?>
                "payee_custom_fields[<?= strtolower($webfrontField->name); ?>]": {
                    required: "Please Enter Your <?= ucfirst($webfrontField->name); ?>",
                    <?php 
                    foreach($validations as $validation) { 
                        if(($webfrontField->validation_id != 1) && ($webfrontField->validation_id == $validation->id)) {
                    ?>
                        regex: "<?= $validation->err_msg ?>",
                    <?php                    
                        }                        
                    } 
                    ?>
                },
                <?php  }}?>
            },
            errorPlacement: function(error, element) {
                if(element.attr("name") === "term") {
                     error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            }
        });
    });
</script>