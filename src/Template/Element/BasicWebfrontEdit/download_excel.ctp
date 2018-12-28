<div id="profilepicture" class="tab-pane fade <?= ($tab == 'profilepicture') ? 'active show' : '' ?>">
    <p class="tab-text-center"><a href="<?= HTTP_ROOT . $webfront->url; ?>" target="_blank" class="tab-cont-link"><i class="fa fa-eye" aria-hidden="true"></i> View Webfront</a></p>
    <p class="tab-text-center"><a href="<?= HTTP_ROOT . "merchants/download-sample-excel/{$webfront->id}" ?>" class="tab-cont-link"><i class="fa fa-download" aria-hidden="true"></i> Download Sample Excel</a></p>
    <p class="tab-text-center"><a href="<?= HTTP_ROOT . "merchants/view-uploads/{$webfront->unique_id}" ?>" class="tab-cont-link" ><i class="fa fa-upload" aria-hidden="true"></i> Upload Excel</a></p>
</div>