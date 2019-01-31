<div class="main-content-section">
    <div class="main-content payment-list-div">
        <div class="merchant-table">
            <table width="100%" align="left" cellpadding="0" cellspacing="0" class="payment-list-table">
                <thead>
                    <tr>
                        <th><?= $this->Paginator->sort('name', 'Name'); ?></th>
                        <th><?= $this->Paginator->sort('email', 'Email'); ?></th>
                        <th><?= $this->Paginator->sort('created', 'Created'); ?></th>                       
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($merchants->count()) {
                        foreach ($merchants as $merchant) {
                            ?>
                            <tr>
                                <td><?= $merchant->name ?></td>
                                <td><?= $merchant->email ?></td>                                
                                <td><?= date_format($merchant->created, "d M Y") ?></td>
                                <?php if ($merchant->is_active != 0) { ?>
                                    <td><a class="paid" onclick="customConfirm('You want to Inactivate.', '<?= HTTP_ROOT . 'admin/update-status/' . $merchant->uniq_id ?>');" href="javascript:;">Active</a></td>
                                <?php } else { ?>
                                    <td><a class="un-paid" onclick="customConfirm('You want to Activate.', '<?= HTTP_ROOT . 'admin/update-status/' . $merchant->uniq_id ?>');" href="javascript:;">Inactive</a></td>
                                <?php } ?>
                                <td>
                                    <div class="action-btn"><i class="fa fa-th" aria-hidden="true"></i>
                                        <ul class="action-btn-list">
                                            <!--<li><a data-toggle="modal" data-target="#myModal<?= $merchant->id; ?>" href="javascript:;"><i class="fa fa-angle-right" aria-hidden="true"></i>View</a></li>-->
                                            <li><a href="admin/view-merchant-profile/<?= $merchant->uniq_id ?>" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i>View</a></li>
                                            <li><a href="admin/edit-merchant/<?= $merchant->uniq_id ?>"><i class="fa fa-edit" aria-hidden="true"></i>Edit</a></li>
                                            <li><a onclick="customConfirm('You want delete the merchant.', '<?= HTTP_ROOT . 'admin/delete-merchant/' . $merchant->uniq_id ?>');" href="javascript:;" class="customalert"><i class="fa fa-trash-o" aria-hidden="true"></i>Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>

                            <?php
                        }
                    } else {
                        ?>
                        <tr><td colspan="5" style="text-align: center; color: red;">No Merchant Found!!</td></tr>
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