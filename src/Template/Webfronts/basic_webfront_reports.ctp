<style>
    .btn-orange { float: right;margin-right: 20px; }
    .btn-blue { float: right; }
    #btn-go { float: left;margin-left: 10px; }
</style>
<div class="main-content-section">

    <div class="dashboard-menu search-item-div">
        <?= $this->Form->create(NULL, ['type' => 'POST', 'style' => "float: left; width: 100%;"]); ?> 
        <?= $this->Form->control('webfront_id', ['options' => $webfrontList, 'empty' => 'Select Webfront', 'class' => 'select-1', 'id' => 'webfrontSelect', 'default' => $webfrontId, 'required' => TRUE, 'label' => FALSE, 'style' => "width: 15%; margin: 0 5px;",]) ?>
        <?= $this->Form->control('due_date', ['placeholder' => 'Search by Due Date', 'class' => 'text-box datepicker', 'autocomplete' => 'off', 'style' => "width: 15%; margin: 0 5px;", 'label' => FALSE, 'value' => @$_GET['due_date']]) ?>
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
        </div>
        <?= $this->Form->end(); ?>
    </div>

    <div class="main-content payment-list-div" style="margin-top: 15px;">  
        <?php if (!empty($webfrontId)) { ?>
            <?= $this->Form->create(NULL, ['type' => 'POST', 'url' => ['controller' => 'Merchants', 'action' => 'deleteInvoices'], 'onSubmit' => "return confirm('Are you sure you want to delete?')"]); ?> 
            <div id="actionsDiv" style="float: right; display: none; margin-bottom: 10px;">
                <button class="btn-red customalert" type="submit" style="margin-right: 0;"><i class="fa fa-trash-o"></i>Delete Selected</button>
            </div>        
            <div class="report-box">
                <table width="100%" align="left" cellpadding="0" cellspacing="0" class="payment-list-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"  /></th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Ref No.</th>
                            <th>Bill Amount</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Paid Via</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($payments as $payment) { ?>
                            <tr>
                                <td><input type="checkbox" class="checkbox" name="delete_invoices[]" value="<?= $payment->uniq_id; ?>" data-status="<?= $payment->status; ?>" <?php if ($payment->status == 1) { ?> disabled <?php } ?> /></td>
                                <td><?= $payment->name; ?></td>
                                <td><?= $payment->email; ?></td>
                                <td><?= $payment->phone; ?></td>
                                <td><?= $payment->reference_number; ?></td>
                                <td>Rs.<?= formatPrice($payment->fee); ?></td>
                                <td><?= date_format($payment->uploaded_payment_file->payment_cycle_date, 'd M, Y'); ?></td>
                                <td>
                                    <?php if ($payment->status == 0) { ?>
                                        <a onclick="customConfirm('You want to mark this as paid.', '<?= HTTP_ROOT . "webfronts/update-payment-status/{$payment->uniq_id}/1" ?>');" href="javascript:;" class="un-paid">Un Paid</a>
                                    <?php } else { ?>
                                        <a onclick="customConfirm('You want to mark this as not paid.', '<?= HTTP_ROOT . "webfronts/update-payment-status/{$payment->uniq_id}/0" ?>');" href="javascript:;" class="paid">Paid</a>
                                    <?php } ?>
                                </td>
                                <td><?= ($payment->status == 1) ? $payment->paid_via : ''; ?></td>
                                <td>
                                    <div class="action-btn"><i class="fa fa-th" aria-hidden="true"></i>
                                        <ul class="action-btn-list">
                                            <li><a href="<?= HTTP_ROOT . "preview-invoice/" . $payment->uniq_id ?>" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i>Preview Invoice</a></li>
                                            <li><a href="<?= HTTP_ROOT . "resend-invoice-email/" . $payment->uniq_id ?>"><i class="fa fa-mail-forward" aria-hidden="true"></i>Resend Email</a></li>
                                            <?php if ($payment->status == 0) { ?>
                                                <li><a href="javascript:;" onclick="editInvoicePopup('<?= $payment->uniq_id ?>')"><i class="fa fa-edit" aria-hidden="true"></i>Edit</a></li>
                                                <li><a onclick="customConfirm('You want to delete.', '<?= HTTP_ROOT . "delete-invoice/" . $payment->uniq_id ?>');" href="javascript:;"><i class="fa fa-trash-o" aria-hidden="true"></i>Delete</a></li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php if (!$payments->count()) { ?>
                            <tr><td colspan="10" style="color: red; text-align: center;">No Record Found!!</td></tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?= $this->Form->end(); ?>
        <?php } ?>
    </div>



    <?php if ($this->Paginator->numbers()): ?>
        <ul class="pagination-style">
            <?= $this->Paginator->prev(('Prev')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(('Next')) ?>
        </ul>
    <?php endif; ?>   

</div>

<div class="modal" id="editInvoice"></div>

<script>

    function editInvoicePopup(uniqueID) {
        $.get(siteUrl + 'webfronts/editInvoice/' + uniqueID, function (response) {
            if (response) {
                $('#editInvoice').html(response).modal('show');
            }
        });
    }

    function calculateTotalPrice() {
        var sum = 0;
        $('.payment-attr').each(function () {
            if ($(this).val()) {
                sum += parseFloat($(this).val());
            }
        });
        $('#total').val(sum);
        $('#hidden-input').val(sum);
        console.log(sum);
    }

    $(document).ready(function () {

        $('#webfrontSelect').on('change', function () {
            if ($(this).val().length > 0) {
                $('.btn-blue').removeAttr('disabled');
                $('.btn-orange').removeAttr('disabled');
            } else {
                $('.btn-blue').attr('disabled', true);
                $('.btn-orange').attr('disabled', true);
            }
        });

        if ($('#webfrontSelect').val().length > 0) {
            $('.btn-orange').removeAttr('disabled');
        } else {
            $('.btn-orange').attr('disabled', true);
        }

        $(document).on('keyup keypress blur change', '.payment-attr', function (e) {
            calculateTotalPrice();
        });

        $(document).on('submit', '#editInvoiceForm', function (e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: siteUrl + 'webfronts/ajaxEditInvoice/',
                data: formData,
                success: function (response) {
                    if (response == 'success') {
                        $('#editInvoice').modal('hide');
                        alert('Updated Successfully!!');
                    } else {
                        alert('Failed to Update!!');
                    }
                }
            });
        });

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
    $('.customalert').on('click', function (e) {
            e.preventDefault();
            var link = $(this).attr('href');
            swal({
                title: 'Are you sure?',
                text: "You want to delete.",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'rgb(140, 212, 245)',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!!'
            },
            function (isConfirm) {
                if (isConfirm) {
                    window.location.href = link;
                }
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