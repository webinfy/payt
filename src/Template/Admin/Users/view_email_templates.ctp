<style>
    .name-row:first-child { padding-left: 20px; }
</style>
<div class="main-content-section">
    <div class="main-content payment-list-div">
        <div class="view-email">
            <table width="100%" align="left" cellpadding="0" cellspacing="0" class="payment-list-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($templates as $template) { ?>
                        <tr>
                            <td><?= $template->name ?></td>
                            <td><?= ($template->is_active == 1) ? '<a href="' . HTTP_ROOT . 'admin/update-template-status/' . $template->id . '" class="paid">Enabled</a>' : '<a href="' . HTTP_ROOT . 'admin/update-template-status/' . $template->id . '" class="un-paid">Disabled</a>' ?></td>
                            <td><a href="<?= HTTP_ROOT ?>admin/edit-template/<?= $template->unique_id ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a> </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>