<style>
    .modal-body tr:nth-child(2n+1) {
        background: #f1f1f1;
        line-height: 16px;
    }
    .edit-payment tr:nth-child(2n+1) {
        background: #FFF !important;
    }
</style>
<div class="main-content-section">
    <div class="main-content payment-list-div">
        <div class="payment-list-top">
            <?= $this->Form->create(null, ['type' => 'GET', 'onChange' => '$(this).submit();', 'valueSources' => ['query']]); ?>
            <?= $this->Form->select('filter_by', ['Invoice List', 'PAID' => 'Paid Invoice List', 'UNPAID' => 'Unpaid Invoice List'], ['value' => $this->request->getQuery('sort'), 'class' => 'select-1', 'label' => false]); ?>
            <?= $this->Form->end(); ?>
            <ul class="payment-list-buttons">
                <li>
                    <a href="javascript:;" class="btn-blue" onclick="$('#select_all').click();"><i class="fa fa-list-alt" aria-hidden="true"></i>Select All </a>
                </li>
                <li><button class="btn-red" id="btn_dlt"><i class="fa fa-trash-o" aria-hidden="true"></i>Delete All</button></li>
                <li><a href="<?= HTTP_ROOT . "merchants/downloadReport/{$uploadFileId->id}" ?>" onclick="return confirm('Are you sure you want all the invoices report ?')" class="btn-orange"><i class="fa fa-download" aria-hidden="true"></i>Download Report</a></li>
            </ul>
        </div>
        <table width="100%" align="left" cellpadding="0" cellspacing="0" class="payment-list-table">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select_all"></th>
                    <th>Name</th>
                    <th>Ref No. </th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Bill Amount</th>
                    <!--<th>PayU Status</th>-->
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $payment) { //pj($payments);exit;?>
                    <tr id="<?= $payment->id; ?>">
                        <td><input type="checkbox" class="checkbox" name="payment_id[]" data-emp-id="<?= $payment->id; ?>" /></td>
                        <td><?= $payment->name; ?></td>
                        <td><?= $payment->reference_number; ?></td>
                        <td><?= $payment->email; ?></td>
                        <td><?= $payment->phone; ?></td>
                        <td>Rs. <?= $payment->fee; ?></td>
                        <!--<td><?= $payment->unmappedstatus; ?></td>-->
                        <td>
                            <?php if ($payment->status == 0) { ?>
                                <a href="javascript:;" class="un-paid">Un Paid</a>
                            <?php } else { ?>
                                <a href="javascript:;" class="paid">Paid</a>
                            <?php } ?>
                        </td>
                        <td>
                            <div class="action-btn"><i class="fa fa-th" aria-hidden="true"></i>
                                <ul class="action-btn-list">
                                    <li><a data-toggle="modal" data-target="#myModal<?= $payment->id; ?>" href="javascript:;"><i class="fa fa-angle-right" aria-hidden="true"></i>View</a></li>
                                    <li><a href="<?= HTTP_ROOT . "merchants/resendEmail/" . $payment->uniq_id ?>"><i class="fa fa-angle-right" aria-hidden="true"></i>Resend Email</a></li>
                                    <li><a href="<?= HTTP_ROOT . "preview-invoice/" . $payment->uniq_id ?>" target="_blank"><i class="fa fa-angle-right" aria-hidden="true"></i>Preview Invoice</a></li>
                                    <!--<li><a href="javascript:;"><i class="fa fa-angle-right" aria-hidden="true"></i>Edit</a></li>-->
                                    <li><a href="<?= HTTP_ROOT . "merchants/delete-payment/" . $payment->uniq_id ?>" onclick="return confirm('Are you sure you want to delete?')"><i class="fa fa-angle-right" aria-hidden="true"></i>Delete</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
                <?php if (!$payments->count()) { ?>
                    <tr><td colspan="9" style="text-align: center; color: red;">No Items Found!!</td></tr>
                <?php } ?>
            </tbody>
        </table>

        <?php foreach ($payments as $payment) { ?>
            <!-- Modal Start -->
            <div class="modal" id="myModal<?= $payment->id; ?>" >
                <div class="modal-dialog" style="max-width: 600px;">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header" style="margin-top: -4px;padding-bottom: 3px;">
                            <h3 class="modal-title" style="margin-left: 33%;">View Payment Details</h3>
                            <button type="button" class="close" data-dismiss="modal">×</button>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body" style="height: 65vh;overflow-y: auto;overflow-x: hidden;">
                            <div> 
                                <h4 class="modal-title" style="padding: 0px 0px 0px 3px;color: #1c32ad;">Customer Details</h4>
                                <table align='center' style="width: 100%;">                        
                                    <tr>
                                        <td style="padding: 10px 10px 2px 10px; width: 40%;"><label><?= $webfronts->customer_name_alias; ?></label></td>
                                        <td style="padding: 10px 10px 2px 10px;"> : <label><?= $payment->name; ?></label></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px 10px 2px 10px; width: 40%;"><label><?= $webfronts->customer_email_alias; ?></label></td>
                                        <td style="padding: 10px 10px 2px 10px;"> : <label><?= $payment->email; ?></label></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px 10px 2px 10px; width: 40%;"><label><?= $webfronts->customer_reference_number_alias; ?></label></td>
                                        <td style="padding: 10px 10px 2px 10px;"> : <label><?= $payment->reference_number; ?></label></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px 10px 2px 10px; width: 40%;"><label><?= $webfronts->customer_phone_alias; ?></label></td>
                                        <td style="padding: 10px 10px 2px 10px;"> : <label><?= $payment->phone; ?></label></td>
                                    </tr>
                                </table>

                                <h4 class="modal-title" style="padding: 10px 0px 0px 3px;color: #1c32ad;">Payment Details</h4>
                                <table align='center' style="width: 100%;">
                                    <tr>
                                        <td style="padding: 10px 10px 2px 10px; width: 40%;"><label>Total Fee</label></td>
                                        <td style="padding: 10px 10px 2px 10px;"> : <label>Rs. <?= $payment->fee; ?></label></td>
                                    </tr>  
                                    <tr>
                                        <td style="padding: 10px 10px 2px 10px; width: 40%;"><label>Convenience Fee </label></td>
                                        <td style="padding: 10px 10px 2px 10px;"> : <label>Rs. <?= $payment->convenience_fee_amount; ?></label></td>
                                    </tr>  
                                    <?php if ($payment->status == 0) { ?>
                                        <tr>
                                            <td style="padding: 10px 10px 2px 10px; width: 40%;"><label>Late Fee </label></td>
                                            <td style="padding: 10px 10px 2px 10px;"> : <label>Rs. <?= $payment->late_fee_amount; ?></label></td>
                                        </tr>  
                                    <?php } ?>
                                    <?php if ($payment->status == 1) { ?>
                                        <tr>
                                            <td style="padding: 10px 10px 2px 10px; width: 40%;"><label>Paid Amount</label></td>
                                            <td style="padding: 10px 10px 2px 10px;"> : <label>Rs. <?= $payment->paid_amount; ?></label></td>
                                        </tr>  
                                        <tr>
                                            <td style="padding: 10px 10px 2px 10px; width: 40%;"><label>Payment Date  </label></td>
                                            <td style="padding: 10px 10px 2px 10px;"> : <label><?= $this->Custom->dateDisplay($payment->payment_date); ?></label></td>
                                        </tr> 
                                    <?php } ?>
                                    <tr>
                                        <td style="padding: 10px 10px 2px 10px; width: 40%;"><label>Payment Cycle </label></td>
                                        <td style="padding: 10px 10px 2px 10px;"> : <label><?= $this->Custom->dateDisplay($payment->uploaded_payment_file->payment_cycle_date); ?></label></td>
                                    </tr>  
                                    <tr>
                                        <td style="padding: 10px 10px 2px 10px; width: 40%;"><label>Payment Status </label></td>
                                        <td style="padding: 10px 10px 2px 10px;"> : <label><?= $payment->status == 1 ? 'Paid' : 'Unpaid' ?></label></td>
                                    </tr>  
                                </table>
                            </div>   
                        </div>
                        <!-- Modal footer -->
                        <div class="modal-footer" style="margin-top: 20px;margin-bottom: -7px;">
                            <button type="button" class="button-2" data-dismiss="modal">Close</button>                        
                        </div>                    
                    </div>
                </div>
            </div>
        <?php } ?>
        <!-- Modal End -->
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#select_all').on('click', function () {
            if (this.checked) {
                $('.checkbox').each(function () {
                    this.checked = true;
                });
            } else {
                $('.checkbox').each(function () {
                    this.checked = false;
                });
            }
        });

        $("#select_count").html($("input.checkbox:checked").length + " Selected");

        $(".checkbox").on('click', function (e) {
            $("#select_count").html($("input.checkbox:checked").length + " Selected");
        });

        // delete selected records
        $('#btn_dlt').on('click', function (e) {
            var employee = [];
            $(".checkbox:checked").each(function () {
                employee.push($(this).data('emp-id'));
            });
            if (employee.length <= 0) {
                alert("Please select records.");
            } else {
                DELETE = "Are you sure you want to delete " + (employee.length > 1 ? "these" : "this") + " row?";
                var checked = confirm(DELETE);
                if (checked == true) {
                    $.ajax({
                        type: "POST",
                        url: "merchants/ajaxDeleteSelectedPayments",
                        cache: false,
                        data: {emp_ids: employee},
                        dataType:"json",
                        success: function (response) {
                            if(response.status == 'success'){
                                $(".checkbox:checked").parents('tr').remove();
                            }
                            alert(response.message);
                        }
                    });
                }
            }
        });
    });
</script>
