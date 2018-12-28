<style>
    .btn-orange { float: right;margin-right: 20px; }
    .btn-blue { float: right; }
    #btn-go { float: left;margin-left: 10px; }
</style>
<div class="main-content-section">

    <div class="dashboard-menu search-item-div">
        <?= $this->Form->create(NULL, ['type' => 'POST']) ?>
        <?= $this->Form->control('webfront_id', ['type' => 'select', 'options' => $webfrontList, 'empty' => 'Select Webfront', 'class' => 'select-1', 'id' => 'typeSelect', 'default' => $webfrontID, 'style' => "width: 15%; margin: 0 5px;", 'label' => FALSE, 'required' => TRUE]) ?>
        <?= $this->Form->control('date', ['placeholder' => 'Search by Due Date', 'class' => 'text-box datepicker', 'autocomplete' => 'off', 'style' => "width: 15%; margin: 0 5px;", 'label' => FALSE, 'value' => @$_GET['date']]) ?>
        <?= $this->Form->control('keyword', ['placeholder' => 'Search by Keyword', 'class' => 'text-box', 'style' => "width: 15%; margin: 0 5px;", 'label' => FALSE, 'value' => @$_GET['keyword']]) ?>
        <div class="input select">
            <select name="status" class="select-1" style="width: 15%;">
                <option value="">Select Status</option>
                <option value="1" <?php if (@$_GET['status'] == 1) { ?> selected <?php } ?>>Paid</option>
                <option value="2" <?php if (@$_GET['status'] == 2) { ?> selected <?php } ?>>Not Paid</option>
            </select>
        </div>
        <div class="right-submit-box">
            <input type="submit" name="download" value="Download Report" class="btn-orange">
            <input type="submit" value="View Report" class="btn-blue">
            <?= $this->Form->end() ?>
        </div>
    </div>

    <div class="main-content payment-list-div">
        <?php if (!empty($webfrontID)) { ?>
            <?= $this->Form->create(NULL, ['type' => 'POST', 'url' => ['controller' => 'Merchants', 'action' => 'deleteInvoices'], 'onSubmit' => "return confirm('Are you sure you want to delete?')"]); ?> 
             <div id="actionsDiv" style="float: right; display: none; margin-bottom: 10px;">
                <button class="btn-red" type="submit" style="margin-right: 0;"><i class="fa fa-trash-o"></i>Delete Selected</button>
            </div>      
            <div class="report-box">
                <table width="100%" align="left" cellpadding="0" cellspacing="0" class="payment-list-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"  /></th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Bill Amount</th>
                            <th>Created</th>
                            <th>Status</th>
                            <th>Paid Via</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($payments as $payment) { ?>
                            <tr>
                                <td><input type="checkbox" class="checkbox" name="delete_invoices[]" value="<?= $payment->uniq_id; ?>" data-status="<?= $payment->status; ?>" <?php if ($payment->status == 1) { ?> disabled <?php } ?> /></td>
                                <td><?= $payment->name ?></td>
                                <td><?= $payment->email ?></td>
                                <td><?= $payment->phone ?></td>
                                <td>Rs.<?= $payment->paid_amount ?></td>
                                <td><?= date_format($payment->created, 'd M, Y') ?></td>
                                <td>
                                    <?php if ($payment->status == 0) { ?>
                                        <a onclick="return confirm('Are sure you want to mark this as paid?');" href="<?= HTTP_ROOT . "webfronts/update-payment-status/{$payment->uniq_id}/1" ?>" class="un-paid">Un Paid</a>
                                    <?php } else { ?>
                                        <a onclick="return confirm('Are sure you want to mark this as not paid?');" href="<?= HTTP_ROOT . "webfronts/update-payment-status/{$payment->uniq_id}/0" ?>" class="paid">Paid</a>
                                    <?php } ?>
                                </td>
                                <td><?= ($payment->status == 1) ? $payment->paid_via : ''; ?></td>
                                <td>
                                    <div class="action-btn"><i class="fa fa-th" aria-hidden="true"></i>
                                        <ul class="action-btn-list">
                                            <li><a href="<?= HTTP_ROOT . "preview-invoice/" . $payment->uniq_id ?>" target="_blank"><i class="fa fa fa-eye" aria-hidden="true"></i>Preview Invoice</a></li>
                                            <?php if ($payment->status == 0) { ?>
                                                <li><a href="<?= HTTP_ROOT . "delete-invoice/" . $payment->uniq_id ?>" onClick="return confirm('Are you sure you want to delete?')"><i class="fa fa-trash-o" aria-hidden="true"></i>Delete</a></li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php if (!$payments->count()) { ?>
                            <tr><td colspan="8" style="color: red;">No Record Found!!</td></tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?= $this->Form->end(); ?>
        <?php } ?>
    </div>

    <?php if ($this->Paginator->numbers()): ?>
        <ul class="pagination-style">
            <?= $this->Paginator->prev(('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(('next')) ?>
        </ul>
    <?php endif; ?>

</div>

<script>
    $(document).ready(function () {

        $('input[name="delete_invoices[]"]').on('click', function () {
            showActions();
        });

        $('#selectAll').on('click', function () {
            if (this.checked) {
                $('input[name="delete_invoices[]"][data-status="0"]').prop('checked', true);
            } else {
                $('input[name="delete_invoices[]"]').prop('checked', false);
            }
            showActions();
        });

    });

    function showActions() {
        if ($('input[name="delete_invoices[]"]:checked').length > 0) {
            $('#actionsDiv').show();
        } else {
            $('#actionsDiv').hide();
        }
    }
</script>