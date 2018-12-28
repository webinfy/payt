<div class="main-content-section">
    <div class="main-content payment-list-div">
        <div class="webfront-table">
            <table width="100%" align="left" cellpadding="0" cellspacing="0" class="payment-list-table">
                <thead>
                    <tr>
                        <th>Merchant Name</th>
                        <th><?= $this->Paginator->sort('title', 'Webfront Title'); ?></th>                        
                        <th><?= $this->Paginator->sort('url', 'Webfront URL'); ?></th>                        
                        <th><?= $this->Paginator->sort('created', 'Created'); ?></th>                        
                        <th>Status</th>                    
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($webfronts as $webfront): ?>
                        <tr>
                            <td><?= $webfront->merchant_name ?></td>
                            <td><span title="<?= $webfront->title; ?>"><?= Cake\Utility\Text::truncate($webfront->title, 25,['ellipsis' => '...','exact' => false]); ?></span></td>
                            <td><a href="<?= HTTP_ROOT . $webfront->url ?>" target="_blank"><?= $webfront->url ?></a></td>                            
                            <td><?= date_format($webfront->created, "d M, Y") ?></td>
                            <td><?= ($webfront->is_published == 1) ? 'Published' : 'Un Published' ?></td>                        
                            <td>
                                <div class="action-btn"><i class="fa fa-th" aria-hidden="true"></i>
                                    <ul class="action-btn-list">
                                        <li><a href="<?= HTTP_ROOT . "admin/payments?webfront_id=" . $webfront->id; ?>"><i class="fa fa-money" aria-hidden="true"></i>View Invoices</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
            <?php if ($this->Paginator->numbers()): ?>
                <ul class="pagination-style" style="margin-top: 20px;">
                    <?= $this->Paginator->prev(('previous')) ?>
                    <?= $this->Paginator->numbers() ?>
                    <?= $this->Paginator->next(('next')) ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>