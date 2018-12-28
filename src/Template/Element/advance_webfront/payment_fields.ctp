<!-- Update Customer Fields Section End -->
<div id="paymentfields" class="tab-pane fade <?= ($tab == 'paymentfields') ? "active show" : "" ?>">
    <div class="section-1">
        <div class="section-header making-bold">Amount Collection Form &nbsp;<i class="fa fa-angle-double-right" aria-hidden="true"></i></div>
        <div class="section-tile border-coloring"><div class="dotted-bordering">

                <?= $this->Form->create(NULL, ['id' => 'amount-collection-form']) ?>
                <input type="hidden" name="step" value="payment_fields" />
                <div class="form-div section-body">
                    <label class="form-label">Add Payment Amount Name: </label>
                    <?= $this->Form->control('name', ['label' => false, 'placeholder' => 'Add Payment Amount Name', 'class' => 'text-box adv-web-text-box']) ?>
                    <?= $this->Form->checkbox('is_required', ['class' => 'check-box']); ?>
                    <span class="payment-field-mandatory">Mandatory(<span class="required-fild">*</span>)</span>
                </div>

                <div class="form-div">
                    <label class="form-label">Type: </label>
                    <span style="display: inline-block">
                        <label><input type="radio" name="is_user_entered" value="0" id="automatic" checked>Ask your customer to enter amount to pay</label><br>
                        <label><input type="radio" name="is_user_entered" value="1" id="specify">Specify an amount for your customer to pay</label>
                    </span>
                    <span class="for-amount-div">
                        <i class="fa fa-inr" aria-hidden="true"></i><input type="text" id="amount-value" class="text-box for-amount-input">
                    </span>
                </div>

                <div class="form-div">
                    <input type="submit" value="Save" id="save-btn" name="save-btn" class="button-2 padding-left160">
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>

    <div class="section-2">
        <div class="section-header making-bold" style="margin-top: 20px;"><i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;You have added <?= $numfields ?> Fields to Collect Amount
        </div>
        <div class="section-tile">
            <div class="accordion" id="fields">

                <?php
                $i = 1;
                foreach ($webfrontPaymentAttributes as $webfrontPaymentAttribute) {
                    ?>

                    <div class="card">
                        <!-- Card Header Starts-->
                        <div class="card-header">
                            <a class="making-block <?= ($i != 1) ? "collapsed" : "" ?>" data-toggle="collapse" href="#collapse<?= $i ?>" role="button" aria-expanded="false">
                                <span>Field <?= $i ?></span>
                            </a>
                        </div>
                        <!-- Card Header Ends -->
                        <!-- Div Covering Card Body For Showing Collapse Starts-->
                        <div id="collapse<?= $i ?>" class="collapse <?= ($i == 1) ? "show" : "" ?>" data-parent="#fields">
                            <!-- Card Body Starts-->
                            <div class="card-body">

                                <?= $this->Form->create($webfrontPaymentAttribute, ['url' => '/merchants/edit-payment-attribute/' . $webfrontPaymentAttribute->id, 'id' => 'field-forms']) ?>
                                <div class="form-div">
                                    <label class="form-label">Field <?= $i ?>:</label>
                                    <?= $this->Form->control('name', ['label' => false, 'placeholder' => 'Add Payment Amount Name', 'class' => 'text-box adv-web-text-box', 'disabled' => true]) ?>
                                    <input type="hidden" name="is_required" value="0">
                                    <input type="checkbox" name="is_required" class='check-box' value="1" disabled <?= ($webfrontPaymentAttribute->is_required != 0) ? "checked" : "" ?> >
                                    <span class="payment-field-mandatory">Mandatory(<span class="required-fild">*</span>)</span>
                                    <a href='merchants/delete-payment-attribute/<?= $webfrontPaymentAttribute->id ?>' style="float: right;margin-left: 1vw;color: black;" id='field-del-<?= $i ?>'><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                    <span style="float: right;color: black;" id='field-edit-<?= $i ?>'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
                                </div>
                                <div class='fom-div to-be-hidden'>
                                    <label class="form-label">Type: </label>
                                    <span style="display: inline-block">
                                        <label><input type="radio" name="is_user_entered" value="0" class='radio-check' id="automatic<?= $i ?>" <?= ($webfrontPaymentAttribute->is_user_entered != 1) ? "checked" : "" ?>>Ask your customer to enter amount to pay</label><br>
                                        <label><input type="radio" name="is_user_entered" value="1" class='radio-check' id="specify<?= $i ?>" <?= ($webfrontPaymentAttribute->is_user_entered != 1) ? "" : "checked" ?>>Specify an amount for your customer to pay</label>
                                    </span>
                                    <span class="field-amount-div for-amount-div<?= $i ?>">
                                        <i class="fa fa-inr" aria-hidden="true"></i><input type="text" id="amount-value<?= $i ?>" class="text-box for-amount-input" value='<?= $webfrontPaymentAttribute->value ?>' name='value'>
                                    </span>
                                </div>
                                <div class="form-div to-be-hidden" style='margin-top: 20px;'>
                                    <input type="submit" value="Save" id="save-btn" name="save-btn" class="button-2 padding-left160">
                                    <span id="cloze-btn<?= $i ?>" class="button-3 cloze-btn">Close</span>
                                </div>
                                <?php if (!empty($webfrontPaymentAttribute->value)) { ?>
                                    <div class="padding-left160" style="margin-top: 10px;">Specified Amount For Your Customer To Pay: <?= $webfrontPaymentAttribute->value ?>
                                    </div>
                                <?php } ?>
                                <?= $this->Form->end() ?>
                            </div>
                            <!-- Card Body Ends-->  
                        </div>
                        <!-- Div Covering Card Body For Showing Collapse Ends-->  
                    </div>
                    <?php
                    $i++;
                }
                ?>
            </div>
        </div>
    </div>

    <div class='section-3'>
        <div class='section-header making-bold' style='margin-top: 20px;'>
            Total Amount:&nbsp;<i class="fa fa-inr" aria-hidden="true"></i><?= $total ?>
            <a id="finish-btn" class="button-2" style='float: right' href="merchants/advance-webfronts">Finish</a>
            <span id="back-btn" class="button-3 prev-btn" style='float: right;'>Back</span>
        </div>
    </div>

</div><!-- Update Customer Fields Section End -->
<script>

    $(document).ready(function() {
        
        $('#specify').on('click', function() {
            $('.for-amount-div').show();
            $('#amount-value').attr('name', 'value');
        });
        
        $('#automatic').on('click', function() {
            $('.for-amount-div').hide();
            $('#amount-value').removeAttr('name');
        });

        $('.radio-check').each(function(index) {
            index += 1;
            if(!$('#specify'+index).is(':checked')) {
                $('.for-amount-div'+index).hide();
            }
        });
        
        $('.radio-check').on('click', function() {
                displayAmountField('#'+$(this).attr('id'));
        });
        
        $('.cloze-btn').on('click', function() {
            $('#'+$(this).attr('id')).parent().hide().prev().hide();
            disableFields('#'+$(this).attr('id'));
        });

        $('.fa-pencil-square-o').on('click', function() {
            $('#'+$(this).parent().attr('id')).parent().next().show().next().show();
            enableFields('#'+$(this).parent().attr('id'));
        });
        
        function displayAmountField(id) {
            if(~id.indexOf('specify')) {
                $('.for-amount-div'+id[id.length - 1]).show().find(":text").attr("name", "value");
            } else {
                $('.for-amount-div'+id[id.length - 1]).hide().find(":text").removeAttr("name");
            }
        }
        
        function enableFields(id) {
            $(id).siblings(".input").find(":text").attr('disabled', false);
            $(id).siblings(":checkbox").attr('disabled', false);
        }
        
        function disableFields(id) {
            $(id).parent().prev().prev().find(":text").attr('disabled', true);;
            $(id).parent().prev().prev().find(":checkbox").attr('disabled', true);;
        }
        
    });

</script>