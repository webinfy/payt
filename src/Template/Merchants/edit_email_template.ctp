<style>
    .textarea-box { height: 400px; }
   
</style>
<div class="main-content-section">
    <!--<div class="main-content payment-list-div">-->
    <div class="main-content">
        <?= $this->Form->create($template, [ 'id' => 'template-form']) ?>

        <div class="form-div">
            <label class="form-label">Subject <span class="required-fild">*</span>:</label>
            <?= $this->Form->control('subject', ['placeholder' => 'Subject', 'class' => 'text-box', 'label' => false]); ?>
        </div>
        <div class="form-div" id="editor">
            <label class="form-label">Content <span class="required-fild">*</span>:</label>
            <div class="form-div editor-box">
                <?= $this->Form->textarea('content', ['placeholder' => 'Content', 'class' => 'textarea-box', 'label' => false, 'rows' => '25']); ?>
            </div>
            <div class="form-div">
                <input type="submit" value="Update" id="update-btn" name="update-btn" class="button-2 padding-left140">
                <a href="admin/view-email-templates" id="cancel-btn" class="button-3">Cancel</a>
            </div>

            <?= $this->Form->end() ?>
        </div>
    </div>
    <script src="https://cdn.ckeditor.com/4.10.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('content');
    </script>