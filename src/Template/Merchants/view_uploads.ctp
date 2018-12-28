<div class="main-content-section">
    <div class="main-content payment-list-div">
        <div class="payment-list-top">
            <a style="float: right; margin-right: 0; margin-bottom: 15px;" data-toggle="modal" data-target="#myModal" href="javascript:;" class="btn-blue"><i class="fa fa-list-alt" aria-hidden="true"></i>Upload New Excel</a></li>
        </div>
        <table width="100%" align="left" cellpadding="0" cellspacing="0" class="payment-list-table">
            <thead>
                <tr>
                    <th>Payment Cycle Date </th>
                    <th>Title</th>
                    <th>Customer Count</th>
                    <th>Upload Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($paymentFiles as $paymentFile) { //pj($paymentFile);exit; ?>
                    <tr>
                        <td><?= $this->Custom->dateDisplay($paymentFile->payment_cycle_date); ?></td>
                        <td><?= $paymentFile->title; ?></td>
                        <td><?= $paymentFile->upload_count; ?></td>
                        <td><?= $paymentFile->created; ?></td>
                        <td>
                            <div class="action-btn"><i class="fa fa-th" aria-hidden="true"></i>
                                <ul class="action-btn-list">
                                    <li><a data-toggle="modal" data-target="#appendRecordModal<?= $paymentFile->id; ?>" href="javascript:;"><i class="fa fa-plus" aria-hidden="true"></i>Append Records</a></li>
                                    <li><a href="<?= HTTP_ROOT . "basic-webfront-reports?webfront_id={$paymentFile->webfront_id}&file_id={$paymentFile->id}&due_date=" . date_format($paymentFile->payment_cycle_date, 'Y-m-d'); ?>"><i class="fa fa-money" aria-hidden="true"></i>View Report</a></li>
                                    <li><a data-toggle="modal" data-target="#reuseModal<?= $paymentFile->id; ?>" href="javascript:;"><i class="fa fa-recycle" aria-hidden="true"></i>Reuse</a></li>
                                    <li><a href="<?= HTTP_ROOT . "merchants/delete-uploadfile/" . $paymentFile->id ?>" onclick="return confirm('You are going to delete all the records uploads. Are you sure you want to delete ?')"><i class="fa fa-trash-o" aria-hidden="true"></i>Delete</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
                <?php if (!$paymentFiles->count()) { ?>
                    <tr><td colspan="5" style="text-align: center; color: red;">No Files Uploaded Yet!!</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>


<!-- Upload File Modal Start-->
<div class="modal" id="myModal" >
    <div class="modal-dialog">
        <div class="modal-content">    
            <?= $this->Form->create(NULL, ['class' => 'main-form', 'url' => ['controller' => 'Merchants', 'action' => 'uploadExcel'], 'enctype' => 'multipart/form-data', 'id' => 'uploadFileForm']); ?>
            <div class="modal-header" style="margin-top: -4px;padding-bottom: 3px;">
                <h3 class="modal-title">Upload New Excel</h3>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                
                <div class="alert alert-danger" id="uploadErrors" style="font-size: 12px; display: none;"></div>
                
                <input type="hidden" name="unique_id" value="<?= $webfront->unique_id; ?>" />
                <div class="form-div">
                    <label class="form-label">Title <span class="required-fild">*</span>:</label>
                    <input type="text" name="title" required="required" class="text-box" placeholder="Title" />
                </div>
                <div class="form-div">
                    <label class="form-label">Payment Cycle Date <span class="required-fild">*</span>:</label>
                    <input type="text" id="datepicker" name="payment_cycle_date" required="required" class="text-box" placeholder="Payment Cycle Date" autocomplete="off" />
                </div>
                <div class="form-div">
                    <label class="form-label">Browse File :<br/><span class="required-fild">(Excel or CSV Only) *</span></label>
                    <input type="file" name="file" style="margin-top: 10px;"><br><br>
                </div>
            </div>           
            <div class="modal-footer">
                <?= $this->Form->submit('Close', ['type' => 'button', 'class' => 'button-2', 'data-dismiss' => 'modal']); ?>
                <?= $this->Form->submit('Import', ['type' => 'submit', 'class' => 'button-3']); ?>                
            </div>
            <?= $this->Form->end(); ?>
        </div>
    </div>
</div><!-- Upload File Modal End-->

<?php foreach ($paymentFiles as $paymentFile) { ?>
    <!-- Append Records To Existing File Upload Modal Start-->
    <div class="modal" id="appendRecordModal<?= $paymentFile->id; ?>" >
        <div class="modal-dialog">
            <div class="modal-content">   
                <?= $this->Form->create(NULL, ['class' => 'main-form', 'url' => ['controller' => 'Merchants', 'action' => 'appendRecords'], 'enctype' => 'multipart/form-data']); ?>
                <div class="modal-header" style="margin-top: -4px;padding-bottom: 3px;">
                    <h3 class="modal-title">Append Records</h3>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $paymentFile->id; ?>" />
                    <div class="form-div">
                        <label class="form-label">Title</label>
                        <?= $paymentFile->title; ?>
                    </div>
                    <div class="form-div">
                        <label class="form-label">Payment Cycle Date</label>
                        <?= $this->Custom->dateDisplay($paymentFile->payment_cycle_date); ?>
                    </div>
                    <div class="form-div">
                        <label class="form-label">Browse File :<br/><span class="required-fild">(Excel or CSV Only) *</span></label>
                        <input type="file" name="file" style="margin-top: 10px;"><br><br>
                    </div>
                    <p>Note : You are going to add more customers for this payment cycle.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="button-2" data-dismiss="modal">Close</button>
                    <input type="submit" value="Import" id="cancel-btn" name="cancel-btn" class="button-3">
                </div>
                <?= $this->Form->end(); ?>
            </div>
        </div>
    </div><!-- Append Records To Existing File Upload Modal End-->

    <!-- Reuse Existing File Upload Modal Start-->
    <div class="modal" id="reuseModal<?= $paymentFile->id; ?>" >
        <div class="modal-dialog">
            <div class="modal-content">
                <?= $this->Form->create(NULL, ['class' => 'main-form', 'url' => ['controller' => 'Merchants', 'action' => 'uploadReuse']]); ?>
                <div class="modal-header" style="margin-top: -4px;padding-bottom: 3px;">
                    <h3 class="modal-title">Reuse Upload</h3>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>                
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $paymentFile->id; ?>" />
                    <div class="form-div">  
                        <label class="form-label">Title <span class="required-fild">*</span>:</label>
                        <input type="text" name="title" required="required" class="text-box" placeholder="Title" />
                    </div>
                    <div class="form-div">
                        <label class="form-label">Payment Cycle Date <span class="required-fild">*</span>:</label>
                        <input type="text" name="payment_cycle_date" required="required" class="text-box datepicker" placeholder="Payment Cycle Date" autocomplete="off" />
                    </div>
                    <div class="form-div">
                        <label class="form-label" style="width: 100%;color: red;font-weight: bold;">* Please enter the payment cycle date to reuse this upload. <span class="required-fild"></span></label>
                        <label class="form-label" style="width: 100%;font-size: smaller;">Note :  You are going to reuse the same customers for a new payment cycle.<span class="required-fild"></span></label>
                        <label class="form-label" style="width: 100%;font-size: smaller;">* Please enter the payment cycle date to reuse this upload.<span class="required-fild"></span></label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="button-2" data-dismiss="modal">Close</button>
                    <input type="submit" value="Reuse" id="cancel-btn" name="cancel-btn" class="button-3">
                </div>
                <?= $this->Form->end(); ?>
            </div>
        </div>
    </div><!-- Reuse Existing File Upload Modal End-->
<?php } ?>
    
<script>
    $(function(){

        $("#uploadFileForm").on('submit', function(e){
            e.preventDefault();       

            var formData = new FormData();
            formData.append('file', $(this).find('input[type="file"]')[0].files[0]);
            formData.append('payment_cycle_date', $(this).find('input[name="payment_cycle_date"]').val());
            formData.append('title', $(this).find('input[name="title"]').val());
            formData.append('unique_id', $(this).find('input[name="unique_id"]').val());            
            var submitBtn = $(this).find('input.button-3[type="submit"]').first();
            submitBtn.addClass('loader-submit');
            $.ajax({
                url : siteUrl + 'merchants/upload-excel',
                type : 'POST',
                data : formData,
                processData: false,  // tell jQuery not to process the data
                contentType: false,  // tell jQuery not to set contentType
                success : function(response) {
                    var response = JSON.parse(response);
                    if(response.status == 'success') {
                        submitBtn.removeClass('loader-submit');
                        alert(response.message);
                        location.reload();
                    } else {
                        alert(response.message);
                        displayErrors(response.errors);
                    }                    
                }
            });

        });

    });
    
    function displayErrors(errors) {
        var errorHtml = '<ul>'
        $.each(errors, function(row, item) {
            errorHtml += '<li>';
            errorHtml += '<div><strong class="ng-binding"></strong> [Row : ' + row + ']</div>';
            errorHtml += '<ol style="padding-bottom: 10px; margin-left: 10px;">';
            $.each(item, function(i, msg) {
                errorHtml += '<li class="ng-binding ng-scope">' + msg + '</li>';        
            });    
            errorHtml += '</ol>';     
            errorHtml += '</li>'; 
        });                                           
        errorHtml += '</ul>';
        $("#uploadErrors").html(errorHtml).show();       
    }    
</script>
