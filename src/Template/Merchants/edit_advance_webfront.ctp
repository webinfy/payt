<style>
    /* Added By Mahesh  */
    .profile-pic-form-div {text-align: center;}
    .profile-pic-form-div .form-div {text-align: center;}
    .profile-pic-form-div #file-browser {margin-left: 26.5vw;margin-bottom: 4.5vh;}
    .adv-web-text-box {width: 33.5%;}
    .adv-web-select-type {width: 11.7%;min-width: 100px; margin-left: 8px;margin-right: 4px;}
    .adv-web-select-validation {width: 14.4%;margin-left: 8px;margin-right: 4px;}
    .choices-container{display: block; margin-top: 15px;}
    .dynamictext {display: block;}
    .mybutton {display: block;margin-left: 160px;}
    .dynamic-text-del-btn {margin-left: 8px;}
    .payment-field-mandatory {margin-top: 6px;float: left;margin-left: 1vw;}
    .border-coloring {border: 1px solid #cdd8e3;padding: 15px;height: 280px;}
    .main-content {padding: 20px;}
    .making-bold {font-weight: bold;margin-bottom: 20px;}
    .fa-chevron-right {font-size: 12px;}
    .dotted-bordering {border: 1px dotted #cdd8e3;height: 245px;padding: 15px;}
    .making-block {display: block;}
    .fa-pencil-square-o{font-size: 30px;margin-top: 4px;cursor: pointer;}
    .fa-trash-o{font-size: 30px;margin-top: 2px;}
    .fa-inr{margin-left: 1vw;}
    .for-amount-input{width: 25%;float: right;margin-top: 27px;margin-right: 100px;}
    .for-amount-div {display: none;}
    .to-be-hidden {display: none;}
    .browse-btn { position: relative; }
    .browse-btn span { position: absolute;margin-left: -117px;width: 223px }
    .browse-btn .text-file { position: absolute;padding: 0;overflow: hidden;margin-left: -4.5vw;display: none; }
</style>
<div class="main-content-section">
    <ul class="dashboard-menu nav nav-tabs">
        <li><a <?= ($tab == 'websitecontent') ? "class='active'" : "" ?> data-toggle="tab" href="#websitecontent" >Website Content</a></li>
        <li><a <?= ($tab == 'profilepicture') ? "class='active'" : "" ?> data-toggle="tab" href="#profilepicture">Webfront Logo </a></li>
        <li><a <?= ($tab == 'customerfields') ? "class='active'" : "" ?> data-toggle="tab" href="#customerfields">Customer Fields</a></li>
        <li><a <?= ($tab == 'paymentfields') ? "class='active'" : "" ?> data-toggle="tab" href="#paymentfields">Payment Fields</a></li>
    </ul>
    <div class="main-content">
        <div class="tab-content">           
            <div id="websitecontent" class="tab-pane fade <?= ($tab == 'websitecontent') ? "active show" : "" ?>">          
                <?= $this->element('AdvanceWebfrontEdit/content_update') ?>
            </div>
            <?= $this->element('AdvanceWebfrontEdit/logo_update') ?>
            <?= $this->element('AdvanceWebfrontEdit/customer_fields') ?>
            <?= $this->element('AdvanceWebfrontEdit/payment_fields') ?> 
        </div>
    </div> 
</div>

<script>
    $(document).ready(function () {
        
        if (window.location.href.indexOf('?') > -1) {
            history.pushState('', document.title, window.location.pathname);
        }
        
        $('.prev-btn').click(function(){
            $('.nav-tabs > li > .active').parent().prev('li').find('a').trigger('click');
        });   
        
//        $('.next-btn').click(function () {
//            $('.nav-tabs > li > .active').parent().next('li').find('a').trigger('click');
//        });        
    });
</script>