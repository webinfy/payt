<div class="main-content-section">
    <ul class="dashboard-menu nav nav-tabs">
        <li><a class="active" data-toggle="tab" href="#basicinfo" >Website Content</a></li>
        <li><a data-toggle="tab" href="javascript:;">Customer Fields</a></li>
        <li><a data-toggle="tab" href="javascript:;">Payment Fields</a></li>
        <li><a data-toggle="tab" href="javascript:;">Webfront Logo</a></li>
        <li><a data-toggle="tab" href="javascript:;">Download Sample Excel</a></li>
    </ul>
    <div class="main-content">
        <div class="tab-content">
            <div id="basicinfo" class="tab-pane fade in active">
                <?= $this->element('BasicWebfrontEdit/content_update'); ?>
            </div>
        </div>
    </div>
</div>