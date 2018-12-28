<!-- Modal Start -->
<div class="modal-dialog" style="max-width: 600px;">
    <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header" style="margin-top: -4px;padding-bottom: 3px;">
            <h3 class="modal-title" style="margin-left: 28%;">Edit Invoice Details</h3>
            <button type="button" class="close" data-dismiss="modal"></button>
        </div>
        <!-- Modal body -->
        <?= $this->Form->create(NULL, ['class' => 'main-form', 'url' => ['controller' => 'Webfronts', 'action' => 'editBasicPayment'], 'id' => 'editInvoiceForm']) ?>
        <div class="modal-body" style="height: 65vh;overflow-y: auto;overflow-x: hidden;">
            <input type="hidden" name="uniq_id" value="<?= $payment->uniq_id; ?>" />
            <div>                 
                <h4 class="modal-title" style="padding: 0px 0px 0px 3px;color: #1c32ad;">Customer Details</h4>
                <table align='center' style="width: 100%;">                        
                    <tr>
                        <td style="padding: 10px 10px 2px 10px; width: 40%;"><label><?= $payment->webfront->customer_name_alias; ?></label></td>
                        <td style="padding: 10px 10px 2px 10px;"> : <label><?= $payment->name; ?></label></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 10px 2px 10px; width: 40%;"><label><?= $payment->webfront->customer_email_alias; ?></label></td>
                        <td style="padding: 10px 10px 2px 10px;"> : <label><?= $payment->email; ?></label></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 10px 2px 10px; width: 40%;"><label><?= $payment->webfront->customer_reference_number_alias; ?></label></td>
                        <td style="padding: 10px 10px 2px 10px;"> : <label><?= $payment->reference_number; ?></label></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 10px 2px 10px; width: 40%;"><label><?= $payment->webfront->customer_phone_alias; ?></label></td>
                        <td style="padding: 10px 10px 2px 10px;"> : <label><?= $payment->phone; ?></label></td>
                    </tr>
                    <?php
                    if (!empty($payment->payee_custom_fields)) {
                        $customerFields = json_decode($payment->payee_custom_fields);
                        foreach ($customerFields as $field) {
                            ?>
                            <tr>
                                <td style="padding: 10px 10px 2px 10px; width: 40%;"><label><?= ($field->field); ?></label></td>
                                <td style="padding: 10px 10px 2px 10px;"> :  <label><?= $field->value; ?></label></td>
                            </tr> 
                            <?php
                        }
                    }
                    ?> 
                </table>
                <h4 class="modal-title" style="padding: 10px 0px 0px 3px;color: #1c32ad;">Payment Details</h4>
                <table align='center' style="width: 100%;">
                    <?php
                    if (!empty($payment->payment_custom_fields)) {
                        $paymentFields = json_decode($payment->payment_custom_fields);
                        foreach ($paymentFields as $field) {
                            ?>
                            <tr>
                                <td style="padding: 10px 10px 2px 10px; width: 40%;"><label><?= ucwords($field->field) ?></label>: </td>
                                <td style="padding: 10px 10px 2px 10px;"> 
                                    <label>
                                        <?= $this->Form->control("payment_custom_fields[][$field->field]", ['placeholder' => $field->field, 'value' => $field->value, 'class' => 'text-box payment-attr', 'label' => false, 'style' => 'width: 250px;']); ?>
                                    </label>
                                </td>
                            </tr> 
                            <?php
                        }
                    }
                    ?>  
                    <tr>
                        <td style="padding: 10px 10px 2px 10px; width: 40%;"><label>Bill Amount</label>: </td>
                        <td style="padding: 10px 10px 2px 10px;"> <label><?= $this->Form->control('fee', ['placeholder' => 'Bill Amount', 'class' => 'text-box', 'id' => 'total', 'value' => $payment->fee, 'label' => false, 'style' => 'width: 250px;']); ?></label></td>
                    <input type="hidden" name="fee" id="hidden-input">
                    </tr>   
                </table>
            </div>   
        </div>
        <!-- Modal footer -->
        <div class="modal-footer" style="margin-top: 20px;margin-bottom: -7px;">            
            <button type="button" class="button-2" data-dismiss="modal">Cancel</button>   
            <button type="submit" class="button-3">Update</button>               
        </div>    
        <?= $this->Form->end(); ?>
    </div>
</div>

