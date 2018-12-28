<style>
    .modal-body tr:nth-child(2n+1) {
        background: #f1f1f1;
        line-height: 16px;
    }
    .edit-payment tr:nth-child(2n+1) {
        background: #FFF !important;
    }
</style>
<div class="main-content-section">
    <div class="main-content payment-list-div">   
        <div class="user-box">
            <table width="100%" align="left" cellpadding="0" cellspacing="0" class="payment-list-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status </th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) { ?>
                        <tr>
                            <td><?= $user->name; ?></td>
                            <td><?= $user->email; ?></td>
                            <td><?= $user->phone; ?></td>
                            <td>
                                <?php if ($user->is_active == 0) { ?>
                                    <a class="un-paid" href="users/activate/<?= $user->uniq_id ?>" style="width: 110px;">Inactivate</a>
                                <?php } else { ?>
                                    <a class="paid" href="users/inactivate/<?= $user->uniq_id ?>" style="width: 110px;">Activate</a>
                                <?php } ?>
                            </td>
                            <td>
                                <div class="action-btn"><i class="fa fa-th" aria-hidden="true"></i>
                                    <ul class="action-btn-list">
                                        <li><a data-toggle="modal" data-target="#myModal<?= $user->id; ?>" href="javascript:;"><i class="fa fa-eye" aria-hidden="true"></i>View</a></li>
                                        <li><a href="<?= HTTP_ROOT . "users/editUser/" . $user->uniq_id ?>"><i class="fa fa-edit" aria-hidden="true"></i>Edit</a></li>
                                        <li><a href="<?= HTTP_ROOT . "users/deleteUser/" . $user->uniq_id ?>" onclick="return confirm('Are you sure want to delete ?')" ><i class="fa fa-trash-o" aria-hidden="true"></i>Delete</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>

                    <?php if (!$users->count()) { ?>
                        <tr><td colspan="5" style="text-align: center; color: red;">No Users Found!!</td></tr>
                    <?php } ?>

                </tbody>
            </table>
        </div>
        <?php foreach ($users as $user) { ?>
            <!-- Modal Start -->
            <div class="modal" id="myModal<?= $user->id; ?>" >
                <div class="modal-dialog" style="max-width: 600px;">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header" style="margin-top: -4px;padding-bottom: 3px;">
                            <h3 class="modal-title">View User Details [<?= $user->name; ?>]</h3>
                            <button type="button" class="close" data-dismiss="modal">×</button>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body">
                            <div> 
                                <table align='center' style="width: 100%;">                        
                                    <tr>
                                        <td style="padding: 10px 10px 2px 10px; width: 40%;"><label>Name</label></td>
                                        <td style="padding: 10px 10px 2px 10px;"> : <label><?= $user->name; ?></label></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px 10px 2px 10px; width: 40%;"><label>Email</label></td>
                                        <td style="padding: 10px 10px 2px 10px;"> : <label><?= $user->email; ?></label></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px 10px 2px 10px; width: 40%;"><label>Phone Number</label></td>
                                        <td style="padding: 10px 10px 2px 10px;"> : <label><?= $user->employee->phone; ?></label></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px 10px 2px 10px; width: 40%;"><label>Access</label></td>
                                        <td style="padding: 10px 10px 2px 10px;"> : <label><?= ($user->access == 2) ? "Full Access" : "View Only"; ?></label></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px 10px 2px 10px; width: 40%;"><label>Created</label></td>
                                        <td style="padding: 10px 10px 2px 10px;"> : <label><?= $user->created; ?></label></td>
                                    </tr>
                                </table>
                            </div>   
                        </div>
                        <!-- Modal footer -->
                        <div class="modal-footer" style="margin-top: 20px;margin-bottom: -7px;">
                            <button type="button" class="button-2" data-dismiss="modal">Close</button>                        
                        </div>                    
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>