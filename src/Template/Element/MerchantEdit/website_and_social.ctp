<!-- WebsiteAndSocial Tab Start-->
<div id="WebsiteAndSocial" class="tab-pane fade">
    <?= $this->Form->create($merchant, ['class' => 'form-horizontal', 'id' => 'validateWebsite']); ?>
    <?= $this->Form->control('action', ['type' => 'hidden', 'value' => 'WebsiteAndSocial']); ?>
    <div class="form-div">
        <label class="form-label">Website <span class="required-fild">*</span>:</label>
        <?= $this->Form->control('merchant.website', ['placeholder' => 'Website', 'label' => false, 'class' => 'text-box']); ?>
    </div>
    <div class="form-div">
        <label class="form-label">Facebook Url <span class="required-fild">*</span>:</label>
        <?= $this->Form->control('merchant.facebook_url', ['placeholder' => 'Facebook', 'label' => false, 'class' => 'text-box']); ?>
    </div>
    <div class="form-div">
        <label class="form-label">Twitter Url <span class="required-fild">*</span>:</label>
        <?= $this->Form->control('merchant.twitter_url', ['placeholder' => 'Twiter', 'label' => false, 'class' => 'text-box']); ?>
    </div>
    <div class="form-div">
        <input type="submit" value="Update" class="button-2 padding-left140">
        <a class="button-3" href="<?= HTTP_ROOT; ?>merchants">Cancel </a>
    </div>
    <?= $this->Form->end(); ?>   
</div><!-- WebsiteAndSocial Tab End-->
