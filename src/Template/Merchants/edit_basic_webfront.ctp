<style>
    /* Added By Mahesh  */
    .profile-pic-form-div {text-align: center;}
    .profile-pic-form-div .form-div {text-align: center;}
    .profile-pic-form-div #file-browser {margin-left: 26.5vw;margin-bottom: 4.5vh;}
    .adv-web-text-box {width: 33.5%;}
    .adv-web-select-type {width: 11.7%;min-width: 100px; margin-left: 8px;margin-right: 4px;}
    .adv-web-select-validation {width: 14.4%;margin-left: 8px;margin-right: 4px;}
    .dynamic-div{display: none;}
    .dynamictext {display: block;}
    .mybutton {display: block;margin-left: 160px;}
    .dynamic-text-del-btn {margin-left: 8px;}
    .payment-field-mandatory {margin-top: 6px;float: left;margin-left: 1vw;}
    .border-coloring {border: 1px solid #cdd8e3;padding: 15px;height: 265px;}
    .main-content {padding: 20px;}
    .making-bold {font-weight: bold;margin-bottom: 20px;}
    .fa-chevron-right {font-size: 12px;}
    .dotted-bordering {border: 1px dotted #cdd8e3;height: 230px;padding: 15px;}
    .making-block {display: block;}
    .fa-pencil-square-o{font-size: 30px;margin-top: 4px;}
    .fa-trash-o{font-size: 30px;margin-top: 2px;}
    .fa-inr{margin-left: 1vw;}    
</style>
<div class="main-content-section">
    <ul class="dashboard-menu nav nav-tabs">
        <li><a <?= ($tab == 'basicinfo') ? 'class= "active"' : '' ?> data-toggle="tab" href="#basicinfo" >Website Content</a></li>
        <li><a <?= ($tab == 'payuinfo') ? 'class= "active"' : '' ?>data-toggle="tab" href="#payuinfo">Customer Fields</a></li>
        <li><a <?= ($tab == 'websitesocial') ? 'class= "active"' : '' ?>data-toggle="tab" href="#websitesocial">Payment Fields</a></li>
        <li><a <?= ($tab == 'webfrontlogo') ? 'class= "active"' : '' ?>data-toggle="tab" href="#webfrontlogo">Webfront Logo</a></li>
        <li><a <?= ($tab == 'profilepicture') ? 'class= "active"' : '' ?>data-toggle="tab" href="#profilepicture">Download Sample Excel</a></li>
    </ul>
    <div class="main-content">
        <div class="tab-content">
            <div id="basicinfo" class="tab-pane fade <?= ($tab == 'basicinfo') ? 'active show' : '' ?>">
                <?= $this->element('BasicWebfrontEdit/content_update'); ?>
            </div>            
            <?= $this->element('BasicWebfrontEdit/customer_fields'); ?>
            <?= $this->element('BasicWebfrontEdit/payment_fields'); ?>
            <?= $this->element('BasicWebfrontEdit/logo_update'); ?>
            <?= $this->element('BasicWebfrontEdit/download_excel'); ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        if (window.location.href.indexOf('?') > -1) {
            history.pushState('', document.title, window.location.pathname);
        }

        $('.prev-btn').click(function () {
            $('.nav-tabs > li > .active').parent().prev('li').find('a').trigger('click');
        });
    });


    $(function () {

        // During page load check all checkboxes if corresponding input field contain some value.
        $('.cust_fields, .payment_field').each(function (e) {
            var checkBox = $(this).closest('div.form-div').find('input[type="checkbox"]');
            if ($(this).val()) {
                checkBox.prop('checked', true);
            } else {
                checkBox.prop('checked', false);
            }
        });

        // Tick checkbox if corresponding input field value contain some value.
        $('.cust_fields, .payment_field').on('keyup keypress blur change', function (e) {
            var checkBox = $(this).closest('div.form-div').find('input[type="checkbox"]');
            if ($(this).val()) {
                checkBox.prop('checked', true);
            } else {
                checkBox.prop('checked', false);
            }
        });

        // Clear input value if corresponding checkbox is unchecked.
        $('.adv-web-check-box').on('change', function (e) {
            var inputBox = $(this).closest('div.form-div').find('input[type="text"]');
            if (!$(this).is(':checked')) {
                inputBox.val('');
            }
        });
    });
</script>