<style>
    .pagination-style { margin-top: 10px; }
    .pagination-style li.prev { width: 70px; }
    .pagination-style li.next { width: 70px; }
    .btn-blue { margin-left: 10px; }
</style>
<div class="main-content-section">

    <div class="dashboard-menu search-item-div">
        <?= $this->Form->create(NULL, ['type' => 'get']) ?>
        <?= $this->Form->control('webfront_id', ['type' => 'select', 'options' => $webfrontList, 'empty' => 'Select Webfront', 'class' => 'select-1', 'id' => 'typeSelect', 'default' => $webfrontID, 'label' => FALSE, 'required' => TRUE]) ?>
        <?= $this->Form->control('keyword', ['class' => 'text-box', 'label' => FALSE, 'placeholder'=> 'Search By Keyword', 'style' => "width: 250px; margin: 0 5px 0 15px;", 'value' => urldecode(@$_REQUEST['keyword'])]) ?>
        <input type="submit" value="View Invoices" class="btn-blue">
        <?= $this->Form->end() ?>
    </div>

    <div class="main-content payment-list-div">
        <div class="payment-table">
            <table width="100%" align="left" cellpadding="0" cellspacing="0" class="payment-list-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Bill Amount</th>
                        <th>Payment Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $payment) { ?>
                        <tr>
                            <td><?= $payment->name ?></td>
                            <td><?= $payment->email ?></td>
                            <td><?= $payment->phone ?></td>
                            <td>Rs.<?= $payment->paid_amount ?></td>
                            <td>
                                <?= ($payment->status == 0) ? 'Not Paid' : 'Paid' ?>
                            </td>
                            <td>
                                <div class="action-btn"><i class="fa fa-th" aria-hidden="true"></i>
                                    <ul class="action-btn-list">
                                        <li><a data-toggle="modal" data-target="#detailModal<?= $payment->id ?>" href="javascript:;"><i class="fa fa-angle-right" aria-hidden="true"></i>View Detail</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php if (empty($payments) || $payments->count() < 1) { ?>
                        <tr><td colspan="6">No Record Found!!</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <?php if (!empty($payments) && $this->Paginator->numbers()) { ?>
            <div class="paginator-div pagination-style">
                <ul>
                    <?= $this->Paginator->prev(__('Previous')) ?>
                    <?= $this->Paginator->numbers() ?>
                    <?= $this->Paginator->next(__('Next')) ?>
                </ul>
            </div>
        <?php } ?>

    </div>
</div>
<?php foreach ($payments as $payment) { ?>
    <!-- The Modal -->
    <div class="modal" id="detailModal<?= $payment->id ?>" >
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h3>Payment Details</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">

                    <table class="table table-striped">
                        <tbody>

                            <tr>
                                <th scope="row">Name:</th>
                                <td><?= $payment->name ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Email:</th>
                                <td><?= $payment->email ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Phone:</th>
                                <td><?= $payment->phone ?></td>
                            </tr>
                            <?php if ($payment->payee_custom_fields != NULL) { $customPayee = json_decode($payment->payee_custom_fields);
                                foreach($customPayee as $payee) {
                            ?>
                                <tr>
                                    <th scope="row"><?= ucwords($payee->field) ?>:</th>
                                    <td><?= $payee->value ?></td>
                                </tr>
                            <?php }} ?>
                            <?php if ($payment->payment_custom_fields != NULL) { $customPayment = json_decode($payment->payment_custom_fields);
                                foreach($customPayment as $cpayment) {
                            ?>
                                <tr>
                                    <th scope="row"><?= ucwords($cpayment->field) ?>:</th>
                                    <td>Rs.<?= $cpayment->value ?></td>
                                </tr>
                            <?php }} ?>
                            <tr>
                                <th scope="row">Total Amount:</th>
                                <td>Rs.<?= $payment->paid_amount ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Status:</th>
                                <td><?= ($payment->status == 0) ? 'Not Paid' : 'Paid' ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Transaction ID:</th>
                                <td><?= ($payment->txn_id == NULL) ? 'Unavailable' : $payment->txn_id ?></td>
                            </tr>

                        </tbody>
                    </table>

                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
<?php } ?>
<script>
    $(document).ready(function () {
//        $('#typeSelect').on('change', function () {
//            if ($(this).val().length > 0) {
//                $('.btn-blue').removeAttr('disabled');
//            } else {
//                $('.btn-blue').attr('disabled', true);
//            }
//        });
    });
</script>