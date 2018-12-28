<div class="main-content-section">
    <div class="main-content payment-list-div">
        <table width="100%" align="left" cellpadding="0" cellspacing="0" class="payment-list-table">
            <thead>
                <tr>
                    <th>Sub-Merchant Name</th>
                    <th>Merchant Name</th>
                    <th>Email</th>
                    <th>PAYUMID</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>                
                <?php foreach ($submerchants as $submerchant): ?>
                    <tr>
                        <td><?= $submerchant->name ?></td>
                        <td><?= $submerchant->merchant_name ?></td>
                        <td><?= $submerchant->email ?></td>
                        <td><?= $submerchant->payumid ?></td>
                        <td><?= date_format($submerchant->created, "d M, Y") ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>