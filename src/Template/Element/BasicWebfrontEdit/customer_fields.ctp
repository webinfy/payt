<div id="payuinfo" class="tab-pane fade <?= ($tab == 'payuinfo') ? 'active show' : '' ?>">
    <?= $this->Form->create($webfront, ['id' => 'websiteContent', 'class' => 'main-form']); ?>
    <div class="form-div">
        <p style="color: red;">All (*) fields are mandatory</p> 
    </div>
    <div class="form-div">
        <label class="form-label">Name<span class="required-fild">*</span>:</label>
        <?= $this->Form->control('customer_name_alias', ['placeholder' => 'Name', 'class' => 'text-box', 'label' => FALSE]); ?>
    </div>
    <div class="form-div">
        <label class="form-label">Reference Number <span class="required-fild">*</span>:</label>
        <?= $this->Form->control('customer_reference_number_alias', ['placeholder' => 'Reference Number', 'class' => 'text-box', 'label' => FALSE]); ?>
    </div>
    <div class="form-div">
        <label class="form-label">Email :</label> 
        <?= $this->Form->control('customer_email_alias', ['placeholder' => 'Email', 'class' => 'text-box', 'label' => FALSE]); ?>    
    </div>
    <div class="form-div">
        <label class="form-label">Phone No. :</label>
        <?= $this->Form->control('customer_phone_alias', ['placeholder' => 'Phone No.', 'class' => 'text-box', 'label' => FALSE]); ?> 
    </div>
    <div class="form-div">
        <label class="form-label">Note :</label>   
        <?= $this->Form->control('customer_note_alias', ['placeholder' => 'Note.', 'class' => 'text-box', 'label' => FALSE]); ?>
    </div>
    <div class="form-div">
        <label class="form-label">CA1 :</label>
        <?= $this->Form->control('webfront_fields.ca1', ['placeholder' => 'Default Label', 'class' => 'text-box cust_fields', 'label' => FALSE]); ?>    
        <label>
            <?= $this->Form->checkbox('form-field-checkbox', ['id' => 'ca1_chk', 'class' => 'check-box adv-web-check-box', 'label' => FALSE]); ?>
        </label>
    </div>
    <div class="form-div">
        <label class="form-label">CA2 :</label>
        <?= $this->Form->control('webfront_fields.ca2', ['placeholder' => 'Default Label', 'class' => 'text-box cust_fields', 'label' => FALSE]); ?>                         <label>
            <?= $this->Form->checkbox('form-field-checkbox', ['id' => 'ca2_chk', 'class' => 'check-box adv-web-check-box', 'label' => FALSE]); ?>
        </label>
    </div>
    <div class="form-div">
        <label class="form-label">CA3 :</label>
        <?= $this->Form->control('webfront_fields.ca3', ['placeholder' => 'Default Label', 'class' => 'text-box cust_fields', 'label' => FALSE]); ?>        
        <label>
            <?= $this->Form->checkbox('form-field-checkbox', ['id' => 'ca3_chk', 'class' => 'check-box adv-web-check-box', 'label' => FALSE]); ?>
        </label>
    </div>
    <div class="form-div">
        <label class="form-label">CA4 :</label>
        <?= $this->Form->control('webfront_fields.ca4', ['placeholder' => 'Default Label', 'class' => 'text-box cust_fields', 'label' => FALSE]); ?>         
        <label>
            <?= $this->Form->checkbox('form-field-checkbox', ['id' => 'ca4_chk', 'class' => 'check-box adv-web-check-box', 'label' => FALSE]); ?>
        </label>
    </div>
    <div class="form-div">
        <label class="form-label">CA5 :</label>
        <?= $this->Form->control('webfront_fields.ca5', ['placeholder' => 'Default Label', 'class' => 'text-box cust_fields', 'label' => FALSE]); ?>         
        <label>
            <?= $this->Form->checkbox('form-field-checkbox', ['id' => 'ca5_chk', 'class' => 'check-box adv-web-check-box', 'label' => FALSE]); ?>
        </label>
    </div>
    <div class="form-div">
        <label class="form-label">CA6 :</label>
        <?= $this->Form->control('webfront_fields.ca6', ['placeholder' => 'Default Label', 'class' => 'text-box cust_fields', 'label' => FALSE]); ?>     
        <label>
            <?= $this->Form->checkbox('form-field-checkbox', ['id' => 'ca6_chk', 'class' => 'check-box adv-web-check-box', 'label' => FALSE]); ?>
        </label>
    </div>
    <div class="form-div">
        <a href="javascript:;" class="button-2 padding-left140 prev-btn">Back</a>
        <input type="submit" value="Next" id="cancel-btn" name="cancel-btn" class="button-3 next-btn">
    </div>
    <?= $this->Form->end(); ?>
</div>