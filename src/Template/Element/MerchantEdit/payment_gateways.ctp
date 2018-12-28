<!-- PaymentGateways Tab Start-->
<div id="PaymentGateways" class="tab-pane fade">
    <div class="border-coloring">
        <div class="dotted-bordering">
            <?= $this->Form->create($merchantPaymentGateway, ['class' => 'main-form', 'id' => 'paymentValidation']) ?> 
            <?= $this->Form->control('action', ['type' => 'hidden', 'value' => 'PaymentGateways']); ?>
            <div class="form-div">
                <label class="form-label">Name <span class="required-fild">*</span>:</label>
                <?= $this->Form->control('title', ['label' => false, 'class' => 'text-box', 'placeholder' => 'Name']); ?>
            </div>
            <div class="form-div">
                <label class="form-label">Payment Gateway<span class="required-fild">*</span>:</label>
                <?= $this->Form->control('payment_gateway_id', ['type' => 'select', 'id' => 'payment_gateway', 'options' => $paymentGatewayList, 'empty' => 'Select Payment Gateway', 'class' => 'text-box', 'id' => 'webfrontSelect', 'label' => FALSE]) ?>
            </div>
            <div class="form-div">
                <label class="form-label">Key <span class="required-fild">*</span>:</label>
                <?= $this->Form->control('merchant_key', ['label' => false, 'class' => 'text-box', 'placeholder' => 'Payment Gateway Key']); ?>
            </div>
            <div class="form-div">
                <label class="form-label">Salt <span class="required-fild">*</span>:</label>
                <?= $this->Form->control('merchant_salt', ['label' => false, 'class' => 'text-box', 'placeholder' => 'Payment Gateway Salt']); ?>
            </div>
            <div class="form-div">
                <input type="submit" value="Save" class="button-2 padding-left140">
                <a class="button-3" href="<?= HTTP_ROOT; ?>merchants">Cancel </a>
            </div>
            <?= $this->Form->end(); ?>   
        </div>
    </div>            

    <div style="clear:both;">&nbsp;</div>

    <div class="section-2" style="width: 100%;float:left;">
        <div class="section-tile">
            <div class="accordion" id="fields">

                <?php
                $i = 1;
                foreach ($merchantPaymentGateways as $paymentGateway) {
                    ?>
                    <div class="card">
                        <div class="card-header">
                            <a class="making-block <?= ($i != 1) ? "collapsed" : "" ?>" data-toggle="collapse" href="#collapse<?= $i ?>" role="button" aria-expanded="false">
                                <span style="display: block;"><?= $paymentGateway->title; ?></span>
                            </a>
                        </div>
                        <div id="collapse<?= $i ?>" class="collapse <?= ($i == 1) ? "show" : "" ?>" data-parent="#fields">
                            <div class="card-body" style="padding: 0px;display: block;float: left; max-width: 85%; margin-top: 20px;">
                                <div id="showPGInfo<?= $paymentGateway->id ?>">
                                    <div class="pro-d-left">Name</div>
                                    <div class="pro-d-right">: <?= $paymentGateway->title; ?></div>
                                    <div class="pro-d-left">Payment Gateway</div>
                                    <div class="pro-d-right">: <?= $paymentGateway->payment_gateway->name; ?></div>
                                    <div class="pro-d-left">Key</div>
                                    <div class="pro-d-right">: <?= $paymentGateway->merchant_key; ?></div>
                                    <div class="pro-d-left">Salt </div>
                                    <div class="pro-d-right">: <?= $paymentGateway->merchant_salt; ?></div>
                                    <div class="pro-d-left">Active </div>
                                    <div class="pro-d-right">: <?= $paymentGateway->is_default ? 'Yes' : 'No'; ?></div>
                                </div>
                                <div id="editPGInfo<?= $paymentGateway->id ?>" style="display: none;">
                                    <?= $this->Form->create($paymentGateway, ['class' => 'main-form', 'id' => 'form' . $paymentGateway->id]) ?>
                                    <?= $this->Form->control('id', ['type' => 'hidden', 'value' => $paymentGateway->id]); ?>
                                    <?= $this->Form->control('action', ['type' => 'hidden', 'value' => 'PaymentGateways']); ?>
                                    <div class="form-div" style="margin-left: 30px;">
                                        <label class="form-label">Name <span class="required-fild">*</span>:</label>
                                        <?= $this->Form->control('title', ['label' => false, 'class' => 'text-box', 'placeholder' => 'Name']); ?>
                                    </div>
                                    <div class="form-div" style="margin-left: 30px;">
                                        <label class="form-label">Payment Gateway<span class="required-fild">*</span>:</label>
                                        <?= $this->Form->control('payment_gateway_id', ['type' => 'select', 'options' => $paymentGatewayList, 'empty' => 'Select Payment Gateway', 'class' => 'select-1', 'id' => 'webfrontSelect', 'label' => FALSE, 'style' => 'width: 65%;']) ?>
                                    </div>
                                    <div class="form-div" style="margin-left: 30px;">
                                        <label class="form-label">Key <span class="required-fild">*</span>:</label>
                                        <?= $this->Form->control('merchant_key', ['label' => false, 'class' => 'text-box', 'placeholder' => 'Payment Gateway Key']); ?>
                                    </div>
                                    <div class="form-div" style="margin-left: 30px;">
                                        <label class="form-label">Salt <span class="required-fild">*</span>:</label>
                                        <?= $this->Form->control('merchant_salt', ['label' => false, 'class' => 'text-box', 'placeholder' => 'Payment Gateway Salt']); ?>
                                    </div>
                                    <div class="form-div">
                                        <input type="submit" value="Update" id="update-btn" name="update-btn" class="button-2 padding-left140" style="margin-left: 190px;">
                                        <a class="button-3" href="<?= HTTP_ROOT; ?>merchants">Cancel </a>
                                    </div>
                                    <?= $this->Form->end(); ?>   
                                </div>

                            </div>   
                            <div style="float: right; margin: 5px 10px;">
                                <?php if ($paymentGateway->is_default) { ?>
                                    <a href="<?= HTTP_ROOT . "merchants/activatePaymentGateway/{$paymentGateway->unique_id}" ?>" onclick="return confirm('Are you sure you want to De-Activate?')"><i class="fa fa-thumbs-up" style="color: #28d227; font-size: 25px;" aria-hidden="true"></i></a>&nbsp;
                                <?php } else { ?>
                                    <a href="<?= HTTP_ROOT . "merchants/activatePaymentGateway/{$paymentGateway->unique_id}" ?>" onclick="return confirm('Are you sure you want to activate?')"><i class="fa fa-thumbs-down" style="color: #e22e4e; font-size: 25px;" aria-hidden="true"></i></a>&nbsp;
                                <?php } ?>
                                <a href="javascript:;" data-id="<?= $paymentGateway->id ?>" class="togglePGInfo"><i class="fa fa-pencil-square-o" style="font-size: 23px;padding-top: 7px;" aria-hidden="true"></i></a>&nbsp;
                                <a href="<?= HTTP_ROOT . "merchants/deletePaymentInfo/{$paymentGateway->unique_id}" ?>" onclick="return confirm('Are you sure you want to delete?')"><i class="fa fa-trash-o" style="color: #212529;font-size: 25px;" aria-hidden="true"></i></a>
                            </div> 
                        </div> 
                    </div>
                    <?php
                    $i++;
                }
                ?>
            </div>
        </div>
    </div>
</div>
<!-- PaymentGateways Tab End-->

<script>
    $(function () {
        $(".togglePGInfo").click(function (e) {
            var id = $(this).attr('data-id');
            $("#showPGInfo" + id).toggle();
            $("#editPGInfo" + id).toggle();
        });
    });
</script>
