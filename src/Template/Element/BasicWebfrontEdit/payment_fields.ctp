<div id="websitesocial" class="tab-pane fade <?= ($tab == 'websitesocial') ? 'active show' : '' ?>">
    <?= $this->Form->create($webfront, ['class' => 'form-horizontal']); ?>
    <div class="form-div">
        <p style="color: red;">All (*) fields are mandatory</p> 
    </div>
    <div class="form-div">
        <label class="form-label">Total Amount<span class="required-fild">*</span>:</label>
        <?= $this->Form->control('total_amount_alias', ['type' => 'text', 'id' => 'total_amount', 'placeholder' => 'Total Amount', 'class' => 'text-box', 'label' => FALSE]); ?>
    </div>
    <div class="form-div">
        <label class="form-label">PA1 <span class="required-fild">*</span>:</label>
        <?= $this->Form->control('payment_fields.pa1', ['placeholder' => 'PA1', 'class' => 'text-box payment_field', 'label' => FALSE]); ?>
        <label><?= $this->Form->checkbox('form-field-checkbox', ['id' => 'pa1_chk', 'class' => 'check-box adv-web-check-box', 'label' => FALSE]); ?></label>
    </div>
    <div class="form-div">
        <label class="form-label">PA2 </label> 
        <?= $this->Form->control('payment_fields.pa2', ['placeholder' => 'PA2', 'class' => 'text-box payment_field', 'label' => FALSE]); ?>  
        <label><?= $this->Form->checkbox('form-field-checkbox', ['id' => 'pa2_chk', 'class' => 'check-box adv-web-check-box', 'label' => FALSE]); ?></label>
    </div>
    <div class="form-div">
        <label class="form-label">PA3</label>
        <?= $this->Form->control('payment_fields.pa3', ['placeholder' => 'PA3', 'class' => 'text-box payment_field', 'label' => FALSE]); ?> 
        <label><?= $this->Form->checkbox('form-field-checkbox', ['id' => 'pa3_chk', 'class' => 'check-box adv-web-check-box', 'label' => FALSE]); ?></label>
    </div>
    <div class="form-div">
        <label class="form-label">PA4</label>   
        <?= $this->Form->control('payment_fields.pa4', ['placeholder' => 'PA4', 'class' => 'text-box payment_field', 'label' => FALSE]); ?>
        <label><?= $this->Form->checkbox('form-field-checkbox', ['id' => 'pa4_chk', 'class' => 'check-box adv-web-check-box', 'label' => FALSE]); ?></label>
    </div>
    <div class="form-div">
        <label class="form-label">PA5</label>
        <?= $this->Form->control('payment_fields.pa5', ['placeholder' => 'PA5', 'class' => 'text-box payment_field', 'label' => FALSE]); ?>    
        <label><?= $this->Form->checkbox('form-field-checkbox', ['id' => 'pa5_chk', 'class' => 'check-box adv-web-check-box', 'label' => FALSE]); ?></label>
    </div>
    <div class="form-div">
        <label class="form-label">PA6</label>
        <?= $this->Form->control('payment_fields.pa6', ['placeholder' => 'PA6', 'class' => 'text-box payment_field', 'label' => FALSE]); ?>           
        <label><?= $this->Form->checkbox('form-field-checkbox', ['id' => 'pa6_chk', 'class' => 'check-box adv-web-check-box', 'label' => FALSE]); ?></label>
    </div>
    <div class="form-div">
        <label class="form-label">PA7</label>
        <?= $this->Form->control('payment_fields.pa7', ['placeholder' => 'PA7', 'class' => 'text-box payment_field', 'label' => FALSE]); ?>        
        <label><?= $this->Form->checkbox('form-field-checkbox', ['id' => 'pa7_chk', 'class' => 'check-box adv-web-check-box', 'label' => FALSE]); ?></label>
    </div>
    <div class="form-div">
        <label class="form-label">PA8</label>
        <?= $this->Form->control('payment_fields.pa8', ['placeholder' => 'PA8', 'class' => 'text-box payment_field', 'label' => FALSE]); ?>         
        <label><?= $this->Form->checkbox('form-field-checkbox', ['id' => 'pa8_chk', 'class' => 'check-box adv-web-check-box', 'label' => FALSE]); ?></label>
    </div>
    <div class="form-div">
        <label class="form-label">PA9</label>
        <?= $this->Form->control('payment_fields.pa9', ['placeholder' => 'PA9', 'class' => 'text-box payment_field', 'label' => FALSE]); ?>         
        <label><?= $this->Form->checkbox('form-field-checkbox', ['id' => 'pa9_chk', 'class' => 'check-box adv-web-check-box', 'label' => FALSE]); ?></label>
    </div>
    <div class="form-div">
        <label class="form-label">PA10</label>
        <?= $this->Form->control('payment_fields.pa10', ['placeholder' => 'PA10', 'class' => 'text-box payment_field', 'label' => FALSE]); ?>   
        <label><?= $this->Form->checkbox('form-field-checkbox', ['id' => 'pa10_chk', 'class' => 'check-box adv-web-check-box', 'label' => FALSE]); ?></label>
    </div>
    <div class="form-div">
        <a href="javascript:;" class="button-2 padding-left140 prev-btn">Back</a>
        <input type="submit" value="Next" id="cancel-btn" name="cancel-btn" class="button-3 next-btn">
    </div>
    <?= $this->Form->end(); ?>
</div>