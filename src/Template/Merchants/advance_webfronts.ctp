<style>
    .for-adv-web-pg { float: right;margin-bottom: 10px; }
    .modal-body .img-div { width: 200px;display: block; }
    .modal-body .button-2 > a { color: #fff; }
    .img-div { text-align: center;margin: 0 auto; }
    .qr-lower-div { text-align: center;margin: 0 auto; }
    .qr-lower-div .button-2{ width: 35%;display: inline; }
    .qr-lower-div .button-3 { margin-right: 0px;width: 35%;display: inline; }
    .url-modal-body .btn-blue { padding: 8.5px 15px; }
    /*    .tooltip { position: relative;display: inline-block; }
        .tooltip .tooltiptext { visibility: hidden;width: 140px;background-color: #555;color: #fff;text-align: center;border-radius: 6px;padding: 5px;position: absolute;z-index: 1;bottom: 150%;left: 50%;margin-left: -75px;opacity: 0;transition: opacity 0.3s; }
        .tooltip .tooltiptext::after { content: "";position: absolute;top: 100%;left: 50%;margin-left: -5px;border-width: 5px;border-style: solid;border-color: #555 transparent transparent transparent; }
        .tooltip:hover .tooltiptext { visibility: visible;opacity: 1; }*/
</style>
<div class="main-content-section">
    <div class="main-content payment-list-div">
        <div class="payment-list-top">
            <a href="merchants/create-advance-webfront" class="btn-blue for-adv-web-pg" style="margin-right: 0;"><i class="fa fa-plus" aria-hidden="true"></i>Create New Advance Webfront</a>
        </div>
        <div class="webfront-content">
            <table width="100%" align="left" cellpadding="0" cellspacing="0" class="payment-list-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>URL</th>     
                        <th>Created</th>
                        <th>Status</th>                    
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($webfronts as $webfront) { ?>
                        <tr>
                            <td><span title="<?= $webfront->title; ?>"><?= Cake\Utility\Text::truncate($webfront->title, 25,['ellipsis' => '...','exact' => false]); ?></span></td>
                            <td><a href="<?= HTTP_ROOT . $webfront->url; ?>" target="_blank"><?= $webfront->url; ?></a></td>                        
                            <td><?= date_format($webfront->created, 'd M, Y'); ?></a></td>
                            <td>
                                <?php if ($webfront->is_published == 0) { ?>
                                    <a class="un-paid" onclick="customConfirm('You want to Unpublished.', '<?= HTTP_ROOT . 'merchants/publish/' . $webfront->id ?>');" href="javascript:;" style="width: 110px;">Un-Published</a>
                                <?php } else { ?>
                                    <a class="paid" onclick="customConfirm('You want to Unpublished.', '<?= HTTP_ROOT . 'merchants/unpublish/' . $webfront->id ?>');" href="javascript:;" style="width: 110px;">Published</a>
                                <?php } ?>
                            </td>                        
                            <td>
                                <div class="action-btn"><i class="fa fa-th" aria-hidden="true"></i>
                                    <ul class="action-btn-list">
                                        <li><a href="<?= HTTP_ROOT . "advance-webfront-reports?webfront_id=" . $webfront->id ?>"><i class="fa fa-money" aria-hidden="true"></i>View Payments</a></li>
                                        <li><a href="merchants/webfront-email-templates/<?= $webfront->unique_id ?>"><i class="fa fa-mail-reply-all" aria-hidden="true"></i>Email Templates</a></li>
                                        <li><a data-toggle="modal" data-target="#modalForQr<?= $webfront->id ?>" href="javascript:;"><i class="fa fa-qrcode" aria-hidden="true"></i>QR</a></li>
                                        <li><a data-toggle="modal" data-target="#modalForShortUrl<?= $webfront->id ?>" href="javascript:;"><i class="fa fa-link" aria-hidden="true"></i>Short URL</a></li>
                                        <li><a href="merchants/edit-advance-webfront/<?= $webfront->unique_id ?>"><i class="fa fa-edit" aria-hidden="true"></i>Edit</a></li>
                                        <li><a onclick="customConfirm('You want to delete.', '<?= HTTP_ROOT . 'merchants/deleteAdvanceWebfront/' . $webfront->unique_id ?>');" href="javascript:;"><i class="fa fa-trash-o" aria-hidden="true"></i>Delete</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>

                    <?php if ($webfronts->count() == 0) { ?>
                        <tr><td colspan="6" style="text-align: center; color: red">No Webfront Found!!</td></tr>
                    <?php } ?>                

                </tbody>
            </table>
        </div>
    </div>
    <?php if ($this->Paginator->numbers()): ?>
        <ul class="pagination-style">
            <?= $this->Paginator->prev(('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(('next')) ?>
        </ul>
    <?php endif; ?>
</div>
<?php foreach ($webfronts as $webfront) { ?>
    <!-- The Modal -->
    <div class="modal" id="modalForQr<?= $webfront->id ?>" >
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">QR Code</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="img-div">
                        <img src="<?= HTTP_ROOT . QR . $webfront->qr; ?>">
                        <div>
                            <span>Pay With &nbsp;</span><img style="margin-top: -5px;" src="<?= HTTP_ROOT ?>images/small-logo.png">
                        </div>
                        <div>
                            <span style="margin: 11px 0; font-weight: 600"><?= $webfront->title ?></span>
                        </div>
                    </div>
                    <div class="qr-lower-div">
                        <div style="margin: 10px 0;">
                            <a class="button-2" href="<?= HTTP_ROOT . 'webfronts/downloadQrCode/' . $webfront->id ?>">Download</a>
                            <button class="button-3">Print</button>
                        </div>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- The Modal -->
    <div class="modal" id="modalForShortUrl<?= $webfront->id ?>" >
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Short URL</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body url-modal-body">
                    <input type="text" value="<?= $webfront->short_url ?>" disabled="disabled" class="text-box">
                    <button class="btn-blue for-copy">Copy</button> <br/><br/><br/>
                    <div style="width: 200px; margin: 0 auto;">
                        Share Link Via :  
                        <a href="whatsapp://send?text=<?= $webfront->short_url ?>" data-action="share/whatsapp/share"><img src="images/whatsapp-share.gif" style="width: 25px;" /></a> &nbsp;
                        <a href="mailto:?subject=Share Webfront URL&body=<?= $webfront->short_url ?>"><img src="images/email-share.png" style="width: 25px;" /></a>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
<?php } ?>
<script>
    $(document).ready(function () {

        $('.for-copy').on('click', function () {

            var copyUrl = $(this).prev();
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(copyUrl.val()).select();
            document.execCommand("copy");
            $temp.remove();

        });

        $('.button-3').on('click', function () {
            var imgHtml = $(this).parents('.modal-body').find('.img-div').html();
            var div = '<div style="text-align: center;">' + imgHtml + '</div>';
            popup = window.open();
            popup.document.write(div);
            popup.focus(); //required for IE
            popup.print();
            popup.close();
        });

    });
</script>