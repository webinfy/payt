<div class="main-content-section">
    <ul class="dashboard-menu nav nav-tabs">
        <li><a class="active" data-toggle="tab" href="#websitecontent" >Website Content</a></li>
        <li><a href="javascript:;">Webfront Logo</a></li>
        <li><a href="javascript:;">Customer Fields</a></li>
        <li><a href="javascript:;">Payment Fields</a></li>
    </ul>
    <div class="main-content">
        <div class="tab-content">
            <div id="websitecontent" class="tab-pane fade in active">
                <?= $this->element('AdvanceWebfrontEdit/content_update'); ?>
            </div>
        </div>
    </div> 
</div>
<script>
    $(document).ready(function () {
        $('.next-btn').click(function () {
            $('.nav-tabs > li > .active').parent().next('li').find('a').trigger('click');
        });

        $('.prev-btn').click(function () {
            $('.nav-tabs > li > .active').parent().prev('li').find('a').trigger('click');
        });
    });
</script>