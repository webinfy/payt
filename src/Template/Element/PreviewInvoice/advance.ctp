<?php
$additionalCustomerFields = json_decode($payment->payee_custom_fields);
$customerFieldCount = count($additionalCustomerFields);

$paymentCustomFields = json_decode($payment->payment_custom_fields, true);
$paymentFieldCount = count($paymentCustomFields);

$due_date = $payment->webfront->payment_cycle_date;

if ($payment->status == 1) {
    $lateFee = $payment->late_fee_amount;
    $convenienceFee = $payment->convenience_fee_amount;
    $grandTotal = $payment->paid_amount;
} else {
    $lateFee = $payment->late_fee_amount;
    $convenienceFee = $payment->webfront->merchant->convenience_fee_amount;
    $grandTotal = $payment->fee + $lateFee + $convenienceFee;
}

$colspan = $payment->status == 1 ? 3 : 2;
?>
<style>
    .razorpay-payment-button { margin-bottom: 8px !important;cursor: pointer;border: 0;text-align: center;display: inline-block;background: #3f51b5;color: #fff;font-size: 14px;text-transform: uppercase;padding: 10px 30px;border-radius: 3px; }
    .payment-div { text-align: center; }
    #razorpay-form { display: inline; }
</style>
<div class="main-content">

    <h1 class="text-center">Pay Online for <span class="blue-color"><?= $payment->webfront->title ?></span></h1>

    <div class="payment-table-section payment-page">

        <?php if ($payment->status == 1) { ?>
            <div class="important-links text-right">
                <a download="" href="<?= HTTP_ROOT . INVOICE_PDF . sprintf('%04d', $payment->id) . ".pdf"; ?>">Download Receipt</a>
                <a href="javascript:;" onclick="printdiv('div_print')">Print Receipt</a>
            </div>
            <div style="position: relative;text-align: center;">            
                <img style="position: absolute; left: 40%; top: -30px;" src="<?php echo 'images/paid.png'; ?>" alt="Paid"  />                             
            </div>        
        <?php } ?>

        <h3>Customer Details</h3>     
        <table class="customer-table" width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <th>Name</th>    
                <th>Email</th>
                <th>Phone</th>
                <?php
                if ($customerFieldCount > 0) {
                    foreach ($additionalCustomerFields as $additionalCustomerField) {
                        ?>
                        <th><?= ucfirst($additionalCustomerField->field) ?></th>
                        <?php
                    }
                }
                ?>
            </tr>
            <tr>
                <td><?= $payment->name ?></td>
                <td><?= $payment->email ?></td>
                <td><?= $payment->phone ?></td>
                <?php
                if ($customerFieldCount > 0) {
                    foreach ($additionalCustomerFields as $additionalCustomerField) {
                        ?>
                        <td><?= $additionalCustomerField->value ?></td>
                        <?php
                    }
                }
                ?>
            </tr>
        </table>
        <h3>Payment Details</h3>      
        <table class="payment-table" width="100%" cellpadding="0" cellspacing="0">
            <tr>   
                <th>Invoice No</th>                
                <th>Invoice Date</th>
                <?php if ($payment->status) { ?>
                    <th>Payment Date</th>  
                <?php } ?>
                <th>Payment Status</th>                  
            </tr>
            <tr>
                <td><?= formatInvoiceNo($payment->id); ?></td>                
                <td><?= $this->Custom->dateDisplay($payment->created); ?></td>
                <?php if ($payment->status) { ?>
                    <td><?= $this->Custom->dateDisplay($payment->payment_date); ?></td>    
                <?php } ?>
                <td><?= $payment->status == 1 ? 'Paid' : 'Not Paid'; ?></td>   
            </tr>        
            <?php
            if ($paymentFieldCount > 0) {
                foreach ($paymentCustomFields as $paymentCustomField) {
                    ?>
                    <tr>
                        <td colspan="<?= $colspan ?>"><?= ucwords($paymentCustomField['field']) ?></td>
                        <td>Rs. <?= formatPrice($paymentCustomField['value']) ?></td>
                    </tr>                                         
                    <?php
                }
            }
            ?>
            <tr>
                <td class="text-right" colspan="<?= $colspan ?>"><b>Total Amount</b></td>
                <td class="text-left"><b>Rs. <?= formatPrice($payment->fee); ?></b></td>
            </tr>
            <tr>
                <td class="text-right" colspan="<?= $colspan ?>">Late Fee</td>
                <td class="text-left">Rs. <?= formatPrice($lateFee); ?></td>
            </tr>
            <tr>
                <td class="text-right" colspan="<?= $colspan ?>">Convenience Fee</td>
                <td class="text-left">Rs. <?= formatPrice($convenienceFee); ?></td>
            </tr>
            <tr>
                <td class="text-right" colspan="<?= $colspan ?>"><b>Net Amount</b></td>
                <td class="text-left"><b>Rs. <?= formatPrice($grandTotal) ?></b></td>
            </tr>            
        </table> 

        <div style="width: 100%; text-align: right;">
            <?php if ($payment->webfront->late_fee_type == 2) { ?>
                <div style="font-size: 14px; margin-top: 10px; display: block;"> Note : Late Fee is Rs <?= $payment->webfront->late_fee_amount ?> Per <?= $payment->webfront->recurring_period ?> Day from Due Date.</div>
            <?php } elseif ($payment->webfront->late_fee_type == 3) { ?>
                <div style="font-size: 14px; margin-top: 10px; display: block;"> Note : Late Fee is Rs. <?= $lateFee ?> till per rules below.<br>
                    <small>
                        <i>
                            <!--Late fees is Rs. <?= $payment->webfront->late_fee_amount ?> after <?= date('d M, Y', strtotime($due_date)) ?><br>-->
                            Late fees is Rs. <?= $payment->webfront->periodic_amount_1 ?> after <?= date('d M, Y', strtotime($due_date . " +{$payment->webfront->periodic_days_1} days")) ?><br>
                            Late fees is Rs. <?= $payment->webfront->periodic_amount_2 ?> after <?= date('d M, Y', strtotime($due_date . " +{$payment->webfront->periodic_days_2} days")) ?>
                        </i>
                    </small>
                </div>
            <?php } else { ?>
                <div style="font-size: 14px; margin-top: 10px; display: block;"> Note : Late Fee is Flat Rs <?= $payment->webfront->late_fee_amount ?> after Due Date.</div>
            <?php } ?>
        </div> 


        <?php if ($payment->status != 1) { // If not Paid Yet  ?> 
            <div class="payment-div">
                <?php
                if (!empty($payment->webfront->user->merchant_payment_gateway)) {
                    if ($payment->webfront->user->merchant_payment_gateway->payment_gateway->id == 2) {
                        ?>
                        <?= $this->Form->create(NULL, ['id' => 'razorpay-form', 'url' => HTTP_ROOT . "razorPayResponse/" . $payment->uniq_id]) ?>
                        <p class="text-center"> <label><?= $this->Form->checkbox('is_agree', ['required' => TRUE]); ?> Accept Term & Conditions</label></p>
                        <script
                            src="https://checkout.razorpay.com/v1/checkout.js"
                            data-key="<?= $payment->webfront->user->merchant_payment_gateway->merchant_key ?>"
                            data-amount="<?= $grandTotal * 100 ?>"
                            data-buttontext="Pay Now"
                            data-name="<?= $payment->name ?>"
                            data-description="<?= $payment->webfront->title ?>"
                            data-image="<?= HTTP_ROOT . WEBFRONT_LOGO . $payment->webfront->logo ?>"
                            data-prefill.name="<?= $payment->name ?>"
                            data-prefill.email="<?= $payment->email ?>"
                            data-prefill.contact="<?= $payment->phone ?>"
                            data-theme.color="#7fc35b"
                        ></script>
                        <input type="hidden" value="<?= $grandTotal ?>" name="paid_amount">
                        <input type="hidden" value="<?= $convenienceFee ?>" name="convenience_fee_amount">
                        <input type="hidden" value="<?= $lateFee ?>" name="late_fee_amount">
                        <input type="hidden" value="<?= $payment->uniq_id ?>" name="uniq_id">
                        <input type="hidden" value="RazorPay" name="gateway_name">
                        <?= $this->Form->end() ?>
                    <?php } else if ($payment->webfront->user->merchant_payment_gateway->payment_gateway->id == 1) { ?>
                        <?php
                        $key = $payment->webfront->user->merchant_payment_gateway->merchant_key;
                        $salt = $payment->webfront->user->merchant_payment_gateway->merchant_salt;
                        if ($payment->webfront->user->merchant_payment_gateway->payment_gateway->is_live) {
                            $payuUrl = $payment->webfront->user->merchant_payment_gateway->payment_gateway->live_url;
                        } else {
                            $payuUrl = $payment->webfront->user->merchant_payment_gateway->payment_gateway->test_url;
                        }
                        $txnid = getTxnID();

                        $email = $payment->email;
                        $name = $payment->name;
                        $phone = $payment->phone;
                        $udf1 = $convenienceFee;
                        $udf2 = $lateFee;

                        $productName = "Payment For " . $payment->webfront->title;

                        $successUrl = HTTP_ROOT . "payuResponse/" . $payment->uniq_id;
                        $failureUrl = HTTP_ROOT . "payuResponse/" . $payment->uniq_id;
                        $cancelUrl = HTTP_ROOT . "preview-invoice/" . $payment->uniq_id;

                        $text = "{$key}|{$txnid}|{$grandTotal}|{$productName}|{$name}|{$email}|$udf1|$udf2|||||||||{$salt}";
                        $output = strtolower(hash("sha512", $text));
                        ?>
                        <form action='<?= $payuUrl ?>' method='post' style="display: inline;">
                            <input type="hidden" name="key" value="<?= $key; ?>" />
                            <input type="hidden" name="txnid" value="<?= $txnid; ?>" />
                            <input type="hidden" name="amount" value="<?= $grandTotal; ?>" />
                            <input type="hidden" name="productinfo" value="<?= $productName ?>" />
                            <input type="hidden" name="firstname" value="<?= $name; ?>" />
                            <input type="hidden" name="email" value="<?= $email; ?>" />  
                            <input type="hidden" name="phone" value="<?= $phone ?>"/>

                            <input type="hidden" name="udf1" value="<?= $convenienceFee ?>"/> <!-- convenience_fee_amount as udf1 -->           
                            <input type="hidden" name="udf2" value="<?= $lateFee ?>"/><!-- late fee as udf2 -->  

                            <input type="hidden" name="hash" value = <?= $output; ?> /> 

                            <input type="hidden" name="surl" value="<?= $successUrl ?>" /> <!--Success URL where PayUMoney will redirect after successful payment.-->
                            <input type="hidden" name="furl" value="<?= $failureUrl ?>" /> <!--Failure URL where PayUMoney will redirect after failed payment.-->  
                            <input type="hidden" name="curl" value="<?= $cancelUrl ?>" />  <!--Cancel URL where PayUMoney will redirect when user cancel the transaction.-->

                            <p class="text-center"> <label><?= $this->Form->checkbox('is_agree', ['required' => TRUE]); ?> Accept Term & Conditions</label></p>
                            <button type="submit" class="button-2" style="margin:0 5px;">Pay Now</button>
                        </form>   
                    <?php } ?>
                <?php } ?>
                <a class="button-3" href="advance-webfronts">Cancel</a>
            </div>
        <?php } ?>

    </div>
</div>
